<?php
/**
 * Create shortcode for NOO Timetable
 *
 * @author      NooTheme
 * @package     NooTimetable/Shortcodes
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

//
// Variables.
//
$category_name = esc_html__( 'By NooTimetable', 'noo-timetable' );

/**
 * Create shortcode: [ntt_trainer]
 */
ns_map( array(
	'name'        => esc_html__( 'Noo Trainer', 'noo-timetable' ),
	'base'        => 'ntt_trainer',
	'description' => '',
	'icon'        => 'fa-user',
	'category'    => $category_name,
	'params'      => array(

		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title (optional)', 'noo-timetable' ),
			'description' => '',
			'admin_label' => true,
			'type'        => 'textfield',
			'value'       => '',
		),

		array(
			'param_name'  => 'control_bar_bg',
			'heading'     => esc_html__( 'Control Bar Background', 'noo-timetable' ),
			'description' => '',
			'admin_label' => false,
			'type'        => 'colorpicker',
			'value'         => '',
			'group'         => esc_html__( 'Control Bar Setting', 'noo-timetable' ),
		),

		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title (optional)', 'noo-timetable' ),
			'description' => '',
			'type'        => 'textfield',
			'value'       => '',
		),



		array(
			'param_name'  => 'test',
			'heading'     => esc_html__( 'Sub Title (optional)', 'noo-timetable' ),
			'description' => '',
			'type'        => 'param_group',
			'params'       => array(
				array(
					'param_name'  => 'sub_title',
					'heading'     => esc_html__( 'Sub Title (optional)', 'noo-timetable' ),
					'description' => '',
					'type'        => 'textfield',
					'value'       => '',
				),
			),
		),


		array(
			'param_name'  => 'layout_style',
			'heading'     => esc_html__( 'Layout Style', 'noo-timetable' ),
			'type'        => 'dropdown',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Grid', 'noo-timetable' ) => 'grid',
				esc_html__( 'List', 'noo-timetable' ) => 'list',
			),
		),

		array(
			'param_name' => 'columns',
			'heading'    => esc_html__( 'Columns', 'noo-timetable' ),
			'type'       => 'ui_slider',
			'value'      => '4',
			'data_min'   => '1',
			'data_max'   => '4',
			'dependency' => array( 'element' => 'layout_style', 'value' => array( 'grid', 'slider' ) ),
		),

		array(
			'param_name'  => 'cat',
			'heading'     => esc_html__( 'Trainer Categories', 'noo-timetable' ),
			'admin_label' => true,
			'description' => '',
			'type'        => 'trainer_categories',
		),
		array(
			'param_name'  => 'orderby',
			'heading'     => esc_html__( 'Order By', 'noo-timetable' ),
			'description' => '',
			'admin_label' => true,
			'type'        => 'dropdown',
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' )                 => 'default',
				esc_html__( 'Recent First', 'noo-timetable' )            => 'latest',
				esc_html__( 'Older First', 'noo-timetable' )             => 'oldest',
				esc_html__( 'Title Alphabet', 'noo-timetable' )          => 'alphabet',
				esc_html__( 'Title Reversed Alphabet', 'noo-timetable' ) => 'ralphabet',
			),
		),
		array(
			'param_name' => 'limit',
			'heading'    => esc_html__( 'Max Number of Trainers', 'noo-timetable' ),
			'type'       => 'ui_slider',
			'value'      => '4',
			'data_min'   => '1',
			'data_max'   => '50',
		),
	),
) );

/**
 * [$classes_param]
 * @var array
 */
$classes_param = array(
	array(
		'param_name'  => 'title',
		'heading'     => esc_html__( 'Title (optional)', 'noo-timetable' ),
		'description' => '',
		'type'        => 'textfield',
		'value'       => '',
	),
	array(
		'param_name'  => 'sub_title',
		'heading'     => esc_html__( 'Sub Title (optional)', 'noo-timetable' ),
		'description' => '',
		'type'        => 'textfield',
		'value'       => '',
	),
	array(
		'param_name'  => 'show_info',
		'heading'     => esc_html__( 'Show Info', 'noo-timetable' ),
		'type'        => 'dropdown',
		'admin_label' => true,
		'value'       => array(
			__( 'Show Date & Time', 'noo-timetable' )  => 'all',
			esc_html__( 'Only Date', 'noo-timetable' ) => 'date',
			esc_html__( 'Only Time', 'noo-timetable' ) => 'time',
			__( 'Hide Date & Time', 'noo-timetable' )  => 'null',
		),
	),
	array(
		'param_name'  => 'going_on',
		'heading'     => esc_html__( 'Hide classes are going on', 'noo-timetable' ),
		'type'        => 'checkbox',
		'value'      => ''
	),

	array(
		'param_name'  => 'cat',
		'heading'     => esc_html__( 'Class Categories', 'noo-timetable' ),
		'description' => '',
		'type'        => 'class_categories',
	),

	array(
		'param_name'  => 'pagination',
		'heading'     => esc_html__( 'Style Pagination', 'noo-timetable' ),
		'description' => '',
		'type'        => 'dropdown',
		'std'         => 'disable',
		'admin_label' => true,
		'value'       => array(
			esc_html__( 'Disable pagination', 'noo-timetable' ) => 'disable',
			esc_html__( 'Default', 'noo-timetable' )            => 'default',
		),
	),

	array(
		'param_name'  => 'limit',
		'heading'     => esc_html__( 'Max Number of Classes', 'noo-timetable' ),
		'type'        => 'ui_slider',
		'admin_label' => true,
		'value'       => '4',
		'data_min'    => '1',
		'data_max'    => '50',
	),
	array(
		'param_name'  => 'layout_style',
		'heading'     => esc_html__( 'Layout Style', 'noo-timetable' ),
		'type'        => 'dropdown',
		'admin_label' => true,
		'value'       => array(
			esc_html__( 'Grid', 'noo-timetable' )   => 'grid',
			esc_html__( 'List', 'noo-timetable' )   => 'list',
			esc_html__( 'Slider', 'noo-timetable' ) => 'slider',
		),
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name'  => 'columns',
		'heading'     => esc_html__( 'Columns', 'noo-timetable' ),
		'type'        => 'ui_slider',
		'admin_label' => true,
		'value'       => '4',
		'data_min'    => '1',
		'data_max'    => '4',
		'dependency'  => array( 'element' => 'layout_style', 'value' => array( 'grid', 'slider' ) ),
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name' => 'autoplay',
		'heading'    => esc_html__( 'Auto Play Slider', 'noo-timetable' ),
		'type'       => 'dropdown',
		'value'      => array(
			esc_html__( 'Yes', 'noo-timetable' ) => 'true',
			esc_html__( 'No', 'noo-timetable' )  => 'false',
		),
		'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ),
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name' => 'filter_position',
		'heading'    => esc_html__( 'Filter Position', 'noo-timetable' ),
		'type'       => 'dropdown',
		'value'      => array(
			esc_html__( 'Top', 'noo-timetable' ) => '',
			esc_html__( 'Left', 'noo-timetable' )  => 'filter-left',
			esc_html__( 'Right', 'noo-timetable' )  => 'filter-right',
		),
		'dependency' => array( 'element' => 'layout_style', 'value' => array( 'grid','slider' ) ),
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name'  => 'filter_by_level',
		'heading'     => esc_html__( 'Filter by Level', 'noo-timetable' ),
		'type'		  => 'checkbox',
		'value'      => array( '' => 'true' ),
		'dependency'  => array(
			'element' => 'layout_style',
			'value'   => array('grid', 'list')
		),
		'edit_field_class' => 'vc_col-sm-3 vc_column vc_column-with-padding',
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name'  => 'filter_by_cat',
		'heading'     => esc_html__( 'Filter by Cat', 'noo-timetable' ),
		'type'		  => 'checkbox',
		'value'      => array( '' => 'true' ),
		'dependency'  => array(
			'element' => 'layout_style',
			'value'   => array('grid', 'list')
		),
		'edit_field_class' => 'vc_col-sm-3 vc_column vc_column-with-padding',
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name'  => 'filter_by_trainer',
		'heading'     => esc_html__( 'Filter by Trainer', 'noo-timetable' ),
		'type'		  => 'checkbox',
		'value'      => array( '' => 'true' ),
		'dependency'  => array(
			'element' => 'layout_style',
			'value'   => array('grid', 'list')
		),
		'edit_field_class' => 'vc_col-sm-3 vc_column vc_column-with-padding',
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name'  => 'filter_by_day',
		'heading'     => esc_html__( 'Filter by Day', 'noo-timetable' ),
		'type'		  => 'checkbox',
		'value'      => array( '' => 'true' ),
		'dependency'  => array(
			'element' => 'layout_style',
			'value'   => array('grid', 'list')
		),
		'edit_field_class' => 'vc_col-sm-3 vc_column vc_column-with-padding',
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),

);

/**
 * Create shortcode: [ntt_class]
 */
$classes_param_add   = $classes_param;
$classes_param_add[] = array(
	'param_name'  => 'orderby',
	'heading'     => esc_html__( 'Order By', 'noo-timetable' ),
	'description' => '',
	'admin_label' => true,
	'type'        => 'dropdown',
	'value'       => array(
		esc_html__( 'Default', 'noo-timetable' )                 => 'default',
		esc_html__( 'Open Date', 'noo-timetable' )               => 'open_date',
		esc_html__( 'Recent First', 'noo-timetable' )            => 'latest',
		esc_html__( 'Older First', 'noo-timetable' )             => 'oldest',
		esc_html__( 'Title Alphabet', 'noo-timetable' )          => 'alphabet',
		esc_html__( 'Title Reversed Alphabet', 'noo-timetable' ) => 'ralphabet',
	),
);
ns_map( array(
	'name'        => esc_html__( 'Noo Class', 'noo-timetable' ),
	'base'        => 'ntt_class',
	'description' => '',
	'icon'        => 'fa-calendar-o',
	'category'    => $category_name,
	'params'      => $classes_param_add,
) );

/**
 * Create shortcode: [ntt_class_coming]
 */
ns_map( array(
	'name'        => esc_html__( 'Noo Upcoming Class', 'noo-timetable' ),
	'base'        => 'ntt_class_coming',
	'description' => '',
	'icon'        => 'fa-calendar-check-o',
	'category'    => $category_name,
	'params'      => $classes_param,
) );

$events_param = array(
	array(
		'param_name'  => 'title',
		'heading'     => esc_html__( 'Title (optional)', 'noo-timetable' ),
		'description' => '',
		'type'        => 'textfield',
		'value'       => '',
	),
	array(
		'param_name'  => 'sub_title',
		'heading'     => esc_html__( 'Sub Title (optional)', 'noo-timetable' ),
		'description' => '',
		'type'        => 'textfield',
		'value'       => '',
	),

	array(
		'param_name'  => 'cat',
		'heading'     => esc_html__( 'Event Categories', 'noo-timetable' ),
		'description' => '',
		'type'        => 'event_categories',
	),

	array(
		'param_name'  => 'pagination',
		'heading'     => esc_html__( 'Style Pagination', 'noo-timetable' ),
		'description' => '',
		'type'        => 'dropdown',
		'std'         => 'disable',
		'admin_label' => true,
		'value'       => array(
			esc_html__( 'Disable pagination', 'noo-timetable' ) => 'disable',
			esc_html__( 'Default', 'noo-timetable' )            => 'default',
		),
	),
	array(
		'param_name'  => 'limit',
		'admin_label' => true,
		'heading'     => esc_html__( 'Max Number of Events', 'noo-timetable' ),
		'type'        => 'ui_slider',
		'value'       => '4',
		'data_min'    => '1',
		'data_max'    => '50',
	),

	array(
		'param_name'  => 'layout_style',
		'heading'     => esc_html__( 'Layout Style', 'noo-timetable' ),
		'type'        => 'dropdown',
		'admin_label' => true,
		'value'       => array(
			esc_html__( 'Grid', 'noo-timetable' )   => 'grid',
			esc_html__( 'List', 'noo-timetable' )   => 'list',
			esc_html__( 'Slider', 'noo-timetable' ) => 'slider',
		),
		'group'       => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name' => 'autoplay',
		'heading'    => esc_html__( 'Auto Play Slider', 'noo-timetable' ),
		'type'       => 'dropdown',
		'value'      => array(
			esc_html__( 'Yes', 'noo-timetable' ) => 'true',
			esc_html__( 'No', 'noo-timetable' )  => 'false',
		),
		'dependency' => array( 'element' => 'layout_style', 'value' => array( 'slider' ) ),
		'group'       => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name'  => 'columns',
		'heading'     => esc_html__( 'Columns', 'noo-timetable' ),
		'type'        => 'ui_slider',
		'admin_label' => true,
		'value'       => '4',
		'data_min'    => '1',
		'data_max'    => '4',
		'dependency'  => array( 'element' => 'layout_style', 'value' => array( 'grid', 'slider' ) ),
		'group'       => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name'  => 'filter_by_cat',
		'heading'     => esc_html__( 'Filter by Level', 'noo-timetable' ),
		'type'		  => 'checkbox',
		'value'      => array( '' => 'true' ),
		'dependency'  => array(
			'element' => 'layout_style',
			'value'   => array('grid', 'list')
		),
		'edit_field_class' => 'vc_col-sm-3 vc_column vc_column-with-padding',
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
	array(
		'param_name'  => 'filter_by_organizer',
		'heading'     => esc_html__( 'Filter by Organizer', 'noo-timetable' ),
		'type'		  => 'checkbox',
		'value'      => array( '' => 'true' ),
		'dependency'  => array(
			'element' => 'layout_style',
			'value'   => array('grid', 'list')
		),
		'edit_field_class' => 'vc_col-sm-3 vc_column vc_column-with-padding',
		'group'         => esc_html__( 'Layout & Filter Setting', 'noo-timetable' ),
	),
);

/**
 * Create shortcode: [ntt_event]
 */
$events_param_add   = $events_param;
$events_param_add[] = array(
	'param_name'  => 'orderby',
	'heading'     => esc_html__( 'Order By', 'noo-timetable' ),
	'description' => '',
	'admin_label' => true,
	'type'        => 'dropdown',
	'value'       => array(
		esc_html__( 'Default', 'noo-timetable' )                 => 'default',
		esc_html__( 'Current Date', 'noo-timetable' )            => 'next_date',
		esc_html__( 'Start Date', 'noo-timetable' )              => 'start_date',
		esc_html__( 'Recent Post Date First', 'noo-timetable' )  => 'latest',
		esc_html__( 'Older Post Date First', 'noo-timetable' )   => 'oldest',
		esc_html__( 'Title Alphabet', 'noo-timetable' )          => 'alphabet',
		esc_html__( 'Title Reversed Alphabet', 'noo-timetable' ) => 'ralphabet',
	),
);
$events_param_add[] = array(
	'heading'    => esc_html__( 'Hide Past Event', 'noo-timetable' ),
	'param_name' => 'hide_past_event',
	'type'       => 'checkbox',
	'value'      => array( '' => 'true' ),
);
ns_map( array(
	'name'        => esc_html__( 'Noo Event', 'noo-timetable' ),
	'base'        => 'ntt_event',
	'description' => '',
	'icon'        => 'fa-bullhorn',
	'category'    => $category_name,
	'params'      => $events_param_add,
) );

/**
 * Create shortcode: [ntt_event_coming]
 *
 * @package     Noo Library
 * @author      Hung Ngo <hungnt@vietbrain.com>
 */
ns_map( array(
	'name'        => esc_html__( 'Noo Upcoming Event', 'noo-timetable' ),
	'base'        => 'ntt_event_coming',
	'description' => '',
	'icon'        => 'fa-angle-double-right',
	'category'    => $category_name,
	'params'      => $events_param,
) );

/**
 * Create shortcode: [ntt_schedule]
 */
$hours = range( 0, 24 );
foreach ( $hours as $k => $v ) {
	$hours[ $k ] = $v . ':00:00';
}
ns_map( array(
	'name'        => esc_html__( 'Noo Schedule', 'noo-timetable' ),
	'base'        => 'ntt_schedule',
	'description' => '',
	'icon'        => 'fa-calendar',
	'category'    => $category_name,
	'params'      => array(

		array(
			'param_name'  => 'title',
			'heading'     => esc_html__( 'Title (optional)', 'noo-timetable' ),
			'description' => '',
			'admin_label' => true,
			'type'        => 'textfield',
			'value'       => '',
		),
		array(
			'param_name'  => 'sub_title',
			'heading'     => esc_html__( 'Sub Title (optional)', 'noo-timetable' ),
			'description' => '',
			'type'        => 'textfield',
			'value'       => '',
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Schedule Layout', 'noo-timetable' ),
			'param_name'  => 'schedule_layout',
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Grid', 'noo-timetable' )                => 'grid',
				esc_html__( 'List', 'noo-timetable' )                => 'list',
			),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Schedule Source', 'noo-timetable' ),
			'param_name'  => 'source',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Class', 'noo-timetable' )                => 'class',
				esc_html__( 'Event', 'noo-timetable' )                => 'event',
				esc_html__( 'Both Class and Event', 'noo-timetable' ) => 'both',
			),
		),
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Default View', 'noo-timetable' ),
			'param_name'  => 'default_view',
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Weekly View', 'noo-timetable' )  => 'agendaWeek',
				esc_html__( 'Monthly View', 'noo-timetable' ) => 'month',
				esc_html__( 'Daily View', 'noo-timetable' )   => 'agendaDay',
			),
		),
		array(
			'param_name'  => 'event_cat',
			'heading'     => esc_html__( 'Event Categories', 'noo-timetable' ),
			'admin_label' => false,
			'description' => '',
			'type'        => 'event_categories',
			'dependency'  => array( 'element' => 'source', 'value' => array( 'event' ) ),
		),
		array(
			'param_name'  => 'item_limit',
			'heading'     => esc_html__( 'Number Item Displayed', 'noo-timetable' ),
			'admin_label' => false,
			'description' => 'Limits the number of items displayed on a day. The rest will show up in a popover.',
			'type'        => 'dropdown',
			'value'       => [
				esc_html__( 'All', 'noo-timetable' )  => '0',
				esc_html__( '2 items', 'noo-timetable' ) => '3',
				esc_html__( '3 items', 'noo-timetable' )   => '4',
				esc_html__( '4 items', 'noo-timetable' )   => '5',
			],
			'std'     => '3'
		),
		array(
			'group'       => esc_html__( 'Time Options', 'noo-timetable' ),
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Schedule Min Time', 'noo-timetable' ),
			'description' => esc_html__( 'Time start of Schedule (Hour), ex: 05:00:00', 'noo-timetable' ),
			'param_name'  => 'min_time',
			'admin_label' => false,
			'value'       => $hours,
			'std'         => '00:00:00',
			'dependency'  => array( 'element' => 'default_view', 'value' => array( 'agendaWeek', 'agendaDay' ) ),
		),
		array(
			'group'       => esc_html__( 'Time Options', 'noo-timetable' ),
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Schedule Max Time', 'noo-timetable' ),
			'description' => esc_html__( 'Time end of Schedule (Hour), ex: 21:00:00', 'noo-timetable' ),
			'param_name'  => 'max_time',
			'admin_label' => false,
			'value'       => $hours,
			'std'         => '24:00:00',
			'dependency'  => array( 'element' => 'default_view', 'value' => array( 'agendaWeek', 'agendaDay' ) ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Schedule Height', 'noo-timetable' ),
			'description' => esc_html__( 'Input height of schedule, leave blank for auto height.', 'noo-timetable' ),
			'param_name'  => 'content_height',
			'admin_label' => false,
			'value'       => '',
			'dependency'  => array( 'element' => 'default_view', 'value' => array( 'agendaWeek', 'agendaDay' ) ),
		),

		array(
			'group'       => esc_html__( 'Time Options', 'noo-timetable' ),
			'type'        => 'time_range_lists',
			'heading'     => esc_html__( 'Hide Time Ranges', 'noo-timetable' ),
			'description' => esc_html__( 'Hours selected here will be hidden from the schedule. Note that you shouldn\'t select hours that have classes as it will lead to wrong calculation.',
				'noo-timetable' ),
			'param_name'  => 'hide_time_range',
			'admin_label' => false,
			'dependency'  => array( 'element' => 'default_view', 'value' => array( 'agendaWeek', 'agendaDay' ) ),
		),

		array(
			'group'       => esc_html__( 'Time Options', 'noo-timetable' ),
			'param_name'  => 'show_time_column',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Time Column', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
			'dependency'  => array( 'element' => 'default_view', 'value' => array( 'agendaWeek', 'agendaDay' ) ),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'show_cate_filter',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Category Filter', 'noo-timetable' ),
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
			'dependency'  => array( 'element' => 'source', 'value' => 'event' ),
		),
		/*
		 * since at version 2.0.4.7
		 */
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'filter_type',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Filter Type', 'noo-timetable' ),
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Category', 'noo-timetable' ) => 'category',
				//esc_html__( 'Trainer', 'noo-timetable' )     => 'trainer',
				esc_html__( 'Level', 'noo-timetable' )      => 'level',
			),
			'dependency'  => array( 'element' => 'source', 'value' => 'class' ),
		),
		array(
			'heading'     => esc_html__( 'Class Categories', 'noo-timetable' ),
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'class_cat',
			'admin_label' => false,
			'type'        => 'class_categories',
			'dependency'  => array(
				'element' => 'filter_type',
				'value' => 'category'
			),
		),
		array(
			'heading'     => esc_html__( 'Class Levels', 'noo-timetable' ),
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'class_level',
			'admin_label' => false,
			'type'        => 'class_levels',
			'dependency'  => array(
				'element' => 'filter_type',
				'value' => 'level'
			),
		),
		/*array(
			'heading'     => esc_html__( 'Class Trainers', 'noo-timetable' ),
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'class_trainer',
			'admin_label' => false,
			'type'        => 'class_trainers',
			'dependency'  => array(
				'element' => 'filter_type',
				'value' => 'trainer'
			),
		),*/
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'show_filter',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Filter', 'noo-timetable' ),
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
			'dependency'  => array( 'element' => 'source', 'value' => 'class' ),
		),
		// end
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'filter_layout',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Filter Layout', 'noo-timetable' ),
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'List', 'noo-timetable' ) => 'list',
				esc_html__( 'Dropdown', 'noo-timetable' )     => 'dropdown',
			),
			'dependency'  => array( 'element' => 'show_cate_filter', 'value' => array( 'default', 'yes' ) ),
		),
		/*
		 * since at version 2.0.4.7
		 */
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'class_filter_layout',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Filter Layout', 'noo-timetable' ),
			'admin_label' => true,
			'value'       => array(
				esc_html__( 'List', 'noo-timetable' ) => 'list',
				esc_html__( 'Dropdown', 'noo-timetable' )     => 'dropdown',
			),
			'dependency'  => array( 'element' => 'show_filter', 'value' => 'yes' ),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'show_all_tab',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show All Tab', 'noo-timetable' ),
			'admin_label' => true,
			'std'		  => 'yes',
			'value'       => array(
				esc_html__( 'Yes', 'noo-timetable' )  => 'yes',
				esc_html__( 'No', 'noo-timetable' )   => 'no',
			),
			'dependency'  => array( 'element' => 'show_cate_filter', 'value' => array( 'default', 'yes' ) ),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'class_show_all_tab',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show All Tab', 'noo-timetable' ),
			'admin_label' => true,
			'std'		  => 'yes',
			'value'       => array(
				esc_html__( 'Yes', 'noo-timetable' )  => 'yes',
				esc_html__( 'No', 'noo-timetable' )   => 'no',
			),
			'dependency'  => array( 'element' => 'show_filter', 'value' => array( 'yes' ) ),
		),
		// end

		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'type'        => 'multiselect',
			'heading'     => esc_html__( 'Show Weekends', 'noo-timetable' ),
			'param_name'  => 'show_weekends',
			'description' => esc_html__( 'Show Weekends = Select "Saturday and Sunday" or "Saturday" or "Sunday". Hide = "None"','noo-timetable'),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) 	=> 'default',
				esc_html__( 'None', 'noo-timetable' )		=> '',
				esc_html__( 'Saturday', 'noo-timetable' )   => 'sat',
				esc_html__( 'Sunday', 'noo-timetable' )   	=> 'sun',
			),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'general_header_toolbar',
			'type'        => 'dropdown',
			'description' => esc_html__( 'Show forward and backward arrowhead on top of the schedule',
				'noo-timetable' ),
			'heading'     => esc_html__( 'Show Toolbar', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'general_header_day',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Date', 'noo-timetable' ),
			'description' => esc_html__( 'Only Weekly view', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
		),
		array(
			'group'      => esc_html__( 'Extend Options', 'noo-timetable' ),
			'heading'    => esc_html__( 'Custom Default Date', 'noo-timetable' ),
			'param_name' => 'custom_general_default_date',
			'type'       => 'checkbox',
			'value'      => array( '' => 'true' ),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'general_default_date',
			'type'        => 'noo_datetimepicker',
			'format'      => 'Y-m-d',
			'heading'     => esc_html__( 'Default Date', 'noo-timetable' ),
			'description' => esc_html__( 'Leave blank to get the current time', 'noo-timetable' ),
			'admin_label' => true,
			'dependency'  => array( 'element' => 'custom_general_default_date', 'value' => array( 'true' ) ),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'general_navigate_link',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Redirect link for Item', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' )                          => 'default',
				esc_html__( 'Go to Link', 'noo-timetable' )                       => 'internal',
				esc_html__( 'Go to Register link if available', 'noo-timetable' ) => 'external',
				esc_html__( 'Disable Link', 'noo-timetable' )                     => 'disable',
			),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'general_popup',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Class/Event info in Popup', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
		),

		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'general_popup_level',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Class level info in Popup', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
			'dependency'  => array( 'element' => 'general_popup', 'value' => array( 'yes' ) ),
		),

		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'general_popup_excerpt',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show excerpt info in Popup', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
			'dependency'  => array( 'element' => 'general_popup', 'value' => array( 'yes' ) ),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'general_popup_style',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Popup Style', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' )              => 'default',
				esc_html__( 'Fade in and Scale', 'noo-timetable' )    => '1',
				esc_html__( 'Slide in (right)', 'noo-timetable' )     => '2',
				esc_html__( 'Slide in (bottom)', 'noo-timetable' )    => '3',
				esc_html__( 'Newspaper', 'noo-timetable' )            => '4',
				esc_html__( 'Fall', 'noo-timetable' )                 => '5',
				esc_html__( 'Side Fall', 'noo-timetable' )            => '6',
				esc_html__( 'Sticky Up', 'noo-timetable' )            => '7',
				esc_html__( '3D Flip (horizontal)', 'noo-timetable' ) => '8',
				esc_html__( '3D Flip (vertical)', 'noo-timetable' )   => '9',
				esc_html__( '3D Sign', 'noo-timetable' )              => '10',
				esc_html__( 'Super Scaled', 'noo-timetable' )         => '11',
				esc_html__( 'Just Me', 'noo-timetable' )              => '12',
				esc_html__( '3D Slit', 'noo-timetable' )              => '13',
				esc_html__( '3D Rotate Bottom', 'noo-timetable' )     => '14',
				esc_html__( '3D Rotate In Left', 'noo-timetable' )    => '15',
				esc_html__( 'Blur', 'noo-timetable' )                 => '16',
			),
			'dependency'  => array( 'element' => 'general_popup', 'value' => array( 'yes' ) ),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'show_export',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Export', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
		),
		array(
			'group'       => esc_html__( 'Extend Options', 'noo-timetable' ),
			'param_name'  => 'show_category',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Category On Mobile', 'noo-timetable' ),
			'admin_label' => false,
			'std'         => 'no',
			'value'       => array(
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
		),
		/*array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'class_show_category',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Class category by its color', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
			'dependency'  => array( 'element' => 'source', 'value' => array( 'class' ) ),
		),*/
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'class_item_style',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Class Item Style', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' )                      => 'default',
				esc_html__( 'Category Background Color', 'noo-timetable' )               => 'cat_bg_color',
				esc_html__( 'Item Background Image', 'noo-timetable' ) => 'item_bg_image',
				esc_html__( 'Item Background Color', 'noo-timetable' ) => 'item_bg_color',
			),
			'dependency'  => array( 'element' => 'source', 'value' => array( 'class' ) ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'teacher_of_class',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Teacher Of Class', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'No', 'noo-timetable' )  => 'no',
				esc_html__( 'Yes', 'noo-timetable' )               => 'yes'
			),
			'dependency'  => array( 'element' => 'schedule_layout', 'value' => array( 'list' ) ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'address_of_class',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Show Address Of Class', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'No', 'noo-timetable' )  => 'no',
				esc_html__( 'Yes', 'noo-timetable' )               => 'yes'
			),
			'dependency'  => array( 'element' => 'schedule_layout', 'value' => array( 'list' ) ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'class_show_icon',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Class Show Icon', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
			'dependency'  => array( 'element' => 'source', 'value' => array( 'class' ) ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'event_item_style',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Event Item Style', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' )          => 'default',
				esc_html__( 'Background Color', 'noo-timetable' ) => 'background_color',
				esc_html__( 'Background Image', 'noo-timetable' ) => 'background_image',
				esc_html__( 'Background None', 'noo-timetable' )  => 'background_none',
			),
			'dependency'  => array( 'element' => 'source', 'value' => array( 'event' ) ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'event_show_icon',
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Event Show Icon', 'noo-timetable' ),
			'admin_label' => false,
			'value'       => array(
				esc_html__( 'Default', 'noo-timetable' ) => 'default',
				esc_html__( 'Yes', 'noo-timetable' )     => 'yes',
				esc_html__( 'No', 'noo-timetable' )      => 'no',
			),
			'dependency'  => array( 'element' => 'source', 'value' => array( 'event' ) ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'general_header_background',
			'type'        => 'colorpicker',
			'admin_label' => false,
			'heading'     => esc_html__( 'Heading Background Color', 'noo-timetable' ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'general_header_color',
			'type'        => 'colorpicker',
			'admin_label' => false,
			'heading'     => esc_html__( 'Heading Text Color', 'noo-timetable' ),
		),
		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'general_today_column',
			'type'        => 'colorpicker',
			'admin_label' => false,
			'heading'     => esc_html__( 'Today\'s background', 'noo-timetable' ),
		),

		array(
			'group'       => esc_html__( 'Design Options', 'noo-timetable' ),
			'param_name'  => 'general_holiday_background',
			'type'        => 'colorpicker',
			'admin_label' => false,
			'heading'     => esc_html__( 'Holiday background', 'noo-timetable' ),
		),
	),
) );
