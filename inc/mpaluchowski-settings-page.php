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

		add_settings_field(
			'addthis_track_clickback',
			'Track ClickBacks',
			array( $this, 'addthis_track_clickback_callback' ),
			'mpaluchowski-settings-admin',
			'section_addthis'
		);

		add_settings_section(
			'section_twitter', // ID
			'Twitter', // Title
			array( $this, 'print_twitter_section_info' ), // Callback
			'mpaluchowski-settings-admin' // Page
		);

		add_settings_field(
			'twitter_follow_username',
			'Twitter Username',
			array( $this, 'twitter_follow_username_callback' ),
			'mpaluchowski-settings-admin',
			'section_twitter'
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

		if( isset( $input['addthis_track_clickback'] ) )
			$new_input['addthis_track_clickback'] = absint( $input['addthis_track_clickback'] );

		if( isset( $input['twitter_follow_username'] ) ) {
			$new_input['twitter_follow_username'] = sanitize_text_field( $input['twitter_follow_username'] );
			// Remove leading @, if entered by user; not needed
			$new_input['twitter_follow_username'] = preg_replace('#@(.*)#', '$1', $new_input['twitter_follow_username']);
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
			'<input type="text" id="addthis_profile_id" name="mpaluchowski_option[addthis_profile_id]" value="%s" class="regular-text">',
			isset( $this->options['addthis_profile_id'] ) ? esc_attr( $this->options['addthis_profile_id']) : ''
		);
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function addthis_services_callback() {
		printf(
			'<input type="text" id="addthis_services" name="mpaluchowski_option[addthis_services]" value="%s" class="regular-text">',
			isset( $this->options['addthis_services'] ) ? esc_attr( $this->options['addthis_services']) : ''
		);
		print('<p class="description">' . __('Coma-delimited list of <a href="http://www.addthis.com/services/list">AddThis services</a>', 'mpaluchowski') . '</p>');
	}

	public function addthis_twitter_via_callback() {
		printf(
			'<input type="text" id="addthis_twitter_via" name="mpaluchowski_option[addthis_twitter_via]" value="%s" class="regular-text">',
			isset( $this->options['addthis_twitter_via'] ) ? esc_attr( $this->options['addthis_twitter_via']) : ''
		);
		print('<p class="description">' . __('Your Twitter handle <strong>without</strong> the leading @ sign', 'mpaluchowski') . '</p>');
	}

	public function addthis_track_clickback_callback() {
		print('<input type="checkbox" id="addthis_track_clickback" name="mpaluchowski_option[addthis_track_clickback]" value="1" ' . checked( 1, get_option( 'mpaluchowski_option' )['addthis_track_clickback'], false ) . '>');
		print('<p class="description">' . __('Should AddThis append a tracking ID to the links being shared?', 'mpaluchowski') . '</p>');
	}

	public function print_twitter_section_info() {
		print 'Enter settings for Twitter widgets:';
	}

	public function twitter_follow_username_callback() {
		printf(
			'<input type="text" id="twitter_follow_username" name="mpaluchowski_option[twitter_follow_username]" value="%s" class="regular-text">',
			isset( $this->options['twitter_follow_username'] ) ? esc_attr( $this->options['twitter_follow_username']) : ''
		);
		print('<p class="description">' . __('Your Twitter handle for the Follow button, <strong>without</strong> the leading @ sign', 'mpaluchowski') . '</p>');
	}

}

if( is_admin() )
	$mpaluchowski_settings_page = new MpaluchowskiSettingsPage();
