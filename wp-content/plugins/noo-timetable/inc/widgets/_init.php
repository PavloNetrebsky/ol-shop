<?php
/**
 * Init Widget
 *
 * @author 		NooTheme
 * @category    Widgets
 * @package 	NooTimetable/Widgets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( !class_exists('Noo__Timetable__Widget_Init') ) {

	/**
	 * Register Noo Widget Init
	 */

    class Noo__Timetable__Widget_Init {

    	public function __construct() {
    		add_action( 'widgets_init', array( $this, 'widgets_init' ), 11 );
    	}

    	public function widgets_init() {

    		if ( class_exists( 'Noo__Timetable__Class' ) ) {

    			/**
				 * Register Class Sidebar
				 */
			    register_sidebar(
			        array(
			            'name'          => esc_html__( 'Class Sidebar', 'noo-timetable' ),
			            'id'            => 'noo-class-sidebar', 
			            'description'   => esc_html__( 'Recommend use in Class page.', 'noo-timetable' ),
			            'before_widget' => '<div id="%1$s" class="widget %2$s">', 
			            'after_widget'  => '</div>', 
			            'before_title'  => '<h4 class="widget-title">', 
			            'after_title'   => '</h4>'
			        )
			    );

			    /**
				 * Register Class Widget
				 */
			    register_widget('Noo__Timetable_Widget_Popular_Class');
			    register_widget('Noo__Timetable_Widget_Popular_Class_Coming');
			}

		    if ( class_exists( 'Noo__Timetable__Event' ) ) {

				/**
				 * Register Event Sidebar
				 */
				register_sidebar(
					array(
						'name'          => esc_html__( 'Event Sidebar', 'noo-timetable' ),
						'id'            => 'noo-event-sidebar', 
						'description'   => esc_html__( 'Recommend use in Event page.', 'noo-timetable' ),
						'before_widget' => '<div id="%1$s" class="widget %2$s">', 
						'after_widget'  => '</div>', 
						'before_title'  => '<h4 class="widget-title">', 
						'after_title'   => '</h4>'
					)
				);

				/**
				 * Register Event Widget
				 */
				register_widget('Noo__Timetable_Widget_Event_Slider');
				// register_widget('Noo__Timetable_Widget_Event_Filters');

			}
			if ( class_exists( 'Noo__Timetable__Trainer' ) ) {

				/**
				 * Register Event Sidebar
				 */
				register_sidebar(
					array(
						'name'          => esc_html__( 'Trainer Sidebar', 'noo-timetable' ),
						'id'            => 'noo-trainer-sidebar', 
						'description'   => esc_html__( 'Recommend use in Trainer page.', 'noo-timetable' ),
						'before_widget' => '<div id="%1$s" class="widget %2$s">', 
						'after_widget'  => '</div>', 
						'before_title'  => '<h4 class="widget-title">', 
						'after_title'   => '</h4>'
					)
				);

			}
		}
    }

    new Noo__Timetable__Widget_Init();
}

