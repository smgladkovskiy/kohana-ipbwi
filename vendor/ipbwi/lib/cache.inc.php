<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2008-10-27 22:51:07 +0000 (Mo, 27 Okt 2008) $
	 * @package			cache
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 * @ignore
	 */
	class ipbwi_cache extends ipbwi {
		private $ipbwi			= null;
		private $data			= array();
		/**
		 * @desc			Loads and checks different vars when class is initiating
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function __construct($ipbwi){
			// loads common classes
			$this->ipbwi = $ipbwi;
		}
		/**
		 * @desc			Gets function results cache.
		 * @param	string	$function SDK Method who's query results have been cached
		 * @param	string	$id Key to identify a query from the function
		 * @return	mixed	Cached item or false if $key does not exist.
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function get($function, $id){
			if(array_key_exists($function, $this->data)){
				return (array_key_exists($id, $this->data[$function])) ? $this->data[$function][$id] : FALSE;
			}else{
				return false;
			}
		}
		/**
		 * @desc			Saves/Updates function results cache.
		 * @param	string	$function SDK Method who's query results have been cached
		 * @param	string	$id Key to identify a query from the function
		 * @param	string	$data Data being cached
		 * @return	bool	true
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function save($function, $id, $data){
			$this->data[$function][$id] = $data;
			return true;
		}
		/**
		 * @desc			Attempts to find some value/object in the cache for cross variable assignments.
		 * @param	string	$function SDK Method who's query results have been cached
		 * @param	string	$key Key to search for in this method's results
		 * @return	mixed	value/object whatever found in cache
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function find($function, $key){
			$data = array();
			if($this->data[$function]){
				foreach(array_keys($this->data[$function]) as $id){
					$vType = gettype($this->data[$function][$id]);
					if($vType == 'array' && isset($this->data[$function][$id][$key])){
						// find array element
						$val = &$this->data[$function][$id][$id][$key];
					}elseif($vType == 'object' && isset($this->data[$function][$id]->$key)){
						// find object property
						$val = &$this->data[$function][$id]->$key;
					}else{
						// find value
						$val = &$this->data[$function][$id];
					}
					if(isset($val)){
						$data[] = $val;
					}
					unset($val);
				}
			}
			return $data;
		}
		/**
		 * @desc			List all cache stores.
		 * @return	array	all cache store key, values and extra.
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function listStores(){
			if($cache = $this->get('listCacheStores', '1')){
				return $cache;
			}
			else{
				$this->ipbwi->ips_wrapper->DB->query('SELECT cs_key, cs_value, cs_extra FROM '.$this->ipbwi->board['sql_tbl_prefix'].'cache_store');
				$cs = array();
				while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
					$cs[$row['cs_key']] = $row;
				}
				$this->save('listCacheStores', '1', $cs);
				return $cs;
			}
		}
		/**
		 * @desc			Get the value of a cache store.
		 * @param	string	$key Key of the cache store
		 * @return	string	value of a cache store.
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function getStoreValue($key){
			$cs = $this->listStores();
			if($cs[$key]){
				return $cs[$key]['cs_value'];
			}else{
				return false;
			}
		}
		/**
		 * @desc			Sets or updates the value of a cache store.
		 * @param	string	$key Key of the cache store
		 * @param	string	$value Value to store
		 * @return	bool	true on success.
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function setStoreValue($key, $value = false){
			$cs = $this->listStores();
			if($cs[$key]){
				// Already exists so just use UPDATE
				$this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'cache_store SET cs_value="'.$value.'", cs_extra="'.(time()+86400).'" WHERE cs_key="'.$key.'"');
				if($this->ipbwi->ips_wrapper->DB->get_affected_rows()){
					// And update our cached copy
					$cs[$key] = array('cs_key' => $key,
						'cs_value' => $value,
						'cs_extra' => (time()+86400),
						);
					$this->save('listCacheStores', '1', $cs);
					return true;
				}else{
					return false;
				}
			}else{
				// Doesn't exist so use INSERT
				$this->ipbwi->ips_wrapper->DB->query('INSERT INTO '.$this->ipbwi->board['sql_tbl_prefix'].'cache_store (cs_key, cs_value, cs_extra) VALUES ("'.$key.'", "'.$value.'", "'.(time()+86400).'")');
				if($this->ipbwi->ips_wrapper->DB->get_affected_rows()){
					// And update our cached copy
					$cs[$key] = array('cs_key' => $key,
						'cs_value' => $value,
						'cs_extra' => (time()+86400),
						);
					$this->save('listCacheStores', '1', $cs);
					return true;
				}else{
					return false;
				}
			}
		}
		/**
		 * @desc			Searches the cache store.
		 * @param	mixed	$value Storage value to search
		 * @param	bool	$exactmatch Use exact matching or wildcard search
		 * @return	array	cache stores matching criteria
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function searchStore($value, $exactmatch = FALSE){
			// Do the SQL Query
			if($exactmatch){
				$this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'cache_store WHERE cs_value="'.$value.'"');
			}else{
				$this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.$this->ipbwi->board['sql_tbl_prefix'].'cache_store WHERE cs_value LIKE "%'.$value.'%"');
			}
			$cs = array();
			while($row = $this->ipbwi->ips_wrapper->DB->fetch()){
				$cs[$row['cs_key']] = $row;
			}
			return $cs;
		}
		/**
		 * @desc			Updates Forum-Cache and recounts Last-Count-Datas.
		 * @param	int		$forumID
		 * @param	array	$deleted_info An optional array with informations of deleted topic can be delivered to update the count-datas.
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function updateForum($forumID,$count=array()){
			if(empty($count['topics'])){
				$count['topics'] = 0;
			}
			if(empty($count['posts'])){
				$count['posts'] = 0;
			}
			// grab data from new latest post in forum
			$lastTopicInfo = array_values($this->ipbwi->topic->getList($forumID,array('limit' => 1,'orderby' => 'last_post')));
			// Finally update the forum
			$query = '
				UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'forums SET
				posts=posts+'.$count['posts'].',
				topics=topics+'.$count['topics'].',
				last_title="'.$lastTopicInfo[0]['title'].'",
				last_id="'.$lastTopicInfo[0]['tid'].'",
				newest_title="'.$lastTopicInfo[0]['title'].'",
				newest_id="'.$lastTopicInfo[0]['tid'].'",
				last_poster_name="'.$lastTopicInfo[0]['last_poster_name'].'",
				last_poster_id="'.$lastTopicInfo[0]['last_poster_id'].'",
				last_post="'.$lastTopicInfo[0]['last_post'].'"
				WHERE id="'.$forumID.'"';
			if($this->ipbwi->ips_wrapper->DB->query($query)
			){
				return true;
			}else{
				return false;
			}
		}
		/**
		 * @desc			Updates PMs-User-Cache.
		 * @param	int		$ownerID
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @since			2.0
		 */
		public function updatePM($ownerID){
			$ownerID = intval($ownerID);
			$folders = $this->ipbwi->pm->getFolders();
			foreach($folders as $folder){
				$this->ipbwi->ips_wrapper->DB->query('SELECT COUNT(mt_id) AS count FROM '.$this->ipbwi->board['sql_tbl_prefix'].'message_topics WHERE mt_vid_folder="'.$folder['id'].'" AND mt_owner_id="'.$ownerID.'"');
				if($message = $this->ipbwi->ips_wrapper->DB->fetch()){
					$count[$folder['id']]['count'] = $message['count'];
					$count[$folder['id']]['name'] = $folder['name'];
				}
			}
			$i = 0;
			$vdirs = '';
			foreach($count as $id => $detail){
				if($i > 0) $pipe = '|'; else $pipe = false;
				$vdirs .= $pipe.$id.':'.$detail['name'].';'.$detail['count'];
				$i++;
			}
			if($this->ipbwi->ips_wrapper->DB->query('UPDATE '.$this->ipbwi->board['sql_tbl_prefix'].'member_extra SET vdirs="'.$vdirs.'" WHERE id="'. $ownerID.'"')){
				return true;
			}else{
				return false;
			}
		}
	}
?>