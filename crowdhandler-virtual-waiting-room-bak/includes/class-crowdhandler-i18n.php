<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.crowdhandler.com/
 * @since      0.1.0
 *
 * @package    Crowdhandler
 * @subpackage Crowdhandler/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1.0
 * @package    Crowdhandler
 * @subpackage Crowdhandler/includes
 * @author     CROWDHANDLER LTD <hello@crowdhandler.com>
 */
class Crowdhandler_i18n
{

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'crowdhandler',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}

}
