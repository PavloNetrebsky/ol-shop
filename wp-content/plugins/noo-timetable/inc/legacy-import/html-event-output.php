<?php
wp_enqueue_style( 'wp-color-picker' );
wp_enqueue_script( 'wp-color-picker' );

$presets    = noo_timetable_get_presets_color();
$categories = get_terms('event_category', array('hide_empty' => false));
?>
<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="noo-import-category"><?php esc_html_e( 'Set Category', 'noo-timetable' ); ?></label>
	</th>
	<td class="forminp">
		<select name="noo-import-category" id="noo-import-category" multiple="multiple">
			<option value="0"><?php esc_html_e( '- Select -', 'noo-timetable' ); ?></option>
			<?php foreach ((array)$categories as $category):?>
            <option value="<?php echo esc_attr($category->term_id)?>"><?php echo esc_html($category->name)?></option>
            <?php endforeach;?>
		</select>
	</td>
</tr>
<tr valign="top">
	<th scope="row" class="titledesc">
		<label for="noo-import-color"><?php esc_html_e( 'Set Color', 'noo-timetable' ); ?></label>
	</th>
	<td class="forminp">
		<div class="wrap-wp-colorpicker">
	        <input type="text" name="noo-import-color" class="noo-import-color" id="noo-import-color" value="" />
	        <?php
            	echo '<div class="wrap_set_color">';
	            foreach( $presets as $color ) {
	                echo '<span class="set_color" data-color="'.esc_attr($color).'" style="background-color:'.esc_attr($color).'"></span>';
	            }
	            echo '</div>';
            ?>
    	</div>
	    <script>
			jQuery(document).ready(function($) {
	        	$('.noo-import-color').wpColorPicker();
			});
		</script>
	</td>
</tr>