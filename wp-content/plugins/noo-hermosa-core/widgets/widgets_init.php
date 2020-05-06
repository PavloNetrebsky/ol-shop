<?php
/**
 * This file initialize widgets area used in this plugin.
 *
 * @package 	Noo_Hermosa_Core
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

require_once NOO_PLUGIN_WIDGETS . '/widgets.php';

/**
 * Register widget
 *
 * @package 	Noo_Hermosa_Core
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_hermosa_core_register_widgets' ) ) :
	
	function noo_hermosa_core_register_widgets() {

		if ( class_exists('Noo__Timetable_Widget_Event_Slider') ) {
			unregister_widget('Noo__Timetable_Widget_Event_Slider');
		}

		register_widget('Noo_Widget_Noo_Event_Info');
		register_widget('Noo_Widget_Noo_Event_Box_Author');
		register_widget('Noo_Widget_Noo_Event_Slider');
		register_widget('Noo_Widget_Noo_Event_Filters');
		register_widget('Noo_Widget_Noo_Event_Box_Map');
		register_widget('Noo_Hermosa_Infomation');
		register_widget('Noo_Hermosa_Latest_Ratting');
		register_widget('Noo_Hermosa_Post_Slider');
		register_widget('Noo_Hermosa_Widget_Categories');
		register_widget('Noo_Hermosa_Widget_Instagram');
		register_widget('Noo_Hermosa_Tabs_Widget');
		register_widget('Noo_Hermosa_Widget_Recent_Posts');
	}

	add_action( 'widgets_init', 'noo_hermosa_core_register_widgets', 13 );

endif;