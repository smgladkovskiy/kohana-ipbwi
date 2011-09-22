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
	$pageTitle = 'Poll New';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	// add test-poll
	if(isset($_POST['new_test_poll']) && $ipbwi->member->isAdmin() && $ipbwi->topic->title2id($pollTopicTitle) === false && $ipbwi->forum->name2id($forumName) != false){
		// get all forum-ids which uses the given forum-name
		$forumIDs = $ipbwi->forum->name2id($forumName);
		// if more than one id is delivered, get the first one
		if(is_array($forumIDs)) $forumIDs = $forumIDs[0];
		// create a new test-poll
		$pollQuestions = array(
			'1' => 'Do you think IPBWI is useful?',
			'2' => 'Do you use IPBWI on your website?',
			'3' => 'RTFM?',
			'4' => 'Do you like...'
		);
		$pollChoices = array(
			'1' => array('yes', 'no'),
			'2' => array('yes, of course','no, but I will','no, never'),
			'3' => array('100% agree!','nope, I insist on first-level-support','w00t? what a weird acronym...'),
			'4' => array('cheese','sausages','vegetable','magic mushrooms oO')
		);
		$pollOnly = 0;
		$pollMulti = array(
			'4' => 1
		);
		$pollTitle = 'Your opinion about IPBWI.';
		if($ipbwi->poll->create($ipbwi->topic->create($forumIDs, $pollTopicTitle, $pollTopicDesc, $pollTopicPost),$pollQuestions,$pollChoices,$pollTitle,$pollOnly,$pollMulti)){
			$ipbwi->addSystemMessage('Success','<strong>'.$pollTitle.'</strong> successful created');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->topic->title2id($pollTopicTitle) === false){
?>
		<h2>No IPBWI-Test-Poll found</h2>
		<form action="poll_new.php" method="post">
			<p><input type="submit" name="new_test_poll" value="Create new IPBWI Test Poll!" /></p>
		</form>
<?php
	}else{
?>
		<h2>IPBWI-Test-Poll already created</h2>
		<p>Go to the <a href="poll_info.php">Poll Informations.</a></p>
<?php
	}
echo $footer;
?>