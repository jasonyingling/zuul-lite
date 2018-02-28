<?php
/**
 * Template part for displaying page content on the home page
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

?>

<div class="zuul-loop">
	<div class="column-1 page-content">
		<?php if ( is_page_template('page-templates/template-full-width.php') ) {
            $content_class = "full-content";
        } else {
            $content_class = "narrow-content";
        } ?>
		<div class="entry-content <?php echo esc_attr( $content_class ); ?>">
			<?php
				the_content();
			?>
		</div><!-- .entry-content -->

	</div>
</div>
