<?php
/**
 * Event Meta Boxes
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

if( ! class_exists('Noo__Timetable__Event_Meta_Boxes') ) {

    class Noo__Timetable__Event_Meta_Boxes {

    	public function __construct() {

			if ( is_admin() ) {
    			// Metabox
				add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ), 30 );

				// Columns
                add_filter( 'manage_noo_event_posts_columns', array( $this, 'manage_edit_columns' ) );
				add_action( 'manage_noo_event_posts_custom_column',  array($this, 'manage_custom_column'), 10, 2 );
                add_filter( 'manage_edit-noo_event_sortable_columns', array( $this, 'sortable_columns' ) );
                // Filter request
                add_filter( 'request', array( $this, 'request_query' ) );
    		}

    	}

    	public function add_meta_boxes() {

            $helper = new Noo__Timetable_Meta_Boxes_Helper( '_noo_event', array(
                'page' => 'event_organizers'
            ));

            $meta_box = array(
                'id' => '_noo_event_box_organizers',
                'title' => esc_html__( 'Information', 'noo-timetable' ),
                'fields' => array(

                    array(
                        'id' => '_noo_event_author',
                        'label' => esc_html__( 'Author', 'noo-timetable' ),
                        'type' => 'text'
                    ),
                    array(
                        'id' => '_noo_event_avatar',
                        'label' => esc_html__( 'Avatar', 'noo-timetable' ),
                        'type' => 'image'
                    ),

                    array(
                        'id'    => '_noo_event_phone',
                        'label' => esc_html__( 'Phone', 'noo-timetable' ),
                        'type'  => 'text'
                    ),
                    array(
                        'id'    => '_noo_event_website',
                        'label' => esc_html__( 'Website', 'noo-timetable' ),
                        'type'  => 'text'
                    ),
                    array(
                        'id'    => '_noo_event_email',
                        'label' => esc_html__( 'Email', 'noo-timetable' ),
                        'type'  => 'text'
                    ),
                    array(
                        'id'    => '_noo_event_position',
                        'label' => esc_html__( 'Position', 'noo-timetable' ),
                        'type'  => 'text'
                    ),
                )
            );

            /**
             * Add box Event ORGANIZERS to page
             */
            $helper->add_meta_box($meta_box);

            $helper = new Noo__Timetable_Meta_Boxes_Helper( '_noo_event', array(
                'page' => 'noo_event'
            ));

            $meta_box = array(
                'id' => '_noo_event_event_organizers',
                'title' => esc_html__( 'Organizers', 'noo-timetable' ),
                'fields' => array(

                    array(
                        'id'      => '_noo_event_organizers',
                        'label'   => esc_html__( 'Organizers', 'noo-timetable' ),
                        'type'    => 'select_multiple_chosen',
                        'options' => Noo__Timetable__Event::get_all_organizers(),
                         'callback' => array( &$this, 'meta_box_select_multiple' )
                    )
                )
            );


            $helper->add_meta_box($meta_box);

            $meta_box = array(
                'id'      => '_noo_event_event_bg_color',
                'title'   => esc_html__( 'Background Color', 'noo-timetable' ),
                'context' => 'side',
                'fields'  => array(
                    array(
                        'id'       => '_noo_event_bg_color',
                        'label'    => '',
                        'type'     => 'colorpicker',
                        'default'  => '#FFF',
                        'callback' => array( &$this, 'meta_box_color_picker' )
                    )
                )
            );


            $helper->add_meta_box($meta_box);

            /**
             * Creating box: Event Date & Time
             * @var array
             */
            $meta_box = array(
                'id' => '_noo_event_event_date_time',
                'title' => esc_html__( 'Date Time Setting', 'noo-timetable' ),
                'fields' => array(
                    array(
                        'id'       => '_noo_event_start_date',
                        'label'    => esc_html__( 'Start Date', 'noo-timetable' ),
                        'type'     => 'datepicker',
                        'callback' => array( &$this, 'meta_box_datepicker' )
                    ),
                    array(
                        'id'       => '_noo_event_start_time',
                        'label'    => esc_html__( 'Start Time', 'noo-timetable' ),
                        'type'     => 'timepicker',
                        'callback' => array( &$this, 'meta_box_timepicker' )
                    ),
                    array(
                        'id'       => '_noo_event_end_date',
                        'label'    => esc_html__( 'End Date', 'noo-timetable' ),
                        'type'     => 'datepicker',
                        'callback' => array( &$this, 'meta_box_datepicker' )
                    ),
                    array(
                        'id'       => '_noo_event_end_time',
                        'label'    => esc_html__( 'End Time', 'noo-timetable' ),
                        'type'     => 'timepicker',
                        'callback' => array( &$this, 'meta_box_timepicker' )
                    ),
                    array(
                        'id'       => '_recurrence',
                        'label'    => esc_html__( 'Recurrence Rules', 'noo-timetable' ),
                        'type'     => 'text',
                        'std'      => '',
                        'callback' => array( &$this, 'meta_box_recurrence' )
                    )
                )
            );


            /**
             * Add box Event Date & Time to page
             */
            $helper->add_meta_box($meta_box);


            /**
             * Creating box: Event LOCATION
             * @var array
             */

            $meta_box = array(
                'id' => '_noo_event_event_location',
                'title' => esc_html__( 'Location', 'noo-timetable' ),
                'fields' => array(
                    array(
                        'id'       => '_noo_event_gmap',
                        'type'     => 'gmap',
                        'callback' => array( &$this, 'meta_box_google_map' )
                    ),
                    array(
                        'id'    => '_noo_event_address',
                        'label' => esc_html__( 'Address', 'noo-timetable' ),
                        'type'  => 'text'
                    ),
                    array(
                        'id'      => '_noo_event_gmap_latitude',
                        'label'   => __( 'Latitude', 'noo-timetable' ),
                        'std'     => '40.71421714027808',
                        'type'    => 'text',
                    ),
                    array(
                        'id'      => '_noo_event_gmap_longitude',
                        'label'   => __( 'Longitude', 'noo-timetable' ),
                        'std'     => '-74.00538682937622',
                        'type'    => 'text',
                    )
                )
            );
            $helper->add_meta_box($meta_box);

            $meta_box = array(
                'id' => '_noo_event_event_register_link',
                'title' => esc_html__( 'Register Link', 'noo-timetable' ),
                'fields' => array(
                    array(
                        'id'       => '_noo_event_register_link',
                        'label'    => esc_html__( 'Register Link', 'noo-timetable' ),
                        'type'     => 'text',
                        'desc'     => esc_html__( 'Use this if you want to link the registration somewhere.', 'noo-timetable' ),
                        'std'      => ''
                    )
                )
            );
            $helper->add_meta_box($meta_box);
    	}

        public function meta_box_recurrence($post, $id, $type, $meta, $std, $field) {
            wp_enqueue_script( 'datetimepicker' );
            wp_enqueue_style( 'datetimepicker' );
            $json = '';
            if ( $meta != '' )
                $json = json_encode(Noo__Timetable__Event::get_param_recurrence($meta, true));

            // Output HTML
            echo '<div class="noo-recurrence-setup">';
            echo '<input type="hidden" style="width: 150%;" class="noo_rrule_string" name="noo_meta_boxes['.$id.']" value="'.esc_attr( $meta ).'" />';
            echo '<input type="hidden" class="noo_rrule_json" value=\''.$json.'\' />';
            echo '<div class="noo-recurrence-flex-1">';

                echo '<select class="noo_rrule_freq" data-notice-disable-start="'.esc_html__('You can not change this field while configuring Recurrence Rule is Custom', 'noo-timetable').'">';
                    echo '<option value="none">'.esc_html__('None', 'noo-timetable').'</option>';
                    echo '<option value="daily">'.esc_html__('Every Day', 'noo-timetable').'</option>';
                    echo '<option value="weekly">'.esc_html__('Every Week', 'noo-timetable').'</option>';
                    echo '<option value="monthly">'.esc_html__('Every Month', 'noo-timetable').'</option>';
                    echo '<option value="yearly">'.esc_html__('Every Year', 'noo-timetable').'</option>';
                    echo '<option disabled="disabled">--------</option>';
                    echo '<option value="custom">'.esc_html__('Custom', 'noo-timetable').'</option>';
                echo '</select>';

                echo '<div class="noo_rrule_use_end" style="display: none;">';
                    esc_html_e('End repeat', 'noo-timetable');

                    echo '<select class="noo_rrule_end">';
                        echo '<option value="count">'.esc_html__('After', 'noo-timetable').'</option>';
                        echo '<option value="until">'.esc_html__('On Date', 'noo-timetable').'</option>';
                    echo '</select>';
                    echo '<input type="text" class="input_num noo_rrule_after" value="1" /><span class="noo_rrule_after">'.esc_html__('Event(s)', 'noo-timetable').'</span>';
                    echo '<input type="text" class="input_text noo_rrule_on_date" style="display: none" />';
                echo '</div>';

            echo '</div><!--/.noo-recurrence-flex-1 -->';

            echo '<div class="noo_rrule_freq_use_custom" style="display: none;">';
                echo '<div class="noo-recurrence-flex-2">';

                    esc_html_e('Frequency: ', 'noo-timetable');
                    echo '<select class="noo_rrule_freq_custom">';
                        echo '<option value="daily" data-unit="'.esc_html__('day(s)', 'noo-timetable').'">'.esc_html__('Daily', 'noo-timetable').'</option>';
                        echo '<option value="weekly" data-unit="'.esc_html__('week(s) on:', 'noo-timetable').'">'.esc_html__('Weekly', 'noo-timetable').'</option>';
                        echo '<option value="monthly" data-unit="'.esc_html__('month(s)', 'noo-timetable').'">'.esc_html__('Monthly', 'noo-timetable').'</option>';
                        echo '<option value="yearly" data-unit="'.esc_html__('year(s) in:', 'noo-timetable').'">'.esc_html__('Yearly', 'noo-timetable').'</option>';
                    echo '</select>';

                    esc_html_e('Every', 'noo-timetable');
                    echo '<input type="text" class="input_num noo_rrule_interval" value="1" />';
                    echo '<span class="noo_rrule_interval_text"></span>';

                echo '</div><!--/.noo-recurrence-flex-2 -->';

                echo '<div class="noo_rrule_week_choice" style="display: none;">';
                    echo '<div class="noo_rrule_byday">';
                        echo '<label><input type="checkbox" value="mo" /> '.esc_html__('Monday', 'noo-timetable').'</label>';
                        echo '<label><input type="checkbox" value="tu" /> '.esc_html__('Tuesday', 'noo-timetable').'</label>';
                        echo '<label><input type="checkbox" value="we" /> '.esc_html__('Wednesday', 'noo-timetable').'</label>';
                        echo '<label><input type="checkbox" value="th" /> '.esc_html__('Thursday', 'noo-timetable').'</label>';
                        echo '<label><input type="checkbox" value="fr" /> '.esc_html__('Friday', 'noo-timetable').'</label>';
                        echo '<label><input type="checkbox" value="sa" /> '.esc_html__('Saturday', 'noo-timetable').'</label>';
                        echo '<label><input type="checkbox" value="su" /> '.esc_html__('Sunday', 'noo-timetable').'</label>';
                    echo '</div>';
                echo '</div>';

                echo '<div class="noo_rrule_month_choice" style="display: none;">';
                    echo '<label><input type="radio" class="month_each" name="r1" value="each" checked />'.esc_html__('Each', 'noo-timetable').'</label>';

                    echo '<div class="noo_rrule_bymonthday">';
                        for ($i=1; $i <= 31 ; $i++) {
                            echo '<label><input type="checkbox" value="'.$i.'" /> '.$i.'</label>';
                        }
                    echo '</div>';

                    echo '<label><input type="radio" class="month_on_the" name="r1" value="onthe" />'.esc_html__('On the', 'noo-timetable').'</label>';

                echo '</div>';

                echo '<div class="noo_rrule_year_choice" style="display: none;">';

                    echo '<div class="noo_rrule_bymonth">';
                        for ($i=1; $i <= 12 ; $i++) {
                            echo '<label><input type="checkbox" value="'.$i.'" /> '.$i.'</label>';
                        }
                    echo '</div>';

                    echo '<label><input class="year_on_the" type="checkbox" value="1" />'.esc_html__('On the', 'noo-timetable').'</label>';

                echo '</div>';

                echo '<div class="noo_rrule_onthe_choice" style="display: none;">';
                    echo '<select class="noo_rrule_bysetpos" disabled="disabled">';
                        echo '<option value="1">'.esc_html__('First', 'noo-timetable').'</option>';
                        echo '<option value="2">'.esc_html__('Second', 'noo-timetable').'</option>';
                        echo '<option value="3">'.esc_html__('Third', 'noo-timetable').'</option>';
                        echo '<option value="4">'.esc_html__('Fourth', 'noo-timetable').'</option>';
                        echo '<option value="5">'.esc_html__('Fifth', 'noo-timetable').'</option>';
                        echo '<option disabled="disabled">--------</option>';
                        echo '<option value="-1">'.esc_html__('Last', 'noo-timetable').'</option>';
                    echo '</select>';
                    echo '<select class="noo_rrule_bydaytype" disabled="disabled">';
                        echo '<option value="su">'.esc_html__('Sunday', 'noo-timetable').'</option>';
                        echo '<option value="mo">'.esc_html__('Monday', 'noo-timetable').'</option>';
                        echo '<option value="tu">'.esc_html__('Tuesday', 'noo-timetable').'</option>';
                        echo '<option value="we">'.esc_html__('Wednesday', 'noo-timetable').'</option>';
                        echo '<option value="th">'.esc_html__('Thursday', 'noo-timetable').'</option>';
                        echo '<option value="fr">'.esc_html__('Friday', 'noo-timetable').'</option>';
                        echo '<option value="sa">'.esc_html__('Saturday', 'noo-timetable').'</option>';
                        echo '<option disabled="disabled">--------</option>';
                        echo '<option value="day">'.esc_html__('Day', 'noo-timetable').'</option>';
                        echo '<option value="weekday">'.esc_html__('Weekday', 'noo-timetable').'</option>';
                        echo '<option value="weekend">'.esc_html__('Weekend day', 'noo-timetable').'</option>';
                    echo '</select>';
                echo '</div>';

            echo '</div><!--noo_rrule_freq_use_custom-->';

            echo '</div><!--noo-recurrence-setup-->';
        }

	    public function meta_box_select_multiple($post, $id, $type, $meta, $std, $field) {

		    if ( 'select_multiple_chosen' == $type ) {
			    wp_enqueue_script( 'chosen-js');
			    wp_enqueue_style( 'chosen-css');
		    }

		    $meta = $meta ? $meta : $std;
		    $meta = noo_timetable_json_decode( $meta );
		    echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="" />';
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
                    });
                </script>
			    <?php
		    }
	    }

        public function meta_box_color_picker( $post, $id, $type, $meta, $std, $field ) {

            $presets   = noo_timetable_get_presets_color();

            $color_default = $field['default'] != '' ? "'" . $field['default'] . "'" : 'false';

            echo '  <div class="wrap-wp-colorpicker">';
            echo '      <input type="text" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' . esc_attr( $meta ) . '" /> ';
            echo '<div class="wrap_set_color">';
            foreach( $presets as $color ) {
                echo '<span class="set_color" data-color="'.esc_attr($color).'" style="background-color:'.esc_attr($color).'"></span>';
            }
            echo '</div>';
            echo '  </div>';
            ?>
            <script>

                jQuery(document).ready(function($) {
                    $('#<?php echo esc_js($id); ?>').wpColorPicker({
                        defaultColor: <?php echo $color_default; ?>,
                    });
                });
            </script>
            <?php
        }

    	public function meta_box_datepicker( $post, $id, $type, $meta, $std, $field ) {

            wp_enqueue_script( 'datetimepicker' );
            wp_enqueue_style( 'datetimepicker' );
            $date_format = 'Y-m-d';

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

        /**
         * Creating field map
         */
        public function meta_box_google_map( $post, $meta_box ) {
        ?>
            <div class="noo_event_google_map">
                <div id="noo_event_google_map" class="noo_event_google_map"
                    style="height: 380px; margin-bottom: 30px; overflow: hidden; position: relative; width: 100%;">
                </div>
                <div class="noo_event_google_map_search">
                    <input type="text" autocomplete="off" id="noo_event_google_map_search_input" placeholder="<?php echo esc_html__( 'Search your map', 'noo-timetable' ); ?>">
                </div>
            </div>

            <style>
            .noo_event_google_map{
                position: relative;
                width: 100%;
            }
            .noo_event_google_map_search {
                position: absolute;
                width: 50%;
                margin: 0 auto;
                top: 10px;
                left: 50%;
                -webkit-transform: translateX(-50%);
                -moz-transform: translateX(-50%);
                transform: translateX(-50%);

            }
            #noo_event_google_map_search_input{
                 margin-top: 16px;
                 border: 1px solid transparent;
                 border-radius: 2px 0 0 2px;
                 box-sizing: border-box;
                 -moz-box-sizing: border-box;
                 height: 32px;
                 outline: none;
                 box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                 background-color: #fff;
                 padding: 0 11px 0 13px;
                 width: 400px;
            }

            ._noo_event_gmap .noo-control {
                width: 100%;
                float: left;
            }
            </style>

            <?php
        }

        public function request_query( $vars ) {
            global $typenow;
            if ( 'noo_event' === $typenow ) {
                if ( isset( $vars['orderby'] ) ) {
                    if ( 'event_start_date' == $vars['orderby'] ) {
                        $vars = array_merge( $vars, array(
                            'meta_key'  => '_noo_event_start_date',
                            'orderby'   => 'meta_value_num'
                        ) );
                    }

                    if ( 'event_next_date' == $vars['orderby'] ) {
                        $vars = array_merge( $vars, array(
                            'meta_key'  => '_next_date',
                            'orderby'   => 'meta_value_num'
                        ) );
                    }
                }
            }
            return $vars;
        }

        public function manage_edit_columns( $columns ) {
            unset( $columns['author'], $columns['comments'], $columns['categories'], $columns['date'] );
            $columns['organizers']       = esc_html__( 'Organizers', 'noo-timetable' );
            $columns['featured']         = esc_html__( 'Featured?', 'noo-timetable' );
            $columns['event_start_date'] = esc_html__( 'Event Start Date', 'noo-timetable' );
            $columns['event_next_date']  = esc_html__( 'Event Current Date', 'noo-timetable' );
            return $columns;
        }

        public function sortable_columns( $columns ) {
            $custom = array(
                'event_start_date' => 'event_start_date',
                'event_next_date'  => 'event_next_date',
            );
            return wp_parse_args( $custom, $columns );
        }

        public function manage_custom_column($column, $post_id) {
            switch ( $column ) {
                case 'organizers':
                    $organizers_ids = get_post_meta( $post_id, "_noo_event_organizers", true );
	                $name_authors = '';
                    if( is_string($organizers_ids) ) {
                        $name_authors  = get_post_meta( $organizers_ids, "_noo_event_author", true );
                    }
                    if( !empty($organizers_ids) && is_array($organizers_ids) ) {
                        foreach($organizers_ids as $oid) {
	                        $name_author   = get_post_meta( $oid, "_noo_event_author", true );
	                        if ( !empty( $name_author ) ) {
		                        if ($oid === end($organizers_ids)) {
			                        $name_authors .= esc_html( $name_author );
		                        } else {
			                        $name_authors .= esc_html( $name_author ) . ', ';
                                }
                            }
                        }
                    }
                    echo $name_authors;
                    break;
                case 'featured':
                    $featured = get_post_meta( $post_id, 'event_is_featured', true );
                    if( empty( $featured ) ) {
                        // Update old data
                        update_post_meta( $post_id, 'event_is_featured', '0' );
                    }
                    $url = wp_nonce_url( admin_url( 'admin-ajax.php?action=event_feature&event_id=' . $post_id ), 'noo-event-feature' );
                    echo '<a href="' . esc_url( $url ) . '" title="'. __( 'Toggle featured', 'noo-timetable' ) . '">';
                    if ( '1' === $featured ) {
                        echo '<span class="noo-event-feature" title="' . esc_attr__( 'Yes', 'noo-timetable' ) . '"><i class="dashicons dashicons-star-filled "></i></span>';
                    } else {
                        echo '<span class="noo-event-feature not-featured" title="' . esc_attr__( 'No', 'noo-timetable' ) . '"><i class="dashicons dashicons-star-empty"></i></span>';
                    }
                    echo '</a>';
                    break;

                case 'event_start_date':
                    $eventStartDate = get_post_meta( $post_id, '_noo_event_start_date', true );
                    if ( $eventStartDate )
                        echo date_i18n( 'Y/m/d', $eventStartDate);
                    else
                        echo '-';
                    break;

                case 'event_next_date':
                    $eventNextDate = get_post_meta( $post_id, '_next_date', true );
                    if ( $eventNextDate )
                        echo date_i18n( 'Y/m/d', $eventNextDate);
                    else
                        echo '-';
                    break;
            }
        }

    }

    new Noo__Timetable__Event_Meta_Boxes();
}
