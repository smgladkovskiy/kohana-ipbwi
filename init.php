<?php defined('SYSPATH') or die('No direct access allowed.');

$configs = Kohana::find_file('config', 'ipbwi');

foreach($configs as $file)
{
	$config = $file;
}

require_once $config;
require_once Kohana::find_file('vendor', 'ipbwi/ipbwi.inc');