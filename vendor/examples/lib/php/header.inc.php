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

$header = <<<EOF_HEADER
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{$pageTitle} - IPBWI</title>
		<link rel="stylesheet" type="text/css" media="screen" href="{$webURL}lib/css/screen.css" />
	</head>
	<body>
		<div style="position:absolute;top:40px;right:20px;width:160px;height:600px;">
			<script type="text/javascript"><!--
				google_ad_client = "pub-9334906533208101";
				/* ipbwi.com, 160x600, Erstellt 18.09.08 */
				google_ad_slot = "0698654696";
				google_ad_width = 160;
				google_ad_height = 600;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
		</div>
		<div id="main">
			<h2>IPBWI LIVE EXAMPLES <a href="{$webURL}">(Examples Home,</a> <a href="http://ipbwi.com">IPBWI Home)</a></h2>
			<div style="margin:10px;">
				<h3 style="margin-top:0px;">Module Examples</h3>
				<table style="width:100%;">
					<tr>
						<td style="width:400px;vertical-align:top;">
							<table id="menu" style="margin:0px;padding:0px;" cellspacing="0">
								<tr>
									<td style="vertical-align:top;">
										<ul style="margin:0px;padding:0px;">
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}member.php">Member</a></li>
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}group.php">Groups</a></li>
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}bbcode.php">BBcode</a></li>
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}pm.php">PMs</a></li>
										</ul>
									</td>
									<td style="vertical-align:top;">
										<ul style="margin:0px;padding:0px;">
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}forum.php">Forums</a></li>
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}topic.php">Topics</a></li>
											<!--<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}poll.php">Polls</a></li>-->
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}post.php">Posts</a></li>
										</ul>
									</td>
									<td style="vertical-align:top;">
										<ul style="margin:0px;padding:0px;">
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}stats.php">Stats</a></li>
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}skin.php">Skins</a></li>
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}anti_spam.php">Anti Spam</a></li>
											<li style="background-color:#FFF;border:1px solid #000;padding:5px;margin-bottom:2px;"><a href="{$webURL}info.php">System Info</a></li>
										</ul>
									</td>
								</tr>
							</table>
						</td>
						<td style="vertical-align:top;">
							<div class="info" style="width:350px;margin-left:auto;">
EOF_HEADER;
if($ipbwi->member->isLoggedIn()){
	$info = $ipbwi->member->info();
	$header .= '
								<div class="i_member">
									<strong>Welcome back, '.$info['members_display_name'].'!</strong>
									<p>Member Settings</p>
									<ul>
										<li><a href="'.ipbwi_WEB_URL.'member_info.php">Profile Info</a></li>
										<li><a href="'.ipbwi_WEB_URL.'member_logout.php">Logout</a></li>
										<li><a href="'.ipbwi_WEB_URL.'member_delete.php">Delete Account</a></li>
									</ul>
								</div>
';
}else{
	$header .= '
								<div class="i_important">
									<strong>You are not logged in!</strong>
									<p><a href="'.ipbwi_WEB_URL.'member_create.php">Register</a> for free and <a href="'.ipbwi_WEB_URL.'member_login.php">login</a> to get access to all live-examples.</p>
								</div>
';
}
	$header .= '
							</div>
							<p style="border:2px solid #000;padding:10px;background-color:#FFFFAA;text-align:center;"><img src="'.ipbwi_WEB_URL.'lib/img/icons/32x32/actions/document-save.png" width="32" height="32" alt="Download" style="vertical-align:middle;" /> <a href="http://ipbwi.com/products/" style="text-decoration:none;font-weight:bold;">Download latest version</a></p>
						</td>
					</tr>
				</table>
				<h1>'.$pageTitle.'</h1>
';

?>
