<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */
 global $zuul_hero_displayed;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="zuul-section-title column-1">
		<?php zuul_section_intro( $post->ID ); ?>
	</div><!-- .zuul-section-title -->

    <?php if ( has_post_thumbnail() ) : ?>
        <figure class="zuul-featured-single"><?php the_post_thumbnail( 'zuul-featured' ); ?></figure>
    <?php endif; ?>

    <div class="column-1 page-content">

        <?php do_action( 'zuul_before_the_content' ); ?>

        <?php if ( is_page_template('page-templates/template-full-width.php') || is_page_template('page-templates/template-sidebar.php') ) {
            $content_class = "full-content";
        } else {
            $content_class = "narrow-content";
        } ?>
    	<div class="entry-content <?php echo esc_attr( $content_class ); ?>">
    		<?php
    			the_content();

    			wp_link_pages( array(
    				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'zuul-lite' ),
    				'after'  => '</div>',
    			) );
    		?>
    	</div><!-- .entry-content -->

        <?php do_action( 'zuul_after_the_content' ); ?>

    </div>

	<?php if ( get_edit_post_link() ) : ?>
        <div class="edit-link-wrap">
		<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'zuul-lite' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
		?>
        </div>
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
