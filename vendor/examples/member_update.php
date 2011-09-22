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
	ini_set('display_errors',1);
	error_reporting(E_ALL);

	// Initialization
	$pageTitle = 'Member Update';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['update'])){
			// update fields
			$ipbwi->member->updateMember($_POST);
			// convert custom_fields in a custom array
			// first check, which custom fields exists
			$customFields = $ipbwi->member->listCustomFields();
			// load fielddatas
			foreach($customFields as $field){
				// get delivered field datas
				if(isset($_POST['field_'.$field['pf_id']])){
					$ipbwi->member->updateCustomField($field['pf_id'],$_POST['field_'.$field['pf_id']]);
				}
			}
			// update password
			if(isset($_POST['new_password']) && $_POST['new_password'] != ''){
				$ipbwi->member->updatePassword($_POST['new_password'],false,$_POST['current_password']);
			}
			// update avatar
			//$ipbwi->member->updateAvatar('avatar_new',(isset($_POST['delete_avatar']) ? true : false));
			// update photo
			//$ipbwi->member->updatePhoto('photo_new',(isset($_POST['delete_photo']) ? true : false));
	}
	$member = $ipbwi->member->info();
	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
<form action="member_update.php" enctype="multipart/form-data" method="post">
	<table style="width:100%;">
		<!--<tr>
			<td style="width:30%;vertical-align:bottom;">
				<p>Avatar</p>
				<p><?php echo $ipbwi->member->avatar(); ?></p>
				<p><input size="14" type="file" name="avatar_new" /></p>
				<p>Delete Avatar: <input name="delete_avatar" value="1" type="checkbox" /></p>
			</td>
			<td style="width:70%;vertical-align:bottom;">
				<p>Photo</p>
				<p><?php echo $ipbwi->member->photo(); ?></p>
				<p><input size="14" type="file" name="photo_new" /></p>
				<p>Delete Photo: <input name="delete_photo" value="1" type="checkbox" /></p>
			</td>
		</tr>-->
		<tr><td><p><strong>Display Name</strong></p></td><td><p><input name="display_name" type="text" value="<?php echo $member['members_display_name']; ?>" /></p></td></tr>
		<tr><td><p><strong>Signature</strong></p></td><td><div style="overflow:auto;"><p><?php echo $ipbwi->bbcode->printTextEditor($member['signature'],'signature'); ?></p></div></td></tr>
		<tr><td><p><strong>About Me</strong></p></td><td><p><?php echo $ipbwi->bbcode->printTextEditor($member['pp_about_me'],'pp_about_me'); ?></p></td></tr>
		<tr>
			<td><p><strong>Birthday</strong></p></td>
			<td>
				Day: <input style="width:20px;" type="text" name="bday_day" value="<?php echo $member['bday_day']; ?>" />
				Month: <input style="width:20px;" type="text" name="bday_month" value="<?php echo $member['bday_month']; ?>" />
				Year: <input style="width:40px;" type="text" name="bday_year" value="<?php echo $member['bday_year']; ?>" />
				<select name="bday_month">
					<option value="">--</option>
<?php
	$i = 1;
	while($i <= 12){
		echo '<option value="'.$i.'"'.(($i == $member['bday_month']) ? ' selected="selected"' : '').'>'.$ipbwi->getLibLang('month_'.$i).'</option>';
		$i++;
	}
?>
				</select>
				<select name="bday_day">
					<option value="">--</option>
<?php
	$i = 1;
	while($i <= 31){
		echo '<option value="'.$i.'"'.(($i == $member['bday_day']) ? ' selected="selected"' : '').'>'.$i.'</option>';
		$i++;
	}
?>
				</select>
				<select name="bday_year">
					<option value="">--</option>
<?php
	$i = $ipbwi->date(time(),'%Y');
	while($i >= 1910){
		echo '<option value="'.$i.'"'.(($i == $member['bday_year']) ? ' selected="selected"' : '').'>'.$i.'</option>';
		$i--;
	}
?>
				</select>
			</td>
		</tr>
<?php
	$fields = $ipbwi->member->listCustomFields();
	if(is_array($fields) && count($fields) > 0){
?>
		<tr><td colspan="2"><h3>Custom Profile Fields</h3></td></tr>
<?php
		foreach($fields as $field){
			// if current custom field is an text-input-field
			if($field['pf_type'] == 'input'){
				echo '<tr><td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td><td><input name="field_'.$field['pf_id'].'" value="'.$ipbwi->member->customfieldValue($field['pf_id']).'" /></td></tr>';
			}
			// if current custom field is an text-area
			elseif($field['pf_type'] == 'textarea'){
				echo '<tr><td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td><td><textarea name="field_'.$field['pf_id'].'" rows="5" cols="30">'.$ipbwi->member->customfieldValue($field['pf_id']).'</textarea></td></tr>';
			}
			// if current custom field is an drop-down-box
			elseif($field['pf_type'] == 'drop'){
				echo '
			<tr><td>
				<p>'.$field['pf_title'].'</p>
				<p style="font-size:9px;">'.$field['pf_desc'].'</p>
			</td>
			<td>
				<select name="field_'.$field['pf_id'].'">
				';
				$fieldcontentvar = explode("|",$field['pf_content']); // split contentlines
				foreach($fieldcontentvar as $var){ // load all contentlines
					$fieldcontentset = explode('=',$var); // explode var and set
					if($ipbwi->member->customFieldValue($field['pf_id']) == $fieldcontentset[0]){
						$selected = ' selected="selected"';
					}else{
						$selected = '';
					}
					echo '<option value="'.$fieldcontentset[0].'"'.$selected.'>'.$fieldcontentset[1].'</option>';
				}
				echo '
				</select>
			</td>
		</tr>
				';
			}
		}
	}
?>
		<tr><td colspan="2"><h3>Change Password:</h3></td></tr>
		<tr><td>Enter old password:</td><td><p><input type="password" name="current_password" /></p></td></tr>
		<tr><td>Enter new password:</td><td><p><input type="password" name="new_password" /></p></td></tr>
		<tr><td>Confirm new password:</td><td><p><input type="password" name="new_password_control" /></p></td></tr>
	</table>
	<p><input type="submit" name="update" value="Update Profile" /></p>
</form>
<?php }else{ ?>
	<h3>Please login</h3>
	<p>You have to <a href="member_login.php">login</a> to view this example</p>
<?
	}
echo $footer;
?>