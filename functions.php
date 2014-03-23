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

	// Add Roboto font used for header
	wp_enqueue_style(
		'mpaluchowski-roboto',
		'//fonts.googleapis.com/css?family=Roboto:900&subset=latin,latin-ext',
		array(),
		null
		);


	// Add main theme stylesheet
	wp_enqueue_style( 'mpaluchowski-style', get_stylesheet_uri() );

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

/**
 * Allows for getting the excerpt outside of The Loop.
 */
function mpaluchowski_get_excerpt( $post_id = 0, $length = 35 ) {
	$post = get_post( $post_id );
	$the_excerpt = $post->post_content;
	$the_excerpt = strip_tags( strip_shortcodes( $the_excerpt ) );
	$words = explode( ' ', $the_excerpt, $length + 1 );
	if ( count( $words ) > $length ) {
		array_pop( $words );
		array_push( $words, '...' );
		$the_excerpt = implode( ' ', $words );
	}
	return $the_excerpt;
}

// Add Theme Settings Page
require get_template_directory() . '/inc/mpaluchowski-settings-page.php';
