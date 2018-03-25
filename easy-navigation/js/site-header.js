jQuery(document).ready(function($){
    $('#mobile-navigation').mmenu({
	    offCanvas: {
	        position: "right",
	    },
		navbar: {
			title: "Menu",
		},
	    extensions: ["pageshadow", "effect-slide-menu", "effect-slide-listitems", "theme-dark"]
	});

    var mmenuAPI = $('#mobile-navigation').data('mmenu');

    $(window).resize( function() {
        if ( $('#mobile-navigation').hasClass('mm-opened') ) {
            mmenuAPI.close();
        }
    } );

    /*
     * WordPress wrapper for jQuery mmenu
     * Include this file after including the jquery.mmenu plugin for default WordPress support.
     */

	var _PLUGIN_ = 'mmenu';

	$[ _PLUGIN_ ].configuration.classNames.selected = 'current-menu-item';

	$("#wpadminbar")
		.css( 'position', 'fixed' )
		.addClass( 'mm-slideout' );

    $('.js-open-site-search').click(function() {
        openSearch(this);
    });

    $('.js-close-site-search').click(function() {
        closeSearch(this);
    });

    function openSearch(button) {
        $(button).addClass('site-search-open');
        $('.js-site-search-dropdown').slideDown();
        $('.js-site-search-dropdown .search-field').focus();
    }

    function closeSearch(button) {
        $(button).removeClass('site-search-open');
        $('.js-site-search-dropdown').slideUp();
    }
});
