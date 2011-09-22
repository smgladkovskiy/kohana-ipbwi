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

	require_once('../ipbwi/ipbwi.inc.php');
	$pageTitle = 'Topic Info';
	require_once('lib/php/includes.inc.php');

	// rate a topic
	if(isset($_POST['rate_topic'])){
		if($ipbwi->topic->rate($ipbwi->topic->title2id($topicTitle),$_POST['rate_value'],true)){
			$ipbwi->addSystemMessage('Success','Topic rated successfully');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	// get topic datas
	$tid = $ipbwi->topic->title2id($topicTitle);
	$topic = $ipbwi->topic->info($tid,true,true,ipbwi_WEB_URL.'attachment.php?id=%id%&hash=%hash%');
?>
		<h2><?php echo $topic['title']; ?></h2>
		<p><strong><?php echo $topic['description']; ?></strong></p>
		<div style="background-color:#FFF;border:1px solid #000;padding:10px;">
			<div style="text-align:right;"><strong><a href="<?php echo $ipbwi->getBoardVar('url'); ?>index.php?showuser=<?php echo $topic['author_id']; ?>"><?php echo $ipbwi->member->id2displayname($topic['author_id']); ?></a> @ <span title="<?php echo $ipbwi->date($topic['start_date']); ?>"><?php echo $ipbwi->date($topic['start_date'],'%d. %B %Y'); ?></span></strong></div>
			<p><?php echo $topic['post']; ?></p>
			<?php if($topic['post_edit_reason']){ ?><p style="border:1px dashed #999;padding:5px;margin:10px;font-size:9px;">Edited by <?php if($topic['edit_name']){ ?><?php echo $topic['edit_name']; } ?> (Reason: &quot;<?php echo $topic['post_edit_reason']; ?>&quot;)</p><?php } ?>
			<?php
			if(count($topic['AttachmentNotInlineInfo']) > 0){
				foreach($topic['AttachmentNotInlineInfo'] as $attachList){
					echo '<p>'.$attachList['ipbwiLink'].'</p>';
				}
			}
			?>
			<div style="text-align:right;"><strong><?php if($ipbwi->member->isAdmin()){ echo '<a onclick="if(confirm(\'Really delete this topic?\')){return true;}else{return false;}" href="topic_delete.php?topic_id='.$topic['tid'].'">delete</a> | '; } ?><a href="topic_edit.php">edit</a></strong></div>
		</div>
		<h3>Rating functions</h3>
		<p>Topic-Rating: <?php if($topic['topic_rating_total'] > 0) echo round($topic['topic_rating_total']/$topic['topic_rating_hits'],0); else echo 0; ?>/5</p>
		<?php if($ipbwi->member->isLoggedIn()){ ?><form action="topic_info.php" method="post"><p><select name="rate_value"><option>1</option><option>2</option><option>3</option><option>4</option><option>5</option></select><input type="submit" name="rate_topic" value="Rate!" /></p></form><?php }else{ ?><a href="login.php">Login</a> to rate this topic<?php } ?>
<?php echo $footer; ?>