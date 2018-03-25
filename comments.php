<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package zuul-lite
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<div class="column-1">
		<h2 class="comments-intro"><?php echo __('Let\'s Talk', 'zuul-lite'); ?></h2>
		<?php
		// You can start editing here -- including this comment!
		if ( have_comments() ) : ?>
			<h3 class="comments-title">
				<?php
				$comment_count = get_comments_number();
				if ( 1 === $comment_count ) {
					printf(
						/* translators: 1: title. */
						esc_html_e( 'One reply to &ldquo;%1$s&rdquo;', 'zuul-lite' ),
						'<span>' . get_the_title() . '</span>'
					);
				} else {
					printf( // WPCS: XSS OK.
						/* translators: 1: comment count number, 2: title. */
						esc_html( _nx( '%1$s replies to &ldquo;%2$s&rdquo;', '%1$s replies to &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'zuul-lite' ) ),
						number_format_i18n( $comment_count ),
						'<span>' . get_the_title() . '</span>'
					);
				}
				?>
			</h3><!-- .comments-title -->

			<ol class="comment-list">
				<?php
					wp_list_comments( array(
						'style'      => 'ol',
						'short_ping' => true,
						'callback'	 => 'zuul_comments'
					) );
				?>
			</ol><!-- .comment-list -->

			<?php the_comments_navigation();

			// If comments are closed and there are comments, let's leave a little note, shall we?
			if ( ! comments_open() ) : ?>
				<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'zuul-lite' ); ?></p>
			<?php
			endif;

		endif; // Check for have_comments().

		comment_form();
		?>
	</div><!-- .column-1 -->
</div><!-- #comments -->
