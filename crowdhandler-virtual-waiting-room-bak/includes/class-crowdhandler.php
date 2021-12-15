<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.crowdhandler.com/
 * @since      0.1.0
 *
 * @package    Crowdhandler
 * @subpackage Crowdhandler/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    Crowdhandler
 * @subpackage Crowdhandler/includes
 * @author     CROWDHANDLER LTD <hello@crowdhandler.com>
 */
class Crowdhandler
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Crowdhandler_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function __construct()
	{
		if (defined('CROWDHANDLER_VERSION')) {
			$this->version = CROWDHANDLER_VERSION;
		} else {
			$this->version = '0.1.0';
		}
		$this->plugin_name = 'crowdhandler';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies()
	{
		$this->loader = new Crowdhandler_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Crowdhandler_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function set_locale()
	{
		$plugin_i18n = new Crowdhandler_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$plugin_admin = new Crowdhandler_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_action('admin_init', $plugin_admin, 'settings_init');
		$this->loader->add_action('admin_menu', $plugin_admin, 'settings_page');
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     0.1.0
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     0.1.0
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_public_hooks()
	{
		$plugin_public = new Crowdhandler_Public($this->get_plugin_name(), $this->get_version());

		global $crowdHandlerGateKeeper;
		if (!isset($crowdHandlerGateKeeper) || !$crowdHandlerGateKeeper instanceof CrowdHandlerGateKeeper) {
			$this->loader->add_action('muplugins_loaded', $plugin_public, 'checkRequest');
			$this->loader->add_action('plugins_loaded', $plugin_public, 'checkRequest');
			$this->loader->add_action('shutdown', $plugin_public, 'recordPerformance');
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Crowdhandler_Loader    Orchestrates the hooks of the plugin.
	 * @since     0.1.0
	 */
	public function get_loader()
	{
		return $this->loader;
	}

}
