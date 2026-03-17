<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Visily_Convert
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>><head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to primary content', 'visily-convert' ); ?></a>

		<header id="masthead" class="site-header">
			<div class="container">
				<div class="site-branding">
					<?php
					the_custom_logo();

					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php
					else :
						?>
						<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php
					endif;

					$visily_convert_description = get_bloginfo( 'description', 'display' );
					if ( $visily_convert_description || is_customize_preview() ) :
						?>
						<p class="site-description"><?php echo $visily_convert_description; ?></p>
						<?php
					endif;
					?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="site-nav">
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'fallback_cb'    => 'wp_page_menu',
						)
					);
					?>
				</nav><!-- #site-navigation -->
			</div>
		</header><!-- #masthead -->
