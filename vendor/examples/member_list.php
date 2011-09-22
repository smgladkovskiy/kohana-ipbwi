<?php
	/**
	 * @desc			This file is only an example for loading IPBWI. Feel free to copy
	 * 					this code to your own website files.
	 * @copyright		2007-2010 IPBWI development team
	 * @package			liveExample
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
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
	<p>List 5 random members from board.</p>
<?php
		foreach($ipbwi->member->listRandomMembers() as $random){
			echo '<p><a href="'.$ipbwi->getBoardVar('url').'index.php?showuser='.$random['member_id'].'" title="'.$random['members_display_name'].'">'.$ipbwi->member->avatar($random['member_id']).'<br />'.$random['members_display_name'].'</a></p>'."\n";
		}
?>
	<h2>List online Members</h2>
	<p>List members who are currently online.</p>
<?php
		echo $onlineMembers = $ipbwi->member->listOnlineMembers(true,true,false);
	}else{
?>
	<h3>Please login</h3>
	<p>You have to <a href="member_login.php">login</a> to view this example</p>
<?
	}
	echo $footer;
?>