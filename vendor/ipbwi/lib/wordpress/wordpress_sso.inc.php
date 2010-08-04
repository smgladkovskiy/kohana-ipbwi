<?php
	// Load Library
	require_once(ipbwi_ROOT_PATH.'lib/wordpress/wordpress_sso_catchRequests.inc.php');
	require_once(ipbwi_ROOT_PATH.'lib/wordpress/wordpress_sso_funcReplace.inc.php');
	// catch Reqests
	if(isset($_GET['action']) && $_GET['action'] == 'lostpassword' && isset($_POST['user_login']) && $_POST['user_login'] != ''){
		ipbwi_PwLostCreateAccount();
	}
	if(isset($_POST['action']) && $_POST['action'] == 'adduser'){
		ipbwi_wpAdminAddMember();
	}
	// extend login form
	function ipbwi_registerForm(){
		echo '
		<p>
			<label>Captcha-Control<br>
			'.$GLOBALS['ipbwi']->antispam->getHTML('wp-login.php?ipbwi_renewImage=1').'
			</label>
		</p>';
	}
	function ipbwi_registrationErrors($errors){
		if(get_option('ipbwi_captcha') == 1){
			if(!$GLOBALS['ipbwi']->antispam->validate()){
				$GLOBALS['ipbwi']->addSystemMessage('Error', $GLOBALS['ipbwi']->getLibLang('captchaWrongCode'));
			}
		}
		if($GLOBALS['ipbwi']->member->name2id($_POST['user_login']) || $GLOBALS['ipbwi']->member->displayname2id($_POST['user_login'])){
			$GLOBALS['ipbwi']->addSystemMessage('Error', $GLOBALS['ipbwi']->getLibLang('wpRegisterNameExists'));
		}
		if($GLOBALS['ipbwi']->member->email2id($_POST['user_email'])){
			$GLOBALS['ipbwi']->addSystemMessage('Error', $GLOBALS['ipbwi']->getLibLang('wpRegisterEmailExists'));
		}
		if($GLOBALS['ipbwi']->printSystemMessages(false,true)){
			$errors->add('ipbwi', $GLOBALS['ipbwi']->printSystemMessages(false,true));
		}
		return $errors;
	}
	function ipbwi_loginErrors($errors){
		// outcomment because it makes double-outputs zo registration errors
		//$errors = $errors.$GLOBALS['ipbwi']->printSystemMessages(false,true);
		return $errors;
	}
	// make login
	function ipbwi_login(){
		// Enable Cookies
		$cookie = true;
		// Remember User?
		$sticky = (isset($_POST['rememberme'])) ? true : false;
		// Retrieve Member-ID from Loginname
		$loginID = $GLOBALS['ipbwi']->member->name2id($_POST['log']);
		// Does Member has an existing board account?
		if($loginID){
			// Make it everytime sync and update user's password when user is logging in
			//$GLOBALS['ipbwi']->member->updatePassword($_POST['pwd'],$loginID);
			// Now log in
			$member = $GLOBALS['ipbwi']->member->login($_POST['log'],$_POST['pwd'],$cookie,false,$sticky);
			if($member){
				return $member;
			}else{
				return false;
			}
		// If wordpress-members does not have a board account, just create one.
		// first: retrieve wordpress-user datas
		}elseif(($user = get_userdatabylogin($_POST['log'])) && isset($_POST['pwd'])){
			// then create user account in board
			if($GLOBALS['ipbwi']->member->create($_POST['log'],$_POST['pwd'],$user->user_email,false,($GLOBALS['ipbwi']->getBoardVar('reg_auth_type') ? true : false),$_POST['log'],true)){
				// now login the brand new board user
				$member = $GLOBALS['ipbwi']->member->login($_POST['log'],$_POST['pwd'],$cookie,false,$sticky);
				if($member){
					return $member;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	// make logout
	function ipbwi_logout(){
		$GLOBALS['ipbwi']->member->logout();
	}
	// make profile update
	function ipbwi_updateProfile(){
		// get accountname of current logged in member
		global $user_login;
		// check if another member is loaded for editing. set this accountname as edit username
		if(isset($_GET['user_id'])){
			$fooUser = get_user_to_edit($_GET['user_id']);
			$userName = $fooUser->user_login;
		// set edit username to accountname of currently logged in user
		}else{
			$userName = $user_login;
		}
		// set userID
		$userID = $GLOBALS['ipbwi']->member->name2id($userName);
		// check if a board account exists
		if(empty($userID)){
			$GLOBALS['ipbwi']->addSystemMessage('Error','Your Wordpress-Account <strong>'.$userName.'</strong> does not exist in Board');
			return false;
		}
		// update password
		if(isset($_POST['pass1']) && isset($_POST['pass2']) && $_POST['pass1'] != '' && $_POST['pass1'] == $_POST['pass2']){
			$GLOBALS['ipbwi']->member->updatePassword($_POST['pass1'],$userID);
			if(!$GLOBALS['ipbwi']->member->isLoggedIn()){
				$GLOBALS['ipbwi']->member->login($userName,$_POST['pass1']);
			}
		}
		// sync basic profile datas
		$update = array();
		if(!$GLOBALS['ipbwi']->member->displayname2id($_POST['display_name']) || ($GLOBALS['ipbwi']->member->name2id($_POST['display_name']) && $userName == $_POST['display_name'])){
			$update['members_display_name']	= $_POST['display_name'];
		}
		$update['email']				= $_POST['email'];
		$update['website']				= $_POST['url'];
		$update['aim_name']				= $_POST['aim'];
		$update['yahoo']				= $_POST['yim'];
		$update['interests']			= $_POST['description'];
		// update Advanced Profile
		if(get_option('ipbwi_sso_advanced_profile') != '' && $user_login == $userName){
			// update signature
			$GLOBALS['ipbwi']->member->updateSig($_POST['ipbwi_signature']);
			// update avatar
			$GLOBALS['ipbwi']->member->updateAvatar('ipbwi_avatar_new',(isset($_POST['ipbwi_delete_avatar']) ? true : false));
			// update photo
			$GLOBALS['ipbwi']->member->updatePhoto('ipbwi_photo_new',(isset($_POST['ipbwi_delete_photo']) ? true : false));

			$update['location']		=  $_POST['ipbwi_location'];
			$update['icq_number']	=  $_POST['ipbwi_icq_number'];
			$update['msnname']		=  $_POST['ipbwi_msnname'];
			$update['bday_month']	=  $_POST['ipbwi_bday_month'];
			$update['bday_day']		=  $_POST['ipbwi_bday_day'];
			$update['bday_year']	=  $_POST['ipbwi_bday_year'];
		}
		// update member informations
		$GLOBALS['ipbwi']->member->updateMember($update,$userID);
		// update custom profile fields
		if(get_option('ipbwi_sso_custom_profile_fields') != '' && $user_login == $userName){
			// update custom fields
			$customfields = $GLOBALS['ipbwi']->member->listCustomFields();
			if(isset($customfields) && is_array($customfields) && count($customfields) > 0){
				foreach($customfields as $field){
					$GLOBALS['ipbwi']->member->updateCustomField($field['pf_id'],$_POST['ipbwi_field_'.$field['pf_id']]);
				}
			}
		}
		return true;
	}
	// make profile extension
	function ipbwi_extendProfile(){
		$currentOutput = ob_get_clean();
		echo str_replace('<form','<form enctype="multipart/form-data"',$currentOutput);
		require_once(get_template_directory().'/ipbwi_profile_update.php');
	}
	// make view profile page
	function ipbwi_show_profile($userName=false){
		if($userName && $userID = $GLOBALS['ipbwi']->member->name2id($userName)){
			$userInfo = $GLOBALS['ipbwi']->member->info($userID);
		}elseif(empty($GLOBALS['ipbwi_request'][3]) && $GLOBALS['ipbwi']->member->isLoggedIn()){
			$userInfo = $GLOBALS['ipbwi']->member->info();
		}else{
			$userInfo = false;
		}
		// add friend
		if(isset($userInfo['id']) && $GLOBALS['ipbwi_request'][4] == 'addfriend'){
			$GLOBALS['ipbwi']->member->addFriend($userInfo['id']);
		}
		// remove friend
		if(isset($userInfo['id']) && $GLOBALS['ipbwi_request'][4] == 'removefriend'){
			$GLOBALS['ipbwi']->member->removeFriend($userInfo['id']);
		}

		require_once(get_template_directory().'/ipbwi_profile_view.php');
	}
	// make delete
	function ipbwi_delete_user(){
		$boardIDs = array();
		foreach($_POST['users'] as $user){
			$info = get_userdata($user);
			$id = $GLOBALS['ipbwi']->member->name2id($info->user_login);
			$boardIDs[$id] = $id;
		}
		if(count($boardIDs) > 0){
			$GLOBALS['ipbwi']->member->delete($boardIDs);
		}
	}
?>