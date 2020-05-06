<?php
/**
 * Post Types Class
 *
 * Registers post types and taxonomies.
 *
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/PostTypes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists('Noo__Timetable__Class') ) {

	class Noo__Timetable__Class {

		public function __construct(){
			add_action( 'init', array( $this, 'register_post_type' ) );
			add_filter( 'template_include', array( $this, 'template_loader' ) );

			if ( !is_admin() ) :
				add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ), 100);
			endif;

			add_action( 'wp_ajax_noo_class_filter', array(&$this, 'class_filter') );
			add_action( 'wp_ajax_nopriv_noo_class_filter', array(&$this, 'class_filter') );

			add_action( 'wp_ajax_noo_class_and_event', array(&$this, 'noo_class_and_event') );
			add_action( 'wp_ajax_nopriv_noo_class_and_event', array(&$this, 'noo_class_and_event') );

			add_action( 'wp_ajax_noo_class_responsive_navigation', array(&$this, 'class_responsive_navigation') );
			add_action( 'wp_ajax_nopriv_noo_class_responsive_navigation', array(&$this, 'class_responsive_navigation') );

			// Set schedule for update next_class
			add_action( 'noo-class-update-next-day', array( $this, 'update_next_day' ) );
			add_action( 'save_post', array( $this, 'setup_one_class_schedule' ) );
			add_action( 'init', array( &$this, 'setup_classes' ), 0 );

			if ( is_admin() ) :
				add_action( 'admin_init', array( &$this, 'holidays_setting_save' ) );
				add_action( 'admin_menu', array( $this,'add_menu_page' ) );

				add_action( 'customize_save', array($this,'customizer_set_transients_before_save') );
				add_action( 'customize_save_after', array($this,'customizer_set_transients_after_save') );
			endif;
		}
		public static function get_coming_class_ids( $only_upcoming = false ) {
			global $wpdb;
			$classes = (array) $wpdb->get_results(
				"SELECT $wpdb->posts.ID, $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_value
                    FROM $wpdb->posts
                    LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE $wpdb->posts.post_status = 'publish'
                    AND $wpdb->posts.post_type = 'noo_class'
                    AND ($wpdb->postmeta.meta_key = '_open_date' OR $wpdb->postmeta.meta_key = '_number_of_weeks' OR $wpdb->postmeta.meta_key = '_number_day') " );

			foreach ($classes as $k => $v) {
				$newarr[$v->ID][$v->meta_key] = $v->meta_value;
			}

			$rearr = array();
			foreach ($newarr as $k => $v) {
				if (!isset($v['_open_date']) || $v['_open_date'] == '') {
					continue;
				}
				if ( $only_upcoming && $v['_open_date'] <= noo_timetable_time_now() ) {
					continue;
				}
				$number_of_weeks = $v['_number_of_weeks'] != '' ? $v['_number_of_weeks'] : 1;
				$end_date = strtotime("+" . $number_of_weeks . " week", $v['_open_date']);
				if ( noo_timetable_time_now() <= $end_date  ) {
					$rearr[] = $k;
				}
			}

			return $rearr;
		}

		public function get_format_date_holidays() {
			$obj = new Noo__Timetable__Class();
			$holidays = $obj->get_holidays( (array) get_option('holidays_class_schedule') );
			$date = array();
			foreach ($holidays as $key => $value) {
				$date[] = date('Y-n-j', $value);
			}
			return $date;
		}

		function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' ) {

			$dates = array();

			if ( ! is_numeric($first) ){
				$current = strtotime($first);
			} else {
				$current = $first;
			}

			if ( ! is_numeric($last) ){
				$last = strtotime($last);
			}

			while( $current <= $last ) {

				$dates[] = date($output_format, $current);
				$current = strtotime($step, $current);
			}

			return $dates;
		}

		public function get_holidays($days_value = array(), $fullarray = false, $dayLimit = '') {
			$range = array();
			$range_full = array();
			$i = 0;
			if ( isset($days_value['start']) ) {
				while ( $i < count($days_value['start']) ) {
					if ( !empty( $days_value['start'][$i] ) && '' != $days_value['start'][$i] ) {
						if($dayLimit !== '' && !empty( $days_value['end'][$i]) && ($days_value['end'][$i] > $dayLimit)) {
							$rag_start = '';
							$rag_end   = '';

							$rag_start = $days_value['start'][$i];
							$description = '';
							if ( isset($days_value['description']) ) {
								$description = $days_value['description'][$i];
							}

							$rag_end = $days_value['end'][$i];

							if ( '' != $rag_start && '' != $rag_end )
							{
								$rag_range = $this->date_range( $rag_start, $rag_end, "+1 day" );
								foreach ($rag_range as $rvalue) {
									$range[] = strtotime($rvalue);

									if ($fullarray === true) {
										$item = array();
										$item['day'] = strtotime($rvalue);
										$item['description'] = $description;
										$range_full[] = $item;
									}
								}
							} else {
								$range[] = $rag_start;

								if ($fullarray === true) {
									$item = array();
									$item['day'] = $rag_start;
									$item['description'] = $description;
									$range_full[] = $item;
								}
							}
						} else {
							$rag_start = '';
							$rag_end   = '';

							$rag_start = $days_value['start'][$i];
							$description = '';
							if ( isset($days_value['description']) ) {
								$description = $days_value['description'][$i];
							}

							if ( !empty( $days_value['end'][$i] ) && '' != $days_value['end'][$i] ) {
								$rag_end = $days_value['end'][$i];
							}

							if ( '' != $rag_start && '' != $rag_end )
							{
								$rag_range = $this->date_range( $rag_start, $rag_end, "+1 day" );
								foreach ($rag_range as $rvalue) {
									$range[] = strtotime($rvalue);

									if ($fullarray === true) {
										$item = array();
										$item['day'] = strtotime($rvalue);
										$item['description'] = $description;
										$range_full[] = $item;
									}
								}
							} else {
								$range[] = $rag_start;

								if ($fullarray === true) {
									$item = array();
									$item['day'] = $rag_start;
									$item['description'] = $description;
									$range_full[] = $item;
								}
							}
						}
					}

					$i++;
				}
			}

			if ($fullarray === true) {
				return $range_full;
			} else {
				return $range;
			}

		}

		public function get_holiday_classes($days_value = array()) {

			$range = array();
			$i = 0;
			$holiday_classes = array();
			$classes = '';
			if ( isset($days_value['start']) ) {
				while ( $i < count($days_value['start']) ) {

					if ( !empty( $days_value['start'][$i] ) && '' != $days_value['start'][$i] ) {

						$rag_start = '';
						$rag_end   = '';

						$rag_start = $days_value['start'][$i];

						if ( !empty( $days_value['end'][$i] ) && '' != $days_value['end'][$i] ) {
							$rag_end = $days_value['end'][$i];
						}

						if (!empty($days_value['classes'][$i])) {
							$classes = $days_value['classes'][$i];
						}
						
						$classes_arr = array();
						if($classes != '') {
							$classes_arr = explode(',', $classes);
                        }
						foreach($classes_arr as $cl_arr) {
						    if( !in_array($cl_arr, $holiday_classes) ) {
							    array_push($holiday_classes, $cl_arr);
                            }
                        }
					}

					$i++;
				}
			}
			return $holiday_classes;
		}
		/*
		@ $days_value: all holiday params
		@ $class_id: Use to check if this class is displayed during a vacation.
		@ $date: Use to get the corresponding class id to compare with $ class_id
		*/
		public function check_holiday_classes_display($days_value = array(), $class_id, $date) {

			if ( isset($days_value['start']) ) {

				if ( !empty( $days_value['start'] ) && '' != $days_value['start'] ) {
					$key = array_search( $date, $days_value['start'] );

					if(!empty($key)){
						$id = $days_value['classes'][$key];
						return $id == $class_id;
					}else{
						return false;
					}
				}
			}
			return false;
		}

		public function holidays_setting_save() {
			register_setting( 'holidays-setting-group', 'holidays_class_schedule' );
		}

		public function add_menu_page(){
			add_submenu_page( 'edit.php?post_type=noo_class', 'Holidays on Class Schedule', 'Holidays Setting', 'manage_options', 'holidays-class-schedule', array($this, 'holidays_config') );
		}

		public function holidays_config(){

			wp_enqueue_script( 'jquery-ui-sortable' );
			$days_value = (array) get_option('holidays_class_schedule');
			$class_args = array(
			    'posts_per_page'    => -1,
                'post_type'         => 'noo_class',
                'post_status'       => 'publish'
            );
			$class_query = new WP_Query( $class_args );

			?>
            <div class="wrap holidays-config">
                <h2><?php esc_html_e('Holidays on Class Schedule', 'noo-timetable'); ?></h2>

                <form method="post" action="options.php">
					<?php settings_fields( 'holidays-setting-group' ); ?>
					<?php do_settings_sections( 'holidays-setting-group' ); ?>
                    <p>&nbsp;</p>
                    <p><?php esc_html_e('Classes on the schedule will be hidden if the fall into the below days.', 'noo-timetable'); ?></p>
                    <p><?php esc_html_e('If your holiday is on a particular date, please only select Start Date and leave End Day Empty', 'noo-timetable'); ?></p>
                    <p><?php esc_html_e('If your holiday lasts more than one day, please select both Start Day and End Day', 'noo-timetable'); ?></p>
                    <div class="noo-control">
                        <div class="row-append">
                            <table class="row-schedule-item form-table" cellpadding="0" cellspacing="0">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th><?php echo esc_html__('Select Start Day', 'noo-timetable'); ?></th>
                                    <th><?php echo esc_html__('Select End Day', 'noo-timetable'); ?></th>
                                    <th><?php echo esc_html__('Description', 'noo-timetable'); ?></th>
                                    <th><?php echo esc_html__('Select Classes In Holiday', 'noo-timetable'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								if ( is_array($days_value) && count($days_value) > 0 && isset( $days_value['start'] ) ) :
									foreach ($days_value['start'] as $k => $value) :

										$text_start_day = is_numeric( $days_value['start'][$k] ) ? date( 'Y/m/d', $days_value['start'][$k] ) : $days_value['start'][$k];
										$text_end_day = is_numeric( $days_value['end'][$k] ) ? date( 'Y/m/d', $days_value['end'][$k] ) : $days_value['end'][$k];
										?>
                                        <tr>
                                            <td><div class="button-action minus">-</div></td>
                                            <td class="sort"><i class="dashicons-grid-view dashicons-before"></i></td>

                                            <td>
                                                <input type="text" readonly="readonly" class="input-holiday date-start regular-text" value="<?php echo isset($text_start_day) ? esc_attr($text_start_day) : ''; ?>">
                                                <input type="hidden" name="holidays_class_schedule[start][]" value="<?php echo isset($days_value['start'][$k]) ? esc_attr($days_value['start'][$k]) : ''; ?>" />
                                            </td>

                                            <td>
                                                <input type="text" readonly="readonly" class="input-holiday date-end regular-text" value="<?php echo isset($text_end_day) ? esc_attr($text_end_day) : ''; ?>">
                                                <input type="hidden" name="holidays_class_schedule[end][]" value="<?php echo isset($days_value['end'][$k]) ? esc_attr($days_value['end'][$k]) : ''; ?>" />
                                            </td>

                                            <td>
                                                <textarea name="holidays_class_schedule[description][]" class="input-holiday regular-text"><?php echo isset($days_value['description'][$k]) ? esc_attr($days_value['description'][$k]) : ''; ?></textarea>
                                            </td>

                                            <td>
                                                <?php $classes = isset($days_value['classes'][$k]) ? explode( ',', $days_value['classes'][$k] ) : array(); ?>
                                                <input type="hidden" name="holidays_class_schedule[classes][]" class="input-holiday holiday-class regular-text" value="<?php echo isset($days_value['classes'][$k]) ? esc_attr($days_value['classes'][$k]) : ''; ?>"/>
                                                <select class="holiday_classes" multiple>
                                                    <?php
                                                    if ( $class_query->have_posts() ) {
	                                                    // The Loop
	                                                    while ( $class_query->have_posts() ) {
		                                                    $class_query->the_post();
		                                                    echo '<option value="' . get_the_ID() . '" ' . ( in_array( get_the_ID(),
				                                                    $classes ) ? 'selected="true"' : '' ) . '>' . get_the_title() . '</option>';
	                                                    }
	                                                    wp_reset_postdata();
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                        </tr>
									<?php
									endforeach;
								else : ?>
                                    <tr>
                                        <td><div class="button-action minus">-</div></td>
                                        <td class="sort"><i class="dashicons-grid-view dashicons-before"></i></td>

                                        <td>
                                            <input type="text" readonly="readonly" class="input-holiday date-start regular-text" value="">
                                            <input type="hidden" name="holidays_class_schedule[start][]" value="" />
                                        </td>

                                        <td>
                                            <input type="text" readonly="readonly" class="input-holiday date-end regular-text" value="">
                                            <input type="hidden" name="holidays_class_schedule[end][]" value="" />
                                        </td>
                                        <td>
                                            <textarea name="holidays_class_schedule[description][]" class="input-holiday regular-text"></textarea>
                                        </td>
                                        <td>
                                            <textarea name="holidays_class_schedule[classes][]" class="input-holiday regular-text"></textarea>
                                        </td>
                                    </tr>
								<?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="button-action add">+</div>
                    </div>
					<?php submit_button(); ?>
                </form>
            </div> <!-- .class -->

            <style>
                .holidays-config .noo-control {
                    width: 100%;
                }
                .holidays-config .form-table thead th {
                    padding-left: 20px;
                }
                .holidays-config .form-table td:nth-of-type(1),
                .holidays-config .form-table td:nth-of-type(2) {
                    width: 1%;
                }
                .holidays-config .form-table td:nth-of-type(3),
                .holidays-config .form-table td:nth-of-type(4) {
                    width: 20%;
                }
                .holidays-config .form-table td:nth-of-type(6) {
                    width: 20%;
                }
            </style>

            <script type="text/javascript">
                jQuery(document).ready(function($) {

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

                        $(this).closest('.noo-control').find('table tbody tr:last-child').find('select').removeClass("chzn-done").removeAttr("id").css("display", "block").next().remove();
                        $(this).closest('.noo-control').find('table tbody tr:last-child').find('select').chosen();
                        $(this).closest('.noo-control').find('table tbody tr:last-child').find('select').unbind("change").bind( "change", function(e, params) {
                            var input_val = $(this).parent().find( "input.holiday-class" ).val();
                            var check_selected = "selected" in params;
                            var input_val_arr = input_val.split(",");
                            var new_val = "";
                            if(check_selected) {
                                if(input_val == "") {
                                    new_val = input_val + params.selected;
                                } else {
                                    new_val = input_val +","+ params.selected;
                                }
                            } else {
                                new_val = input_val_arr.filter( function(ele) {
                                    return ele != params.deselected;
                                });
                                new_val = new_val.join(",");
                            }
                            $(this).parent().find( "input.holiday-class" ).val( new_val );
                        });

                        $('.button-action.minus').click(function(){
                            if ($(this).closest('.noo-control').find('table tbody tr').length > 1){
                                $(this).closest('tr').hide(300, function(){
                                    $(this).remove();
                                });
                            }
                        });

                        $(this).closest('.noo-control').find('table tbody tr:last-child').find('.date-start').datetimepicker({
                            format: "Y/m/d",
                            timepicker: false,
                            datepicker: true,
                            scrollInput: false,
                            closeOnDateSelect: true,
                            onShow:function( ct, $input ){
                                this.setOptions({
                                    maxDate:$input.closest('tr').find('.date-end').val()?$input.closest('tr').find('.date-end').val():false
                                })
                            },
                            onChangeDateTime:function(dp,$input){
                                if ((typeof(dp) !== 'undefined') && (dp !== null)) {
                                    $input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
                                }
                            }
                        });

                        $(this).closest('.noo-control').find('table tbody tr:last-child').find('.date-end').datetimepicker({
                            format: "Y/m/d",
                            timepicker: false,
                            datepicker: true,
                            scrollInput: false,
                            closeOnDateSelect: true,
                            onShow:function( ct,$input ){
                                this.setOptions({
                                    minDate:$input.closest('tr').find('.date-start').val()?$input.closest('tr').find('.date-start').val():false
                                })
                            },
                            onChangeDateTime:function(dp,$input){
                                if ((typeof(dp) !== 'undefined') && (dp !== null)) {
                                    $input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
                                }
                            }
                        });

                    });

                    $('.button-action.minus').click(function(){
                        if ($(this).closest('.noo-control').find('table tbody tr').length > 1){
                            $(this).closest('tr').hide(300, function(){
                                $(this).remove();
                            });
                        } else {
                            var inputs = $(this).closest('tr').find('input');
                            var textarea = $(this).closest('tr').find('textarea');
                            var select = $(this).closest('tr').find('select');
                            inputs.val('');
                            textarea.val('');
                            select.find('option').removeAttr('selected');
                            select.trigger('chosen:updated');
                        }
                    });

                    $('.date-start').datetimepicker({
                        format: "Y/m/d",
                        timepicker: false,
                        datepicker: true,
                        scrollInput: false,
                        closeOnDateSelect: true,
                        onShow:function( ct, $input ){
                            this.setOptions({
                                maxDate:$input.closest('tr').find('.date-end').val()?$input.closest('tr').find('.date-end').val():false
                            })
                        },
                        onChangeDateTime:function(dp,$input){
                            if ((typeof(dp) !== 'undefined') && (dp !== null)) {
                                $input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
                            }
                        }
                    });

                    $('.date-end').datetimepicker({
                        format: "Y/m/d",
                        timepicker: false,
                        datepicker: true,
                        scrollInput: false,
                        closeOnDateSelect: true,
                        onShow:function( ct,$input ){
                            this.setOptions({
                                minDate:$input.closest('tr').find('.date-start').val()?$input.closest('tr').find('.date-start').val():false
                            })
                        },
                        onChangeDateTime:function(dp,$input){
                            if ((typeof(dp) !== 'undefined') && (dp !== null)) {
                                $input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
                            }
                        }
                    });

                    $('.holiday_classes').chosen({
                        width: "100%",
                        display_selected_options: false,
                    });
                    $( "select.holiday_classes" ).unbind("change").bind( "change", function(e, params) {
                        var input_val = $(this).parent().find( "input.holiday-class" ).val();
                        var check_selected = "selected" in params;
                        var input_val_arr = input_val.split(",");
                        var new_val = "";
                        if(check_selected) {
                            if(input_val == "") {
                                new_val = input_val + params.selected;
                            } else {
                                new_val = input_val +","+ params.selected;
                            }
                        } else {
                            new_val = input_val_arr.filter( function(ele) {
                                return ele != params.deselected;
                            });
                            new_val = new_val.join(",");
                        }
                        $(this).parent().find( "input.holiday-class" ).val( new_val );
                    });
                });
            </script>
			<?php
		}

		public function template_loader( $template ) {

			$find = array();
			$file = '';

			if ( is_single() && get_post_type() == 'noo_class' ) {

				$file   = 'single-noo_class.php';
				$find[] = $file;
				$find[] = Noo__Timetable__Main::template_path() . $file;

			} elseif ( is_post_type_archive( 'noo_class' ) || is_tax( 'class_category' ) ) {

				$file   = 'archive-noo_class.php';
				$find[] = $file;
				$find[] = Noo__Timetable__Main::template_path() . $file;
			}

			if ( $file ) {
				$template = locate_template( array_unique( $find ) );

				if ( ! $template ) {
					$template = Noo__Timetable__Main::plugin_path() . '/templates/' . $file;
				}
			}

			return $template;
		}

		public function pre_get_posts($q){
			if ( ! $q->is_main_query() ) {
				return;
			}

			if ( is_post_type_archive('noo_class') && $q->get('post_type') == 'noo_class' ) {
				$orderby = NOO_Settings()->get_option('noo_classes_orderby', 'opendate');
				$order   = NOO_Settings()->get_option('noo_classes_order', 'asc');
				if( $orderby == 'opendate' ) {
					$q->set('meta_key', '_open_date');
					$q->set('orderby', 'meta_value_num');
					$q->set('order', ( $order == 'asc' ? 'ASC' : 'DESC' ));
				} else {
					$q->set('orderby', 'date');
					$q->set('order', ( $order == 'asc' ? 'ASC' : 'DESC' ));
				}
				$number = NOO_Settings()->get_option('noo_classes_number_class', 6);

				if ( is_numeric($number) ){
					$q->set('posts_per_page', $number);
				}
			}

			if(isset($_GET['trainer']) && is_post_type_archive('noo_class') && $q->get('post_type') == 'noo_class'){
				if( isset($_GET['load']) && $_GET['load'] == 'all' ) {
					$q->set('posts_per_page', -1);
				}
			}
		}

		public function register_post_type(){

			// Check post type exists
			if ( post_type_exists( 'noo_class' ) )
				return;

			// Clear transient
			if ( get_transient( 'noo_class_slug_before' ) != get_transient( 'noo_class_slug_after' ) ) {
				flush_rewrite_rules();
				delete_transient( 'noo_class_slug_before' );
				delete_transient( 'noo_class_slug_after' );
			}

			$class_slug = NOO_Settings()->get_option('noo_class_page', 'classes');
			// $class_slug = !empty($class_page) ? get_post( $class_page )->post_name : '';

			register_post_type(
				'noo_class',
				array(
					'labels' => array(
						'name'               => esc_html__( 'Classes', 'noo-timetable' ),
						'singular_name'      => esc_html__( 'Class', 'noo-timetable' ),
						'add_new'            => esc_html__( 'Add New Class', 'noo-timetable' ),
						'add_new_item'       => esc_html__( 'Add Class', 'noo-timetable' ),
						'edit'               => esc_html__( 'Edit', 'noo-timetable' ),
						'edit_item'          => esc_html__( 'Edit Class', 'noo-timetable' ),
						'new_item'           => esc_html__( 'New Class', 'noo-timetable' ),
						'view'               => esc_html__( 'View', 'noo-timetable' ),
						'view_item'          => esc_html__( 'View Class', 'noo-timetable' ),
						'search_items'       => esc_html__( 'Search Class', 'noo-timetable' ),
						'not_found'          => esc_html__( 'No Classes found', 'noo-timetable' ),
						'not_found_in_trash' => esc_html__( 'No Classes found in Trash', 'noo-timetable' ),
						'parent'             => esc_html__( 'Parent Class', 'noo-timetable' )
					),
					'public'      => true,
					'has_archive' => true,
					'menu_icon'   => 'dashicons-calendar-alt',
					'menu_position'      => 40,
					'rewrite'     => array( 'slug' => $class_slug, 'with_front' => false ),
					'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments','custom-fields' ),
					'can_export'  => true
				)
			);

			register_taxonomy(
				'class_category',
				'noo_class',
				array(
					'labels' => array(
						'name'          => esc_html__( 'Class Category', 'noo-timetable' ),
						'add_new_item'  => esc_html__( 'Add New Class Category', 'noo-timetable' ),
						'new_item_name' => esc_html__( 'New Class Category', 'noo-timetable' )
					),
					'hierarchical' => true,
					'query_var'    => true,
					'rewrite'      => array( 'slug' => 'class-category' )
				)
			);

			register_taxonomy(
				'class_level',
				'noo_class',
				array(
					'labels' => array(
						'name'          => esc_html__( 'Class Level', 'noo-timetable' ),
						'add_new_item'  => esc_html__( 'Add New Class Level', 'noo-timetable' ),
						'new_item_name' => esc_html__( 'New Class Level', 'noo-timetable' )
					),
					'hierarchical' => true,
					'query_var'    => true,
					'rewrite'      => array( 'slug' => 'class-level' )
				)
			);

		}

		public function customizer_set_transients_before_save() {
			set_transient( 'noo_class_slug_before', NOO_Settings()->get_option( 'noo_class_page', 'classes' ), 60 );
		}

		public function customizer_set_transients_after_save() {
			set_transient( 'noo_class_slug_after', NOO_Settings()->get_option( 'noo_class_page', 'classes' ), 60 );
		}

		public static function get_color_by_category($class_id) {
			$post_category = get_the_terms( $class_id, 'class_category' );
			if ( !empty($post_category) ) {
				$post_category = reset($post_category);
				$category_parent = $post_category->parent;
				if( !empty($category_parent) ):
					$color = get_term_meta( $post_category->parent, 'category_color', true );
				else:
					$color = get_term_meta( $post_category->term_id, 'category_color', true );
				endif;

				return $color;
			}
		}

		public static function get_trainer_list($trainer_ids) {
			?>
            <span title="<?php echo esc_html__('Trainer', 'noo-timetable'); ?>" class="trainer-info"><i class="fa fa-user"></i>
				<?php if ( is_array($trainer_ids) ) : ?>

					<?php foreach ($trainer_ids as $k => $trid) : ?>
                        <a href="<?php echo get_permalink($trid)?>"><?php echo get_the_title($trid) ?></a><?php if ( ($k+1) < count($trainer_ids) ) echo ', '; ?>
					<?php endforeach; ?>

				<?php else : ?>

                    <a href="<?php echo get_permalink($trainer_ids)?>"><?php echo get_the_title($trainer_ids) ?></a>

				<?php endif; ?>
            </span>
			<?php
		}

		public static function _get_week_day( $day, $get_text = false ) {

			if ( $get_text ) {

				global $wp_locale;
				return $wp_locale->get_weekday( $day );

			} else {
				// Not change
				switch( $day ) {
					case 0: return 'sunday';
					case 1: return 'monday';
					case 2: return 'tuesday';
					case 3: return 'wednesday';
					case 4: return 'thursday';
					case 5: return 'friday';
					case 6: return 'saturday';
				}

			}
			return '';
		}

		public static function convertPHPToMomentFormat($php_format) {
			$replacements = array(
				'd' => 'DD',
				'D' => 'ddd',
				'j' => 'D',
				'l' => 'dddd',
				'N' => 'E',
				'S' => 'o',
				'w' => 'e',
				'z' => 'DDD',
				'W' => 'W',
				'F' => 'MMMM',
				'm' => 'MM',
				'M' => 'MMM',
				'n' => 'M',
				't' => '', // no equivalent
				'L' => '', // no equivalent
				'o' => 'YYYY',
				'Y' => 'YYYY',
				'y' => 'YY',
				'a' => 'a',
				'A' => 'A',
				'B' => '', // no equivalent
				'g' => 'h',
				'G' => 'H',
				'h' => 'hh',
				'H' => 'HH',
				'i' => 'mm',
				's' => 'ss',
				'u' => 'SSS',
				'e' => 'zz', // deprecated since version 1.6.0 of moment.js
				'I' => '', // no equivalent
				'O' => '', // no equivalent
				'P' => '', // no equivalent
				'T' => '', // no equivalent
				'Z' => '', // no equivalent
				'c' => '', // no equivalent
				'r' => '', // no equivalent
				'U' => 'X',
			);
			$js_format = "";
			$escaping = false;
			for($i = 0; $i < strlen($php_format); $i++)
			{
				$char = $php_format[$i];
				if($char === '\\') // PHP date format escaping character
				{
					$i++;
					if($escaping) $js_format .= $php_format[$i];
					else $js_format .= '[' . $php_format[$i];
					$escaping = true;
				}
				else
				{
					if($escaping) { $js_format .= "]"; $escaping = false; }
					if(isset($replacements[$char]))
						$js_format .= $replacements[$char];
					else
						$js_format .= $char;
				}
			}
			if($escaping) { $js_format .= "]"; $escaping = false; }
			// $js_format = strtr($php_format, $replacements);
			return $js_format;
		}

		public function get_class_filter_list( $data_filter = '', $filter_type = '' ) {
			$list_arr = array();
			if($data_filter == 'all') {
				if($filter_type == 'category') {
					$categories = get_terms( 'class_category' );
					if ( $categories ){
						foreach ($categories as $category) {
							$cate_obj = new stdClass();
							$cate_obj->id    = $category->term_id;
							$cate_obj->title = $category->name;
							$list_arr[] = $cate_obj;
						}
					}
				} else if($filter_type == 'trainer') {
					$trainers = get_posts([
						'post_type' => 'noo_trainer',
						'post_status' => 'publish',
						'numberposts' => -1,
					]);

					if ( $trainers ){
						foreach ($trainers as $trainer) {
							$trainer_obj = new stdClass();
							$trainer_obj->id    = $trainer->ID;
							$trainer_obj->title = $trainer->post_title;
							$list_arr[] = $trainer_obj;
						}
					}
				} else {
					$levels = get_terms( 'class_level' );
					if ( $levels ){
						foreach ($levels as $level) {
							$level_obj = new stdClass();
							$level_obj->id    = $level->term_id;
							$level_obj->title = $level->name;
							$list_arr[] = $level_obj;
						}
					}
				}
			} else {
				$value_selected = explode(",", $data_filter);
				if($filter_type == 'category') {
					foreach($value_selected as $val) {
						$category = get_term( $val, 'class_category' );
						if($category) {
							$cate_obj = new stdClass();
							$cate_obj->id    = $category->term_id;
							$cate_obj->title = $category->name;
							$list_arr[] = $cate_obj;
						}
					}
				} else if($filter_type == 'trainer') {
					$trainers = get_posts([
						'post_type' => 'noo_trainer',
						'post_status' => 'publish',
						'numberposts' => -1,
						'post__in' => $value_selected,
					]);
					if ($trainers)
					{
						foreach( $value_selected as $val)
						{
							foreach ($trainers as $trainer)
							{
								if( $trainer->ID == $val)
								{
									$trainer_obj        = new stdClass();
									$trainer_obj->id    = $trainer->ID;
									$trainer_obj->title = $trainer->post_title;
									$list_arr[]         = $trainer_obj;
								}
							}
						}
					}
				} else {
					foreach($value_selected as $val) {
						$level = get_term( $val, 'class_level' );
						if($level) {
							$level_obj = new stdClass();
							$level_obj->id    = $level->term_id;
							$level_obj->title = $level->name;
							$list_arr[] = $level_obj;
						}
					}
				}
			}

			return $list_arr;
		}

		public function _get_schedule_category_list( $cat = '' ) {


			$categories = get_terms( 'class_category' );

			$cat_select = explode(",", $cat);

			$categories_arr = array();
			if ( $categories ){
				foreach ($categories as $category) {

					$cate_obj = new stdClass();
					$cate_obj->id    = $category->term_id;
					$cate_obj->title = $category->name;

					if ( $cat == '' || $cat == 'all' ) {
						$categories_arr[] = $cate_obj;
					}
					else {
						if ( in_array($category->term_id, $cat_select) ) {
							$categories_arr[] = $cate_obj;
						}
					}
				}
			}

			return $categories_arr;
		}
		public function class_filter() {

			if( check_ajax_referer('class_filter','security' , false) ) {
				wp_send_json('');
			}
			$from = isset( $_POST['from'] ) ? $_POST['from'] : '';
			$to = isset( $_POST['to'] ) ? $_POST['to'] : '';
			$category      = isset( $_POST['class_category'] ) ? $_POST['class_category'] : '';
			$shorcode_attr = isset( $_POST['shorcode_attr'] ) ? json_decode( stripslashes($_POST['shorcode_attr']), true) : array();
			wp_send_json($this->show_schedule_class_list($from, $to, $category, 'category', $shorcode_attr));
		}

		public function show_schedule_class_list( $from, $to, $category = '', $filter_type = '', $attrs = array() ) {
			global $wpdb;
			extract($attrs);
			$body_item_style                        = isset($class_item_style) ? $class_item_style : '';
			$body_color_by_category                 = isset($class_show_category) ? $class_show_category : '';
			$noo_schedule_class_show_icon           = isset($class_show_icon) ? $class_show_icon : 'no';
			$noo_schedule_navigate_link             = isset($general_navigate_link) ? $general_navigate_link : 'internal';
			$noo_schedule_general_header_background = isset($general_header_background) ? $general_header_background : '';
			$show_excerpt_in_modal                  = isset($general_popup_excerpt) ? $general_popup_excerpt : 'yes';
			$show_all_tab                           = isset($show_all_tab) ? $show_all_tab : 'yes';

			// Get holidays
			$holidays = $this->get_holidays( (array) get_option('holidays_class_schedule'), false, strtotime( $from ) );
			$holiday_classes = $this->get_holiday_classes( (array) get_option('holidays_class_schedule') );			

			if($default_view == 'month' or $default_view == 'agendaWeek') {
				if($default_view == 'month') {
					$prev_from = date('Y-m-d',( strtotime( '-1 month' , strtotime( $from ) ) ) );
					$prev_to = date('Y-m-t', strtotime($prev_from));

					$next_from = date('Y-m-d',( strtotime( '+1 month' , strtotime( $from ) ) ) );
					$next_to = date('Y-m-t', strtotime($next_from));
				} else {
					$prev_from = date('Y-m-d',( strtotime( '-1 week' , strtotime( $from) ) ) );
					$prev_to = date('Y-m-d',( strtotime( '+1 week' , strtotime( $prev_from ) ) ) );
					$prev_to = date('Y-m-d',( strtotime( '-1 days' , strtotime( $prev_to ) ) ) );

					$next_from = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $from ) ) ) );
					$next_to = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $next_from ) ) ) );
					$next_to = date('Y-m-d',( strtotime ( '-1 days' , strtotime ( $next_to ) ) ) );
				}
			} else {
				$prev_from = date('Y-m-d',( strtotime ( '-1 days' , strtotime ( $from) ) ) );
				$prev_to = $prev_from;
				$next_from = date('Y-m-d',( strtotime ( '+1 days' , strtotime ( $to ) ) ) );
				$next_to = $next_from;
			}

			$args = array(
				'posts_per_page'    => -1,
				'post_type'         => 'noo_class',
				'post_status'       => 'publish',
                'tax_query'         => array(),
			);

			$query_string = '';
			if($category == 'all') {
				if($class_show_all_tab == 'no')
				{
					if ($filter_type == 'category')
					{
						$categories = get_terms('class_category');
						if ($categories)
						{
							$args['tax_query'] = array(
								array(
									'taxonomy'  => 'class_category',
									'field'     => 'term_taxonomy_id',
									'terms'     => $categories[0]->term_id
								)
							);
						}
					}
					else
					{
						$levels = get_terms('class_level');
						if ($levels)
						{
							$args['tax_query'] = array(
								array(
									'taxonomy'  => 'class_level',
                                    'field'     => 'term_taxonomy_id',
                                    'terms'     => $levels[0]->term_id
								)
							);
						}
					}
				}
			} else {
				$value_selected = explode(",", $category);
				$list_arr = [];
				if(is_array($value_selected) && !empty($value_selected[0])){
					if($filter_type == 'category') {
						// foreach($value_selected as $val) {
						// 	$category = get_term( $val, 'class_category' );
						// 	if($category) {
						// 		$list_arr[] = $category->term_id;
						// 	}
						// }
						$args['tax_query'] = array(
							array(
								'taxonomy'  => 'class_category',
								'field'     => 'term_taxonomy_id',
								'terms'     => $value_selected
							)
						);
					}else {
						// foreach($value_selected as $val) {
						// 	$level = get_term( $val, 'class_level' );
						// 	if($level) {
						// 		$list_arr[] = $level->term_id;
						// 	}
						// }
						$args['tax_query'] = array(
							array(
								'taxonomy'  => 'class_level',
								'field'     => 'term_taxonomy_id',
								'terms'     => $value_selected
							)
						);
					}
				}
			}

			$classes = new WP_Query( $args );
			if( isset($show_weekends) && !is_array($show_weekends) ){
				$show_weekends = explode(',', $show_weekends);
			}

			if($classes->have_posts()){
				while ($classes->have_posts()){
					$classes->the_post();

					$open_date          = noo_timetable_get_post_meta( get_the_ID(), "_open_date", '' );
					$number_days        = noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );
					$number_weeks       = (int) noo_timetable_get_post_meta( get_the_ID(), "_number_of_weeks", '1' );
					$end_date           = date_i18n('Y-m-d', strtotime (  date_i18n('Y-m-d', (int) $open_date ) . ' + ' . $number_weeks.' weeks' ) );
					$start_of_week      = Noo__Timetable__Class::_get_week_day( get_option('start_of_week') );

					$first_week_day = date_i18n( 'Y-m-d', strtotime('last ' . $start_of_week, strtotime($from)) );
					$day1 = date_i18n("Y-m-d", strtotime($start_of_week . ' this week', strtotime($to)));
					$end_week_day = date_i18n( 'Y-m-d', strtotime($day1 . ' +6 days') );

					$register_link      = noo_timetable_get_post_meta( get_the_ID(), "_register_link", '' );

					if (empty($open_date)) continue;

					$post_category = wp_get_post_terms( get_the_ID(), 'class_category' );
					$color = '#87D4CB';
					$category_id = '';
					if(!empty($post_category)){
						$post_category = reset($post_category);
						$category_parent = $post_category->parent;
						if(!empty($category_parent)):
							// $color = get_term_meta( $post_category->parent, 'category_color', true );
							$category_id = $post_category->parent;
						else:
							// $color = get_term_meta( $post_category->term_id, 'category_color', true );
							$category_id = $post_category->term_id;
						endif;
                        $color = get_term_meta( $post_category->term_id, 'category_color', true );
					}

					$cl_obj = new stdClass();
					$cl_obj->id = get_the_ID();
					$cl_obj->title = get_the_title();

                    $cl_obj->showcatBycolor = $body_color_by_category;
					$cl_obj->borderColor = 'transparent';
                    $cl_obj->backgroundColor = $color; // Class background color
                    $cl_obj->backgroundMobileColor = $color;
                    $feat_image_url = array('');
                    $feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), array(800, 600) );
                    $cl_obj->backgroundImage = '';
                    $cl_obj->popup_bgImage = isset($feat_image_url[0]) ? $feat_image_url[0] : '';

                    if($body_item_style == 'item_bg_image'){
                        $cl_obj->backgroundImage = isset($feat_image_url[0]) ? $feat_image_url[0] : '';
                        $cl_obj->backgroundColor = '';
                        $cl_obj->catColor = $color != '' ? $color : '#87D4CB';
                    }elseif($body_item_style == 'cat_bg_color'){
                        $cl_obj->backgroundColor = $color != '' ? $color : '#87D4CB';
                        $cl_obj->catColor = $color != '' ? $color : '#87D4CB';
                    }elseif($body_item_style == 'item_bg_color'){
                        $cl_obj->backgroundColor = $color != '' ? $color : '#87D4CB';
                        $cl_obj->catColor = $color != '' ? $color : '#87D4CB';
                    }else{
                        $cl_obj->backgroundColor = '#fff';
                        $cl_obj->catColor = $color != '' ? $color : '#87D4CB';
                    }
					
					$cl_obj->register = $register_link;
					if ( $noo_schedule_navigate_link != 'disable' ) {
						$cl_obj->url = get_the_permalink(get_the_ID());
						if ( $noo_schedule_navigate_link == 'external' ) {
							if ( $register_link != '' ) {
								$cl_obj->url = $register_link;
							}
						}
					}

					$cl_obj->register_link = $register_link;

					$cl_obj->address = noo_timetable_get_post_meta( get_the_ID(), "_address", '' );

					$cl_obj->resourceId = 0;

					if(!empty($post_category)){
						$cl_obj->categoryName = $post_category->name;
						$cl_obj->resourceId = $post_category->term_id;
					}

					if($source == 'both' && $default_view == 'agendaDay') {
						$cl_obj->resourceId = 'all';
                    }

					$cl_obj->className = 'md-trigger fc-noo-class';

					if(!empty($category_id))
						$cl_obj->className = 'md-trigger fc-noo-class fc-class-'.$category_id;

					if ( $noo_schedule_class_show_icon == 'yes' ) {
						$cl_obj->className .= ' show-icon';
					}
                    if ($body_item_style == 'categoryColor' || $body_item_style == 'cat_bg_color' || $body_item_style == 'item_bg_image') {
                        $cl_obj->className .= ' class-'.$body_item_style;
                    }

					$trainer = (array) noo_timetable_json_decode( noo_timetable_get_post_meta(get_the_ID(), '_trainer') );

					$cl_obj->level = strip_tags(get_the_term_list(get_the_ID(), 'class_level',' ',', '));

					if ( is_array($trainer) && !empty($trainer['0']) ) {
						$temp_name = array();
						foreach ($trainer as $tr_id) {
							$temp_name[] = get_the_title($tr_id);
						}
						$cl_obj->trainer = implode(', ', $temp_name);
					} else {
						$cl_obj->trainer = esc_attr__('not trainer yet','noo-timetable');
					}

					if ( $show_excerpt_in_modal == 'yes' ) {
						// $excerpt = htmlentities(get_the_excerpt());
						$excerpt = get_the_excerpt();
						if(empty($excerpt))
							$excerpt = get_the_content();

						$exc_length = NOO_Settings()->get_option('noo_classes_excerpt_length', 18);
						$excerpt = wp_trim_words( $excerpt, $exc_length, '...');
						$cl_obj->excerpt = do_shortcode($excerpt);
					}

					$use_manual_settings = noo_timetable_get_post_meta( get_the_ID(), "_use_manual_settings", false );
					$open_time           = noo_timetable_get_post_meta(  get_the_ID(), "_open_time", '1470301200' );
					$close_time          = noo_timetable_get_post_meta(  get_the_ID(), "_close_time", '1470308400' );

					if ( $use_manual_settings ) {
						$clone_cl_obj = new stdClass();
						$clone_cl_obj = clone $cl_obj;
						$clone_cl_obj->start = date_i18n('Y-m-d',$open_date).'T'.date_i18n('H:i',$open_time);
						$clone_cl_obj->end = date_i18n('Y-m-d',$open_date).'T'.date_i18n('H:i',$close_time);
						if ( ! in_array($open_date, $holidays) || ( in_array($clone_cl_obj->id, $holiday_classes) && in_array($open_date, $holidays ) ) ) {
							$classes_arr[] = $clone_cl_obj;
						}

						$meta_manual_date        = noo_timetable_get_post_meta(  get_the_ID(), "_manual_date", '' );
						$meta_manual_open_time   = noo_timetable_get_post_meta(  get_the_ID(), "_manual_open_time", '' );
						$meta_manual_closed_time = noo_timetable_get_post_meta(  get_the_ID(), "_manual_closed_time", '' );
						$meta_manual_trainer     = noo_timetable_get_post_meta(  get_the_ID(), "_manual_trainer", '' );
						$meta_manual_address     = noo_timetable_get_post_meta(  get_the_ID(), "_manual_address", '' );

						$meta_manual_date        = (array) noo_timetable_json_decode($meta_manual_date);
						$meta_manual_open_time   = (array) noo_timetable_json_decode($meta_manual_open_time);
						$meta_manual_closed_time = (array) noo_timetable_json_decode($meta_manual_closed_time);
						$meta_manual_trainer     = (array) noo_timetable_json_decode($meta_manual_trainer);
						$meta_manual_address     = (array) noo_timetable_json_decode($meta_manual_address);
						if ( count($meta_manual_date) == 1 && $meta_manual_date[0] == '' ) {
							$meta_manual_date = array();
						} elseif( $meta_manual_date[0] == '' ){
							$meta_manual_date[] = $open_date;
						}
						if( $meta_manual_open_time && $meta_manual_open_time[0] == '' )
							$meta_manual_open_time[] = $open_time;
						if( $meta_manual_closed_time && $meta_manual_closed_time[0] == '' )
							$meta_manual_closed_time[] = $close_time;

						if ( $meta_manual_date ) {
							foreach ($meta_manual_date as $k => $mm_date) {

								$mm_date = ($mm_date && $mm_date != '') ? $mm_date : $open_date;

								if ( $meta_manual_open_time[$k] == '' && $meta_manual_closed_time[$k] == '' ) {
									$meta_manual_open_time[$k] = $open_time;
									$meta_manual_closed_time[$k] = $close_time;
								}

								if ( $meta_manual_open_time[$k] && $meta_manual_closed_time[$k] ) {

									$clone_cl_obj = new stdClass();
									$clone_cl_obj = clone $cl_obj;
									$clone_cl_obj->start = date_i18n('Y-m-d',$mm_date).'T'.date_i18n('H:i',$meta_manual_open_time[$k]);
									$clone_cl_obj->end = date_i18n('Y-m-d',$mm_date).'T'.date_i18n('H:i',$meta_manual_closed_time[$k]);

									if( isset($meta_manual_trainer[$k]) && $meta_manual_trainer[$k] != '' && $meta_manual_trainer[$k] != 0 ){
										$trainer_exp = explode(',', $meta_manual_trainer[$k]);
										$temp_name = array();
										foreach ($trainer_exp as $tr_id) {
											if ( is_numeric( $tr_id ) && $tr_id > 0 ) {
												$temp_name[] = get_the_title($tr_id);
											}
										}
										if ( count($temp_name) > 0 ) {
											$clone_cl_obj->trainer = implode(', ', $temp_name);
										}
									}
									if( isset($meta_manual_address[$k]) && $meta_manual_address[$k] != '' )
										$clone_cl_obj->address = $meta_manual_address[$k];

									if ( ! in_array($mm_date, $holidays) ) {
										$classes_arr[] = $clone_cl_obj;
									} else {
										if( in_array($clone_cl_obj->id, $holiday_classes ) ) {
											$classes_arr[] = $clone_cl_obj;
										}
                                    }
								}
							}
						}

					} else {
						if( $number_weeks > 0 && !empty( $number_days ) ) {
							$use_advanced_multi_time = noo_timetable_get_post_meta( get_the_ID(), "_use_advanced_multi_time", false );

							foreach( $number_days as $day ) {

								$week_day = self::_get_week_day( $day );

								if($use_advanced_multi_time) {
									$meta_open    = noo_timetable_get_post_meta(  get_the_ID(), "_open_time_".$day, '' );
									$meta_closed  = noo_timetable_get_post_meta(  get_the_ID(), "_closed_time_".$day, '' );
									$meta_trainer = noo_timetable_get_post_meta(  get_the_ID(), "_trainer_".$day, '' );
									$meta_address = noo_timetable_get_post_meta(  get_the_ID(), "_address_".$day, '' );

									$meta_open    = (array) noo_timetable_json_decode($meta_open);
									$meta_closed  = (array) noo_timetable_json_decode($meta_closed);
									$meta_trainer = (array) noo_timetable_json_decode($meta_trainer);
									$meta_address = (array) noo_timetable_json_decode($meta_address);

									if( $meta_open && $meta_open[0] == '' )
										$meta_open[] = $open_time;
									if( $meta_closed && $meta_closed[0] == '' )
										$meta_closed[] = $close_time;

									foreach ($meta_open as $k => $mopen) {
										if ( $mopen && $meta_closed[$k] ) {
											$start_day = strtotime("yesterday", $open_date);

											// Time class less than 2 hour
											$class_short = '';
											$t3 = strtotime(date('H:i', $meta_closed[$k])) - strtotime(date('H:i', $mopen));
											if ( $t3 <= 1800 )
												$class_short = 'time-short-1';
											if ( $t3 <= 3600 && $t3 > 1800 )
												$class_short = 'time-short-2';
											for( $week = 1; $week <= $number_weeks; $week++ ) {
												$new_date = strtotime( "next " . $week_day, $start_day );
												if($new_date >= strtotime($first_week_day) && $new_date <= strtotime($end_week_day) && $new_date <= strtotime($end_date))
												{
													$clone_cl_obj            = new stdClass();
													$clone_cl_obj            = clone $cl_obj;
													$clone_cl_obj->start     = date_i18n('Y-m-d', $new_date) . 'T' . date_i18n('H:i', $mopen);
													$clone_cl_obj->end       = date_i18n('Y-m-d', $new_date) . 'T' . date_i18n('H:i', $meta_closed[$k]);
													$clone_cl_obj->className = $clone_cl_obj->className . ' ' . $class_short;

													if (isset($meta_trainer[$k]) && $meta_trainer[$k] != '' && $meta_trainer[$k] != 0)
													{
														$trainer_exp = explode(',', $meta_trainer[$k]);
														$temp_name   = array();
														foreach ($trainer_exp as $tr_id)
														{
															if (is_numeric($tr_id) && $tr_id > 0)
															{
																$temp_name[] = get_the_title($tr_id);
															}
														}
														if (count($temp_name) > 0)
														{
															$clone_cl_obj->trainer = implode(', ', $temp_name);
														}
													}
													if (isset($meta_address[$k]) && $meta_address[$k] != '')
														$clone_cl_obj->address = $meta_address[$k];

													if ( !in_array($new_date, $holidays ) ) {
														$classes_arr[] = $clone_cl_obj;
													}
													else
													{
														if( in_array($clone_cl_obj->id, $holiday_classes ) ) {
															$classes_arr[] = $clone_cl_obj;
														}
														else
														{
															$week = $week - 1;
														}
													}

												}
													$start_day = strtotime("+1 week", $start_day);
											}
										}
									}

								} else {
									$start_day = strtotime("yesterday", $open_date);
									if( empty( $week_day ) ) continue;

									

									// Time class less than 2 hour
									$class_short = '';
									$t3 = strtotime(date('H:i', $close_time)) - strtotime(date('H:i', $open_time));
									if ( $t3 <= 1800 )
										$class_short = 'time-short-1';
									if ( $t3 <= 3600 && $t3 > 1800 )
										$class_short = 'time-short-2';

									for( $week = 1; $week <= $number_weeks; $week++ ) {
										$new_date = strtotime( "next " . $week_day, $start_day );

										if($new_date >= strtotime($first_week_day) && $new_date <= strtotime($end_week_day) && $new_date <= strtotime($end_date))
										{
											$clone_cl_obj            = new stdClass();
											$clone_cl_obj            = clone $cl_obj;
											$clone_cl_obj->start     = date_i18n('Y-m-d', $new_date) . 'T' . date_i18n('H:i', $open_time);
											$clone_cl_obj->end       = date_i18n('Y-m-d', $new_date) . 'T' . date_i18n('H:i', $close_time);
											$clone_cl_obj->className = $clone_cl_obj->className . ' ' . $class_short;

											$holiday_classes_check = $this->check_holiday_classes_display( (array) get_option('holidays_class_schedule'), $clone_cl_obj->id, $new_date);

											if ( !in_array($new_date, $holidays ) ) {
												$classes_arr[] = $clone_cl_obj;
											} else {
											    if( $holiday_classes_check ) {
												    $classes_arr[] = $clone_cl_obj;
											    }
											    else
											    {
												    $week = $week - 1;
											    }
											}
										}

										$start_day = strtotime("+1 week", $start_day);
									}
								}
							}
						}
					} // check if use_manual_settings
					if($default_view === 'agendaDay' && $source === 'both') {
						$cl_obj->resourceId = 'all';
					}
				}

			} // check if Classes

			// Setup holidays on schedules
			$holidays_arr = [];
			$holidays_full = $this->get_holidays( (array) get_option('holidays_class_schedule'), true, strtotime( $from ) );

			foreach ($holidays_full as $key => $hday) {
				$hol_day = date('Y-m-d', $hday['day']);
				$holiday_cl_obj                  = new stdClass();
				$holiday_cl_obj->day           = $hol_day;
				$holiday_cl_obj->description     = $hday['description'];
				$holiday_cl_obj->className       = 'fc-noo-class-holiday';
				$holiday_cl_obj->backgroundColor = $general_holiday_background;
				$holidays_arr[] = $holiday_cl_obj;
			}

			$result['events_data'] = isset($classes_arr) ? $classes_arr : [];
			$result['holidays_data'] = $holidays_arr;
			$result['datetime'] = [
				'prev_from' => $prev_from,
				'prev_to'   => $prev_to,
				'next_from' => $next_from,
				'next_to'   => $next_to,
				'cur_from' => $from,
				'cur_to'   => $to,
			];

			return $result;
		}


		public function noo_class_and_event(){
			if( check_ajax_referer('noo_class_and_event','security' , false) ) {
				return '';
			}

			$first_week_day = isset( $_POST['from'] ) ? $_POST['from'] : '';
			$end_week_day   = isset( $_POST['to'] ) ? $_POST['to'] : '';
			$category      = isset( $_POST['the_category'] ) ? $_POST['the_category'] : '';
			$shorcode_attr = isset( $_POST['shorcode_attr'] ) ? json_decode( stripslashes($_POST['shorcode_attr']), true) : array();
			?>
            <h2><?php echo esc_html_e('Class','noo-timetable');?></h2>
			<?php
			$this->_schedule_class_list_mobile($first_week_day, $end_week_day, $category, $shorcode_attr);?>
            <h2><?php echo esc_html_e('Event','noo-timetable');?></h2>
			<?php
			Noo__Timetable__Event::show_list_calender_mobile( $first_week_day, $end_week_day, $category, $shorcode_attr );
			exit();
		}
		public function class_responsive_navigation() {

			if( check_ajax_referer('class_responsive_navigation','security' , false) ) {
				return '';
			}

			$from          = isset( $_POST['from'] ) ? $_POST['from'] : '';
			$to            = isset( $_POST['to'] ) ? $_POST['to'] : '';
			$category      = isset( $_POST['the_category'] ) ? $_POST['the_category'] : '';
			$shorcode_attr = isset( $_POST['shorcode_attr'] ) ? json_decode( stripslashes($_POST['shorcode_attr']), true) : array();

			$this->_schedule_class_list_mobile($from, $to, $category, $shorcode_attr);

			exit();
		}

		public function _schedule_class_list_mobile( $from = '', $to = '', $the_category = '', $attrs = array() ) {

			extract($attrs);
			$weekends = true;
			global $wp_locale;
			$classes_arr = $this->show_schedule_class_list($from, $to, $the_category, 'all', $attrs);
			$classes_arr = $classes_arr['events_data'];
			$new_arr     = array();

			$datetime['from'] = $from;
			$datetime['to'] = $to;
			$from        = $from . '00:00';
			$to          = $to   . '23:59';

			foreach ($classes_arr as $key => $class) :
				$kq = null;
				$kq = str_replace('T', ' ', $class->start);
				$kq = strtotime($kq);
				if ( $kq !== null && isset($class->id) ) {

					// Remove class out of range
					if ($from != '' && $to != '') {

						if ( $kq < strtotime($from) || $kq > strtotime($to) ) {
							continue;
						}
					}

					// Remove weekends
					if ( !$weekends && ( date( "w", $kq) == 0 || date( "w", $kq) == 6 ) ) {
						continue;
					}
					$new_arr[$kq][] = array(
						'id'            => $class->id,
						'title'         => $class->title,
						'backgroundColor' => isset($class->backgroundColor)&& !empty($class->backgroundColor) ? $class->backgroundColor : '#87D4CB',
                        'backgroundMobileColor' => isset($class->backgroundMobileColor)&& !empty($class->backgroundMobileColor) ? $class->backgroundMobileColor : '#87D4CB',
						'url'           => isset($class->url) ? $class->url : '',
						'address'       => isset($class->address) ? $class->address : '',
						'categoryName'  => isset($class->categoryName) ? $class->categoryName : '',
						'className'     => $class->className,
						'trainer'       => $class->trainer,
						'level'         =>  isset($class->class_level) ? $class->class_level : '',
						'start'         => $class->start,
						'end'           => $class->end,
						'start_time'    => date_i18n( get_option( 'time_format' ), strtotime( str_replace('T', ' ', $class->start) ) ),
						'end_time'      => date_i18n( get_option( 'time_format' ), strtotime( str_replace('T', ' ', $class->end) ) ),
						'weekday'       => $wp_locale->get_weekday( date( "w", $kq) ),
						'excerpt'       => isset($class->excerpt) ? $class->excerpt : '',
					);

				}

			endforeach;
			ksort($new_arr);
			if ( count($new_arr) > 0 ) {
				$last_weekday = '';
				foreach ($new_arr as $key => $value) {
					foreach ($value as $k => $cl) {

						if ( $last_weekday != $cl['weekday'] ) :
							$today = '';
							if ( strtotime(date('Y-m-d', $key)) === strtotime(date('Y-m-d')) ) {
								$today = 'today';
							}
							?>
                            <div class="item-weekday <?php echo esc_attr($today); ?>">
								<?php echo $cl['weekday']; ?>
								<?php
								if ( 'yes' == $general_header_day ) {
									echo ' (' . date_i18n( get_option( 'date_format' ), $key ) . ')';
								}
								?>
                            </div>
						<?php endif; ?>
                        <div class="item-day" style="background-color: <?php echo $cl['backgroundMobileColor'] ?>">
                            <a href="<?php echo $cl['url']; ?>">
                                <div class="event-time">
                                    <span><?php echo esc_attr( $cl['start_time'] ); ?> - </span>
                                    <span><?php echo esc_attr( $cl['end_time'] ); ?></span>
                                </div>
                                <div class="event-title"><?php echo esc_attr( $cl['title'] ); ?></div>
								<?php if ($cl['trainer'] != '') : ?>
                                    <div class="class-trainer"><?php echo esc_html__(' ', 'noo-timetable'); ?><?php echo esc_attr( $cl['trainer'] ); ?></div>
								<?php endif; ?>
                            </a>
                        </div>
						<?php
						$last_weekday = $cl['weekday'];
					}
				}

			} else {
				echo '<center><p>'.esc_html('Class not found.', 'noo-timetable').'</p></center>';
			}

			//Create label
			$label_start = date_i18n( get_option( 'date_format' ), strtotime($from) );
			$label_end = date_i18n( get_option( 'date_format' ), strtotime($from . ' +6 days') );

			// Current
			$curr_start = $datetime['from'];
			$curr_end = date('Y-m-d', strtotime($datetime['from'] . ' +6 days') );

			// Create nav
			$prev_from = date('Y-m-d',( strtotime ( '-1 week' , strtotime ( $from ) ) ) );
			$prev_to = date('Y-m-d',( strtotime ( '-1 week' , strtotime ( $to ) ) ) );

			$next_from = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $from ) ) ) );
			$next_to = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $to ) ) ) );

			?>
            <input type="hidden" class="prev-from-hidden" value="<?php echo $prev_from; ?>" />
            <input type="hidden" class="prev-to-hidden" value="<?php echo $prev_to; ?>" />

            <input type="hidden" class="next-from-hidden" value="<?php echo $next_from; ?>" />
            <input type="hidden" class="next-to-hidden" value="<?php echo $next_to; ?>" />

            <input type="hidden" class="label-start" value="<?php echo $label_start; ?>" />
            <input type="hidden" class="label-end" value="<?php echo $label_end; ?>" />

            <input type="hidden" class="curr-start" value="<?php echo $curr_start; ?>" />
            <input type="hidden" class="curr-end" value="<?php echo $curr_end; ?>" />
			<?php
		}

		public static function _get_manual_date( $class_id ) {

		}

		public static function get_open_date_display( $args='' ) {
			$defaults = array(
				'open_date'       => '',
				'number_of_weeks' => 0,
				'number_days'     => array(),
				'manual_date'     => array(),
			);
			$args = wp_parse_args( $args, $defaults );
			extract($args);

			$open_date_display = noo_timetable_get_option( 'noo_class_open_date', 'all' );

			$next_date = null;
			if ( ( $open_date_display == 'next' || $open_date_display == 'all' ) ) {
				$next_date = self::_get_next_date(array(
					'open_date'       => $open_date,
					'number_of_weeks' => $number_of_weeks,
					'number_days'     => $number_days,
					'manual_date'     => $manual_date,
				));
			}

			// return array by get options display open date
			if ( $open_date_display == 'open' ) {
				$arr['open_date'] = $open_date;
			} elseif ( $open_date_display == 'next' ) {
				$arr['next_date'] = $next_date;
			} else {
				$arr = array(
					'open_date' => $open_date,
					'next_date' => $next_date
				);
			}
			if ( noo_timetable_time_now() < $open_date && ( $open_date_display == 'next' || $open_date_display == 'all' ) ) {
				$arr = array(
					'open_date' => $open_date
				);
			}
			return $arr;
		}

		public static function _get_next_date( $args='' ) {
			$defaults = array(
				'open_date'       => '',
				'open_time'       => '',
				'number_of_weeks' => 0,
				'number_days'     => array(),
				'manual_date'     => array(),
			);
			$args = wp_parse_args( $args, $defaults );
			extract($args);

			$next_date = null;
			if ( is_array($manual_date) && count($manual_date) > 0 && $manual_date[0] != '' ) {
				$this_time = noo_timetable_time_now();
				foreach ($manual_date as $date) {
					if ( $this_time < $date ) {
						$next_date = $date;
						break;
					}
				}
			} else {
				if ( $number_of_weeks > 0 && !empty( $number_days ) && !empty($open_date) ) {
					// Reorder Number_days
					asort($number_days);
					$number_days = array_values($number_days);
					$today = getdate();
					$wday = $today['wday'];
					$week_next = $number_days[0];
					foreach ($number_days as $k => $numday) {
						if ( $numday > $wday ) {
							$week_next = $numday;
							break;
						}
					}
					$this_time = ( $open_date > noo_timetable_time_now() ) ? $open_date : noo_timetable_time_now();

					if ($open_time != '')
						$next_date = strtotime( "next ".self::_get_week_day( $week_next )."+ ".date('H', $open_time)." hours + ".date('i', $open_time)." minutes", $this_time );
					else
						$next_date = strtotime( "next ".self::_get_week_day( $week_next ), $this_time );
					// Get max date of this week, max is Sunday (0)
					$indi = ($number_days[0] == 0) ? 0 : end($number_days);
					$max_date = strtotime( "next ".self::_get_week_day($indi) , strtotime("yesterday", $open_date) );
					// Get end date depend on Max date to check this noo_timetable_time_now()
					$end_date = strtotime( "+".($number_of_weeks-1)." week" , $max_date );
					if ( $next_date > $end_date )
						$next_date = null;
				}
			} // check $manual_date
			if ( noo_timetable_time_now() >= $next_date )
				$next_date = null;
			return $next_date;
		}

		public function setup_one_class_schedule( $post_id ) {
			// Check if this post is noo_class
			// Check if publish
			if ( 'noo_class' == get_post_type( $post_id ) ) {
				// Clear old schedule
				wp_clear_scheduled_hook( 'noo-class-update-next-day', array( $post_id ) );
				if ( 'publish' == get_post_status( $post_id ) ) {
					$this->update_next_day( $post_id );
				}
			}
		}

		public function setup_classes() {
			// Once time
			//
			if( get_option( 'has_setup_classes' ) ) {
				return;
			}
			update_option( 'has_setup_classes', 1 );
			// Filter class and setup the first chedule
			// Loop for all classes
			global $wpdb;
			$classes = (array) $wpdb->get_results(
				"SELECT $wpdb->posts.ID, $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_value
                    FROM $wpdb->posts
                    LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE $wpdb->posts.post_status = 'publish'
                    AND $wpdb->posts.post_type = 'noo_class'
                    AND ($wpdb->postmeta.meta_key = '_open_date' OR $wpdb->postmeta.meta_key = '_number_of_weeks' OR $wpdb->postmeta.meta_key = '_number_day' OR $wpdb->postmeta.meta_key = '_open_time') " );

			$newarr = array();
			if ( $classes ){
				foreach ($classes as $k => $v) {
					$newarr[$v->ID][$v->meta_key] = $v->meta_value;
				}
			}
			if ( !empty( $newarr ) ){
				foreach ($newarr as $k => $v) {
					if (!isset($v['_open_date']) || $v['_open_date'] == '')
						continue;
					$class_id = $k;
					$number_of_weeks = $v['_number_of_weeks'] != '' ? $v['_number_of_weeks'] : 1;
					$end_date = strtotime("+" . $number_of_weeks . " week", $v['_open_date']);
					$next_date = noo_timetable_get_post_meta( $class_id, "_next_date", '' );

					if ( $next_date == '' || ( noo_timetable_time_now() >= $next_date && noo_timetable_time_now() <= $end_date ) ){
						$manual_date = array();
						if ( $v['_use_manual_settings'] )
							$manual_date = (array) noo_timetable_json_decode( $v['_manual_date'] );

						$next_date = self::_get_next_date(array(
							'open_date'       => $v['_open_date'],
							'open_time'       => $v['_open_time'],
							'number_of_weeks' => $v['_number_of_weeks'],
							'number_days'     => (array) noo_timetable_json_decode( $v['_number_day'] ),
							'manual_date'     => $manual_date,
						));
						if ( $next_date != '' ){
							update_post_meta( $class_id, '_next_date', $next_date );
							// Create cron
							wp_schedule_single_event( $next_date, 'noo-class-update-next-day', array( $class_id ) );
						}
						else{
							update_post_meta( $class_id, '_next_date', $end_date );
						}
					}
				}
			}
		}

		public function update_next_day( $class_id ) {
			// Calculate and update next day.

			$open_date           = noo_timetable_get_post_meta( $class_id, "_open_date", '' );
			$open_time           = noo_timetable_get_post_meta( $class_id, "_open_time", '' );
			$number_of_weeks     =  (int) noo_timetable_get_post_meta( $class_id, "_number_of_weeks", '1' );
			$number_days         =  noo_timetable_get_post_meta( $class_id, "_number_day", '' );
			$use_manual_settings = noo_timetable_get_post_meta( get_the_ID(), "_use_manual_settings", false );
			$manual_date         = $use_manual_settings ? (array) noo_timetable_json_decode( noo_timetable_get_post_meta( $class_id, "_manual_date", '' ) ) : '';

			$next_date = self::_get_next_date(array(
				'open_date'       => $open_date,
				'open_time'       => $open_time,
				'number_of_weeks' => $number_of_weeks,
				'number_days'     => (array) noo_timetable_json_decode( $number_days ),
				'manual_date'     => $manual_date,
			));
			if ( $next_date != '' ){
				update_post_meta( $class_id, '_next_date', $next_date );
				// Create cron
				wp_schedule_single_event( $next_date, 'noo-class-update-next-day', array( $class_id ) );
			}
		}

		public static function show_information() {

			global $wp_locale;

			$class_address       = noo_timetable_get_post_meta( get_the_ID(), "_address", '' );
			$number_of_weeks     = noo_timetable_get_post_meta( get_the_ID(), "_number_of_weeks", '' );
			$open_date           = noo_timetable_get_post_meta( get_the_ID(), "_open_date", '' );
			$number_days         = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );
			$open_time           = noo_timetable_get_post_meta( get_the_ID(), "_open_time", '' );
			$close_time          = noo_timetable_get_post_meta( get_the_ID(), "_close_time", '' );
			$register_link       = noo_timetable_get_post_meta( get_the_ID(), "_register_link", '' );
			$use_manual_settings = noo_timetable_get_post_meta( get_the_ID(), "_use_manual_settings", false );
			$manual_date         = $use_manual_settings ? (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_manual_date", '' ) ) : '';
			$show_class_meta 	 = NOO_Settings()->get_option('show_class_meta',array('open_date','next_date','address'));

			$class_dates = Noo__Timetable__Class::get_open_date_display(array(
				'open_date'       => $open_date,
				'number_of_weeks' => $number_of_weeks,
				'number_days'     => $number_days,
				'manual_date'     => $manual_date,
			));

			$use_advanced_multi_time = noo_timetable_get_post_meta( get_the_ID(), "_use_advanced_multi_time", false );
			$use_advanced_multi_time = $use_manual_settings ? false : $use_advanced_multi_time;

			$holiday = get_option('holidays_class_schedule');

			$holiday_start = isset($holiday['start']) ? $holiday['start'] : array();
			$holiday_end = isset($holiday['end']) ? $holiday['end'] : array();

			
			$open_date = isset($class_dates['open_date']) ? (string)$class_dates['open_date'] : '';
			$next_date = isset($class_dates['next_date']) ? (string)$class_dates['next_date'] : '';

			do_action('before_show_information');
			?>
            <div class="class-info-sidebar">
				<?php if( in_array('address', $show_class_meta) && !$use_advanced_multi_time && !$use_manual_settings ) : ?>
					<?php if( !empty( $class_address ) ) : ?>
                        <div class="address clearfix"><i class="fa fa-map-marker"></i>&nbsp;<?php echo esc_html__($class_address); ?></div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if( in_array('number_of_week', $show_class_meta) && !empty( $number_of_weeks ) && !$use_manual_settings ) : ?>
                    <div class="number-week clearfix"><i class="fa fa-file-text-o"></i>&nbsp;<?php echo esc_html__('Number of Week','noo-timetable');?><span><?php echo esc_html($number_of_weeks); ?></span></div>
				<?php endif; ?>
				<?php if( in_array('open_date', $show_class_meta) &&  !empty( $open_date ) && !in_array($open_date, $holiday_start) && !in_array($open_date, $holiday_end)) : ?>
                    <div class="open-date clearfix"><i class="fa fa-calendar"></i>&nbsp;<?php echo esc_html__('Open:', 'noo-timetable'); ?> <?php echo esc_html(date_i18n(get_option('date_format'),$open_date)); ?></div>
				<?php endif; ?>
				<?php if( in_array('next_date', $show_class_meta) && !empty( $next_date ) && !in_array($next_date, $holiday_start) && !in_array($next_date, $holiday_end)) : ?>
                    <div class="next-date clearfix"><i class="fa fa-calendar"></i>&nbsp;<?php echo esc_html__('Next:', 'noo-timetable'); ?> <?php echo esc_html(date_i18n(get_option('date_format'),$class_dates['next_date'])); ?></div>
				<?php endif; ?>
				<?php if( in_array('day_of_week', $show_class_meta) &&  !empty( $number_days ) && !$use_manual_settings ) :
					$start_of_week = get_option('start_of_week');
					$ndays1 = array();
					$ndays2 = array();
					asort( $number_days );
					foreach ($number_days as $k => $nday) {
						if ( $nday >= $start_of_week ) {
							$ndays1[] = $nday;
						} else {
							$ndays2[] = $nday;
						}
					}
					$number_days = array_merge($ndays1, $ndays2);
					?>
                    <div class="clearfix tag-days">
                        <i class="fa fa-check"></i>&nbsp;<?php echo esc_html__('Days','noo-timetable');?>
                        <div class="wrap-days">
							<?php foreach ($number_days as $number_day) : ?>
								<?php if ( is_numeric($number_day) ) : ?>
                                    <span><?php echo esc_html__($wp_locale->get_weekday_abbrev($wp_locale->get_weekday($number_day))) ?></span>
								<?php endif; ?>
							<?php endforeach; ?>
                        </div>
                    </div>
				<?php endif; ?>
				<?php if(in_array('address', $show_class_meta)):?>
					<?php if( !$use_advanced_multi_time && !$use_manual_settings ) : ?>
						<?php if( !empty( $open_time ) || !empty( $close_time ) ) :?>
	                        <div class="clock clearfix"><i class="fa fa-clock-o"></i>&nbsp;<?php echo date_i18n(get_option('time_format'), $open_time).' - '. date_i18n(get_option('time_format'), $close_time); ?></div>
						<?php endif; ?>
					<?php else : ?>
	                    <div class="clock clearfix"><a href="#time-table"><i class="fa fa-clock-o"></i>&nbsp;<?php echo esc_html__('Multiple time', 'noo-timetable'); ?> <i class="icon ion-android-arrow-forward"></i></a></div>
					<?php endif; ?>
				<?php endif;?>
            </div>
			<?php if( !empty( $register_link ) ) : ?>
                <a href="<?php echo esc_url( $register_link );?>" class="button register_button"><?php echo esc_html__('Register Now', 'noo-timetable');?></a>
			<?php endif; ?>

			<?php
			do_action('after_show_information');
		}

		public static function show_trainers() {

			$trainer_ids = noo_timetable_get_post_meta(get_the_ID(), '_trainer');

			if ( ! $trainer_ids ) {
				return;
			}

			?>

            <div class="trainer-tag-wrap">
				<?php
				$trainer_ids = (array) noo_timetable_json_decode($trainer_ids);

				foreach ( $trainer_ids as $trainer_id ) : ?>
                    <div class="trainer-bio">
                        <a class="trainer-avatar" href="<?php echo get_permalink($trainer_id) ; ?>">
							<?php echo get_the_post_thumbnail($trainer_id, 'noo-thumbnail-trainer') ?>
                        </a>
                        <div class="trainer-info">
                            <h4>
                                <a title="<?php printf( esc_html__( 'Post by %s','noo-timetable'), get_the_title($trainer_id) ); ?>" href="<?php echo get_permalink($trainer_id) ; ?>" rel="author">
									<?php echo get_the_title($trainer_id) ?>
                                </a>
                            </h4>
                            <div class="trainer-category">
								<?php
								echo get_the_term_list($trainer_id, 'class_category',' ',', ');
								?>
                            </div>
							<?php
							$facebook       =   noo_timetable_get_post_meta( $trainer_id, "_noo_trainer_facebook", '' );
							$google         =   noo_timetable_get_post_meta( $trainer_id, "_noo_trainer_google", '' );
							$twitter        =   noo_timetable_get_post_meta( $trainer_id, "_noo_trainer_twitter", '' );
							$pinterest      =   noo_timetable_get_post_meta( $trainer_id, "_noo_trainer_pinterest", '' );
							?>
							<?php if(!empty($facebook) || !empty($twitter) || !empty($google) || !empty($linkedin) || !empty($pinterest)):?>
                                <div class="trainer-social all-social-share">
									<?php echo ( !empty($facebook) ? '<a href="' . $facebook . '" class="fa fa-facebook"></a>' : '' ); ?>
									<?php echo ( !empty($google) ? '<a href="' . $google . '" class="fa fa-google-plus"></a>' : '' ); ?>
									<?php echo ( !empty($twitter) ? '<a href="' . $twitter . '" class="fa fa-twitter"></a>' : '' ); ?>
									<?php echo ( !empty($pinterest) ? '<a href="' . $pinterest . '" class="fa fa-pinterest"></a>' : '' ); ?>
                                </div>
							<?php endif; ?>
                            <a class="button view-profile" title="<?php printf( esc_html__( 'Post by %s','noo-timetable'), get_the_title($trainer_id) ); ?>" href="<?php echo get_permalink($trainer_id); ?>" rel="author">
								<?php echo esc_html__('View Profile', 'noo-timetable'); ?>
                            </a>
                        </div>
                    </div>
				<?php endforeach; ?>
            </div>

			<?php
		}

		public static function load_sidebar_info() {

			?>

			<?php if ( is_singular('noo_class' ) ) :  ?>

                <!-- Class Information -->
                <section id="class-info-1" class="widget widget_class_info">
                    <h2 class="widget-title"><?php esc_html_e('Class Information', 'noo-timetable'); ?></h2>
					<?php self::show_information(); ?>
                </section>

                <!-- Trainers Information -->
                <section id="class-trainer-1" class="widget widget_class_trainer">
                    <h2 class="widget-title"><?php esc_html_e('Class Trainer', 'noo-timetable'); ?></h2>
					<?php self::show_trainers(); ?>
					<?php do_action('after_class_trainer');?>
                </section>

			<?php endif; ?>


			<?php
		}

		public static function timetable_with_manual( $args ) {
			extract($args);
			?>
            <div id="time-table" class="timetable_week">
                <h4>
                    <span>
                        <?php
                        echo apply_filters( 'widget_title', esc_html__('Class Timetable', 'noo-timetable'));
                        ?>
                    </span>
                </h4>

                <table>
                    <tr>
                        <th>&nbsp;</th>
						<?php if ( $show_meta_time ) : ?>
                            <th><?php echo esc_html__('Open Time', 'noo-timetable'); ?></th>
                            <th><?php echo esc_html__('Close Time', 'noo-timetable'); ?></th>
						<?php endif; ?>
						<?php if ( $show_meta_trainer ) : ?>
                            <th><?php echo esc_html__('Trainer', 'noo-timetable'); ?></th>
						<?php endif; ?>
						<?php if ( $show_meta_address ) : ?>
                            <th><?php echo esc_html__('Address', 'noo-timetable'); ?></th>
						<?php endif; ?>
                    </tr>
					<?php
					$time_format = get_option('time_format');
					$trainer = (array) noo_timetable_json_decode($trainer_ids);

					$open_date  = noo_timetable_get_post_meta( get_the_ID(), "_open_date", '' );
					$open_time  = noo_timetable_get_post_meta( get_the_ID(), "_open_time", '1470301200' );
					$close_time = noo_timetable_get_post_meta( get_the_ID(), "_close_time", '1470308400' );
					$txt_address = noo_timetable_get_post_meta( get_the_ID(), "_address", '' );
					$txt_trainer = '';
					if ( $trainer ) {
						$temp_name = array();
						foreach ($trainer as $tr_id) {
							$temp_name[] = get_the_title($tr_id);
						}
						$txt_trainer = implode(', ', $temp_name);
					}

					$meta_manual_date        = noo_timetable_get_post_meta(  get_the_ID(), "_manual_date", '' );
					$meta_manual_open_time   = noo_timetable_get_post_meta(  get_the_ID(), "_manual_open_time", '' );
					$meta_manual_closed_time = noo_timetable_get_post_meta(  get_the_ID(), "_manual_closed_time", '' );
					$meta_manual_trainer     = noo_timetable_get_post_meta(  get_the_ID(), "_manual_trainer", '' );
					$meta_manual_address     = noo_timetable_get_post_meta(  get_the_ID(), "_manual_address", '' );

					$meta_manual_date        = (array) noo_timetable_json_decode($meta_manual_date);
					$meta_manual_open_time   = (array) noo_timetable_json_decode($meta_manual_open_time);
					$meta_manual_closed_time = (array) noo_timetable_json_decode($meta_manual_closed_time);
					$meta_manual_trainer     = (array) noo_timetable_json_decode($meta_manual_trainer);
					$meta_manual_address     = (array) noo_timetable_json_decode($meta_manual_address);

					if ( count($meta_manual_date) == 1 && $meta_manual_date[0] == '' ) {
						$meta_manual_date = array();
					} elseif( $meta_manual_date[0] == '' ){
						$meta_manual_date[] = $open_date;
					}
					if( $meta_manual_open_time && $meta_manual_open_time[0] == '' )
						$meta_manual_open_time[] = $open_time;
					if( $meta_manual_closed_time && $meta_manual_closed_time[0] == '' )
						$meta_manual_closed_time[] = $close_time;

					if ( $meta_manual_date ) {
						array_unshift($meta_manual_date, $open_date);
						array_unshift($meta_manual_open_time, $open_time);
						array_unshift($meta_manual_closed_time, $close_time);
						array_unshift($meta_manual_trainer, $txt_trainer);
						array_unshift($meta_manual_address, $txt_address);
						foreach ($meta_manual_date as $k => $mm_date) {
							$day_class = 'day-' . ( $k % 2 ? 'odd' : 'even' );

							$mm_date = ($mm_date && $mm_date != '') ? $mm_date : $open_date;

							if ( $meta_manual_open_time[$k] == '' && $meta_manual_closed_time[$k] == '' ) {
								$meta_manual_open_time[$k] = $open_time;
								$meta_manual_closed_time[$k] = $close_time;
							}

							if ( $meta_manual_open_time[$k] && $meta_manual_closed_time[$k] ) {

								$day_text = date_i18n(get_option('date_format'),$mm_date);
								$time_open_text = date_i18n($time_format,$meta_manual_open_time[$k]);
								$time_closed_text = date_i18n($time_format,$meta_manual_closed_time[$k]);

								?>
                                <tr class="day_wrap <?php echo $day_class; ?>">
                                    <td><span class="day"><?php echo $day_text; ?></span></td>
									<?php if ( $show_meta_time ) : ?>
                                        <td><?php echo esc_attr( $time_open_text ); ?></td>
                                        <td><?php echo esc_attr( $time_closed_text ); ?></td>
									<?php endif; ?>
									<?php if ( $show_meta_trainer ) : ?>
                                        <td>
											<?php
											$echo_trainers = $txt_trainer;
											if( isset($meta_manual_trainer[$k]) && $meta_manual_trainer[$k] != '' && $meta_manual_trainer[$k] != 0 ){
												$trainer_exp = explode(',', $meta_manual_trainer[$k]);
												$temp_name = array();
												foreach ($trainer_exp as $tr_id) {
													if ( is_numeric( $tr_id ) && $tr_id > 0 ) {
														$temp_name[] = get_the_title($tr_id);
													}
												}
												if ( count($temp_name) > 0 ) {
													$echo_trainers = implode(', ', $temp_name);
												}
											}
											echo esc_attr($echo_trainers);
											?>
                                        </td>
									<?php endif; ?>
									<?php if ( $show_meta_address ) : ?>
                                        <td>
											<?php
											if( isset($meta_manual_address[$k]) && $meta_manual_address[$k] != '' )
												echo $meta_manual_address[$k];
											else
												echo $txt_address;
											?>
                                        </td>
									<?php endif; ?>
                                </tr>
								<?php
							}
						}
					}
					?>
                </table>

                <div class="res-sche-content">
					<?php
					if ( $meta_manual_date ) {
						foreach ($meta_manual_date as $k => $mm_date) {
							$day_class = 'day-' . ( $k % 2 ? 'odd' : 'even' );

							$mm_date = ($mm_date && $mm_date != '') ? $mm_date : $open_date;

							if ( $meta_manual_open_time[$k] == '' && $meta_manual_closed_time[$k] == '' ) {
								$meta_manual_open_time[$k] = $open_time;
								$meta_manual_closed_time[$k] = $close_time;
							}

							if ( $meta_manual_open_time[$k] && $meta_manual_closed_time[$k] ) {

								$day_text = date_i18n(get_option('date_format'),$mm_date);
								$time_open_text = date_i18n($time_format,$meta_manual_open_time[$k]);
								$time_closed_text = date_i18n($time_format,$meta_manual_closed_time[$k]);

								?>
                                <div class="item-weekday"><?php echo $day_text; ?></div>
                                <div class="item-day">
                                    <span class="time"><?php echo esc_attr( $time_open_text ); ?> - </span>
                                    <span class="time"><?php echo esc_attr( $time_closed_text ); ?></span>
                                    <i>- <?php echo esc_html__('with ', 'noo-timetable'); ?> <?php
										$echo_trainers = $txt_trainer;
										if( isset($meta_manual_trainer[$k]) && $meta_manual_trainer[$k] != '' && $meta_manual_trainer[$k] != 0 ){
											$trainer_exp = explode(',', $meta_manual_trainer[$k]);
											$temp_name = array();
											foreach ($trainer_exp as $tr_id) {
												if ( is_numeric( $tr_id ) && $tr_id > 0 ) {
													$temp_name[] = get_the_title($tr_id);
												}
											}
											if ( count($temp_name) > 0 ) {
												$echo_trainers = implode(', ', $temp_name);
											}
										}
										echo esc_attr($echo_trainers);
										?></i>
                                    <i class="address">- <?php echo esc_html__('at', 'noo-timetable'); ?> <?php
										if( isset($meta_manual_address[$k]) && $meta_manual_address[$k] != '' )
											echo $meta_manual_address[$k];
										else
											echo $txt_address;
										?></i>
                                </div>
								<?php
							}
						}
					}
					?>
                </div> <!-- .res-sche-content -->
            </div> <!-- .timetable_week -->
			<?php
		}

		public static function timetable_with_advanced( $args ) {
			extract($args);
			if ( ! $number_days || $number_days[0] == '' )
				return;
			?>
            <div id="time-table" class="timetable_week">
                <h4>
                    <span>
                    <?php
                    echo apply_filters( 'widget_title', esc_html__('Class Timetable', 'noo-timetable'));
                    ?>
                    </span>
                </h4>

                <table>
                    <tr>
                        <th>&nbsp;</th>
						<?php if ( $show_meta_time ) : ?>
                            <th><?php echo esc_html__('Open Time', 'noo-timetable'); ?></th>
                            <th><?php echo esc_html__('Close Time', 'noo-timetable'); ?></th>
						<?php endif; ?>
						<?php if ( $show_meta_trainer ) : ?>
                            <th><?php echo esc_html__('Trainer', 'noo-timetable'); ?></th>
						<?php endif; ?>
						<?php if ( $show_meta_address ) : ?>
                            <th><?php echo esc_html__('Address', 'noo-timetable'); ?></th>
						<?php endif; ?>
                    </tr>
					<?php
					$time_format = get_option('time_format');
					$trainer = (array) noo_timetable_json_decode($trainer_ids);
					foreach ($number_days as $index => $day) :
						$day_class = 'day-' . ( $index % 2 ? 'odd' : 'even' );

						$new_open_id    = '_open_time_' . $day;

						$new_closed_id  = '_closed_time_' . $day;
						$new_trainer_id = '_trainer_' . $day;
						$new_address_id = '_address_' . $day;

						$meta_open    = noo_timetable_get_post_meta( get_the_ID(), $new_open_id, '' );
						$meta_closed  = noo_timetable_get_post_meta( get_the_ID(), $new_closed_id, '' );
						$meta_trainer = noo_timetable_get_post_meta( get_the_ID(), $new_trainer_id, '' );
						$meta_address = noo_timetable_get_post_meta( get_the_ID(), $new_address_id, '' );

						$meta_open    = (array) noo_timetable_json_decode($meta_open);
						$meta_closed  = (array) noo_timetable_json_decode($meta_closed);
						$meta_trainer = (array) noo_timetable_json_decode($meta_trainer);
						$meta_address = (array) noo_timetable_json_decode($meta_address);

						if ( ($meta_open && $meta_open[0] == '') || !$meta_open ) {
							$meta_open[0] = noo_timetable_get_post_meta( get_the_ID(), "_open_time", '' );
						}
						if ( ($meta_closed && $meta_closed[0] == '') || !$meta_closed ) {
							$meta_closed[0] = noo_timetable_get_post_meta( get_the_ID(), "_close_time", '' );
						}

						if ( is_array($meta_open) && count($meta_open) > 0 ) :
							foreach ($meta_open as $k => $mopen) :
								$time_open_text = is_numeric( $mopen ) ? date( $time_format, $mopen ) : $mopen;
								$time_open = is_numeric( $mopen ) ? $mopen : strtotime( $mopen );
								$time_closed_text = '';
								$time_closed = '';
								if ( isset($meta_closed[$k]) ){
									$time_closed_text = is_numeric( $meta_closed[$k] ) ? date( $time_format, $meta_closed[$k] ) : $meta_closed[$k];
									$time_closed = is_numeric( $meta_closed[$k] ) ? $meta_closed[$k] : strtotime( $meta_closed[$k] );
								}
								?>
                                <tr class="day_wrap <?php echo $day_class; ?>">
                                    <td><span class="day"><?php echo self::_get_week_day($day, true); ?></span></td>
									<?php if ( $show_meta_time ) : ?>
                                        <td><?php echo esc_attr( $time_open_text ); ?></td>
                                        <td><?php echo esc_attr( $time_closed_text ); ?></td>
									<?php endif; ?>
									<?php if ( $show_meta_trainer ) : ?>
                                        <td>
											<?php
											$echo_trainers = '';
											if ( is_array($trainer) && !empty($trainer['0']) ) {
												$temp_name = array();
												foreach ($trainer as $tr_id) {
													$temp_name[] = get_the_title($tr_id);
												}
												$echo_trainers = implode(', ', $temp_name);
											}else{
												$echo_trainers = esc_html__('not available','noo-timetable');
											}
											if( isset($meta_trainer[$k]) && $meta_trainer[$k] != '' && $meta_trainer[$k] != 0 ){
												$trainer_exp = explode(',', $meta_trainer[$k]);
												$temp_name = array();
												foreach ($trainer_exp as $tr_id) {
													if ( is_numeric( $tr_id ) && $tr_id > 0 ) {
														$temp_name[] = get_the_title($tr_id);
													}
												}
												if ( count($temp_name) > 0 ) {
													$echo_trainers = implode(', ', $temp_name);
												}
											}
											echo esc_attr($echo_trainers);
											?>
                                        </td>
									<?php endif; ?>
									<?php if ( $show_meta_address ) : ?>
                                        <td><?php echo (isset($meta_address[$k]) && $meta_address[$k] != '' ) ? esc_attr($meta_address[$k]) : noo_timetable_get_post_meta( get_the_ID(), "_address", '' ); ?></td>
									<?php endif; ?>
                                </tr>

							<?php
							endforeach;
						endif;
					endforeach;
					?>
                </table>

                <div class="res-sche-content">
					<?php
					$time_format = get_option('time_format');
					$trainer = (array) noo_timetable_json_decode($trainer_ids);
					$number_days = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );
					foreach ($number_days as $index => $day) :

						$new_open_id    = '_open_time_' . $day;

						$new_closed_id  = '_closed_time_' . $day;
						$new_trainer_id = '_trainer_' . $day;
						$new_address_id = '_address_' . $day;

						$meta_open    = noo_timetable_get_post_meta( get_the_ID(), $new_open_id, '' );
						$meta_closed  = noo_timetable_get_post_meta( get_the_ID(), $new_closed_id, '' );
						$meta_trainer = noo_timetable_get_post_meta( get_the_ID(), $new_trainer_id, '' );
						$meta_address = noo_timetable_get_post_meta( get_the_ID(), $new_address_id, '' );

						$meta_open    = (array) noo_timetable_json_decode($meta_open);
						$meta_closed  = (array) noo_timetable_json_decode($meta_closed);
						$meta_trainer = (array) noo_timetable_json_decode($meta_trainer);
						$meta_address = (array) noo_timetable_json_decode($meta_address);

						if ( ($meta_open && $meta_open[0] == '') || !$meta_open ) {
							$meta_open[0] = noo_timetable_get_post_meta( get_the_ID(), "_open_time", '' );
						}
						if ( ($meta_closed && $meta_closed[0] == '') || !$meta_closed ) {
							$meta_closed[0] = noo_timetable_get_post_meta( get_the_ID(), "_close_time", '' );
						}

						if ( is_array($meta_open) && count($meta_open) > 0 ) :
							foreach ($meta_open as $k => $mopen) :
								$time_open_text = is_numeric( $mopen ) ? date( $time_format, $mopen ) : $mopen;
								$time_open = is_numeric( $mopen ) ? $mopen : strtotime( $mopen );

								$time_closed_text = '';
								$time_closed = '';
								if ( isset($meta_closed[$k]) ){
									$time_closed_text = is_numeric( $meta_closed[$k] ) ? date( $time_format, $meta_closed[$k] ) : $meta_closed[$k];
									$time_closed = is_numeric( $meta_closed[$k] ) ? $meta_closed[$k] : strtotime( $meta_closed[$k] );
								}
								?>
								<?php if ( $k == 0 ) : ?>
                                <div class="item-weekday"><?php echo self::_get_week_day($day, true); ?></div>
							<?php endif; ?>
                                <div class="item-day">
                                    <span class="time"><?php echo esc_attr( $time_open_text ); ?> - </span>
                                    <span class="time"><?php echo esc_attr( $time_closed_text ); ?></span>
                                    <i>- <?php echo esc_html__(' ', 'noo-timetable'); ?> <?php echo (isset($meta_trainer[$k]) && is_numeric($meta_trainer[$k]) ) ? get_the_title($meta_trainer[$k]) : get_the_title($trainer[0]) ?></i>
                                    <i class="address">- <?php echo esc_html__('at', 'noo-timetable'); ?> <?php echo (isset($meta_address[$k]) && $meta_address[$k] != '' ) ? esc_attr($meta_address[$k]) : noo_timetable_get_post_meta( get_the_ID(), "_address", '' ); ?></i>
                                </div>
							<?php
							endforeach;
						endif;
					endforeach;
					?>
                </div> <!-- .res-sche-content -->
            </div> <!-- .timetable_week -->
			<?php
		}

		public static function get_timetable() {

			$show_meta_time    = apply_filters( 'noo_timetable_classes_meta_time', true );
			$show_meta_trainer = apply_filters( 'noo_timetable_classes_meta_trainer', true );
			$show_meta_address = apply_filters( 'noo_timetable_classes_meta_address', true );
			$trainer_ids = noo_timetable_get_post_meta(get_the_ID(),'_trainer');
			$number_days = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );

			$use_manual_settings     = noo_timetable_get_post_meta( get_the_ID(), "_use_manual_settings", false );
			$use_advanced_multi_time = noo_timetable_get_post_meta( get_the_ID(), "_use_advanced_multi_time", false );
			$use_advanced_multi_time = $use_manual_settings ? false : $use_advanced_multi_time;

			$args = array(
				'show_meta_time'    => $show_meta_time,
				'show_meta_trainer' => $show_meta_trainer,
				'show_meta_address' => $show_meta_address,
				'trainer_ids'       => $trainer_ids,
				'number_days'       => $number_days,
			);

			if ( $use_advanced_multi_time )
				self::timetable_with_advanced( $args );

			if ( $use_manual_settings )
				self::timetable_with_manual( $args );
		}

	}
	new Noo__Timetable__Class();

}
