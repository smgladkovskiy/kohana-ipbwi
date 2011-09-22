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
	$pageTitle		= 'Add a new forum';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	$permsCat		= array( // test category permissions
		'show' => '*',
		'read' => array(),
		'start' => array(),
		'reply' => array(),
		'upload' => array(),
		'download' => array()
	);
	$perms			= array( // test forum permissions
		'show' => '*',
		'read' => '*',
		'start' => '*',
		'reply' => '*',
		'upload' => '*',
		'download' => '*'
	);

	// add new forum
	if($ipbwi->member->isAdmin() && isset($_POST['add_forum'])){
		if($ipbwi->forum->create($_POST['forum_name'], $_POST['forum_desc'], $_POST['forum_cat'], $perms)){
			$ipbwi->addSystemMessage('Success','<strong>'.$_POST['forum_name'].'</strong> successful created');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
		<form action="forum_new.php" method="post">
			<p>Forum Name: <input type="text" name="forum_name" /></p>
			<p>Forum Description: <input type="text" name="forum_desc" /></p>
			<p>Parent Category: <select name="forum_cat"><?php echo $ipbwi->forum->getAllSubs($ipbwi->forum->name2id($forumName),'html_form'); ?></select></p>
			<p><input type="submit" name="add_forum" value="add forum!" /></p>
		</form>
<?php
	}else{
?>
		<p>You have to <a href="member_login.php">login</a> to use this live-example.</p>
<?php
	}
echo $footer;
?>