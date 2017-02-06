<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
		
		<?php do_action('favicon') ?>
		
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		
		<?php do_action( 'bp_head' ) ?>
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/style.css" type="text/css" media="screen" />
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        
		<?php wp_head(); ?>
    </head>

<body <?php body_class() ?> id='x2'>
 <div id="outerrim">
 
 	<?php do_action( 'bp_before_header' ) ?>
	

	<?php do_action( 'bp_after_header' ) ?>		
	<?php do_action( 'bp_before_container' ) ?>

	<div id="container">