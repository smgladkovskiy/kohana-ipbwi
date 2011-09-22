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
	$pageTitle = 'Topic Delete';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(empty($_GET['topic_id'])){
		$ipbwi->addSystemMessage('Error', '<strong>No Topic-ID delivered</strong> - please go to <a href="topic_list.php">topic List</a> and from there you can delete your topics created on <a href="topic_create.php">Topic Create Page</a>.');
	}elseif($ipbwi->member->isAdmin() && $ipbwi->topic->delete($_GET['topic_id'])){
		$ipbwi->addSystemMessage('Success', 'Topic '.$_GET['topic_id'].' successful deleted.');
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
echo $footer;
?>