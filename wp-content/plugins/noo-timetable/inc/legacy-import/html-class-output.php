<?php
$categories = get_terms('class_category', array('hide_empty' => false));
$trainers = get_posts( array('post_type'=>'noo_trainer','posts_per_page'=>-1,'suppress_filters'=>0) );
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
		<label for="noo-import-trainer"><?php esc_html_e( 'Set Trainers', 'noo-timetable' ); ?></label>
	</th>
	<td class="forminp">
		<select name="noo-import-trainer" id="noo-import-trainer" multiple="multiple">
			<option value="0"><?php esc_html_e( '- Select -', 'noo-timetable' ); ?></option>
			<?php foreach ((array)$trainers as $trainer):?>
                <option value="<?php echo esc_attr($trainer->ID)?>"><?php echo esc_html($trainer->post_title)?></option>
            <?php endforeach;?>
		</select>
	</td>
</tr>