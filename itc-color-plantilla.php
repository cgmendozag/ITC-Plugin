<?php $colors = $this->getColors();
//print_r($colors); echo json_encode($colors, JSON_HEX_TAG);
?>
<style type="text/css">
    .simplecolorpicker.inline{
        display: inline-block;
        height: 25px;
        padding: 4px 0;
    }
    .simplecolorpicker span{
        font-size: 18px;
    }
</style>
<select name="itc_select" id="itc_select">
    <?php foreach ($colors as $color){?>
    <option value="<?php echo $color["code"] ?>"><?php echo $color["es"] ?></option>
    <?php } ?>
</select>
<br/>
<?php if(count($colors) > 0) : ?>
<label for="color_code"><?php _e("Current color code:", "itc"); ?></label><input type="text" readonly="readonly" id="color_code"/>
<br/>
<?php endif; ?>
<input type="button" id="add_color" value="<?php _e("New Color", "itc") ?>" />
<input type="button" id="remove_color" value="<?php _e("Remove Selected Color", "itc") ?>" />
<div id="new_color_div">
    <label for="new_color_name_es"><b><?php _e("Color name", "itc") ?> :</b></label>
    <input type="text" id="new_color_name_es" name="new_color_name_es" placeholder="<?php _e("Color name", "itc") ?>" value=""/>
    <br/>
    <input type="text" name="new_color" id="new_color" value="#bada55" />
    <input type="button" id="new_color_save" value="<?php _e("Add"); ?>"/>
</div>
<input type="hidden" name="itc_colors" id="itc_colors" value="<?php if($colors){echo htmlentities(json_encode($colors));} else {echo htmlentities(json_encode(array()));} ?>" />
<div id="color_message"><?php _e("Changes will not take effect until settings are saved.", "itc"); ?></div>
<script type="text/javascript">
    jQuery("document").ready(function() {
        jQuery('#color_message').hide();
        jQuery('#new_color_div').toggle(false);
        jQuery('select[name="itc_select"]').simplecolorpicker().on("change", function() { 
                jQuery("#color_code").val(jQuery('select[name="itc_select"] option:selected').val());
        });
        jQuery("#color_code").val(jQuery('select[name="itc_select"] option:selected').val());
        //jQuery('#new_color').val(jQuery('select[name="catalog_colors"] option:selected'));
        jQuery('#new_color').iris();
        jQuery('#add_color').click(function() {
            jQuery('#new_color_div').toggle("slow", function() {
                jQuery('#new_color').iris("toggle");
            });
        });
        jQuery('#new_color_save').click(function() {
            if ( jQuery('#new_color_name_es').val() == "" ) {
                alert("Must define color name");
            } else {
                //jQuery("#catalog_colors").val(data);
                var opt = '<option value="' + jQuery('#new_color').val() + '">' + jQuery('#new_color_name_es').val() + '</option>';
                jQuery('#itc_select').append(opt);
                jQuery('select[name="itc_select"]').simplecolorpicker('destroy');
                jQuery('select[name="itc_select"]').simplecolorpicker();
                jQuery('#new_color').iris('toggle');
                jQuery('#new_color_div').toggle();
                
                var colors = jQuery.parseJSON(jQuery("#itc_colors").val());
                var new_color = {};
                    new_color.code = jQuery('#new_color').val();
                    new_color.es = jQuery('#new_color_name_es').val();
                colors.push(new_color);
                jQuery('#itc_colors').val(JSON.stringify(colors));
                jQuery('#color_message').show();
                jQuery("#color_code").val(jQuery('select[name="itc_select"] option:selected').val());
            }
        });
        
        jQuery('#remove_color').click(function(){
            var colors = jQuery.parseJSON(jQuery("#itc_colors").val());
            //colors.push(new_color);
            var old_color = jQuery('#itc_select option:selected').val();
            for(var i = 0; i < colors.length; i++){
                if(colors[i].code == old_color){
                    colors.splice(i,1);
                }
            }
            jQuery('#itc_select option:selected').remove();
            jQuery('select[name="itc_select"]').simplecolorpicker('destroy');
            jQuery('select[name="itc_select"]').simplecolorpicker();
            jQuery('#itc_colors').val(JSON.stringify(colors));
            jQuery('#color_message').show();
        });
    });
</script>