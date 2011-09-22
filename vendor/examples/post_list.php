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
	$pageTitle = 'Post List';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	$userInfo = $ipbwi->member->info();
?>
	<h2>Replies of <?php echo $topicTitle; ?></h2>
<?php
	$settings = array();
	$settings['order'] = 'desc';
	if(isset($_GET['getOwnPostsOnly']) && $ipbwi->member->isLoggedIn()){
		$settings['memberid'] = $userInfo['member_id'];
		echo '<p>Now there are only your posts listed.</p>';
	}elseif($ipbwi->member->isLoggedIn()){
		echo '<p><a href="?getOwnPostsOnly=1">Click here</a> to get viewed your own posts only.</p>';
	}
	$posts = $ipbwi->post->getList($ipbwi->topic->title2id($topicTitle),$settings);
	if(isset($posts) && is_array($posts) && count($posts) > 0){
		foreach($posts as $post){
?>
	<div style="background-color:#FFF;border:1px solid #000;padding:10px;margin-top:10px;">
		<div style="text-align:right;"><strong><a href="<?php echo $ipbwi->getBoardVar('url'); ?>/index.php?showuser=<?php echo $post['author_id']; ?>"><?php echo $ipbwi->member->id2displayname($post['author_id']); ?></a> @ <span title="<?php echo $ipbwi->date($post['post_date']); ?>"><?php echo $ipbwi->date($post['post_date'],'%d. %B %Y'); ?></span></strong></div>
<?php
		echo $post['post'];
?>
		<div style="text-align:right;">
<?php
			if($ipbwi->member->isLoggedIn() && ($ipbwi->member->isAdmin() || $ipbwi->member->isSuperMod() || ($post['author_id'] === $userInfo['member_id']))){
				echo '<a href="post_edit.php?post_id='.$post['pid'].'">edit</a> | <a onclick="if(confirm(\'Really delete this post?\')){return true;}else{return false;}" href="post_delete.php?post_id='.$post['pid'].'">delete</a>';
			}
?>
		</div>
	</div>
<?php
		}
	}
	echo $footer;
?>