<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta property="og:url" content="<?php the_permalink() ?>">
	<?php if ( is_single() ): ?>
	<meta property="og:type" content="article">
	<meta property="og:title" content="<?php single_post_title() ?>">
	<meta property="og:description" content="<?php echo mpaluchowski_get_excerpt( 0, 30 ) ?>">
	<meta name="twitter:title" content="<?php single_post_title() ?>">
	<meta name="twitter:description" content="<?php echo mpaluchowski_get_excerpt( 0, 30 ) ?>">
	<?php else: ?>
	<meta property="og:type" content="website">
	<meta property="og:title" content="<?php bloginfo( 'name' ) ?>">
	<meta property="og:description" content="<?php bloginfo( 'description' ) ?>">
	<meta name="twitter:title" content="<?php bloginfo( 'name' ) ?>">
	<meta name="twitter:description" content="<?php bloginfo( 'description' ) ?>">
	<?php endif; ?>
	<meta name="twitter:card" content="summary">
	<meta name="twitter:creator" content="@mpaluchowski">
	<meta name="twitter:site" content="@mpaluchowski">

	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>
<body itemscope itemtype="http://schema.org/WebPage">

<div id="wrap">
	<header id="site-head" itemscope itemtype="http://schema.org/WPHeader">

		<?php if ( get_header_image() ) : ?>
		<div id="blog-logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php header_image(); ?>" alt="">
			</a>
		</div>
		<?php endif; ?>

		<?php if ( is_single() ): ?>
			<div id="site-title" itemprop="headline">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php bloginfo( 'name' ); ?>
				</a>
			</div>
			<div id="site-description" itemprop="description"><?php bloginfo( 'description' ); ?></div>
		<?php else: ?>
			<h1 itemprop="headline">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php bloginfo( 'name' ); ?>
				</a>
			</h1>
			<h2 itemprop="description"><?php bloginfo( 'description' ); ?></h2>
		<?php endif; ?>

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

	<main role="main" itemprop="mainContentOfPage" itemscope itemtype="http://schema.org/Blog">
		<?php
			if ( have_posts() ) :
				while ( have_posts() ) : the_post();
		?>
		<article class="h-entry" itemprop="blogPost" itemscope itemtype="http://schema.org/BlogPosting">
			<header>
				<p class="post-meta">
					<time class="dt-published" datetime="<?php echo get_the_date( 'c' ) ?>" itemprop="datePublished"><?php echo esc_html( get_the_date() ) ?></time>
					<span class="comments"><?php comments_popup_link( __( 'Leave a comment', 'mpaluchowski' ), __( '1 Comment', 'mpaluchowski' ), __( '% Comments', 'mpaluchowski' ) ); ?></span>
				</p>
				<?php
					if ( is_single() ) {
						the_title( '<h1 class="p-name" itemprop="name headline">', '</h1>' );
					} else {
						the_title( '<h2 class="p-name" itemprop="name headline"><a href="' . esc_url( get_permalink() ) . '" class="u-url">', '</a></h2>' );
					}
				?>
			</header>
			<section class="post-content e-content" itemprop="articleBody">
				<?php
					the_content();
				?>
			</section>
			<footer>
				<?php
					the_schema_tags('<ul class="tag-list"><li>', '</li><li>', '</li></ul>');
				?>

				<?php if ( is_single() ): ?>

				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
				<?php foreach ( explode( ',', get_option( 'mpaluchowski_option' )['addthis_services'] ) as $service ): ?>
				<a class="addthis_button_<?php echo $service ?>"></a>
				<?php endforeach; ?>
				</div>
				<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo get_option( 'mpaluchowski_option' )['addthis_profile_id'] ?>"></script>
				<script type="text/javascript">
					var addthis_share = addthis_share || {}
						addthis_share = {
							passthrough : {
								twitter: {
									<?php if ( get_option( 'mpaluchowski_option' )['addthis_twitter_via'] ): ?>
									via: "<?php echo get_option( 'mpaluchowski_option' )['addthis_twitter_via'] ?>"
									<?php endif; ?>
								}
							}
						}
					var addthis_config = {
						data_track_clickback : <?php echo get_option( 'mpaluchowski_option' )['addthis_track_clickback'] ? 'true' : 'false' ?>
					}
				</script>
				<!-- AddThis Button END -->

				<section class="author-description">
					<h4>Written by <span class="p-author" itemprop="author" itemscope itemtype="http://schema.org/Person"><span itemprop="name"><?php echo get_the_author() ?></span></span></h4>
				<?php
					if ( get_the_author_meta( 'description' ) ):
				?>
					<p><?php the_author_meta( 'description' ); ?></p>
				<?php
					endif; // Check author description
				?>
				</section>

				<nav class="post-navigation-links">
				<?php
					previous_post_link( '%link' );
					next_post_link( '%link' );
				?>
				</nav>
				<?php endif; // is_single() ?>
			</footer>

			<?php
				if ( is_single() ):
			?>
			<section class="comments">
				<?php
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}

					if ( !comments_open() ) {
						echo '<p class="no-comments">' . __( 'Comments are closed.', 'mpaluchowski' ) . '</p>';
					}
				?>
			</section>
			<?php
				endif; // is_single()
			?>
		</article>
		<?php
				endwhile; // have_posts()
		?>

		<?php if ( !is_singular() ): ?>
		<nav class="post-navigation-links">
		<?php
			next_posts_link( 'Older stuff' );
			previous_posts_link( 'Fresh thinking' );
		?>
		</nav>
		<?php endif; // is_singular() ?>

		<?php
			endif; // have_posts()
		?>
	</main>

	<div id="side-links">
		<nav id="side-navigation" role="navigation" itemscope itemtype="htt://schema.org/SiteNavigationElement">
			<ul>
				<li><a href="http://michal.paluchowski.com/about/" rel="author">About Michał</a></li>
			</ul>
		</nav>

		<footer itemscope itemtype="http://schema.org/WPFooter">
			Made in Warsaw by Michał Paluchowski. All text and code is under a <a href="http://creativecommons.org/licenses/by/4.0/" rel="license">Creative Commons Attribution 4.0 International License</a>.
		</footer>
	</div>
</div>

<?php wp_footer(); ?>

</body>
</html>
