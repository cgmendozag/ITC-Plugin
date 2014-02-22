<?php
require_once('../../../wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/admin.php');

$id = dnd_media_handle_upload('async-upload');
unset($_FILES);
if ( is_wp_error($id) ) {
        $errors['upload_error'] = $id;
        $id = false;
}

if ($errors) {
        echo "<p>There was an error uploading your file.</p>".print_r($errors);
} else {
    if(isset($_POST["html-upload"])){
        wp_redirect(wp_get_referer());
        die();
    }
        echo "$id";
}

 /**
 * This handles the file upload POST itself, creating the attachment post.
 *
 * @since 2.5.0
 *
 * @param string $file_id Index into the {@link $_FILES} array of the upload
 * @param array $post_data allows you to overwrite some of the attachment
 * @param array $overrides allows you to override the {@link wp_handle_upload()} behavior
 * @return int the ID of the attachment
 */
function dnd_media_handle_upload($file_id, $post_data = array(), $overrides = array( 'test_form' => false )) {
//error_log(print_r($_FILES,true));
	$time = current_time('mysql');

	$name = $_FILES['async-upload']['name'];

	//$file = wp_handle_upload($_FILES['file'], $overrides, $time);
        $file = os_handle_upload($_FILES['async-upload']);

	if ( isset($file['error']) )
		return new WP_Error( 'upload_error', $file['error'] );

	$name_parts = pathinfo($name);
	$name = trim( substr( $name, 0, -(1 + strlen($name_parts['extension'])) ) );

	$url = $file['url'];
	$type = $file['type'];
	$file = $file['file'];
	$title = $name;
	$content = '';

	// use image exif/iptc data for title and caption defaults if possible
	if ( $image_meta = @wp_read_image_metadata($file) ) {
		if ( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) )
			$title = $image_meta['title'];
		if ( trim( $image_meta['caption'] ) )
			$content = $image_meta['caption'];
	}

	return $url;
}

function os_handle_upload(&$file){
    global $oITC;
    $respuesta = array();
    $allowedFileTypes = array("image/png", "image/x-png", "image/jpeg");
    //if (!is_null($_FILES["file"]) && !empty($_FILES["file"]["name"])) {
        if ($file['error'] !== 0) {
            // gulp
        }

        if(!in_array($file["type"], $allowedFileTypes)){
            $file["error"] = __("File Type not allowed", "catalogo");
            return $file;
        }
        
        // Move the file out of 'tmp', or rename
        if(!is_dir(get_template_directory() . $oITC->icons_path)){
            mkdir(get_template_directory() . $oITC->icons_path);
        }
        $location = get_template_directory() . $oITC->icons_path . "/" . $file['name'];
        @rename($file['tmp_name'], $location);

        $itc_icon = $oITC->rutaIconos . $file['name'];
        $respuesta["file"] = $location;
        $respuesta["url"] = $itc_icon;
        $respuesta["type"] = $file["type"];
    //}
    return $respuesta;
}