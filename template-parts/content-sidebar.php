<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="zuul-section-title column-1">
		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php zuul_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php zuul_section_intro( $post->ID ); ?>
	</div><!-- .zuul-section-title -->

	<?php if ( has_post_thumbnail() ) : ?>
        <figure class="zuul-featured-single"><?php the_post_thumbnail( 'zuul-featured' ); ?></figure>
    <?php endif; ?>

	<div class="column-1 single-content content-with-sidebar">

		<?php do_action( 'zuul_before_the_content' ); ?>

		<div class="entry-content sidebar-content">
			<?php
				the_content( sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'zuul-lite' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'zuul-lite' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<?php get_sidebar(); ?>

		<?php do_action( 'zuul_after_the_content' ); ?>

	</div><!-- .column-1 -->

	<?php if ( is_singular('post') ) : ?>

		<footer class="entry-footer">
			<?php zuul_author_meta(); ?>
			<?php if ( get_the_author_meta('description') ) : ?>
				<p><?php the_author_meta('description'); ?></p>
			<?php endif; ?>
			<?php zuul_entry_footer(); ?>
		</footer><!-- .entry-footer -->

	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
