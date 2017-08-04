(function($) {

$('.slider').flexslider({
	directionNav: false,
	animation: "slide",
	before: function(){
		$('.slide-title').hide();
	},
	after: function(){
		$('.slide-title').fadeIn();
	}
});

/*
$('.slider-2').flexslider({
	animation: "slide",
	itemWidth: 256,
	itemMargin: 29,
	minItems: 2,
	maxItems: 4,
	slideshow: false,
	controlNav: false
});
*/

})( jQuery );