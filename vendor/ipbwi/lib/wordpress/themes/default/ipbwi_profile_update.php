<?php // Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	// get user info
	$userInfo = $GLOBALS['ipbwi']->member->info();
?>
<!-- You can start editing here. -->
	<h3>Board Settings</h3>
	<table class="form-table">
<?php
	if(get_option('ipbwi_sso_advanced_profile') != ''){
?>
		<tr>
			<th><label for="ipbwi_avatar_new">Avatar</label></th>
			<td>
				<p><?php echo $GLOBALS['ipbwi']->member->avatar(); ?></p>
				<p><input size="14" type="file" name="ipbwi_avatar_new" /></p>
				<p>Delete Avatar: <input name="ipbwi_delete_avatar" value="1" type="checkbox" /></p>
			</td>
		</tr>
		<tr>
			<th><label for="ipbwi_photo_new">Photo</label></th>
			<td>
				<p><?php echo $GLOBALS['ipbwi']->member->photo(); ?></p>
				<p><input size="14" type="file" name="ipbwi_photo_new" /></p>
				<p>Delete Photo: <input name="ipbwi_delete_photo" value="1" type="checkbox" /></p>
			</td>
		</tr>
		<tr>
			<th><label for="ipbwi_location">Location</label></th>
			<td><input name="ipbwi_location" id=ipbwi_location" value="<?php echo $userInfo['location']; ?>" class="regular-text" type="text" /></td>
		</tr>
		<tr>
			<th><label for="ipbwi_icq_number">ICQ-ID</label></th>
			<td><input name="ipbwi_icq_number" id="ipbwi_icq_number" value="<?php echo $userInfo['icq_number']; ?>" class="regular-text" type="text" /></td>
		</tr>
		<tr>
			<th><label for="ipbwi_msnname">MSN-ID</label></th>
			<td><input name="ipbwi_msnname" id="ipbwi_msnname" value="<?php echo $userInfo['msnname']; ?>" class="regular-text" type="text" /></td>
		</tr>
		<tr>
			<th><label for="ipbwi_signature">Signature</label><p>(BBcode is allowed)</p></th>
			<td><textarea name="ipbwi_signature" id="ipbwi_signature" rows="10" cols="60" class="regular-text"><?php echo $GLOBALS['ipbwi']->member->rawSig(); ?></textarea></td>
		</tr>
		<tr>
			<th><label for="ipbwi_bday">Birthday</label></th>
			<td>
				<select name="ipbwi_bday_month">
					<option value="">--</option>
<?php
	$i = 1;
	while($i <= 12){
		echo '<option value="'.$i.'"'.(($i == $userInfo['bday_month']) ? ' selected="selected"' : '').'>'.$GLOBALS['ipbwi']->getLibLang('month_'.$i).'</option>';
		$i++;
	}
?>
				</select>
				<select name="ipbwi_bday_day">
					<option value="">--</option>
<?php
	$i = 1;
	while($i <= 31){
		echo '<option value="'.$i.'"'.(($i == $userInfo['bday_day']) ? ' selected="selected"' : '').'>'.$i.'</option>';
		$i++;
	}
?>
				</select>
				<select name="ipbwi_bday_year">
					<option value="">--</option>
<?php
	$i = 1910;
	while($i <= $GLOBALS['ipbwi']->date(time(),'%Y')){
		echo '<option value="'.$i.'"'.(($i == $userInfo['bday_year']) ? ' selected="selected"' : '').'>'.$i.'</option>';
		$i++;
	}
?>
				</select>
			</td>
		</tr>
<?php
	}
	if(get_option('ipbwi_sso_custom_profile_fields') != ''){
		$fields = $GLOBALS['ipbwi']->member->listCustomFields();
		if(is_array($fields) && count($fields) > 0){
			foreach($fields as $field){
				if($field['pf_show_on_reg'] == 1){
					echo '<tr>';
					// if current custom field is an text-input-field
					if($field['pf_type'] == 'text'){ echo '<td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td><td><input name="ipbwi_field_'.$field['pf_id'].'" value="'.$GLOBALS['ipbwi']->member->customfieldValue($field['pf_id']).'" /></td>'; }
					// if current custom field is an text-area
					elseif($field['pf_type'] == 'area'){ echo '<td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td><td><textarea name="ipbwi_field_'.$field['pf_id'].'" rows="5" cols="30">'.$GLOBALS['ipbwi']->member->customfieldValue($field['pf_id']).'</textarea></td>'; }
					// if current custom field is an drop-down-box
					elseif($field['pf_type'] == 'drop'){
						echo '<td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td>';
						echo '<td><select name="ipbwi_field_'.$field['pf_id'].'">';
						$fieldcontentvar = split("[\n|]",$field['pf_content']); // split contentlines
						for($x=0;$x<count($fieldcontentvar);$x++){ // load all contentlines
							$fieldcontentset = explode('=',$fieldcontentvar[$x]); // explode var and set
							if($GLOBALS['ipbwi']->member->customFieldValue($field['pf_id']) == $fieldcontentset[1]){
								$selected = ' selected="selected"'; }else{ $selected = '';
							}
							echo '<option value="'.$fieldcontentset[0].'"'.$selected.'>'.$fieldcontentset[1].'</option>';
						}
						echo '</select></td>';
					}
					echo '</tr>';
				}
			}
		}
	}
?>
</table>