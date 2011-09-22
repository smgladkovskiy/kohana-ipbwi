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
	$pageTitle = 'View Private Message';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['delete_pm'])){
		if($ipbwi->pm->delete($_GET['pm_id'])){
			$ipbwi->addSystemMessage('Success','PM '.$_GET['pm_id'].' successfully deleted.');
		}
	}elseif(isset($_POST['move_pm'])){
		if($ipbwi->pm->move($_GET['pm_id'],$_POST['move_to'])){
			$ipbwi->addSystemMessage('Success','PM '.$_GET['pm_id'].' successfully moved.');
		}
	}elseif(isset($_POST['block_contact'])){
		if($ipbwi->pm->blockContact($_POST['block_member_id'])){
			$ipbwi->addSystemMessage('Success','Member successfully added to blocklist.');
		}
	}elseif(isset($_POST['write_pm'])){
		if($ipbwi->pm->reply($_GET['pm_id'],$_POST['post'])){
			$ipbwi->addSystemMessage('Success','PM reply successfully sent.');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
		if(empty($_GET)){
?>
	<h3>No PM-id delivered</h3>
	<p>Please select a PM from <a href="pm_list.php">PM list</a> to view it. If you do not have any PMs in your Box, use <a href="pm_new.php">PM new</a> example first.</p>
<?php
		}else{
			$topic = $ipbwi->pm->info($_GET['pm_id']);
			if(is_array($topic) && count($topic) > 0){
?>
	<form method="post">
		<table style="width:500px;border:1px solid #000;background-color:#FFF;" border="1">
		<tr><th colspan="2"><?php echo $topic[0]['mt_title']; ?></th></tr>
<?php
				foreach($topic as $pm){
?>
			<tr><th>From: <?php echo $pm['members_display_name']; ?></th><th> (@ <?php echo $ipbwi->date($pm['msg_date']); ?>)</th></tr>
			<tr><td style="vertical-align:top;"><?php echo $ipbwi->member->avatar($pm['member_id']); ?></td><td style="vertical-align:top;width:200px;overflow:auto;"><?php echo $pm['msg_post']; ?></td></tr>
<?php
				}
?>
			<tr>
				<td><input type="submit" name="delete_pm" value="Delete PM" /><input type="hidden" name="block_member_id" value="<?php echo $pm['mt_from_id']; ?>" /><input type="submit" name="block_contact" value="Block Sender" /></td>
				<td>
				<?php
					$folders = $ipbwi->pm->getFolders();
					if(count($folders) > 0){
				?>
					<select name="move_to">
						<?php
							foreach($folders as $folder){
								if($pm['map_folder_id'] != $folder['id'] && $folder['id'] != 'drafts' && $folder['id'] != 'new'){
									echo '<option value="'.$folder['id'].'">'.$folder['real'].'</option>';
								}
							}
						?>
					</select>
					<input type="submit" name="move_pm" value="Move PM" />
				<?php
					}
				?>
				</td>
			</tr>
		</table>
	</form>
	<h2>Reply on this conversation</h2>
	<form method="post">
	<table cellspacing="10">
		<tr><td style="vertical-align:top;">Your Message</td><td><?php echo $ipbwi->bbcode->printTextEditor(); ?></td></tr>
		<tr><td></td><td style="text-align:right;"><input type="submit" name="write_pm" value="Send Reply" /></td></tr>
	</table>
	</form>
<?php
			}else{
				echo '<p>PM with ID '.$_GET['pm_id'].' does not exist.</p>';
			}
		}
	}else{
?>
	<h3>Please login</h3>
	<p>You have to <a href="member_login.php">login</a> to view this example</p>
<?
	}
echo $footer;
?>