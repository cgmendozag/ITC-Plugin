jQuery("document").ready(function() {
    if (jQuery('select[name="category_color"]'))
        jQuery('select[name="category_color"]').simplecolorpicker();

    jQuery('#category_icon').imagepicker();
});
