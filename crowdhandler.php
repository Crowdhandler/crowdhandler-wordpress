<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.crowdhandler.com/
 * @since             1.0.0
 * @package           Crowdhandler
 *
 * @wordpress-plugin
 * Plugin Name:       CrowdHandler™ Virtual Waiting Room - Protect your Website
 * Plugin URI:        https://www.crowdhandler.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            CROWDHANDLER LTD
 * Author URI:        https://www.crowdhandler.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       crowdhandler
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define('BASE_PATH', plugin_dir_path(__FILE__));
define('BASE_URL', plugin_dir_url(__FILE__));

require_once BASE_PATH . 'vendor/autoload.php';

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('CROWDHANDLER_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-crowdhandler-activator.php
 */
function activate_crowdhandler()
{
	require_once BASE_PATH . 'includes/class-crowdhandler-activator.php';
	Crowdhandler_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-crowdhandler-deactivator.php
 */
function deactivate_crowdhandler()
{
	require_once BASE_PATH . 'includes/class-crowdhandler-deactivator.php';
	Crowdhandler_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_crowdhandler');
register_deactivation_hook(__FILE__, 'deactivate_crowdhandler');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require BASE_PATH . 'includes/class-crowdhandler.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_crowdhandler()
{
	$plugin = new Crowdhandler();
	$plugin->run();
}

run_crowdhandler();
