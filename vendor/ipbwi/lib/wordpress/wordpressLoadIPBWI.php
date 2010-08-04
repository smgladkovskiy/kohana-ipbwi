<?php
/**
 * @desc			This file loads IPBWI as plugin to Wordpress.
 * @copyright		2007-2008 IPBWI development team
 * @package			Wordpress
 * @author			Matthias Reuter ($LastChangedBy: matthias $)
 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @version			$LastChangedDate: 2008-12-10 01:01:41 +0000 (Mi, 10 Dez 2008) $
 * @since			2.0
 * @web				http://ipbwi.com
 */
/*
Plugin Name: Invision Power Board Wordpress Integration (IPBWI)
Plugin URI: http://ipbwi.com
Description: IPBWI provides direct access to many functions and datas of the Invision Power Board (IP.Board)
Version: 2.05
Author: Matthias Reuter
Author URI: http://pc-intern.com
*/

// ob_start is important when there should be no skin output, e.g. for sitemaps or attachments
ob_start();

// Load options for IPBWI
define('ipbwi_IN_WORDPRESS',true);
define('ipbwi_BOARD_PATH',get_option('ipbwi_board_path'));
define('ipbwi_ROOT_PATH',WP_PLUGIN_DIR.'/ipbwi/');
define('ipbwi_UPLOAD_PATH',get_option('ipbwi_upload_path'));
define('ipbwi_WEB_URL',get_option('siteurl').'/');
define('ipbwi_COOKIE_DOMAIN',get_option('ipbwi_sso_cookie_domain'));
define('ipbwi_UTF8',get_option('ipbwi_utf8'));
define('ipbwi_LANG',get_option('ipbwi_lang'));
//define('ipbwi_CAPTCHA_MODE',get_option('ipbwi_captcha'));
if(get_option('ipbwi_db_prefix') != ''){
	define('ipbwi_DB_prefix',get_option('ipbwi_db_prefix'));
}else{
	define('ipbwi_DB_prefix','ipbwi_');
}

// load ipbwi wordpress functions
require_once(ipbwi_ROOT_PATH.'lib/wordpress/wordpress.inc.php');

// Hook for adding admin toplevel menu
function ipbwi_add_toplevel_menu() {
	add_menu_page('IPBWI', 'IPBWI', 8, __FILE__, 'ipbwi_toplevel_page');
	add_submenu_page(__FILE__, 'Core Settings', 'Core Settings', 8, __FILE__, 'ipbwi_toplevel_page');
}
add_action('admin_menu', 'ipbwi_add_toplevel_menu');

// check if IPBWI is properly configured
if(get_option('ipbwi_board_path') != ''){
	// load IPBWI class
	require_once(ipbwi_ROOT_PATH.'ipbwi.inc.php');
	/*@ini_set('display_errors',1);
	error_reporting(E_ALL);*/

	// makes IPBWI compatible to WP MU -_-
	if(isset($_POST[ 'option_page' ])){
		$option_page = $_POST['option_page'];
	}else{
		$option_page = 'options';
	}

	// define globals
	if(isset($_SERVER['REDIRECT_URL'])){
		$GLOBALS['ipbwi_request'] = explode('/',$_SERVER['REDIRECT_URL']);
	}
	// $_SERVER['REQUEST_URI']
	$GLOBALS['ipbwi'] = $ipbwi;

	add_filter('404_template', 'ipbwi_404');

	// Single Sign On
	if(get_option('ipbwi_sso') != ''){
		require_once(ipbwi_ROOT_PATH.'lib/wordpress/wordpress_sso.inc.php');
		// add captcha...
		if(get_option('ipbwi_captcha') == 1){
			// to registration form
			add_action('register_form','ipbwi_registerForm',10,0);
		}
		add_filter('registration_errors', 'ipbwi_registrationErrors');
		// Forum Login
		add_action('wp_login','ipbwi_login',10,2);
		add_filter('login_errors', 'ipbwi_loginErrors');
		// Forum Logout
		add_action('wp_logout','ipbwi_logout',10,0);
		// Update Forum Profile
		add_action('profile_update','ipbwi_updateProfile',10,0);
		// Extend Profile Edit Screen
		if(get_option('ipbwi_sso_advanced_profile') != '' || get_option('ipbwi_sso_custom_profile_fields') != ''){
			add_action('show_user_profile','ipbwi_extendProfile',10,0);
		}
		// delete board account when wordpress account is deleted from admin
		add_action('delete_user','ipbwi_delete_user',10,1); // Forum Login
	}

	// Latest Topics
	if(get_option('ipbwi_settings_widget_latestTopics') != ''){
		require_once(ipbwi_ROOT_PATH.'lib/wordpress/wordpress_topics.inc.php');
		add_action('widgets_init','ipbwi_widget_latestTopics');
	}

	// Latest Images
	if(get_option('ipbwi_settings_widget_latestImages') != '' && $GLOBALS['ipbwi']->gallery->installed){
		require_once(ipbwi_ROOT_PATH.'lib/wordpress/wordpress_gallery.inc.php');
		add_action('widgets_init','ipbwi_widget_latestImages');
		add_action('widgets_init','ipbwi_widget_imageOfTheDay');
	}

	if(isset($GLOBALS['ipbwi_request'][1]) && $GLOBALS['ipbwi_request'][1] == 'ipbwi'){
		add_filter('wp_title', 'ipbwi_title');
	}

	// Hook for adding admin menus
	function ipbwi_add_sublevel_menus() {
		// Single Sign On
		add_submenu_page(__FILE__, 'Single Sign On', 'Single Sign On', 8, 'ipbwi_plugin_single_sign_on', 'ipbwi_plugin_sso');
		// Topics
		add_submenu_page(__FILE__, 'Topics', 'Topics', 8, 'ipbwi_plugin_topics', 'ipbwi_plugin_topics');
		// Gallery
		if($GLOBALS['ipbwi']->gallery->installed){
			add_submenu_page(__FILE__, 'Gallery', 'Gallery', 8, 'ipbwi_plugin_gallery', 'ipbwi_plugin_gallery');
		}
	}
	add_action('admin_menu', 'ipbwi_add_sublevel_menus');
}

	// not finished yet

	// Sitemap
	if(get_option('ipbwi_sitemap') != ''){
		require_once(ipbwi_ROOT_PATH.'lib/wordpress/wordpress_sitemap.inc.php');
		add_shortcode('ipbwi_sitemap','ipbwi_generateSitemap');
	}

	// Tag Cloud
	if(get_option('ipbwi_topics_widget_tagcloud') != ''){
		require_once(ipbwi_ROOT_PATH.'lib/wordpress/wordpress_tagcloud.inc.php');
		add_action('widgets_init','ipbwi_viewCloud'); // View Tag Cloud
	}

?>