<?php

class MpaluchowskiSettingsPage {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'page_init' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page(
			'mpaluchowski Settings',
			'mpaluchowski',
			'manage_options',
			'mpaluchowski-settings-admin',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page() {
		// Set class property
		$this->options = get_option( 'mpaluchowski_option' );
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>mpaluchowski Settings</h2>
			<form method="post" action="options.php">
			<?php
				// This prints out all hidden setting fields
				settings_fields( 'mpaluchowski_option_group' );
				do_settings_sections( 'mpaluchowski-settings-admin' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register and add settings
	 */
	public function page_init() {
		register_setting(
			'mpaluchowski_option_group', // Option group
			'mpaluchowski_option', // Option name
			array( $this, 'sanitize' ) // Sanitize
		);

		add_settings_section(
			'section_addthis', // ID
			'AddThis', // Title
			array( $this, 'print_addthis_section_info' ), // Callback
			'mpaluchowski-settings-admin' // Page
		);

		add_settings_field(
			'addthis_profile_id', // ID
			'Profile ID', // Title
			array( $this, 'addthis_profile_id_callback' ), // Callback
			'mpaluchowski-settings-admin', // Page
			'section_addthis' // Section
		);

		add_settings_field(
			'addthis_services',
			'Services',
			array( $this, 'addthis_services_callback' ),
			'mpaluchowski-settings-admin',
			'section_addthis'
		);

		add_settings_field(
			'addthis_twitter_via',
			'Twitter Via Username',
			array( $this, 'addthis_twitter_via_callback' ),
			'mpaluchowski-settings-admin',
			'section_addthis'
		);
	}

	/**
	 * Sanitize each setting field as needed
	 *
	 * @param array $input Contains all settings fields as array keys
	 */
	public function sanitize( $input ) {
		$new_input = array();
		if( isset( $input['addthis_profile_id'] ) )
			$new_input['addthis_profile_id'] = sanitize_text_field( $input['addthis_profile_id'] );

		if( isset( $input['addthis_services'] ) )
			$new_input['addthis_services'] = sanitize_text_field( $input['addthis_services'] );

		if( isset( $input['addthis_twitter_via'] ) ) {
			$new_input['addthis_twitter_via'] = sanitize_text_field( $input['addthis_twitter_via'] );
			// Remove leading @, if entered by user; not needed
			$new_input['addthis_twitter_via'] = preg_replace('#@(.*)#', '$1', $new_input['addthis_twitter_via']);
		}

		return $new_input;
	}

	/**
	 * Print the Section text
	 */
	public function print_addthis_section_info() {
		print 'Enter settings for the <a href="http://www.addthis.com/">AddThis</a> widgets:';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function addthis_profile_id_callback() {
		printf(
			'<input type="text" id="addthis_profile_id" name="mpaluchowski_option[addthis_profile_id]" value="%s" />',
			isset( $this->options['addthis_profile_id'] ) ? esc_attr( $this->options['addthis_profile_id']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function addthis_services_callback() {
		printf(
			'<input type="text" id="addthis_services" name="mpaluchowski_option[addthis_services]" value="%s" />',
			isset( $this->options['addthis_services'] ) ? esc_attr( $this->options['addthis_services']) : ''
		);
	}

	public function addthis_twitter_via_callback() {
		printf(
			'<input type="text" id="addthis_twitter_via" name="mpaluchowski_option[addthis_twitter_via]" value="%s" />',
			isset( $this->options['addthis_twitter_via'] ) ? esc_attr( $this->options['addthis_twitter_via']) : ''
		);
	}
}

if( is_admin() )
	$mpaluchowski_settings_page = new MpaluchowskiSettingsPage();
