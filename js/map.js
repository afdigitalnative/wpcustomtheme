function load(){
	//Style Map
	var styles = [
	  {
	    "elementType": "geometry.fill",
	    "stylers": [
	      { "saturation": -100 },
	      { "color": "#F3F3F3" }
	    ]
	  },{
	    "elementType": "geometry.stroke",
	    "stylers": [
	      { "color": "#CCCCCC" }
	    ]
	  },{
	    "elementType": "labels.text.stroke",
	    "stylers": [
	      { "color": "#D2D2D2" },
	      { "weight": 0.1 }
	    ]
	  },{
	    "featureType": "road",
	    "elementType": "geometry.stroke",
	    "stylers": [
	      { "color": "#999999" }
	    ]
	  }, {
	    "featureType": "road",
	    "elementType": "geometry.fill",
	    "stylers": [
	      { "color": "#D2D2D2" }
	    ]
	  }
	]

	// Tells Map Where To Start
	var point = new google.maps.LatLng(33.64233,-117.150072);
	var useragent = navigator.userAgent;
	
	if (useragent.indexOf("iPhone") != -1 || useragent.indexOf("Android") != -1 ) {
		var mapOptions = {
			zoom: 14,
			center: point,
			mapTypeId: google.maps.MapTypeId.TERRAIN,
			draggable: false,
			disableDefaultUI:true
		};
	} else {
		var mapOptions = {
			zoom: 16,
			center: point,
			mapTypeId: google.maps.MapTypeId.TERRAIN,
			draggable: true,
			disableDefaultUI:true
		};
	}
	
	// Create Map
	var map = new google.maps.Map(document.getElementById("map"),mapOptions);

	// Display Icon Image
	var icon = new google.maps.MarkerImage(
		"https://powerhut.co.uk/googlemaps/dynamic/13643236754FVCUH/image.png",
		new google.maps.Size(40,44),
		new google.maps.Point(0,0),
		new google.maps.Point(20,44)
	);
	
	// Create Icon Shadow
	var shadow = new google.maps.MarkerImage(
		"https://powerhut.co.uk/googlemaps/dynamic/136424987362SUS2/shadow.png",
		new google.maps.Size(66,44),
		new google.maps.Point(0,0),
		new google.maps.Point(20,44)
	);
	
	// Create Clickable Area  
	var shape = {
		coord: [32,0,32,1,32,2,31,3,30,4,29,5,28,6,27,7,26,8,26,9,18,10,21,11,39,12,39,13,39,14,39,15,39,16,39,17,38,18,38,19,38,20,37,21,37,22,22,23,22,24,22,25,22,26,22,27,22,28,22,29,22,30,23,31,23,32,23,33,23,34,24,35,24,36,25,37,25,38,26,39,26,40,27,41,28,42,0,42,25,41,24,40,23,39,22,38,21,37,21,36,20,35,19,34,19,33,18,32,18,31,18,30,17,29,17,28,17,27,16,26,16,25,16,24,16,23,16,22,16,21,16,20,16,19,16,18,16,17,15,16,14,15,14,14,13,13,13,12,12,11,12,10,13,9,13,8,21,7,21,6,21,5,21,4,22,3,22,2,23,1,24,0,32,0],
		type: "poly"
	};

	// Make Icon Work
  	var marker = new google.maps.Marker({	
		draggable: false,
		raiseOnDrag: false,
		icon: icon,
		shadow: shadow,
		shape: shape,
		map: map,
		position: point
	});
	
	var revivalMapType = new google.maps.StyledMapType(styles);
	
	map.mapTypes.set("bestfromgoogle", revivalMapType);
	map.setMapTypeId("bestfromgoogle");
  
	google.maps.event.addListener(marker,'click',function() {
		$('footer').slideToggle();
  	});
  
  	// Keep Map Centered On Resize
	function calculateCenter() {
		point = map.getCenter();
	}
	google.maps.event.addDomListener(map, "idle", function() {
		calculateCenter();
	});
	google.maps.event.addDomListener(window, "resize", function() {
		map.setCenter(point);
	});
}