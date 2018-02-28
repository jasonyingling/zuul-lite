/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
jQuery(document).ready(function($){
	$('.woo-expand').click( function(e) {
		if ( $(this).hasClass('woo-closed') ) {
			$('.shop-sidebar').toggleClass('sidebar-opened').slideDown(500, function() {
				$('.woo-expand').removeClass('woo-closed').addClass('woo-expanded');
			});
		} else {
			$('.shop-sidebar').toggleClass('sidebar-opened').slideUp(500, function() {
				$('.woo-expand').removeClass('woo-expanded').addClass('woo-closed');
			});
		}
	});

	$('.zuul-loop-projects').isotope({
		// options
		itemSelector: '.zuul-item',
	});

	// init Isotope
	var $grid = $('.zuul-loop-projects').isotope({
		// options
	});
	// filter items on button click
	$('.zuul-project-filters').on( 'click', 'a', function(e) {
		e.preventDefault();
		var filterValue = $(this).attr('data-filter');
		$grid.isotope({ filter: filterValue });
		$('.zuul-project-filters a').removeClass('zuul-active-filter');
		$(this).addClass('zuul-active-filter');
	});

});
