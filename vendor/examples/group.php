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
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	// Initialization
	$pageTitle		= 'Groups Live Examples';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['change_group'])){
		// define allowed group-changes
		$to['validating'] = 1;
		$to['member'] = 3;
		// check if new group is valid
		if(isset($_POST['group']) && isset($to[$_POST['group']])){
			$newGroup = $to[$_POST['group']];
		}else{
			$newGroup = false;
		}
		// change group
		if(isset($newGroup) && isset($to[$_POST['group']]) && $to[$_POST['group']] > 0 && $ipbwi->group->change($newGroup) === true){
			$ipbwi->addSystemMessage('Success','Group successfully changed.');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
?>
		<h3>Are you an admin?</h3>
<?php
	if($ipbwi->group->isInGroup(4)){
		echo '<p>Congratulation! You are an admin :)</p>';
	}else{
		echo '<p>Uh, you are not an admin - too bad!</p>';
	}
	$group = $ipbwi->group->info();
?>
		<p>Currently, you are in member's group <?php echo $group['prefix'].$group['g_title'].$group['suffix']; ?></p>
		<h3>Change Group</h3>
<?php
	if($group['g_id'] != 1){
?>
		<form method="post">Move your Account to Validating-Group: <input type="hidden" name="group" value="validating" /><input type="submit" name="change_group" value="Now!" /></form>
<?php
	}else{
?>
		<form method="post">Move your Account to Default-Member-Group: <input type="hidden" name="group" value="member" /><input type="submit" name="change_group" value="Now!" /></form>
<?php
	}
	if($ipbwi->member->isAdmin()){
?>
		<div class="info"><div class="i_important"><strong>Important Notice:</strong> If you are an Admin and change your group, you do not have access to the admin center of your IP.Board anymore. Login to you database and change your groupid <em>member_group_id</em> in table <em>'.$this->ipbwi->board['sql_tbl_prefix'].'members</em> to 4 to revert the changes on your admin-account.</div></div>
<?php
	}
echo $footer;
?>