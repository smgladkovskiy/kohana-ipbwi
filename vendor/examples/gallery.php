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
	$pageTitle		= 'Gallery Live Examples';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	echo $header;

	if($ipbwi->gallery->installed){
		$gallery = $ipbwi->gallery->getLatestList('*');
		if($gallery){
			echo '<h3>List latest Images</h3>';
			foreach($gallery as $img){
				echo '
					<ul>
						<li><a href="'.$ipbwi->getBoardVar('url').'index.php?autocom=gallery&req=si&img='.$img['id'].'"><img src="'.$ipbwi->gallery->url.$img['directory'].'/tn_'.$img['masked_file_name'].'" alt="'.$img['description'].'" title="'.$img['description'].'" /></a></li>
					</ul>
				';
			}
		}
		if($ipbwi->member->isAdmin()){
			echo '<h3>List all Categories</h3><form><select>'.$ipbwi->gallery->getAllSubs('*','html_form').'</select></form>';
		}
	}else{
?>
		<p>This live-example needs IP.gallery installed on your IP.board.</p>
<?php
	}

echo $footer;
?>