<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package Visily_Convert
 */

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col" style="flex: 2;">
			<main id="main" class="site-main">

				<?php
				if ( have_posts() ) :

					if ( is_home() && ! is_front_page() ) :
						?>
						<header>
							<h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
						</header>
						<?php
					endif;

					// Load the individual posts.
					while ( have_posts() ) :
						the_post();

						/
						 * Include the Post-Format-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;

					// Previous/next posts navigation.
					the_posts_pagination(
						array(
							'prev_text' => esc_html__( 'Previous', 'visily-convert' ),
							'next_text' => esc_html__( 'Next', 'visily-convert' ),
						)
					);

				else :

					// If no content, include the "No posts found" template.
					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>

			</main><!-- #main -->
		</div><!-- .col -->

		<div class="col">
			<?php
			if ( is_active_sidebar( 'primary' ) ) {
				dynamic_sidebar( 'primary' );
			}
			?>
		</div><!-- .col -->
		</div><!-- .row -->
</div><!-- .container -->

<?php
get_footer();