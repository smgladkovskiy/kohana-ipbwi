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
	$pageTitle		= 'Skin Live Examples';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	if(isset($_POST['set_skin'])){
		if($ipbwi->skin->set($_POST['set_skin'])){
			$ipbwi->addSystemMessage('Success','Forum Skin successfully changed.');
		}
	}

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
?>
		<h3>List all Skins</h3>
		<p>
<?php
	foreach($ipbwi->skin->getList() as $skinID){
		$info = $ipbwi->skin->info($skinID);
		echo '#'.$skinID.': '.$info['set_name'].'<br />';
	}
?>
		</p>
		<h3>Set Skin</h3>
		<form action="skin.php" method="post">
			<div>
				<select name="set_skin">
<?php
	foreach($ipbwi->skin->getList() as $skinID){
		$info = $ipbwi->skin->info($skinID);
		if($skinID == 1){
			$name = $info['set_name'].' (forum\'s default)';
		}else{
			$name = $info['set_name'];
		}
		if($ipbwi->skin->id() == $skinID){
			$select = ' selected="selected"';
		}else{
			$select = false;
		}
?>
					<option value="<?php echo $skinID; ?>"<?php echo $select; ?>><?php echo $name; ?></option>
<?
	}
?>
				</select>
			</div>
			<p><input type="submit" name="login" value="Set Skin!" /></p>
		</form>
		<h3>Get CSS from selected Board-Skin</h3>
<?php

$css = $ipbwi->skin->css();
?>
		<textarea style="width:100%;height:300px;" rows="10" cols="20"><?php echo htmlentities($css['ipb_styles']); ?></textarea>
		<h3>Get all Datas from selected Skin</h3>
		<?php
			$skinData = $ipbwi->skin->info($ipbwi->skin->id());
			foreach($skinData as $name => $data){
				echo '<p><strong>'.$name.':</strong> '.htmlentities($data).'</p>';
			}
echo $footer;
?>