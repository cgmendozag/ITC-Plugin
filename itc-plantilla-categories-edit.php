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
            <th scope="row"><label for="category_color"><?php _e("Color", 'itc');?>:</label></th>
            <td>
                <div class="control-group">
                    <div class="controls">
                        <select name="category_color" id="category_color">
                            <?php $colors = $this->getColors();
                            foreach ($colors as $color){?>
                             <option value="<?php echo $color["code"] ?>"><?php echo $color["es"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>    
                <?php 
                if(count($itc)){ ?>
                <script type="text/javascript">
                    jQuery("document").ready(function() {
                        jQuery('select[name="category_color"]').simplecolorpicker('selectColor', '<?php echo $itc["color"]; ?>');
                    });
                </script>
                <?php } ?>
                <span class="description"></span>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="category_icon"><?php _e("Icon", 'itc'); ?>:</label></th>
            <td>
               <select name="category_icon" id="category_icon" style="width: 120px">
                   <?php 
                   foreach ($this->getAvailableIcons() as $icon) {?>
                   <option <?php if($icon == $itc['icon']){?>selected="selected"<?php }?> data-img-src="<?php echo $icon; ?>" value="<?php echo $icon; ?>"></option>
                    <?php } ?>
               </select>
                <span class="description"></span>
            </td>
        </tr>
    </tbody>
</table>