jQuery(document).ready(function () {
    var a = 300;
    jQuery("#layout-controls a").click(function () {
        var c = jQuery("ul#archives").attr("class");
        var b = jQuery(this).attr("class");
        jQuery("ul#archives").fadeOut(a, function () {
            jQuery("ul#archives").removeClass(c, a);
            jQuery("ul#archives").addClass(b, a)
        }).fadeIn(a);   
        return false
    })
});