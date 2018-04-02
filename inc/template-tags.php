<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package zuul-lite
 */

if ( ! function_exists( 'zuul_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function zuul_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">' . __( 'Published: ', 'zuul-lite' ) . '%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">' . __( 'Published: ', 'zuul-lite' ) . '%2$s</time><time class="updated" datetime="%3$s">' . __( 'Updated: ', 'zuul-lite' ) . '%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		return '<span class="posted-on">' . $time_string . '</span>'; // WPCS: XSS OK.

	}
endif;

if ( ! function_exists( 'zuul_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function zuul_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'zuul-lite' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links"><i class="fa fa-folder-open" aria-hidden="true"></i>%1$s</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'zuul-lite' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links"><i class="fa fa-tags" aria-hidden="true"></i>%1$s</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'zuul-lite' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( 'zuul_edit_post' ) ) :
	/**
	 * Prints HTML with edit post link
	 */
	function zuul_edit_post() {
		echo '<div class="edit-link-wrap">';
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
		echo '</div>';
	}
endif;

if ( ! function_exists( 'zuul_section_intro' ) ) :
	/**
	 * Prints HTML for the section title and intro copy
	 */
	 function zuul_section_intro( $post_id = null ) {

		global $zuul_hero_displayed;

		if ( $zuul_hero_displayed && ! is_front_page() ) {
			return;
		}

		$section_meta = '';

		if ( is_singular() && ! is_front_page() ) {
			$section_title = '<h1 class="entry-title">' . get_the_title( $post_id ) . '</h1>';
			$section_intro = ( has_excerpt($post_id) ) ? '<p>' . get_the_excerpt( $post_id ) . '</p>' : '';
		} elseif( is_archive() ) {
			$section_title = '<h1 class="archive-title">' . get_the_archive_title( $post_id ) . '</h1>';
			$section_intro = ( get_the_archive_description( $post_id ) ) ? get_the_archive_description( $post_id ) : '';
		} else {
			$section_title = '<h2 class="entry-title">' . get_the_title( $post_id ) . '</h2>';
			$section_intro = ( has_excerpt($post_id) ) ? '<p>' . get_the_excerpt( $post_id ) . '</p>' : '';
		}


		printf( '<header class="zuul-intro zuul-bigger-content">%s%s%s</header>', $section_meta, $section_title, $section_intro );

	 }
endif;

if ( ! function_exists( 'zuul_author_meta' ) ) :
	/**
	 * Prints HTML for the author meta info
	 */
	 function zuul_author_meta() {
		 ?>

		 <div class="zuul-author-meta">
			 <figure class="zuul-author-thumb">
			 	<?php echo get_avatar( get_the_author_meta( 'ID' ), 136 ); ?>
			 </figure>
			 <div class="zuul-author-info">
				 <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="zuul-author-name"><?php the_author(); ?></a>
				 <span class="zuul-author-title"><?php the_author_meta('nickname'); ?></span>
			 </div>
		 </div><!-- .zuul-author-meta -->

		 <?php
	 }
endif;

if ( ! function_exists( 'zuul_single_author_meta' ) ) :
	/**
	 * Prints HTML for the author meta info
	 */
	 function zuul_single_author_meta() {
		 ?>

		 <div class="zuul-single-author-meta">
			 <div class="zuul-single-author-bio">
			 	<?php the_author_meta('description'); ?>
			 </div><!-- .zuul-single-author-bio -->
			 <figure class="zuul-single-author-thumb">
			 	<?php echo get_avatar( get_the_author_meta( 'ID' ), 180 ); ?>
			 </figure>
			 <div class="zuul-single-author-info">
				 <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="zuul-author-name"><?php the_author(); ?></a>
				 <?php if ( get_the_author_meta('nickname') ) : ?>
					 <span class="zuul-single-author-title"><?php the_author_meta('nickname'); ?></span>
				 <?php endif; ?>
				 <?php $author_twitter = get_the_author_meta( 'twitter' ); ?>
				 <?php if ( $author_twitter ) : ?>
				 	<a href="https://www.twitter.com/<?php echo esc_url( $author_twitter ); ?>" target="_blank" class="zuul-single-author-link"><i class="fa fa-twitter" aria-hidden="true"></i></a>
				 <?php endif; ?>
			 </div>
		 </div><!-- .zuul-author-meta -->

		 <?php
	 }
endif;

if ( ! function_exists( 'zuul_related_posts' ) ) :
	/**
	 * Prints HTML for the Related Posts
	 */
	 function zuul_related_posts( $post_id, $taxonomy ) {

		 	$post_type = get_post_type( $post_id );
			$terms = get_the_terms( $post_id, $taxonomy );

			$term_ids = array();

			foreach ( $terms as $term ) {
				array_push( $term_ids, $term->term_id );
			}

			$args = array (
				'post_type'			=> $post_type,
				'post__not_in'		=> array( $post_id ),
				'posts_per_page' 	=> 2,
				'tax_query' 		=> array(
					array(
						'taxonomy'	=> $taxonomy,
						'field'		=> 'term_id',
						'terms'		=> $term_ids,
					),
				),
			);

			$the_query = new WP_Query($args);

			if ( $the_query->have_posts() ) : ?>

			<div class="zuul-related-posts zuul-hp-loop">
				<h3><?php echo __( 'Related Posts', 'zuul-lite' ); ?></h3>

				<?php while( $the_query->have_posts() ) : $the_query->the_post(); ?>

					<?php get_template_part( 'template-parts/content', 'related-post' ); ?>

				<?php endwhile; wp_reset_postdata(); ?>

			</div>

		<?php endif;

	 }
endif;

if ( ! function_exists( 'zuul_pagination' ) ) :
	/**
	 * Outputs pagination
	 */
	function zuul_pagination( $query = false ) {
		global $wp_query;
		if( $query ) {
			$temp_query = $wp_query;
			$current_query = $query;
		} else {
			$current_query = $wp_query;
		}

		// Return early if there's only one page.
		if ( $current_query->max_num_pages < 2 ) {
			return;
		}

		$big = 999999999; // need an unlikely integer

		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'current'   => max( 1, get_query_var('paged') ),
			'total'     => $current_query->max_num_pages,
	 		'prev_text'          => __('<i class="fa fa-long-arrow-left" aria-hidden="true"></i>', 'zuul-lite'),
	 		'next_text'          => __('<i class="fa fa-long-arrow-right" aria-hidden="true"></i>', 'zuul-lite'),
	 	);
	 	$zuul_pagination = paginate_links( $args );

		printf( '<div class="column-1 zuul-pagination posts-navigation">%s</div>', $zuul_pagination);
	}

endif;

if ( ! function_exists( 'zuul_hero_content' ) ) :

	function zuul_hero_content($title = null, $desc = null, $cta = null, $link = null) {

		global $zuul_hero_displayed;

		if ( is_page_template('page-templates/template-homepage.php') && is_front_page() ) {
			$page_id = get_the_ID();
			$title = esc_html( get_theme_mod( 'zuul_hero_title', get_bloginfo('name') ) );
			$desc = esc_html( get_theme_mod( 'zuul_hero_desc', get_bloginfo('description') ) );
			$cta = esc_html( get_theme_mod( 'zuul_hero_cta' ) );
			$link = esc_url( get_theme_mod( 'zuul_hero_link' ) );
		} elseif ( is_home() ) {
			$page_id = get_option('page_for_posts');
		} else {
			$page_id = get_the_ID();
		}

		if (
			is_singular('post') ||
			is_singular('product') ||
			is_singular('download') ||
			is_singular('jetpack-testimonial') ||
			is_singular('jetpack-portfolio') ||
			( is_page() && ! is_page_template('page-templates/template-homepage.php') ) ||
			is_tax() ||
			( is_archive() ) ||
			is_search() ||
			is_404() ||
			( is_home() && is_front_page() )
		) {
			return;
		}

		$content = '';
		if ($desc) {
			$description = $desc;
		} elseif ( has_excerpt( $page_id ) ) {
			$description = get_the_excerpt( $page_id );
		} else {
			$description = '';
		}
		$hero_content = array(
			'title' => ($title) ? $title : get_the_title( $page_id ),
			'description' => $description,
			'call_to_action_link' => $link,
			'call_to_action' => $cta,
		);

		if ( class_exists( 'acf' ) && ! is_page_template('page-templates/template-homepage.php') ) {
			$hero_content['call_to_action'] = sanitize_text_field( get_field('ara_call_to_action', $page_id) );
			$hero_content['call_to_action_link'] = esc_url_raw( get_field('ara_call_to_action_link', $page_id) );
		}

		if( has_filter('zuul_hero_content') ) {
			$hero_content = apply_filters('zuul_hero_content', $hero_content);
		}

		if ( $hero_content['title'] ) {
			$content .= '<h1 class="zuul-hero-title">' . $hero_content['title'] . '</h1>';
		}

		if ( $hero_content['description'] ) {
			$content .= '<h2 class="zuul-hero-desc">' . $hero_content['description'] . '</h2>';
		}

		if ( $hero_content['call_to_action_link'] && $hero_content['call_to_action'] ) {
			$zuul_hero_cta_markup = '<a href="' . $hero_content['call_to_action_link'] . '" class="zuul-button">' . $hero_content['call_to_action'] . '</a>';

			$content .= apply_filters('zuul_hero_cta_markup', $zuul_hero_cta_markup);
		}

		if ( $content ) {
			$zuul_hero_displayed = true;
			printf('<section class="zuul-hero-copy">%s</section>', $content);
		}

	}

endif;

if ( ! function_exists( 'zuul_svg_angle' ) ) :

	function zuul_svg_angle() {

		global $zuul_hero_displayed;

		if ( ! $zuul_hero_displayed ) {
			return;
		}

		?>
			<svg class="zuul-angle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
				<polygon fill="<?php echo '#' . esc_attr( get_theme_mod('background_color', 'ffffff') ); ?>" points="0,100 100,0 100,100"/>
			</svg>
		<?php
	}

endif;

if ( ! function_exists( 'zuul_comments' ) ) :

	function zuul_comments($comment, $args, $depth) {
	    if ( 'div' === $args['style'] ) {
	        $tag       = 'div';
	        $add_below = 'comment';
	    } else {
	        $tag       = 'li';
	        $add_below = 'div-comment';
	    }
	    ?>
	    <<?php echo esc_attr($tag) ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	    <?php if ( 'div' != $args['style'] ) : ?>
	        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	    <?php endif; ?>

	    <?php if ( $comment->comment_approved == '0' ) : ?>
	         <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'zuul-lite' ); ?></em>
	          <br />
	    <?php endif; ?>

	    <?php comment_text(); ?>

		<div class="zuul-author-meta">
			<?php if ( get_avatar( $comment ) ) : ?>
				<figure class="zuul-author-thumb">
				   <?php echo get_avatar( $comment, 136 ); ?>
				</figure>
			<?php endif; ?>
				<div class="zuul-author-info">
				<?php echo get_comment_author_link(); ?>
				<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
			        <?php
			        /* translators: 1: date, 2: time */
			        printf( __('%1$s at %2$s', 'zuul-lite'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'zuul-lite' ), '  ', '' );
			        ?>
			    </div>
			</div>
		</div><!-- .zuul-author-meta -->

	    <div class="reply">
	        <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	    </div>
	    <?php if ( 'div' != $args['style'] ) : ?>
	    </div>
	    <?php endif; ?>
	    <?php
    }

endif;

/**
 * Modify the archive title prefix
 */
 function zuul_modify_archive_title( $title ) {
	// Skip if the site isn't LTR, this is visual, not functional.
	if ( is_rtl() ) {
		return $title;
	}

	// Split the title into parts so we can wrap them with spans.
	$title_parts = explode( ': ', $title, 2 );

	// Glue it back together again.
	if ( ! empty( $title_parts[1] ) ) {
		$title = wp_kses(
			$title_parts[1],
			array(
				'span' => array(
					'class' => array(),
				),
			)
		);
		$title = '<span>' . esc_html( $title_parts[0] ) . ': </span>' . $title;
	}
	return $title;
}
add_filter( 'get_the_archive_title', 'zuul_modify_archive_title' );

/**
 * Count our number of active panels.
 *
 * Primarily used to see if we have any panels active, duh.
 */
function zuul_panel_count() {

	$panel_count = 0;

	/**
	 * Filter number of front page sections
	 */
	$num_sections = apply_filters( 'zuul_front_page_sections', 7 );

	// Create a setting and control for each of the sections available in the theme.
	for ( $i = 1; $i < ( 1 + $num_sections ); $i++ ) {
		if ( get_theme_mod( 'panel_' . $i ) ) {
			$panel_count++;
		}
	}

	return $panel_count;
}


/**
 * Display Front Page Sections
 *
 * @since 1.0.0
 */
function zuul_front_page_section( $partial = null, $id = 0 ) {
	if ( is_a( $partial, 'WP_Customize_Partial' ) ) {
		// Find out the id and set it up during a selective refresh.
		global $zuul_counter;
		$id = str_replace( 'panel_', '', $partial->id );
		$zuul_counter = $id;
	}

	global $post; // Modify the global post object before setting up post data.
	global $zuul_counter;

	if ( get_theme_mod( 'panel_' . $id ) ) {
		global $post;
		$post = get_post( get_theme_mod( 'panel_' . $id ) );
		//$post = get_post( 733 );
		setup_postdata( $post );
		set_query_var( 'panel', $id );

		// grab post template value
		$page_template = get_post_meta( $post->ID, '_wp_page_template', true );
		$permalink = get_the_permalink( $post->ID );
		?>

		<section class="zuul-content" id="panel<?php echo esc_attr( $zuul_counter ); ?>">

			<div class="container">

				<?php $include_more = true; ?>

					<?php
						// Retreive the homepage section templates
						if ( $post->ID == get_option( 'woocommerce_shop_page_id' ) || $page_template == 'page-templates/template-products.php' ) {
							zuul_section_intro( $post->ID );
							get_template_part( 'template-parts/content-products' );
						} else if ( $page_template == 'page-templates/template-portfolio.php' ) {
							zuul_section_intro( $post->ID );
							get_template_part( 'template-parts/content-projects' );
						} else if ( $post->ID == get_option( 'page_for_posts' ) || $page_template == 'page-templates/template-blog.php' ) {
							zuul_section_intro( $post->ID );
							get_template_part( 'template-parts/content-blog' );
						} else if ( $page_template == 'page-templates/template-features.php' ) {
							zuul_section_intro( $post->ID );
							get_template_part( 'template-parts/content-features' );
						} else if ( $page_template == 'page-templates/template-downloads.php' ) {
							zuul_section_intro( $post->ID );
							get_template_part( 'template-parts/content-downloads-loop' );
						} else if ( $page_template == 'page-templates/template-testimonial.php' ) {
							zuul_section_intro( $post->ID );
							get_template_part( 'template-parts/content-testimonials' );
						} else {
							get_template_part( 'template-parts/content-section' );
							$include_more = false;
						}

						wp_reset_postdata();
	 				?>


				<?php if ( $include_more ) : ?>

					<div class="column-1 text-center"><a href="<?php echo esc_url( $permalink ); ?>" class="zuul-more"><?php echo __('See the rest', 'zuul-lite'); ?></a></div>

				<?php endif; ?>

			</div>

		</section>

		<?php

	} elseif ( is_customize_preview() ) {
		// The output placeholder anchor.
		echo '<section class="placeholder-section container" id="panel' . $id . '"><h2>' . sprintf( __( 'Section %1$s Placeholder', 'zuul-lite' ), $id ) . '</h2></section>';
	}
}
