<?php
/**
 * @package Make
 */

// Header Options
$header_text     = get_theme_mod( 'header-text', false );
$social_links    = ttfmake_get_social_links();
$show_social     = (int) get_theme_mod( 'header-show-social', ttfmake_get_default( 'header-show-social' ) );
$show_search     = (int) get_theme_mod( 'header-show-search', ttfmake_get_default( 'header-show-search' ) );
$subheader_class = ( 1 === $show_social || 1 === $show_search ) ? ' right-content' : '';
$hide_site_title = (int) get_theme_mod( 'hide-site-title', ttfmake_get_default( 'hide-site-title' ) );
$hide_tagline    = (int) get_theme_mod( 'hide-tagline', ttfmake_get_default( 'hide-tagline' ) );
$menu_label      = get_theme_mod( 'navigation-mobile-label', ttfmake_get_default( 'navigation-mobile-label' ) );
$header_bar_menu = wp_nav_menu( array(
	'theme_location' => 'header-bar',
	'fallback_cb'    => false,
	'echo'           => false,
) );
?>

<header id="site-header" class="<?php echo esc_attr( ttfmake_get_site_header_class() ); ?>" role="banner">
	<?php // Only show Sub Header if it has content
	if ( ! empty( $header_text ) || 1 === $show_search || ( ! empty ( $social_links ) && 1 === $show_social ) || ! empty( $header_bar_menu ) ) : ?>
	<div class="header-bar<?php echo esc_attr( $subheader_class ); ?>">
		<div class="container">
			<a class="skip-link screen-reader-text" href="#site-content"><?php _e( 'Skip to content', 'make' ); ?></a>
			<a href=<?php echo esc_url( home_url("AboutUs") ) ?> style="float:right; display:inline;"> About Us </a>
			<ul style="list-style-type:none;padding: 0px;margin: 0px;display:inline;">
                        <li style="display:inline;"><?php wp_register(); ?></li>
                        <li style="display:inline;"><?php wp_loginout(); ?></li>
                        </ul>
			<?php // Search form
			if ( 1 === $show_search ) :
				get_search_form();
			endif; ?>
			<?php // Social links
			ttfmake_maybe_show_social_links( 'header' ); ?>
			<?php // Header text; shown only if there is no header menu
			if ( ( ! empty( $header_text ) || ttfmake_is_preview() ) && empty( $header_bar_menu ) ) : ?>
				<span class="header-text">
				<?php echo ttfmake_sanitize_text( $header_text ); ?>
				</span>
			<?php endif; ?>
			<?php echo $header_bar_menu; ?>
		</div>
	</div>
	<?php endif; ?>
	<div class="site-header-main">
		<div class="container">
			<div class="site-branding">
				<?php if ( ttfmake_get_logo()->has_logo() ) : ?>
				<div class="custom-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"></a>
				</div>
				<?php endif; ?>
				<h1 class="site-title">
					<?php // Site title
					if ( 1 !== $hide_site_title && get_bloginfo( 'name' ) ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<?php bloginfo( 'name' ); ?>
					</a>
					<?php endif; ?>
				</h1>
				<?php // Tagline
				if ( 1 !== $hide_tagline && get_bloginfo( 'description' ) ) : ?>
				<span class="site-description">
					<?php bloginfo( 'description' ); ?>
				</span>
				<?php endif; ?>
			</div>

			<nav id="site-navigation" class="site-navigation" role="navigation">
				<span class="menu-toggle"><?php echo esc_html( $menu_label ); ?></span>
				<a class="skip-link screen-reader-text" href="#site-content"><?php _e( 'Skip to content', 'make' ); ?></a>
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary'
				) );
				?>
				<?php if (!is_singular()) { ?>
				<span class="time-controls"> 
				<a class="time-control-link" href=<?php echo add_query_arg('timerange','currentday', remove_query_arg('timerange'))?> > Today </a> 
				<a class="time-control-link" href=<?php echo add_query_arg('timerange','currentweek',remove_query_arg('timerange'))?> > This Week </a>
				<a class="time-control-link" href=<?php echo add_query_arg('timerange','currentmonth', remove_query_arg('timerange'))?> > This Month </a>
				<a class="time-control-link" href=<?php echo add_query_arg('timerange','currentyear', remove_query_arg('timerange'))?> > This Year </a>
				</span>
				<?php } ?>
			</nav>
		</div>
	</div>
</header>