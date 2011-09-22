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
	$pageTitle = 'delete Member Account';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	$member = $ipbwi->member->info();

	if($ipbwi->member->isLoggedIn() && isset($_POST['account_pw']) && $_POST['account_pw'] != ''){
		if($ipbwi->member->delete($member['member_id'],$_POST['account_pw'])){
			header('location: '.ipbwi_WEB_URL).die();
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
	<p><strong>Deletion of Account <em><?php echo $ipbwi->member->id2name($member['member_id']); ?></em> (ID: <?php echo $member['member_id']; ?>)</strong></p>
	<p>Please type in your account password and submit to delete your account.</p>
	<form action="member_delete.php" method="post">
		<p>Your Account Password: <input name="account_pw" type="password" /> <input onclick="if(confirm('Really delete this account?')){return true;}else{return false;}" type="submit" value="Delete Account" /></p>
		<div class="info"><div class="i_important">Your account will be lost immediately, you will not asked again after submitting this form. There is no backup to restore your account after this step.</div></div>
	</form>
<?php
	}else{
?>
	<h3>Please login</h3>
	<p>You have to <a href="member_login.php">login</a> to view this example</p>
<?
	}
	echo $footer;
?>
