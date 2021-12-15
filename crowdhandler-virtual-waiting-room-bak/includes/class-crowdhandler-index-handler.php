<?php

/**
 * Handles writing of index.php when override index.php is checked/unchecked
 * and the plugin is enabled and disabled
 *
 * @link       https://www.crowdhandler.com/
 * @since      1.1.3
 *
 * @package    Crowdhandler
 * @subpackage Crowdhandler/includes
 */

class CrowdhandlerIndexHandler
{

	/**
	 * Is index.php writable.
	 *
	 * @since    1.1.3
	 * @access   private
	 */
	private $isWritable;

	public function __construct($options)
	{
		$this->options = $options;
		$this->isWritable = $this->isIndexFileWritable();
	}


	public function isWritable()
	{
		return $this->isWritable;
	}


	public function handleIndexFilesOverrides()
	{

		if ($this->isWritable) {


			/** @var WP_Filesystem_Direct $wp_filesystem */
			global $wp_filesystem;

			if (
				isset($this->options['crowdhandler_settings_field_is_enabled']) &&
				$this->options['crowdhandler_settings_field_is_enabled'] === 'on' &&
				isset($this->options['crowdhandler_settings_field_override_index']) &&
				$this->options['crowdhandler_settings_field_override_index'] === 'on'
			) {

				$this->addCrowdHandlerIndexFile();
			} elseif ($wp_filesystem->exists(CROWDHANDLER_PLUGIN_INDEX_COPY_FILE_PATH)) {
				$wp_filesystem->move(CROWDHANDLER_PLUGIN_INDEX_COPY_FILE_PATH, CROWDHANDLER_PLUGIN_INDEX_FILE_PATH, true);
			}
		}
	}

	public function removeCrowdHandlerIndexFile()
	{
		$dir = plugin_dir_path(__DIR__);
		require_once $dir . '/consts.php';

		/** @var WP_Filesystem_Direct $wp_filesystem */
		global $wp_filesystem;

		if ($wp_filesystem->exists(CROWDHANDLER_PLUGIN_INDEX_FILE_PATH)) {
			$wp_filesystem->move(CROWDHANDLER_PLUGIN_INDEX_COPY_FILE_PATH, CROWDHANDLER_PLUGIN_INDEX_FILE_PATH, true);
		}
	}

	public function addCrowdHandlerIndexFile()
	{

		$dir = plugin_dir_path(__DIR__);
		require_once $dir . '/consts.php';

		/** @var WP_Filesystem_Direct $wp_filesystem */
		global $wp_filesystem;

		$data = var_export(
			array(
				'plugin_path' => CROWDHANDLER_PLUGIN_BASE_PATH,
				'options' => $this->options,
			),
			true
		);

		if (!$wp_filesystem->exists(CROWDHANDLER_PLUGIN_INDEX_COPY_FILE_PATH)) {
			$wp_filesystem->move(CROWDHANDLER_PLUGIN_INDEX_FILE_PATH, CROWDHANDLER_PLUGIN_INDEX_COPY_FILE_PATH);
		}

		$wp_filesystem->put_contents(
			CROWDHANDLER_PLUGIN_INDEX_FILE_PATH,
			<<<PHP
<?php

\$config = {$data};

require_once \$config['plugin_path'] . 'vendor/autoload.php';

\$crowdHandlerGateKeeper = new CrowdHandlerGateKeeper(\$config['options']);
\$crowdHandlerGateKeeper->checkRequest();

include 'wp-index.php';

\$crowdHandlerGateKeeper->recordPerformance(http_response_code());

PHP
		);
	}


	private function isIndexFileWritable()
	{
		if (get_filesystem_method() !== 'direct') {
			return false;
		}

		$creds = request_filesystem_credentials(site_url() . '/wp-admin/', '', false, false, array());

		if (!WP_Filesystem($creds)) {
			return false;
		}

		/** @var WP_Filesystem_Direct $wp_filesystem */
		global $wp_filesystem;

		if (!$wp_filesystem->is_writable(ABSPATH)) {
			return false;
		}

		if (!$wp_filesystem->is_writable(CROWDHANDLER_PLUGIN_INDEX_FILE_PATH)) {
			return false;
		}

		return true;
	}
}
