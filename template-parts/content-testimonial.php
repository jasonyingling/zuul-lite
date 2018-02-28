<?php
/**
 * Template part for displaying products on template-homepage.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article <?php post_class('zuul-testimonial column-2'); ?>>

	<div class="zuul-post-wrap">

		<section class="zuul-post-content">
			<?php the_content(); ?>
		</section>

		<footer class="zuul-post-footer">
			<div class="zuul-author-meta">
				<?php if ( class_exists('acf') && get_field('author_image') ) : ?>
					<figure class="zuul-author-thumb">
						<?php $author_image = get_field('author_image'); ?>
						<?php echo wp_get_attachment_image( $author_image['id'] ); ?>
					</figure>
				<?php endif; ?>
				<div class="zuul-author-info">
					<?php if ( class_exists('acf') && ( get_field('author_link') || get_field('ara_title') ) ) : ?>
						<a href="<?php the_field('author_link') ?>" class="zuul-author-name" target="_blank"><?php the_title(); ?></a>
						<span class="zuul-author-title"><?php the_field( 'ara_title' ); ?></span>
					<?php else : ?>
						<span class="zuul-author-name"><?php the_title(); ?></span>
						<span class="zuul-author-title">&nbsp;</span>
					<?php endif; ?>

				</div>
			</div><!-- .zuul-author-meta -->
		</footer>
	</div>

	<?php if ( has_post_thumbnail() ) : ?>
		<?php $thumb_url = get_the_post_thumbnail_url($post->ID, 'medium'); ?>
		<div class="zuul-image-background" style="background-image: url('<?php echo esc_url( $thumb_url ); ?>');"></div>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
