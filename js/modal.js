(function($) {
	 
/*---------------------------
 Listener for data-reveal-id attributes
----------------------------*/
	$(document).on('click', 'a[data-reveal-id]', function(e) {
//	$('a[data-reveal-id]').live('click', function(e) {
		e.preventDefault();
		var modalLocation = $(this).attr('data-reveal-id');
		$('#'+modalLocation).reveal($(this).data());
		/*
		setTimeout(function() {
            $('#'+modalLocation).trigger('reveal:close')
        }, 7000);
		*/
	});

/*---------------------------
 Extend and Execute
----------------------------*/
    $.fn.reveal = function(options) {
        
        var defaults = {  
		    animationspeed: 300, //how fast animtions are
		    closeonbackgroundclick: true, //if you click background will modal close?
		    dismissmodalclass: 'modal-close' //the class of a button or element that will close an open modal
    	}; 
    	
        //Extend dem' options
        var options = $.extend({}, defaults, options); 
	
        return this.each(function() {
        
/*---------------------------
 Global Variables
----------------------------*/
        	var modal = $(this),
        		topMeasure  = parseInt(modal.css('top')),
				topOffset = modal.height() + topMeasure,
          		locked = false,
				modalBG = $('.modal-bg');

/*---------------------------
 Create Modal BG
----------------------------*/
			if(modalBG.length == 0) {
				modalBG = $('<div class="modal-bg" />').insertAfter(modal);
			}		    
     
/*---------------------------
 Open & Close Animations
----------------------------*/
			//Entrance Animations
			modal.bind('reveal:open', function () {
			  modalBG.unbind('click.modalEvent');
				$('.' + options.dismissmodalclass).unbind('click.modalEvent');
				if(!locked) {
					lockModal();					
					modal.css({'top': $(document).scrollTop()-topOffset, 'opacity' : 0, 'visibility' : 'visible'});
					modalBG.fadeIn(options.animationspeed/2);
					modal.show().delay(options.animationspeed/2).animate({
						"top": $(document).scrollTop()+topMeasure + 'px',
						"opacity" : 1
					}, options.animationspeed,unlockModal());					
				}
				modal.unbind('reveal:open');
			}); 	

			//Closing Animation
			modal.bind('reveal:close', function () {
			  if(!locked) {
					lockModal();
					modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
					modal.animate({
						"top":  $(document).scrollTop()-topOffset + 'px',
						"opacity" : 0
					}, options.animationspeed/2, function() {
						modal.css({'top':topMeasure, 'opacity' : 1, 'visibility' : 'hidden'});
						modal.hide();
						unlockModal();
					});					
				}
				modal.unbind('reveal:close');
			});     
   	
/*---------------------------
 Open and add Closing Listeners
----------------------------*/
        	//Open Modal Immediately
			modal.trigger('reveal:open')
			
			//Close Modal Listeners
			var closeButton = $('.' + options.dismissmodalclass).bind('click.modalEvent', function () {
			  modal.trigger('reveal:close')
			});
			
			if(options.closeonbackgroundclick) {
				modalBG.css({})
				modalBG.bind('click.modalEvent', function () {
				  modal.trigger('reveal:close')
				});
			}
			$('body').keyup(function(e) {
        		if(e.which===27){ modal.trigger('reveal:close'); } // 27 is the keycode for the Escape key
			});
			
/*---------------------------
 Animations Locks
----------------------------*/
			function unlockModal() { 
				locked = false;
			}
			function lockModal() {
				locked = true;
			}	
			
        });//each call
    }//orbit plugin call
})(jQuery);