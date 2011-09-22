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
	$pageTitle = 'Post Add';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['post']) && $_POST['post'] != ''){
		if($ipbwi->post->create($ipbwi->topic->title2id($topicTitle),$_POST['post'],$_POST['useEmo'],$_POST['useSig'])){
			$ipbwi->addSystemMessage('Success','<strong>Post successful created!</strong> Click <a href="post_list.php">here</a> to view it.');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->member->isLoggedIn()){
?>
		<h2>Add a post to <?php echo $topicTitle; ?></h2>
		<script type="text/javascript" src="<?php echo ipbwi_WEB_URL; ?>lib/js/bbcode_editor.js"></script>
		<form action="post_add.php" method="post">
			<?php echo $ipbwi->bbcode->printTextEditor(); ?>
			<p class="enable_emoticons">Enable Emoticons: <input type="checkbox" name="useEmo" value="1" checked="checked" /></p>
			<p class="enable_signature">Enable Signature: <input type="checkbox" name="useSig" value="1" checked="checked" /></p>
			<p><input type="submit" name="post_add" value="Create new Post!" /></p>
		</form>
<?php
	}else{
?>
		<p><a href="member_login.php">Login</a> to add a post.</p>
<?php
	}
echo $footer;
?>