<?php
	// check if current installed version is up2date
	function up2date(){
		$text = '<p>You are currently using <a href="http://projects.pc-intern.com/index.php?showtopic=5676&amp;view=getnewpost"><strong>IPBWI '.ipbwi::VERSION.'</strong></a></p>';
		$handle = @fopen(ipbwi::WEBSITE.'misc/updatecheck.php','r');
		if($handle){
			$releaseVersion = fread($handle, '1024');
			fclose($handle);
			if($releaseVersion > ipbwi::VERSION){
				return $text.'<p><strong>New IPBWI Release: '.$releaseVersion.'</strong> =&gt; <a href="'.ipbwi::WEBSITE.'">Go to IPBWI-Website</a> / <a href="http://projects.pc-intern.com/index.php?showtopic=5676&amp;view=getnewpost">changelog</a></p>';
			}else{
				return $text.'<p><strong>Congratulation:</strong> Your IPBWI-installation is up2date</p>';
			}
		}else{
			return false;
		}
	}

	// change title
	function ipbwi_title(){
		if($GLOBALS['ipbwi_request'][2] == 'user'){
			if(isset($GLOBALS['ipbwi_request'][3]) && ($userID = $GLOBALS['ipbwi']->member->name2id($GLOBALS['ipbwi_request'][3]))){
				echo ' &laquo; '.$GLOBALS['ipbwi']->member->id2displayname($userID).' &raquo; ';
			}elseif(empty($GLOBALS['ipbwi_request'][3]) && $GLOBALS['ipbwi']->member->isLoggedIn() && $userInfo = $GLOBALS['ipbwi']->member->info()){
				echo ' &laquo; '.$userInfo['members_display_name'].' &raquo; ';
			}else{
				echo ' &laquo; Member not found &raquo; ';
			}
		}else{
			echo ' &laquo; '.$GLOBALS['ipbwi_request'][3].' &raquo; ';
		}
	}

	// Grab 404 Pages
	function ipbwi_404(){
		get_header();
		if($GLOBALS['ipbwi_request'][1] == 'ipbwi'){
			header('HTTP/1.1 200 OK');
			// Load User Profile Page
			if($GLOBALS['ipbwi_request'][2] == 'user'){
				ipbwi_show_profile((isset($GLOBALS['ipbwi_request'][3]) ? $GLOBALS['ipbwi_request'][3] : false));
			}
			// tag cloud
			if($GLOBALS['ipbwi_request'][2] == 'tag_cloud'){
				echo '<div id="content" class="narrowcolumn"><h2 class="center">'.$GLOBALS['ipbwi_request'][3].'</h2>';
		        $data = $GLOBALS['ipbwi']->tagCloud->getTagData($GLOBALS['ipbwi_request'][3]);
		        foreach($data as $line){
		            $taglist = '';
		            if(isset($line['title'])){
		                $title = $line['title'];
		            }elseif(isset($line['tid'])){
		                $title = $GLOBALS['ipbwi']->topic->id2title($line['tid']);
		                $taglist = ' (<a href="?topicID='.$line['tid'].'">View all tags of this topic</a>)';
		            }else{
		                $title = '<em>No title or topic ID</em>';
		            }
		            echo '<p><a href="'.$line['destination'].'">'.$title.'</a>'.$taglist.'</p>';
		        }
		        echo '</div>';
			}
		}else{
			echo '<div id="content" class="narrowcolumn"><h2 class="center">'; _e('Error 404 - Not Found', 'kubrick'); echo '</h2></div>';
		}
		get_sidebar();
		get_footer();
	}

	// wordpress admin menus
	function ipbwi_toplevel_page() {
	$path = ipbwi_ROOT_PATH.'lib/lang/';
	$dir = opendir($path);
	$langFiles = '<select name="ipbwi_lang">';
	while($file=readdir($dir)){
		if(filetype($path.$file)!='dir'){
			$name = explode('.',$file);
			$langFiles .= '<option value="'.$name[0].'" '.((get_option('ipbwi_lang') == $name[0] || (get_option('ipbwi_lang') == '' && $name[0] == 'en')) ? 'selected="selected"' : '').'>'.$name[0].'</option>';
		}
	}
	closedir($dir);
	$langFiles .= '</select>';

	echo '
	<div class="wrap">
		<h2>Welcome to IPBWI configuration panel</h2>
		<p>Thank you for using IPBWI to connect you Wordpress installation with Invision Power Board. This is the page for core configuration of IPBWI. Please note, that these settings will overwrite your settings in the configuration file of IPBWI. However: Please be sure that the file config.inc.php located in '.ipbwi_ROOT_PATH.' still exists, because some settings are still retrieved from this file.</p>
		'.((get_option('ipbwi_board_path') != '') ? up2date() : '<p><strong>Please finish installation of IPBWI by completion of this form.</p>').'
		<form method="post" action="options.php">
			'.wp_nonce_field('update-options').'
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ipbwi_board_path">Board Path*</label></th>
					<td><input type="text" name="ipbwi_board_path" id="ipbwi_board_path" value="'.get_option('ipbwi_board_path').'" class="regular-text code" /></td>
					<td>
						<span class="setting-description">
							The full qualified filesystem path to the folder of your IPB installation.
							You must add a trailing slash.<br />
							Example path: /home/public_html/community/forums/
						</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ipbwi_db_prefix">Database Prefix</label></th>
					<td><input type="text" name="ipbwi_db_prefix" id="ipbwi_db_prefix" value="'.get_option('ipbwi_db_prefix').'" class="regular-text code" /></td>
					<td>
						<span class="setting-description">
							If you want to define another prefix for ipbwi-tables in your board\'s database,
							you are able to define it here.
						</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ipbwi_utf8">UTF-8 support</label></th>
					<td>
					<input name="ipbwi_utf8" type="checkbox" id="ipbwi_utf8" value="1" '.((get_option('ipbwi_utf8') == 1) ? 'checked="checked"' : '').' />
					</td>
					<td>
						<span class="setting-description">
							IP.board 2 does not support natively UTF-8 character encoding.
							Turn this option to true, if you want to get all output-strings
							in UTF-8 encoding, otherwise turn to false to get them in ISO encoding.
						</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ipbwi_lang">Language</label></th>
					<td>'.$langFiles.'</td>
					<td>
						<span class="setting-description">
							The Default IPBWI Language Pack.
							Language packs should be named xx.inc.php where "xx" is the
							language and be situated in the lib/lang/ folder.
							By default, this uses the "en" (English) language pack.
						</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ipbwi_captcha">Enable Captcha</label></th>
					<td>
					<input name="ipbwi_captcha" type="checkbox" id="ipbwi_captcha" value="1" '.((get_option('ipbwi_captcha') == 1) ? 'checked="checked"' : '').' />
					</td>
					<td>
						<span class="setting-description">
							Enable captcha for spam protection. This feature retrieves all necessary settings from your board configuration.
						</span>
					</td>
				</tr>
			</table>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="ipbwi_board_path,ipbwi_db_prefix,ipbwi_utf8,ipbwi_lang,ipbwi_captcha" />
			<p class="submit"><input type="submit" name="Submit" class="button-primary" /></p>
		</form>
	</div>
	';
	}

	function ipbwi_plugin_sso() {
	if(get_option('ipbwi_sso') != ''){
	echo '
	<div class="wrap">
		<h2>Single Sign On</h2>
		<h3>Important Notice: Board Redirects</h3>
		<p>The main benefit of Single Sign On is, that both databases, Wordpress and Board ones, are always sync.
		This requires that the some board-pages redirect to specific wordpress-pages to prevent outsync datafields.
		Please create a .htaccess-file in your board\'s root directory and insert the code snippet listed below to enable these redirects.
		Please note that this script needs mod-rewrite enabled on your server and requires to be updated whenever you changed your wordpress site URL.</p>
		<script type="text/javascript">
			// this js-code is from http://www.hscripts.com/scripts/JavaScript/select-div-tag.php
			// big thx to the author!
			function fnSelect(objId)
			{
			   fnDeSelect();
			   if (document.selection)
			  {
			      var range = document.body.createTextRange();
			      range.moveToElementText(document.getElementById(objId));
			      range.select();
			   }
			   else if (window.getSelection)
			   {
			      var range = document.createRange();
			      range.selectNode(document.getElementById(objId));
			      window.getSelection().addRange(range);
			   }
			}

			// function to deselect the selected contents
			function fnDeSelect() {
			   if (document.selection)
			             document.selection.empty();
			   else if (window.getSelection)
			              window.getSelection().removeAllRanges();
			}
		</script>
		<div onclick="fnSelect(\'redirectcode\')" id="redirectcode">
<pre style="height:100px;overflow:auto;border:1px solid #000;background-color:#CCC;padding:5px;">
############################################
# IPBWI Wordpress Integration
# Start Board to Wordpress Redirect Area
############################################
RewriteEngine on

# Registration
RewriteCond %{QUERY_STRING} act=Reg&CODE=00 [NC]
RewriteRule (.*) '.ipbwi_WEB_URL.'wp-login.php?action=register [L,R=301]
# Login
RewriteCond %{QUERY_STRING} act=Login&CODE=00 [NC]
RewriteRule (.*) '.ipbwi_WEB_URL.'wp-login.php [L,R=301]
# Logout
RewriteCond %{QUERY_STRING} act=Login&CODE=03 [NC]
RewriteRule (.*) '.ipbwi_WEB_URL.'wp-login.php?action=logout [L,R=301]
# Change Password
RewriteCond %{QUERY_STRING} act=UserCP&CODE=28 [NC]
RewriteRule (.*) '.ipbwi_WEB_URL.'wp-admin/profile.php [L,R=301]
# New Password
RewriteCond %{QUERY_STRING} act=Reg&CODE=10 [NC]
RewriteRule (.*) '.ipbwi_WEB_URL.'wp-login.php?action=lostpassword [L,R=301]
# Change Email
RewriteCond %{QUERY_STRING} act=UserCP&CODE=08 [NC]
RewriteRule (.*) '.ipbwi_WEB_URL.'wp-admin/profile.php [L,R=301]
# Change Display Name
RewriteCond %{QUERY_STRING} act=UserCP&CODE=dname_start [NC]
RewriteRule (.*) '.ipbwi_WEB_URL.'wp-admin/profile.php [L,R=301]
# Change Profile Data
RewriteCond %{QUERY_STRING} act=UserCP&CODE=01 [NC]
RewriteRule (.*) '.ipbwi_WEB_URL.'wp-admin/profile.php [L,R=301]
############################################
# End Board to Wordpress Redirect Area
############################################
</pre>
		</div>
';
}
echo '
		<h3>Settings</h3>
		<form method="post" action="options.php">
			'.wp_nonce_field('update-options').'
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ipbwi_sso">Enable Single Sign On</label></th>
					<td>
					<input name="ipbwi_sso" type="checkbox" id="ipbwi_sso" value="1" '.((get_option('ipbwi_sso') == 1) ? 'checked="checked"' : '').' />
					</td>
					<td>
						<span class="setting-description">
							Enable this for Single Sign On. This means, if a user login/logout/register account in wordpress, the same will be done in IP.Board. Additionally, basic wordpress profile fields will be synced to board, too. This requires that login-name and password of the user is set to the same in wordpress and IP.board.
						</span>
					</td>
				</tr>
';
	if(get_option('ipbwi_sso') != ''){
		echo '
				<tr valign="top">
					<th scope="row"><label for="ipbwi_sso_cookie_domain">Cookie Domain</label></th>
					<td><input type="text" name="ipbwi_sso_cookie_domain" id="ipbwi_sso_cookie_domain" value="'.get_option('ipbwi_sso_cookie_domain').'" class="regular-text code" /></td>
					<td>
						<span class="setting-description">
							Makes login possible on a different domain as the domain where the board is installed.
							If not set, the board\'s cookie domain will be used.
							Do not touch this setting, if you do not know how to use it.
							Please insert a dot before the domain.<br />
							Example: .domain.com<br />
							Example for subdomain: .site.domain.com
						</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ipbwi_sso_advanced_profile">Enable Advanced Profiles</label></th>
					<td>
					<input name="ipbwi_sso_advanced_profile" type="checkbox" id="ipbwi_sso_advanced_profile" value="1" '.((get_option('ipbwi_sso_advanced_profile') == 1) ? 'checked="checked"' : '').' />
					</td>
					<td>
						<span class="setting-description">
							Enable this for advanced profiles in wordpress. The wordpress profile will be enhanced with many IP.board profile fields.
						</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ipbwi_sso_custom_profile_fields">Enable Custom Profile Fields</label></th>
					<td>
					<input name="ipbwi_sso_custom_profile_fields" type="checkbox" id="ipbwi_sso_custom_profile_fields" value="1" '.((get_option('ipbwi_sso_custom_profile_fields') == 1) ? 'checked="checked"' : '').' />
					</td>
					<td>
						<span class="setting-description">
							Enable this for custom profile fields in wordpress. All custom profile fields from board will be retrieved.
						</span>
					</td>
				</tr>
';
	}
	echo '
			</table>
			<input type="hidden" name="action" value="update" />
			<input type="hidden" name="page_options" value="ipbwi_sso,ipbwi_sso_advanced_profile,ipbwi_sso_custom_profile_fields,ipbwi_sso_cookie_domain" />
			<p class="submit"><input type="submit" name="Submit" class="button-primary" /></p>
		</form>
	</div>
';
	}

	function ipbwi_plugin_topics() {
		echo '
		<div class="wrap">
			<h2>Topics</h2>
			<form method="post" action="options.php">
				'.wp_nonce_field('update-options').'
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="ipbwi_settings_widget_latestTopics">Enable Latest Topic Widget</label></th>
						<td>
						<input name="ipbwi_settings_widget_latestTopics" type="checkbox" id="ipbwi_settings_widget_latestTopics" value="1" '.((get_option('ipbwi_settings_widget_latestTopics') == 1) ? 'checked="checked"' : '').' />
						</td>
						<td>
							<span class="setting-description">
								This enables a widget viewing latest topics from forum. Please go to widget control panel to configurate and activate this widget.
							</span>
						</td>
					</tr>
				</table>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="ipbwi_settings_widget_latestTopics" />
				<p class="submit"><input type="submit" name="Submit" class="button-primary" /></p>
			</form>
		</div>
		';
	}

	function ipbwi_plugin_gallery() {
		if(get_option('ipbwi_settings_widget_imageOfTheDay') == 1){
			ipbwi_widget_imageOfTheDay_addNewTable();
			ipbwi_widget_imageOfTheDay_addNewImg();
			ipbwi_widget_imageOfTheDay_updateImg();
		}
		echo '
		<div class="wrap">
			<h2>Gallery</h2>
			'.$GLOBALS['ipbwi']->printSystemMessages().'
			<form method="post" action="options.php">
				'.wp_nonce_field('update-options').'
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="ipbwi_settings_widget_latestImages">Enable Latest Images Widget</label></th>
						<td>
						<input name="ipbwi_settings_widget_latestImages" type="checkbox" id="ipbwi_settings_widget_latestImages" value="1" '.((get_option('ipbwi_settings_widget_latestImages') == 1) ? 'checked="checked"' : '').' />
						</td>
						<td>
							<span class="setting-description">
								This enables a widget viewing latest images from gallery. Please go to widget control panel to configurate and activate this widget.
							</span>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="ipbwi_settings_widget_imageOfTheDay">Enable Image of the Day Widget</label></th>
						<td>
						<input name="ipbwi_settings_widget_imageOfTheDay" type="checkbox" id="ipbwi_settings_widget_imageOfTheDay" value="1" '.((get_option('ipbwi_settings_widget_imageOfTheDay') == 1) ? 'checked="checked"' : '').' />
						</td>
						<td>
							<span class="setting-description">
								This enables a widget viewing an image of the day from gallery. Please also go to widget control panel to configurate and activate display settings of this widget.
							</span>
						</td>
					</tr>
				</table>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="ipbwi_settings_widget_latestImages,ipbwi_settings_widget_imageOfTheDay" />
				<p class="submit"><input type="submit" name="Submit" class="button-primary" /></p>
			</form>
';
if(get_option('ipbwi_settings_widget_imageOfTheDay') == 1){
echo '
			<h3>Add a new image of the day</h3>
			<form method="post">
				<table class="form-table">
					<tr valign="top">
						<tr valign="top"><td>Image-ID</td><td><input name="ipbwi_add_imageOfTheDay_imgID" style="width:50px;" type="text" /></td></tr>
						<tr valign="top"><td>Short Description</td><td><input name="ipbwi_add_imageOfTheDay_imgDesc" style="width:300px;" type="text" /></td></tr>
						<tr valign="top"><td>Date</td>
						<td>
							<select name="ipbwi_add_imageOfTheDay_month">
								<option value="">--</option>
';
	$i = 1;
	while($i <= 12){
		echo '<option value="'.$i.'">'.$GLOBALS['ipbwi']->getLibLang('month_'.$i).'</option>';
		$i++;
	}
echo '
							</select>
							<select name="ipbwi_add_imageOfTheDay_day">
								<option value="">--</option>
';
	$i = 1;
	while($i <= 31){
		echo '<option value="'.$i.'">'.$i.'</option>';
		$i++;
	}
echo '
							</select>
							<select name="ipbwi_add_imageOfTheDay_year">
								<option value="">--</option>
';
	$i = $GLOBALS['ipbwi']->date(time(),'%Y');
	$j = $i+5;
	while($i < $j){
		echo '<option value="'.$i.'">'.$i.'</option>';
		$i++;
	}
echo '
							</select>
						</td></tr>
						<tr valign="top">
							<td colspan="2">
								<input name="ipbwi_add_imageOfTheDay" class="button-primary" type="submit" id="ipbwi_add_imageOfTheDay" />
							</td>
						</tr>
					</tr>
				</table>
			</form>
';
		$sections = array('Today\'s Image' => '=','Upcoming Images' => '>','Past Images' => '<');
		echo '<table class="form-table">';
		foreach($sections as $title => $section){
			$query = $GLOBALS['ipbwi']->DB->query('SELECT * FROM '.ipbwi_DB_prefix.'image_of_the_day WHERE img_date '.$section.' CURDATE() ORDER BY img_date ASC');
			if($GLOBALS['ipbwi']->DB->getTotalRows($query) > 0){
				echo '<th><h3>'.$title.'</h3></th>';
				while($upcoming = $GLOBALS['ipbwi']->DB->fetch($query)){
					$img = $GLOBALS['ipbwi']->gallery->info($upcoming['img_id']);
					$date = explode('-',$upcoming['img_date']);
					echo '
						<form method="post"><tr>
							<td><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?autocom=gallery&req=si&img='.$img['id'].'"><img src="'.$GLOBALS['ipbwi']->gallery->url.$img['directory'].'/tn_'.$img['masked_file_name'].'" alt="'.strip_tags($img['description']).'" title="'.strip_tags($img['description']).'" /></a></td>
							<td>Delete Entry: <input type="checkbox" name="ipbwi_edit_imageOfTheDay_delete" value="1" /></td>
							<td><input name="ipbwi_edit_imageOfTheDay_imgDesc" style="width:300px;" type="text" value="'.$upcoming['img_desc'].'" /></td>
							<td><select name="ipbwi_edit_imageOfTheDay_month">
								<option value="">--</option>
';
	$i = 1;
	while($i <= 12){
		echo '<option value="'.$i.'"'.(($date[1] == $i) ? ' selected="selected"' : '').'>'.$GLOBALS['ipbwi']->getLibLang('month_'.$i).'</option>';
		$i++;
	}
echo '
							</select>
							<select name="ipbwi_edit_imageOfTheDay_day">
								<option value="">--</option>
';
	$i = 1;
	while($i <= 31){
		echo '<option value="'.$i.'"'.(($date[2] == $i) ? ' selected="selected"' : '').'>'.$i.'</option>';
		$i++;
	}
echo '
							</select>
							<select name="ipbwi_edit_imageOfTheDay_year">
								<option value="">--</option>
';
	$i = $GLOBALS['ipbwi']->date(time(),'%Y');
	$j = $i+5;
	while($i < $j){
		echo '<option value="'.$i.'"'.(($date[0] == $i) ? ' selected="selected"' : '').'>'.$i.'</option>';
		$i++;
	}
echo '
							</select></td>
							<td><input type="hidden" name="ipbwi_edit_imageOfTheDay_imgID" value="'.$upcoming['img_id'].'" /><input name="ipbwi_edit_imageOfTheDay" class="button-primary" type="submit" id="ipbwi_edit_imageOfTheDay" /></td>
						</tr></form>
					';
				}
			}
		}
		echo '</table>';
	}
}
?>