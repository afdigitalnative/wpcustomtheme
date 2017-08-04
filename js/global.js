(function($) {

// self invoked search form function
(function searchform() {

	var modalBG = $('#overlay'),
		searchInput = $('#search-input'),
		animationspeed = 200,
		helpText = $('#searchform p'),
		helpTextOG = helpText.html(),
		modal = function() {
		if ( modalBG.is(':visible') ) {
			modalBG.fadeOut(animationspeed);
		} else {
			modalBG.fadeIn(animationspeed);
			searchInput.focus();
		}
	};
	
	$(document).keyup(function(e) {
	
		if ( e.keyCode === 83 && !modalBG.is(':visible') && !$('input').is(':focus') ) {
			modal();
		}
		
		if( searchInput.val() ) {
			helpText.html('Press <span>enter</span> to search');
		} else {
			helpText.html(helpTextOG);
		}
	});
	
	$('#search').click(function() {
		modal();
	});
	
	// close modal 'x' click or BG click
	$('.modal-close').click(function() {
		modalBG.fadeOut(animationspeed);
	});
	
	// close modal with ESC key
	if ( !modalBG.is(':visible') ) {
		$(document).keyup(function(e){
			if (e.keyCode === 27) { modalBG.fadeOut(animationspeed); }
		});
	}

}());

// Close Elements
$('.close').click(function() {
	$(this).parent().fadeOut(200);
});

// Mobile Menu
$('#toggle').click(function(){
	$('#searchform, #menu-header').slideToggle(300);
});

$(window).resize(function(){
	if(window.innerWidth > 768) {
		$('#searchform, #menu-header').removeAttr('style');
	}
});

// Add "parent" class to list items containing a ul
$('#menu-header li:has(> ul)').addClass('parent hover');

// Menu Open & Close
if(window.innerWidth < 768) {
	$('li.parent').removeClass('hover').addClass('click');
}

$('.hover').hover(function(e) {
	
	e.preventDefault();
	
	$(this).toggleClass('flip').children('ul').first().slideToggle(300);
});

$('.click').click(function(e) {

	e.preventDefault();
	
	$(this).toggleClass('flip').children('ul').first().slideToggle(300);
});

// Archives (sidebar)
$('.aside-year > li > ul, .aside-month > li > ul').hide();

$('.aside-posts').each(function() {
   $(this).easyPaginate({numeric:false});
});

$('.aside-year h4').click(function() {
	$(this).next('ul').slideToggle();
});

$('.aside-month h5').click(function() {
	$(this).next('ul').slideToggle();
	$(this).nextAll('div.pagination').slideToggle();
	return false;
});

})( jQuery );