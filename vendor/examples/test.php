<?php
	/**
	 * @desc			This file is only an example for loading IPBWI. Feel free to copy
	 * 					this code to your own website files.
	 * @copyright		2007-2010 IPBWI development team
	 * @package			liveExample
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 * @version			$LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
	 * @since			2.0
	 * @web				http://ipbwi.com
	 */

	ini_set('display_errors',1);
	error_reporting(E_ALL);
	
	// Initialization
	$pageTitle = 'Information';
	$ipbwicore = '../ipbwi/ipbwi.inc.php';
	if(!file_exists($ipbwicore)){
		die('<p>Could not load '.$ipbwicore.'. Please check the include-path in '.$_SERVER['PHP_SELF'].'</p>');
	}else{
		require_once($ipbwicore);
	}
	
?>