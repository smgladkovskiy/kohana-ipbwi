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
	$pageTitle = 'Manage Member Friends';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['friends_del_id']) && $ipbwi->member->removeFriend($_POST['friends_del_id'])){
		$ipbwi->addSystemMessage('Success','Friend #'.$_POST['friends_del_id'].' successfully deleted.');
	}elseif(isset($_POST['add_friend']) && $ipbwi->member->addFriend($_POST['friend_id'])){
		$ipbwi->addSystemMessage('Success','Member #'.$_POST['friend_id'].' successfully added to friendslist.');
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
	<h3>Your Friends</h3>
		<table style="width:500px;border:1px solid #000;background-color:#FFF;" border="1">
		<tr><th>Photograph</th><th>Added</th><th>Remove?</th></tr>
<?php
		// list friends
		$friends = $ipbwi->member->friendsList(false,true);
		foreach($friends as $friend){
echo '
			<tr><th colspan="3">'.$ipbwi->member->id2displayname($friend['details']['id']).'</th></tr>
			<tr>
				<td>'.$ipbwi->member->photo($friend['details']['id']).'</td>
				<td>'.$ipbwi->date($friend['friends_added']).'</td>
				<td><form method="post"><input type="hidden" name="friends_del_id" value="'.$friend['details']['id'].'" /><input type="submit" name="remove_friend" value="Now!" /></form></td>
			</tr>
';
		}
?>
		</table>
		<form method="post">
			<h3>Add new Friend</h3>
			<p>Insert Member-ID: <input type="text" name="friend_id" /> <input type="submit" name="add_friend" value="Add new friend" /></p>
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