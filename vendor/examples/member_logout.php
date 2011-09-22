<?php
	/**
	 * @desc			This file is only an example for loading IPBWI. Feel free to copy
	 * 					this code to your own website files.
	 * @copyright		2007-2010 IPBWI development team
	 * @package			liveExample
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 * @version			$LastChangedDate: 2008-09-19 18:49:53 +0000 (Fr, 19 Sep 2008) $
	 * @since			2.0
	 * @link			http://ipbwi.com
	 * @ignore
	 */

	// Initialization
	$pageTitle = 'Member Logout';
	require_once('../ipbwi/ipbwi.inc.php');

	if(!$ipbwi->member->isLoggedIn()){
		$already = true;
	}else{
		$ipbwi->member->logout();
	}

	require_once('lib/php/includes.inc.php');

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
?>
		<h2>Login-Form</h2>
<?php
	if(isset($already)){
?>
		<p>Your are already logged out</p>
<?php
	}else{
?>
		<p>Logout successfull</p>
<?php
	}
echo $footer;
?>