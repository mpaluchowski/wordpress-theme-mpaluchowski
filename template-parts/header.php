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
		<?php get_search_form() ?>
	</div>
</header>
