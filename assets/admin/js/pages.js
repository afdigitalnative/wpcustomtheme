jQuery(function($) {
    
    function hideShowMetaBoxes(page) {
        
        $('#normal-sortables [id$=options]').hide();
	
		var tempID = '#page_template'
			template = 'templates/' + page + '.php',
			metabox = '#' + page + '_options';

		if ($(tempID).val() == template) {
	        $(metabox).show();
	        $(metabox+'-hide').prop('checked', true);
	    }
	    
    };
    
    $('#page_template').bind('change', function () {
    	hideShowMetaBoxes( $("option:selected", this).text().toLowerCase().replace(/ /g,"_") );
    	console.log($("option:selected", this).text().toLowerCase().replace(/ /g,"_"));
	});
	$('#page_template').trigger('change');
	
});