<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('zuul-loop-post'); ?>>

	<?php if ( has_post_thumbnail() ) { ?>

		<figure class="zuul-featured-hp"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'zuul-featured' ); ?></a></figure>

	<?php } ?>

	<div class="zuul-section-title">
		<?php if ( 'post' === get_post_type() ) : ?>
			<div class="entry-categories">
				<?php $cats = get_the_category(); ?>
				<ul class="post-categories">
					<li><a href="<?php echo get_term_link( $cats[0], 'category' ); ?>"><?php echo esc_html( $cats[0]->name ); ?></a></li>
				</ul>
			</div><!-- .entry-meta -->
		<?php endif; ?>

		<div class="zuul-intro">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		</div>

		<?php if ( 'post' === get_post_type() ) : ?>
			<?php zuul_author_meta(); ?>
		<?php endif; ?>
	</div><!-- .zuul-section-title -->

	<div class="hp-content">

    	<div class="entry-content narrow-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->

	</div><!-- .column-1 -->

</article><!-- #post-<?php the_ID(); ?> -->
