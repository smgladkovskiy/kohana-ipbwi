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

	// check if PHP version is 5 or higher
	if(version_compare(PHP_VERSION,'5.0.0','<')){
		die('<p>ERROR: You need PHP 5 or higher to use IPBWI. Your current version is '.PHP_VERSION.'</p>');
	}

	// print header
	if(eregi("(msie) ([0-9]{1,2}.[0-9]{1,3})",$_SERVER['HTTP_USER_AGENT'])) header('Content-type: text/html; charset=utf-8');
	//else header('Content-type: application/xhtml+xml; charset=utf-8');
	else header('Content-type: text/html; charset=utf-8'); // adsense fix :( oh man, google is not able to create proper webcode

	// print errors
	//error_reporting(E_ALL);
	//ini_set('display_errors',1);

	$permsCat		= array( // test category permissions
		'show' => '*',
		'read' => array(),
		'start' => array(),
		'reply' => array(),
		'upload' => array(),
		'download' => array()
	);
	$perms			= array( // test forum permissions
		'show' => '*',
		'read' => '*',
		'start' => '*',
		'reply' => '*',
		'upload' => '*',
		'download' => '*'
	);

	// common test-forum datas
	$forumName = 'IPBWI Test Forum'; // test forums name
	$forumDesc	= 'IPB Website Integration - Test Forum'; // test forums desc
	// common test-topic datas
	$topicTitle = 'IPB Website Integration - Test Topic';
	$topicDesc = 'Test Topic';
	$topicPost = 'Gratulation!

This is a Forum-Topic, created via the [url=http://ipbwi.com]IPBWI[/url]. Feel free to edit, reply or (as admin) delete this topic.

Have fun!';
	// common test-poll datas
	$pollTopicTitle = 'IPB Website Integration - Test Poll';
	$pollTopicDesc = 'Test Poll';
	$pollTopicPost = 'Gratulation!

This is a Forum-Poll, created via the [url=http://ipbwi.com]IPBWI[/url]. Feel free to vote, reply or (as admin) delete this poll.

Have fun!';

	// copy static var to dynamic to make it compatible with heredoc in header/footer file
	$webURL = ipbwi_WEB_URL;

	// print sourcecode of current page
	if(isset($_GET['show']) && $_GET['show'] == 'source'){
		ob_start();
		highlight_file($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF']);
		$source = ob_get_contents();
		$source = '<div id="source">'.$source.'</div>';
		ob_end_clean();
	}else{
		$source = false;
	}

	// check if current installed version is up2date
	function up2date(){
		$handle = @fopen(ipbwi::WEBSITE.'misc/updatecheck.php','r');
		if($handle){
			$releaseVersion = fread($handle, '1024');
			fclose ($handle);
			if($releaseVersion > ipbwi::VERSION){
				return $releaseVersion;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	include('header.inc.php');
	include('footer.inc.php');

?>
