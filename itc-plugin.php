<?php
/**
  Plugin Name: Impressive Themed Categories
  Plugin URI: http://www.opensistemas.com/
  Description: Permite agregar colores específicos e iconos a una categoría
  Version: 1.0
  Author: Carlos Mendoza, Tamara Osona
  Author Email: cmendoza@opensistemas.com, tosona@opensistemas.com
 */
if (!class_exists('ITC_Plugin')) {

    class ITC_Plugin {

        static $uniqueObject = null;
        var $itc_icon;
        var $icons_path = "/images/iconos/";
        var $rutaIconos;
        var $tablename;

        function __construct() {
            global $wpdb;
            add_action('wp_ajax_fancypost_remove_icon', array($this, 'ajax_remove_icon'));
            add_action('admin_enqueue_scripts', array($this, 'incluirJs'));
            add_action('plugins_loaded', array($this, 'load_text_domain'));

            //Form para categorias
            add_action('category_add_form_fields', array($this, 'itc_add_category_fields'));
            add_action('category_edit_form', array($this, 'itc_edit_category_fields'));

            //acciones para CRUD en categorias
            add_action('create_category', array($this, 'itc_save'), 10, 2);
            add_action('edit_category', array($this, 'itc_save'), 10, 2);
            add_action('delete_category', array($this, 'itc_delete'));

            add_action('admin_menu', array($this, 'add_option_menu'));
            add_action('admin_init', array($this, 'register_and_build_fields'));

            $this->rutaIconos = content_url("themes/" . get_template() . $this->icons_path);
            $this->tablename = $wpdb->prefix . "itc";
        }

        function instalar() {
            /* @var $wpdb wpdb */
            global $wpdb;
            $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}itc (
                id int(11) NOT NULL AUTO_INCREMENT,
                category_id int(11) DEFAULT NULL,
                color varchar(50) NULL,
                icon varchar(200) NULL,
                PRIMARY KEY (id)
              ) ENGINE=InnoDB");
            update_option('itc_plugin_installed', 'yes');
        }

        function desinstalar() {
            update_option('itc_plugin_installed', 'no');
        }

        function load_text_domain() {
            $plugin_dir = basename(dirname(__FILE__));
            load_plugin_textdomain('itc', false, $plugin_dir . "/languages");
        }

        function add_option_menu() {
            add_options_page(__('IT Categories', 'itc'), __('IT Categories', 'itc'), 'manage_options', 'itc-options', array(&$this, 'show_itc_options'));
        }

        function show_itc_options() {
            ?><div id="itc-options-wrap" class="wrap">
                <h2><?php _e('Impressive Themed Categories Options', 'itc'); ?></h2>
                <form action="options.php" method="post">
                    <?php
                    settings_fields('itc_options');
                    do_settings_fields('itc_options', 'itc_colors');
                    do_settings_sections(__FILE__);
                    submit_button();
                    ?>
                </form>
            <?php include "itc-icono-plantilla.php"; ?>
            </div>
            <?php
        }

        function register_and_build_fields() {
            register_setting('itc_options', 'itc_colors', array(&$this, 'validate_colors'));
            add_settings_section('color_section', __('Color Settings', 'itc'), array(&$this, 'section_cb'), __FILE__);
            add_settings_field('itc_colors', __("Colors:", "itc"), array(&$this, 'itc_colors_cb'), __FILE__, 'color_section');
        }

        function section_cb() {
            
        }

        function validate_colors($value) {
            return json_decode($value, true);
        }

        function validate_setting($value) {
            return $value;
        }

        function itc_colors_cb() {
            include "itc-color-plantilla.php";
        }

        function itc_icons_cb() {
            include "itc-icono-plantilla.php";
        }

        function incluirJs($hook) {
            wp_enqueue_script('itc_script', plugins_url(basename(__DIR__) . '/js/itc.js'));
            wp_enqueue_script('color-script-jquery', plugins_url(basename(__DIR__) . "/js/simplecolorpicker/jquery.simplecolorpicker.js"), array('jquery'));
            wp_enqueue_style('color-script-css', plugins_url(basename(__DIR__) . "/js/simplecolorpicker/jquery.simplecolorpicker.css"));
            wp_enqueue_script('image-picker', plugins_url(basename(__DIR__) . "/js/image-picker/image-picker.min.js"), array('jquery'));
            wp_enqueue_style('image-picker-css', plugins_url(basename(__DIR__) . "/js/image-picker/image-picker.css"));

            if ($hook == "settings_page_itc-options") {
                wp_enqueue_script('jquery-ui-draggable');
                wp_enqueue_script('iris');
                wp_enqueue_script('dnd-fileupload', plugins_url(basename(__DIR__) . "/js/jquery-fileupload/jquery.fileupload.js"));
                wp_enqueue_script('dnd-fileupload-ui', plugins_url(basename(__DIR__) . "/js/jquery-fileupload/jquery.fileupload-ui.js"));
                wp_enqueue_style('dnd-fileupload-style', plugins_url(basename(__DIR__) . "/js/jquery-fileupload/jquery.fileupload-ui.css"));
                wp_enqueue_script('itc-iconupload', plugins_url(basename(__DIR__) . "/js/iconupload.js"), array('plupload', 'plupload-html5', 'plupload-html4', 'plupload-handlers'));
            }
        }

        function getAvailableIcons() {
            $availableIcons = array();
            $icons = scandir(get_template_directory() . $this->icons_path, 1);
            $exclude = array(".", "..");

            foreach (array_diff($icons, $exclude) as $icon) {
                $availableIcons[$icon] = $this->rutaIconos . $icon;
            }

            return $availableIcons;
        }

        function getColors() {
            return get_option('itc_colors');
        }

        function restrictIconUploadSize() {
            return 2097152;
        }

        function restrictIconTypes($plupload_init) {
            $plupload_init["url"] = plugins_url(basename(__DIR__) . "/dnd-upload.php");
            $plupload_init["filters"][0]["extensions"] = "jpg,jpeg,png";
            $plupload_init["max_file_size"] = $this->restrictIconUploadSize();
            return $plupload_init;
        }

        function ajax_remove_icon() {
            $icon = filter_input(INPUT_POST, "icono");
            @unlink(get_template_directory() . $this->icons_path . $icon);
            die();
        }

        function itc_add_category_fields() {
            ?><h3><?php _e("ITC Settings", "itc"); ?></h3><?php
            include "itc-plantilla-categories.php";
        }

        function itc_edit_category_fields($term_obj) {
            $itc = $this->getITC($term_obj->term_id);
            ?><h3><?php _e("ITC Settings", "itc"); ?></h3><?php
            include "itc-plantilla-categories-edit.php";
        }

        function itc_delete($term_id) {
            /* @var $wpdb wpdb */
            global $wpdb;
            $wpdb->delete($this->tablename, array("category_id" => $term_id), array("%d"));
        }

        function itc_save($term_id) {
            global $wpdb;
            $color = filter_input(INPUT_POST, "category_color");
            $icon = filter_input(INPUT_POST, "category_icon");
            $icon = str_replace($this->rutaIconos, "", $icon);

            if (count($this->getITC($term_id))) {
                $wpdb->update($this->tablename, array("color" => $color, "icon" => $icon), array("category_id" => $term_id), array("%s", "%s"), array("%d"));
            } else {
                $wpdb->insert($this->tablename, array("category_id" => $term_id, "color" => $color, "icon" => $icon), array("%d", "%s", "%s"));
            }
        }

        function getITC($cat_id) {
            /* @var $wpdb wpdb */
            global $wpdb;
            $vals = $wpdb->get_row($wpdb->prepare("SELECT color, icon FROM $this->tablename WHERE category_id = %s", intval($cat_id)), ARRAY_A);
            if (is_null($vals)) {
                return array();
            } else {
                $vals["icon"] = $this->rutaIconos . $vals['icon'];
                return $vals;
            }
        }

    }

    $oITC = new ITC_Plugin();
    register_activation_hook(__FILE__, array($oITC, 'instalar'));
    register_deactivation_hook(__FILE__, array($oITC, 'desinstalar'));
}
