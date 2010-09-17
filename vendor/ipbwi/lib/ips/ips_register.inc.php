<?php

require_once(ipbwi_BOARD_PATH . 'admin/applications/core/modules_public/global/register.php' );
class ipbwi_ips_public_core_global_register extends public_core_global_register {

	public $errors		= null;
	public $request		= array();

	// load login handler. these functions are the base for login and logout
	public function initRegister($core=false)
	{
		$this->registry		= $core;
		$this->DB			= $this->registry->DB();
		$this->settings		= $this->registry->fetchSettings();
		$this->request		= $this->registry->fetchRequest();
		$this->lang			= $this->registry->getClass('class_localization');
		ipsRegistry::getClass('class_localization')->loadLanguageFile(array('public_register'), 'core');
		$this->member		= $this->registry->member();
		$this->memberData	= $this->registry->member()->fetchMemberData();
		$this->cache		= $this->registry->cache();
		$this->caches		= $this->registry->cache()->fetchCaches();
	}
	
	// set request for registration
	public function create($request){
		$this->request = $request;
		$this->request['coppa_user']			= 0;
		$this->settings['reg_auth_type'] 		= $request['reg_auth_type']; // set validation
		$this->settings['bot_antispam_type']	= $request['bot_antispam_type'];
		
		@$this->registerProcessForm(); // @ todo: check notices from ip.board
	}
	
	// catch registration errors
	public function registerForm($form_errors=array()){
		$this->errors = $form_errors;
	}
}

?>