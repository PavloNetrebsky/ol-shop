<?php
/**
 * Create shortcode: [noo_trainer]
 *
 * @package 	Noo_Hermosa_Core
 * @author      Hung Ngo <hungnt@vietbrain.com>
 */

if ( ! function_exists( 'shortcode_noo_class_schedule' ) ) :
	
	function shortcode_noo_class_schedule( $atts ) {

		extract( shortcode_atts( array(
            'title'                => '',
            'sub_title'            => '',
            'min_time'             => '01:00:00',
            'max_time'             => '24:00:00',
            'content_height'       => '',
            'default_view'         => 'agendaWeek',
            'hide_time_range'      => '',
            'show_weekends'        => 'yes',
            'show_time_column'     => 'yes',
            'show_export'          => 'yes',
        ), $atts ) );

        // 
        // Style Control Class
        // 

        if ( $show_time_column == 'yes' )
            $class_shortcode = 'noo-class-schedule-shortcode';
        else
            $class_shortcode = 'noo-class-schedule-shortcode hide-time-column';

        wp_enqueue_script('calendar');
        wp_enqueue_script('calendar-lang');
        wp_enqueue_script('ics');
        wp_enqueue_script('ics-deps');

        ob_start();
        
        ?>
        <div class=" <?php echo esc_attr( $class_shortcode ); ?>">

            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
            <!-- Section title -->
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

            <!-- Section content -->

            <?php

            $id        = uniqid('noo_class_schedule_');
            $filter_id = uniqid('schedule_filter_');
            $export_id = uniqid('export_timetable_');

            $doituong = new Noo__Timetable__Class();
            $classes_arr = $doituong->_get_schedule_class_list();


            $content_height = is_numeric( $content_height ) ? $content_height : "'auto'";

            $header['next'] = 'next';
            $header['prev'] = 'prev';
            // RTL Options
            if ( is_rtl() ){
                $header['next'] = 'prev';
                $header['prev'] = 'next';
            }
            
            // Weekend option
            $weekends = ( $show_weekends == 'no' ) ? 'false' : 'true';      
            
            ob_start();
            ?>
            <div class="noo-class-schedule">
                <div id="<?php echo esc_attr($filter_id); ?>" class="class-schedule-filter noo-filters">
                    <?php if($categories = get_terms('class_category')):?>
                    <ul>
                        <li><a href="#" class="selected" data-filter=""><?php esc_html_e('All Category', 'noo-hermosa-core')?></a></li>
                        <?php foreach ((array)$categories as $category):?>
                        <li><a href="#" data-filter="<?php echo esc_attr($category->term_id)?>"><?php echo esc_html($category->name)?></a></li>
                        <?php endforeach;?>
                    </ul>
                    <?php endif;?>
                </div>
                <div id="<?php echo esc_attr($id)?>" class="class-schedule"></div>

                <?php
                    if ( $show_export == 'yes' ) {
                        echo '<div class="export-timetable"><a href="#" id="' . esc_attr($export_id) . '"><i class="icon ion-android-arrow-dropright-circle text-primary"></i>' . esc_html__('Export Classes', 'noo-hermosa-core') . '</a></div>';
                    }
                ?>
                
                <script>
                    var source_<?php echo esc_attr($id)?> = <?php echo json_encode($classes_arr)?>;
                </script>
                <script>
                    jQuery(document).ready(function($) {
                        $("#<?php echo esc_attr($id)?>").fullCalendar({
                            isRTL: <?php echo is_rtl() ? 'true' : 'false'; ?>,
                            header: {
                                left: '<?php echo $header['prev']; ?>',
                                center: 'title',
                                right: '<?php echo $header['next']; ?>',
                            },
                            axisFormat: 'HH:mm',
                            minTime: '<?php echo apply_filters('noo-class-schedule-mintime', $min_time)?>',
                            maxTime: '<?php echo apply_filters('noo-class-schedule-maxtime', $max_time)?>',
                            timeFormat: '<?php echo Noo__Timetable__Class::convertPHPToMomentFormat( get_option('time_format') ); ?>',
                            axisFormat: '<?php echo Noo__Timetable__Class::convertPHPToMomentFormat( get_option('time_format') ); ?>',
                            defaultView: '<?php echo esc_attr( $default_view ); ?>',
                            firstDay: <?php echo get_option('start_of_week'); ?>,
                            slotDuration: '01:00:00',
                            <?php if ( $default_view == 'agendaWeek'  ) : ?>
                            columnFormat: 'dddd\n|MM/DD/YYYY~',
                            <?php else : ?>
                            columnFormat: 'dddd',
                            <?php endif; ?>
                            allDaySlot: false,
                            // defaultDate: '<?php echo current_time('Y-m-d')?>',
                            defaultDate: '2016-05-02',
                            editable: false,
                            lang:'<?php echo get_locale()?>',
                            eventLimit: true, // allow "more" link when too many events
                            events: source_<?php echo esc_attr($id)?>,
                            labelColumnTime: '<?php esc_html_e('Time', 'noo-hermosa-core') ?>',
                            weekends: <?php echo $weekends; ?>,
                            textWith: '<?php echo esc_html__('with','noo-hermosa-core'); ?>',
                            <?php if ( $default_view == 'agendaWeek'  ) : ?>
                            hideTimeRange: '<?php echo $hide_time_range; ?>',
                            contentHeight: <?php echo $content_height; ?>
                            <?php endif; ?>
                        });

                        $("#<?php echo esc_attr($filter_id); ?> a").on("click", function(e){
                            e.preventDefault();
                            var $this = $(this);

                            $.ajax({
                                type: 'POST',
                                url: nooL10n.ajax_url,
                                data: {
                                    action          : 'noo_class_filter',
                                    class_category  : $(this).data("filter"),
                                    sercurity       : '<?php echo wp_create_nonce( 'class_filter' ); ?>'
                                },
                                beforeSend: function() {
                                    $this.closest('.noo-class-schedule').find('.fc-body').addClass('overlay-loading-tripped');
                                    $this.closest('.noo-class-schedule').find(".class-schedule-filter a.selected")
                                        .removeClass("selected")
                                        .removeClass('class-schedule-infi-pulse');
                                    $this
                                        .addClass("selected")
                                        .addClass('class-schedule-infi-pulse');
                                },
                                success: function(res){
                                    var newsource = res;
                                    $this.closest('.noo-class-schedule').find('.fc-body').removeClass('overlay-loading-tripped');
                                    $this.closest('.noo-class-schedule').find(".class-schedule-filter a.selected")
                                        .removeClass("selected")
                                        .removeClass('class-schedule-infi-pulse');
                                    $this
                                        .addClass("selected")
                                        .removeClass('class-schedule-infi-pulse');

                                    if(newsource){
                                        $("#<?php echo esc_attr($id)?>").fullCalendar('removeEventSource', source_<?php echo esc_attr($id)?>)
                                        $("#<?php echo esc_attr($id)?>").fullCalendar('refetchEvents')
                                        $("#<?php echo esc_attr($id)?>").fullCalendar('addEventSource', newsource)
                                        $("#<?php echo esc_attr($id)?>").fullCalendar('refetchEvents');
                                        source_<?php echo esc_attr($id)?> = newsource;
                                    }
                                },
                                error: function () {
                                    location.reload();
                                }
                            });
                        });

                        
                        // Download iCal button onclick listener
                        $("#<?php echo esc_attr($export_id); ?>").on('click',function(){
                            // setup ics
                            var cal = ics();
                            // go through each event from the json and add an event for it to ics
                            $.each(source_<?php echo esc_attr($id)?>,function(i, $event){
                                cal.addEvent($event.title, $event.categoryName, '', $event.start, $event.end);
                            });

                            cal.download('<?php esc_html_e('ical-class-chedule', 'noo-hermosa-core'); ?>', '.ics');
                            return false;
                        });
                    });
                </script>
            </div>

            
        </div> <!-- /.noo-class-schedule-shortcode -->

        <div class="noo-responsive-schedule-wrap">
            <?php
                if ( get_option('start_of_week') == date( "w") ) {
                    $first_week_day = date('Y-m-d');
                } else {
                    $start_of_week = Noo__Timetable__Class::_get_week_day( get_option('start_of_week') );
                    $first_week_day = date( 'Y-m-d', strtotime('last ' . $start_of_week) );
                }
                $end_week_day = date( 'Y-m-d', strtotime($first_week_day . ' +7 days') );

                //Create label
                $label_start = date_i18n( get_option( 'date_format' ), strtotime($first_week_day) );
                $label_end = date_i18n( get_option( 'date_format' ), strtotime($first_week_day . ' +6 days') );

                // Create nav
                $prev_from = date('Y-m-d',( strtotime ( '-1 week' , strtotime ( $first_week_day ) ) ) );
                $prev_to = date('Y-m-d',( strtotime ( '-1 week' , strtotime ( $end_week_day ) ) ) );

                $next_from = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $first_week_day ) ) ) );
                $next_to = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $end_week_day ) ) ) );
            ?>
            
            <div class="res-sche-navigation">
                <a href="#" class="prev" data-from="<?php echo esc_attr( $prev_from ); ?>" data-to="<?php echo esc_attr( $prev_to ); ?>"><i class="icon ion-ios-arrow-left"></i></a>
                <h3><?php echo esc_attr( $label_start ); ?> - <?php echo esc_attr( $label_end ); ?></h3>
                <a href="#" class="next" data-from="<?php echo esc_attr( $next_from ); ?>" data-to="<?php echo esc_attr( $next_to ); ?>"><i class="icon ion-ios-arrow-right"></i></a>
            </div>

            <div class="res-sche-content">
                <?php
                    $doituong = new Noo__Timetable__Class();
                    $doituong->_schedule_class_list_mobile($first_week_day, $end_week_day, true);
                ?>
            </div>

            <div class="res-sche-navigation">
                <a href="#" class="prev" data-from="<?php echo esc_attr( $prev_from ); ?>" data-to="<?php echo esc_attr( $prev_to ); ?>"><i class="icon ion-ios-arrow-left"></i></a>
                <h3><?php echo esc_attr( $label_start ); ?> - <?php echo esc_attr( $label_end ); ?></h3>
                <a href="#" class="next" data-from="<?php echo esc_attr( $next_from ); ?>" data-to="<?php echo esc_attr( $next_to ); ?>"><i class="icon ion-ios-arrow-right"></i></a>
            </div>

        </div> <!-- noo-responsive-schedule-wrap -->

        <script>
        jQuery(document).ready(function($) {
            
            $(".res-sche-navigation a").on("click", function(e){
                e.preventDefault();
                var $this = $(this);

                $.ajax({
                    type: 'POST',
                    url: nooL10n.ajax_url,
                    data: {
                        action          : 'noo_class_responsive_navigation',
                        from            : $this.attr("data-from"),
                        to              : $this.attr("data-to"),
                        weekends        : true,
                        sercurity       : '<?php echo wp_create_nonce( 'class_responsive_navigation' ); ?>'
                    },
                    beforeSend: function() {
                        var sche_wrap = $this.closest('.noo-responsive-schedule-wrap');
                        sche_wrap.find('.res-sche-content').addClass('overlay-loading-tripped');
                    },
                    success: function(res){

                        var sche_wrap = $this.closest('.noo-responsive-schedule-wrap');
                        sche_wrap.find('.res-sche-content').removeClass('overlay-loading-tripped');
                        sche_wrap.find('.res-sche-content').html(res);

                        label_start = sche_wrap.find('.label-start').val();
                        label_end = sche_wrap.find('.label-end').val();

                        sche_wrap.find('.res-sche-navigation h3').html(label_start + ' - ' + label_end);

                        var _nav_prev = sche_wrap.find('.res-sche-navigation .prev');
                        var _nav_next = sche_wrap.find('.res-sche-navigation .next');

                        _nav_prev.attr( 'data-from', sche_wrap.find('.prev-from-hidden').val() );
                        _nav_prev.attr( 'data-to', sche_wrap.find('.prev-to-hidden').val() );

                        _nav_next.attr( 'data-from', sche_wrap.find('.next-from-hidden').val() );
                        _nav_next.attr( 'data-to', sche_wrap.find('.next-to-hidden').val() );

                    },
                    error: function () {
                        location.reload();
                    }
                });
            });

        });

        </script>


		<?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}
    add_shortcode( 'noo_class_schedule', 'shortcode_ntt_schedule' );

endif;