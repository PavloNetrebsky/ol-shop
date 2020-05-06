<?php
/**
 * Class Meta Boxes
 *
 * Setting the information is used on the calendar, archive and single pages.
 * 
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/PostTypes/MetaBoxes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! class_exists('Noo__Timetable__Class_Meta_Boxes') ) {

    class Noo__Timetable__Class_Meta_Boxes {

    	public function __construct() {

    		if ( is_admin() ) {
    			// Metabox
				add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ), 30 );

				// Add field for class category
				add_action( 'class_category_add_form_fields', array( &$this, 'class_category_add_meta_field' ), 100, 2 );
				add_action( 'class_category_edit_form_fields', array( &$this, 'class_category_edit_meta_field' ), 100, 2 );
				add_action( 'edited_class_category', array( &$this, 'class_category_update_meta_field' ), 10, 2 );
				add_action( 'create_class_category', array( &$this, 'class_category_save_meta_field' ), 10, 2 );

				// Columns
				add_filter( 'manage_edit-noo_class_columns', array( &$this, 'manage_edit_columns' ) );
				add_filter( 'manage_noo_class_posts_custom_column', array( &$this,'manage_custom_column' ), 2 );
    		}

    	}

    	public function class_category_add_meta_field() {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			$presets   = noo_timetable_get_presets_color();
			?>
			<div class="form-field">
				<div class="wrap-wp-colorpicker">
			        <label for="term_meta-category_color"><?php esc_html_e( 'Color', 'noo-timetable' ); ?></label>
			        <input type="text"  name="category_color" class="category_color" id="term_meta-category_color" value="" />
			        <?php
		            	echo '<div class="wrap_set_color">';
			            foreach( $presets as $color ) {
			                echo '<span class="set_color" data-color="'.esc_attr($color).'" style="background-color:'.esc_attr($color).'"></span>';
			            }
			            echo '</div>';
		            ?>
            	</div>
		    </div>
		    <script>
				jQuery(document).ready(function($) {
		        	$('.category_color').wpColorPicker();
				});

			</script>
			<?php
		}

		public function class_category_edit_meta_field($term) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			?>
			<tr class="form-field">
		        <th scope="row" valign="top"><label for="term_meta-category_color"><?php esc_html_e( 'Color', 'noo-timetable' ); ?></label></th>
		        <td>
		        	<div class="wrap-wp-colorpicker">
			            <?php
			            	$color_pk	= get_term_meta( $term->term_id, 'category_color', true );
			            	$presets   = noo_timetable_get_presets_color();
			            ?>
			            <input type="text"  class="category_color" name="category_color" id="term_meta-category_color" value="<?php echo esc_html($color_pk); ?>" />
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
		                    $('.category_color').wpColorPicker();
						});
					</script>
		        </td>
		    </tr>
			<?php
		}

		public function class_category_save_meta_field( $term_id, $tt_id ) {
			if( isset( $_POST['category_color'] )){
		    	$group = '';
		    	if('' !== $_POST['category_color']){
		    		$group = '#'.sanitize_title( $_POST['category_color'] );
		    	}
		        add_term_meta( $term_id, 'category_color', $group, true );
		    }
		}

		public function class_category_update_meta_field( $term_id, $tt_id ){
		    if( isset( $_POST['category_color'] )){
		    	$group = '';
		    	if('' !== $_POST['category_color']){
		    		$group = '#'.sanitize_title( $_POST['category_color'] );
		    	}
		        update_term_meta( $term_id, 'category_color', $group );
		    }
		}

		public function class_category_edit_column( $columns ){
		    $columns['category_color'] = esc_html__( 'Color', 'noo-timetable' );
		    return $columns;
		}

    	public function add_meta_boxes() {

			// Declare helper object
			// 
			$helper = new Noo__Timetable_Meta_Boxes_Helper( '_noo_wp_class', array(
				'page' => 'noo_class'
			));

			// Get Option
			global $wp_locale;
			$weekday_options = array();
			$start_of_week = (int) get_option('start_of_week');
			for ($day_index = $start_of_week; $day_index <= 7 + $start_of_week; $day_index++) :
				$weekday_options[$day_index%7] = $wp_locale->get_weekday($day_index%7);
			endfor;

			$trainer_options = $this->get_trainer_options();

			// Settings
			$meta_box = array(
				'id'          => "class_settings",
				'title'       => esc_html__( 'Class Settings', 'noo-timetable') ,
				'description' => esc_html__( 'Enter the opening date for the class, it will be shown out a timetable based on the number of weeks that you enter.', 'noo-timetable') ,
				'fields'      => array(
					array(
						'id'       => '_open_date',
						'label'    => esc_html__( 'Open Date', 'noo-timetable' ),
						'type'     => 'datepicker',
						'callback' => array( &$this, 'meta_box_datepicker' )
					),
					array(
						'id'       => '_open_time',
						'label'    => esc_html__( 'Open Time', 'noo-timetable' ),
						'type'     => 'timepicker',
						'callback' => array( &$this, 'meta_box_timepicker' )
					),
					array(
						'id'       => '_close_time',
						'label'    => esc_html__( 'Close Time', 'noo-timetable' ),
						'type'     => 'timepicker',
						'callback' => array( &$this, 'meta_box_timepicker' )
					),
					array( 
						'id'       => '_trainer', 
						'label'    => esc_html__( 'Trainers', 'noo-timetable' ), 
						'type'     => 'select_multiple_chosen', 
						'options'  => $trainer_options,
						'callback' => array( &$this, 'meta_box_select_multiple' )
					),
					array( 
						'id'       => '_address',
						'label'    => esc_html__( 'Address', 'noo-timetable' ), 
						'type'     => 'text'
					),
					array( 
						'id'       => '_use_manual_settings', 
						'label'    => esc_html__( 'Set Dates Manually', 'noo-timetable' ), 
						'type'     => 'checkbox'
					),
					array( 
						'id'       => '_manual_settings', 
						'label'    => esc_html__( 'Add your date', 'noo-timetable' ), 
						'options'  => array(),
						'type'     => 'select_multiple',
						'callback' => array( &$this, 'meta_box_timeslot_multiple' )
					),
					array( 
						'id'       => '_number_of_weeks', 
						'label'    => esc_html__( 'Number of Weeks', 'noo-timetable' ), 
						'type'     => 'text',
					),
					array( 
						'id'       => '_number_day', 
						'label'    => esc_html__( 'Days of Week', 'noo-timetable' ), 
						'type'     => 'select_multiple_chosen', 
						'options'  => $weekday_options, 
						'callback' => array( &$this, 'meta_box_select_multiple' )
					),
					array( 
						'id'       => '_use_advanced_multi_time', 
						'label'    => esc_html__( 'Use Advanced Weekly Schedule', 'noo-timetable' ), 
						'type'     => 'checkbox'
					),
					array( 
						'id'       => '_advanced_multi_time', 
						'label'    => esc_html__( 'Advanced Schedule', 'noo-timetable' ), 
						'options'  => $weekday_options,
						'type'     => 'select_multiple',
						'callback' => array( &$this, 'meta_box_tab_multiple' )
					),
					array(
						'type'     => 'divider'
					),
					array(
						'id'       => '_register_link',
						'label'    => esc_html__( 'Register Link', 'noo-timetable' ),
						'type'     => 'text',
						'desc'     => esc_html__( 'Use this if you want to link the registration somewhere.', 'noo-timetable' ),
						'std'      => ''
					)
				)
			);

			$helper->add_meta_box($meta_box);
    	}

    	public function meta_box_timeslot_multiple($post, $id, $type, $meta, $std, $field) {

			wp_enqueue_script('datetimepicker');
			wp_enqueue_style('datetimepicker');

			$args = array(
				'post_type'     => 'noo_trainer',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'suppress_filters' => 0
			);

			$trainers = get_posts($args); //new WP_Query($args);
			$trainer_options = array();
			$trainer_options[] = array('label'=>__('Select Trainer&hellip;','noo-timetable'),'value'=>'0');

			if(!empty($trainers)){
				foreach ($trainers as $trainer){
					$trainer_options[] = array('label'=>$trainer->post_title,'value'=>$trainer->ID);
				}
			}

			$new_manual_date_id        = '_manual_date';
			$new_manual_open_time_id   = '_manual_open_time';	
			$new_manual_closed_time_id = '_manual_closed_time';
			$new_manual_trainer_id     = '_manual_trainer';
			$new_manual_address_id     = '_manual_address';

			$meta_manual_date        = noo_timetable_get_post_meta(  $post->ID, $new_manual_date_id, '' );
		    $meta_manual_open_time   = noo_timetable_get_post_meta(  $post->ID, $new_manual_open_time_id, '' );
		    $meta_manual_closed_time = noo_timetable_get_post_meta(  $post->ID, $new_manual_closed_time_id, '' );
		    $meta_manual_trainer     = noo_timetable_get_post_meta(  $post->ID, $new_manual_trainer_id, '' );
		    $meta_manual_address     = noo_timetable_get_post_meta(  $post->ID, $new_manual_address_id, '' );
		    
		    $meta_manual_date        = (array) noo_timetable_json_decode($meta_manual_date);
		    $meta_manual_open_time   = (array) noo_timetable_json_decode($meta_manual_open_time);
		    $meta_manual_closed_time = (array) noo_timetable_json_decode($meta_manual_closed_time);
		    $meta_manual_trainer     = (array) noo_timetable_json_decode($meta_manual_trainer);
		    $meta_manual_address     = (array) noo_timetable_json_decode($meta_manual_address);

		    $time_format = 'H:i';
		    
			?>

			<div class="noo-control">
				<p><?php echo esc_html__('Include first settings', 'noo-timetable'); ?></p>
				<div class="row-append">
					<table class="row-schedule-item" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th></th>
								<th></th>
								<th><?php echo esc_html__('Open Date', 'noo-timetable'); ?></th>
								<th><?php echo esc_html__('Open Time', 'noo-timetable'); ?></th>
								<th><?php echo esc_html__('Close Time', 'noo-timetable'); ?></th>
								<th><?php echo esc_html__('Trainers', 'noo-timetable'); ?></th>
								<th><?php echo esc_html__('Address', 'noo-timetable'); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php if ( is_array($meta_manual_date) && count($meta_manual_date) > 0 ) : foreach ($meta_manual_date as $k => $mm_date) :
							
							$date_open_text = is_numeric( $mm_date ) ? date( 'm/d/Y', $mm_date ) : $mm_date;
							$date_open = is_numeric( $mm_date ) ? $mm_date : strtotime( $mm_date );

							$time_open_text = '';
							$time_open = '';
							if ( isset($meta_manual_open_time[$k]) ){
								$time_open_text = is_numeric( $meta_manual_open_time[$k] ) ? date( $time_format, $meta_manual_open_time[$k] ) : $meta_manual_open_time[$k];
								$time_open = is_numeric( $meta_manual_open_time[$k] ) ? $meta_manual_open_time[$k] : strtotime( $meta_manual_open_time[$k] );
							}

							$time_closed_text = '';
							$time_closed = '';
							if ( isset($meta_manual_closed_time[$k]) ){
								$time_closed_text = is_numeric( $meta_manual_closed_time[$k] ) ? date( $time_format, $meta_manual_closed_time[$k] ) : $meta_manual_closed_time[$k];
								$time_closed = is_numeric( $meta_manual_closed_time[$k] ) ? $meta_manual_closed_time[$k] : strtotime( $meta_manual_closed_time[$k] );
							}
						?>
						<tr>
							<td><div class="button-action minus">-</div></td>
							<td class="sort"><i class="dashicons-grid-view dashicons-before"></i></td>
							<td>
								<input class="add_date" placeholder="<?php echo esc_html__( 'Default is the general Open Date', 'noo-timetable' ); ?>" type="text" readonly="" class="input_text" id="<?php echo $new_manual_date_id; ?>" value="<?php echo esc_attr( $date_open_text ); ?>">
								<input type="hidden" name="noo_meta_boxes[<?php echo $new_manual_date_id; ?>][]" value="<?php echo esc_attr( $date_open ); ?>">
							</td>
							<td>
								<input class="add_time" placeholder="<?php echo esc_html__( 'Default is the general Open Time', 'noo-timetable' ); ?>" type="text" class="input_text" id="<?php echo $new_manual_open_time_id; ?>" value="<?php echo esc_attr( $time_open_text ); ?>">
								<input type="hidden" name="noo_meta_boxes[<?php echo $new_manual_open_time_id; ?>][]" value="<?php echo esc_attr( $time_open ); ?>">
							</td>
							<td>
								<input class="add_time" placeholder="<?php echo esc_html__( 'Default is the general Close Time', 'noo-timetable' ); ?>" type="text" class="input_text" id="<?php echo $new_manual_closed_time_id; ?>" value="<?php echo esc_attr( $time_closed_text ); ?>">
								<input type="hidden" name="noo_meta_boxes[<?php echo $new_manual_closed_time_id; ?>][]" value="<?php echo esc_attr( $time_closed ); ?>">
							</td>
							<td>
								<select class="alter_parent_trainer" multiple>
									<?php
									$exp_trainer = explode(',', $meta_manual_trainer[$k]);
									foreach ($trainer_options as $trainer) :
										echo '<option value='.$trainer['value'];
											selected( in_array($trainer['value'], $exp_trainer), true );
										echo '>'.$trainer['label'].'</option>';
									endforeach; ?>
								</select>
								<input type="hidden" name="noo_meta_boxes[<?php echo $new_manual_trainer_id; ?>][]" value="<?php echo $meta_manual_trainer[$k]; ?>">
							</td>
							<td><input type="text" name="noo_meta_boxes[<?php echo $new_manual_address_id; ?>][]" class="input_text" id="" value="<?php echo isset($meta_manual_address[$k]) ? esc_attr($meta_manual_address[$k]) : ''; ?>"></td>
						</tr>
						<?php endforeach;
						else:
							?>
						<tr>
							<td><div class="button-action minus">-</div></td>
							<td class="sort"><i class="dashicons-grid-view dashicons-before"></i></td>
							<td>
								<input class="add_date" placeholder="<?php echo esc_html__( 'Default is the general Open Date', 'noo-timetable' ); ?>" type="text" readonly="" class="input_text" id="<?php echo $new_manual_date_id; ?>" value="">
								<input type="hidden" name="noo_meta_boxes[<?php echo $new_manual_date_id; ?>][]" value="">
							</td>
							<td>
								<input class="add_time" placeholder="<?php echo esc_html__( 'Default is the general Open Time', 'noo-timetable' ); ?>" type="text" class="input_text" id="<?php echo $new_manual_open_time_id; ?>" value="">
								<input type="hidden" name="noo_meta_boxes[<?php echo $new_manual_open_time_id; ?>][]" value="">
							</td>
							<td>
								<input class="add_time" placeholder="<?php echo esc_html__( 'Default is the general Close Time', 'noo-timetable' ); ?>" type="text" class="input_text" id="<?php echo $new_manual_closed_time_id; ?>" value="">
								<input type="hidden" name="noo_meta_boxes[<?php echo $new_manual_closed_time_id; ?>][]" value="">
							</td>
							<td>
								<select class="alter_parent_trainer" multiple>
									<?php foreach ($trainer_options as $trainer) :
										echo '<option value='.$trainer['value'].'>'.$trainer['label'].'</option>';
									endforeach; ?>
								</select>
								<input type="hidden" name="noo_meta_boxes[<?php echo $new_manual_trainer_id; ?>][]" value="">
							</td>
							<td><input type="text" name="noo_meta_boxes[<?php echo $new_manual_address_id; ?>][]" class="input_text" id="" value=""></td>
						</tr>
						</tbody>
						<?php
						endif; ?>
					</table>
				</div>
				<div class="button-action add">+</div>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#<?php echo $new_manual_open_time_id; ?>, #<?php echo $new_manual_closed_time_id; ?>').datetimepicker({
							format:"H:i",
							step:5,
							timepicker: true,
							datepicker: false,
							scrollInput: false,
							onChangeDateTime:function(dp,$input){
								if ((typeof(dp) !== 'undefined') && (dp !== null)) {
									$input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
								}
							}
						});

						$('.add_date').datetimepicker({
							format:"m/d/Y",
							timepicker: false,
							datepicker: true,
							scrollInput: false,
							closeOnDateSelect: true,
							onChangeDateTime:function(dp,$input){
								if ((typeof(dp) !== 'undefined') && (dp !== null)) {
									$input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
								}
							}
						});

						$('#_use_manual_settings').change(function(){
							check_manual_settings($(this));
						});
						check_manual_settings($('#_use_manual_settings'));
						function check_manual_settings(obj) {
							if ( obj.prop('checked') == false ) {
								$('._manual_settings').hide();
								$('._number_of_weeks').show();
								$('._number_day').show();
								$('#_number_day_chosen').css('width', '100%');
								$('._use_advanced_multi_time').show();
								if ( $('#_use_advanced_multi_time').prop('checked') == false ) {
									$('._advanced_multi_time').hide();
								} else {
									$('._advanced_multi_time').show();
								}
							} else {
								$('._manual_settings').show();
								$('._number_of_weeks').hide();
								$('._number_day').hide();
								$('._use_advanced_multi_time').hide();
								$('._advanced_multi_time').hide();
							}
						}

					});
					
				</script>
			</div>

			<?php
		}

    	public function meta_box_datepicker( $post, $id, $type, $meta, $std, $field ) {

			wp_enqueue_script( 'datetimepicker' );
			wp_enqueue_style( 'datetimepicker' );
			$date_format = 'm/d/Y';

			// @TODO: this function is kept because of the old data in previous version
			// should change to the version in core file generate-meta-box.php in near future.
			$date_text = is_numeric( $meta ) ? date( $date_format, $meta ) : $meta;
			$date = is_numeric( $meta ) ? $meta : strtotime( $meta );

			echo '<div>';
			echo '<input type="text" readonly class="input_text" id="' . $id . '" value="' .
				esc_attr( $date_text ) . '" /> ';
			echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="' .
				esc_attr( $date ) . '" /> ';
			echo '</div>';
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('#<?php echo esc_js($id); ?>').datetimepicker({
						format:"<?php echo esc_html( $date_format ); ?>",
						timepicker: false,
						datepicker: true,
						scrollInput: false,
						closeOnDateSelect: true,
						onChangeDateTime:function(dp,$input){
							if ((typeof(dp) !== 'undefined') && (dp !== null)) {
								$input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
							}
						}
					});
				});
			</script>

		<?php
		}

		public function meta_box_timepicker( $post, $id, $type, $meta, $std, $field ) {
			
			wp_enqueue_script( 'timepicker' );
			wp_enqueue_style( 'timepicker' );
			$date_format = 'H:i';

			// @TODO: this function is kept because of the old data in previous version
			// should change to the version in core file generate-meta-box.php in near future.
			$date_text = is_numeric( $meta ) ? date( $date_format, $meta ) : $meta;
			$date = is_numeric( $meta ) ? $meta : strtotime( $meta );

			echo '<div>';
			echo '<input type="text" readonly class="input_text" id="' . $id . '" value="' .
				esc_attr( $date_text ) . '" /> ';
			echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="' .
				esc_attr( $date ) . '" /> ';
			echo '</div>';
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('#<?php echo esc_js($id); ?>').timepicker({
						onClose: function(timeText, inst) {
							var dp = new Date();
                            dp.setHours(inst.hours);
                            dp.setMinutes(inst.minutes);
                            jQuery(this).next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
				        }
					});
				});
			</script>
		<?php
		}

		public function meta_box_select_multiple($post, $id, $type, $meta, $std, $field) {

			if ( 'select_multiple_chosen' == $type ) {
				wp_enqueue_script( 'chosen-js');
				wp_enqueue_style( 'chosen-css');
			}

			$meta = $meta ? $meta : $std;
			$meta = noo_timetable_json_decode( $meta );
			echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="'.implode(',', $meta).'" />';
			echo'<select id="'.$id.'" name="noo_meta_boxes[' . $id . '][]" multiple>';
			if( isset( $field['options'] ) && !empty( $field['options'] ) ) {
				foreach ( $field['options'] as $key=>$option ) {
					$opt_value  = $key;
					$opt_label  = $option;
					echo '<option';
					echo ' value="'.$opt_value.'"';
					if ( count($meta) > 0 && $meta[0] != '' && in_array($opt_value, (array) $meta)  )
						echo ' selected="selected"';
					echo '>' . $opt_label . '</option>';
				}
			}
			echo '</select>';

			if ( 'select_multiple_chosen' == $type ) {
			?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#<?php echo esc_js($id); ?>').chosen();
                        $('#<?php echo esc_js($id); ?>').on("change", function() {
                            $('input[name="noo_meta_boxes[<?php echo esc_js($id); ?>]"]').val($(this).val());
                        });
					});
				</script>
			<?php
			}
		}

		public function meta_box_tab_multiple($post, $id, $type, $meta, $std, $field) {

			wp_enqueue_script('jquery-ui-tabs');

			wp_enqueue_script('datetimepicker');
			wp_enqueue_style('datetimepicker');

			$args = array(
				'post_type'     => 'noo_trainer',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'suppress_filters' => 0
			);

			$trainers = get_posts($args); //new WP_Query($args);
			$trainer_options = array();
			$trainer_options[] = array('label'=>__('Select Trainer&hellip;','noo-timetable'),'value'=>'0');

			if(!empty($trainers)){
				foreach ($trainers as $trainer){
					$trainer_options[] = array('label'=>$trainer->post_title,'value'=>$trainer->ID);
				}
			}


			?>
			<div id="tabs">
				<ul>
				<?php
				if( isset( $field['options'] ) && !empty( $field['options'] ) ) {
					foreach ( $field['options'] as $key=>$option ) {
						echo '<li><a href="#tabs-'.$key.'">'.$option.'</a></li>';
					}
				}
				?>
				</ul>
				<?php
					$time_format = 'H:i';
					foreach ( $field['options'] as $key=>$option ) {
						$opt_value      = $key;
						$opt_label      = $option;
						$new_open_id    = '_open_time_' . $key;
						$new_closed_id  = '_closed_time_' . $key;	
						$new_trainer_id = '_trainer_' . $key;
						$new_address_id = '_address_' . $key;

						$meta_open    = noo_timetable_get_post_meta( $post->ID, $new_open_id, '' );
						$meta_closed  = noo_timetable_get_post_meta( $post->ID, $new_closed_id, '' );
						$meta_trainer = noo_timetable_get_post_meta( $post->ID, $new_trainer_id, '' );
						$meta_address = noo_timetable_get_post_meta( $post->ID, $new_address_id, '' );
						
						$meta_open    = (array) noo_timetable_json_decode($meta_open);
						$meta_closed  = (array) noo_timetable_json_decode($meta_closed);
						$meta_trainer = (array) noo_timetable_json_decode($meta_trainer);
						$meta_address = (array) noo_timetable_json_decode($meta_address);

						echo '<div id="tabs-'.$opt_value.'">';
						?>
						<div class="noo-control">
							<div class="row-append">
								<table class="row-schedule-item" cellpadding="0" cellspacing="0">
									<thead>
										<tr>
											<th></th>
											<th></th>
											<th><?php echo esc_html__('Open Time', 'noo-timetable'); ?></th>
											<th><?php echo esc_html__('Close Time', 'noo-timetable'); ?></th>
											<th><?php echo esc_html__('Trainers', 'noo-timetable'); ?></th>
											<th><?php echo esc_html__('Address', 'noo-timetable'); ?></th>
										</tr>
									</thead>
									<tbody>
									<?php if ( is_array($meta_open) && count($meta_open) > 0 ) : foreach ($meta_open as $k => $mopen) :
										$time_open_text = is_numeric( $mopen ) ? date( $time_format, $mopen ) : $mopen;
										$time_open = is_numeric( $mopen ) ? $mopen : strtotime( $mopen );

										$time_closed_text = '';
										$time_closed = '';
										if ( isset($meta_closed[$k]) ){
											$time_closed_text = is_numeric( $meta_closed[$k] ) ? date( $time_format, $meta_closed[$k] ) : $meta_closed[$k];
											$time_closed = is_numeric( $meta_closed[$k] ) ? $meta_closed[$k] : strtotime( $meta_closed[$k] );
										}
									?>
									<tr>
										<td><div class="button-action minus">-</div></td>
										<td class="sort"><i class="dashicons-grid-view dashicons-before"></i></td>
										<td>
											<input class="add_time" placeholder="<?php echo esc_html__( 'Default is the general Open Time', 'noo-timetable' ); ?>" type="text" class="input_text" id="<?php echo $new_open_id; ?>" value="<?php echo esc_attr( $time_open_text ); ?>">
											<input type="hidden" name="noo_meta_boxes[<?php echo $new_open_id; ?>][]" value="<?php echo esc_attr( $time_open ); ?>">
										</td>
										<td>
											<input class="add_time" placeholder="<?php echo esc_html__( 'Default is the general Close Time', 'noo-timetable' ); ?>" type="text" class="input_text" id="<?php echo $new_closed_id; ?>" value="<?php echo esc_attr( $time_closed_text ); ?>">
											<input type="hidden" name="noo_meta_boxes[<?php echo $new_closed_id; ?>][]" value="<?php echo esc_attr( $time_closed ); ?>">
										</td>
										<td>
											<select class="alter_parent_trainer" multiple>
												<?php
												$exp_trainer = explode(',', $meta_trainer[$k]);
												foreach ($trainer_options as $trainer) :
													echo '<option value='.$trainer['value'];
														selected( in_array($trainer['value'], $exp_trainer), true );
													echo '>'.$trainer['label'].'</option>';
												endforeach; ?>
											</select>
											<input type="hidden" name="noo_meta_boxes[<?php echo $new_trainer_id; ?>][]" value="<?php echo $meta_trainer[$k]; ?>">
										</td>
										<td><input type="text" name="noo_meta_boxes[<?php echo $new_address_id; ?>][]" class="input_text" id="" value="<?php echo isset($meta_address[$k]) ? esc_attr($meta_address[$k]) : ''; ?>"></td>
									</tr>
									<?php endforeach;
									else:
										?>
									<tr>
										<td><div class="button-action minus">-</div></td>
										<td class="sort"><i class="dashicons-grid-view dashicons-before"></i></td>
										<td>
											<input class="add_time" placeholder="<?php echo esc_html__( 'Default is the general Open Time', 'noo-timetable' ); ?>" type="text" class="input_text" id="<?php echo $new_open_id; ?>" value="">
											<input type="hidden" name="noo_meta_boxes[<?php echo $new_open_id; ?>][]" value="">
										</td>
										<td>
											<input class="add_time" placeholder="<?php echo esc_html__( 'Default is the general Close Time', 'noo-timetable' ); ?>" type="text" class="input_text" id="<?php echo $new_closed_id; ?>" value="">
											<input type="hidden" name="noo_meta_boxes[<?php echo $new_closed_id; ?>][]" value="">
										</td>
										<td>
											<select class="alter_parent_trainer" multiple>
												<?php foreach ($trainer_options as $trainer) :
													echo '<option value='.$trainer['value'].'>'.$trainer['label'].'</option>';
												endforeach; ?>
											</select>
											<input type="hidden" name="noo_meta_boxes[<?php echo $new_trainer_id; ?>][]" value="">
										</td>
										<td><input type="text" name="noo_meta_boxes[<?php echo $new_address_id; ?>][]" class="input_text" id="" value=""></td>
									</tr>
									</tbody>
									<?php
									endif; ?>
								</table>
							</div>
							<div class="button-action add">+</div>
							<script type="text/javascript">
								jQuery(document).ready(function($) {
									$('#<?php echo $new_open_id; ?>, #<?php echo $new_closed_id; ?>').datetimepicker({
										format:"H:i",
										step:5,
										timepicker: true,
										datepicker: false,
										scrollInput: false,
										onChangeDateTime:function(dp,$input){
											if ((typeof(dp) !== 'undefined') && (dp !== null)) {
												$input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
											}
										}
									});

								});
							</script>
						</div>
						<?php
						echo '</div>';
					}
				?>
			</div> <!--# Tab -->
			<script>
				jQuery('document').ready(function ($) {
					$( "#tabs" ).tabs();
					$('#_use_advanced_multi_time').change(function(){
						show_hide_multi_time($(this));
					});
					$('#_number_day').change(function(){
						show_hide_multi_time($('#_use_advanced_multi_time'));
					});
					show_hide_multi_time($('#_use_advanced_multi_time'));

					$('.row-schedule-item tbody').sortable({
				        items:'tr',
				        cursor:'move',
				        axis:'y',
				        handle: 'td.sort',
				        scrollSensitivity:40,
				        forcePlaceholderSize: true,
				        helper: 'clone',
				        opacity: 0.65
				    });

					$('.button-action.add').click(function(){
						var _html = $(this).closest('.noo-control').find('table tbody tr:last-child').html();
						$(this).closest('.noo-control').find('table tbody').append('<tr>'+_html+'</tr>');
						$(this).closest('.noo-control').find('table tbody tr:last-child').find('input, select').val('');
						$(this).closest('.noo-control').find('table tbody tr:last-child').find('select[multiple]').removeClass('full-height');

						$('.add_time').datetimepicker({
							format:"H:i",
							step:5,
							timepicker: true,
							datepicker: false,
							scrollInput: false,
							onChangeDateTime:function(dp,$input){
								if ((typeof(dp) !== 'undefined') && (dp !== null)) {
									$input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
								}
							}
						});

						$('.add_date').datetimepicker({
							format:"m/d/Y",
							timepicker: false,
							datepicker: true,
							scrollInput: false,
							closeOnDateSelect: true,
							onChangeDateTime:function(dp,$input){
								if ((typeof(dp) !== 'undefined') && (dp !== null)) {
									$input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
								}
							}
						});

						$('.button-action.minus').click(function(){
							if ($(this).closest('.noo-control').find('table tbody tr').length > 1){
								$(this).closest('tr').hide(300, function(){
									$(this).remove();
								});
							}
						});

						$('.alter_parent_trainer').each(function(){
							$(this).change(function(){
								var _element = $(this).next();
								_element.val( $(this).val() );
							});
						});
					});

					$('.alter_parent_trainer').each(function(){
						$(this).change(function(){
							var _element = $(this).next();
							_element.val( $(this).val() );
						});
					});

					// $('.alter_parent_trainer').change(function(){
					// 	// reload_multi_trainer($(this));
					// });

					$('.button-action.minus').click(function(){
						if ($(this).closest('.noo-control').find('table tbody tr').length > 1){
							$(this).closest('tr').hide(300, function(){
								$(this).remove();
							});
						}
					});

					function reload_multi_trainer(){
						var string = [];
						$('.alter_parent_trainer').each(function(){
							if ( $(this).val() != '' ){
								string.push( $(this).val() );
							}
						});
						trainer_clone = $('#_trainer').clone();
						parent_noo = $('#_trainer').closest('.noo-control');
						parent_noo.html(trainer_clone);
						$('#_trainer').val(string);
						$('#_trainer').chosen();
						parent_noo.find('.chosen-container-multi').width('100%');
						
					}

					function show_hide_multi_time(obj){
						
						if(obj.prop('checked') == false){
							$('._advanced_multi_time').hide();
						}else{
							if ( $('#_use_manual_settings').prop('checked') == false ) {
								$('._advanced_multi_time').show();
							}
						}
						$arr = $('#_number_day').val();

						$('.ui-tabs-nav li').addClass('ui-state-disabled');
						if ($arr) {
							for(var i=0; i<$arr.length; i++){
								$('.ui-tabs-nav li[aria-controls="tabs-'+$arr[i]+'"]').removeClass('ui-state-disabled');
							}
						}
						$('.ui-tabs-nav li').each(function(index){
							if( ! $(this).hasClass('ui-state-disabled') ){
								$( "#tabs" ).tabs({ active: index });		
								return false;
							}
						})
					}
			  	});
			</script>
			<?php
		}

		public function get_trainer_options() {
			$trainer_options = array();
			$trainers = get_posts(
				array(
					'post_type'        => 'noo_trainer',
					'posts_per_page'   => -1,
					'post_status'      => 'publish',
					'suppress_filters' => 0
				)
			);
			if ( !empty($trainers) ) {
				foreach ($trainers as $trainer){
					$trainer_options[$trainer->ID] = $trainer->post_title;
				}
			}

			return $trainer_options;
		}

		public function manage_edit_columns( $columns ) {
			$new_columns = array();
			$new_columns['cb'] = $columns['cb'];
			$new_columns['title'] = $columns['title'];
			$new_columns['open_date'] = __('Open Date','noo-timetable');
			$new_columns['number_day'] = __('Number Days','noo-timetable');
			$new_columns['trainer'] = __( 'Trainer', 'noo-timetable' );
			unset( $columns['cb'] );
			unset( $columns['title'] );
			return array_merge( $new_columns, $columns );
		}
			
		public function manage_custom_column( $column) {
			global $post,$wp_locale;
			$open_date		    = noo_timetable_get_post_meta( $post->ID, "_open_date", '' );
			$number_days		= noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );
			if($column === 'trainer'){
				if($trainer_id = noo_timetable_get_post_meta(get_the_ID(),'_trainer')){
					if (!is_array($trainer_id)) {
						$trainer_id = str_replace(array('[', ']', '"'), array('', '', ''), $trainer_id);	
						$trainer_id = explode(',', $trainer_id);
					} 
					foreach ($trainer_id as $itd => $tid) {
						echo edit_post_link(get_the_title($tid),'','',$tid);
						if ( ($itd + 1) < count($trainer_id) ) echo ', ';	
					}
				}else{
					echo '&ndash;';
				}
			}elseif ($column === 'open_date'){
				if ($open_date != '')
					echo date_i18n(__( 'Y/m/d', 'noo-timetable' ),$open_date);
			}elseif ($column === 'number_day'){
				$number_day_arr = array();
				?>
				<?php foreach ((array)$number_days as $number_day) :?>
		        	<?php if( $number_day !== '' && $number_day !== null ) $number_day_arr[] = esc_html($wp_locale->get_weekday_abbrev($wp_locale->get_weekday($number_day))); ?>
				<?php endforeach; ?>
				<?php echo implode(' - ', $number_day_arr)?>
				<?php
			}
			return $column;
		}

    }

    new Noo__Timetable__Class_Meta_Boxes();
}