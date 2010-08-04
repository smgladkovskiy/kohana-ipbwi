<?php

	// tag cloud integration
	// make tag cloud
	function ipbwi_viewCloud(){
		// Check for required functions
		if(!function_exists('register_sidebar_widget')){
			return;
		}

		// widget options
		function ipbwi_viewCloud_control(){
			$options = $newoptions = get_option('ipbwi_widget_tagcloud');
			if(isset($_POST['ipbwi_widget_tagcloud_submit'])){
				$newoptions['ipbwi_widget_tagcloud_title'] = strip_tags(stripslashes($_POST['ipbwi_widget_tagcloud_title']));
				$newoptions['ipbwi_widget_tagcloud_category'] = strip_tags(stripslashes($_POST['ipbwi_widget_tagcloud_category']));
			}
			if($options != $newoptions){
				$options = $newoptions;
				update_option('ipbwi_widget_tagcloud', $options);
				wp_cache_delete('ipbwi_widget_tagcloud', 'widget');
			}
			$title = attribute_escape($options['ipbwi_widget_tagcloud_title']);
			$category = attribute_escape($options['ipbwi_widget_tagcloud_category']);
			echo'
			<fieldset>
				<legend>'; _e('Title:'); echo '</legend>
				<label for="ipbwi_widget_tagcloud_title">
					<input class="widefat" id="ipbwi_widget_tagcloud_title" name="ipbwi_widget_tagcloud_title" type="text" value="'.$title.'" />
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('Name of Category to show:'); echo '</legend>
				<label for="ipbwi_widget_tagcloud_category">
					<select class="widefat" id="ipbwi_widget_tagcloud_category" name="ipbwi_widget_tagcloud_category">
';
			echo '<option value="">'; _e('All Categories'); echo '</option>';
			foreach($GLOBALS['ipbwi']->tagCloud->getCategoryList() as $cat){
				echo '<option value="'.$cat.'"'.(($cat == $category) ? ' selected="selected"' : '').'>'.$cat.'</option>';
			}
echo '
					</select>
				</label>
			</fieldset>
			<input type="hidden" id="ipbwi_widget_tagcloud_submit" name="ipbwi_widget_tagcloud_submit" value="1" />
			<br />
			';
		}

		// widget block
		function ipbwi_viewCloud_output($args){
			$options = get_option('ipbwi_widget_tagcloud');
			echo
			$args['before_widget'].
			$args['before_title'].
			(($options['ipbwi_widget_tagcloud_title'] != '') ? $options['ipbwi_widget_tagcloud_title'] : 'Tag Cloud').
			$args['after_title'].
			$GLOBALS['ipbwi']->tagCloud->view((($options['ipbwi_widget_tagcloud_category'] != '') ? $options['ipbwi_widget_tagcloud_category'] : false),'/ipbwi/tag_cloud/%key%').
			$args['after_widget'];
		}
		$widget_ops = array('classname' => 'ipbwi_widget_tagcloud', 'description' => __("Viewing a Tag Cloud based on your Forum Topics."));
		wp_register_sidebar_widget('ipbwi_widget_tagcloud',__('IPBWI Tag Cloud'),'ipbwi_viewCloud_output',$widget_ops);
		wp_register_widget_control('ipbwi_widget_tagcloud', __('IPBWI Tag Cloud'), 'ipbwi_viewCloud_control' );
	}

?>