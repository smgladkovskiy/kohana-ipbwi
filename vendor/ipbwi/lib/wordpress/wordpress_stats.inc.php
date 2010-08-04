<?php
	// make Board Stats
	function ipbwi_boardStats($val){
		$stats = $GLOBALS['ipbwi']->stats->board();
		echo $stats[$val];
	}

	// make online list
	function ipbwi_onlineList(){
		echo $GLOBALS['ipbwi']->member->listOnlineMembers(true,true,false,'member_name','ASC',', ');
	}
?>