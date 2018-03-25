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
			<div class="entry-categories">
				<?php $cats = get_the_category(); ?>
				<ul class="post-categories">
					<li><a href="<?php echo get_term_link( $cats[0], 'category' ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a></li>
				</ul>
			</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php zuul_section_intro( $post->ID ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<?php zuul_author_meta(); ?>
		<?php endif; ?>
	</div><!-- .zuul-section-title -->

	<?php
	// Get the post content
	$content = apply_filters( 'the_content', $post->post_content );

	// Check for video post format content
	$media = get_media_embedded_in_content( $content );

	// If it's a video format, get the first video embed from the post to replace the featured image
	if ( has_post_format( 'video' ) && ! empty( $media ) ) {

		echo '<figure class="zuul-featured-single"><div class="featured-video">';
			echo $media[0];
		echo '</div></figure>';

	}
	// If it's a gallery format, get the first gallery from the post to replace the featured image
	else if ( has_post_format( 'gallery' ) ) {

		echo '<figure class="zuul-featured-single featured-gallery">';
			echo get_post_gallery();
		echo '</figure>';

	} else if ( has_post_thumbnail() ) { ?>

		<figure class="zuul-featured-single"><?php the_post_thumbnail( 'zuul-featured' ); ?></figure>

	<?php

	} wp_reset_postdata(); ?>

	<div class="column-1 single-content">

		<?php do_action( 'zuul_before_the_content' ); ?>

		<?php if ( is_page_template('page-templates/template-full-width.php') || is_page_template('page-templates/template-sidebar.php') ) {
            $content_class = "full-content";
        } else {
            $content_class = "narrow-content";
        } ?>
    	<div class="entry-content <?php echo esc_attr( $content_class ); ?>">
			<?php
				if ( has_post_format( 'video' ) || has_post_format( 'gallery') ) {
					zuul_filtered_content();
				} else {
					the_content();
				}

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'zuul-lite' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<?php do_action( 'zuul_after_the_content' ); ?>

	</div><!-- .column-1 -->

	<?php if ( is_singular('post') ) : ?>

		<footer class="entry-footer">
			<?php echo zuul_posted_on(); ?>
			<?php zuul_entry_footer(); ?>

			<?php zuul_single_author_meta(); ?>

			<?php zuul_related_posts( $post->ID, 'category' ); ?>
		</footer><!-- .entry-footer -->

	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
