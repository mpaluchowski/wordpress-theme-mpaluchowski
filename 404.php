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

<div id="wrap">
	<?php get_template_part( 'template-parts/header' ); ?>
</div>

<?php wp_footer(); ?>

</body>
</html>
