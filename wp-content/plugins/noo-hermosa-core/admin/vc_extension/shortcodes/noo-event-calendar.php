<?php
/**
 * Create shortcode: [noo_event_calendar]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'shortcode_noo_event_calendar' ) ) :
	
	function shortcode_noo_event_calendar( $atts ) {

        if ( ! class_exists('Noo__Timetable__Event') ) return;

		extract( shortcode_atts( array(
            'title'         => '',
            'sub_title'     => '',
            'show_weekends' => 'yes',
            'show_export'   => 'yes'
        ), $atts ) );

        ob_start();

        /**
         * Enqueue library
         */
		wp_enqueue_script( 'calendar' );
        wp_enqueue_script('ics');
        wp_enqueue_script('ics-deps');

        /**
         * VAR
         */
        $source = Noo__Timetable__Event::show_mobile_event();
        $weekends = ( $show_weekends == 'no' ) ? 'false' : 'true';    

        ?>

        <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>

            <div class="noo-theme-wraptext">
                <div class="wrap-title">

                <?php if ( !empty( $title ) ) : ?>
                    <div class="noo-theme-title-bg"></div>

                    <h3 class="noo-theme-title">
                        <?php
                            $title = explode( ' ', $title );
                            $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
                            $title = implode( ' ', $title );
                        ?>
                        <?php echo $title; ?>
                    </h3>
                <?php endif; ?>

                <?php if ( !empty( $sub_title ) ) : ?>
                    <p class="noo-theme-sub-title">
                        <?php echo esc_html( $sub_title ); ?>
                    </p>
                <?php endif; ?>

                </div> <!-- /.wrap-title -->    
            </div>

        <?php endif; ?>

        <div class="noo-event-calendar-wrap"></div>

        <div class="noo-responsive-calendar-wrap">
            <?php
                $first_week_day = date('Y-m-d');
                $end_week_day   = date( 'Y-m-d', strtotime($first_week_day . ' +7 days') );

                //Create label
                $label_start = date_i18n( get_option( 'date_format' ), strtotime($first_week_day) );
                $label_end = date_i18n( get_option( 'date_format' ), strtotime($first_week_day . ' +6 days') );

                // Create nav
                $prev_from = date('Y-m-d',( strtotime ( '-1 week' , strtotime ( $first_week_day ) ) ) );
                $prev_to = date('Y-m-d',( strtotime ( '-1 week' , strtotime ( $end_week_day ) ) ) );

                $next_from = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $first_week_day ) ) ) );
                $next_to = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $end_week_day ) ) ) );
            ?>
            
            <div class="res-calendar-navigation">
                <a href="#" class="prev" data-from="<?php echo esc_attr( $prev_from ); ?>" data-to="<?php echo esc_attr( $prev_to ); ?>"><i class="icon ion-ios-arrow-left"></i></a>
                <h3><?php echo esc_attr( $label_start ); ?> - <?php echo esc_attr( $label_end ); ?></h3>
                <a href="#" class="next" data-from="<?php echo esc_attr( $next_from ); ?>" data-to="<?php echo esc_attr( $next_to ); ?>"><i class="icon ion-ios-arrow-right"></i></a>
            </div>

            <div class="res-calendar-content">
                <?php
                    Noo__Timetable__Event::show_list_calender_mobile( $first_week_day, $end_week_day, true );
                ?>
            </div>

            <div class="res-calendar-navigation">
                <a href="#" class="prev" data-from="<?php echo esc_attr( $prev_from ); ?>" data-to="<?php echo esc_attr( $prev_to ); ?>"><i class="icon ion-ios-arrow-left"></i></a>
                <h3><?php echo esc_attr( $label_start ); ?> - <?php echo esc_attr( $label_end ); ?></h3>
                <a href="#" class="next" data-from="<?php echo esc_attr( $next_from ); ?>" data-to="<?php echo esc_attr( $next_to ); ?>"><i class="icon ion-ios-arrow-right"></i></a>
            </div>

        </div> <!-- noo-responsive-schedule-wrap -->

        <?php
            if ( $show_export == 'yes' ) {
                echo '<div class="export-timetable"><a href="#"><i class="icon ion-android-arrow-dropright-circle text-primary"></i>' . esc_html__('Export Event', 'noo-hermosa-core') . '</a></div>';
            }
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.noo-event-calendar-wrap').each(function(index, el) {
                    /**
                     * VAR
                     */
                        var _this = $(this);
                    
                    /**
                     * Process
                     */
                        _this.fullCalendar({
                            isRTL: <?php echo is_rtl() ? 'true' : 'false'; ?>,
                            header: {
                                left: 'prev',
                                center: 'title',
                                right: 'next'
                            },
                            defaultDate: '<?php echo current_time('Y-m-d')?>',
                            editable: false,
                            columnFormat: 'dddd',
                            timeFormat: 'h:mm a',
                            eventLimit: true,
                            events: <?php echo $source; ?>,
                            weekends: <?php echo $weekends; ?>,
                        });


                });

                // setup ics
                var cal = ics();
                // go through each event from the json and add an event for it to ics
                $.each(<?php echo $source; ?>,function(i, $event){
                    cal.addEvent($event.title, '', '', $event.start, $event.end);
                });
                // Download iCal button onclick listener
                $(".export-timetable").on('click',function(){
                    cal.download('ical','.ics');
                    return;
                });
            });
        </script>

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_event_calendar', 'shortcode_noo_event_calendar' );

endif;
