<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Visily_Convert_Theme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php
        if ( is_singular() ) :
            the_title( '<h1 class="entry-title">', '</h1>' );
        else :
            the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
        endif;

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta">
                <?php
                visily_convert_theme_posted_on();
                visily_convert_theme_posted_by();

                if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
                    echo '<span class="comments-link">';
                    comments_popup_link(
                        sprintf(
                            wp_kses(
                                /* translators: %s: post title */
                                __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'visily-convert-theme' ),
                                array(
                                    'span' => array(
                                        'class' => array(),
                                    ),
                                )
                            ),
                            wp_kses_post( get_the_title() )
                        )
                    );
                    echo '</span>';
                }

                edit_post_link(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post */
                            __( 'Edit <span class="screen-reader-text">%s</span>', 'visily-convert-theme' ),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post( get_the_title() )
                    ),
                    '<span class="edit-link">',
                    '</span>'
                );
                ?>
            </div><!-- .entry-meta -->
            <?php
        endif;
        ?>
    </header><!-- .entry-header -->

    <?php if ( visily_convert_theme_can_show_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <?php
            if ( is_singular() ) :
                the_post_thumbnail( 'visily-featured' );
            else :
                ?>
                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                    <?php the_post_thumbnail( 'visily-thumbnail' ); ?>
                </a>
                <?php
            endif;
            ?>
        </div><!-- .post-thumbnail -->
    <?php endif; ?>

    <div class="entry-content">
        <?php
        if ( is_singular() ) :
            the_content(
                sprintf(
                    wp_kses(
                        /* translators: %s: Name of current post. Only visible to screen readers */
                        __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'visily-convert-theme' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                )
            );

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'visily-convert-theme' ),
                    'after'  => '</div>',
                )
            );

        else :
            the_excerpt();
            ?>
            <a href="<?php the_permalink(); ?>" class="read-more-link">
                <?php
                printf(
                    wp_kses(
                        /* translators: %s: Name of current post */
                        __( 'Read more<span class="screen-reader-text"> "%s"</span>', 'visily-convert-theme' ),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    wp_kses_post( get_the_title() )
                );
                ?>
            </a>
            <?php
        endif;
        ?>
    </div><!-- .entry-content -->

    <?php if ( 'post' === get_post_type() ) : ?>
        <footer class="entry-footer">
            <?php
            $categories_list = get_the_category_list( esc_html__( ', ', 'visily-convert-theme' ) );
            if ( $categories_list ) {
                /* translators: 1: list of categories */
                printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'visily-convert-theme' ) . '</span>', wp_kses_post( $categories_list ) );
            }

            $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'visily-convert-theme' ) );
            if ( $tags_list ) {
                /* translators: 1: list of tags */
                printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'visily-convert-theme' ) . '</span>', wp_kses_post( $tags_list ) );
            }
            ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
