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
	$pageTitle = 'Private Messages Live Examples';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;
?>
		<ul>
			<li><a href="pm_new.php">New PM</a></li>
			<li><a href="pm_list.php">List PMs</a></li>
			<li><a href="pm_view.php">View PM</a></li>
			<li><a href="pm_folder.php">Manage PM Folder</a></li>
		</ul>
<?php echo $footer; ?>