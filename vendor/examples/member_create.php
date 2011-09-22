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
	$pageTitle = 'Member Creation';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['action']) && $_POST['action'] == 'register'){
		if($_POST['password'] != $_POST['password_control']){
			$ipbwi->addSystemMessage('Error','You have to type the same password for control.');
		}else{
			// convert custom_fields in a custom array
			// first check, which custom fields exists
			$customFields = $ipbwi->member->listCustomFields();
			// load fielddatas
			foreach($customFields as $field){
				// get delivered field datas
				if(isset($_POST['field_'.$field['pf_id']])){
					$fieldDatas['field_'.$field['pf_id']] = $_POST['field_'.$field['pf_id']];
				}else{
					$fieldDatas['field_'.$field['pf_id']] = '';
				}
			}
			if($ipbwi->member->create($_POST['username'], $_POST['password'], $_POST['email'], $fieldDatas, true, $_POST['displayname'])){
				if($ipbwi->member->login($_POST['username'], $_POST['password'])){
					header('location: '.ipbwi_WEB_URL).die();
				}
			}
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
		<p>Your are already logged in</p>
<?php
	}else{
?>
		<form action="member_create.php" method="post">
			<table>
				<tr><td colspan="2"><div class="info"><div class="i_important">fields with * must be filled out.</div></div></td></tr>
				<tr><td>Username*</td><td><input style="width: 200px;" name="username" type="text" /></td></tr>
				<tr><td>Email*</td><td><input style="width: 200px;" name="email" type="text" /></td></tr>
				<tr><td>Password*</td><td><input style="width: 200px;" name="password" type="password" /></td></tr>
				<tr><td>Password*</td><td><input style="width: 200px;" name="password_control" type="password" /></td></tr>
				<tr><td>Display Name</td><td><input style="width: 200px;" name="displayname" type="text" /></td></tr>
				<?php echo $ipbwi->antispam->getHTML('anti_spam.php?renewImage=true'); ?>
				<tr><td colspan="2"><input name="register" value="register" type="submit" /><input name="action" value="register" type="hidden" /></td></tr>
			</table>
		</form>
<?php
	}
echo $footer;
?>