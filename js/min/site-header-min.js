jQuery(document).ready(function($){function e(e){$(e).addClass("site-search-open"),$(".js-site-search-dropdown").slideDown(),$(".js-site-search-dropdown .search-field").focus()}function s(e){$(e).removeClass("site-search-open"),$(".js-site-search-dropdown").slideUp()}$("#mobile-navigation").mmenu({offCanvas:{position:"right"},navbar:{title:"Menu"},extensions:["pageshadow","effect-slide-menu","effect-slide-listitems","theme-dark"]});var i=$("#mobile-navigation").data("mmenu");$(window).resize(function(){$("#mobile-navigation").hasClass("mm-opened")&&i.close()}),$(".js-open-site-search").click(function(){e(this)}),$(".js-close-site-search").click(function(){s(this)})});