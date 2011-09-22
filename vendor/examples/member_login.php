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
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	// Initialization
	$pageTitle = 'Member Login';
	require_once('../ipbwi/ipbwi.inc.php');

	if(isset($_POST['action']) && $_POST['action'] == 'login'){
		if(empty($_POST['username'])){
			$ipbwi->addSystemMessage('Error', 'You have to type an username.');
		}elseif(empty($_POST['password'])){
			$ipbwi->addSystemMessage('Error', 'You have to type a password.');
		}else{
			if(isset($_POST['setcookie'])){
				$setCookie	= true;
			}else{
				$setCookie	= false;
			}
			if(isset($_POST['anonlogin'])){
				$anonLogin	= true;
			}else{
				$anonLogin	= false;
			}
			$ipbwi->member->login($_POST['username'],$_POST['password'],$setCookie,$anonLogin);
		}
	}

	require_once('lib/php/includes.inc.php');
	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
?>
		<h2>Login-Form</h2>
<?php
	if($ipbwi->member->isLoggedIn()){
?>
		<p>Your are already logged in</p>
<?php
	}else{
?>
		<form action="member_login.php" method="post">
			<table>
				<tr>
					<td>
						<table>
							<tr><td>username</td><td><input style="width:200px;" type="text" name="username" /></td></tr>
							<tr><td>password</td><td><input style="width:200px;" type="password" name="password" /></td></tr>
						</table>
					</td><td>
						<table>
							<tr><td>remember login</td><td><input type="checkbox" name="setcookie" value="1" checked="checked" /></td></tr>
							<tr><td>anonymous login</td><td><input type="checkbox" name="anonlogin" value="1" /></td></tr>
						</table>
					</td>
				</tr>
				<tr><td colspan="2"><input type="submit" name="login" value="Login" /></td></tr>
			</table>
			<input type="hidden" name="action" value="login" />
		</form>
<?php
	}
echo $footer;
?>