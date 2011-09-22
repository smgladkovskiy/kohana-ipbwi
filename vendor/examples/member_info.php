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
	$pageTitle = 'get Advanced Member Info';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
		$member = $ipbwi->member->info();
?>
	<table style="width:100%;">
		<tr><td style="width:30%;"><p><?php echo $ipbwi->member->avatar(); ?></p></td><td style="width:70%;"><p><?php echo $ipbwi->member->photo(); ?></p></td></tr>
		<tr><td><p><strong>Display Name</strong></p></td><td><p><?php echo $member['members_display_name']; ?></p></td></tr>
		<tr><td><p><strong>Login Name</strong></p></td><td><p><?php echo $member['name']; ?></p></td></tr>
		<tr><td><p><strong>Member ID</strong></p></td><td><p><?php echo $member['member_id']; ?></p></td></tr>
		<tr><td><p><strong>Posts</strong></p></td><td><p><?php echo $member['posts']; ?></p></td></tr>
		<tr><td><p><strong>Email</strong></p></td><td><p><a href="mailto:<?php echo $member['email']; ?>"><?php echo $member['email'] ?></a></p></td></tr>
		<tr><td><p><strong>Signature</strong></p></td><td><div style="overflow:auto;"><p><?php echo $member['signature']; ?></p></div></td></tr>
		<tr><td><p><strong>Personal Statement</strong></p></td><td><p><?php echo $member['pp_bio_content']; ?></p></td></tr>
		<tr><td><p><strong>Last visit at</strong></p></td><td><p><?php echo $ipbwi->date($member['last_visit']); ?></p></td></tr>
		<tr><td><p><strong>Last activity at</strong></p></td><td><p><?php echo $ipbwi->date($member['last_activity']); ?></p></td></tr>
		<tr><td><p><strong>Last post at</strong></p></td><td><p><?php echo $ipbwi->date($member['last_post']); ?></p></td></tr>
		<tr><td><p><strong>Automatic Logout</strong><br />(Cookie Expire)</p></td><td><p><?php echo $ipbwi->date($member['member_login_key_expire']); ?></p></td></tr>
		<tr><td><p><strong>Member since</strong></p></td><td><p><?php echo $ipbwi->date($member['joined']); ?></p></td></tr>
<?php
		$fields = $ipbwi->member->listCustomFields();
		if(isset($fields) && is_array($fields) && count($fields) > 0){
?>
				<tr><td colspan="2"><h3>Custom Profile Fields</h3></td></tr>
<?php
			foreach($fields as $field){
				echo '<tr><td><p>'.$field['pf_title'].'</p><p style="font-size:9px;">'.$field['pf_desc'].'</p></td><td>'.$ipbwi->member->customFieldValue($field['pf_id']).'</td></tr>';
			}
		}
?>
	</table>
	<h3>Converting Functions</h3>
	<p><strong>displayname2id:</strong> converting <em><?php echo $member['members_display_name']; ?></em> to ID: <strong><?php echo $ipbwi->member->displayname2id($member['members_display_name']); ?></strong></p>
	<p><strong>id2displayname:</strong> converting <em><?php echo $member['member_id']; ?></em> to members display name: <strong><?php echo $ipbwi->member->id2displayname($member['member_id']); ?></strong></p>
	<p><strong>id2name:</strong> converting <em><?php echo $member['member_id']; ?></em> to members name: <strong><?php echo $ipbwi->member->id2name($member['member_id']); ?></strong></p>
	<p><strong>name2id:</strong> converting <em><?php echo $member['name']; ?></em> to ID: <strong><?php echo $ipbwi->member->name2id($member['name']); ?></strong></p>
	<p><strong>email2id:</strong> converting <em><?php echo $member['email']; ?></em> to ID: <strong><?php echo $ipbwi->member->email2id($member['email']); ?></strong></p>
<?php
	}else{
?>
	<h3>Please login</h3>
	<p>You have to <a href="member_login.php">login</a> to view this example</p>
<?
	}
?>
	<h3>Status Check</h3>
	<p><?php if($ipbwi->member->isLoggedIn()){ ?>You are <?php }else{ ?>You are not <?php } ?>logged in.</p>
	<p><?php if($ipbwi->member->isSuperMod()){ ?>You are a <?php }else{ ?>You are not a <?php } ?>super mod.</p>
	<p><?php if($ipbwi->member->isAdmin()){ ?>You are an <?php }else{ ?>You are not an <?php } ?>admin.</p>
	<p><strong><?php echo $ipbwi->member->numNewPosts(); ?></strong> new posts since your last visit.</p>
	<p>You have <strong><?php echo $ipbwi->member->pips(); ?></strong> pips, your Group Icon: <?php echo $ipbwi->member->icon(); ?></p>
<?php
	echo $footer;
?>