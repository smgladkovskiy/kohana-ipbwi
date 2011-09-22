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
	$pageTitle = 'List PMs of a folder';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	// get skin info
	$skinData = $ipbwi->skin->info($ipbwi->skin->id());
	$skinIMG = $ipbwi->getBoardVar('url').'public/style_images/'.$skinData['set_image_dir'].'/';

	if($ipbwi->member->isLoggedIn()){
		echo '
		<h3>Your Folders</h3>
		<div style="margin:20px 0px 20px 0px;">
		';
		// list PM folders
		$folders = $ipbwi->pm->getFolders();
		foreach($folders as $folder){
			echo '<span style="border:1px dashed #000;padding:5px;margin:1px;"><a href="?folder_id='.$folder['id'].'">'.$folder['real'].'</a> ('.$folder['count'].')</span>';
		}
		echo '</div>';
		// get pm space usage
?>
	<table style="width:300px;border:1px solid #000;background-color:#FFF;" border="1">
		<tr><td colspan="3">Your folders are <?php echo $ipbwi->pm->spaceUsage(); ?>% full</td></tr>
		<tr><td colspan="3" nowrap="nowrap" valign="middle" style="background-color:#FFFFFF;border:1px solid #D5DDE5;height:20px;"><span style="background-color:#243F5C;"><img src="<?php echo $skinIMG; ?>gradient_bg.png" alt="" height="11" width="<?php echo $ipbwi->pm->spaceUsage(); ?>%" /></span></td></tr>
		<tr><td style="vertical-align:middle;width:33%;">0%</td><td style="text-align:center;vertical-align:middle;width:33%;">50%</td><td style="text-align:right;vertical-align:middle;width:33%;">100%</td></tr>
		<tr><td colspan="3">Total: <?php echo $ipbwi->pm->numTotalPMs(); ?> Messages (<?php echo $ipbwi->pm->numNewPMs(); ?> new)</td></tr>
	</table>
<?php
		// list PMs
		if(isset($_GET['folder_id']) && $ipbwi->pm->folderExists($_GET['folder_id'])){
			echo '
			<h3>'.$ipbwi->pm->folderid2name($_GET['folder_id']).'</h3>
			<p>Total: '.$ipbwi->pm->numFolderPMs($_GET['folder_id']).' Messages</p>
			';
			$PMs = $ipbwi->pm->getList($_GET['folder_id']);
?>
	<table style="width:100%;" border="1" cellspacing="1" cellpadding="2">
		<tr><th>Topic</th><th>Starter</th><th>To</th><th>Last Message</th></tr>
		<?php
			if(is_array($PMs) && count($PMs) > 0){
				foreach($PMs as $PM){
					echo '<tr><td><a href="pm_view.php?pm_id='.$PM['mt_id'].'">'.$PM['mt_title'].'</a></td><td>'.$PM['members_display_name'].'</td><td>'.$ipbwi->member->id2displayname($PM['mt_to_member_id']).'</td><td>'.$ipbwi->date($PM['map_last_topic_reply']).'</td></tr>';
				}
			}
		?>
	</table>
<?php
		// PM folder does not exist
		}elseif(isset($_GET['folder_id']) && !$ipbwi->pm->folderExists($_GET['folder_id'])){
			echo 'The delivered PM-Folder ('.$_GET['folder_id'].') does not exist.';
		}
	}else{
?>
	<h3>Please login</h3>
	<p>You have to <a href="member_login.php">login</a> to view this example</p>
<?
	}
echo $footer;
?>