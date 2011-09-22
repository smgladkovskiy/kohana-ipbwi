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
	$pageTitle = 'Topic Edit';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['edit_topic'])){
		$options = array();
		if(isset($_POST['use_emo'])){
			$options['use_emo'] = 1;
		}else{
			$options['use_emo'] = 0;
		}
		if(isset($_POST['use_sig'])){
			$options['use_sig'] = 1;
		}else{
			$options['use_sig'] = 0;
		}
		$ipbwi->topic->edit($ipbwi->topic->title2id($topicTitle),$topicTitle,$_POST['post'],$_POST['desc'],$_POST['reason'],$options);
		header('location: topic_info.php');
		die();
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
		// get topic datas
		$tid = $ipbwi->topic->title2id($topicTitle);
		$topic = $ipbwi->topic->info($tid);
		if($topic['state'] == 'open'){
			$closed = false;
		}else{
			$closed = true;
		}
?>
		<form action="topic_edit.php" method="post">
			<p>Title: <span title="Original title of this topic is important for working live-examples">(deactivated)</span> <input type="text" style="width:100%;" name="title" value="<?php echo $topic['title']; ?>" disabled="disabled" /></p>
			<p>Description: <input type="text" style="width:100%;" name="desc" value="<?php echo $topic['description']; ?>" /></p>
			<div style="background-color:#FFF;border:1px solid #000;padding:10px;">
				<div style="text-align:right;"><strong><a href="<?php echo $ipbwi->getBoardVar('url'); ?>index.php?showuser=<?php echo $topic['author_id']; ?>"><?php echo $ipbwi->member->id2displayname($topic['author_id']); ?></a> @ <span title="<?php echo $ipbwi->date($topic['start_date']); ?>"><?php echo $ipbwi->date($topic['start_date'],'%d. %B %Y'); ?></span></strong></div>
				<p><?php echo $ipbwi->bbcode->printTextEditor($topic['post'],'post'); ?></p>
				<p>Reason for Editing: <input type="text" style="width:100%;" name="reason" value="<?php echo $topic['post_edit_reason']; ?>" /></p>
				<p>Enable Emoticons: <input type="checkbox" name="use_emo" value="1" <?php if(isset($topic['use_emo']) && $topic['use_emo'] == 1){ echo ' checked="checked"'; } ?> /></p>
				<p>Enable Signature: <input type="checkbox" name="use_sig" value="1" <?php if(isset($topic['use_sig']) && $topic['use_sig'] == 1){ echo ' checked="checked"'; } ?> /></p>
				<div style="text-align:right;"><input type="submit" name="edit_topic" value="Update this topic" /></div>
			</div>
		</form>
<?php }else{ ?>
	<p>You have to be logged in to view this example</p>
<?php
	}
echo $footer;
?>