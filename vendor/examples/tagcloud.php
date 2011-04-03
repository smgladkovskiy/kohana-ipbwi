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
	$pageTitle		= 'Tag Cloud Examples';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	if(isset($_POST['addtag']) && $ipbwi->member->isAdmin()){
		if($ipbwi->tagCloud->addTag($_POST['tag'],$_POST['destination'],$_POST['topicid'],$_POST['title'],$_POST['category'])){
			$ipbwi->addSystemMessage('Success', 'A new tag is added to the tag cloud');
		}
	}elseif(isset($_POST['addtag'])){
		$ipbwi->addSystemMessage('Error', 'You have to be an admin to add tags');
	}
	if(isset($_GET['delete_tag']) && $ipbwi->member->isAdmin()){
		if($ipbwi->tagCloud->deleteTag($_GET['delete_tag'])){
			$ipbwi->addSystemMessage('Success', 'Tag #'.$_GET['delete_tag'].' was successful deleted');
		}
	}elseif(isset($_GET['delete_tag'])){
		$ipbwi->addSystemMessage('Error', 'You have to be an admin to delete tags');
	}

	// Error Output
	echo $ipbwi->printSystemMessages();

	if(isset($_GET['tag'])){
?>
		<h2>List URLs of tag: <?php echo $_GET['tag']; ?></h2>
		<div style="margin:10px;padding:5px;border:1px dashed #000;">
<?php
		$data = $ipbwi->tagCloud->getTagData($_GET['tag']);
		foreach($data as $line){
			$taglist = '';
			if(isset($line['title'])){
				$title = $line['title'];
			}elseif(isset($line['tid'])){
				$title = $ipbwi->topic->id2title($line['tid']);
				$taglist = ' (<a href="?topicID='.$line['tid'].'">View all tags of this topic</a>)';
			}else{
				$title = '<em>No title or topic ID</em>';
			}
			echo '<p><a href="'.$line['destination'].'">'.$title.'</a>'.$taglist.'</p>';
		}
?>
		</div>
<?php
	}
?>
		<h2>Printing a Tag Cloud</h2>
<?php
	echo $ipbwi->tagCloud->view('IPBWI');
	if(isset($_GET['topicID'])){
?>
		<h2>List Tags</h2>
<?php
		$data = $ipbwi->tagCloud->getTagList($_GET['topicID']);
		if(is_array($data) && count($data) > 0){
			foreach($data as $line){
				echo '<br /><a href="?tag='.$line['tag'].'">'.$line['tag'].'</a>';
				if($ipbwi->member->isAdmin()){
					echo ' <a href="?delete_tag='.$line['id'].'" title="Delete this Tag (#'.$line['id'].': '.$line['tag'].')"><img src="'.ipbwi_WEB_URL.'examples/lib/img/icons/16x16/actions/process-stop.png" width="16" height="16" alt="Delete this Tag (#'.$line['id'].': '.$line['tag'].')" /></a>';
				}
			}
		}
	}
	if($ipbwi->member->isAdmin()){
?>
		<h2>Add a Tag</h2>
		<form method="post">
			<table>
				<tr><td><p>Tag Name:</p></td><td><p><input type="text" name="tag" /></p></td></tr>
				<tr><td><p>Destination:</p></td><td><p><input type="text" name="destination" /></p></td></tr>
				<tr><td><p>Topic ID (optional):</p></td><td><p><input type="text" name="topicid" /></p></td></tr>
				<tr><td><p>Title (optional):</p></td><td><p><input type="text" name="title" /></p></td></tr>
				<tr><td><p>Category (optional):</p></td><td><p><input type="text" name="category" /></p></td></tr>
			</table>
			<p><input type="hidden" name="addtag" value="1" /><input type="submit" value="Add Tag" /></p>
		</form>
<?php
	}
echo $footer;
?>