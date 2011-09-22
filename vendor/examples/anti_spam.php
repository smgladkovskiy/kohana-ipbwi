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
	$pageTitle = 'Anti Spam Protection';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_GET['renewImage']) && $_GET['renewImage'] == true){
		die($ipbwi->antispam->renewGdImage());
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();


	if(isset($_POST['spam_control'])){
		if($ipbwi->antispam->validate()){
?>
		<h2>Access Granted, you seem not to be a spambot</h2>
		<p>You typed the valid characters!</p>
<?php
		}else{
?>
		<h2>Access Denied</h2>
		<p>You typed <strong>not</strong> matching characters. Try again.</p>
<?php
		}
	}
?>
		<h2>Spam Protection</h2>
		<form action="anti_spam.php" method="post">
			<?php echo $ipbwi->antispam->getHTML('anti_spam.php?renewImage=true'); ?>
			<p><input type="submit" name="spam_control" value="Check!" /></p>
		</form>
<?php
	echo $footer;
?>