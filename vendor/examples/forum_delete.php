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
	$pageTitle = 'Forum Delete';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['delete_forum']) && $ipbwi->member->isAdmin()){
		if($ipbwi->forum->delete($_POST['forum_id'])){
			$ipbwi->addSystemMessage('Success','Forum successful deleted');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->forum->name2id($forumName) === false && $ipbwi->member->isAdmin()){ ?>
		<h2>No IPBWI-Test-Forum found</h2>
		<form action="forum_info.php" method="post">
			<p><a href="forum_info.php">Create a new IPBWI Test Forum!</a></p>
		</form>
<?php
	}elseif($ipbwi->member->isAdmin()){
		// get topic datas
		$forumID = $ipbwi->forum->name2id($forumName);
		$forum = $ipbwi->forum->info($forumID);
?>
		<h2>Delete forum</h2>
		<p>Klick on forum-name to delete the test-sub-forums including all topics, polls and posts.</p>
		<form action="forum_delete.php" method="post" onsubmit="return confirm('Really delete this forum?')">
			<select name="forum_id"><?php echo $ipbwi->forum->getAllSubs($ipbwi->forum->name2id($forumName),'html_form'); ?></select>
			<p><input type="submit" name="delete_forum" value="delete forum!" /></p>
		</form>
<?php
	}else{
?>
		<h2>No permissions to delete any forums</h2>
		<p>You have to be an admin to delete forums. if you aren't: Install IPBWI-package which includes all examples on your server.</p>
<?
	}
echo $footer;
?>