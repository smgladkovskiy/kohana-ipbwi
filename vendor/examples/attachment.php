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
	$pageTitle = 'Attachment downloading &amp; uploading';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	// show/download attachment
	if(isset($_GET['id'])){
		if(isset($_GET['hash']) && $_GET['hash'] == $ipbwi->attachment->hash){
			die($ipbwi->attachment->load($_GET['id']));
		}else{
			die('False hash key for attachment #'.$_GET['id'].'. Please do not deep link to this download.');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();


	// get topic datas
	$tid = $ipbwi->topic->title2id($topicTitle);
?>
	<h2>Attachments of topic <?php echo $topicTitle; ?></h2>
<?php
	if($attachments = $ipbwi->attachment->getList($tid,array('type' => 'topic','ipbwiLink' => ipbwi_WEB_URL.'attachment.php?id=%id%&hash=%hash%'))){
		foreach($attachments as $attachment){
?>
	<h3>Attachment #<?php echo $attachment['attach_id']; ?> (<?php echo $attachment['attach_file']; ?>)</h3>
	<p><a href="<?php echo $attachment['boardURL']; ?>">Download via board</a></p>
	<p><a href="<?php echo $attachment['ipbwiURL']; ?>">Download via IPBWI</a></p>
<?php
		}
	}
?>
		<p><a href="<?php echo ipbwi::DOCS; ?>attachment/attachment.html">Attachment Documentation</a></p>
<?php
	echo $footer;
?>