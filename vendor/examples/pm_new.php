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
	$pageTitle = 'Write new PM';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['write_pm'])){
		if($ipbwi->pm->send($ipbwi->member->displayname2id($_POST['tomember']),$_POST['subject'],$_POST['post'])){
			$ipbwi->addSystemMessage('Success','PM successfully sent.');
		}
	}elseif(isset($_POST['save_pm'])){
		if($ipbwi->pm->send($ipbwi->member->displayname2id($_POST['tomember']),$_POST['subject'],$_POST['post'],false,$options=array('isDraft' => 1))){
			$ipbwi->addSystemMessage('Success','PM successfully saved.');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
		<h2>PM-Form</h2>
		<form method="post">
		<table cellspacing="10">
			<tr><td>Recipients Display Name</td><td><input type="text" name="tomember" /></td></tr>
			<tr><td>Title</td><td><input type="text" name="subject" /></td></tr>
			<tr><td style="vertical-align:top;">Your Message</td><td><?php echo $ipbwi->bbcode->printTextEditor(); ?></td></tr>
			<tr><td></td><td style="text-align:right;"><input type="submit" name="save_pm" value="Save as Draft" /><input type="submit" name="write_pm" value="Send PM" /></td></tr>
		</table>
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