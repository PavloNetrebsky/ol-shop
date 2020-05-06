<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
<?php wp_site_icon(); ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<div class="site">

	<header class="noo-header <?php noo_hermosa_header_class(); ?>">
		<?php noo_hermosa_get_layout('navbar'); ?>
	</header>
	<?php noo_hermosa_get_layout('heading'); ?>

