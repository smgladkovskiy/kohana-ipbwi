<?php


	// make Sitemap
	function ipbwi_generateSitemap($settings){
		if(!is_array($settings['forum_ids'])){
			$forumIDs = explode(',',$settings['forum_ids']);
		}
		$stores = $GLOBALS['ipbwi']->cache->listStores();

		if($settings['plain']){
			if(intval($stores['sitemap_google']['cs_extra']) > time()){
				$cache = $GLOBALS['ipbwi']->cache->getStoreValue('sitemap_plain');
				echo $cache;
			}else{
				$sitemap = '';
				foreach($forumIDs as $forumID){
					$forumInfo = $GLOBALS['ipbwi']->forum->info($forumID);
					if($forumInfo['name'] != ''){
						$sitemap .= 'http://pc-intern.com/'.strtolower($forumInfo['name']).'.html<br />'."\n";
						$topicList = $GLOBALS['ipbwi']->topic->getList($forumID,array('order' => 'DESC', 'orderby' => 'pid', 'start' => 0, 'limit' => 2000, 'linked' => true, 'ignoreapproval' => true));
						foreach($topicList as $topicInfo){
							$sitemap .= 'http://pc-intern.com/'.strtolower($forumInfo['name']).'-'.$topicInfo['tid'].'.html<br />'."\n";
						}
					}
				}
				$GLOBALS['ipbwi']->cache->setStoreValue('sitemap_plain', $sitemap);
				echo $sitemap;
			}
		}elseif($settings['xml'] == 'google'){
			ob_clean();
			header('Content-type: application/xhtml+xml; charset=utf-8');
			if(intval($stores['sitemap_google']['cs_extra']) > time()){
				$cache = $GLOBALS['ipbwi']->cache->getStoreValue('sitemap_google');
				echo $cache;
			}else{
				$sitemap = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.google.com/schemas/sitemap/0.84">';
				foreach($forumIDs as $forumID){
					$forumInfo = $GLOBALS['ipbwi']->forum->info($forumID);
					if($forumInfo['name'] != ''){
						$sitemap .= '<url><loc>http://pc-intern.com/'.strtolower($forumInfo['name']).'.html</loc></url>'."\n";
						$topicList = $GLOBALS['ipbwi']->topic->getList($forumID,array('order' => 'DESC', 'orderby' => 'pid', 'start' => 0, 'limit' => 2000, 'linked' => true, 'ignoreapproval' => true));
						foreach($topicList as $topicInfo){
							$sitemap .= '<url><loc>http://pc-intern.com/'.strtolower($forumInfo['name']).'-'.$topicInfo['tid'].'.html</loc></url>'."\n";
						}
					}
				}
				$sitemap .= '</urlset>';
				$GLOBALS['ipbwi']->cache->setStoreValue('sitemap_google', addslashes($sitemap));
				echo $sitemap;
			}
			die();
		}else{
			if(intval($stores['sitemap_google']['cs_extra']) > time()){
				$cache = $GLOBALS['ipbwi']->cache->getStoreValue('sitemap');
				echo $cache;
			}else{
				$sitemap = '';
				foreach($forumIDs as $forumID){
					$forumInfo = $GLOBALS['ipbwi']->forum->info($forumID);
					if($forumInfo['name'] != ''){
						$topicList = $GLOBALS['ipbwi']->topic->getList($forumID,array('order' => 'DESC', 'orderby' => 'pid', 'start' => 0, 'limit' => 2000, 'linked' => true, 'ignoreapproval' => true));
						$sitemap .= '<h3><a href="'.strtolower($forumInfo['name']).'.html">'.$forumInfo['name'].'</a></h3>'."\n";
						$sitemap .= '<ul>';
						if(count($topicList) > 0){
							foreach($topicList as $topicInfo){
								$sitemap .= '<li><a href="'.strtolower($forumInfo['name']).'-'.$topicInfo['tid'].'.html">'.$topicInfo['title'].'</a></li>'."\n";
							}
						}
						$sitemap .= '</ul>';
					}
				}
				$GLOBALS['ipbwi']->cache->setStoreValue('sitemap', addslashes($sitemap));
				echo $sitemap;
			}
		}
	}

?>