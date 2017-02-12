<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head itemscope itemtype="http://schema.org/WebSite">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<?php wp_head(); ?>
</head>

<body <?php body_class() ?> itemscope itemtype="http://schema.org/WebPage">

<div class="wrap-404">
	<?php get_template_part( 'template-parts/header' ); ?>

	<main class="content-404 post-content">
		<h1><?php _e( 'Oops! No page here', 'mpaluchowski' ); ?></h1>
		<p><?php _e( "We couldn't find anything at this location. Maybe it went missing, or maybe it was never here in the first place. Try <a href=\"" . home_url() . "\">starting from home</a> or use the search form in the sidebar.", 'mpaluchowski' ); ?></p>
	</main>
</div>

<?php wp_footer(); ?>

</body>
</html>
