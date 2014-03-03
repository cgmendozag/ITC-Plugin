<style type="text/css">
    .simplecolorpicker span:hover, .simplecolorpicker span.selected{
        padding: 6px;
    }
     .simplecolorpicker span{
        padding: 3px;
    }
</style>
<div class="form-field">
    <label for="category_color"><?php _e("Color", 'itc');?>:</label>
    <select name="category_color" id="category_color" class="postform">
        <?php $colors = $this->getColors();
        foreach ($colors as $color){?>
         <option value="<?php echo $color["code"] ?>"><?php echo $color["es"]; ?></option>
        <?php } ?>
    </select>
    
    <?php 
    $category_color = get_post_meta($post->ID, 'category_color', true);

    if(isset($category_color) && !empty($category_color)){ ?>
    <script text="javascript">
        jQuery("document").ready(function() {
            jQuery('select[name="category_color"]').simplecolorpicker('selectColor', '<?php echo $category_color; ?>');
        });
    </script>
    <?php } ?>
    <p class="description"></p>
</div>
<div class="form-field">
    <label for="category_icon"><?php _e("Icon", 'itc'); ?>:</label>
    <select name="category_icon" id="category_icon" class="postform">
        <?php 
        $category_icon = get_post_meta($post->ID, 'category_icon', true);
        foreach ($this->getAvailableIcons() as $icon) {?>
        <option <?php if($icon == $category_icon){?>selected="selected"<?php }?> data-img-src="<?php echo $icon; ?>" value="<?php echo $icon; ?>"></option>
         <?php } ?>
    </select>
    <p class="description"></p>
</div>