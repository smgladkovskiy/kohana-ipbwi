<?php
	// create wordpress account when lost password function is used and board account still exists
	function ipbwi_PwLostCreateAccount(){
		if($GLOBALS['ipbwi']->group->isInGroup(1,$GLOBALS['ipbwi']->member->name2id($_POST['user_login']))){
			return false;
		}
		require_once(ABSPATH . WPINC . '/pluggable.php');
		if(!get_user_by_email($_POST['user_login']) && !get_userdatabylogin($_POST['user_login'])){
			$id = $GLOBALS['ipbwi']->member->name2id($_POST['user_login']);
			if(!$id){
				$GLOBALS['ipbwi']->member->email2id($_POST['user_login']);
			}
			if(isset($id) && $member = $GLOBALS['ipbwi']->member->info($id)){
				/** WordPress Registration API */
				require_once( ABSPATH . WPINC . '/registration.php');
				$memberData = array();
				$memberData['user_pass']		= '';
				$memberData['user_login']		= $member['name'];
				$memberData['user_nicename']	= $member['members_display_name'];
				$memberData['user_url']			= $member['website'];
				$memberData['user_email']		= $member['email'];
				$memberData['display_name']		= $member['members_display_name'];
				$memberData['nickname']			= $member['members_display_name'];
				$memberData['description']		= $member['interests'];
				$memberData['aim']				= $member['aim_name'];
				$memberData['yim']				= $member['yahoo'];
				wp_insert_user($memberData);
			}
		}
	}
	// create board account when wp-account is added in wp-admin-menu
	function ipbwi_wpAdminAddMember(){
		$id = $GLOBALS['ipbwi']->member->create($_POST['user_login'],$_POST['pass1'],$_POST['email']);
		if($id){
			$GLOBALS['ipbwi']->member->updateMember(array('url' => $_POST['url']),$id);
		}
	}
	// deliver renewed anti spam captcha image
	if(isset($_GET['ipbwi_renewImage'])){
		die($GLOBALS['ipbwi']->antispam->renewGdImage());
	}
	// make logout from board link possible
	if(isset($_GET['action']) && $_GET['action'] == 'logout'){
		if(!empty($_SERVER['HTTP_REFERER'])) $ref = $_SERVER['HTTP_REFERER'];
		elseif(!empty($_ENV['HTTP_REFERER'])) $ref = $_SERVER['HTTP_REFERER'];
		elseif(!empty($_REQUEST['HTTP_REFERER'])) $ref = $_REQUEST['HTTP_REFERER'];
		if(strstr($ref,$GLOBALS['ipbwi']->getBoardVar('url'))){
			$_REQUEST['HTTP_REFERER'] = $_ENV['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'] = ipbwi_WEB_URL;
			require_once(ABSPATH . WPINC . '/pluggable.php');
			wp_clear_auth_cookie();
			do_action('wp_logout');
			$GLOBALS['ipbwi']->member->logout();
			header('location: '.$ref);
			die();
		}
	}
?>