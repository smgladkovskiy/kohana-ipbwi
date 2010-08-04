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
	$pageTitle = 'Member Live Examples';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;
?>
		<ul>
			<li><a href="member_create.php">Member Create</a></li>
			<li><a href="member_login.php">Member Login</a></li>
			<li><a href="member_logout.php">Member Logout</a></li>
			<li><a href="member_info.php">Member Info</a></li>
			<li><a href="member_update.php">Member Update</a></li>
			<!--<li><a href="member_list.php">Member List</a></li>
			<li><a href="member_friends.php">Member Friends</a></li>-->
			<li><a href="member_delete.php">Member Delete</a></li>
		</ul>
<?php echo $footer; ?>