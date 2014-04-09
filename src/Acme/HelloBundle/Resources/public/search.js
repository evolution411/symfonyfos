$(document).ready(function(){
	   $(window).bind('scroll', function() {
	   var navHeight = $( window ).height() - 70;
			 if ($(window).scrollTop() > navHeight) {
				 $('main-search').addClass('fixed');
			 }
			 else {
				 $('main-search').removeClass('fixed');
			 }
		});
	});