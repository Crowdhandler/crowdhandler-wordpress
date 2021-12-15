<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.crowdhandler.com/
 * @since      0.1.0
 *
 * @package    Crowdhandler
 * @subpackage Crowdhandler/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    Crowdhandler
 * @subpackage Crowdhandler/includes
 * @author     CROWDHANDLER LTD <hello@crowdhandler.com>
 */
class Crowdhandler_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.1.0
	 */
	public static function deactivate()
	{
		$options = get_option('crowdhandler_settings');

		if (get_filesystem_method() === 'direct') {
			$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());
			if (!WP_Filesystem($creds)) {
				return false;
			}
			
			$handler = new CrowdhandlerIndexHandler($options);
			$handler->removeCrowdHandlerIndexFile();
			
		}

		unset($options['crowdhandler_settings_field_is_enabled']);
		unset($options['crowdhandler_settings_field_override_index']);

		update_option('crowdhandler_settings', $options);
	}
	
}
