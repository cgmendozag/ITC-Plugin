<style type="text/css">
    .simplecolorpicker span:hover, .simplecolorpicker span.selected{
        padding: 6px;
    }
     .simplecolorpicker span{
        padding: 3px;
    }
</style>
<table class="form-table">
    <tbody>
        <tr>
            <th scope="row"><label for="post_color"><?php _e("Color", 'catalogo');?>:</label></th>
            <td>
                <div class="control-group">
                    <div class="controls">
                        <select name="post_color" id="post_color">
                            <?php $colors = $this->getColors();
                            foreach ($colors as $color){?>
                             <option value="<?php echo $color["code"] ?>"><?php echo $color["es"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>    
                <?php 
                $post_color = get_post_meta($post->ID, 'post_color', true);
                
                if(isset($post_color) && !empty($post_color)){ ?>
                <script text="javascript">
                    jQuery("document").ready(function() {
                        jQuery('select[name="post_color"]').simplecolorpicker('selectColor', '<?php echo $post_color; ?>');
                    });
                </script>
                <?php } ?>
                <span class="description"></span>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="post_icon"><?php _e("Icon", 'catalogo'); ?>:</label></th>
            <td>
               <select name="post_icon" id="post_icon" style="width: 120px">
                   <?php 
                   $post_icon = get_post_meta($post->ID, 'post_icon', true);
                   foreach ($this->getAvailableIcons() as $icon) {?>
                   <option <?php if($icon == $post_icon){?>selected="selected"<?php }?> data-img-src="<?php echo $icon; ?>" value="<?php echo $icon; ?>"></option>
                    <?php } ?>
               </select>
                <span class="description"></span>
            </td>
        </tr>
    </tbody>
</table>