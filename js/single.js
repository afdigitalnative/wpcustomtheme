(function($) {

	//TABS    

    // Define Plugin
    $.organicTabs = function(el, options) {
    
		// JavaScript native version of this
		var base = this;
		
		// jQuery version of this
		base.$el = $(el);
		
		// Navigation for current selector passed to plugin
		base.$nav = base.$el.find(".tab-nav");
		
		// Runs once when plugin called       
		base.init = function() {
		
		// Pull in arguments
		base.options = $.extend({},$.organicTabs.defaultOptions, options);
		
		// Accessible hiding fix (hmmm, re-look at this, screen readers still run JS)
		$(".hide").css({
			"position": "relative",
			"top": 0,
			"left": 0,
			"display": "none"
		});
		
		// When navigation tab is clicked...
		base.$nav.delegate("a", "click", function(e) {
			
			// no hash links
			e.preventDefault();
			
			// Figure out current list via CSS class
			var curList = base.$el.find("a.current").attr("href").substring(1),
			
			// List moving to
			$newList = $(this),
			
			// Figure out ID of new list
			listID = $newList.attr("href").substring(1),
			
			// Set outer wrapper height to (static) height of current inner list
			$allListWrap = base.$el.find(".list-wrap");
			$allListWrap.height();
			//$allListWrap.height(curListHeight);
			
			if ((listID !== curList) && ( base.$el.find(":animated").length === 0)) {
			
				// Fade out current list
				base.$el.find("#"+curList).fadeOut(base.options.speed, function() {
			
					// Fade in new list on callback
					base.$el.find("#"+listID).fadeIn(base.options.speed);
					
					// Remove highlighting - Add to just-clicked tab
					base.$el.find(".tab-nav li a").removeClass("current");
					$newList.addClass("current");
					
					// Change window location to add URL params
					if (window.history && history.pushState) {
						// NOTE: doesn't take into account existing params
						history.replaceState("", "", "?" + base.options.param + "=" + listID);
					}
					
				});
			
			}
		
		});
		
		var queryString = {};
		
		window.location.href.replace(
			new RegExp("([^?=&]+)(=([^&]*))?", "g"),
			function($0, $1, $2, $3) { queryString[$1] = $3; }
		);
		
		if (queryString[base.options.param]) {
			
			var tab = $("a[href='#" + queryString[base.options.param] + "']");
			
			tab
			.closest(".tab-nav")
			.find("a")
			.removeClass("current")
			.end()
			.next(".list-wrap")
			.find("section")
			.hide();
			tab.addClass("current");
			$("#" + queryString[base.options.param]).show();
			
		}
		
	};

	base.init();
	
	};
	
	$.organicTabs.defaultOptions = {
		"speed": 300,
		"param": "tab"
	};
	
	$.fn.organicTabs = function(options) {
		return this.each(function() {
			($.organicTabs(this, options));
	});
	};

	$("#tabs").organicTabs();

	// Add Span Tags To Scripture Reference Numbers
	$('#scripture p').html(function(i,oldHTML){
		return oldHTML.replace( /\b(\d+)\b/g, '<span>$1</span>' );
	});

	// SHARRRE
    $("#facebook").sharrre({
        share: {
            facebook: true
        },
        template: '<span>F</span>',
        enableHover: false,
        enableTracking: true,
        click: function (api) {
            api.simulateClick();
            api.openPopup("facebook");
        }
    });
    $("#twitter").sharrre({
        share: {
            twitter: true
        },
        template: '<span>T</span>',
        enableHover: false,
        enableTracking: true,
        buttons: {
            twitter: {
                via: "revivaltv"
            }
        },
        click: function (api) {
            api.simulateClick();
            api.openPopup("twitter");
        }
    });
    
    // Animate Share Link
	$('#share-link span').click(function() {
		
		var link = '#short-link';
		
		if ($(link).is(':visible')){
			$(link).fadeOut();
		} else {
			$(link).fadeIn();
		}
	});
	
	// Fade Sharrre Buttons In
	setTimeout(function() {
		$('#share').animate({opacity: 1});
	}, 1000);
    
    // Countdown
	if ($().countDown) {
		$('.countdown').countDown({
			with_separators: false,
			label_hh: "hrs",
			label_mm: "min",
			label_ss: "sec"
		});
	}
	
})( jQuery );