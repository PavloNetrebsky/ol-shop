<?php
/**
 * Setting for General
 *
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !function_exists( 'noo_timetable_setting_general' ) ) {
	function noo_timetable_setting_general() {
		
		$noo_class_page = NOO_Settings()->get_option( 'noo_class_page', 'classes' );
    	$noo_class_page = $noo_class_page != '' ? $noo_class_page : 'classes';
    	$class_slug = trailingslashit(get_site_url() . '/' . $noo_class_page);

    	$noo_trainer_page = NOO_Settings()->get_option( 'noo_trainer_page', 'trainers' );
    	$noo_trainer_page = $noo_trainer_page != '' ? $noo_trainer_page : 'trainers';
    	$trainer_slug = trailingslashit(get_site_url() . '/' . $noo_trainer_page);

    	$noo_event_page = NOO_Settings()->get_option( 'noo_event_page', 'events' );
    	$noo_event_page = $noo_event_page != '' ? $noo_event_page : 'events';
    	$event_slug = trailingslashit(get_site_url() . '/' . $noo_event_page);

		$options = array(
			// Classes general
			array( 
				'title' => esc_html__( 'Classes', 'noo-timetable' ), 
				'type'  => 'title',
				'desc'  => esc_html__( 'The following options affect how classes are displayed on the frontend.', 'noo-timetable' ),
				'id'    => 'class_options'
			),

			array(
				'title'       => esc_html__( 'Classes URL slug', 'noo-timetable' ),
				'desc'        => esc_html__( 'The slug used for building the classes URL.Your current classes URL is:', 'noo-timetable' ),
				'id'          => 'noo_class_page',
				'default'     => 'classes',
				'placeholder' => 'classes',
				'type'        => 'text',
				'link'        => $class_slug,
			),

			array(
				'title'       => esc_html__( 'Number of classes on a page', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_classes_number_class',
				'default'     => '6',
				'placeholder' => '6',
				'css' 		  => 'width:50px',
				'type'        => 'number'
			),

			array(
				'title'    => esc_html__( 'Class page layout', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'noo_classes_style',
				'default'  => 'grid',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'grid'     => esc_html__( 'Grid', 'noo-timetable' ),
					'list'     => esc_html__( 'List', 'noo-timetable' )
				)
			),

			array(
				'title'    => esc_html__( 'Number of columns', 'noo-timetable' ),
				'desc'     => esc_html__( 'Number of columns shown on the grid interface (Only affect Grid Layout)', 'noo-timetable' ),
				'id'       => 'noo_classes_grid_columns',
				'default'  => '2',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'1'     => esc_html__( 'One', 'noo-timetable' ),
					'2'     => esc_html__( 'Two', 'noo-timetable' ),
					'3'     => esc_html__( 'Three', 'noo-timetable' ),
					'4'     => esc_html__( 'Four', 'noo-timetable' )
				)
			),

			array(
				'title'    => esc_html__( 'Show Color by Class category', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'noo_classes_show_color',
				'default'  => 'yes',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'yes' => esc_html__( 'Yes', 'noo-timetable' ),
					'no'  => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'    => esc_html__( 'Order Classes By', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'noo_classes_orderby',
				'default'  => 'opendate',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'opendate' => esc_html__( 'Open Date', 'noo-timetable' ),
					'adddate'  => esc_html__( 'Class Creation Date', 'noo-timetable' )
				)
			),

			array(
				'title'    => esc_html__( 'Order Direction', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'noo_classes_order',
				'default'  => 'asc',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'asc'  => esc_html__( 'Ascending', 'noo-timetable' ),
					'desc' => esc_html__( 'Descending', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Class excerpt length', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_classes_excerpt_length',
				'default'     => '18',
				'placeholder' => '18',
				'css' 		  => 'width:50px',
				'type'        => 'number'
			),
			array(
				'title'    => esc_html__( 'Show Class Meta', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'show_class_meta',
				'default'  => 'asc',
				'type'     => 'multiselect',
				'css'      => 'width:500px;min-height: 150px;',
				'default'  => array('open_date','next_date','start_time'),
				'options'  => array(
					'class_level'  		=> esc_html__( 'Class Level', 'noo-timetable' ),
					'class_category'  	=> esc_html__( 'Class Category', 'noo-timetable' ),
					'open_date'  		=> esc_html__( 'Open Date', 'noo-timetable' ),
					'next_date' 		=> esc_html__( 'Next Date', 'noo-timetable' ),
					'address' 			=> esc_html__( 'Address', 'noo-timetable' ),
					'start_time' 		=> esc_html__( 'Start Time', 'noo-timetable' ),
					'day_of_week' 		=> esc_html__( 'Day of Week', 'noo-timetable' ),
					'trainer' 			=> esc_html__( 'Trainer', 'noo-timetable' ),
					'number_of_week' 	=> esc_html__( 'Number of Week (only use for Class Single Page)', 'noo-timetable' )
				)
			),

			array( 'type' => 'sectionend', 'id' => 'class_options'),

			// Trainer general
			array( 
				'title' => esc_html__( 'Trainers', 'noo-timetable' ), 
				'type'  => 'title',
				'desc'  => esc_html__( 'The following options affect how trainers are displayed on the frontend.', 'noo-timetable' ),
				'id'    => 'trainer_options'
			),

			array(
				'title'       => esc_html__( 'Trainers URL slug', 'noo-timetable' ),
				'desc'        => esc_html__( 'The slug used for building the trainers URL.Your current trainers URL is:', 'noo-timetable' ),
				'id'          => 'noo_trainer_page',
				'default'     => 'trainers',
				'placeholder' => 'trainers',
				'type'        => 'text',
				'link'        => $trainer_slug,
			),

			array(
				'title'       => esc_html__( 'Number of trainers on a page', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_trainer_num',
				'default'     => '12',
				'placeholder' => '12',
				'css' 		  => 'width:50px',
				'type'        => 'number'
			),

			// array(
			// 	'title'    => esc_html__( 'Trainer page layout', 'noo-timetable' ),
			// 	'desc'     => '',
			// 	'id'       => 'noo_trainer_style',
			// 	'default'  => 'grid',
			// 	'type'     => 'select',
			// 	'css'      => 'width:150px;',
			// 	'options'  => array(
			// 		'grid'     => esc_html__( 'Grid', 'noo-timetable' ),
			// 		'list'     => esc_html__( 'List', 'noo-timetable' )
			// 	)
			// ),

			array(
				'title'    => esc_html__( 'Number of columns', 'noo-timetable' ),
				'desc'     => esc_html__( 'Number of columns shown on the grid interface (Only affect Grid Layout)', 'noo-timetable' ),
				'id'       => 'noo_trainer_columns',
				'default'  => '4',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'1'     => esc_html__( 'One', 'noo-timetable' ),
					'2'     => esc_html__( 'Two', 'noo-timetable' ),
					'3'     => esc_html__( 'Three', 'noo-timetable' ),
					'4'     => esc_html__( 'Four', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Trainer excerpt length', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_trainer_excerpt_length',
				'default'     => '28',
				'placeholder' => '28',
				'css' 		  => 'width:50px',
				'type'        => 'number'
			),

			array( 'type' => 'sectionend', 'id' => 'trainer_options'),

			// Event general
			array( 
				'title' => esc_html__( 'Events', 'noo-timetable' ), 
				'type'  => 'title',
				'desc'  => esc_html__( 'The following options affect how events are displayed on the frontend.', 'noo-timetable' ),
				'id'    => 'event_options'
			),

			array(
				'title'       => esc_html__( 'Events URL slug', 'noo-timetable' ),
				'desc'        => esc_html__( 'The slug used for building the events URL.Your current events URL is:', 'noo-timetable' ),
				'id'          => 'noo_event_page',
				'default'     => 'events',
				'placeholder' => 'events',
				'type'        => 'text',
				'link'        => $event_slug,
			),

			array(
				'title'       => esc_html__( 'Number of events on a page', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_event_num',
				'default'     => '10',
				'placeholder' => '10',
				'css' 		  => 'width:50px',
				'type'        => 'number'
			),

			array(
				'title'    => esc_html__( 'Event page layout', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'noo_event_default_layout',
				'default'  => 'grid',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'grid'     => esc_html__( 'Grid', 'noo-timetable' ),
					'list'     => esc_html__( 'List', 'noo-timetable' )
				)
			),

			array(
				'title'    => esc_html__( 'Number of columns', 'noo-timetable' ),
				'desc'     => esc_html__( 'Number of columns shown on the grid interface (Only affect Grid Layout)', 'noo-timetable' ),
				'id'       => 'noo_event_grid_column',
				'default'  => '2',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'1'     => esc_html__( 'One', 'noo-timetable' ),
					'2'     => esc_html__( 'Two', 'noo-timetable' ),
					'3'     => esc_html__( 'Three', 'noo-timetable' ),
					'4'     => esc_html__( 'Four', 'noo-timetable' )
				)
			),

			array(
				'title'    => esc_html__( 'Show Color by Event background', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'noo_event_show_color',
				'default'  => 'yes',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'yes' => esc_html__( 'Yes', 'noo-timetable' ),
					'no'  => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'    => esc_html__( 'Order Events By', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'noo_event_orderby',
				'default'  => 'default',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'default'   => esc_html__( 'Default', 'noo-timetable' ),
					// 'next_date' => esc_html__( 'Current Date', 'noo-timetable' ),
					'start_date' => esc_html__( 'Event Date', 'noo-timetable' ),
					'latest'    => esc_html__( 'Recent Post Date First', 'noo-timetable' ),
					'oldest'    => esc_html__( 'Older Post Date First', 'noo-timetable' ),
					'alphabet'  => esc_html__( 'Title Alphabet', 'noo-timetable' ),
					'ralphabet' => esc_html__( 'Title Reversed Alphabet', 'noo-timetable' )
				)
			),

			array(
				'title'    => esc_html__( 'Hide past events', 'noo-timetable' ),
				'desc'     => '',
				'id'       => 'noo_event_hide_past',
				'default'  => 'no',
				'type'     => 'select',
				'css'      => 'width:150px;',
				'options'  => array(
					'yes' => esc_html__( 'Yes', 'noo-timetable' ),
					'no'  => esc_html__( 'No', 'noo-timetable' )
				)
			),

			array(
				'title'       => esc_html__( 'Event excerpt length', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'noo_event_excerpt_length',
				'default'     => '18',
				'placeholder' => '18',
				'css' 		  => 'width:50px',
				'type'        => 'number'
			),

			array( 'type' => 'sectionend', 'id' => 'event_options'),

			// Google Maps API Options
			array( 
				'title' => esc_html__( 'Google Maps API Key', 'noo-timetable' ), 
				'type'  => 'title',
				'desc'  => __( 'To get started using the Google Maps JavaScript API, click the link below <a href="https://support.nootheme.com/knowledge-base/get-google-maps-api/" target="_blank">Get a key</a>', 'noo-timetable' ),
				'id'    => 'google_map_api_option'
			),

			array(
				'title'       => esc_html__( 'Google Maps API Key', 'noo-timetable' ),
				'desc'        => esc_html__( 'Input an API key so that Map on single event page can be displayed well', 'noo-timetable' ),
				'id'          => 'noo_google_map_api_key',
				'default'     => '',
				'placeholder' => '',
				'css'         => 'width:450px',
				'type'        => 'text',
			),
			array( 'type' => 'sectionend', 'id' => 'google_map_api_option'),


			// Google Maps latitude and longitude
			array( 
				'title' => esc_html__( 'Google Maps Latitude and Longitude', 'noo-timetable' ), 
				'type'  => 'title',
				'desc'  => __( 'Enter value of latitude and longitude', 'noo-timetable' ),
				'id'    => 'google_map_latlng_options'
			),

			array(
				'title'       => esc_html__( 'Latitude', 'noo-timetable' ),
				'desc'        => esc_html__( 'Default: 51.508742', 'noo-timetable' ),
				'id'          => 'noo_google_map_latitude',
				'default'     => '',
				'placeholder' => '',
				'css'         => 'width:150px',
				'type'        => 'text',
			),

			array(
				'title'       => esc_html__( 'Longitude', 'noo-timetable' ),
				'desc'        => esc_html__( 'Default: -0.120850', 'noo-timetable' ),
				'id'          => 'noo_google_map_longitude',
				'default'     => '',
				'placeholder' => '',
				'css'         => 'width:150px',
				'type'        => 'text',
			),

			array(
				'title'       => esc_html__( 'Zoom', 'noo-timetable' ),
				'desc'        => esc_html__( 'Default: 11', 'noo-timetable' ),
				'id'          => 'noo_google_map_zoom',
				'default'     => '',
				'placeholder' => '',
				'css'         => 'width:150px',
				'type'        => 'text',
			),
			
			array( 'type' => 'sectionend', 'id' => 'google_map_latlng_options'),
		);

		return apply_filters( 'noo_timetable_setting_general', $options);
	}
}