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
					<p><strong>Features List</strong><br />A small features-summary of this tool:</p>
					<ul>
						<li><p>Member Management (create, login, logout, view, edit, list, friends &amp; delete)</p></li>
						<li><p>Forum Management (create, view &amp; delete)</p></li>
						<li><p>Topic Management (create, view, edit, list &amp; delete)</p></li>
						<li><p>Post Management (create, view, list &amp; delete)</p></li>
						<li><p>Poll Management (create, vote &amp; view results)</p></li>
						<li><p>Private Messages Management (send, save, view, list, move, delete, contact- &amp; folder-management)</p></li>
						<li><p>Attachment Management (view (including replacing post-attachment-vars) &amp; download)</p></li>
						<li><p>Group Management (view, check, change &amp; permission control)</p></li>
						<li><p>Skin Management (change skin &amp; retrieve skin informations)</p></li>
						<li><p>Stats (Board stats including Activity &amp; Birthdays)</p></li>
						<li><p>Anti Spam Captcha check (including support for GD-based and reCaptcha based Captcha)</p></li>
						<li><p>System Info which provides core informations about your IPBWI and PHP installation</p></li>
						<li><p>Tag Cloud (generate cloud, add tag, list Urls)</p></li>
					</ul>
				</div>
			</div>
			<h3>Search via Forum-Search</h3>
			<div class="info">
				<div class="i_blank">
					<p>Maybe your problem is already solved? Try searching in manual and support-forums.</p>
					<form action="<?php echo ipbwi::WEBSITE; ?>examples/search.php" method="post">
						<table>
							<tr>
								<td><p><input type="text" name="request" id="search" onfocus="myFocus('search')" onblur="myBlur('search')" /></p></td>
								<td><p><input style="background-color:transparent;border:0px;" type="image" src="<?php echo ipbwi::WEBSITE; ?>examples/lib/img/icons/22x22/actions/system-search.png" name="search" value="Search!" /></p></td>
							</tr>
						</table>
						<p>View search results...<br />
							<input style="border:0px;background-color:transparent;" type="radio" name="site" value="ipbwi" checked="checked" /> <span class="bold">via IPBWI</span>
							<input style="border:0px;background-color:transparent;" type="radio" name="site" value="ipb" /> via IPB
						</p>
						<p><input type="hidden" name="forums" value="148" /></p>
					</form>
				</div>
			</div>
			<h3>Search via Google-Search</h3>
			<div class="info">
				<div class="i_blank">
					<!-- SiteSearch Google -->
					<form method="get" action="<?php echo ipbwi::WEBSITE; ?>examples/search.php#google">
						<p><input type="hidden" name="domains" value="ipbwi.com;projects.pc-intern.com" /></p>
						<table>
							<tr>
								<td><p><input type="text" name="q" size="15" maxlength="255" value="" id="sbi" /></p></td>
								<td><p><input style="background-color:transparent;border:0px;" type="image" src="<?php echo ipbwi::WEBSITE; ?>examples/lib/img/icons/22x22/actions/system-search.png" name="sa" value="Google Search" id="sbb" /></p></td>
							</tr>
						</table>
						<p><input type="radio" name="sitesearch" value="ipbwi.com" checked="checked" id="ss1" /> ipbwi.com</p>
						<p><input type="radio" name="sitesearch" value="projects.pc-intern.com" id="ss2" /> projects.pc-intern.com (Support Forum)</p>
						<p><input type="radio" name="sitesearch" value="" id="ss0" /> Whole Web</p>
						<p>
							<input type="hidden" name="client" value="pub-9334906533208101" />
							<input type="hidden" name="forid" value="1" />
							<input type="hidden" name="channel" value="3493826355" />
							<input type="hidden" name="ie" value="ISO-8859-1" />
							<input type="hidden" name="oe" value="ISO-8859-1" />
							<input type="hidden" name="safe" value="active" />
							<input type="hidden" name="cof" value="GALT:#000000;GL:1;DIV:#FFFFCC;VLC:000000;AH:center;BGC:FFFFCC;LBGC:FFFFCC;ALC:FF6600;LC:FF6600;T:000000;GFNT:000000;GIMP:000000;LH:50;LW:390;L:<?php echo ipbwi::WEBSITE; ?>examples/lib/img/logo_468x60_google.jpg;S:<?php echo ipbwi::WEBSITE; ?>;FORID:11" />
							<input type="hidden" name="hl" value="en" />
							<input type="hidden" name="sig" value="_TAaDUJf-J01qler" />
							<input type="hidden" name="engine" value="google" />
						</p>
					</form>
					<!-- SiteSearch Google -->
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
			<h3>Top Sponsor</h3>
			<div class="info" style="margin-bottom:10px;">
				<div class="i_blank">
					<p><a href="http://totalenvironment.com" title="Total Environment Media - custom web and multimedia application development"><img src="lib/img/TEM-Logo.png" width="250" height="49" alt="Total Environment Media - custom web and multimedia application development" /></a></p>
					<p>Big thanks to <a href="http://totalenvironment.com" title="Total Environment Media - custom web and multimedia application development"><strong>Total Environment Media</strong></a> for making IPBWI 3 release possible.</p>
				</div>
			</div>
			<h3>Hard Facts</h3>
			<div class="info" style="margin-bottom:10px;">
				<div class="i_applications_internet">
					<strong>Version</strong>
					<ul>
						<li><p>Version installed: <a href="http://projects.pc-intern.com/index.php?showtopic=5676&amp;view=getnewpost"><strong><?php echo ipbwi::VERSION; ?></strong></a></p></li>
						<li><p>Compatibility: PHP 5 or higher, IPB v3.x</p></li>
						<li><p>License: <strong><a href="http://www.gnu.org/licenses/gpl.html">GPL 3.0 or higher</a></strong></p></li>
					</ul>
				</div>
			</div>
			<div class="info" style="margin-bottom:10px;">
				<div class="i_help">
					<strong>Help</strong>
					<ul>
						<li><p><a href="<?php echo ipbwi::WEBSITE; ?>">Official Website</a></p></li>
						<li><p><a href="<?php echo ipbwi::DOCS; ?>">Official Documentation</a></p></li>
						<li><p><a href="http://projects.pc-intern.com/index.php?showtopic=7821">Small Installation Guide</a></p></li>
						<li><p><strong>Forum Support</strong></p></li>
						<li><ul>
							<li><p><a href="http://projects.pc-intern.com/index.php?showforum=149&amp;setlanguage=1&amp;langid=en">english</a> (official)</p></li>
							<li><p><a href="http://projects.pc-intern.com/index.php?showforum=149&amp;setlanguage=1&amp;langid=2">german</a> (official)</p></li>
							<li><p><a href="http://forums.invisionboard.fr/index.php?showforum=126">french</a> (unofficial)<br /> by invisionboard.fr</p></li>
						</ul></li>
					</ul>
				</div>
			</div>
			<div class="info">
				<div class="i_support">
					<strong>Support this project</strong>
					<ul>
						<li><p><strong>Sponsorship</strong> by paying for Premium-Support or for creation of new functions you need immediately. <a href="<?php echo ipbwi::WEBSITE; ?>misc/sponsors.php">Details</a></p></li>
						<li><p><strong>Send a Gift</strong> via <a href="http://amazon.de/gp/registry/wishlist/28YBVQTZM4MR0/303-7286227-7653041?reveal=unpurchased&amp;filter=all&amp;sort=priority&amp;layout=standard&amp;x=11&amp;y=12">Amazon Wishlist</a> (unfortunately german speech only). This should be used if you just want to honor the contributed time of the project leader.</p></li>
						<li><p><strong>Add a Backlink</strong> from your site to <em><?php echo ipbwi::WEBSITE; ?></em> to spread this project.</p></li>
					</ul>
				</div>
			</div>
			<h3>Subversion (SVN) Access</h3>
			<div class="info">
				<div class="i_applications_internet">
					<strong>Be up2date</strong>
					<p>If you want to get access to the latest nightly build of code, you should connect to our SVN-Server.</p>
					<p>Please be aware that on SVN released sourcecode isn't tested or valid, so be careful with using code from this location. If you decide to contribute any code to the project, SVN-Access helps you to be up2date.</p>
					<p><strong>Just use the following Login-Datas for Webbrowser- or SVN-Access.</strong></p>
					<p>URL: <a href="http://server1.pc-intern.com/svn/ipbwi_v2/">server1.pc-intern.com/svn/ipbwi_v2/</a></p>
					<p>Username: ipbwi</p>
					<p>Password: guest</p>
				</div>
			</div>
		</div>
<?php
	echo $footer;
?>