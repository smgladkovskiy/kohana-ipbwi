<?php
	/* replace core wordpress functions for compatibility */

	// get password when new user registers and create board account
	if(!function_exists('wp_new_user_notification')){
		function wp_new_user_notification($user_id, $plaintext_pass = '') {

			$user = new WP_User($user_id);

			$user_login = stripslashes($user->user_login);
			$user_email = stripslashes($user->user_email);

			// yeah, it's really only this line code for adding a board user with same login datas
			$GLOBALS['ipbwi']->member->create($user_login,$plaintext_pass,$user_email,false,($GLOBALS['ipbwi']->getBoardVar('reg_auth_type') ? true : false),$user_login,true);
			if($GLOBALS['ipbwi']->printSystemMessages()){
				add_action('admin_notices', 'ipbwi_warning');
			}

			$message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
			$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

			@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);

			if ( empty($plaintext_pass) )
				return;

			$message  = sprintf(__('Username: %s'), $user_login) . "\r\n";
			$message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n";
			$message .= site_url("wp-login.php", 'login') . "\r\n";

			wp_mail($user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message);
		}
	}
	// when user logins to wordpress, create new wordpress account if not exist with board's account datas
	if(!function_exists('wp_authenticate')){
		function wp_authenticate($username, $password) {
			if($GLOBALS['ipbwi']->group->isInGroup(1,$GLOBALS['ipbwi']->member->name2id($username))){
				$errors = new WP_Error;
				$errors->add('validation', __('<strong>ERROR</strong>: Your account is not validated yet.'),'IPBWI');
				return $errors;
			}
			global $wpdb;
			$username = sanitize_user($username);

			if ( '' == $username )
				return new WP_Error('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));

			if ( '' == $password )
				return new WP_Error('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

			$user = get_userdatabylogin($username);

			// this part is for IPBWI compatibility
			if(!$user){
				$member = ipbwi_login();
				if($member){
					/** WordPress Registration API */
					require_once( ABSPATH . WPINC . '/registration.php');
					$memberData = array();
					$memberData['user_pass']		= $password;
					$memberData['user_login']		= $member['name'];
					$memberData['user_nicename']	= $member['members_display_name'];
					$memberData['user_url']			= $member['website'];
					$memberData['user_email']		= $member['email'];
					$memberData['display_name']		= $member['members_display_name'];
					$memberData['nickname']			= $member['members_display_name'];
					$memberData['description']		= $member['interests'];
					$memberData['aim']				= $member['aim_name'];
					$memberData['yim']				= $member['yahoo'];

					//create new wordpress user
					$newUserID = wp_insert_user($memberData);

					/*
					 * okay, wordpress does not allow loginnames with special chars,
					 * but IP.board does it in some cases. So replace loginname in wordpress
					 * with the original one from board.
					 */
					$wpdb->query('UPDATE '.$wpdb->users.' SET user_login="'.$member['name'].'" WHERE ID="'.$newUserID.'"');
				}
			}
			$user = get_userdatabylogin($username);
			// end of IPBWI compatibility part

			if ( !$user || ($user->user_login != $username) ) {
				do_action( 'wp_login_failed', $username );
				return new WP_Error('invalid_username', __('<strong>ERROR</strong>: Invalid username.'));
			}

			$user = apply_filters('wp_authenticate_user', $user, $password);
			if ( is_wp_error($user) ) {
				do_action( 'wp_login_failed', $username );
				return $user;
			}

			if ( !wp_check_password($password, $user->user_pass, $user->ID) ) {
				do_action( 'wp_login_failed', $username );
				return new WP_Error('incorrect_password', __('<strong>ERROR</strong>: Incorrect password.'));
			}

			return new WP_User($user->ID);
		}
	}
	// update board's password when user generates a new one through wordpress
	if(!function_exists('wp_set_password')){
		function wp_set_password( $password, $user_id ) {
			global $wpdb,$user_login;
			// update pw in board
			$GLOBALS['ipbwi']->member->updatePassword($password,$GLOBALS['ipbwi']->member->name2id($user_login));

			$hash = wp_hash_password($password);
			$query = $wpdb->prepare("UPDATE $wpdb->users SET user_pass = %s, user_activation_key = '' WHERE ID = %d", $hash, $user_id);
			$wpdb->query($query);
			wp_cache_delete($user_id, 'users');
		}
	}
?>