<script type="text/javascript" src="<?php echo osc_current_web_theme_js_url('jquery.maphilight.min.js') ; ?>"></script>
<h2 class="render-title <?php echo (osc_get_preference('footer_link', 'theme_map_theme') ? '' : 'separate-top'); ?>"><?php _e('Map Options', 'theme_map'); ?></h2>
<div class="flashmessage flashmessage-warning flashmessage-inline"><p><strong><?php _e('How to assign a region to your map?','theme_map'); ?></strong><br><?php _e('First, click in the map to select the area you want to set. Then, a dialog will appear and you only need to select the region you want to assign. Finally, once you have selected the region, click the save button.','theme_map'); ?></p></div>
<div id="main-map" style="float:left; margin:15px 0;position:relative">
    <?php osc_current_web_theme_path('map.php') ; ?>
</div>
<div style="display:none">
<?php
function countrySelect($id = null,$selected = null){
    $echo  = '<select name="region">';
    $echo .= '<option value="">' . __('Select a region', 'theme_map') . '</option>';
    foreach(osc_get_regions() as $region){
        $echo .= '<option value="'.$region['pk_i_id'].'"';
        if($region['pk_i_id'] == $selected){
            $echo .= ' selected="selected" ';
        }
        $echo .=  '>'.$region['s_name'].'</option>';
    }
    $echo .= '</select><input type="hidden" name="target-id" value="'.$id.'"/>';
    return $echo;
}
$regions = json_decode(osc_get_preference('region_maps','theme_map_theme'),true);
for($i=0;$i<27;$i++){
    ?>
    <form id="region-<?php echo $i; ?>" class="region-dialog has-form-actions">
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Select a region', 'theme_map'); ?></div>
                <div class="form-controls"><?php $selected = 'false'; if(isset($regions[$i])){ $selected = $regions[$i]; } echo countrySelect($i,$selected); ?></div>
            </div>
            <div class="form-actions">
                <div class="wrapper">
                <input type="submit" value="<?php _e('Save changes', 'theme_map'); ?>" class="btn btn-submit">
                <input type="hidden" name="action_specific" value="edit_region_map"/>
                <input type="hidden" name="page" value="<?php echo Params::getParam('page'); ?>"/>
                <input type="hidden" name="action" value="<?php echo Params::getParam('action'); ?>"/>
                <input type="hidden" name="file" value="<?php echo Params::getParam('file'); ?>"/>
            </div>
            </div>
        </div>
    </form>
<?php
}
for($i=1;$i<2;$i++){
    ?>
    <form id="region-group-<?php echo $i; ?>" class="region-dialog has-form-actions">
        <div class="form-horizontal">
            <div class="form-row">
                <div class="form-label"><?php _e('Select a region', 'theme_map'); ?></div>
                <div class="form-controls"><?php $selected = 'false'; if(isset($regions['group-'.$i])){ $selected = $regions['group-'.$i]; } echo countrySelect('group-'.$i,$selected); ?></div>
            </div>
            <div class="form-actions">
                <div class="wrapper">
                <input type="submit" value="<?php _e('Save changes', 'theme_map'); ?>" class="btn btn-submit">
                <input type="hidden" name="action_specific" value="edit_region_map"/>
                <input type="hidden" name="page" value="<?php echo Params::getParam('page'); ?>"/>
                <input type="hidden" name="action" value="<?php echo Params::getParam('action'); ?>"/>
                <input type="hidden" name="file" value="<?php echo Params::getParam('file'); ?>"/>
            </div>
            </div>
        </div>
    </form>
<?php
}
?>
</div>
<script type="text/javascript">
$('.region-dialog').dialog({
    autoOpen: false,
    modal:true,
    width:500,
    title: '<?php echo osc_esc_js(__('Select a region', 'theme_map')); ?>'
});
$(function() {
    var linksRegions = new Array();
<?php
    if($regions){
        foreach($regions as $key => $value){
            echo "    linksRegions['$key'] = '".osc_search_url( array( 'sRegion' => $value ) )."';".PHP_EOL;
        }
    }
?>
	//find all regions map has assigned a location
	$('area').each(function(){
		var $_hasClass = $(this).attr('class'); //catching
		var $_index = $('area:not([class^="group"])').index($(this));   //catching
		var colorStatus = true;
		if($_hasClass != undefined){
	        if(typeof linksRegions[$_hasClass] == 'undefined'){
	            colorStatus = false;
	        }
	    } else {
	    	if(typeof linksRegions[$_index] == 'undefined'){
	            colorStatus = false;
	        }
	    }
		//colors
	    color = 'rgba(55,200,211,1)';
	    lineColor = 'rgba(0,190,214,1)';
	    if(!colorStatus){
	        color = '#95CFD1';
	        lineColor = 'rgba(0,190,214,1)';
	    }
	    options = {
			lineColor:  lineColor,
			fillColor:  color
		}
	    drawCanvas('map-status', $(this).attr('coords'), options);
	    //draw($(this).attr('coords'),colorStatus);
	    $(this).click(function(){
	        if($_hasClass != undefined){
	            var element = $('#region-'+$_hasClass);
	        } else {
	            var element = $('#region-'+$_index);
	        }
	        element.dialog('open');
	        return false;
	    }).hover(function(){
	    	canvas = document.getElementById("map-hover");
	        canvas.width = canvas.width;
	        options = {
				lineColor:  '#de9200',
				fillColor:  '#ffa800'
			}
	        if($(this).attr('class') != undefined){
	            $('.'+$(this).attr('class')).each(function(){
	            	drawCanvas('map-hover', $(this).attr('coords'), options);
	            });
	        } else {
	            drawCanvas('map-hover', $(this).attr('coords'), options);
	        }
	    },function(){
	        canvas.width = canvas.width;
	    });
	});
	//
});
</script>
<div class="clear"></div>