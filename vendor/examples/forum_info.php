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
	$pageTitle		= 'Forum Info';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	// get topic datas
	$forumID = $ipbwi->forum->name2id($forumName);
	$forum = $ipbwi->forum->info($forumID);
?>
		<h2><?php echo $forum['name']; ?></h2>
		<p><strong><?php echo $forum['description']; ?></strong></p>
		<p><a href="topic_info.php">IPBWI Test Topic</a></p>
<?php echo $footer; ?>