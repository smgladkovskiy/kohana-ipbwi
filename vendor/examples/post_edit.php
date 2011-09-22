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
	$pageTitle = 'Post Edit';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['edit_post'])){
		if(isset($_POST['useEmo'])){
			$useEmo = 1;
		}else{
			$useEmo = 0;
		}
		if(isset($_POST['useSig'])){
			$useSig = 1;
		}else{
			$useSig = 0;
		}
		if($ipbwi->post->edit($_POST['post_id'], $_POST['post'], $useEmo, $useSig)){
			header('location: post_list.php').die();
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
	if(isset($_GET['post_id'])){
		$post = $ipbwi->post->info($_GET['post_id']);
		if($ipbwi->member->isLoggedIn()){
			$userInfo = $ipbwi->member->info();
			if($ipbwi->member->isAdmin() || $ipbwi->member->isSuperMod() || ($post['author_id'] === $userInfo['id'])){
?>
		<form action="post_edit.php?post_id=<?php echo $_GET['post_id']; ?>" method="post">
			<div style="background-color:#FFF;border:1px solid #000;padding:10px;">
				<div style="text-align:right;"><strong><a href="<?php echo $ipbwi->getBoardVar('url'); ?>/index.php?showuser=<?php echo $post['author_id']; ?>"><?php echo $ipbwi->member->id2displayname($post['author_id']); ?></a> @ <span title="<?php echo $ipbwi->date($post['post_date']); ?>"><?php echo $ipbwi->date($post['post_date'],'%d. %B %Y'); ?></span></strong></div>
					<?php echo $ipbwi->bbcode->printTextEditor($post['post']); ?>
					<p>Enable Emoticons: <input type="checkbox" name="useEmo" value="1" <?php echo (isset($post['use_emo']) && $post['use_emo'] == 1) ? ' checked="checked"' : false; ?> /></p>
					<p>Enable Signature: <input type="checkbox" name="useSig" value="1" <?php echo (isset($post['use_sig']) && $post['use_sig'] == 1) ? ' checked="checked"' : false; ?> /></p>
					<input type="hidden" name="post_id" value="<?php echo $_GET['post_id']; ?>" />
					<div style="text-align:right;"><input type="submit" name="edit_post" value="Update this post" /></div>
				</div>
			</div>
		</form>
<?php
			}else{
				echo '<p>You are not the post-author, so you need moderator or admin rights to edit this post.</p>';
			}
		}else{
			echo '<p>You have to be logged in to view this example</p>';
		}
	}else{
		echo '<p>You have to deliver a post-id to view this example. <a href="post_add.php">Create one</a> and delete it from <a href="post_list.php">post-listing-page</a>.</p>';
	}
	
echo $footer;
?>