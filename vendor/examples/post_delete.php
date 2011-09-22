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
	$pageTitle = 'Post Delete';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(empty($_GET['post_id'])){
		$ipbwi->addSystemMessage('Error', '<strong>No Post-ID delivered</strong> - please go to <a href="post_list.php">Post List</a> and from there you can delete your posts created on <a href="post_add.php">Post Add Page</a>.');
	}elseif($ipbwi->post->delete($_GET['post_id'])){
		$ipbwi->addSystemMessage('Success', '<strong>Post '.$_GET['post_id'].'</strong> successful deleted.');
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

echo $footer;
?>