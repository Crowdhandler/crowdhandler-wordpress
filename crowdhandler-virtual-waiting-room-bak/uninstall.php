<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://www.crowdhandler.com/
 * @since      0.1.0
 *
 * @package    Crowdhandler
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

check_ajax_referer('updates');


require_once 'consts.php';

if (get_filesystem_method() === 'direct') {
	$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());
	if (!WP_Filesystem($creds)) {
		return false;
	}

	/** @var WP_Filesystem_Direct $wp_filesystem */
	global $wp_filesystem;

	if ($wp_filesystem->exists(CROWDHANDLER_PLUGIN_INDEX_COPY_FILE_PATH)) {
		$wp_filesystem->move(CROWDHANDLER_PLUGIN_INDEX_COPY_FILE_PATH, CROWDHANDLER_PLUGIN_INDEX_FILE_PATH, true);
	}
}

delete_option('crowdhandler_settings');
