<header id="zuul-masthead" class="nv-site-header">

    <?php
	    $header_opacity = get_theme_mod( 'zuul_bg_opacity', '.5' );
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) { ?>
			<div class="site-header-bg" style="opacity: <?php echo esc_attr( $header_opacity ); ?>;">
                <?php the_custom_header_markup(); ?>
            </div>
	<?php } ?>



    <div class="site-search-dropdown js-site-search-dropdown">
		<div class="nv-container">
            <button class="js-close-site-search close-site-search"><i class="fa fa-times" aria-hidden="true"></i></button>
			<?php get_search_form(); ?>
		</div><!-- .container -->
	</div><!-- .site-search-dropdown -->

    <div class="container">
        <div class="nv-container">
            <div class="nv-site-branding">
                <?php
                the_custom_logo();
                if ( is_front_page() && is_home() ) : ?>
                    <h1 class="nv-site-title site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php else : ?>
                    <p class="nv-site-title site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                <?php
                endif;

                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) : ?>
                    <p class="nv-site-description site-description"><?php echo esc_html( $description ); /* WPCS: xss ok. */ ?></p>
                <?php
                endif; ?>
            </div><!-- .site-branding -->

            <?php if ( has_nav_menu('mobile-menu-1') ) : ?>
                <a href="#mobile-navigation" class="mobile-menu-button hamburger-button">
                    <span></span>
                </a>
            <?php endif; ?>

            <?php if ( has_nav_menu('menu-1') ) : ?>
                <nav id="nv-site-navigation" class="nv-main-navigation">
                    <?php
                        wp_nav_menu( array(
                            'theme_location' => 'menu-1',
                            'menu_id'        => 'primary-menu',
                            'menu_class'     => 'nav-menu'
                        ) );
                    ?>
                </nav><!-- #site-navigation -->
            <?php endif; ?>

            <?php if ( has_nav_menu('mobile-menu-1') ) : ?>
                <nav id="mobile-navigation" class="mobile-navigation" role="navigation">
    				<?php wp_nav_menu( array( 'theme_location' => 'mobile-menu-1', 'menu_id' => 'mobile-menu' ) ); ?>
        		</nav><!-- #mobile-navigation -->
            <?php endif; ?>

        <?php get_template_part( 'template-parts/content', 'hero' ); ?>
    </div>
</div><!-- .container -->

    <?php zuul_svg_angle(); ?>

</header><!-- #masthead -->
