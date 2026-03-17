<?php
/**
 * Visily Convert Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Visily_Convert_Theme
 */

if ( ! defined( 'VISILY_CONVERT_THEME_VERSION' ) ) {
    define( 'VISILY_CONVERT_THEME_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function visily_convert_theme_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_theme_textdomain( 'visily-convert-theme', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    // Set default thumbnail size.
    set_post_thumbnail_size( 1200, 630, true );

    // Add additional image sizes.
    add_image_size( 'visily-featured', 800, 450, true );
    add_image_size( 'visily-thumbnail', 400, 225, true );

    /*
     * This theme uses wp_nav_menus() in one location.
     */
    register_nav_menus(
        array(
            'primary'   => esc_html__( 'Primary Menu', 'visily-convert-theme' ),
            'footer'    => esc_html__( 'Footer Menu', 'visily-convert-theme' ),
            'social'    => esc_html__( 'Social Links Menu', 'visily-convert-theme' ),
        )
    );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Set up the WordPress core custom background feature.
    add_theme_support(
        'custom-background',
        apply_filters(
            'visily_convert_theme_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 100,
            'width'       => 300,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    // Add support for Block Styles.
    add_theme_support( 'wp-block-styles' );

    // Add support for full and wide align images.
    add_theme_support( 'align-wide' );

    // Add support for editor styles.
    add_theme_support( 'editor-styles' );

    // Add support for responsive embedded content.
    add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', 'visily_convert_theme_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function visily_convert_theme_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'visily_convert_theme_content_width', 1200 );
}
add_action( 'after_setup_theme', 'visily_convert_theme_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function visily_convert_theme_widgets_init() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'Sidebar', 'visily-convert-theme' ),
            'id'            => 'sidebar-1',
            'description'   => esc_html__( 'Add widgets here.', 'visily-convert-theme' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Widget Area 1', 'visily-convert-theme' ),
            'id'            => 'footer-1',
            'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'visily-convert-theme' ),
            'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Widget Area 2', 'visily-convert-theme' ),
            'id'            => 'footer-2',
            'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'visily-convert-theme' ),
            'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__( 'Footer Widget Area 3', 'visily-convert-theme' ),
            'id'            => 'footer-3',
            'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'visily-convert-theme' ),
            'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'visily_convert_theme_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function visily_convert_theme_scripts() {
    // Main stylesheet.
    wp_enqueue_style(
        'visily-convert-theme-style',
        get_stylesheet_uri(),
        array(),
        VISILY_CONVERT_THEME_VERSION
    );

    // Theme script.
    wp_enqueue_script(
        'visily-convert-theme-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        array(),
        VISILY_CONVERT_THEME_VERSION,
        true
    );

    // Comment reply script.
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    // Pass theme variables to JavaScript.
    wp_localize_script(
        'visily-convert-theme-navigation',
        'visilyConvertTheme',
        array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'visily_convert_theme_nonce' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'visily_convert_theme_scripts' );

/**
 * Custom template tags for this theme.
 */
function visily_convert_theme_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
        $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }

    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( DATE_W3C ) ),
        esc_html( get_the_date() ),
        esc_attr( get_the_modified_date( DATE_W3C ) ),
        esc_html( get_the_modified_date() )
    );

    $posted_on = sprintf(
        /* translators: %s: post date */
        esc_html_x( 'Posted on %s', 'post date', 'visily-convert-theme' ),
        '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
    );

    echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Print HTML with meta information for the current author.
 */
function visily_convert_theme_posted_by() {
    $byline = sprintf(
        /* translators: %s: post author */
        esc_html_x( 'by %s', 'post author', 'visily-convert-theme' ),
        '<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
    );

    echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Determine whether a post has a large image or not.
 *
 * @return bool
 */
function visily_convert_theme_can_show_post_thumbnail() {
    return apply_filters( 'visily_convert_theme_can_show_post_thumbnail', ! post_password_required() && ! is_attachment() && has_post_thumbnail() );
}

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function visily_convert_theme_pingback_header() {
    if ( is_singular() && pings_open() ) {
        printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
    }
}
add_action( 'wp_head', 'visily_convert_theme_pingback_header' );
