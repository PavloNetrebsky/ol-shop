<?php
/**
 * Setting for Schedule
 *
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !function_exists( 'noo_timetable_setting_schedule' ) ) {
	function noo_timetable_setting_schedule() {

		// Get trainer category
		$arr = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
		$trainer_category['all'] = esc_html__( 'All', 'noo-timetable' );
		foreach ( $arr as $category ) {
            $trainer_category[$category->term_id] = $category->name;
        }

		$options = array(
			// General Setting
			array(
				'title' => esc_html__( 'Schedule settings', 'noo-timetable' ), 
				'type'  => 'title',
				'desc'  => esc_html__( 'Here is default configuration of Schedule shortcode', 'noo-timetable' ),
				'id'    => 'schedule_general'
			),

			array(
				'title'       => esc_html__( 'Show Category Filter', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_header_filter',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Time Column', 'noo-timetable' ),
				'desc'    	  => esc_html__( 'Only Weekly and Daily View', 'noo-timetable' ),
				'id'          => 'noo_schedule_general_header_time_column',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Weekends', 'noo-timetable' ),
				'desc'    	  => esc_html__( 'Show Weekends = Select "Saturday and Sunday" or "Saturday" or "Sunday". Hide = "None".', 'noo-timetable' ),
				'id'          => 'noo_schedule_general_header_weekends',
				'class'       => '',
				'type'     	  => 'multiselect',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					''     	  => esc_html__( 'None', 'noo-timetable' ),
					'sat'     => esc_html__( 'Saturday', 'noo-timetable' ),
					'sun'     => esc_html__( 'Sunday', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Toolbar', 'noo-timetable' ),
				'desc'    	  => esc_html__( 'Show forward and backward arrowhead on top of the schedule', 'noo-timetable' ),
				'id'          => 'noo_schedule_general_header_toolbar',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Date', 'noo-timetable' ),
				'desc'    	  => esc_html__( 'Only Weekly view', 'noo-timetable' ),
				'id'          => 'noo_schedule_general_header_day',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Default Date', 'noo-timetable' ),
				'desc'        => esc_html__( 'Leave blank to get the current time', 'noo-timetable' ),
				'id'          => 'noo_schedule_general_default_date',
				'class'       => '',
				'default'     => '',
				'placeholder' => '',
				'type'        => 'datetimepicker',
			),

			array(
				'title'       => esc_html__( 'Redirect link for Item', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_navigate_link',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:250px;',
				'options'  	  => array(
					'internal' => esc_html__( 'Go to Link', 'noo-timetable' ),
					'external' => esc_html__( 'Go to Register link if available', 'noo-timetable' ),
					'disable'  => esc_html__( 'Disable Link', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Class/Event info in Popup', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_popup',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Class level info in Popup', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_popup_level',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Thumbnail in Popup', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_popup_thumb',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),
			array(
				'title'       => esc_html__( 'Show Time in Popup', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_popup_time',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),
			array(
				'title'       => esc_html__( 'Show Title in Popup', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_popup_title',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),
			array(
				'title'       => esc_html__( 'Show Adress(Event) or Trainer(Class) in Popup', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_popup_adress_trainer',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),
			array(
				'title'       => esc_html__( 'Show excerpt info in Popup', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_popup_excerpt',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Popup Style', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_popup_style',
				'class'       => '',
				'default'  	  => '1',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'1'     => esc_html__( 'Fade in & Scale', 'noo-timetable' ),
					'2'     => esc_html__( 'Slide in (right)', 'noo-timetable' ),
					'3'     => esc_html__( 'Slide in (bottom)', 'noo-timetable' ),
					'4'     => esc_html__( 'Newspaper', 'noo-timetable' ),
					'5'     => esc_html__( 'Fall', 'noo-timetable' ),
					'6'     => esc_html__( 'Side Fall', 'noo-timetable' ),
					'7'     => esc_html__( 'Sticky Up', 'noo-timetable' ),
					'8'     => esc_html__( '3D Flip (horizontal)', 'noo-timetable' ),
					'9'     => esc_html__( '3D Flip (vertical)', 'noo-timetable' ),
					'10'     => esc_html__( '3D Sign', 'noo-timetable' ),
					'11'     => esc_html__( 'Super Scaled', 'noo-timetable' ),
					'12'     => esc_html__( 'Just Me', 'noo-timetable' ),
					'13'     => esc_html__( '3D Slit', 'noo-timetable' ),
					'14'     => esc_html__( '3D Rotate Bottom', 'noo-timetable' ),
					'15'     => esc_html__( '3D Rotate In Left', 'noo-timetable' ),
					'16'     => esc_html__( 'Blur', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Export', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_show_export',
				'class'       => '',
				'default'  	  => 'no',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'no'     => esc_html__( 'No', 'noo-timetable' ),
					'yes'     => esc_html__( 'Yes', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Category On Mobile', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_general_show_category',
				'class'       => '',
				'default'  	  => 'no',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'no'      => esc_html__( 'No', 'noo-timetable' ),
					'yes'     => esc_html__( 'Yes', 'noo-timetable' )
				)
			),

			array( 'type' => 'sectionend', 'id' => 'schedule_general'),


			// Event
			array(
				'title' => esc_html__( 'Design Options', 'noo-timetable' ), 
				'type'  => 'title',
				'desc'  => esc_html__( 'Design Options', 'noo-timetable' ),
				'id'    => 'schedule_design_options'
			),


			array(
				'title'       => esc_html__( 'Show Class category', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_class_show_category',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Class Item Style', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_class_item_style',
				'class'       => '',
				'default'  	  => 'categoryColor',
				'type'     	  => 'select',
				'css'      	  => 'width:250px;',
				'options'  	  => array(
					'categoryColor' => esc_html__( 'Category Color', 'noo-timetable' ),
					'cat_bg_color'    => esc_html__( 'Background color by Category', 'noo-timetable' ),
					'item_bg_image'    => esc_html__( 'Item Background Image', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Class Icon', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_class_show_icon',
				'class'       => '',
				'default'  	  => 'no',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'no'     => esc_html__( 'No', 'noo-timetable' ),
					'yes'     => esc_html__( 'Yes', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Event Item Style', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_event_item_style',
				'class'       => '',
				'default'  	  => 'background_color',
				'type'     	  => 'select',
				'css'      	  => 'width:250px;',
				'options'  	  => array(
					'background_color'   => esc_html__( 'Background Color', 'noo-timetable' ),
					'background_image'   => esc_html__( 'Background Image', 'noo-timetable' ),
					'background_none'    => esc_html__( 'Background None', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Show Event Icon', 'noo-timetable' ),
				'desc'    	  => '',
				'id'          => 'noo_schedule_event_show_icon',
				'class'       => '',
				'default'  	  => 'yes',
				'type'     	  => 'select',
				'css'      	  => 'width:150px;',
				'options'  	  => array(
					'yes'     => esc_html__( 'Yes', 'noo-timetable' ),
					'no'     => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Heading Background Color', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_schedule_general_header_background',
				'class'       => '',
				'default'     => '#cf3d6f',
				'placeholder' => '',
				'type'        => 'color',
			),

			array(
				'title'       => esc_html__( 'Heading Text Color', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_schedule_general_header_color',
				'class'       => '',
				'default'     => '#fff',
				'placeholder' => '',
				'type'        => 'color',
			),

			array(
				'title'       => esc_html__( 'Today\'s background', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_schedule_general_today_column',
				'class'       => '',
				'default'     => '#fcf8e3',
				'placeholder' => '',
				'type'        => 'color',
			),

			array(
				'title'       => esc_html__( 'Holiday Background Color', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_schedule_general_holiday_background',
				'class'       => '',
				'default'     => '#cf3d6f',
				'placeholder' => '',
				'type'        => 'color',
			),

			array( 'type' => 'sectionend', 'id' => 'schedule_design_options'),

		);

		return apply_filters( 'noo_timetable_setting_schedule', $options);
	}
}

if( !function_exists( 'noo_timetable_admin_setting_field_datetimepicker' ) ) {

	function noo_timetable_admin_setting_field_datetimepicker( $value ) {
		
		$date_format = 'Y-m-d';

		$option_value = NOO_Settings()->get_option( $value['id'], $value['default'] );

		$date_text = is_numeric( $option_value ) ? date( $date_format, $option_value ) : $option_value;
		$date = is_numeric( $option_value ) ? $option_value : strtotime( $option_value );

		// Description handling
		$field_description = NOO_Settings()->get_field_description( $value );
		extract( $field_description );

		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
				
			</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
				<input
					type="text"
					id="<?php echo esc_attr( $value['id'] ); ?>"
					value="<?php echo esc_attr( $date_text ); ?>"
					class="<?php echo esc_attr( $value['class'] ); ?>"
					style="<?php echo esc_attr( $value['css'] ); ?>"
					placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
					/>
				<input
					name="timetable_settings[<?php echo esc_attr( $value['id'] ); ?>]"
					type="hidden"
					value="<?php echo esc_attr( $date ); ?>"
					/> <?php echo $description; ?>

				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#<?php echo esc_attr( $value['id'] ); ?>').datetimepicker({
							format:"<?php echo esc_html( $date_format ); ?>",
							timepicker: false,
							datepicker: true,
							scrollInput: false,
							onChangeDateTime:function(dp,$input){
								if ((typeof(dp) !== 'undefined') && (dp !== null)) {
									$input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
								} else {
									$input.next('input[type="hidden"]').val('');
								}
							}
						});
					});
				</script>
			</td>
		</tr>
		<?php
	}
	
	add_action( 'noo_timetable_admin_field_datetimepicker', 'noo_timetable_admin_setting_field_datetimepicker' );
}
