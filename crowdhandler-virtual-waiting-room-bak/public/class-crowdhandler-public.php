<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.crowdhandler.com/
 * @since      0.1.0
 *
 * @package    Crowdhandler
 * @subpackage Crowdhandler/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Crowdhandler
 * @subpackage Crowdhandler/public
 * @author     CROWDHANDLER LTD <hello@crowdhandler.com>
 */
class Crowdhandler_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * @var CrowdHandlerGateKeeper
	 */
	private $gateKeeper;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    0.1.0
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function checkRequest()
	{
		if (!$this->gateKeeper) {
			$this->gateKeeper = new CrowdHandlerGateKeeper();
		}

		$this->gateKeeper->checkRequest();
	}

	public function recordPerformance()
	{
		if ($this->gateKeeper) {
			$this->gateKeeper->recordPerformance(http_response_code());
		}
	}

}
