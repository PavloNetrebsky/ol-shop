<?php
/**
 * Providing processing functions of color based user options.
 *
 * @author      NooTheme
 * @category    Library
 * @package     NooTimetable/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !function_exists( 'noo_timetable_customizer_css_generator' ) ) {

	function noo_timetable_customizer_css_generator( $options = array() ) {

		ob_start();

		$css_id = $options && $options['css_class'] ? 'noo-timetable-css-inline-' . $options['css_class'] : 'noo-timetable-css-inline';
		$css_class = $options && $options['css_class'] ? '.' . $options['css_class'] : '';

		// Variables
        $noo_schedule_content_height                        = $options && $options['content_height'] ? $options['content_height'] : 'auto';
		$noo_schedule_body_today_column             	  	=  	$options && $options['general_today_column'] ? $options['general_today_column'] : NOO_Settings()->get_option('noo_schedule_general_today_column', '#fcf8e3');
		$noo_schedule_header_color                        	=  	$options && $options['general_header_color'] ? $options['general_header_color'] : NOO_Settings()->get_option('noo_schedule_general_header_color', '#fff');
		$noo_schedule_header_background                   	=  	$options && $options['general_header_background'] ? $options['general_header_background'] : NOO_Settings()->get_option('noo_schedule_general_header_background', '#cf3d6f');
		
		$noo_schedule_holiday_background                   	=  	$options && $options['general_holiday_background'] ? $options['general_holiday_background'] : NOO_Settings()->get_option('noo_schedule_general_holiday_background', '#cf3d6f');


		$noo_schedule_header_background_fade_darken_15_20 	=   noo_timetable_css_fade( noo_timetable_css_darken( $noo_schedule_header_background, '15%' ), '20%' );
		$noo_schedule_header_background_fade_lighten_25_5 	=   noo_timetable_css_fade( noo_timetable_css_lighten( $noo_schedule_header_background, '25%' ), '5%' );
		?>

        <?php if( $noo_schedule_content_height == 'auto' ) { ?>
            <?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-month-view .fc-scroller,
            <?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-agendaWeek-view .fc-scroller {
                overflow-x: visible !important;
                overflow-y: visible !important;
            }
        <?php } ?>

		/**
		 * Header
		 * Background Color
		 */
		<?php echo $css_class; ?> .noo-class-schedule-shortcode.background-event .fc-view .fc-body .fc-time-grid .fc-event,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-body .fc-time-grid .fc-event .fc-content .fc-category,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-month-view .fc-popover .fc-header,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .prev:focus,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .next:focus,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .prev:hover,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .next:hover,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-month-view .fc-today.fc-day-number span,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-toolbar .fc-button:focus,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-toolbar .fc-button:hover,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-head table .fc-resource-cell,
	
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-head table .fc-widget-header {
			background-color: <?php echo esc_html($noo_schedule_header_background); ?>;
		}


		<?php echo $css_class; ?> .noo-class-schedule-shortcode.background-event .fc-view .fc-body .fc-time-grid .fc-event,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-month-view .fc-holiday {
			background-color: <?php echo esc_html($noo_schedule_holiday_background); ?>;
		}

		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .prev:focus,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .next:focus,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .prev:hover,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .next:hover {
			color: #fff;
		}
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-head td,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-head table .fc-resource-cell,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-head table .fc-widget-header {
			border-color: <?php echo esc_html($noo_schedule_header_background_fade_darken_15_20); ?>;
		}
		<?php echo $css_class; ?> .noo-filters ul li a:hover,
		<?php echo $css_class; ?> .noo-filters ul li a:focus {
			color: <?php echo esc_html($noo_schedule_header_background); ?>;
		}
		<?php echo $css_class; ?> .noo-filters ul li a.selected {
			color: <?php echo esc_html($noo_schedule_header_background); ?>;
		}
		<?php echo $css_class; ?> .noo-filters ul li a.selected:before {
			border-color: <?php echo esc_html($noo_schedule_header_background); ?>;
		}
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-body .fc-time-grid .fc-event .fc-ribbon,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .prev,
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .next,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-toolbar .fc-button {
			color: <?php echo esc_html($noo_schedule_header_background); ?>;
			border-color: <?php echo esc_html($noo_schedule_header_background); ?>;
		}
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-body .fc-time-grid .fc-event.fc-noo-class.show-icon .fc-content:before,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-body .fc-time-grid .fc-event.fc-noo-event.show-icon .fc-content:before {
			color: <?php echo esc_html($noo_schedule_header_background); ?>;
		}
		/**
		 * Header
		 * Text Color
		 */
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .res-sche-navigation .next:hover,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-month-view .fc-popover .fc-header .fc-close,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-month-view .fc-popover .fc-header,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-toolbar .fc-button:focus,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-toolbar .fc-button:hover,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-month-view .fc-today.fc-day-number span,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-head table .fc-axis,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-head table .fc-resource-cell,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-head table .fc-day-header {
			color: <?php echo $noo_schedule_header_color; ?>
		}

		/**
		 * Body
		 * Background
		 */
		<?php echo $css_class; ?> .noo-responsive-schedule-wrap .item-weekday.today,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-bg .fc-today,
		<?php echo $css_class; ?> .noo-class-schedule-shortcode .fc-view .fc-list-table .fc-today  {
			background-color: <?php echo $noo_schedule_body_today_column; ?>;
		}
		<?php

		$css_inline = ob_get_contents(); ob_end_clean();

		// Remove comment, space
		$css_inline = preg_replace( '#/\*.*?\*/#s', '', $css_inline );
		$css_inline = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css_inline );
		$css_inline = preg_replace( '/\s\s+(.*)/', '$1', $css_inline );

		echo '<style id="' . $css_id . '" type="text/css">' . $css_inline . '</style>';
	}

}

if( !function_exists( 'noo_timetable_customizer_css_generator_color' ) ) {

	function noo_timetable_customizer_css_generator_color() {
		ob_start();

		$css_id = 'noo-timetable-css-inline-color';
		?>
		/**
		 * Hentry
		 * Background Color
		 */
		<?php

		if (NOO_Settings()->get_option( 'noo_classes_show_color', 'yes' ) == 'yes' ) :

			$args = array(
			    'posts_per_page' => '-1',
			    'post_type'      => 'noo_class'
			);

			$classes = get_posts($args);
			foreach ( $classes as $cl ) {

				$color = Noo__Timetable__Class::get_color_by_category($cl->ID);
				$class_color = str_replace('#', 'class_category_', $color);

				$color_darken_15 = noo_timetable_css_darken( $color, '25%' );

				?>
	            .noo-class-shortcode article.hentry.<?php echo $class_color; ?> .content-meta i,
	            .noo-class-shortcode article.hentry.<?php echo $class_color; ?> a:not(.button) {
					color: <?php echo esc_html($color); ?>;
				}
				.noo-class-shortcode article.hentry.<?php echo $class_color; ?> a:not(.button):hover {
					color: <?php echo esc_html($color_darken_15); ?>;
				}
				.noo-class-shortcode article.hentry.<?php echo $class_color; ?> .button {
					background-color: <?php echo esc_html($color); ?>;
				}
				.noo-class-shortcode article.hentry.<?php echo $class_color; ?> .button:hover {
					background-color: <?php echo esc_html($color_darken_15); ?>;
				}
	            <?php
			}

		endif;

		if (NOO_Settings()->get_option( 'noo_event_show_color', 'yes' ) == 'yes' ) :

			$args = array(
			    'posts_per_page' => '-1',
			    'post_type'      => 'noo_event'
			);

			$events = get_posts($args);
			foreach ( $events as $ev ) {

				$color = get_post_meta( $ev->ID, "_noo_event_bg_color", '#fff' );
				$class_color = str_replace('#', 'event_', $color);

				$color_darken_15 = noo_timetable_css_darken( $color, '25%' );

				?>
	            .noo-event-shortcode article.hentry.<?php echo $class_color; ?> .noo-event-meta i,
	            .noo-event-shortcode article.hentry.<?php echo $class_color; ?> a:not(.button) {
					color: <?php echo esc_html($color); ?>;
				}
				.noo-event-shortcode article.hentry.<?php echo $class_color; ?> a:not(.button):hover {
					color: <?php echo esc_html($color_darken_15); ?>;
				}
				.noo-event-shortcode article.hentry.<?php echo $class_color; ?> .button {
					background-color: <?php echo esc_html($color); ?>;
				}
				.noo-event-shortcode article.hentry.<?php echo $class_color; ?> .button:hover {
					background-color: <?php echo esc_html($color_darken_15); ?>;
				}
	            <?php
			}

		endif;

		$css_inline = ob_get_contents(); ob_end_clean();

		// Remove comment, space
		$css_inline = preg_replace( '#/\*.*?\*/#s', '', $css_inline );
		$css_inline = preg_replace( '/\s*([{}|:;,])\s+/', '$1', $css_inline );
		$css_inline = preg_replace( '/\s\s+(.*)/', '$1', $css_inline );

		echo '<style id="' . $css_id . '" type="text/css">' . $css_inline . '</style>';
	}
}