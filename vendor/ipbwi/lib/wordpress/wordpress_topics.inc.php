<?php

	// topic integration
	// make Latest Topics
	function ipbwi_widget_latestTopics(){
		// Check for required functions
		if(!function_exists('register_sidebar_widget')){
			return;
		}
		// widget options
		function ipbwi_widget_latestTopics_control(){
			$options = $newoptions = get_option('ipbwi_widget_latestTopics');
			if(isset($_POST['ipbwi_widget_latestTopics_submit'])){
				$newoptions['ipbwi_widget_latestTopics_title'] = strip_tags(stripslashes($_POST['ipbwi_widget_latestTopics_title']));
				$newoptions['ipbwi_widget_latestTopics_viewAll'] = strip_tags(stripslashes($_POST['ipbwi_widget_latestTopics_viewAll']));
				$newoptions['ipbwi_widget_latestTopics_category'] = strip_tags(stripslashes($_POST['ipbwi_widget_latestTopics_category']));
				$newoptions['ipbwi_widget_latestTopics_limit'] = strip_tags(stripslashes($_POST['ipbwi_widget_latestTopics_limit']));

			}
			if($options != $newoptions){
				$options = $newoptions;
				update_option('ipbwi_widget_latestTopics', $options);
				wp_cache_delete('ipbwi_widget_latestTopics', 'widget');
			}
			$title = attribute_escape($options['ipbwi_widget_latestTopics_title']);
			$viewAll = attribute_escape($options['ipbwi_widget_latestTopics_viewAll']);
			$category = attribute_escape($options['ipbwi_widget_latestTopics_category']);
			$limit = attribute_escape($options['ipbwi_widget_latestTopics_limit']);
			echo'
			<fieldset>
				<legend>'; _e('Title:'); echo '</legend>
				<label for="ipbwi_widget_latestTopics_title">
					<input class="widefat" id="ipbwi_widget_latestTopics_title" name="ipbwi_widget_latestTopics_title" type="text" value="'.$title.'" />
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('Link-Text for "view all new posts"-link:'); echo '</legend>
				<label for="ipbwi_widget_latestTopics_viewAll">
					<input class="widefat" id="ipbwi_widget_latestTopics_viewAll" name="ipbwi_widget_latestTopics_viewAll" type="text" value="'.$viewAll.'" />
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('List latest topics from...'); echo '</legend>
				<label for="ipbwi_widget_latestTopics_category">
					<select class="widefat" style="width:220px;overflow:hidden;" id="ipbwi_widget_latestTopics_category" name="ipbwi_widget_latestTopics_category">
';
			echo '<option style="background-color:#000;color:#FFF;font-weight:bold;" value="">'; _e('All Categories'); echo '</option>';
			echo $GLOBALS['ipbwi']->forum->getAllSubs('*','html_form','—',false,$category);
echo '
					</select>
				</label>
			</fieldset>
			<br />
			<fieldset>
				<legend>'; _e('Number of posts to show:'); echo '</legend>
				<label for="ipbwi_widget_latestTopics_limit">
					<input style="width: 40px; text-align: center;" id="ipbwi_widget_latestTopics_limit" name="ipbwi_widget_latestTopics_limit" type="text" value="'.$limit.'" />
				</label>
			</fieldset>
			<input type="hidden" id="ipbwi_widget_latestTopics_submit" name="ipbwi_widget_latestTopics_submit" value="1" />
			<br />
			';
		}

		// widget block
		function ipbwi_widget_latestTopics_output($args){
			$options = get_option('ipbwi_widget_latestTopics');
			$topics = $GLOBALS['ipbwi']->topic->getList((($options['ipbwi_widget_latestTopics_category'] != '') ? $options['ipbwi_widget_latestTopics_category'] : '*'),array('allsubs' => true, 'limit' => $options['ipbwi_widget_latestTopics_limit']));
			$topicData = '<ul class="ipbwi_widget_latestTopics">';
			foreach($topics as $topic){
				$topicData .= '
					<li class="ipbwi_widget_latestTopics_entry">
						<div class="ipbwi_widget_latestTopics_title"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?showtopic='.$topic['tid'].'">'.$topic['title'].'</a></div>
						<div class="ipbwi_widget_latestTopics_author"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?showuser='.$topic['starter_id'].'">'.$topic['starter_name'].'</a></div>
						<div class="ipbwi_widget_latestTopics_date" title="'.$GLOBALS['ipbwi']->date($topic['start_date']).'">'.$GLOBALS['ipbwi']->date($topic['start_date'],'%d.%m.%Y').'</div>
					</li>
				';
			}
			$topicData .= '</ul>';
			echo
			$args['before_widget'].
			$args['before_title'].
			(($options['ipbwi_widget_latestTopics_title'] != '') ? $options['ipbwi_widget_latestTopics_title'] : 'Latest forum topics').
			$args['after_title'].
			$topicData.
			(($GLOBALS['ipbwi']->member->isLoggedIn()) ? '<div class="ipbwi_widget_latestTopics_more"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?act=Search&CODE=getnew"><strong>'.(($options['ipbwi_widget_latestTopics_viewAll'] != '') ? $options['ipbwi_widget_latestTopics_viewAll'] : 'View all new posts since your last visit!').'</strong></a></div>' : '').
			$args['after_widget'];
		}
		$widget_ops = array('classname' => 'ipbwi_widget_latestTopics', 'description' => __('Viewing a list of latest forum topics.'));
		wp_register_sidebar_widget('ipbwi_widget_latestTopics', __('IPBWI Latest Topics'), 'ipbwi_widget_latestTopics_output', $widget_ops);
		wp_register_widget_control('ipbwi_widget_latestTopics', __('IPBWI Latest Topics'), 'ipbwi_widget_latestTopics_control');
	}
?>