$(function() {
	// Once the video is ready
	_V_("example_video_1").ready(function(){
		var myPlayer = this;    // Store the video object
		var aspectRatio = 9/11.5; // Make up an aspect ratio
		
		function resizeVideoJS(){
		  // Get the parent element's actual width
		  var width = document.getElementById(myPlayer.id).parentElement.offsetWidth;
		  // Set width to fill parent element, Set height
		  myPlayer.width(width).height( width * aspectRatio );
		}
		
		resizeVideoJS(); // Initialize the function
		window.onresize = resizeVideoJS; // Call the function on resize
	});
});