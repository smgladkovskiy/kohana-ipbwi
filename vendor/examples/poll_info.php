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
	$pageTitle		= 'Poll Info';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	// vote the poll
	if(isset($_POST['vote_poll'])){
		$voteData = array();
		foreach($_POST as $choice => $option){
			// arrange POST-Data and make a proper array
			if(is_array($option)){
				foreach($option as $subChoice => $subOption){
					$voteData[$subChoice] = $subOption;
				}
			}elseif(is_string($option) && $choice != 'vote_poll'){
				$choiceMultidata = explode('_',str_replace('choice_','',$choice));
				$voteData[$choiceMultidata[0]][$choiceMultidata[1]] = 1;
			}
		}
		if($ipbwi->poll->vote($ipbwi->topic->title2id($pollTopicTitle), $voteData)){
			$ipbwi->addSystemMessage('Success','<strong>Voting successful,</strong> thank you for your vote.');
		}
	// null-vote the poll
	}elseif(isset($_POST['null_vote_poll'])){
		$ipbwi->poll->nullvote($ipbwi->topic->title2id($pollTopicTitle));
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();

	if($ipbwi->topic->title2id($pollTopicTitle) === false){
?>
		<h2>No IPBWI-Test-Poll found</h2>
		<p><a href="poll_new.php">Create new IPBWI Test Poll!</a></p>
<?php
	}else{
		// get poll-topic datas
		$pollTopicID = $ipbwi->topic->title2id($pollTopicTitle);
		$pollTopic = $ipbwi->topic->info($pollTopicID);
?>
		<h2><?php echo $pollTopic['title']; ?></h2>
		<p><strong><?php echo $pollTopic['description']; ?></strong> created from <?php echo $ipbwi->member->id2displayname($pollTopic['author_id']); ?> @ <?php echo $ipbwi->date($pollTopic['start_date']); ?></p>
		<div style="background-color:#FFF;border:1px solid #000;padding:10px;"><?php echo $pollTopic['post']; ?></div>
<?php
		// get poll-vote datas
		$poll = $ipbwi->poll->info($pollTopicID);
?>
		<h3><?php echo $poll['title'] ?> (<?php echo $ipbwi->poll->totalVotes($ipbwi->poll->id2topicid($poll['pid'])); ?> Votes)</h3>
		<div style="background-color:#FFF;border:1px solid #000;padding:10px;">
			<form action="poll_info.php" method="post">
<?php
		echo '<table style="width:100%;">';
		foreach($poll['choices'] as $key => $choice){
			// print question
			echo '<tr><td colspan="3" style="padding-top:20px;"><strong>'.$choice['question'].'</strong></td></tr>';
			// print choices
			$i = 0;
			foreach($choice as $option){
				if(!$ipbwi->poll->voted($pollTopicID)){
					// print radio-button
					if($i > 1 && $choice['multi'] == 0){
						echo '<tr><td><input name="choice['.$key.']" value="'.$option['option_id'].'" class="radiobutton" type="radio" />'.$option['option_title'].'</td></tr>'."\n";
					// print checkbox if multi = 1
					}elseif($i > 1 && $choice['multi'] == 1){
						echo '<tr><td><input name="choice_'.$key.'_'.$option['option_id'].'" value="1" class="checkbox" type="checkbox" />'.$option['option_title'].'</td></tr>'."\n";
					}
				// print poll result
				}elseif($i > 1){
					echo '<tr><td style="width:50%;">'.$option['option_title'].'</td><td style="width:25%;">[<strong>'.$option['votes'].'</strong>]</td><td style="width:25%;"><img src="http://projects.pc-intern.com/style_images/1/bar_left.gif" width="4" height="11" alt="" /><img src="http://projects.pc-intern.com/style_images/1/bar.gif" width="'.$option['percentage'].'" height="11" alt="" /><img src="http://projects.pc-intern.com/style_images/1/bar_right.gif" width="4" height="11" alt="" />&nbsp;['.$option['percentage'].'%]</td></tr>'."\n";
				}
				$i++;
			}
			echo "\n\n";
		}
		echo '</table>';
		if(!$ipbwi->poll->voted($pollTopicID)){
?>
				<p style="margin-top:20px;"><input type="submit" name="vote_poll" value="Vote now!" /><input style="margin-left:50px;" type="submit" name="null_vote_poll" value="Make NULL-Vote" /></p>
<?php
		}else{
?>
				<p><strong>You have already voted in this poll.</strong></p>
<?php
		}
?>
			</form>
		</div>
<?php
	}
echo $footer;
?>