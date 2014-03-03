<?php $dnd_fileupload_dir = plugins_url('ITC-plugin/') ?>
<select name="itc_icons" id="itc_icons" style="width: 80px">
    <?php foreach ($this->getAvailableIcons() as $icon => $fullUrl) { ?>
        <option data-img-src="<?php echo $fullUrl; ?>" value="<?php echo $icon; ?>"></option>
    <?php } ?>
</select>
<input type="button" id="remove_icon" value="<?php _e("Remove Selected Icon", "itc"); ?>" />
<?php
wp_enqueue_script('plupload-handlers');
$form_class = 'media-upload-form type-form validate';
if ( get_user_setting('uploader') || isset( $_GET['browser-uploader'] ) )
    $form_class .= ' html-uploader';
?>
<form enctype="multipart/form-data" method="post" action="<?php echo plugins_url(basename(__DIR__).'/dnd-upload.php'); ?>" class="<?php echo esc_attr( $form_class ); ?>" id="file-form">
<?php 
add_filter('plupload_init', array($this, 'restrictIconTypes'));
add_filter('upload_size_limit', array($this, 'restrictIconUploadSize'));
media_upload_form();
remove_filter('upload_size_limit', array($this, 'restrictIconUploadSize'));
remove_filter('plupload_init', array($this, 'restrictIconTypes'));
?>
    <script type="text/javascript">
    var post_id = 0;
    var shortform = 3;
    </script>
    <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />
    <?php wp_nonce_field('media-form'); ?>
</form>
<script language="JavaScript" type="text/javascript">
var rutaIconos = '<?php echo $this->rutaIconos; ?>';
jQuery("document").ready(function() {
    jQuery('#itc_icons').imagepicker(); 
    jQuery("#remove_icon").click(function(){
        if(confirm('<?php _e("This action can not be undone. Are you sure you want to proceed?", "itc") ?>')){
            jQuery.ajax({
                url: ajaxurl,
                dataType: 'json',
                async: false,
                data: {
                    'action': 'itc_remove_icon',
                    'icono': jQuery('#itc_icons option:selected').val()
                },
                type: 'POST',
                success: function(data) {
                    jQuery('#itc_icons option:selected').remove();
                    jQuery('#itc_icons').imagepicker();
                }
            });
        }
    });
});
</script>