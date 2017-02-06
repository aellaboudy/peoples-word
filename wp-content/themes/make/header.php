<?php
/**
 * @package Make
 */
?><!DOCTYPE html>
<!--[if lte IE 9]><html class="no-js IE9 IE" <?php language_attributes(); ?>><![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="google-site-verification" content="4kCFA5B08dkwFRVZYmACf62nKaF7OOV0cWV9wTeMRaU" />
		<title><?php wp_title( '|', true, 'right' ); ?></title>

		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
		<div id="site-wrapper" class="site-wrapper">

			<?php ttfmake_maybe_show_site_region( 'header' ); ?>

			<div id="site-content" class="site-content">
				<div class="container">