<?php defined('SYSPATH') or die('No direct access allowed.');

class Ipbwi extends Ipbwi_Core {

	/**
	 * Instance storage
	 * @var Ipbwi
	 */
	protected static $instance;

	/**
	 * Singleton instantiating
	 *
	 * @static
	 * @return Ipbwi
	 */
	public static function instance()
	{

		if (! isset (self::$instance))
		{
			self::$instance = new Ipbwi();
		}
		return self::$instance;
	}
}