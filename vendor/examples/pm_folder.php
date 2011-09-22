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
	$pageTitle = 'Manage PM Folder';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');
	// empty PM Folder
	if(isset($_POST['empty'])){
		foreach($_POST['empty'] as $folder){
			if(empty($_POST['keep_unread'])){
				$_POST['keep_unread'] = 0;
			}
			if($deleted = $ipbwi->pm->folderFlush($folder,$_POST['keep_unread'])){
				$ipbwi->addSystemMessage('Success','<strong>'.$deleted.'</strong> Messages deleted in Folder with ID <strong>'.$folder.'</strong>');
			}
		}
	}
	// Remove PM Folder
	if(isset($_POST['delete'])){
		foreach($_POST['delete'] as $folder){
			if($ipbwi->pm->folderDelete($folder)){
				$ipbwi->addSystemMessage('Success','Folder with ID <strong>'.$folder.'</strong> successful deleted.');
			}
		}
	}
	// Add PM Folder
	if(isset($_POST['new_folder'])){
		if($ipbwi->pm->folderAdd($_POST['name'])){
			$ipbwi->addSystemMessage('Success','Folder '.$_POST['name'].' successful created.');
		}
	}
	// Rename PM Folder
	if(isset($_POST['rename_folder'])){
		if($ipbwi->pm->folderRename($_POST['folder'],$_POST['new_name'])){
			$ipbwi->addSystemMessage('Success','Folder successful renamed to <strong>'.$_POST['new_name'].'</strong>.');
		}
	}
	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
	<h3>Your Folders</h3>
	<form method="post">
		<table style="width:500px;border:1px solid #000;background-color:#FFF;" border="1">
			<tr><th>Folder</th><th>No. PMs</th><th>Empty?</th><th>Delete?</th></tr>
<?php
		// list PM folders
		$folders = $ipbwi->pm->getFolders();
		$folderOption = '';
		if(is_array($folders) && count($folders) > 0){
			foreach($folders as $folder){
				if(empty($folder['count'])){
					$folder['count'] = false;
				}
				echo '
				<tr>
					<td>'.$folder['real'].'</td>
					<td>'.$folder['count'].'</td>
					<td><input type="checkbox" name="empty[]" value="'.$folder['id'].'" /></td>
					<td>
				';
				// delete folder
				if($folder['id'] != 'new' && $folder['id'] != 'myconvo' && $folder['id'] != 'drafts'){
					echo '<input type="checkbox" name="delete[]" value="'.$folder['id'].'" />';
				}else{
					echo '<em>impossible</em>';
				}
				echo '
					</td>
				</tr>
				';
				// grab all folders to an option list for renaming-form
				$folderOption .= '<option value="'.$folder['id'].'">'.$folder['real'].'</option>';
			}
		}
?>
		<tr><td colspan="3" style="text-align:center;"><input type="checkbox" name="keep_unread" value="1" /> Keep Unread PMs?</td><td>&nbsp;</td></tr>
		<tr><td colspan="4" style="text-align:center;"><input type="submit" name="submit_folder" value="Submit" /></td></tr>
		</table>
	</form>
	<h3>Create new Folder</h3>
	<form method="post">
		<input type="text" name="name" /><input type="submit" name="new_folder" value="Create" />
	</form>
	<h3>Rename Folder</h3>
	<form method="post">
		<select name="folder"><?php echo $folderOption; ?></select>
		<input type="text" name="new_name" /><input type="submit" name="rename_folder" value="Rename" />
	</form>
<?php
		}else{
?>
	<h3>Please login</h3>
	<p>You have to <a href="member_login.php">login</a> to view this example</p>
<?
	}
echo $footer;
?>