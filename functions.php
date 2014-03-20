<?php


if ( ! isset( $content_width ) ) $content_width = 640;

if ( ! function_exists( 'mpaluchowski_setup' ) ) :
/**
 * Setup basic theme configuration.
 */
function mpaluchowski_setup() {

	// Users can upload custom header images, showin in the sidebar
	add_theme_support( 'custom-header', [
		'uploads' => true,
		'width' => 286,
		'height' => 286,
		'flex-height' => true,
		'header-text' => false
		] );

	add_editor_style( array( 'css/editor-style.css' ) );

	// Enable HTML5 markup for widgets
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
	) );

}
endif; // mpaluchowski_setup()
add_action( 'after_setup_theme', 'mpaluchowski_setup' );

function mpaluchowski_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'mpaluchowski' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'mpaluchowski_wp_title', 10, 2 );


function mpaluchowski_previous_posts_attributes( ) {
	return 'rel="next"';
}
add_filter( 'previous_posts_link_attributes', 'mpaluchowski_previous_posts_attributes' );

function mpaluchowski_next_posts_attributes( ) {
	return 'rel="prev"';
}
add_filter( 'next_posts_link_attributes', 'mpaluchowski_next_posts_attributes' );


/**
 * Adds http://schema.org/ markup to tags.
 */
function the_schema_tags( $before = '<ul><li>', $sep = '</li><li>', $after = '</li></ul>') {
	$tags = get_the_tags();

	if ( !$tags )
		return;

	$tags_links = [];
	foreach ($tags as $tag) {
		$tags_links[] = '<a href="' . get_tag_link($tag->term_id)
				. '" class="p-category" itemprop="keywords">'. $tag->name . '</a>';
	}

	echo $before . implode($tags_links, $sep) . $after;
}

// Add embedder provider for TED Talks
wp_oembed_add_provider( '#https?://(www\.)?ted.com/talks/.*#i', 'http://www.ted.com/talks/oembed.{format}', true );

/**
 * TED Talks added with WordPress's standard embedder show with the wrong
 * proportions. Width is fine, following the configured content width, but height
 * was way too long.
 *
 * This changes height to be proportional for 16:9 videos.
 */
function fix_embed_ted_height($oembvideo, $url, $attr) {
	if (strpos($url, 'ted.com') !== false) {
		preg_match( '#width="(\d+)"#', $oembvideo, $matches );
		$width = $matches[1];
		$oembvideo = preg_replace( '#height="\d+"#', 'height="' . $width * 0.5625 . '"', $oembvideo);
	}
	return $oembvideo;
}
add_filter('embed_oembed_html', 'fix_embed_ted_height', 10, 3);

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
