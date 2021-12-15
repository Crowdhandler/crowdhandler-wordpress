<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.crowdhandler.com/
 * @since      0.1.0
 *
 * @package    Crowdhandler
 * @subpackage Crowdhandler/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Crowdhandler
 * @subpackage Crowdhandler/admin
 * @author     CROWDHANDLER LTD <hello@crowdhandler.com>
 */
class Crowdhandler_Admin
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
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    0.1.0
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Crowdhandler_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Crowdhandler_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style(
				$this->plugin_name,
				CROWDHANDLER_PLUGIN_BASE_URL . 'admin/css/crowdhandler-admin.css',
				[],
				$this->version,
				'all'
		);
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts()
	{
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Crowdhandler_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Crowdhandler_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script(
				$this->plugin_name,
				CROWDHANDLER_PLUGIN_BASE_URL . 'admin/js/crowdhandler-admin.js',
				['jquery'],
				$this->version,
				false
		);
	}

	public function settings_page()
	{
		add_menu_page(
			'CrowdHandler™ Virtual Waiting Room',
			'CrowdHandler™',
			'manage_options',
			'crowdhandler',
			[$this, 'settings_page_html']
		);
	}

	public function settings_page_html()
	{
		// check user capabilities
		if (!current_user_can('manage_options')) {
			return;
		}

		// add error/update messages

		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if (isset($_GET['settings-updated'])) {
			// add settings saved message with the class of "updated"
			add_settings_error(
				'crowdhandler_settings_messages',
				'crowdhandler_settings_message',
				__('Settings Saved', 'crowdhandler'),
				'updated'
			);

			$this->handleIndexFilesOverrides();
		}

		// show error/update messages
		settings_errors('crowdhandler_settings_messages');
		?>
		<div class="wrap">
			<h1><?php
				echo esc_html(get_admin_page_title()); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields('crowdhandler');
				do_settings_sections('crowdhandler');
				submit_button('Save Settings');
				?>
			</form>
		</div>
		<?php
	}

	public function settings_init()
	{
		register_setting('crowdhandler', 'crowdhandler_settings');

		add_settings_section(
			'crowdhandler_settings_section',
			__('CrowdHandler Settings', 'crowdhandler'),
			array($this, 'settings_section_callback'),
			'crowdhandler'
		);

        add_settings_field(
			'crowdhandler_settings_field_public_key',
			__('Public Key', 'crowdhandler'),
			array($this, 'settings_field_public_key_callback'),
			'crowdhandler',
			'crowdhandler_settings_section',
			array(
				'label_for' => 'crowdhandler_settings_field_public_key',
				'class' => 'crowdhandler_row',
			)
		);
        
		add_settings_field(
			'crowdhandler_settings_field_override_index',
			__('Override index.php', 'crowdhandler'),
			array($this, 'settings_field_override_index_callback'),
			'crowdhandler',
			'crowdhandler_settings_section',
			array(
				'label_for' => 'crowdhandler_settings_field_override_index',
				'class' => 'crowdhandler_row',
			)
		);

		add_settings_field(
			'crowdhandler_settings_field_is_enabled',
			__('Enabled', 'crowdhandler'),
			array($this, 'settings_field_is_enabled_callback'),
			'crowdhandler',
			'crowdhandler_settings_section',
			array(
				'label_for' => 'crowdhandler_settings_field_is_enabled',
				'class' => 'crowdhandler_row',
			)
		);


	}

	public function settings_section_callback($args)
	{
		?>
        <p id="<?php echo esc_attr( $args['id'] ); ?>">Install CrowdHandler on your WordPress site below.</p>
        <p>You need a CrowdHandler account to complete the set up: <a target="_blank" href="https://signup.crowdhandler.com/?utm_source=WordPress&utm_medium=Plugin_Dir">Sign up here</a>.</p>
        <p>Already have an account? Go to your dashboard to <a target="_blank" href="https://admin.crowdhandler.com/">configure your waiting room!</a></p>
		<?php
	}

	public function settings_field_public_key_callback($args)
	{
		$options = get_option('crowdhandler_settings');
		?>
		<textarea
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="crowdhandler_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
			class="crowdhandler-input crowdhandler-input--textarea"
		><?php echo isset($options[$args['label_for']]) ? esc_textarea($options[$args['label_for']]) : ''; ?></textarea>
		<p class="description">
			<?php esc_html_e( 'Your CrowdHandler API Public Key', 'crowdhandler' ); ?>. Find your <a target="_blank" href="https://admin.crowdhandler.com/account/api">key here</a>.
		</p>
		<?php
	}

	public function settings_field_is_enabled_callback($args)
	{
		$options = get_option('crowdhandler_settings');		
		?>
		<input
			type="checkbox"
			<?php 
			if ( isset($options[$args['label_for']]) )
			{
				esc_attr(checked( $options[$args['label_for']], "on", true ));
			}
			?>
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="crowdhandler_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
			class="crowdhandler-input"
		>
		<p class="description">
			<?php esc_html_e( 'Activates CrowdHandler for this site', 'crowdhandler' ); ?>
		</p>
		<?php
	}

	public function settings_field_override_index_callback($args)
	{
		$options = get_option('crowdhandler_settings');
		$handler = new CrowdhandlerIndexHandler($options);
		?>
		<input
			type="checkbox"
			<?php 
			if ( isset($options[$args['label_for']]) )
			{
				esc_attr(checked( $options[$args['label_for']], "on", true ));
			}
			?>
			id="<?php echo esc_attr( $args['label_for'] ); ?>"
			name="crowdhandler_settings[<?php echo esc_attr( $args['label_for'] ); ?>]"
			class="crowdhandler-input"
			<?php echo !$handler->isWritable() ? 'disabled' : ''; ?>
		>
		<?php if (!$handler->isWritable()): ?>
			<p class="description">
				<strong><?php esc_html_e('Main index.php file is not writable.', 'crowdhandler'); ?></strong>
			</p>
		<?php endif; ?>
		<p class="description">
			<?php esc_html_e('Recommended: Overriding index.php provides better performance and protection, by validating the user at the earliest opportunity.', 'crowdhandler'); ?>
		</p>
		<?php
	}

	public function handleIndexFilesOverrides()
	{
		$options = get_option('crowdhandler_settings');
		$handler = new CrowdhandlerIndexHandler($options);
		$handler->handleIndexFilesOverrides();
		
	}

}
