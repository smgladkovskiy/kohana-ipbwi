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
	$pageTitle		= 'BBCode';
	require_once('../ipbwi/ipbwi.inc.php');
	require_once('lib/php/includes.inc.php');

	$bbCode = '[b]test[/b] :)';

	echo $header;

	// Error Output
	echo $ipbwi->printSystemMessages();
?>
		<h2>BBCode Live Examples</h2>
		<h3>Original BBCode</h3>
		<pre><?php echo $bbCode; ?></pre>

		<h3>bbcode2html</h3>
		<div class="info"><div class="i_blank"><?php echo $ipbwi->bbcode->bbcode2html($bbCode); ?></div></div>
<?php echo $footer; ?>