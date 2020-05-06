<?php

if ( !function_exists('noo_hermosa_update_options_timetable') ) {

	function noo_hermosa_update_options_timetable() {

		if( get_option( 'has_update_options_timetable' ) ) {
            return;
        }
        update_option( 'has_update_options_timetable', 1 );

        noo_hermosa_update_category_color();
        noo_hermosa_update_settings_general();
        noo_hermosa_update_event_datetime();
        noo_hermosa_update_event_sidebar();
		
		flush_rewrite_rules();

	}

	add_action( 'init', 'noo_hermosa_update_options_timetable' );

}

if ( !function_exists('noo_hermosa_update_category_color') ) {
	function noo_hermosa_update_category_color() {
		$args = array(
		    'posts_per_page' => '-1',
		    'post_type'      => 'noo_class'
		);

		$classes = get_posts($args);
		foreach ( $classes as $cl ) {
			$post_category = get_the_terms( $cl->ID, 'class_category' );

			if ( !empty($post_category) ) {

				$post_category = reset($post_category);
				$category_parent = $post_category->parent;

				if( !empty($category_parent) ) :
				    $color = noo_hermosa_get_term_meta( $post_category->parent, 'category_color', '' );
				else:
				    $color = noo_hermosa_get_term_meta( $post_category->term_id, 'category_color', '#fe6367' );
				endif;
			}

			add_term_meta( $post_category->term_id, 'category_color', $color, true );
		}
	}
}

if ( !function_exists('noo_hermosa_update_settings_general') ) {
	function noo_hermosa_update_settings_general() {

		$class_page = noo_hermosa_get_option('noo_class_page', '');
		$class_slug = !empty($class_page) ? get_post( $class_page )->post_name : 'classes';

		$event_page = noo_hermosa_get_option('noo_event_page', '');
        $event_slug = !empty($event_page) ? get_post( $event_page )->post_name : 'events';

        $trainer_page = noo_hermosa_get_option('noo_trainer_page', '');
		$trainer_slug = !empty($trainer_page) ? get_post( $trainer_page )->post_name : 'trainers';

		$timetable_settings = array(
			'noo_class_page'           => $class_slug,
			'noo_classes_number_class' => noo_hermosa_get_option('noo_classes_number_class', 6),
			'noo_classes_style'        => noo_hermosa_get_option('noo_classes_style', 'grid'),
			'noo_classes_grid_columns' => noo_hermosa_get_option('noo_classes_grid_columns', 2),
			'noo_classes_orderby'      => noo_hermosa_get_option('noo_classes_orderby', 'opendate'),
			'noo_classes_order'        => noo_hermosa_get_option('noo_classes_order', 'asc'),
			
			'noo_trainer_page'         => $trainer_slug,
			'noo_trainer_num'          => noo_hermosa_get_option('noo_trainer_num', 12),
			// 'noo_trainer_style'     => 'noo_trainer_style',
			'noo_trainer_columns'      => noo_hermosa_get_option('noo_trainer_columns', 4),
			
			'noo_event_page'           => $event_slug,
			'noo_event_num'            => noo_hermosa_get_option( 'noo_event_num', '10' ),
			'noo_event_default_layout' => noo_hermosa_get_option( 'noo_event_default_layout', 'grid' ),
			// 'noo_event_grid_column' => 'noo_event_grid_column',
		);

		update_option( 'timetable_settings', $timetable_settings );
	}
}

if ( !function_exists('noo_hermosa_update_event_datetime') ) {
	function noo_hermosa_update_event_datetime() {
		$args = array(
		    'posts_per_page' => '-1',
		    'post_type'      => 'noo_event'
		);

		$events = get_posts($args);

		foreach ($events as $ev) {
			$start_date      = Noo__Timetable__Event::get_start_date( $ev->ID, 'Y-m-d' );
            $start_time      = Noo__Timetable__Event::get_start_date( $ev->ID, 'H:i' );
            $end_date        = Noo__Timetable__Event::get_end_date( $ev->ID, 'Y-m-d' );
            $end_time        = Noo__Timetable__Event::get_end_date( $ev->ID, 'H:i' );

            update_post_meta( $ev->ID, '_noo_event_start_time', strtotime($start_time) );
            update_post_meta( $ev->ID, '_noo_event_end_time', strtotime($end_time) );
		}
	}
}

if ( !function_exists('noo_hermosa_update_event_sidebar') ) {
	function noo_hermosa_update_event_sidebar() {
		$event_sidebar = noo_hermosa_get_option( 'noo_event_sidebar', '' );

		if ( 'sidebar-event' === $event_sidebar ) {
			set_theme_mod('noo_event_sidebar', 'noo-event-sidebar');
		}

		$class_sidebar = noo_hermosa_get_option( 'noo_class_sidebar', '' );

		if ( 'sidebar-class' === $class_sidebar ) {
			set_theme_mod('noo_class_sidebar', 'noo-class-sidebar');
		}
	}
}