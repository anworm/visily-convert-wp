<?php
/**
 * The footer for the Visily Convert theme
 *
 * Contains the closing tags opened in header.php and all the structure and widgets
 * for the footer area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Visily_Convert_Theme
 */
?>

    <footer id="colophon" class="site-footer">
        <div class="container">

            <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
                <div class="footer-widgets">

                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <div class="footer-widget-area footer-widget-area-1">
                            <?php dynamic_sidebar( 'footer-1' ); ?>
                        </div><!-- .footer-widget-area-1 -->
                    <?php endif; ?>

                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <div class="footer-widget-area footer-widget-area-2">
                            <?php dynamic_sidebar( 'footer-2' ); ?>
                        </div><!-- .footer-widget-area-2 -->
                    <?php endif; ?>

                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <div class="footer-widget-area footer-widget-area-3">
                            <?php dynamic_sidebar( 'footer-3' ); ?>
                        </div><!-- .footer-widget-area-3 -->
                    <?php endif; ?>

                </div><!-- .footer-widgets -->
            <?php endif; ?>

            <?php if ( has_nav_menu( 'footer' ) ) : ?>
                <nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Navigation', 'visily-convert-theme' ); ?>">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'container'      => false,
                            'depth'          => 1,
                        )
                    );
                    ?>
                </nav><!-- .footer-navigation -->
            <?php endif; ?>

        </div><!-- .container -->

        <div class="site-info">
            <div class="container">
                <span class="copyright">
                    &copy;
                    <?php
                    echo esc_html( gmdate( 'Y' ) );
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php bloginfo( 'name' ); ?>
                    </a>.
                    <?php esc_html_e( 'All rights reserved.', 'visily-convert-theme' ); ?>
                </span>
                <span class="powered-by">
                    <?php
                    printf(
                        /* translators: %s: WordPress URL */
                        esc_html__( 'Powered by %s', 'visily-convert-theme' ),
                        '<a href="' . esc_url( __( 'https://wordpress.org/', 'visily-convert-theme' ) ) . '">WordPress</a>'
                    );
                    ?>
                </span>
            </div><!-- .container -->
        </div><!-- .site-info -->

    </footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
