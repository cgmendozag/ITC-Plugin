jQuery("document").ready(function(){
    uploader.bind('FileUploaded', function(up, file, response) {
        //uploadSuccess(file, response.response);
        var doappend = true;
        jQuery('#itc_icons option').each(function(){
            if(jQuery(this).val() == response.response){
                doappend = false;
            }
        });
        if(doappend){
            jQuery('#itc_icons').append('<option data-img-src="'+response.response+'" value="'+file.name+'"></option>');
            jQuery('#itc_icons').imagepicker();
        }
    });
});
