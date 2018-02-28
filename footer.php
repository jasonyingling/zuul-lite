<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package zuul-lite
 */

?>

	</div><!-- #content -->



	<footer id="colophon" class="site-footer">
		<div class="container">
			<?php if ( is_active_sidebar( 'footer_column_1' ) || is_active_sidebar( 'footer_column_2' ) || is_active_sidebar( 'footer_column_3' ) || is_active_sidebar( 'footer_column_4' ) ) : ?>
				<aside class="footer-widgets">
					<?php if ( is_active_sidebar( 'footer_column_1' ) ) : ?>
						<div class="footer-column">
							<?php dynamic_sidebar('footer_column_1'); ?>
						</div>
					<?php endif; ?>
					<?php if ( is_active_sidebar( 'footer_column_2' ) ) : ?>
						<div class="footer-column">
							<?php dynamic_sidebar('footer_column_2'); ?>
						</div>
					<?php endif; ?>
					<?php if ( is_active_sidebar( 'footer_column_3' ) ) : ?>
						<div class="footer-column">
							<?php dynamic_sidebar('footer_column_3'); ?>
						</div>
					<?php endif; ?>
					<?php if ( is_active_sidebar( 'footer_column_4' ) ) : ?>
						<div class="footer-column">
							<?php dynamic_sidebar('footer_column_4'); ?>
						</div>
					<?php endif; ?>
				</aside><!-- .footer-widgets -->
			<?php endif; ?>
			<div class="site-info">
				<div class="site-copyright">
					<?php
						$date = sprintf( esc_html__(  'Copyright %1$s, %2$s', 'zuul-lite' ), date('Y'), get_bloginfo('name') );
						echo apply_filters( 'zuul_copyright_info', $date );
					?>
				</div>
				<?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
					<div class="footer-menu">
						<?php
		                    wp_nav_menu( array(
		                        'theme_location' => 'footer-menu',
		                        'menu_id'        => 'footer-menu',
		                    ) );
		                ?>
					</div>
				<?php endif; ?>
			</div><!-- .site-info -->
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
