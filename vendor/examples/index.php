<?php
	/**
	 * @desc			This file is only an example for loading IPBWI. Feel free to copy
	 * 					this code to your own website files.
	 * @copyright		2007-2010 IPBWI development team
	 * @package			liveExample
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 * @version			$LastChangedDate: 2009-08-26 19:19:41 +0200 (Mi, 26 Aug 2009) $
	 * @since			2.0
	 * @web				http://ipbwi.com
	 */

	ini_set('display_errors',1);
	error_reporting(E_ALL);

	// Initialization
	$pageTitle = 'Information';
	$ipbwicore = '../ipbwi/ipbwi.inc.php';
	if(!file_exists($ipbwicore)){
		die('<p>Could not load '.$ipbwicore.'. Please check the include-path in '.$_SERVER['PHP_SELF'].'</p>');
	}else{
		require_once($ipbwicore);
	}
	if(!is_object($ipbwi) || get_class($ipbwi) != 'ipbwi'){
		die('<p>IPBWI class does not exist. Please check variable $ipbwi in '.$_SERVER['PHP_SELF'].'</p>');
	}
	require_once('lib/php/includes.inc.php');
	echo $ipbwi->printSystemMessages();

	// add test category and test-forum
	if($ipbwi->member->isAdmin() && isset($_POST['add_test_forum']) && $ipbwi->forum->name2id($forumName) === false){
		if($ipbwi->forum->create($forumName, $forumDesc, $ipbwi->forum->create('IPBWI Test Category', '', '-1', $permsCat), $perms)){
			$ipbwi->addSystemMessage('Success','<strong>'.$forumName.'</strong> successful created');
		}
	}
	// add test-topic
	if(isset($_POST['new_test_topic']) && $ipbwi->member->isAdmin() && $ipbwi->topic->title2id($topicTitle) === false && $ipbwi->forum->name2id($forumName) != false){
		// get all forum-ids which uses the given forum-name
		$forumIDs = $ipbwi->forum->name2id($forumName);
		// if more than one id is delivered, get the first one
		if(is_array($forumIDs)) $forumIDs = $forumIDs[0];
		// create a new test-topic
		if($ipbwi->topic->create($forumIDs, $topicTitle, $topicPost, $topicDesc)){
			$ipbwi->addSystemMessage('Success', '<strong>'.$topicTitle.'</strong> successful created');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
	// proper installation check
	if($ipbwi->forum->name2id($forumName) === false && $ipbwi->member->isAdmin()){ ?>
		<h2>No IPBWI-Test-Forum found</h2>
		<!--<form method="post">
			<p><input type="submit" name="add_test_forum" value="Create a new IPBWI Test Forum!" /></p>
		</form>-->
		<p>Please Create a Category "IPBWI Test Category" with a forum "IPBWI Test Forum" (both without quotes) to get live examples work. Within the next releases of IPBWI there will be functions which will do this for you. But as for the moment, you have to do this through the board's adminpanel.</p>
<?php }elseif($ipbwi->topic->title2id($topicTitle) === false && $ipbwi->member->isAdmin()){ ?>
		<h2>No IPBWI-Test-Topic found</h2>
		<!--<form method="post">
			<p><input type="submit" name="new_test_topic" value="Create new IPBWI Test Topic!" /></p>
		</form>-->
		<p>Please create now a Topic titled "IPB Website Integration - Test Topic" (without quotes) in the forum "IPBWI Test Forum" to get live examples work.</p>
<?php }elseif($ipbwi->forum->name2id($forumName) === false || $ipbwi->topic->title2id($topicTitle) === false){ ?>
		<h2>IPBWI Example - Finish the Installation Process</h2>
		<p>IPBWI Examples are not installed completed yet. Please <a href="examples/member_login.php">login</a> as admin and return back to this index-page to finish the installation-process.</p>
<?php } ?>
		<div style="width:400px;float:left;">
			<h3>About IPBWI</h3>
			<div class="info">
				<div class="i_blank">
					<p><strong>About IPBWI</strong><br />IPBWI (Invision Power Board Website Integration) allows you to create PHP applications that can interact with Invision Power Board (IPB). IPBWI contains more than 130 functions within a single class that can be used to simulate nearly all of the functions with Invision Power Board.</p>
				</div>
			</div>
			<h3>Top Sponsor</h3>
			<div class="info" style="margin-bottom:10px;">
				<div class="i_blank">
					<p><a href="http://totalenvironment.com" title="Total Environment Media - custom web and multimedia application development"><img src="lib/img/TEM-Logo.png" width="250" height="49" alt="Total Environment Media - custom web and multimedia application development" /></a></p>
					<p>Big thanks to <a href="http://totalenvironment.com" title="Total Environment Media - custom web and multimedia application development"><strong>Total Environment Media</strong></a> for making IPBWI 3 release possible.</p>
				</div>
			</div>
		</div>
		<div style="width:300px;float:left;margin-left:5px;">
<?php
if($ipbwi->member->isAdmin()){
	echo '<h3>Update-Check</h3>
	<div class="info">';
	$version = up2date();
	if($version === true){
		echo '<div class="i_up2date"><strong>Congratulation</strong><p>Your IPBWI-installation is up2date</p></div>';
	}else{
echo '
		<div class="i_outdated">
			<strong>Attention!</strong>
			<p>New IPBWI Release: <strong>'.$version.'</strong><br />
			<a href="'.ipbwi::WEBSITE.'">Go to IPBWI-Website</a></p>
		</div>
';
	}
	echo '</div>';
}
?>
			<h3>Hard Facts</h3>
			<div class="info" style="margin-bottom:10px;">
				<div class="i_applications_internet">
					<strong>Version</strong>
					<ul>
						<li><p>Version installed: <a href="http://ipbwi.com/products/"><strong><?php echo ipbwi::VERSION; ?></strong></a></p></li>
						<li><p>Compatibility: PHP 5 or higher, IPB v3.x</p></li>
						<li><p>License: <strong><a href="http://www.gnu.org/licenses/gpl.html">GPL 3.0 or higher</a></strong></p></li>
					</ul>
				</div>
			</div>
		</div>
<?php
	echo $footer;
?>