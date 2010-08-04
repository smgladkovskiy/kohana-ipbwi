<?php

define('IPB_THIS_SCRIPT', 'public');
define( 'IN_IPB', 1 );
define( 'ALLOW_FURLS', FALSE ); // disable friendly url check

require_once(ipbwi_BOARD_PATH.'admin/api/api_core.php');
class ipbwi_ips_wrapper extends apiCore {
	public	$loggedIn;
	public	$DB;
	public	$settings;
	public	$request;
	public	$lang;
	public	$member;
	public	$cache;	
	public	$registry;
	public	$perm;
	public	$parser;
	
	public function __construct(){
	
		$this->init();
		
		$this->loggedIn					= (bool) $this->lang->memberData['member_id']; // status wether a member is logged in
		$this->settings['base_url']		= $this->settings['board_url'].'?';
		
		// get common functions
		require_once(ipbwi_BOARD_PATH.'admin/sources/base/ipsController.php');
		$this->command		= new ipsCommand_default();
		
		// initialize session
		require_once(ipbwi_BOARD_PATH.'admin/sources/classes/session/publicSessions.php');
		$this->session		= new publicSessions();

		// prepare bbcode functions
		$this->cache->rebuildCache( 'emoticons', 'global' );
		
		// force ability of using rich text editor
		$this->registry->member()->setProperty('_canUseRTE', TRUE );
		
		/*
		MEMBER FUNCTIONS
		*/
		
		// get login / logout functions
		require_once(ipbwi_ROOT_PATH.'lib/ips/ips_public_core_global_login.inc.php');
		$this->login = new ipbwi_ips_public_core_global_login();
		$this->login->initHanLogin($this->registry); 
		
		// get registration function
		require_once(ipbwi_ROOT_PATH.'lib/ips/ips_register.inc.php');
		$this->register = new ipbwi_ips_public_core_global_register();
		$this->register->initRegister($this->registry);
		
		// deactivate redirect function
		require_once(ipbwi_ROOT_PATH.'lib/ips/ips_output.inc.php');
		$this->registry->output = new ipbwi_ips_output($this->registry);
		
		// get permission functions
		require_once(ipbwi_BOARD_PATH.'admin/sources/classes/class_public_permissions.php');
		$this->perm = new classPublicPermissions($this->registry);
		
		// get bbcode functions
		require_once(ipbwi_BOARD_PATH.'admin/sources/handlers/han_parse_bbcode.php');
		$this->parser = new parseBbcode($this->registry);
		
		// get messenger functions
		require_once(ipbwi_BOARD_PATH.'admin/applications/members/sources/classes/messaging/messengerFunctions.php');
		$this->messenger = new messengerFunctions($this->registry);
		
		// get member functions
		/*require_once(ipbwi_BOARD_PATH.'admin/sources/classes/member/memberFunctions.php');
		$this->memberFunctions = new memberFunctions($this->registry);*/
		
	}
	
	public function memberDelete($id, $check_admin=false){
		if( !is_array($id) && !intval($id) )
		{
			$id = $this->member->member_id;
		}
		// first logout
		@$this->login->doLogout(false); // @ todo: check notices from ip.board
		// delete member
		$return = @IPSMember::remove($id, $check_admin); // @ todo: check notices from ip.board
		
        return $return === null ? true : false;
	}
	// return data of current member
	public function myInfo(){
		return $this->lang->memberData;
	}
	
	// change user's pw
	public function changePW($newPass, $userID = false, $currentPass = false){
		$salt		= IPSMember::generatePasswordSalt(5);
		$hash		= IPSMember::generateCompiledPasshash($salt, md5($newPass));

		// check old pass
		if($currentPass !== false){
			$sql		= $this->DB->query('SELECT members_pass_hash,members_pass_salt FROM '.$this->settings['sql_tbl_prefix'].'members WHERE member_id="'.$userID.'"');
			$info		= $this->DB->fetch($sql);
			
			$hash_old	= IPSMember::generateCompiledPasshash($info['members_pass_salt'], md5($currentPass));
			
			if($info['members_pass_hash'] != $hash_old){
				return false;
			}
		}
		
		$SQL = 'UPDATE '.$this->settings['sql_tbl_prefix'].'members SET members_pass_hash="'.$hash.'",members_pass_salt="'.$salt.'" WHERE member_id="'.$userID.'"';
		
		$this->DB->query($SQL);

		return true;
	}
}
?>