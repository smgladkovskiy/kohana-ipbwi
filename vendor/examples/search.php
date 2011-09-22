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
	$pageTitle		= 'Search';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['request'])){
		// search in forum IPBWI including all subforums
		$searchid = $ipbwi->search->simple($_POST['request'],array(148),0,1);
		// if site is ipb, redirect
		if($_POST['site'] == 'ipb'){
			header('location: '.$ipbwi->getBoardVar('url').'index.php?act=Search&CODE=show&searchid='.$searchid).die();
		}
		// else, go on
		if($searchid){
			$results = $ipbwi->search->results($searchid);
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if(isset($_GET['engine']) && $_GET['engine'] == 'google'){
?>
<a name="google">&nbsp;</a>
<h3>Google Search Results</h3>
<!-- Google Search Result Snippet Begins -->
<div id="googleSearchUnitIframe"></div>
<script type="text/javascript">
   var googleSearchIframeName = 'googleSearchUnitIframe';
   var googleSearchFrameWidth = 700;
   var googleSearchFrameborder = 0 ;
   var googleSearchDomain = 'www.google.com';
</script>
<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>
<!-- Google Search Result Snippet Ends -->
<?php
	}elseif(isset($_POST['site']) && $_POST['site'] == 'ipbwi' && isset($results) && is_array($results['results']) && count($results['results']) > 0){
		foreach($results['results'] as $result){
			$forumInfo = $ipbwi->forum->info($result['forum_id']);
			echo '
			<div style="border:1px dashed #000;padding:5px;margin:10px;">
				<p>
					<a href="'.$ipbwi->getBoardVar('url').'index.php?showtopic='.$result['tid'].'&view=findpost&p='.$result['pid'].'"><strong>'.$result['topic_title'].'</strong></a>
					in <a href="'.$ipbwi->getBoardVar('url').'index.php?showforum='.$result['forum_id'].'">'.$forumInfo['name'].'</a> by <a href="'.$ipbwi->getBoardVar('url').'index.php?showuser='.$result['author_id'].'">'.$result['author_name'].'</a>
					@ '.$ipbwi->date($result['post_date']).'
				</p>
			';
			$post = preg_replace('/\[attachment=(.*)\]/','',substr(strip_tags(str_replace('<br />',' ',$result['post'])),0,300));
			echo '
				<p id="post_'.$result['pid'].'">'.$post.'...</p>
			</div>
			';
		}
	}
echo $footer;
?>