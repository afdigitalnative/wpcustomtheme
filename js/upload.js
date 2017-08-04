jQuery(document).ready(function($) {
	// Use Media Uploader
    $('.custom_upload_image_button').click(function() {
        imageField = $(this).prev('input');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
    });
    window.send_to_editor = function(html) {
		imgurl = $(html).attr('href');
		$(imageField).val(imgurl);
		tb_remove();
    };
	$('.clear_field').click(function() {
		var defaultImage = jQuery(this).parent().siblings('.custom_default_image').text();
		jQuery(this).parent().siblings('.custom_upload_image').val('');
		return false;
	});
});