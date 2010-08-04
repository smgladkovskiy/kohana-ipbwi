<?php
	// gallery integration
	// make Latest Images
	function ipbwi_widget_latestImages(){
		// Check for required functions
		if(!function_exists('register_sidebar_widget')){
			return;
		}
		// widget options
		function ipbwi_widget_latestImages_control(){
			$options = $newoptions = get_option('ipbwi_widget_latestImages');
			if(isset($_POST['ipbwi_widget_latestImages_submit'])){
				$newoptions['ipbwi_widget_latestImages_title'] = strip_tags(stripslashes($_POST['ipbwi_widget_latestImages_title']));
				$newoptions['ipbwi_widget_latestImages_viewAll'] = strip_tags(stripslashes($_POST['ipbwi_widget_latestImages_viewAll']));
				$newoptions['ipbwi_widget_latestImages_category'] = strip_tags(stripslashes($_POST['ipbwi_widget_latestImages_category']));
				$newoptions['ipbwi_widget_latestImages_limit'] = strip_tags(stripslashes($_POST['ipbwi_widget_latestImages_limit']));

			}
			if($options != $newoptions){
				$options = $newoptions;
				update_option('ipbwi_widget_latestImages', $options);
				wp_cache_delete('ipbwi_widget_latestImages', 'widget');
			}
			$title = attribute_escape($options['ipbwi_widget_latestImages_title']);
			$viewAll = attribute_escape($options['ipbwi_widget_latestImages_viewAll']);
			$category = attribute_escape($options['ipbwi_widget_latestImages_category']);
			$limit = attribute_escape($options['ipbwi_widget_latestImages_limit']);
			echo'
			<fieldset>
				<legend>'; _e('Title:'); echo '</legend>
				<label for="ipbwi_widget_latestImages_title">
					<input class="widefat" id="ipbwi_widget_latestImages_title" name="ipbwi_widget_latestImages_title" type="text" value="'.$title.'" />
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('Link-Text for "view all images"-link:'); echo '</legend>
				<label for="ipbwi_widget_latestImages_viewAll">
					<input class="widefat" id="ipbwi_widget_latestImages_viewAll" name="ipbwi_widget_latestImages_viewAll" type="text" value="'.$viewAll.'" />
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('List latest images from...'); echo '</legend>
				<label for="ipbwi_widget_latestImages_category">
					<select class="widefat" style="width:220px;overflow:hidden;" id="ipbwi_widget_latestImages_category" name="ipbwi_widget_latestImages_category">
';
			echo '<option style="background-color:#000;color:#FFF;font-weight:bold;" value="">'; _e('All Categories'); echo '</option>';
			echo $GLOBALS['ipbwi']->gallery->getAllSubs('*','html_form','—',false,$category);
echo '
					</select>
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('Number of posts to show:'); echo '</legend>
				<label for="ipbwi_widget_latestImages_limit">
					<input style="width: 40px; text-align: center;" id="ipbwi_widget_latestImages_limit" name="ipbwi_widget_latestImages_limit" type="text" value="'.$limit.'" />
				</label>
			</fieldset>
			<input type="hidden" id="ipbwi_widget_latestImages_submit" name="ipbwi_widget_latestImages_submit" value="1" />
			<br />
			';
		}

		// widget block
		function ipbwi_widget_latestImages_output($args){
			$options = get_option('ipbwi_widget_latestImages');
			$gallery = $GLOBALS['ipbwi']->gallery->getLatestList((($options['ipbwi_widget_latestImages_category'] != '') ? $options['ipbwi_widget_latestImages_category'] : '*'),array('limit' => $options['ipbwi_widget_latestImages_limit']));
			$imgData = '<ul class="ipbwi_widget_latestImages">';
			foreach($gallery as $img){
				$imgData .= '
					<li class="ipbwi_widget_latestImages_entry">
						<div class="ipbwi_widget_latestImages_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?autocom=gallery&req=si&img='.$img['id'].'"><img src="'.(($img['file_type'] != '') ? ($GLOBALS['ipbwi']->gallery->url.$img['directory'].'/tn_'.$img['masked_file_name']) : ($GLOBALS['ipbwi']->getBoardVar('url').'style_images/ip.boardpr/win_player.gif')).'" alt="'.strip_tags($img['description']).'" title="'.strip_tags($img['description']).'" /></a></div>
						<div class="ipbwi_widget_latestImages_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?showuser='.$img['member_id'].'">'.$GLOBALS['ipbwi']->member->id2displayname($img['member_id']).'</a></div>
						<div class="ipbwi_widget_latestImages_date" title="'.$GLOBALS['ipbwi']->date($img['idate']).'">'.$GLOBALS['ipbwi']->date($img['idate'],'%d.%m.%Y').'</div>
					</li>
				';
			}
			$imgData .= '</ul>';
			echo
			$args['before_widget'].
			$args['before_title'].
			(($options['ipbwi_widget_latestImages_title'] != '') ? $options['ipbwi_widget_latestImages_title'] : 'Latest gallery images').
			$args['after_title'].
			$imgData.
			'<div class="ipbwi_widget_latestImages_more"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?autocom=gallery&req=stats&op=newest"><strong>'.(($options['ipbwi_widget_latestImages_viewAll'] != '') ? $options['ipbwi_widget_latestImages_viewAll'] : 'View all new images!').'</strong></a></div>'.
			$args['after_widget'];
		}
		$widget_ops = array('classname' => 'ipbwi_widget_latestImages', 'description' => __('Viewing a list of latest Gallery images.'));
		wp_register_sidebar_widget('ipbwi_widgetlatestImages', __('IPBWI Latest Images'), 'ipbwi_widget_latestImages_output', $widget_ops);
		wp_register_widget_control('ipbwi_widgetlatestImages', __('IPBWI Latest Images'), 'ipbwi_widget_latestImages_control');
	}

	function ipbwi_widget_imageOfTheDay_addNewTable(){
		// create table if not exists
			$sql_create = '
			CREATE TABLE IF NOT EXISTS '.ipbwi_DB_prefix.'image_of_the_day (
				img_id int(10) NOT NULL,
				img_desc text character set utf8 collate utf8_unicode_ci NOT NULL,
				img_date DATE NOT NULL,
				PRIMARY KEY (img_id)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;';
		$GLOBALS['ipbwi']->DB->query($sql_create);
	}

	// add image of the day
	function ipbwi_widget_imageOfTheDay_addNewImg(){
		if(isset($_POST['ipbwi_add_imageOfTheDay_imgID']) && intval($_POST['ipbwi_add_imageOfTheDay_year'].$_POST['ipbwi_add_imageOfTheDay_month'].$_POST['ipbwi_add_imageOfTheDay_day']) != 0){
			$date = $_POST['ipbwi_add_imageOfTheDay_year'].'-'.
			((strlen($_POST['ipbwi_add_imageOfTheDay_month']) == 1) ? '0'.$_POST['ipbwi_add_imageOfTheDay_month'] : $_POST['ipbwi_add_imageOfTheDay_month']).'-'.
			((strlen($_POST['ipbwi_add_imageOfTheDay_day']) == 1) ? '0'.$_POST['ipbwi_add_imageOfTheDay_day'] : $_POST['ipbwi_add_imageOfTheDay_day']);
			if($GLOBALS['ipbwi']->gallery->info($_POST['ipbwi_add_imageOfTheDay_imgID'])){
				$query = $GLOBALS['ipbwi']->DB->query('SELECT * FROM '.ipbwi_DB_prefix.'image_of_the_day WHERE img_id="'.$_POST['ipbwi_add_imageOfTheDay_imgID'].'" OR img_date="'.$date.'"');
				if($GLOBALS['ipbwi']->DB->getTotalRows($query) == 0){
					$GLOBALS['ipbwi']->DB->query('INSERT INTO '.ipbwi_DB_prefix.'image_of_the_day (img_id,img_desc,img_date) VALUES("'.intval($_POST['ipbwi_add_imageOfTheDay_imgID']).'","'.$_POST['ipbwi_add_imageOfTheDay_imgDesc'].'","'.$date.'") ');
					$GLOBALS['ipbwi']->addSystemMessage('Success', 'New Image was added.');
					return true;
				}else{
					while($check = $GLOBALS['ipbwi']->DB->fetch($query)){
						if($check['img_id'] == $_POST['ipbwi_add_imageOfTheDay_imgID']){
							$GLOBALS['ipbwi']->addSystemMessage('Error', 'Image ID <strong>#'.$_POST['ipbwi_add_imageOfTheDay_imgID'].'</strong> still exists');
						}
						if($check['img_date'] == $date){
							$GLOBALS['ipbwi']->addSystemMessage('Error', 'There is still an image set for date <strong>'.$_POST['ipbwi_add_imageOfTheDay_day'].'. '.$GLOBALS['ipbwi']->getLibLang('month_'.$_POST['ipbwi_add_imageOfTheDay_month']).' '.$_POST['ipbwi_add_imageOfTheDay_year'].'</strong>');
						}
					}
					return false;
				}
			}else{
				$GLOBALS['ipbwi']->addSystemMessage('Error', 'The given Image-ID does not exist.');
				return false;
			}
		}elseif(isset($_POST['ipbwi_add_imageOfTheDay_imgID'])){
			$GLOBALS['ipbwi']->addSystemMessage('Error', 'Please do not forget to set an image publishing date.');
			return false;
		}else{
			return false;
		}
	}

	// update image of the day
	function ipbwi_widget_imageOfTheDay_updateImg(){
		if(isset($_POST['ipbwi_edit_imageOfTheDay_imgID'])){
			if(isset($_POST['ipbwi_edit_imageOfTheDay_delete'])){
				$query = $GLOBALS['ipbwi']->DB->query('DELETE FROM '.ipbwi_DB_prefix.'image_of_the_day WHERE img_id="'.intval($_POST['ipbwi_edit_imageOfTheDay_imgID']).'" LIMIT 1');
				$GLOBALS['ipbwi']->addSystemMessage('Success', 'Image of the Day entry was deleted.');
				return true;
			}else{
				$date = $_POST['ipbwi_edit_imageOfTheDay_year'].'-'.
				((strlen($_POST['ipbwi_edit_imageOfTheDay_month']) == 1) ? '0'.$_POST['ipbwi_edit_imageOfTheDay_month'] : $_POST['ipbwi_edit_imageOfTheDay_month']).'-'.
				((strlen($_POST['ipbwi_edit_imageOfTheDay_day']) == 1) ? '0'.$_POST['ipbwi_edit_imageOfTheDay_day'] : $_POST['ipbwi_edit_imageOfTheDay_day']);
				$query = $GLOBALS['ipbwi']->DB->query('SELECT * FROM '.ipbwi_DB_prefix.'image_of_the_day WHERE img_date="'.$date.'"');
				$check = $GLOBALS['ipbwi']->DB->fetch($query);
				if($GLOBALS['ipbwi']->DB->getTotalRows($query) == 0 || $check['img_id'] == $_POST['ipbwi_edit_imageOfTheDay_imgID']){
					$query = $GLOBALS['ipbwi']->DB->query('UPDATE '.ipbwi_DB_prefix.'image_of_the_day SET img_desc="'.$_POST['ipbwi_edit_imageOfTheDay_imgDesc'].'", img_date="'.$date.'" WHERE img_id="'.intval($_POST['ipbwi_edit_imageOfTheDay_imgID']).'"');
					$GLOBALS['ipbwi']->addSystemMessage('Success', 'Image of the Day entry was updated.');
					return true;
				}else{
					$GLOBALS['ipbwi']->addSystemMessage('Error', 'There is still an image set for date <strong>'.$_POST['ipbwi_edit_imageOfTheDay_day'].'. '.$GLOBALS['ipbwi']->getLibLang('month_'.$_POST['ipbwi_edit_imageOfTheDay_month']).' '.$_POST['ipbwi_edit_imageOfTheDay_year'].'</strong>');
					return false;
				}
			}
		}else{
			return false;
		}
	}

	// make Image of the Day Widget
	function ipbwi_widget_imageOfTheDay(){
		// Check for required functions
		if(!function_exists('register_sidebar_widget')){
			return;
		}
		// widget options
		function ipbwi_widget_imageOfTheDay_control(){
			$options = $newoptions = get_option('ipbwi_widget_imageOfTheDay');
			if(isset($_POST['ipbwi_widget_imageOfTheDay_submit'])){
				$newoptions['ipbwi_widget_imageOfTheDay_title'] = strip_tags(stripslashes($_POST['ipbwi_widget_imageOfTheDay_title']));
				$newoptions['ipbwi_widget_imageOfTheDay_size'] = strip_tags(stripslashes($_POST['ipbwi_widget_imageOfTheDay_size']));
				$newoptions['ipbwi_widget_imageOfTheDay_customSize'] = strip_tags(stripslashes($_POST['ipbwi_widget_imageOfTheDay_customSize']));
			}
			if($options != $newoptions){
				$options = $newoptions;
				update_option('ipbwi_widget_imageOfTheDay', $options);
				wp_cache_delete('ipbwi_widget_imageOfTheDay', 'widget');
			}
			$title = attribute_escape($options['ipbwi_widget_imageOfTheDay_title']);
			$size = attribute_escape($options['ipbwi_widget_imageOfTheDay_size']);
			$customSize = attribute_escape($options['ipbwi_widget_imageOfTheDay_customSize']);
			echo'
			<fieldset>
				<legend>'; _e('Title:'); echo '</legend>
				<label for="ipbwi_widget_imageOfTheDay_title">
					<input class="widefat" id="ipbwi_widget_imageOfTheDay_title" name="ipbwi_widget_imageOfTheDay_title" type="text" value="'.$title.'" />
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('Size:'); echo '</legend>
				<label for="ipbwi_widget_imageOfTheDay_size">
					<select name="ipbwi_widget_imageOfTheDay_size">
						<option value="tn"'.($size == 'tn' ? 'selected="selected"' : '').'>Thumbnail</option>
						<option value="med"'.($size == 'med' ? 'selected="selected"' : '').'>Medium</option>
					</select><br /><br />
					<p>You are able to set the image-sizes in your IP.board admin panel at Components -> IP.Gallery -> Settings.</p>
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('Custom Size:'); echo '</legend>
				<label for="ipbwi_widget_imageOfTheDay_customSize">
					<input class="widefat" id="ipbwi_widget_imageOfTheDay_customSize" name="ipbwi_widget_imageOfTheDay_customSize" type="text" value="'.$customSize.'" />
					<p>If you want to set a custom size, you are able to define the max-width in pixels here. The image based on selected size above will be proportionaly scaled down, if needed.</p>
				</label>
			</fieldset>
			<input type="hidden" id="ipbwi_widget_imageOfTheDay_submit" name="ipbwi_widget_imageOfTheDay_submit" value="1" />
			<br />
			';
		}

		// widget block
		function ipbwi_widget_imageOfTheDay_output($args){
			$query = $GLOBALS['ipbwi']->DB->query('SELECT * FROM '.ipbwi_DB_prefix.'image_of_the_day WHERE img_date = CURDATE()');
			if($GLOBALS['ipbwi']->DB->getTotalRows($query) > 0){
				$img_otd = $GLOBALS['ipbwi']->DB->fetch($query);
				$img = $GLOBALS['ipbwi']->gallery->info($img_otd['img_id']);
				$options = get_option('ipbwi_widget_imageOfTheDay');

				// check if image needs a custom resize
				if(intval($options['ipbwi_widget_imageOfTheDay_customSize']) > 0){
					$imgServerPath = $GLOBALS['ipbwi']->getBoardVar('upload_dir').$img['directory'].'/'.(($options['ipbwi_widget_imageOfTheDay_size'] != '') ? $options['ipbwi_widget_imageOfTheDay_size'] : 'tn').'_'.$img['masked_file_name'];
					if(!file_exists($imgServerPath)){
						$imgServerPathFallBack = $GLOBALS['ipbwi']->getBoardVar('upload_dir').$img['directory'].'/'.$img['masked_file_name'];
						$imgServerPath = (file_exists($imgServerPathFallBack) ? $imgServerPathFallBack : false);
						if($imgServerPath == false){
							return false;
						}
					}
					$origSize = getimagesize($imgServerPath);
					$imgDest = $GLOBALS['ipbwi']->getBoardVar('upload_dir').'ipbwi_imageoftheday_resized.jpg';
					if(file_exists($imgDest)){
						$fileLastModified = $img_otd['img_id'].'-'.date('mdy',filemtime($imgDest));
					}else{
						$fileLastModified = false;
					}
					if($fileLastModified != $img_otd['img_id'].date('mdy',time())){
						$newHeight = round($options['ipbwi_widget_imageOfTheDay_customSize']/$origSize[0]*$origSize[1]);
						$im_p = imagecreatetruecolor($options['ipbwi_widget_imageOfTheDay_customSize'],$newHeight);
						$im = imagecopyresampled($im_p,imagecreatefromjpeg($imgServerPath),0,0,0,0,$options['ipbwi_widget_imageOfTheDay_customSize'],$newHeight,$origSize[0],$origSize[1]);
						imagejpeg($im_p,$imgDest,90);
					}
					if(file_exists($imgDest) && empty($fileLastModified)){
						$fileLastModified = $img_otd['img_id'].'-'.date('mdy',filemtime($imgDest));
					}
					$imgURL = $GLOBALS['ipbwi']->getBoardVar('upload_url').'ipbwi_imageoftheday_resized.jpg?unique='.$fileLastModified;
				}else{
					$imgURL = $GLOBALS['ipbwi']->gallery->url.$img['directory'].'/'.(($options['ipbwi_widget_imageOfTheDay_size'] != '') ? $options['ipbwi_widget_imageOfTheDay_size'] : 'tn').'_'.$img['masked_file_name'];
				}
				echo
				$args['before_widget'].
				$args['before_title'].
				(($options['ipbwi_widget_imageOfTheDay_title'] != '') ? $options['ipbwi_widget_imageOfTheDay_title'] : 'Image of the Day').
				$args['after_title'].
				'<div class="ipbwi_widget_imageOfTheDay"><div class="ipbwi_widget_imageOfTheDay_image"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?autocom=gallery&req=si&img='.$img_otd['img_id'].'"><img src="'.$imgURL.'" alt="'.strip_tags($img['description']).'" title="'.strip_tags($img['description']).'" /></a></div>'.
				'<div class="ipbwi_widget_imageOfTheDay_desc">'.$img_otd['img_desc'].'</div></div>'.
				$args['after_widget'];
			}
		}
		$widget_ops = array('classname' => 'ipbwi_widget_imageOfTheDay', 'description' => __('Views image of today.'));
		wp_register_sidebar_widget('ipbwi_widgetimageOfTheDay', __('IPBWI Image of the Day'), 'ipbwi_widget_imageOfTheDay_output', $widget_ops);
		wp_register_widget_control('ipbwi_widgetimageOfTheDay', __('IPBWI Image of the Day'), 'ipbwi_widget_imageOfTheDay_control');
	}
?>