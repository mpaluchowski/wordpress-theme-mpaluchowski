<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<link href="//fonts.googleapis.com/css?family=Roboto:900&amp;subset=latin,latin-ext" rel="stylesheet" type="text/css">
	<link href="<?php echo get_template_directory_uri(); ?>/style.css" rel="stylesheet">

	<?php wp_head(); ?>
</head>
<body>

<div id="wrap">
	<header id="site-head">

		<?php if ( get_header_image() ) : ?>
		<div id="blog-logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php header_image(); ?>" alt="">
			</a>
		</div>
		<?php endif; ?>

		<h1><?php bloginfo( 'name' ); ?></h1>
		<h2><?php bloginfo( 'description' ); ?></h2>

		<div id="site-search">
			<form role="search" method="get" id="searchform" class="searchform" action="http://michal.paluchowski.com/">
				<div>
					<label class="screen-reader-text" for="s">Search for:</label>
					<input type="text" value="" name="s" id="s" placeholder="Search for...">
					<button type="submit">Search</button>
				</div>
			</form>
		</div>
	</header>

	<main role="main">
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
		?>
		<article>
			<header>
				<p class="post-meta">
					<time datetime="2013-07-06"><?php echo esc_html( get_the_date() ) ?></time>
					<span class="comments"><?php comments_popup_link( __( 'Leave a comment', 'twentyfourteen' ), __( '1 Comment', 'twentyfourteen' ), __( '% Comments', 'twentyfourteen' ) ); ?></span>
				</p>
				<?php
					the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				?>
			</header>
			<section class="post-content">
				<?php
					the_content();
				?>
			</section>
			<footer>
				<?php
					the_tags('<ul class="tag-list"><li>', '</li><li>', '</li></ul>');
				?>

				<?php
					if ( is_single() && get_the_author_meta( 'description' ) ) :
				?>
				<section class="author-description">
					<h4>Written by <?php echo get_the_author() ?></h4>
					<p><?php the_author_meta( 'description' ); ?></p>
				</section>
				<?php
					endif;
				?>
			</footer>
		</article>
		<?php
				endwhile;
			endif;
		?>
	</main>

	<div id="side-links">
		<nav id="side-navigation">
			<ul>
				<li><a href="http://michal.paluchowski.com/about/">About Michał</a></li>
			</ul>
		</nav>

		<footer>
			Made in Warsaw by Michał Paluchowski. All text and code is under a <a href="http://creativecommons.org/licenses/by/4.0/" rel="license">Creative Commons Attribution 4.0 International License</a>.
		</footer>
	</div>
</div>

<?php wp_footer(); ?>

</body>
</html>
