<?php
	/**
	 * @author			Matthias Reuter ($LastChangedBy: matthias $)
	 * @version			$LastChangedDate: 2008-11-02 16:54:59 +0000 (So, 02 Nov 2008) $
	 * @package			tagCloud
	 * @copyright		2007-2010 IPBWI development team
	 * @link			http://ipbwi.com/examples/stats.php
	 * @since			2.0
	 * @license			http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
	 */
	class ipbwi_tagCloud extends ipbwi {
		private $ipbwi			= null;
		/**
		 * @desc			Loads and checks different vars when class is initiating
		 * @author			Matthias Reuter
		 * @since			2.0
		 * @ignore
		 */
		public function __construct($ipbwi){
			// loads common classes
			$this->ipbwi = $ipbwi;

			// create table if not exists
			$sql_create = '
			CREATE TABLE IF NOT EXISTS '.ipbwi_DB_prefix.'tagcloud (
				id int(10) NOT NULL auto_increment,
				tag text character set utf8 collate utf8_unicode_ci NOT NULL,
				destination text character set utf8 collate utf8_unicode_ci NOT NULL,
				tid int(10) default NULL,
				title text character set utf8 collate utf8_unicode_ci,
				category text character set utf8 collate utf8_unicode_ci NOT NULL,
				PRIMARY KEY (id)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 ;';

			$this->ipbwi->ips_wrapper->DB->query($sql_create);
		}
		/**
		 * @desc			Creates a tag cloud
		 * @param	string	$category Set a Category Name, if you want to get a tagcloud from one category only.
		 * @param	string	$link define a custom link to the tags and insert %key% as var which will be replaced, default: ?tag=%key%
		 * @return	string	Tag Cloud HTML
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->tagCloud->view();
		 * $ipbwi->tagCloud->view('Category Name');
		 * $ipbwi->tagCloud->view('Category Name','/ipbwi_tagcloud_%key');
		 * </code>
		 * @since			2.0
		 */
		public function view($category=false,$link='?tag=%key%'){
			if(isset($category) && $category != ''){
				$category = ' WHERE category = "'.$category.'"';
			}
			$query = $this->ipbwi->ips_wrapper->DB->query('SELECT * from '.ipbwi_DB_prefix.'tagcloud'.$category);
			if($this->ipbwi->ips_wrapper->DB->getTotalRows($query) == 0){
				return false;
			}
			while($row = $this->ipbwi->ips_wrapper->DB->fetch($query)){
				if(empty($cloud[$row['tag']])){
					$cloud[$row['tag']] = 1;
				}
				$cloud[$row['tag']]++;
			}
			ksort($cloud);

			$size[0] = 'font-size:xx-small;';
			$size[1] = 'font-size:x-small;';
			$size[2] = 'font-size:small;';
			$size[3] = 'font-size:medium;';
			$size[4] = 'font-size:large;';
			$size[5] = 'font-size:x-large;';
			$size[6] = 'font-size:xx-large;';

			$output = '';
			foreach($cloud as $key => $value){
				$fmax = 6; //maximale Fontgröße
				$ti = $value; //Anzahl
				$tmin = min($cloud); //minimale Anzahl
				$tmax = max($cloud); //maximale Anzahl

				//Anzuzeigende Fontgröße
				if($tmax-$tmin > 0){
					$si = ($fmax * ($ti-$tmin)) / ($tmax-$tmin);
				}else{
					$si = $fmax;
				}
				$output .= '<a href="'.str_replace('%key%',$key,$link).'"><span style="'.$size[$si].'">'.$key.'</span></a>'."\n";
			}
			return $output;
		}
		/**
		 * @desc			gets all data from a tag
		 * @param	string	$tag Name of the tag
		 * @return	string	Tag Cloud HTML
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->tagCloud->getTagData('Tools');
		 * </code>
		 * @since			2.0
		 */
		public function getTagData($tag){
			$query = $this->ipbwi->ips_wrapper->DB->query('SELECT * from '.ipbwi_DB_prefix.'tagcloud WHERE tag="'.$tag.'"');
			if($this->ipbwi->ips_wrapper->DB->getTotalRows($query) == 0){
				return false;
			}
			$data = '';
			while($row = $this->ipbwi->ips_wrapper->DB->fetch($query)){
				$data[] = $row;
			}
			return $data;
		}
		/**
		 * @desc			gets array with all tags of a topic.
		 * @param	int		$topicID Get Tags of a specific topic
		 * @return	array	array with all tags
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->tagCloud->getTagList();
		 * $ipbwi->tagCloud->getTagList(55);
		 * </code>
		 * @since			2.0
		 */
		public function getTagList($topicID){
			$query = $this->ipbwi->ips_wrapper->DB->query('SELECT * FROM '.ipbwi_DB_prefix.'tagcloud WHERE tid="'.intval($topicID).'"');
			if($this->ipbwi->ips_wrapper->DB->getTotalRows($query) == 0){
				return false;
			}
			$data = array();
			while($row = $this->ipbwi->ips_wrapper->DB->fetch($query)){
				$data[] = $row;
			}
			return $data;
		}
		/**
		 * @desc			gets array with all categories
		 * @return	array	array with all categories
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->tagCloud->getCategoryList();
		 * </code>
		 * @since			2.01
		 */
		public function getCategoryList(){
			$query = $this->ipbwi->ips_wrapper->DB->query('SELECT DISTINCT category FROM '.ipbwi_DB_prefix.'tagcloud');
			if($this->ipbwi->ips_wrapper->DB->getTotalRows($query) == 0){
				return false;
			}
			$data = array();
			while($row = $this->ipbwi->ips_wrapper->DB->fetch($query)){
				$data[] = $row['category'];
			}
			return $data;
		}
		/**
		 * @desc			adds a tag for a topic to tagcloud
		 * @param	string	$tag Name of the Tag
		 * @param	string	$destination Define a destination. This could be a full qualified URL, a relative path or filename or just again the topic id.
		 * @param	int		$topicID ID of the specific topic
		 * @param	string	$title Define a title. This could be an alternative of retrieving title informations through the topic id
		 * @param	string	$category Define a category to make handling with tags more efficient
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->tagCloud->addTag(55,'New tag','http://ipbwi.com/');
		 * $ipbwi->tagCloud->addTag(66,'Another new tag','example.php','examples');
		 * </code>
		 * @since			2.0
		 */
		public function addTag($tag,$destination,$topicID=false,$title=false,$category='default'){
			if($tag == '' || !is_string($tag)){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('badTag'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			if($destination == '' || !is_string($destination)){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('badDestination'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			if(intval($topicID) > 0){
				$topicID = '"'.intval($topicID).'"';
			}else{
				$topicID = 'NULL';
			}
			if(strlen($title) > 0){
				$title = '"'.$title.'"';
			}else{
				$title = 'NULL';
			}
			$this->ipbwi->ips_wrapper->DB->query('INSERT INTO '.ipbwi_DB_prefix.'tagcloud (tag,destination,tid,title,category) VALUES("'.$tag.'","'.$destination.'",'.$topicID.','.$title.',"'.$category.'")');
			return true;
		}
		/**
		 * @desc			deletes a tag from tagcloud
		 * @param	int		$tagID ID of the tag which should be deleted
		 * @return	bool	true on success, otherwise false
		 * @author			Matthias Reuter
		 * @sample
		 * <code>
		 * $ipbwi->tagCloud->deleteTag(55);
		 * </code>
		 * @since			2.0
		 */
		public function deleteTag($tagID){
			if($tagID == '' || intval($tagID) == 0 || !is_int(intval($tagID))){
				$this->ipbwi->addSystemMessage('Error',$this->ipbwi->getLibLang('badTagID'),'Located in file <strong>'.__FILE__.'</strong> at class <strong>'.__CLASS__.'</strong> in function <strong>'.__FUNCTION__.'</strong> on line #<strong>'.__LINE__.'</strong>');
				return false;
			}
			$this->ipbwi->ips_wrapper->DB->query('DELETE FROM '.ipbwi_DB_prefix.'tagcloud WHERE id="'.$tagID.'"');
			return true;
		}
	}
?>