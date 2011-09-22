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
	$pageTitle = 'Topic New';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['new_topic'])){
		// create a new topic
		if($ipbwi->topic->create($ipbwi->forum->name2id($forumName), $_POST['topic_title'], $_POST['post'], $_POST['topic_desc'], $_POST['useEmo'], $_POST['useSig'])){
			$ipbwi->addSystemMessage('Success', '<strong>'.stripslashes($_POST['topic_title']).'</strong> successful created.');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){ ?>
		<h2>Add a Topic to <?php echo $forumName; ?></h2>
		<form action="topic_new.php" method="post">
			<p>Create topic with title <input type="text" name="topic_title" /></p>
			<p>and description: <input type="text" name="topic_desc" /></p>
			<p>in forum <strong><?php echo $forumName; ?></strong> (<a href="topic_list.php">list forum topics</a>)</p>
			<p><?php echo $ipbwi->bbcode->printTextEditor(); ?></p>
			<p class="enable_emoticons">Enable Emoticons: <input type="checkbox" name="useEmo" value="1" checked="checked" /></p>
			<p class="enable_signature">Enable Signature: <input type="checkbox" name="useSig" value="1" checked="checked" /></p>
			<p><input type="submit" name="new_topic" value="Create new Topic!" /></p>
		</form>
<?php }else{ ?>
		<p>Please <a href="member_login.php">login</a> to add a topic.</p>
<?php
	}
echo $footer;
?>