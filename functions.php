<?php
/**
 * Visily Convert Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Visily_Convert
 */

if ( ! defined( 'VISILY_CONVERT_VERSION' ) ) {
	define( 'VISILY_CONVERT_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function visily_convert_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'visily-convert', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register nav menus.
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'visily-convert' ),
			'footer'  => esc_html__( 'Footer Menu', 'visily-convert' ),
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for core block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );

	// Add custom logo support.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);
}
add_action( 'after_setup_theme', 'visily_convert_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
function visily_convert_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'visily_convert_content_width', 640 );
}
add_action( 'wp_head', 'visily_convert_content_width', 0 );

/**
 * Register widget area.
 */
function visily_convert_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Primary Sidebar', 'visily-convert' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'Main sidebar that appears on the right', 'visily-convert' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Widget Area', 'visily-convert' ),
			'id'            => 'footer',
			'description'   => esc_html__( 'Widget area in footer', 'visily-convert' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'visily_convert_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function visily_convert_scripts() {
	wp_enqueue_style( 'visily-convert-style', get_stylesheet_uri(), array(), VISILY_CONVERT_VERSION );
	wp_style_add_data( 'visily-convert-style', 'rtl', 'replace' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Enqueue custom JS
	wp_enqueue_script( 'visily-convert-script', get_template_directory_uri() . '/assets/js/main.js', array(), VISILY_CONVERT_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'visily_convert_scripts' );
