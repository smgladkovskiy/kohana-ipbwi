<?php // Do not delete these lines
	if (isset($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');
		$loggedInUser = $GLOBALS['ipbwi']->member->info();
?>
<!-- You can start editing here. -->
	<div id="left">
		<div class="frontpage-stickypost">
<?php
	echo $GLOBALS['ipbwi']->printSystemMessages();
	if($userInfo === false){
		echo '<h2>There is no member using this loginname.</h2>';
	}else{
		// is friend of me?
		if(($myFriends = $GLOBALS['ipbwi']->member->friendsList($loggedInUser['id'])) && count($myFriends) > 0){
			foreach($myFriends as $myFriend){
				if($myFriend['friends_friend_id'] == $userInfo['id']){
					$isFriend = true;
				}
			}
		}
		// gender
		if($userInfo['pp_gender'] == 'male'){
			$gender = ' | <a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?act=members&pp_gender='.$userInfo['pp_gender'].'">male</a>';
		}elseif($userInfo['pp_gender'] == 'female'){
			$gender = ' | <a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?act=members&pp_gender='.$userInfo['pp_gender'].'">female</a>';
		}else{
			$gender = '';
		}
		// age
		if($userInfo['bday_day'] != '' && $userInfo['bday_month'] != '' && $userInfo['bday_year'] != ''){
			$now = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$bday = mktime(0,0,0,$userInfo['bday_month'],$userInfo['bday_day'],$userInfo['bday_year']);
			$age = '| '.intval(($now - $bday) / (3600 * 24 * 365)).' years old';
		}else{
			$age = '';
		}
?>
			<h2><a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url').'index.php?showuser='.$userInfo['id']; ?>"><?php echo $userInfo['members_display_name']; ?></a></h2>
			<div class="frontpage-stickypost-infos">
				<?php echo intval($userInfo['posts']); ?> Posts
				| <a href="<? echo $GLOBALS['ipbwi']->getBoardVar('url').'index.php?showuser='.$userInfo['id']; ?>">Forum Profile</a>
<?php if($GLOBALS['ipbwi']->member->isLoggedIn() && $loggedInUser['name'] != $userInfo['name']){ ?>
				| <a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url').'index.php?act=Msg&CODE=4&MID=29328;'; ?>">Send Message</a>
				| <?php echo (($isFriend == false) ? '<a href="/ipbwi/user/'.$userInfo['name'].'/addfriend">Add Friend</a>' : '<a href="/ipbwi/user/'.$userInfo['name'].'/removefriend">Remove Friend</a>'); ?>
<?php } ?>
			</div>
			<div class="content">
				<div style="float:right;"><?php echo $GLOBALS['ipbwi']->member->photo($userID); ?></div>
				<div>
					<table cellspacing="0">
		<?php echo
			($userInfo['joined'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">Member Since:</td><td style="border-bottom:1px dashed #CCC;">'.$GLOBALS['ipbwi']->date($userInfo['joined'],'%d. %B %Y').'</td></tr>' : '').
			($userInfo['last_visit'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">Last Visit:</td><td style="border-bottom:1px dashed #CCC;">'.$GLOBALS['ipbwi']->date($userInfo['last_visit'],'%d. %B %Y').'</td></tr>' : '').
			($userInfo['last_activity'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">Last Activity:</td><td style="border-bottom:1px dashed #CCC;">'.$GLOBALS['ipbwi']->date($userInfo['last_activity'],'%d. %B %Y').'</td></tr>' : '').
			($userInfo['last_post'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">Last Post:</td><td style="border-bottom:1px dashed #CCC;">'.$GLOBALS['ipbwi']->date($userInfo['last_post'],'%d. %B %Y').'</td></tr>' : '').
			($userInfo['msnname'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">MSN:</td><td style="border-bottom:1px dashed #CCC;">'.$userInfo['msnname'].'</td></tr>' : '').
			($userInfo['icq_number'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">ICQ:</td><td style="border-bottom:1px dashed #CCC;">'.$userInfo['icq_number'].'</td></tr>' : '').
			($userInfo['yahoo'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">Yahoo:</td><td style="border-bottom:1px dashed #CCC;">'.$userInfo['yahoo'].'</td></tr>' : '').
			($userInfo['aim_name'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">AIM:</td><td style="border-bottom:1px dashed #CCC;">'.$userInfo['aim_name'].'</td></tr>' : '')
		; ?>
					</table>
				</div>
				<div style="clear:both;"><h3>About me</h3></div>
				<table>
<?php
if($userInfo['signature'] || $userInfo['interests'] || $userInfo['pp_bio_content']){
echo
		($userInfo['signature'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">Signature:</td><td style="border-bottom:1px dashed #CCC;"><div style="overflow:auto;">'.$userInfo['signature'].'</div></td></tr>' : '').
		($userInfo['interests'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">Interests:</td><td style="border-bottom:1px dashed #CCC;">'.$userInfo['interests'].'</td></tr>' : '').
		($userInfo['pp_bio_content'] ? '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">Bio:</td><td style="border-bottom:1px dashed #CCC;">'.$userInfo['pp_bio_content'].'</td></tr>' : '')
;
}else{
	echo '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;" colspan="2">No data</td></tr>';
}
	$fields = $GLOBALS['ipbwi']->member->listCustomFields();
	if(isset($fields) && is_array($fields) && count($fields) > 0){
		foreach($fields as $field){
			echo '<tr><td style="padding:5px;border-bottom:1px dashed #CCC;">'.$field['pf_title'].':</td><td style="border-bottom:1px dashed #CCC;">'.$GLOBALS['ipbwi']->member->customFieldValue($field['pf_id'],$userID).'</td></tr>';
		}
	}
?>
				</table>
<?php
	$friends = $GLOBALS['ipbwi']->member->friendsList($userInfo['id']);
	$i = 0;
	if(is_array($friends) && count($friends) > 0){
		echo '<h3>My Friends</h3><table cellspacing="20" style="text-align:center;"><tr>';
		foreach($friends as $friend){
			echo (($i == 2) ? '<tr>' : '').'<td style="background-color:#EEE;border:1px solid #000;padding:10px;vertical-align:top;">
			<p><a href="'.ipbwi_WEB_URL.'ipbwi/user/'.$GLOBALS['ipbwi']->member->id2name($friend['friends_friend_id']).'">'.$GLOBALS['ipbwi']->member->id2displayname($friend['friends_friend_id']).'</a></p>
			'.($GLOBALS['ipbwi']->member->photo($friend['friends_friend_id']) ? $GLOBALS['ipbwi']->member->photo($friend['friends_friend_id']) : '<img src="'.$GLOBALS['ipbwi']->getBoardVar('url').'style_images/ip.boardpr/folder_profile_portal/pp-blank-thumb.png" alt="No Photo available" />').'
			</td>'.(($i == 1) ? '</tr>' : '');

			if($i == 1){
				$i = 0;
			}else{
				$i++;
			}
		}
		echo '</tr></table>';
	}
	// latest topics
	$topics = $GLOBALS['ipbwi']->topic->getList('*',array('memberid' => $userInfo['id'],'limit' => 5));
	if(is_array($topics) && count($topics) > 0){
		echo '<h3>My new forumtopics</h3><ul>';
		foreach($topics as $topic){
			echo '<li style="padding:5px;border-bottom:1px dashed #CCC;"><strong><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?showtopic='.$topic['tid'].'">'.$topic['title'].'</strong></a>'.($topic['description'] ? '<br />'.$topic['description'] : '').'</li>';
		}
		echo '</ul>';
	}

	// latest images
	$images = $GLOBALS['ipbwi']->gallery->getLatestList('*',array('memberid' => $userInfo['id'],'limit' => 12));
	if(is_array($images) && count($images) > 0){
		$i = 0;
		echo '<h3>My new images</h3><table><tr>';
		foreach($images as $image){
			echo (($i == 4) ? '<tr>' : '').'<td style="padding:5px;border-bottom:1px dashed #CCC;"><strong><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?autocom=gallery&req=si&img='.$image['id'].'" title="'.$image['caption'].'"><img src="'.$GLOBALS['ipbwi']->gallery->url.$image['directory'].'/tn_'.$image['masked_file_name'].'" alt="'.strip_tags($image['caption']).'" title="'.strip_tags($image['caption']).'" /></a></td>'.(($i == 3) ? '</tr>' : '');

			if($i == 4){
				$i = 0;
			}else{
				$i++;
			}
		}
		echo '</tr></table>';
	}
?>
			</div>
			<div class="frontpage-stickypost-infos">
				<a href="<?php echo $GLOBALS['ipbwi']->getBoardVar('url'); ?>index.php?act=Members&filter=<?php echo $userInfo['mgroup']; ?>"><?php echo $userInfo['prefix'].$userInfo['g_title'].$userInfo['suffix']; ?></a>
				<?php echo $gender; ?>
				<?php echo $age; ?>
				<?php echo ($userInfo['title'] ? ' | '.$userInfo['title'] : ''); ?>
				<?php echo ($userInfo['location'] ? ' | '.$userInfo['location'] : ''); ?>
				<?php echo ($userInfo['website'] ? ' | <a href="'.$userInfo['website'].'" target="_blank">Website</a>' : ''); ?>
				<?php echo ($GLOBALS['ipbwi']->member->isAdmin() ? ' | <a href="mailto:'.$userInfo['email'].'">'.$userInfo['email'].'</a>' : ''); ?>
			</div>
<?
}
?>
		</div>
	</div>