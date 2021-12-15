<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

define('CROWDHANDLER_PLUGIN_BASE_PATH', plugin_dir_path(__FILE__));
define('CROWDHANDLER_PLUGIN_BASE_URL', plugin_dir_url(__FILE__));

define('CROWDHANDLER_PLUGIN_INDEX_FILE_PATH', ABSPATH . 'index.php');
define('CROWDHANDLER_PLUGIN_INDEX_COPY_FILE_PATH', ABSPATH . 'wp-index.php');
