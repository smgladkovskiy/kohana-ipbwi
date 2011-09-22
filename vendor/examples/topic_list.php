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
	$pageTitle = 'Topic List';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	$userInfo = $ipbwi->member->info();
?>
	<h2>Topics of <?php echo $forumName; ?></h2>
<?php
	$settings = array();
	if(isset($_GET['getOwnTopicsOnly']) && $ipbwi->member->isLoggedIn()){
		$settings['memberid'] = $userInfo['id'];
		echo '<p>Now there are only your topics listed.</p>';
	}elseif($ipbwi->member->isLoggedIn()){
		echo '<p><a href="?getOwnTopicsOnly=1">Click here</a> to get viewed your own topics only.</p>';
	}
	$topics = $ipbwi->topic->getList($ipbwi->forum->name2id($forumName),$settings);
	if(is_array($topics) && count($topics)>0){
		foreach($topics as $topic){
?>
	<h3><?php echo $topic['title']; ?></h3>
	<div style="background-color:#FFF;border:1px solid #000;padding:10px;margin-top:10px;">
		<?php echo $topic['post']; ?>
		<p style="font-size:10px;color:#999;">
			Post from <strong><?php echo $ipbwi->member->id2displayname($topic['author_id']); ?></strong>
			@ <span title="<?php echo $ipbwi->date($topic['start_date']); ?>"><?php echo $ipbwi->date($topic['start_date'],'%d. %B %Y'); ?></span>
			<?php
				if($ipbwi->member->isLoggedIn() && ($ipbwi->member->isAdmin() || $ipbwi->member->isSuperMod() || ($topic['author_id'] === $userInfo['id']))){
					echo '<a onclick="if(confirm(\'Really delete this topic?\')){return true;}else{return false;}" href="topic_delete.php?topic_id='.$topic['tid'].'">delete this topic</a>';
				}
			?>
		</p>
	</div>
<?php
		}
	}
	echo $footer;
?>