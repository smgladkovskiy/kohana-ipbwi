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
	$pageTitle = 'Member Lists';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
	<h2>List random Members</h2>
	<p>List members at least with more than one post and an existing avatar.</p>
<?php
		foreach($ipbwi->member->listRandomMembers(5,"posts!=0 AND me.avatar_location != '' AND me.avatar_location != 'noavatar'") as $random){
			echo '<p><a href="'.$ipbwi->getBoardVar('url').'index.php?showuser='.$random['id'].'" title="'.$random['members_display_name'].'">'.$ipbwi->member->avatar($random['id']).'</a></p>'."\n";
		}
?>
	<h2>List online Members</h2>
	<p>List members which are currently online.</p>
<?php
		$onlineMembers = $ipbwi->member->listOnlineMembers(1,array('orderby' => 'running_time','order' => 'asc'));
		if(isset($onlineMembers) && is_array($onlineMembers) && count($onlineMembers) > 0){
			foreach($onlineMembers as $member){
		        if(isset($i)){
		            echo ', ';
		        }
		        echo '<a href="'.$ipbwi->getBoardVar('url').'?showuser='.$member['id'].'">'.$member['prefix'].$member['members_display_name'].$member['suffix'].'</a>';
		    	$i = true;
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