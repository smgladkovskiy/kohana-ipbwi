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
	$pageTitle		= 'Statistics Live Examples';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
?>
		<h3>Versions</h3>
		<p><strong>IP.Board Version:</strong> <?php echo $ipbwi->getBoardVar('version'); ?></p>
		<p><strong>IPBWI Version:</strong> <?php echo ipbwi::VERSION; ?></p>
		<h3>Board Statistics</h3>
<?php
		$stats = $ipbwi->stats->board();
		echo '
		<p><strong>Total Posts:</strong> '.$stats['total_replies'].'</p>
		<p><strong>Total Topics:</strong> '.$stats['total_topics'].'</p>
		<p><strong>Total Members:</strong> '.$stats['mem_count'].'</p>
		<p><strong>Newest Member:</strong> '.$stats['last_mem_name'].'</p>
		<p><strong>Newest Member ID:</strong> '.$stats['last_mem_id'].'</p>
		<p><strong>Online At Once Record:</strong> '.$stats['most_count'].'</p>
		<p><strong>Online At Once Date:</strong> '.$ipbwi->date($stats['most_date']).'</p>
		';
?>
		<h3>Members Activity Statistics</h3>
<?php
		$active = $ipbwi->stats->activeCount();
		echo '
		<p><strong>anonymous online:</strong> '.$active['anon'].'</p>
		<p><strong>guests online:</strong> '.$active['guests'].'</p>
		<p><strong>members online:</strong> '.$active['members'].'</p>
		<p><strong>total online:</strong> '.$active['total'].'</p>
		';
?>
		<h3>Today's Birthdays</h3>
<?php
		$birthday = $ipbwi->stats->birthdayMembers();
		if(count($birthday) > 0){
			foreach($birthday as $member){
				echo '<p>'.$member['members_display_name'].'</p>';
			}
		}
echo $footer;
?>