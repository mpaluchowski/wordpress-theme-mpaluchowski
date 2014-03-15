<?php
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
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'mpaluchowski_wp_title', 10, 2 );

add_theme_support( 'custom-header', ['uploads' => true] );

function mpaluchowski_previous_posts_attributes( ) {
	return 'rel="next"';
}
add_filter( 'previous_posts_link_attributes', 'mpaluchowski_previous_posts_attributes' );

function mpaluchowski_next_posts_attributes( ) {
	return 'rel="prev"';
}
add_filter( 'next_posts_link_attributes', 'mpaluchowski_next_posts_attributes' );

function the_schema_tags( $before = '<ul><li>', $sep = '</li><li>', $after = '</li></ul>') {
	$tags = get_the_tags();
	$tags_links = [];
	foreach ($tags as $tag) {
		$tags_links[] = '<a href="' . get_tag_link($tag->term_id)
				. '" rel="tag" itemprop="keywords">'. $tag->name . '</a>';
	}

	echo $before . implode($tags_links, $sep) . $after;
}

if ( ! isset( $content_width ) ) $content_width = 640;

wp_oembed_add_provider( '#https?://(www\.)?ted.com/talks/.*#i', 'http://www.ted.com/talks/oembed.{format}', true );

function fix_embed_ted_height($oembvideo, $url, $attr) {
	if (strpos($url, 'ted.com') !== false) {
		preg_match( '#width="(\d+)"#', $oembvideo, $matches );
		$width = $matches[1];
		$oembvideo = preg_replace( '#height="\d+"#', 'height="' . $width * 0.5625 . '"', $oembvideo);
	}
	return $oembvideo;
}
add_filter('embed_oembed_html', 'fix_embed_ted_height', 10, 3);
