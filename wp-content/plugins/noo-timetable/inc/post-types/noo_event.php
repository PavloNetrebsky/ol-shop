<?php
/**
 * Post Types Event
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

if ( !class_exists( 'Noo__Timetable__Event' ) ) {

	class Noo__Timetable__Event{
		/**
		 * The array of templates that this plugin using.
		 */
		protected $noo_event;

		/**
		 * Initializes the plugin by setting filters and administration functions.
		 */
		public function __construct() {

			/**
			 * VAR
			 */
			$this->noo_event = array(
				'slug'                    => 'noo_event',
				'slug_cat'                => 'event_category',
				'rewrite_slug'            => 'events',
				'rewrite_slug_cat'        => 'event-category',
				'slug_organizers'         => 'event_organizers',
				'rewrite_slug_organizers' => 'event-organizers',
				'icon'                    => 'dashicons-megaphone',
				'prefix'                  => '_noo_event'
			);

			/**
			 * Load action/filter
			 */
			add_action( 'init', array( &$this, 'register_post_type' ) );
			add_filter( 'template_include', array( &$this, 'template_loader' ) );

			add_action( 'wp_ajax_noo_event_filter', array(&$this, 'event_filter') );
			add_action( 'wp_ajax_nopriv_noo_event_filter', array(&$this, 'event_filter') );

			add_action( 'wp_ajax_load_event', array( &$this, 'load_event' ) );
			add_action( 'wp_ajax_nopriv_load_event', array( &$this, 'load_event' ) );

			add_action( 'wp_ajax_calendar_mobile', array( &$this, 'ajax_calendar_mobile' ) );
			add_action( 'wp_ajax_nopriv_calendar_mobile', array( &$this, 'ajax_calendar_mobile' ) );

			add_action( 'wp_ajax_noo_class_event_filter', array( &$this, 'noo_class_event_filter' ) );
			add_action( 'wp_ajax_nopriv_noo_class_event_filter', array( &$this, 'noo_class_event_filter' ) );

			add_action( 'noo-event-update-next-day', array( $this, 'update_next_day' ) );
			add_action( 'save_post', array( $this, 'setup_event_recurrence' ) );
			add_action( 'init', array( &$this, 'setup_events' ), 1 );

			if ( ! is_admin() ) :
				add_action( 'pre_get_posts', array( &$this, 'pre_get_posts' ) );
			endif;

			if ( is_admin() ) :
				add_action( 'customize_save', array($this,'customizer_set_transients_before_save') );
				add_action( 'customize_save_after', array($this,'customizer_set_transients_after_save') );
				$this->_feature_event();

			endif;

		}

		public static function get_coming_event_ids() {
			global $wpdb;
			$events = (array) $wpdb->get_results(
				"SELECT $wpdb->posts.ID, $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_value
                    FROM $wpdb->posts
                    LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE $wpdb->posts.post_status = 'publish'
                    AND $wpdb->posts.post_type = 'noo_event'
                    AND ($wpdb->postmeta.meta_key = '_noo_event_start_date' OR $wpdb->postmeta.meta_key = '_noo_event_start_time' OR $wpdb->postmeta.meta_key = '_recurrence') " );

			foreach ($events as $k => $v) {
				$newarr[$v->ID][$v->meta_key] = $v->meta_value;
			}

			$rearr = array();
			foreach ($newarr as $eventID => $event) {
				if (!isset($event['_noo_event_start_date']) || $event['_noo_event_start_date'] == '')
					continue;

				$start_date = strtotime(date('Y/m/d', $event['_noo_event_start_date']) . ' ' . date('H:i:s', $event['_noo_event_start_time']));
				$event_repeat = array();
				if (isset($event['_recurrence']) && $event['_recurrence'] != '') {
					$args = self::get_param_recurrence( $event['_recurrence'] );
					$ppevent = self::get_prepare_event( $eventID );
					$event_repeat  = self::get_repeat_events( $ppevent, $args );
					$end_date = end($event_repeat);
					$end_date = str_replace('T', ' ', $end_date['start']);
					$end_date = strtotime($end_date);
				} else {
					$end_date = $start_date;
				}
				if ( noo_timetable_time_now() <= $end_date  ) {
					$rearr[] = $eventID;
				}
			}
			return $rearr;
		}

		public function setup_event_recurrence( $post_id ) {
			if ( 'noo_event' == get_post_type( $post_id ) ) {
				// Clear old schedule
				wp_clear_scheduled_hook( 'noo-event-update-next-day', array( $post_id ) );
				if ( 'publish' == get_post_status( $post_id ) ) {
					$this->update_next_day( $post_id );
				}
			}
		}

		public static function _get_next_date( $args = array() ) {
			$startDate   = $args['start_date'];
			$time_end    = $args['end_date'];
			$eventRepeat = $args['event_repeat'];
			$nextDate = '';
			$now = noo_timetable_time_now();
			if ( count($eventRepeat) > 0 ) {
				foreach ( $eventRepeat as $event ) {
					$time = str_replace('T', ' ', $event['start']);
					$time = strtotime($time);
					if ( $now < $time ) {
						if( $now > $time_end ){
							$nextDate = $time;
						}
						break;
					}
					$time_end = str_replace('T', ' ', $event['end']);
					$time_end = strtotime($time_end);
				}
			} else {
				if ( $now < $startDate ) {
					$nextDate = $startDate;
				}
			}
			return $nextDate;
		}

		public static function _get_next_date_end( $args = array() ) {
			$endDate     = $args['end_date'];
			$eventRepeat = $args['event_repeat'];
			$nextDateEnd = '';
			$now = noo_timetable_time_now();
			if ( count($eventRepeat) > 0 ) {
				foreach ( $eventRepeat as $event ) {
					$time = str_replace('T', ' ', $event['end']);
					$time = strtotime($time);
					if ( $now < $time ) {
						$nextDateEnd = $time;
						break;
					}
				}
			} else {
				if ( $now < $endDate ) {
					$nextDateEnd = $endDate;
				}
			}
			return $nextDateEnd;
		}

		public function update_next_day( $eventID ) {
			// Calculate and update next day.
			$start_date   = noo_timetable_get_post_meta( $eventID, "_noo_event_start_date", '' );
			$end_date     = noo_timetable_get_post_meta( $eventID, "_noo_event_end_date", '' );
			$recurrence   = noo_timetable_get_post_meta( $eventID, "_recurrence", '' );
			$event_repeat = array();
			if (isset($recurrence) && $recurrence != '' && $start_date != '' && $end_date != '' ) {

				$args         = self::get_param_recurrence( $recurrence );
				$ppevent      = self::get_prepare_event( $eventID );
				$event_repeat = self::get_repeat_events( $ppevent, $args );

				$start_date_time = strtotime( date( 'Y/m/d', $start_date) . ' ' . date ( 'H:i:m', noo_timetable_get_post_meta( $eventID, "_noo_event_start_time", '' ) ) );
				$end_date_time   = strtotime( date( 'Y/m/d', noo_timetable_get_post_meta( $eventID, "_noo_event_end_date", '' )) . ' ' . date ( 'H:i:m', noo_timetable_get_post_meta( $eventID, "_noo_event_end_time", '' ) ) );
				$date_distance   = $end_date_time - $start_date_time;

			}

			$next_date = self::_get_next_date(array(
				'start_date'   => $start_date,
				'end_date'     => $end_date,
				'event_repeat' => $event_repeat
			));
			if ( $next_date != '' ){
				update_post_meta( $eventID, '_next_date', $next_date );
				wp_schedule_single_event( $next_date, 'noo-event-update-next-day', array( $eventID ) );
			}

			$next_date_end = self::_get_next_date_end(array(
				'end_date'   => $end_date,
				'event_repeat' => $event_repeat
			));

			if ( $next_date_end != '' ){
				update_post_meta( $eventID, '_next_date_end', $next_date_end );
				wp_schedule_single_event( $next_date_end, 'noo-event-update-next-day', array( $eventID ) );
			}
		}

		public function setup_events() {
			// Run only one time
			if( get_option( 'has_setup_events' ) )
				return;
			update_option( 'has_setup_events', 1 );

			global $wpdb;
			$loops = (array) $wpdb->get_results(
				"SELECT $wpdb->posts.ID, $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_value
                    FROM $wpdb->posts
                    LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE $wpdb->posts.post_status = 'publish'
                    AND $wpdb->posts.post_type = 'noo_event'
                    AND ($wpdb->postmeta.meta_key = '_noo_event_start_date' OR $wpdb->postmeta.meta_key = '_noo_event_end_date' OR $wpdb->postmeta.meta_key = '_recurrence') " );

			$newarr = array();
			if ( $loops ){
				foreach ($loops as $k => $v) {
					$newarr[$v->ID][$v->meta_key] = $v->meta_value;
				}
			}

			if ( !empty( $newarr ) ){
				foreach ($newarr as $eventID => $event) {

					if (!isset($event['_noo_event_start_date']) || $event['_noo_event_start_date'] == '')
						continue;

					$start_date   = $event['_noo_event_start_date'];
					$end_date     = $event['_noo_event_end_date'];
					$event_repeat = array();

					if (isset($event['_recurrence']) && $event['_recurrence'] != '') {

						$args              = self::get_param_recurrence( $event['_recurrence'] );
						$ppevent           = self::get_prepare_event( $eventID );
						$event_repeat      = self::get_repeat_events( $ppevent, $args );
						$event_repeat_date = end($event_repeat);

						$start_date_next   = str_replace('T', ' ', $event_repeat_date['start']);
						$start_date_next   = strtotime($start_date_next);

						$end_date_next     = str_replace('T', ' ', $event_repeat_date['end']);
						$end_date_next     = strtotime($end_date_next);

					} else {

						$start_date_next = $start_date;
						$end_date_next   = $end_date;

					}

					$next_date     = noo_timetable_get_post_meta( $eventID, '_next_date', '' );
					$next_date_end = noo_timetable_get_post_meta( $eventID, '_next_date_end', '' );
					$now           = noo_timetable_time_now();

					if ( $next_date == '' || ( $now >= $next_date && $now >= $next_date_end && $now <= $start_date_next ) ) {
						$next_date = self::_get_next_date(array(
							'start_date'   => $start_date,
							'end_date'     => $end_date,
							'event_repeat' => $event_repeat
						));
						if ( $next_date != '' ){
							update_post_meta( $eventID, '_next_date', $next_date );
							wp_schedule_single_event( $next_date, 'noo-event-update-next-day', array( $eventID ) );
						}
						else {
							update_post_meta( $eventID, '_next_date', $start_date_next );
						}
					}

					if ( $next_date_end == '' || ( $now >= $next_date_end && $now <= $end_date_next ) ) {
						$next_date_end = self::_get_next_date_end(array(
							'end_date'     => $end_date,
							'event_repeat' => $event_repeat
						));
						if ( $next_date_end != '' ){
							update_post_meta( $eventID, '_next_date_end', $next_date_end );
							wp_schedule_single_event( $next_date_end, 'noo-event-update-next-day', array( $eventID ) );
						}
						else {
							update_post_meta( $eventID, '_next_date_end', $end_date );
						}
					}

				}
			}
		}

		/**
		 * Reset query to custom post type
		 */
		public function pre_get_posts( $query ) {
			if (
				!is_admin() &&
				$query->is_post_type_archive( 'noo_event' ) &&
				$query->is_main_query()
			) {
				$orderby = NOO_Settings()->get_option('noo_event_orderby', 'default');
				$order = 'DESC';
				switch ( $orderby ) {
					// case 'next_date':
					// 	$orderby  = 'meta_value_num';
					// 	$order    = 'ASC';
					// 	$orderkey = '_next_date';
					// 	break;
					case 'start_date':
						$orderby  = 'meta_value_num';
						$orderkey = '_noo_event_start_date';
						break;
					case 'latest':
						$orderby = 'date';
						break;
					case 'oldest':
						$orderby = 'date';
						$order = 'ASC';
						break;
					case 'alphabet':
						$orderby = 'title';
						$order = 'ASC';
						break;
					case 'ralphabet':
						$orderby = 'title';
						break;
					default:
						$orderby = 'default';
						break;
				}
				$query->set( 'orderby', $orderby );
				$query->set( 'order', $order );
				if ('meta_value_num' == $orderby) {
					$query->set('meta_key', $orderkey);
				}

				$hide_past = NOO_Settings()->get_option('noo_event_hide_past', 'no');
				if ( $hide_past == 'yes' ) {
					$meta_query = array(
						'relation' => 'OR',
						array(
							'relation' => 'AND',
							array(
								'key'     => '_recurrence',
								'value'   => '',
								'compare' => '!='
							),
							array(
								'key'     => '_next_date_end',
								'value'   => noo_timetable_time_now(),
								'compare' => '>='
							)
						),
						array(
							'relation' => 'AND',
							array(
								'key'     => '_noo_event_end_date',
								'value'   => strtotime( date( 'Y-m-d', noo_timetable_time_now() ) ),
								'compare' => '>='
							),
							array(
								'key'     => '_noo_event_end_time',
								'value'   => strtotime( date( 'H:i:s', noo_timetable_time_now() ) ),
								'compare' => '>='
							)
						)
					);
					$query->set( 'meta_query', $meta_query );
				}

				$query->set( 'posts_per_page', NOO_Settings()->get_option( 'noo_event_num', '10' ) );
			}
		}


		/**
		 * Loader template
		 */
		public function template_loader( $template ) {

			$find = array();
			$file = '';

			if ( is_single() && get_post_type() == 'noo_event' ) {

				$file   = 'single-noo_event.php';
				$find[] = $file;
				$find[] = Noo__Timetable__Main::template_path() . $file;

			} elseif ( is_post_type_archive( 'noo_event' ) || is_tax( 'event_category' ) ) {

				$file   = 'archive-noo_event.php';
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

		/**
		 * Enqueue script
		 */
		public function enqueue_scripts( $hook ) {

			global $post;

			if ( $hook == 'post-new.php' || $hook == 'post.php' ) {

				if ( 'noo_event' === $post->post_type ) {

					wp_enqueue_style( 'noo-event', NOO_PLUGIN_ASSETS_URI . '/css/noo_event.css');

					$latitude = '40.714398';
					$lat = get_post_meta( $post->ID, $this->noo_event['prefix'] . '_gmap_latitude', true );
					if( !empty( $lat ) )
						$latitude = $lat;

					$longitude = '-74.005279';
					$long = get_post_meta( $post->ID, $this->noo_event['prefix'] . '_gmap_longitude', true );
					if( !empty( $long ) )
						$longitude = $long;

					$nooEventMap = array(
						'latitude'          => $latitude,
						'longitude'         => $longitude,
						'localtion_disable' => false
					);
					wp_register_script(
						'google-map',
						'http' . ( is_ssl() ? 's' : '' ) . '://maps.googleapis.com/maps/api/js?v=3.exp&sensor=true&libraries=places',
						array('jquery'),
						'1.0',
						false
					);
					wp_register_script(
						'noo-event',
						NOO_PLUGIN_ASSETS_URI . '/js/noo_event.js',
						array( 'jquery', 'google-map' ),
						null,
						true
					);
					wp_localize_script( 'noo-event', 'nooEventMap', $nooEventMap );
					wp_enqueue_script( 'noo-event' );
				}
			}
		}

		/**
		 * Register post type: noo_event
		 * Register taxonomy: event_category
		 *

		 */
		public function register_post_type() {

			/**
			 * Creating post type: noo_event
			 * @var array
			 */
			// Clear transient
			if ( get_transient( 'noo_event_slug_before' ) != get_transient( 'noo_event_slug_after' ) ) {
				flush_rewrite_rules();
				delete_transient( 'noo_event_slug_before' );
				delete_transient( 'noo_event_slug_after' );
			}

			$event_slug = NOO_Settings()->get_option('noo_event_page', 'events');
			// $event_slug = !empty($event_page) ? get_post( $event_page )->post_name : 'events';

			$team_labels = array(
				'name'               => esc_html__( 'Events', 'noo-timetable' ),
				'singular_name'      => esc_html__( 'Events', 'noo-timetable' ),
				'menu_name'          => esc_html__( 'Events', 'noo-timetable' ),
				'add_new'            => esc_html__( 'Add New Event', 'noo-timetable' ),
				'add_new_item'       => esc_html__( 'Add New Event Item', 'noo-timetable' ),
				'edit_item'          => esc_html__( 'Edit Event Item', 'noo-timetable' ),
				'new_item'           => esc_html__( 'Add New Event Item', 'noo-timetable' ),
				'view_item'          => esc_html__( 'View Event', 'noo-timetable' ),
				'search_items'       => esc_html__( 'Search Event', 'noo-timetable' ),
				'not_found'          => esc_html__( 'No Event items found', 'noo-timetable' ),
				'not_found_in_trash' => esc_html__( 'No Event items found in trash', 'noo-timetable' ),
				'parent_item_colon'  => ''
			);

			// Options
			$team_args = array(
				'labels'             => $team_labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'menu_position'      => 30,
				'menu_icon'          => $this->noo_event['icon'],
				'capability_type'    => 'post',
				'hierarchical'       => false,
				'supports'           => array(
					'title',
					'editor',
					'thumbnail',
					'excerpt',
					'comments'
				),
				'has_archive'        => true,
				'rewrite'            => array(
					'slug'       => $event_slug,
					'with_front' => true
				)
			);

			register_post_type( $this->noo_event['slug'], $team_args );

			/**
			 * Creating taxomony: event_category
			 * @var array
			 */
			$category_labels = array(
				'name'                       => esc_html__( 'Event Categories', 'noo-timetable' ),
				'singular_name'              => esc_html__( 'Event Category', 'noo-timetable' ),
				'menu_name'                  => esc_html__( 'Event Categories', 'noo-timetable' ),
				'all_items'                  => esc_html__( 'All Event Categories', 'noo-timetable' ),
				'edit_item'                  => esc_html__( 'Edit Event Category', 'noo-timetable' ),
				'view_item'                  => esc_html__( 'View Event Category', 'noo-timetable' ),
				'update_item'                => esc_html__( 'Update Event Category', 'noo-timetable' ),
				'add_new_item'               => esc_html__( 'Add New Event Category', 'noo-timetable' ),
				'new_item_name'              => esc_html__( 'New Event Category Name', 'noo-timetable' ),
				'parent_item'                => esc_html__( 'Parent Event Category', 'noo-timetable' ),
				'parent_item_colon'          => esc_html__( 'Parent Event Category:', 'noo-timetable' ),
				'search_items'               => esc_html__( 'Search Event Categories', 'noo-timetable' ),
				'popular_items'              => esc_html__( 'Popular Event Categories', 'noo-timetable' ),
				'separate_items_with_commas' => esc_html__( 'Separate Event Categories with commas', 'noo-timetable' ),
				'add_or_remove_items'        => esc_html__( 'Add or remove Event Categories', 'noo-timetable' ),
				'choose_from_most_used'      => esc_html__( 'Choose from the most used Event Categories', 'noo-timetable' ),
				'not_found'                  => esc_html__(  'No Event Categories found', 'noo-timetable' ),
			);

			$category_args = array(
				'labels'            => $category_labels,
				'public'            => true,
				'show_ui'           => true,
				'show_in_nav_menus' => false,
				'show_tagcloud'     => false,
				'show_admin_column' => true,
				'hierarchical'      => true,
				'query_var'         => true,
				'rewrite'           => array(
					'slug'       =>  $this->noo_event['rewrite_slug_cat'],
					'with_front' => true
				) ,
			);

			register_taxonomy( $this->noo_event['slug_cat'], array( $this->noo_event['slug'] ), $category_args );

			/**
			 * Creating post_type: event_organizers
			 * @var array
			 */
			register_post_type( $this->noo_event['slug_organizers'],
				array(
					'labels'             => array(
						'name'               => esc_html__( 'Organizers', 'noo-timetable' ),
						'singular_name'      => esc_html__( 'Organizer', 'noo-timetable' ),
						'menu_name'          => esc_html__( 'Organizers', 'noo-timetable' ),
						'add_new'            => esc_html__( 'Add New Organizer', 'noo-timetable' ),
						'add_new_item'       => esc_html__( 'Add New Organizer Item', 'noo-timetable' ),
						'edit_item'          => esc_html__( 'Edit Organizer Item', 'noo-timetable' ),
						'new_item'           => esc_html__( 'Add New Organizer Item', 'noo-timetable' ),
						'view_item'          => esc_html__( 'View Organizer', 'noo-timetable' ),
						'search_items'       => esc_html__( 'Search Organizer', 'noo-timetable' ),
						'not_found'          => esc_html__( 'No Organizer items found', 'noo-timetable' ),
						'not_found_in_trash' => esc_html__( 'No Organizer items found in trash', 'noo-timetable' ),
					),
					'show_in_menu'       => 'edit.php?post_type=noo_event',
					'public'             => true,
					'publicly_queryable' => false,
					'menu_position'      => 30,
					'has_archive'        => true,
					'supports'           => array( 'title' ),
					'rewrite'            => array( 'slug' => $this->noo_event['rewrite_slug_organizers'] ),
				)
			);

		}

		public function customizer_set_transients_before_save() {
			set_transient( 'noo_event_slug_before', NOO_Settings()->get_option( 'noo_event_page', 'events' ), 60 );
		}

		public function customizer_set_transients_after_save() {
			set_transient( 'noo_event_slug_after', NOO_Settings()->get_option( 'noo_event_page', 'events' ), 60 );
		}

		/**
		 * Register metabox to post type noo_event
		 *
		 */
		public function register_metabox() {

			/**
			 * VAR
			 * @var string
			 */
			$helper = new Noo__Timetable_Meta_Boxes_Helper( $this->noo_event['prefix'], array(
				'page' => $this->noo_event['slug_organizers']
			));

			/**
			 * Creating box: ORGANIZERS
			 * @var array
			 */
			$meta_box = array(
				'id' => $this->noo_event['prefix'] . '_box_organizers',
				'title' => esc_html__( 'Information', 'noo-timetable' ),
				'fields' => array(

					array(
						'id' => $this->noo_event['prefix'] .'_author',
						'label' => esc_html__( 'Author', 'noo-timetable' ),
						'type' => 'text'
					),
					array(
						'id' => $this->noo_event['prefix'] .'_avatar',
						'label' => esc_html__( 'Avatar', 'noo-timetable' ),
						'type' => 'image'
					),

					array(
						'id'    => $this->noo_event['prefix'] .'_phone',
						'label' => esc_html__( 'Phone', 'noo-timetable' ),
						'type'  => 'text'
					),
					array(
						'id'    => $this->noo_event['prefix'] .'_website',
						'label' => esc_html__( 'Website', 'noo-timetable' ),
						'type'  => 'text'
					),
					array(
						'id'    => $this->noo_event['prefix'] .'_email',
						'label' => esc_html__( 'Email', 'noo-timetable' ),
						'type'  => 'text'
					),
					array(
						'id'    => $this->noo_event['prefix'] .'_position',
						'label' => esc_html__( 'Position', 'noo-timetable' ),
						'type'  => 'text'
					),
				)
			);

			/**
			 * Add box Event ORGANIZERS to page
			 */
			$helper->add_meta_box($meta_box);

			$helper = new Noo__Timetable_Meta_Boxes_Helper( $this->noo_event['prefix'], array(
				'page' => $this->noo_event['slug']
			));

			/**
			 * Creating box: ORGANIZERS
			 * @var array
			 */
			$meta_box = array(
				'id' => $this->noo_event['prefix'] . '_event_organizers',
				'title' => esc_html__( 'ORGANIZERS', 'noo-timetable' ),
				'fields' => array(

					array(
						'id'      => $this->noo_event['prefix'] .'_organizers',
						'label'   => esc_html__( 'Organizers', 'noo-timetable' ),
						'type'    => 'select',
						'options' => self::get_all_organizers()
					)
				)
			);

			/**
			 * Add box Event Date & Time to page
			 */
			$helper->add_meta_box($meta_box);

			/**
			 * Creating box: Event Date & Time
			 * @var array
			 */
			$meta_box = array(
				'id' => $this->noo_event['prefix'] . '_event_date_time',
				'title' => esc_html__( 'TIME & DATE', 'noo-timetable' ),
				'fields' => array(
					array(
						'id'       => $this->noo_event['prefix'] . '_start_date',
						'label'    => esc_html__( 'Start Date', 'noo-timetable' ),
						'type'     => 'datetimepicker',
						'callback' => array( &$this, 'meta_box_datetimepicker' )
					),
					array(
						'id'       => $this->noo_event['prefix'] .'_end_date',
						'label'    => esc_html__( 'End Date', 'noo-timetable' ),
						'type'     => 'datetimepicker',
						'callback' => array( &$this, 'meta_box_datetimepicker' )
					),
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
				'id' => $this->noo_event['prefix'] . '_event_location',
				'title' => esc_html__( 'LOCATION', 'noo-timetable' ),
				'fields' => array(
					array(
						'id'       => $this->noo_event['prefix'] . '_gmap',
						'type'     => 'gmap',
						'callback' => array( &$this, 'meta_box_google_map' )
					),
					array(
						'id'    => $this->noo_event['prefix'] .'_address',
						'label' => esc_html__( 'Address', 'noo-timetable' ),
						'type'  => 'text'
					),
					array(
						'label'   => __( 'Latitude', 'noo-timetable' ),
						'id'      => $this->noo_event['prefix'] . '_gmap_latitude',
						'std'     => '40.71421714027808',
						'type'    => 'text',
					),
					array(
						'label'   => __( 'Longitude', 'noo-timetable' ),
						'id'      => $this->noo_event['prefix'] . '_gmap_longitude',
						'std'     => '-74.00538682937622',
						'type'    => 'text',
					)
				)
			);


			/**
			 * Add box Event LOCATION to page
			 */
			$helper->add_meta_box($meta_box);

		}

		/**
		 * Creating field date time picker
		 *

		 */
		public function meta_box_datetimepicker( $post, $id, $type, $meta, $std, $field ) {
			/**
			 * Call library datatime picker
			 */
			wp_enqueue_script( 'datetimepicker' );
			wp_enqueue_style( 'datetimepicker' );

			/**
			 * VAR
			 * @var string
			 */
			$date_format = 'm/d/Y H:i';
			$date_text   = is_numeric( $meta ) ? date( $date_format, $meta ) : $meta;
			$date        = is_numeric( $meta ) ? $meta : strtotime( $meta );

			echo '  <div>';
			echo '      <input type="text" readonly class="input_text" id="' . $id . '" value="' . esc_attr( $date_text ) . '" /> ';
			echo '      <input type="hidden" name="noo_meta_boxes[' . $id . ']" value="' . esc_attr( $date ) . '" /> ';
			echo '  </div>';
			?>
            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#<?php echo esc_js($id); ?>').datetimepicker({
                        format:"<?php echo esc_html( $date_format ); ?>",
                        step:15,
                        onChangeDateTime:function(dp,$input){
                            $input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
                        },
                        onShow:function( ct ){
                            this.setOptions({
								<?php if ( $id === '_noo_event_end_date' ) : ?>
                                minDate: $( '#_noo_event_start_date' ).val() ? $( '#_noo_event_start_date' ).val() : false
								<?php elseif ( $id === '_noo_event_start_date' ) : ?>
                                maxDate: $( '#_noo_event_end_date' ).val() ? jQuery( '#_noo_event_end_date' ).val() : false
								<?php endif; ?>
                            })
                        },
                    });
                });
            </script>
			<?php
		}

		/**
		 * Creating field map
		 *

		 */
		public function meta_box_google_map( $post, $meta_box ) {
			?>
            <div class="noo_event_google_map">
                <div id="noo_event_google_map" class="noo_event_google_map"
                     style="height: 380px; margin-bottom: 30px; overflow: hidden; position: relative; width: 100%;">
                </div>
                <div class="noo_event_google_map_search">
                    <input
                            placeholder="<?php echo esc_html__( 'Search your map', 'noo-timetable' ); ?>"
                            type="text" autocomplete="off" id="noo_event_google_map_search_input">
                </div>
            </div>
			<?php
		}

		/**
		 * Get the Event's Start date
		 * @param  int Event ID
		 * @param  mix $format date format, can be specific PHP format, false for now format or blank to get format from WordPress setting
		 * @return Unixtime if $format === false or Date base on format.
		 */
		public static function get_start_date( $event_id = null, $format = false ) {
			$event_id = empty($event_id) ? get_the_ID() : $event_id;
			if( empty( $event_id ) ) return false;

			$start_date = get_post_meta( $event_id, '_noo_event_start_date', true );

			if( empty( $start_date ) ) return false;

			if( !is_numeric($start_date) ) {
				// Convert old version to new unix format
				$start_date = strtotime($start_date);
				update_post_meta( $event_id, '_noo_event_start_date', $start_date );
			}

			if( $format === false ) {
				return ( int ) $start_date;
			}

			$date_format = empty( $format ) ? get_option('date_format') . ' ' . get_option('time_format') : $format;

			return date_i18n($date_format, $start_date);
		}

		/**
		 * Get the Event's End date
		 * @param  int Event ID
		 * @param  mix $format date format, can be specific PHP format, false for now format or blank to get format from WordPress setting
		 * @return Unixtime if $format === false or Date base on format.
		 */
		public static function get_end_date( $event_id = null, $format = false ) {
			$event_id = empty($event_id) ? get_the_ID() : $event_id;
			if( empty( $event_id ) ) return false;

			$end_date = get_post_meta( $event_id, '_noo_event_end_date', true );

			if( empty( $end_date ) ) return false;

			if( !is_numeric($end_date) ) {
				// Convert old version to new unix format
				$end_date = strtotime($end_date);
				update_post_meta( $event_id, '_noo_event_end_date', $end_date );
			}

			if( $format === false ) {
				return ( int ) $end_date;
			}

			$date_format = empty( $format ) ? get_option('date_format') . ' ' . get_option('time_format') : $format;

			return date_i18n($date_format, $end_date);
		}

		/**
		 * Process event is feature
		 *


		 */
		public function _feature_event(){
			if(isset($_GET['action']) && $_GET['action'] == 'event_feature'){
				if ( ! current_user_can( 'edit_posts' ) ) {
					wp_die( __( 'You do not have sufficient permissions to access this page.', 'noo-timetable' ), '', array( 'response' => 403 ) );
				}

				if ( ! check_admin_referer( 'noo-event-feature' ) ) {
					wp_die( __( 'You have taken too long. Please go back and retry.', 'noo-timetable' ), '', array( 'response' => 403 ) );
				}

				$post_id = ! empty( $_GET['event_id'] ) ? (int) $_GET['event_id'] : '';

				if ( ! $post_id || get_post_type( $post_id ) !== 'noo_event' ) {
					die;
				}

				$featured = get_post_meta( $post_id, 'event_is_featured', true );

				if ( '1' === $featured ) {
					update_post_meta( $post_id, 'event_is_featured', '0' );
				} else {
					update_post_meta( $post_id, 'event_is_featured', '1' );
				}


				wp_safe_redirect( esc_url_raw( remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) ) );
				die();
			}
		}

		/**
		 * Show info meta on event
		 *


		 */
		public static function show_meta( $args = array() ) {
			global $post;

			/**
			 * Defaults value
			 * @var [type]
			 */
			$args = wp_parse_args(
				$args,
				array(
					'timer_start'   => NOO_Settings()->get_option( 'noo_event_time_start', true ),
					'timer_end'     => NOO_Settings()->get_option( 'noo_event_time_end', true ),
					'address_event' => get_theme_mod( 'noo_event_address_event', true ),
				)
			);

			/**
			 * VAR
			 */
			$post_type   = get_post_type();
			$timer_start = esc_attr( $args['timer_start'] );
			$timer_end   = esc_attr( $args['timer_end'] );
			$address     = esc_html( $args['address_event'] );
			$prefix      = '_noo_event';

			/**
			 * Process data
			 */
			if ( $post_type === 'noo_event' ) :

				$start_date = noo_timetable_get_post_meta( $post->ID, "_noo_event_start_date", '' );
				$end_date   = noo_timetable_get_post_meta( $post->ID, "_noo_event_end_date", '' );

				$start_time = noo_timetable_get_post_meta( $post->ID, "_noo_event_start_time", '' );
				$end_time   = noo_timetable_get_post_meta( $post->ID, "_noo_event_end_time", '' );

				$recurrence = noo_timetable_get_post_meta( $post->ID, "_recurrence", '' );
				if ( $recurrence != '' ) {

					$next_date     = noo_timetable_get_post_meta( $post->ID, "_next_date", '' );
					$next_date_end = noo_timetable_get_post_meta( $post->ID, "_next_date_end", '' );

				}

				echo '<div class="noo-event-meta">';

				if ( !empty( $timer_start ) || !empty( $timer_end ) ) :

					echo '<span><i class="fa fa-calendar"></i> ';

					if ( !empty( $timer_start ) ) :
						if ( isset($next_date) && $next_date != '' ) {
							echo date_i18n( get_option('date_format'), $next_date );
						} else {
							if ( !empty( $start_date ) ) :
								echo date_i18n( get_option('date_format'), $start_date );
							endif;
						}
						if ( !empty( $start_time ) ) :
							echo ' ' . date_i18n( get_option('time_format'), $start_time );
						endif;
					endif;

					if ( !empty( $timer_start ) && !empty( $timer_end ) ) :
						echo ' - ';
					endif;

					if ( !empty( $timer_end ) ) :
						if ( isset($next_date_end) && $next_date_end != '' ) {
							echo date_i18n( get_option('date_format'), $next_date_end );
						} else {
							if ( !empty( $end_date ) ) :
								echo date_i18n( get_option('date_format'), $end_date );
							endif;
						}
						if ( !empty( $end_time ) ) :
							echo ' ' . date_i18n( get_option('time_format'), $end_time );
						endif;
					endif;

					echo '</span>';

				endif;

				if ( !empty( $address ) ) :

					$address_event = get_post_meta( $post->ID, $prefix . "_address", true );

					echo '<span class="location-info"><i class="fa fa-map-marker"></i> ' . esc_html( $address_event ) . '</span>';

				endif;

				echo '</div>';

			endif;

		}

		/**
		 * Show featured image event
		 *


		 */
		public static function show_featured() {
			global $post;
			$is_featured = get_post_meta( $post->ID, 'event_is_featured', true );

			echo '<div class="noo-featured-event">';
			echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '">';
			// if ( $is_featured == '1' ) :
			//     echo '<span class="is_featured">' . esc_html__( 'Featured', 'noo-timetable' ) . '</span>';
			// endif;
			the_post_thumbnail( array(800, 600) );
			echo '</a>';
			echo '</div>';
		}

		/**
		 * Load all item event by ajax
		 */
		public function load_event() {
			$posts_per_page = noo_timetable_get_option( 'noo_event_num', '10' );
			// $post_class_col = noo_timetable_get_option( 'noo_event_grid_column', 2 );
			// $post_class     = 'noo-xs-6 noo-sm-6 noo-md-' . absint((12 / $post_class_col));

			/**
			 * Check paged
			 */
			if( is_front_page() || is_home()) :
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
			else :
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			endif;

			/**
			 * Create array
			 */

			$args = array(
				'post_type'      => 'noo_event',
				'post_status'    => 'publish',
				'paged'          => $paged,
				'posts_per_page' => $posts_per_page
			);

			if ( !empty( $_POST['keyword'] ) ) :

				$args['s'] = $_POST['keyword'];

			endif;

			if ( !empty( $_POST['address'] ) ):
				$args['meta_query'] = array(
					'relation' => 'AND',
					array(
						'key'   => '_noo_event_address',
						'value' => $_POST['address'],
						'compare' => 'LIKE'
					)
				);
			endif;

			if ( !empty( $_POST['date'] ) ) :

				$start_date         = strtotime( $_POST['date'] . ' 23:59' );
				$end_date           = strtotime( $_POST['date'] . ' 00:00' );
				$args['meta_query'] = array(
					'relation' => 'OR',
					array(
						'relation' => 'AND',
						array(
							'key'     => '_next_date',
							'value'   => $start_date,
							'compare' => '<='
						),
						array(
							'key'     => '_next_date_end',
							'value'   => $end_date,
							'compare' => '>='
						)
					),
					array(
						'relation' => 'AND',
						array(
							'key'     => '_noo_event_start_date',
							'value'   => $start_date,
							'compare' => '<='
						),
						array(
							'key'     => '_noo_event_end_date',
							'value'   => $end_date,
							'compare' => '>='
						)
					)
				);

			endif;

			if ( !empty( $_POST['cat'] ) && $_POST['cat'] !== 'none' ) :

				$args['tax_query'] = array(
					array(
						'taxonomy' => 'event_category',
						'field'    => 'id',
						'terms'    => $_POST['cat'],
					),
				);

			endif;
			$wp_query = new WP_Query( $args );
			$layout = ( !empty( $_POST['layout'] ) ? $_POST['layout'] : noo_timetable_get_option( 'noo_event_default_layout', 'grid' ) );
			?>
            <div class="archive-noo-event-head">

                <span class="noo-event-text">
                    <?php echo sprintf( esc_html__( 'We found %s available events for you', 'noo-timetable' ), '<span>' . $wp_query->found_posts . '</span>' ); ?>
                </span>

                <span class="noo-event-button">
                    <i class="fa fa-th-list<?php echo ( $layout === 'list' ? ' active' : '' ); ?>" data-id="list"></i>
                    <i class="fa fa-th-large<?php echo ( $layout === 'grid' ? ' active' : '' ); ?>" data-id="grid"></i>
                </span>

            </div><!-- /.archive-noo-event-head -->

            <div class="archive-noo-event-wrap event-<?php echo esc_attr( $layout ); ?> noo-row">
				<?php
				if ($wp_query->have_posts()) :

					while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

                        <div class="archive-noo-event-item-wrap noo-md-6">

                            <div class="archive-noo-event-item">

                                <div class="noo-archive-event-body">

									<?php self::show_featured(); ?>

                                    <div class="noo-single-event-head">

                                        <span class="event-category">
                                            <?php echo get_the_term_list( get_the_ID(), 'event_category', '',', ' );?>
                                        </span>

                                        <h3 class="noo-title">
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
												<?php the_title(); ?>
                                            </a>
                                        </h3>
										<?php self::show_meta( array( 'address' => false ) ); ?>
                                    </div>

									<?php the_excerpt(); ?>

                                </div><!-- /.noo-archive-event-body -->

                                <div class="noo-archive-event-footer">
									<?php noo_timetable_social_share(); ?>
                                    <a class="readmore" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
										<?php echo esc_html__( 'Read More', 'noo-timetable' ); ?>
                                    </a>
                                </div>

                            </div>

                        </div><!-- /.archive-noo-event-item -->

					<?php endwhile;
					noo_timetable_pagination_normal( array( 'link' => get_post_type_archive_link( 'noo_event' ) ), $wp_query );

				else : ?>
                    <div class="noo-md-12">

                        <h4 class="center"><?php echo esc_html__( 'Not Found', 'noo-timetable' ); ?></h4>
                        <p class="center">
							<?php echo esc_html__( 'Sorry, but you are looking for something that isn\'t here.', 'noo-timetable' ); ?>
                        </p>

                    </div>

				<?php endif; ?>

            </div>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('.noo-event-button').on('click', 'i', function(event) {
                        event.preventDefault();
                        /**
                         * VAR
                         */
                        var $$      = $(this),
                            id      = $$.data( 'id' );

                        /**
                         * Process
                         */
                        $( '.noo-event-button i' ).removeClass( 'active' );
                        $$.addClass( 'active' );


                        if ( id === 'grid' ) {
                            if ( $( '.archive-noo-event-wrap' ).hasClass('event-list') ) {
                                $( '.archive-noo-event-wrap' ).removeClass('event-list').addClass('event-grid');
                            }
                        } else if ( id === 'list' ) {
                            if ( $( '.archive-noo-event-wrap' ).hasClass('event-grid') ) {
                                $( '.archive-noo-event-wrap' ).removeClass('event-grid').addClass('event-list');
                            }
                        }
                    });
                });
            </script>

			<?php wp_die();
		}

		public static function show_category_event( $cat = '' ) {
			$categories_arr = array();
			if($cat == 'all') {
				$categories = get_terms( 'event_category' );
				if ( $categories ){
					foreach ($categories as $category) {
						$cate_obj = new stdClass();
						$cate_obj->id    = $category->term_id;
						$cate_obj->title = $category->name;
						$categories_arr[] = $cate_obj;
					}
				}
			} else {
				$cat_select = explode(",", $cat);
				foreach($cat_select as $catSl) {
					$catData = get_term( $catSl, 'event_category' );
					if($catData) {
						$cate_obj = new stdClass();
						$cate_obj->id    = $catData->term_id;
						$cate_obj->title = $catData->name;
						$categories_arr[] = $cate_obj;
					}
				}
			}

			return $categories_arr;
		}

		public static function createDateRangeArray($strDateFrom,$strDateTo)
		{
			// takes two dates formatted as YYYY-MM-DD and creates an
			// inclusive array of the dates between the from and to dates.

			// could test validity of dates here but I'm already doing
			// that in the main script

			$aryRange=array();

			$iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
			$iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

			if ($iDateTo>=$iDateFrom)
			{
				array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
				while ($iDateFrom<$iDateTo)
				{
					$iDateFrom+=86400; // add 24 hours
					array_push($aryRange,date('Y-m-d',$iDateFrom));
				}
			}
			return $aryRange;
		}

		public function event_filter() {

			if( check_ajax_referer('class_filter','security' , false) ) {
				wp_send_json('');
			}
			$from = isset( $_POST['from'] ) ? $_POST['from'] : '';
			$to = isset( $_POST['to'] ) ? $_POST['to'] : '';
			$category      = isset( $_POST['class_category'] ) ? $_POST['class_category'] : '';
			$shorcode_attr = isset( $_POST['shorcode_attr'] ) ? json_decode( stripslashes($_POST['shorcode_attr']), true) : array();
			$json_data = self::show_list_event($from, $to, $category, $shorcode_attr);
			wp_send_json( $json_data );
		}

		public function noo_class_event_filter() {
			$from = isset( $_POST['from'] ) ? $_POST['from'] : '';
			$to = isset( $_POST['to'] ) ? $_POST['to'] : '';
			$category      = isset( $_POST['class_category'] ) ? $_POST['class_category'] : '';
			$filter_type      = isset( $_POST['filter_type'] ) ? $_POST['filter_type'] : '';
			$shorcode_attr = isset( $_POST['shorcode_attr'] ) ? json_decode( stripslashes($_POST['shorcode_attr']), true) : array();

			$doituong = new Noo__Timetable__Class();
			$classes_list = $doituong->show_schedule_class_list($from, $to, $category, $filter_type, $shorcode_attr);
			$events_list = self::show_list_event( $from, $to, $category, $shorcode_attr);
			$classes_arr = array_merge($classes_list['events_data'], $events_list['events_data']);
			$result = [];
			$result['events_data'] = $classes_arr;
			$result['datetime'] = $events_list['datetime'];
			$result['holidays_data'] = $classes_list['holidays_data'];

			//$json_data = self::show_list_event($from, $to, $category, $shorcode_attr);
			wp_send_json( $result );
		}

		public static function get_prepare_event($eventID) {
			$start_date = self::get_start_date( $eventID, 'Y-m-d' );
			$start_time = get_post_meta( $eventID, "_noo_event_start_time", true );
			$end_date   = self::get_end_date( $eventID, 'Y-m-d' );
			$end_time   = get_post_meta( $eventID, "_noo_event_end_time", true );
			$start_time = !empty($start_time) ? $start_time : '1470301200';
			$end_time   = !empty($end_time) ? $end_time : '1470301200';

			$prepare_event = array();
			$prepare_event['id']              = $eventID;
			$prepare_event['start']           = $start_date . 'T' . date_i18n('H:i', $start_time);
			$prepare_event['end']             = $end_date . 'T' . date_i18n('H:i', $end_time);
			$prepare_event['start_date']      = $start_date;
			$prepare_event['start_time']      = $start_time;
			$prepare_event['end_date']        = $end_date;
			$prepare_event['end_time']        = $end_time;
			return $prepare_event;
		}

		public static function get_array_string_date( $freq, $lstString ) {
			$text_and = esc_html__('and', 'noo-timetable');
			if ( $freq == 'mweekly' ) {
				if (count($lstString) == 2) {
					$days = esc_html__('Weekend Day', 'noo-timetable');
				} elseif (count($lstString) == 5) {
					$days = esc_html__('Weekday', 'noo-timetable');
				} elseif (count($lstString) == 7) {
					$days = esc_html__('Day', 'noo-timetable');
				} else {
					$days = self::_get_week_day(end($lstString), true);
				}
			} elseif ( $freq == 'weekly' ) {
				$eday = self::_get_week_day(end($lstString), true);
				$days = array();
				foreach($lstString as $k => $day){
					if ( count($lstString) == ($k + 1)) break;
					$days[] = self::_get_week_day($day, true);
				}
				$days = implode(', ', $days);
				$days = ( $days != '' ) ? $days.' '.$text_and.' '.$eday : $eday;
			} elseif ( $freq == 'monthly' ) {
				$eday = self::get_short_ordinal_month(end($lstString));
				$days = array();
				foreach($lstString as $k => $day){
					if ( count($lstString) == ($k + 1)) break;
					$days[] = self::get_short_ordinal_month($day);
				}
				$days = implode(', ', $days);
				$days = ( $days != '' ) ? $days.' '.$text_and.' '.$eday : $eday;
			} else {
				$eday = self::_get_full_month(end($lstString));
				$days = array();
				foreach($lstString as $k => $day){
					if ( count($lstString) == ($k + 1)) break;
					$days[] = self::_get_full_month($day);
				}
				$days = implode(', ', $days);
				$days = ( $days != '' ) ? $days.' '.$text_and.' '.$eday : $eday;
			}
			return $days;
		}

		public static function get_short_ordinal_month($month) {
			if ( $month == -1 ) return esc_html__('last', 'noo-timetable');
			$abb = 'th';
			switch ($month) {
				case '1':  $abb = 'st'; break;
				case '2':  $abb = 'nd'; break;
				case '3':  $abb = 'rd'; break;
				case '4':  $abb = 'th'; break;
				case '5':  $abb = 'th'; break;
				case '6':  $abb = 'th'; break;
				case '7':  $abb = 'th'; break;
				case '8':  $abb = 'th'; break;
				case '9':  $abb = 'th'; break;
				case '10': $abb = 'th'; break;
				case '11': $abb = 'th'; break;
				case '12': $abb = 'th'; break;
				case '13': $abb = 'th'; break;
				case '14': $abb = 'th'; break;
				case '15': $abb = 'th'; break;
				case '16': $abb = 'th'; break;
				case '17': $abb = 'th'; break;
				case '18': $abb = 'th'; break;
				case '19': $abb = 'th'; break;
				case '20': $abb = 'th'; break;
				case '21': $abb = 'st'; break;
				case '22': $abb = 'nd'; break;
				case '23': $abb = 'rd'; break;
				case '24': $abb = 'th'; break;
				case '25': $abb = 'th'; break;
				case '26': $abb = 'th'; break;
				case '27': $abb = 'th'; break;
				case '28': $abb = 'th'; break;
				case '29': $abb = 'th'; break;
				case '30': $abb = 'th'; break;
				case '31': $abb = 'st'; break;
			}
			return $month.$abb;
		}

		public static function get_description_recurrence( $recurrence ) {
			$recurrence = self::get_param_recurrence($recurrence);
			extract($recurrence);
			$interval = isset($interval) ? $interval : '';
			$bysetpos = isset($bysetpos) ? $bysetpos : 0;
			if ( $freq == 'daily' ) {
				$desc = sprintf(esc_html__('Repeats Every %s Days', 'noo-timetable'),
					$interval
				);
			}
			if ( $freq == 'weekly' ) {
				if (isset($byday)) {
					$ard = self::get_array_string_date('weekly', $byday);
					$desc = sprintf(esc_html__('Repeats Every %s Week on %s', 'noo-timetable'),
						$interval,
						$ard
					);
				} else {
					$desc = sprintf(esc_html__('Repeats Every %s Week', 'noo-timetable'),
						$interval
					);
				}
			}
			if ( $freq == 'monthly' ) {
				if (isset($bymonthday)) {
					$ard = self::get_array_string_date('monthly', $bymonthday);
					$desc = sprintf(esc_html__('Repeats Every %s Month on the %s', 'noo-timetable'),
						$interval,
						$ard
					);
				} elseif (isset($byday)) {
					$strsetpos = self::get_short_ordinal_month($bysetpos);
					$ard = self::get_array_string_date('mweekly', $byday);
					$desc = sprintf(esc_html__('Repeats Every %s Month on the %s %s', 'noo-timetable'),
						$interval,
						$strsetpos,
						$ard
					);
				} else {
					$desc = sprintf(esc_html__('Repeats Every %s Month', 'noo-timetable'),
						$interval
					);
				}
			}
			if ( $freq == 'yearly' ) {
				if (isset($bymonth)) {
					$ary = self::get_array_string_date('yearly', $bymonth);
					if (isset($byday)) {
						$strsetpos = self::get_short_ordinal_month($bysetpos);
						$ard = self::get_array_string_date('mweekly', $byday);
						$desc = sprintf(esc_html__('Repeats Every %s Year on the %s %s of %s', 'noo-timetable'),
							$interval,
							$strsetpos,
							$ard,
							$ary
						);
					} else {
						$desc = sprintf(esc_html__('Repeats Every %s Year in %s', 'noo-timetable'),
							$interval,
							$ary
						);
					}
				} else {
					$desc = sprintf(esc_html__('Repeats Every %s Year', 'noo-timetable'),
						$interval
					);
				}
			}
			return $desc;
		}

		public static function get_param_recurrence( $recurrence, $hold_string = false ) {
			$r = array();
			$s = strtolower($recurrence);
			$e = explode(";", $s);
			foreach ( $e as $ec ) {
				$x = explode("=", $ec);
				$r[$x[0]] = $x[1];
			}
			if ( $hold_string )
				return $r;
			if ( isset($r['until']) ) {
				// 20161222 to 2016-12-22
				$u = explode('t', $r['until']);
				$u = $u[0];
				$sr = substr_replace($u, '-', 4, 0);
				$sr = substr_replace($sr, '-', 7, 0);
				$r['until'] = strtotime( $sr );
			}
			if ( isset($r['byday']) ) {
				if ( strpos($r['byday'], ',') === false ) {
					// 5FR, get 5 and FR
					// -1SA, get -1 and SA
					$r['bysetpos'] = substr($r['byday'], 0, -2);
					$r['byday'] = substr($r['byday'], -2, 2);
				}
				$sra = array(
					'mo' => 1,
					'tu' => 2,
					'we' => 3,
					'th' => 4,
					'fr' => 5,
					'sa' => 6,
					'su' => 0,
				);
				$lt = str_replace(array_keys($sra), array_values($sra), $r['byday']);
				$r['byday'] = explode(",", $lt);
			}
			if ( isset($r['bymonthday']) ) {
				$r['bymonthday'] = explode(",", $r['bymonthday']);
			}
			if ( isset($r['bymonth']) ) {
				$r['bymonth'] = explode(",", $r['bymonth']);
			}

			return $r;
		}

		public static function handle_event( $prepare_event, $start_date, $datediff, $start_time, $end_time ) {
			$new_start_date = date('Y-m-d', $start_date);
			$rrule = $prepare_event;
			$rrule['start'] = $new_start_date . 'T' . date_i18n('H:i', $start_time);
			if ( $datediff > 0 ) {
				$new_end = strtotime("+".$datediff." days", $start_date);
			} else {
				$new_end = $start_date;
			}
			$new_end_date = date('Y-m-d', $new_end);
			$rrule['end'] = $new_end_date . 'T' . date_i18n('H:i', $end_time);
			return $rrule;
		}

		public static function sort_by_start_date( $days, $start_date, $date_format = 'w' ) {
			$day_to_check = date($date_format, $start_date);
			$ndays1 = array();
			$ndays2 = array();
			asort( $days );
			foreach ($days as $k => $nday) {
				if ( $nday >= $day_to_check ) {
					$ndays1[] = $nday;
				} else {
					$ndays2[] = $nday;
				}
			}
			$days = array_merge($ndays1, $ndays2);
			return $days;
		}

		public static function get_ordinal_string_relative($bysetpos) {
			switch ( $bysetpos ) {
				case '1':
					$str = 'first'; break;
				case '2':
					$str = 'second'; break;
				case '3':
					$str = 'third'; break;
				case '4':
					$str = 'fourth'; break;
				case '5':
					$str = 'fifth'; break;
				case '-1':
					$str = 'last'; break;
				default:
					$str = 'first'; break;
			}
			return $str;
		}

		public static function get_repeat_events($prepare_event, $args) {

			$defaults = array(
				'freq'       => 'daily',
				'until'      => false,
				'count'      => 1,
				'interval'   => 1,
				'byday'      => false,
				'bymonthday' => false,
				'bysetpos'   => false,
				'bymonth'    => false,
			);
			$args = wp_parse_args( $args, $defaults );
			extract( $args );

			$freq = strtolower( $freq );
			$events = array();

			$start_time = $prepare_event['start_time'];
			$end_time   = $prepare_event['end_time'];
			$start_date = strtotime($prepare_event['start_date']);
			$end_date   = strtotime($prepare_event['end_date']);

			// Get number day between two date
			$datediff = floor(($end_date - $start_date) / (60 * 60 * 24));

			switch ( $freq ) {
				case 'daily':
					$text_strtime = 'days';
					break;
				case 'weekly':
					$text_strtime = 'week';
					break;
				case 'monthly':
					$text_strtime = 'month';
					break;
				case 'yearly':
					$text_strtime = 'year';
					break;
				default:
					$text_strtime = $freq;
					break;
			}

			if ( $freq == 'yearly' && $bymonth ) {
				if ( is_array($bymonth) && count($bymonth) > 0 ) {
					$bymonth = self::sort_by_start_date( $bymonth, $start_date, 'm' );
					$mo_start = $bymonth[0];

					if ( $until ) {
						$last_start_date = $start_date;
						$i = 1;
						while ( $last_start_date <= $until) {
							foreach ($bymonth as $key => $month) {
								$string_month = date('Y', $start_date).'-'.$month.'-'.date('d', $start_date);
								$loop_date = strtotime( $string_month );
								if ($mo_start <= $month) {
									$loop_date = strtotime( $interval." year ago" , $loop_date);
								}
								$last_start_date = strtotime("+".($i * $interval)." ".$text_strtime, $loop_date );
								if ( $last_start_date > $until )
									break;
								if ( is_array($byday) && count($byday) == 1 && $bysetpos ) {
									$stringpos = self::get_ordinal_string_relative($bysetpos);
									$c = null;
									$d = strtotime($stringpos.' '.self::_get_week_day($byday[0]).' of '.strtolower(date('F', $last_start_date)).' '.date('Y', $last_start_date));
									if ( date('m', $d) == date('m', $last_start_date) )
										$c = true;
									else
										$c = false;
									if ( $c ) {
										$events[] = self::handle_event($prepare_event, $d, $datediff, $start_time, $end_time);
									}
								}
                                elseif ( is_array($byday) && count($byday) > 1 && $bysetpos ) {
									if ( $bysetpos == -1 ) {
										$t = strtotime($stringpos.' day of '.strtolower(date('F', $last_start_date)).' '.date('Y', $last_start_date));
										$f = strtotime('first day of '.strtolower(date('F', $last_start_date)).' '.date('Y', $last_start_date));
										if ( count($byday) == 5 ) {
											if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
												$t = strtotime("previous friday", $t);
											}
										} elseif ( count($byday) == 2 ) {
											if ( date('w', $t) != 0 && date('w', $t) != 6 ) {
												$nt = strtotime("+1 days", $t);
												$t = strtotime("previous sunday", $nt);
											}
										}
										$n = strtotime("+".$interval." month", $f);
									}
									else {
										$t = strtotime(date('Y', $last_start_date).'-'.date('m', $last_start_date).'-'.$bysetpos);
										if ( count($byday) == 5 ) {
											$t = strtotime('first day of '.strtolower(date('F', $last_start_date)).' '.date('Y', $last_start_date));
											$ch = 0;
											$c = 0;
											while ( $ch < $bysetpos) {
												if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
													$ch = 0;
												} else {
													$ch++;
													$c++;
												}
												if ($c == $bysetpos)
													break;
												$t = strtotime("+1 days", $t);
											}
										} elseif ( count($byday) == 2 ) {
											$t = strtotime('first day of '.strtolower(date('F', $last_start_date)).' '.date('Y', $last_start_date));
											$ch = 0;
											$c = 0;
											while ( $ch < 31) {
												if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
													$ch++;
													$c++;
												} else {
													$ch = 0;
												}
												if ($c == $bysetpos)
													break;
												$t = strtotime("+1 days", $t);
											}
										}

										$n = strtotime("+".$interval." month", $t);
									}
									$events[] = self::handle_event($prepare_event, $t, $datediff, $start_time, $end_time);
								} else {
									$events[] = self::handle_event($prepare_event, $last_start_date, $datediff, $start_time, $end_time);
								}
							}
							if ( $last_start_date > $until )
								break;
							$i++;
						}
					}
					else {
						for ($i=1; $i < $count; $i++) {
							foreach ($bymonth as $key => $month) {
								$string_month = date('Y', $start_date).'-'.$month.'-'.date('d', $start_date);
								$loop_date = strtotime( $string_month );
								if ($mo_start <= $month) {
									$loop_date = strtotime( $interval." year ago" , $loop_date);
								}
								$new_start = strtotime("+".($i * $interval)." ".$text_strtime, $loop_date );

								if ( is_array($byday) && count($byday) == 1 && $bysetpos ) {
									$stringpos = self::get_ordinal_string_relative($bysetpos);
									$c = null;
									$d = strtotime($stringpos.' '.self::_get_week_day($byday[0]).' of '.strtolower(date('F', $new_start)).' '.date('Y', $new_start));
									if ( date('m', $d) == date('m', $new_start) )
										$c = true;
									else
										$c = false;
									if ( $c ) {
										$events[] = self::handle_event($prepare_event, $d, $datediff, $start_time, $end_time);
									}
								}
                                elseif ( is_array($byday) && count($byday) > 1 && $bysetpos ) {
									if ( $bysetpos == -1 ) {
										$t = strtotime($stringpos.' day of '.strtolower(date('F', $new_start)).' '.date('Y', $new_start));
										$f = strtotime('first day of '.strtolower(date('F', $new_start)).' '.date('Y', $new_start));
										if ( count($byday) == 5 ) {
											if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
												$t = strtotime("previous friday", $t);
											}
										} elseif ( count($byday) == 2 ) {
											if ( date('w', $t) != 0 && date('w', $t) != 6 ) {
												$nt = strtotime("+1 days", $t);
												$t = strtotime("previous sunday", $nt);
											}
										}
										$n = strtotime("+".$interval." month", $f);
									}
									else {
										$t = strtotime(date('Y', $new_start).'-'.date('m', $new_start).'-'.$bysetpos);
										if ( count($byday) == 5 ) {
											$t = strtotime('first day of '.strtolower(date('F', $new_start)).' '.date('Y', $new_start));
											$ch = 0;
											$c = 0;
											while ( $ch < $bysetpos) {
												if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
													$ch = 0;
												} else {
													$ch++;
													$c++;
												}
												if ($c == $bysetpos)
													break;
												$t = strtotime("+1 days", $t);
											}
										} elseif ( count($byday) == 2 ) {
											$t = strtotime('first day of '.strtolower(date('F', $new_start)).' '.date('Y', $new_start));
											$ch = 0;
											$c = 0;
											while ( $ch < 31) {
												if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
													$ch++;
													$c++;
												} else {
													$ch = 0;
												}
												if ($c == $bysetpos)
													break;
												$t = strtotime("+1 days", $t);
											}
										}

										$n = strtotime("+".$interval." month", $t);
									}
									$events[] = self::handle_event($prepare_event, $t, $datediff, $start_time, $end_time);
								} else {
									$events[] = self::handle_event($prepare_event, $new_start, $datediff, $start_time, $end_time);
								}
								if ( count($events) == $count ) {
									break;
								}
							}
							if ( count($events) == $count ) {
								break;
							}
						}
					}
				}
				return $events;
			}

			if ( $freq == 'monthly' && $byday && $bysetpos ) {
				if ( is_array($byday) && count($byday) > 1 ) {
					$stringpos = self::get_ordinal_string_relative($bysetpos);
					$l = $start_date;
					$until_count = ( $until ) ? $until : $count;
					$ld = ( $until ) ? $l : 1;
					$i = 1;
					while ( $ld <= $until_count) {
						if ( $bysetpos == -1 ) {
							$t = strtotime($stringpos.' day of '.strtolower(date('F', $l)).' '.date('Y', $l));
							$f = strtotime('first day of '.strtolower(date('F', $l)).' '.date('Y', $l));
							if ( count($byday) == 5 ) {
								if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
									$t = strtotime("previous friday", $t);
								}
							} elseif ( count($byday) == 2 ) {
								if ( date('w', $t) != 0 && date('w', $t) != 6 ) {
									$nt = strtotime("+1 days", $t);
									$t = strtotime("previous sunday", $nt);
								}
							}
							$n = strtotime("+".$interval." month", $f);
						}
						else {
							$t = strtotime(date('Y', $l).'-'.date('m', $l).'-'.$bysetpos);
							if ( count($byday) == 5 ) {
								$t = strtotime('first day of '.strtolower(date('F', $l)).' '.date('Y', $l));
								$ch = 0;
								$c = 0;
								while ( $ch < $bysetpos) {
									if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
										$ch = 0;
									} else {
										$ch++;
										$c++;
									}
									if ($c == $bysetpos)
										break;
									$t = strtotime("+1 days", $t);
								}
							} elseif ( count($byday) == 2 ) {
								$t = strtotime('first day of '.strtolower(date('F', $l)).' '.date('Y', $l));
								$ch = 0;
								$c = 0;
								while ( $ch < 31) {
									if ( date('w', $t) == 0 || date('w', $t) == 6 ) {
										$ch++;
										$c++;
									} else {
										$ch = 0;
									}
									if ($c == $bysetpos)
										break;
									$t = strtotime("+1 days", $t);
								}
							}

							$n = strtotime("+".$interval." month", $t);
						}

						$l = $n;
						$i++;
						$ld = ( $until ) ? $t : $i;
						if ( $until ) {
							if ( $ld > $until_count )
								break;
						}
						$events[] = self::handle_event($prepare_event, $t, $datediff, $start_time, $end_time);
						if ( $ld > $until_count )
							break;
					}
				}
			}

			if ( $freq == 'monthly' && $byday && $bysetpos ) {
				if ( is_array($byday) && count($byday) == 1 ) {
					$stringpos = self::get_ordinal_string_relative($bysetpos);
					$l = $start_date;
					if ( $until ) {
						$ld = $l;
						$com = 0;
						while ( $ld <= $until) {
							$c = null;
							$d = strtotime($stringpos.' '.self::_get_week_day($byday[0]).' of '.strtolower(date('F', $l)).' '.date('Y', $l));
							if ( date('m', $d) == date('m', $l) )
								$c = true;
							else
								$c = false;
							$f = strtotime('first day of '.strtolower(date('F', $l)).' '.date('Y', $l));
							$n = strtotime("+".$interval." month", $f);
							$ld = $d;
							$l = $n;
							if ( $ld > $until )
								break;
							if ( $c ) {
								$events[] = self::handle_event($prepare_event, $d, $datediff, $start_time, $end_time);
								$com++;
							} else {
								$ld = 0;
							}
							if ($com == $count)
								break;
						}
					}
					else {
						$com = 0;
						for ($i=0; $i < $count; $i++) {
							$c = null;
							$d = strtotime($stringpos.' '.self::_get_week_day($byday[0]).' of '.strtolower(date('F', $l)).' '.date('Y', $l));
							if ( date('m', $d) == date('m', $l) )
								$c = true;
							else
								$c = false;
							$f = strtotime('first day of '.strtolower(date('F', $l)).' '.date('Y', $l));
							$n = strtotime("+".$interval." month", $f);
							$l = $n;
							if ( $c ) {
								$events[] = self::handle_event($prepare_event, $d, $datediff, $start_time, $end_time);
								$com++;
							} else {
								$i = 0;
							}
							if ($com == $count)
								break;
						}
					}
				}
				return $events;
			}

			if ( $freq == 'monthly' && $bymonthday ) {
				if ( is_array($bymonthday) && count($bymonthday) > 1 ) {
					$bymonthday = self::sort_by_start_date( $bymonthday, $start_date, 'd' );
					$mday_start = $bymonthday[0];

					if ( $until ) {
						$last_start_date = $start_date;
						$i = 1;
						while ( $last_start_date <= $until) {
							foreach ($bymonthday as $key => $day) {
								$string_day = date('Y', $start_date).'-'.date('m', $start_date).'-'.$day;
								$loop_date = strtotime( $string_day );
								if ($mday_start <= $day) {
									$loop_date = strtotime( $interval." month ago" , $loop_date);
								}
								$last_start_date = strtotime("+".($i * $interval)." ".$text_strtime, $loop_date );
								if ( $last_start_date > $until )
									break;
								$events[] = self::handle_event($prepare_event, $last_start_date, $datediff, $start_time, $end_time);
							}
							if ( $last_start_date > $until )
								break;
							$i++;
						}
					}
					else {
						for ($i=1; $i < $count; $i++) {
							foreach ($bymonthday as $key => $day) {
								$string_day = date('Y', $start_date).'-'.date('m', $start_date).'-'.$day;
								$loop_date = strtotime( $string_day );
								if ($mday_start <= $day) {
									$loop_date = strtotime( $interval." month ago" , $loop_date);
								}
								$new_start = strtotime("+".($i * $interval)." ".$text_strtime, $loop_date );
								$events[] = self::handle_event($prepare_event, $new_start, $datediff, $start_time, $end_time);
								if ( count($events) == $count ) {
									break;
								}
							}
							if ( count($events) == $count ) {
								break;
							}
						}
					}
				}
				return $events;
			}

			if ( $freq == 'weekly' && $byday ) {
				if ( is_array($byday) && count($byday) >= 1 ) {
					$byday = self::sort_by_start_date( $byday, $start_date, 'w' );
					$wday_start = $byday[0];

					if ( $until ) {
						$last_start_date = $start_date;
						$i = 1;
						while ( $last_start_date <= $until) {
							foreach ($byday as $key => $day) {
								$loop_date = strtotime( "next ".self::_get_week_day($day) , strtotime("yesterday", $start_date) );
								if ( $wday_start > $day && $interval > 1 ) {
									$loop_date = strtotime( "1 week ago" , $loop_date);
								} else {
									$loop_date = strtotime( $interval." week ago" , $loop_date);
								}
								$last_start_date = strtotime("+".($i * $interval)." ".$text_strtime, $loop_date );
								if ( $last_start_date > $until )
									break;
								$events[] = self::handle_event($prepare_event, $last_start_date, $datediff, $start_time, $end_time);
							}
							if ( $last_start_date > $until )
								break;
							$i++;
						}
					}
					else {
						for ($i=1; $i < $count; $i++) {
							foreach ($byday as $key => $day) {
								$loop_date = strtotime( "next ".self::_get_week_day($day) , strtotime("yesterday", $start_date) );
								if ( $wday_start > $day && $interval > 1 ) {
									$loop_date = strtotime( "1 week ago" , $loop_date);
								} else {
									$loop_date = strtotime( $interval." week ago" , $loop_date);
								}
								$new_start = strtotime("+".($i * $interval)." ".$text_strtime, $loop_date );
								$events[] = self::handle_event($prepare_event, $new_start, $datediff, $start_time, $end_time);
								if ( count($events) == $count ) {
									break;
								}
							}
							if ( count($events) == $count ) {
								break;
							}
						}
					}
				}
				return $events;
			}

			// Add first event on start date
			$events[] = $prepare_event;

			if ( $until ) {
				$last_start_date = $start_date;
				$i = 1;
				while ( $last_start_date <= $until) {
					$last_start_date = strtotime("+".($i * $interval)." ".$text_strtime, $start_date );
					if ( $last_start_date > $until )
						break;
					$events[] = self::handle_event($prepare_event, $last_start_date, $datediff, $start_time, $end_time);

					$i++;
				}
			}
			else {
				for ( $i=1; $i < $count; $i++ ) {
					$new_start = strtotime("+".($i * $interval)." ".$text_strtime, $start_date );
					$events[] = self::handle_event($prepare_event, $new_start, $datediff, $start_time, $end_time);
				}
			}
			return $events;
		}

		/**
		 * Show all event
		 *
		 */
		public static function show_mobile_event( $show = 'json', $category = '', $attrs = array() ) {

			extract($attrs);

			/**
			 * VAR
			 */
			$show_all_tab  = isset($show_all_tab) ? $show_all_tab : 'yes';
			$list_event = array();

			// ----- Creat array
			$event_args = array(
				'posts_per_page'   => -1,
				'post_status'      => 'publish',
				'post_type'        => 'noo_event',
				'suppress_filters' => 0
			);

			if( !empty($category ) && $category !== 'all' ) {

				if ( ! is_numeric($category) )
					$category = explode(',', $category);
				if($show_all_tab == 'no'){
					if(is_array($category) && count($category) > 1){
						$category = $category[0];
					}
					$event_args['tax_query'] = array(
						array(
							'taxonomy'  => 'event_category',
							'terms'     => $category,
						),
					);
				}else{
					$event_args['tax_query'] = array(
						array(
							'taxonomy'  => 'event_category',
							'terms'     => $category,
						),
					);
				}
			}

			/**
			 * Create new query
			 * @var WP_Query
			 */
			$wp_query = new WP_Query( $event_args );

			/**
			 * Process
			 */
			if ( $wp_query->have_posts() ) :

				// Get some settings
				$noo_schedule_event_show_icon  = isset($event_show_icon) ? $event_show_icon : 'yes';
				$noo_schedule_event_item_style = isset($event_item_style) ? $event_item_style : 'background_color';
				$noo_schedule_event_split      = isset($event_split) ? $event_split : 'yes';
				$default_view                  = isset($default_view) ? $default_view : 'agendaWeek';
				$show_excerpt_in_modal         = isset($general_popup_excerpt) ? $general_popup_excerpt : 'yes';
				$noo_schedule_navigate_link    = isset($general_navigate_link) ? $general_navigate_link : 'internal';
				$is_mobile                     = isset($is_mobile) ? $is_mobile : false;

				$context = 'noo_event_array_merge';
				while ( $wp_query->have_posts() ) :
					$wp_query->the_post();
					global $post;

					$end_date = '';
					$recurrence = get_post_meta( get_the_ID(), "_recurrence", true );
					$start_date      = self::get_start_date( get_the_ID(), 'Y-m-d' );
					$start_time      = get_post_meta( get_the_ID(), "_noo_event_start_time", true );
					// recurrence -> none
					// if(empty($recurrence))
					$end_date        = self::get_end_date( get_the_ID(), 'Y-m-d' );

					$end_time        = get_post_meta( get_the_ID(), "_noo_event_end_time", true );

					$address         = get_post_meta( get_the_ID(), "_noo_event_address", true );

					$start_time 	 = !empty($start_time) ? $start_time : '1470301200';
					$end_time 		 = !empty($end_time) ? $end_time : '1470301200';					

					$bg_color = $catColor  = get_post_meta( get_the_ID(), "_noo_event_bg_color", true );
					$register_link   = get_post_meta( get_the_ID(), "_noo_event_register_link", true );

					// ClassName
					$class_name = 'md-trigger fc-noo-event';
					if ( $noo_schedule_event_show_icon == 'yes' ) {
						$class_name .= ' show-icon';
					}
					$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array(800, 600) );
					$popup_bgImage  = isset($feat_image_url[0]) ? $feat_image_url[0] : '';
					$bacgroundImage = null;
					$text_color = '#fff';
					$item_border_style = 'transparent';
					if ( $noo_schedule_event_item_style == 'background_none' ) {
						$bg_color        = '#fff';
						$text_color = 'inherit';
						$item_border_style = 'transparent';						
						
					}elseif($noo_schedule_event_item_style == 'background_image'){
						$bacgroundImage = isset($feat_image_url[0]) ? $feat_image_url[0] : '';
						$bg_color        = '';
						if(empty($bacgroundImage)){
							$bg_color        = '#fff';
							$text_color      = '#000';
						}
					}
					if(empty($bg_color)) $text_color = '#000';

					$excerpt = '';
					if ( $show_excerpt_in_modal == 'yes' ) {
						$excerpt = $post->post_excerpt;
						if(empty($excerpt))
						{
							$excerpt = $post->post_content;
							$exc_length = NOO_Settings()->get_option('noo_event_excerpt_length', 18);
							$excerpt = wp_trim_words( htmlentities($excerpt), $exc_length, '...');
						}

						$excerpt = html_entity_decode( $excerpt );
						$excerpt = do_shortcode($excerpt);
					}

					$event_url = '';
					if ( $noo_schedule_navigate_link != 'disable' ) {
						$event_url = get_permalink();
						if ( $noo_schedule_navigate_link == 'external' ) {
							if ( $register_link != '' ) {
								$event_url = $register_link;
							}
						}
					}

					$post_category = get_the_terms( get_the_ID(), 'event_category' );
					$post_category_id = 0;
					if(!empty($post_category)){
						$post_category = reset($post_category);
						$post_category_id = $post_category->term_id;
					}

					if($default_view === 'agendaDay' && $source === 'both') {
						$post_category_id = 'all';
					}

					$prepare_event = array();
					$prepare_event['id']              = get_the_ID();
					$prepare_event['title']           = get_the_title();
					$prepare_event['start']           = $start_date . 'T' . date_i18n('H:i', $start_time);
					$prepare_event['end']             = $end_date . 'T' . date_i18n('H:i', $end_time);
					$prepare_event['url']             = $event_url;
					$prepare_event['address']         = $address;
					if($default_view === 'agendaDay' && $source === 'both') {
						$prepare_event['resourceId']  = 'all';
					} else {
						$prepare_event['resourceId']  = $post_category_id;
                    }

					$prepare_event['textColor']       = $text_color;
					$prepare_event['backgroundColor'] = $bg_color;
					$prepare_event['borderColor']     = $item_border_style;
					$prepare_event['backgroundImage'] = $bacgroundImage;
					$prepare_event['className']       = $class_name;
					$prepare_event['excerpt']         = $excerpt;
					$prepare_event['register_link']   = $register_link;
					$prepare_event['start_date']      = $start_date;
					$prepare_event['start_time']      = $start_time;
					$prepare_event['end_date']        = $end_date;
					$prepare_event['end_time']        = $end_time;

					$event_repeat = array();
					if ( $recurrence ) {
						// Handle recurrence
						$args = self::get_param_recurrence($recurrence);
						$event_repeat = self::get_repeat_events( $prepare_event, $args);
					} else {
						$list_event[] = $prepare_event;
					}
					$result = wp_raise_memory_limit($context);
					$list_event = array_merge($list_event , $event_repeat);
					// }
				endwhile;
				wp_reset_postdata();
			endif;
			if ( $show === 'json' ) :
				return json_encode( $list_event );
			else :
				return $list_event;
			endif;

		}

		public static function show_list_event($from = '', $to = '', $category = '', $attrs = array(), $show = 'array') {
			global $wpdb;
			extract($attrs);
			/**
			 * VAR
			 */
			$show_all_tab  = isset($show_all_tab) ? $show_all_tab : 'yes';
			$list_event = array();

			$new_cat = '';
			if($category != '')
			{
				if ($category == 'all')
				{
					$new_cat = '';
				}
				else
				{
					$new_cat = $category;
					$category = explode(',', $category);
					if($show_all_tab == 'no'){
						if(is_array($category) && count($category) > 1){
							$new_cat = $category[0];
						}
					}
				}
			}

			$query = "
            SELECT p.*
            FROM $wpdb->postmeta AS tribe_event_start
            LEFT JOIN $wpdb->posts as p ON (tribe_event_start.post_id = p.ID)";
            if($new_cat != '') {
	            $query .= "LEFT JOIN $wpdb->term_relationships as tr ON (tribe_event_start.post_id = tr.object_id)
	            LEFT JOIN $wpdb->term_taxonomy AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
	            LEFT JOIN $wpdb->terms AS t ON (t.term_id = tt.term_id)";
	        }
            $query .= "LEFT JOIN $wpdb->postmeta as tribe_event_end_date ON ( tribe_event_start.post_id = tribe_event_end_date.post_id AND tribe_event_end_date.meta_key = '_noo_event_end_date' )
            WHERE tribe_event_start.meta_key = '_noo_event_start_date'
            AND p.post_type = 'noo_event' ";
            if($new_cat != '') {
	            $query .= "AND tt.taxonomy = 'event_category'
	            AND tr.term_taxonomy_id IN ({$new_cat}) ";
			}
			$query .= "AND p.post_status = 'publish'
            GROUP BY p.ID
            ORDER BY p.post_date
            DESC";

   //          $query = "
   //          SELECT p.*
   //          FROM $wpdb->postmeta AS tribe_event_start
   //          LEFT JOIN $wpdb->posts as p ON (tribe_event_start.post_id = p.ID)
   //          LEFT JOIN $wpdb->term_relationships as tr ON (tribe_event_start.post_id = tr.object_id)
   //          LEFT JOIN $wpdb->term_taxonomy AS tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id)
   //          LEFT JOIN $wpdb->terms AS t ON (t.term_id = tt.term_id)
   //          LEFT JOIN $wpdb->postmeta as tribe_event_end_date ON ( tribe_event_start.post_id = tribe_event_end_date.post_id AND tribe_event_end_date.meta_key = '_noo_event_end_date' )
   //          WHERE tribe_event_start.meta_key = '_noo_event_start_date'
   //          AND p.post_type = 'noo_event'
   //          AND tt.taxonomy = 'event_category' ";
			// if($new_cat != '') {
			// 	$query .= "AND tr.term_taxonomy_id IN ({$new_cat}) ";
			// }
			// $query .= "AND p.post_status = 'publish'
   //          GROUP BY p.ID
   //          ORDER BY p.post_date
   //          DESC";


            //  AND (
            //     (
            //         tribe_event_start.meta_value >= '".strtotime($from)."'
            //         AND tribe_event_start.meta_value <= '".strtotime($to)."'
            //     )
            //     OR (
            //         tribe_event_end_date.meta_value >= '".strtotime($from)."'
            //         AND tribe_event_end_date.meta_value <= '".strtotime($to)."'
            //     )
            //     OR (
            //         tribe_event_start.meta_value < '".strtotime($from)."'
            //         AND tribe_event_end_date.meta_value > '".strtotime($to)."'
            //     )
            // )
			if($default_view == 'month' or $default_view == 'agendaWeek') {
				if($default_view == 'month') {
					$prev_from = date('Y-m-d',( strtotime ( '-1 month' , strtotime ( $from) ) ) );
					$prev_to = date('Y-m-t', strtotime($prev_from));

					$next_from = date('Y-m-d',( strtotime ( '+1 month' , strtotime ( $from ) ) ) );
					$next_to = date('Y-m-t', strtotime($next_from));
				} else {
					$prev_from = date('Y-m-d',( strtotime ( '-1 week' , strtotime ( $from) ) ) );
					$prev_to = date('Y-m-d',( strtotime ( '+1 week' , strtotime ( $prev_from ) ) ) );
					$prev_to = date('Y-m-d',( strtotime ( '-1 days' , strtotime ( $prev_to ) ) ) );

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

			$events = $wpdb->get_results($query, OBJECT);
			
			/**
			 * Process
			 */
			if($events) :

				// Get some settings
				$noo_schedule_event_show_icon  = isset($event_show_icon) ? $event_show_icon : 'yes';
				$noo_schedule_event_item_style = isset($event_item_style) ? $event_item_style : 'background_color';
				$noo_schedule_event_split      = isset($event_split) ? $event_split : 'yes';
				$default_view                  = isset($default_view) ? $default_view : 'agendaWeek';
				$show_excerpt_in_modal         = isset($general_popup_excerpt) ? $general_popup_excerpt : 'yes';
				$noo_schedule_navigate_link    = isset($general_navigate_link) ? $general_navigate_link : 'internal';
				$is_mobile                     = isset($is_mobile) ? $is_mobile : false;
				$context = 'noo_event_array_merge';
				foreach($events as $ev) :
					$end_date = '';
					$recurrence = get_post_meta( $ev->ID, "_recurrence", true );
					$start_date      = self::get_start_date( $ev->ID, 'Y-m-d' );
					$start_time      = get_post_meta( $ev->ID, "_noo_event_start_time", true );
					$end_date        = self::get_end_date( $ev->ID, 'Y-m-d' );

					$end_time        = get_post_meta( $ev->ID, "_noo_event_end_time", true );

					$address         = get_post_meta( $ev->ID, "_noo_event_address", true );

					$start_time = !empty($start_time) ? $start_time : '1470301200';
					$end_time = !empty($end_time) ? $end_time : '1470301200';
					$bg_color = $catColor = get_post_meta( $ev->ID, "_noo_event_bg_color", true );
					$register_link = get_post_meta( $ev->ID, "_noo_event_register_link", true );

					// ClassName
					$class_name = 'md-trigger fc-noo-event';
					if ( $noo_schedule_event_show_icon == 'yes' ) {
						$class_name .= ' show-icon';
					}
					$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($ev->ID), array(800, 600) );
					$popup_bgImage  = isset($feat_image_url[0]) ? $feat_image_url[0] : '';
					$bacgroundImage = null;
					$text_color = '#fff';
					$item_border_style = 'transparent';
					if ( $noo_schedule_event_item_style == 'background_none' ) {
						$bg_color        = '#fff';
						$text_color = 'inherit';					
						
					}elseif($noo_schedule_event_item_style == 'background_image'){
						$bacgroundImage = isset($feat_image_url[0]) ? $feat_image_url[0] : '';
						$bg_color        = '';
						if(empty($bacgroundImage)){
							$bg_color        = '#fff';
							$text_color      = '#000';
						}
					}
					if(empty($bg_color)) $text_color = '#000';
					$excerpt = '';
					if ( $show_excerpt_in_modal == 'yes' ) {

						$excerpt = ($ev->post_excerpt); // remove htmlentities
						if(empty($excerpt))
						{
							$excerpt = $ev->post_content;
							$exc_length = NOO_Settings()->get_option('noo_event_excerpt_length', 18);
							$excerpt = wp_trim_words( $excerpt, $exc_length, '...');
						}
						$excerpt = do_shortcode($excerpt);
					}

					$event_url = '';
					if ( $noo_schedule_navigate_link != 'disable' ) {
						$event_url = get_permalink($ev->ID);
						if ( $noo_schedule_navigate_link == 'external' ) {
							if ( $register_link != '' ) {
								$event_url = $register_link;
							}
						}
					}

					$post_category = get_the_terms( $ev->ID, 'event_category' );
					$post_category_id = 0;
					if(!empty($post_category)){
						$post_category = reset($post_category);
						$post_category_id = $post_category->term_id;
					}
					$prepare_event = array();
					$prepare_event['id']                = $ev->ID;
					$prepare_event['title']             = $ev->post_title;
					$prepare_event['start']             = $start_date . 'T' . date_i18n('H:i', $start_time);
					$prepare_event['end']               = $end_date . 'T' . date_i18n('H:i', $end_time);
					$prepare_event['url']               = $event_url;
					$prepare_event['address']           = $address;
					if($default_view === 'agendaDay' && $source === 'both') {
						$prepare_event['resourceId']    = 'all';
					} else {
						$prepare_event['resourceId']    = $post_category_id;
                    }
					$prepare_event['textColor']         = $text_color;
					$prepare_event['backgroundColor']   = $bg_color;
					$prepare_event['catColor']   	    = $catColor;
					$prepare_event['borderColor']       = $item_border_style;
					$prepare_event['backgroundImage']   = $bacgroundImage;
					$prepare_event['popup_bgImage']     = $popup_bgImage;
					$prepare_event['className']         = $class_name;
					$prepare_event['excerpt']           = $excerpt;
					$prepare_event['register_link']     = $register_link;
					$prepare_event['start_date']        = $start_date;
					$prepare_event['start_time']        = $start_time;
					$prepare_event['end_date']          = $end_date;
					$prepare_event['end_time']          = $end_time;

					$event_repeat = array();
					if ( $recurrence ) {
						// Handle recurrence
						$args = self::get_param_recurrence($recurrence);
						$event_repeat = self::get_repeat_events( $prepare_event, $args);
					} else {
						$list_event[] = $prepare_event;
					}
					$list_event = array_merge($list_event , $event_repeat);
				endforeach;
			endif;

			$result = [];

			if($show == 'array') {
				$result['events_data'] = $list_event;
			} else {
				$result['events_data'] = json_encode($list_event);
			}
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

		/**
		 * Show list event on mobile device
		 *
		 */
		public static function show_list_calender_mobile( $from = '', $to = '', $the_category = '', $attrs = array() )
		{

			extract($attrs);
            $weekends = true;

            $attrs['is_mobile'] = true;

            global $wp_locale;            
            $event_arr = json_decode( self::show_mobile_event('json', $the_category, $attrs), true );
            $new_arr = array();
            foreach ($event_arr as $event) :
                $kq = null;
                // $kq = substr($event['start'],0,10); // Custom
                $kq = str_replace('T', ' ', $event['start']); // Old
                $kq = strtotime($kq);
                
                if ( $kq !== null ) {
                    
                    // Remove event out of range
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
                        'id'                => $event['id'],
                        'title'             => $event['title'],
                        'url'               => $event['url'],
                        'cat_id'            => $event['resourceId'],
                        'address'           => $event['address'],
                        'start'             => $event['start'],
                        'end'               => $event['end'],
                        'start_time'        => date_i18n( get_option( 'time_format' ), strtotime( str_replace('T', ' ', $event['start']) ) ),
                        'end_time'          => date_i18n( get_option( 'time_format' ), strtotime( str_replace('T', ' ', $event['end']) ) ),
                        'textColor'         => $event['textColor'],
                        'backgroundColor'   => $event['backgroundColor'] ? $event['backgroundColor'] : '#929292',
                        'borderColor'       => $event['borderColor'],
                        'weekday'           => $wp_locale->get_weekday(date("w", $kq))
                    );

                }

            endforeach;

            $last_weekday = '';
            ksort($new_arr);
            if ( count($new_arr) > 0 ) {
                foreach ($new_arr as $key => $value) {?>
                    <div class="item-weekday">
						<?php
						echo $wp_locale->get_weekday(date("w", $key));
						if ( 'yes' == $general_header_day ) {
							echo ' (' . date_i18n( get_option( 'date_format' ), $key ) . ')';
						}
						?>
                    </div>
					<?php foreach ($value as $k => $cl) { ?>
                        <div class="item-day fc-noo-event-mobile" style="background-color: <?php echo $cl['backgroundColor'] ?>; color: <?php echo $cl['textColor']; ?>; border: 1px solid <?php echo $cl['borderColor']; ?>">
                            <?php
                            $text_color = $event_item_style == 'background_none' ? '#333' : $cl['textColor'];
                            ?>
                            <a href="<?php echo $cl['url']; ?>" style="color: <?php echo $text_color ?>">
                                <div class="event-time">
									<?php
									if($cl['start_time'] == 'allday' || $cl['end_time'] == 'allday' ) {
										echo '<span style="color: ' . $text_color . '">' . __('all-day', 'noo-timetable') . '</span>';
									} else {
										echo '<span style="color: ' . $text_color . '">' . esc_attr( $cl['start_time']) . ' - </span>';
										echo '<span style="color: ' . $text_color . '">' . esc_attr( $cl['end_time']) . '</span>';
									} ?>
                                </div>
								<?php
								if( $show_category == 'yes' )
									echo '<span class="category">'.esc_html(get_cat_name( $cl['cat_id'] )).'</span>';
								?>
                                <div class="event-title"> <?php echo esc_attr( $cl['title'] ); ?></div>
								<?php if( !empty($cl['address']) ) { ?>
                                    <div class="event-address"><?php echo esc_attr( $cl['address'] ); ?></div>
								<?php } ?>
                            </a>
                        </div>
						<?php
						$last_weekday = $cl['weekday'];
					}
                }
            } else {
                echo '<div style="text-align: center; padding: 30px 0;">' . esc_html__( 'No event for the week!', 'noo-timetable' ) . '</div>';
            }

            //Create label
            $label_start = date_i18n( get_option( 'date_format' ), strtotime($from) );
            $label_end = date_i18n( get_option( 'date_format' ), strtotime($from . ' +6 days') );

            // Current
            $curr_start = $from;
            $curr_end = date('Y-m-d', strtotime($from . ' +6 days') );

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

		public static function _get_full_month( $month ) {
			global $wp_locale;
			return $wp_locale->get_month( $month );
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

		public static function ajax_calendar_mobile() {
			$first_week_day = isset( $_POST['from'] ) ? $_POST['from'] : '';
			$end_week_day   = isset( $_POST['to'] ) ? $_POST['to'] : '';
			$category      = isset( $_POST['the_category'] ) ? $_POST['the_category'] : '';
			$shorcode_attr = isset( $_POST['shorcode_attr'] ) ? json_decode( stripslashes($_POST['shorcode_attr']), true) : array();

			self::show_list_calender_mobile( $first_week_day, $end_week_day, $category, $shorcode_attr );

			exit();
		}

		/**
		 * Get all organizers
		 */
		public static function get_all_organizers() {
			$organizers = array();

			$args = array(
				'post_type'        => 'event_organizers',
				'post_status'      => 'publish',
				'posts_per_page'   => -1,
				'suppress_filters' => 0
			);

			$organizer_query = get_posts($args);

			foreach ( $organizer_query as $organizer ) {

				if( !empty( $organizer->post_title ) ) {

					$organizers[$organizer->ID] = $organizer->post_title;
				}
			}

			return $organizers;
		}

		public static function show_organizer() {

			global $post;

			$organizer_ids   = get_post_meta( $post->ID, "_noo_event_organizers", true );
			if($organizer_ids == '')
				$organizer_ids = array();
			foreach($organizer_ids as $organizer_id)
			{
				$name_author     = get_post_meta($organizer_id, "_noo_event_author", true);
				$avatar_author   = get_post_meta($organizer_id, "_noo_event_avatar", true);
				$phone_author    = get_post_meta($organizer_id, "_noo_event_phone", true);
				$website_author  = get_post_meta($organizer_id, "_noo_event_website", true);
				$email_author    = get_post_meta($organizer_id, "_noo_event_email", true);
				$address_author  = get_post_meta($organizer_id, "_noo_event_address", true);
				$position_author = get_post_meta($organizer_id, "_noo_event_position", true);
				?>

                <div class="noo-event-box-author-wrap">
                    <div class="noo-box-author-head">
						<?php if (!empty($avatar_author)) : ?>
                            <div class="noo-thumbnail-author">
								<?php echo wp_get_attachment_image($avatar_author, 'thumbnail'); ?>
                            </div>
						<?php endif; ?>

                        <div class="noo-info-author">
							<?php if (!empty($name_author)) : ?>
                                <h3 class="noo-name-author">
									<?php echo esc_html($name_author); ?>
                                </h3>
							<?php endif; ?>

							<?php if (!empty($position_author)) : ?>
                                <p class="noo-position-author">
									<?php echo esc_html($position_author); ?>
                                </p>
							<?php endif; ?>
                        </div>
                    </div>

                    <div class="noo-box-author-body">
						<?php if (!empty($phone_author)) : ?>
                            <div class="noo-box-author-item">
                                <i class="fa fa-phone"></i>
                                <span class="phone">
                                <a href="callto://<?php echo esc_html($phone_author); ?>"
                                   title="<?php echo esc_html__('Call Phone', 'noo-timetable') ?>">
                                    <?php echo esc_html($phone_author); ?>
                                </a>
                            </span>
                            </div>
						<?php endif; ?>

						<?php if (!empty($email_author)) : ?>
                            <div class="noo-box-author-item">
                                <i class="fa fa-paper-plane"></i>
                                <span class="email">
                                <a href="mailto:<?php echo esc_html($email_author); ?>"
                                   title="<?php echo esc_html__('Mail To', 'noo-timetable') ?>">
                                    <?php echo esc_html($email_author); ?>
                                </a>
                            </span>
                            </div>
						<?php endif; ?>

						<?php if (!empty($website_author)) : ?>
                            <div class="noo-box-author-item">
                                <i class="fa fa-globe"></i>
                                <span class="web">
                                <a href="<?php echo esc_html($website_author); ?>"
                                   title="<?php echo esc_html__('Visit website', 'noo-timetable') ?>">
                                    <?php echo esc_html($website_author); ?>
                                </a>
                            </span>
                            </div>
						<?php endif; ?>

                    </div>

                </div><!-- /.noo-event-box-author-wrap -->
				<?php
			}
		}

		public static function show_map() {

			global $post;

			$lat     = get_post_meta( $post->ID, "_noo_event_gmap_latitude", true );
			$lng     = get_post_meta( $post->ID, "_noo_event_gmap_longitude", true );
			$address = get_post_meta( $post->ID, "_noo_event_address", true );
			wp_enqueue_script( 'noo-event-maps' );
			?>

			<?php

			$google_map_api_key = NOO_Settings()->get_option( 'noo_google_map_api_key', '' );
			$google_map_latitude = NOO_Settings()->get_option( 'noo_google_map_latitude', '51.508742' );
			$google_map_longitude = NOO_Settings()->get_option( 'noo_google_map_longitude', '-0.120850' );
			$google_zoom = NOO_Settings()->get_option( 'noo_google_map_zoom', '11' );

			$latitude = ( $lat != '' ) ? $lat : $google_map_latitude;
			$longitude = ( $lng != '' ) ? $lng : $google_map_longitude;
			?>

			<?php if (!empty($google_map_api_key)): ?>
                <div class="noo-event-maps" data-lat="<?php echo esc_attr( $latitude ); ?>" data-lng="<?php echo esc_attr( $longitude ); ?>" data-zoom="<?php echo esc_attr( $google_zoom ); ?>"></div>
			<?php else: ?>
                <iframe width="100%" height="483px" frameborder="0" scrolling="no" marginheight="0"
                        marginwidth="0"
                        src="https://maps.google.com/maps?q=<?php echo $latitude; ?>,<?php echo $longitude; ?>&hl=es;z=<?php echo $google_zoom; ?>&amp;output=embed"></iframe>
			<?php endif; ?>
			<?php if (isset($address)) { ?>
            	<address><i class="fa fa-map-marker"></i> <?php echo esc_html( $address ); ?></address>
            <?php } ?>
			<?php
		}

		public static function show_repeat_info() {
			$recurrence = get_post_meta( get_the_ID(), "_recurrence", true );
			if ( $recurrence ) {
				$desc = self::get_description_recurrence( $recurrence );
				$start_on = esc_html__('Start on', 'noo-timetable');
				$start_on .= ' '.self::get_start_date( get_the_ID(), get_option('date_format') );
				printf('<div class="noo-event-meta">');
				printf('<span><i class="fa fa-calendar-plus-o"></i> %s %s</span>', $desc, $start_on);
				printf('</div>');
			}
		}

		public static function load_sidebar_info() {
			$register_link = noo_timetable_get_post_meta( get_the_ID(), "_noo_event_register_link", '' );
			?>
			<?php if ( is_singular('noo_event' ) ) :  ?>
                <!-- Event Information -->
                <section id="event-info-1" class="widget widget_event_info">
                    <h2 class="widget-title"><?php esc_html_e('Event Information', 'noo-timetable'); ?></h2>
					<?php self::show_meta(); ?>
					<?php self::show_repeat_info(); ?>
					<?php
					$recurrence = get_post_meta( get_the_ID(), "_recurrence", true );
					if ( $recurrence ) {
						echo '<div class="noo-event-occurred-on">';
						$args = self::get_param_recurrence($recurrence);
						$prepare_event = self::get_prepare_event(get_the_ID());
						$event_repeat  = self::get_repeat_events( $prepare_event, $args );
						echo esc_html__('Event occurred on:', 'noo-timetable');
						foreach ( $event_repeat as $event ) {
							$est = explode('T', $event['start']);
							$est = $est[0];
							printf('<div>%s</div>', date_i18n( get_option('date_format'), strtotime($est) ) );
						}
						?>
                        <script>
                            jQuery(document).ready(function($){
                                $('.noo-event-occurred-on').readmore({
                                    speed: 200,
                                    collapsedHeight: 148,
                                    moreLink: '<a href="#"><?php echo esc_html__( 'More', 'noo-timetable' ); ?></a>',
                                    lessLink: '<a href="#"><?php echo esc_html__( 'Less', 'noo-timetable' ); ?></a>',
                                });
                            });
                        </script>
						<?php
						echo '</div>';
					}
					?>
					<?php if( !empty( $register_link ) ) : ?>
                        <a href="<?php echo esc_url( $register_link );?>" class="button register_button"><?php echo esc_html__('Register Now', 'noo-timetable');?></a>
					<?php endif; ?>
                </section>

                <!-- Organizer Information -->
                <section id="event-organizer-1" class="widget widget_event_organizer">
                    <h2 class="widget-title"><?php esc_html_e('Organizers', 'noo-timetable'); ?></h2>
					<?php self::show_organizer(); ?>
                </section>

                <!-- Location Information -->
                <section id="event-location-1" class="widget widget_event_location">
                    <h2 class="widget-title"><?php esc_html_e('Location', 'noo-timetable'); ?></h2>
					<?php self::show_map(); ?>
                </section>

			<?php endif; ?>
			<?php
		}

		public static function get_up_comming_events() {
			global $wpdb;
			$events = (array) $wpdb->get_col(
				"SELECT $wpdb->posts.ID
                    FROM $wpdb->posts
                    LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'noo_event' AND $wpdb->postmeta.meta_key = '_noo_event_start_date' AND $wpdb->postmeta.meta_value > UNIX_TIMESTAMP()" );
			return $events;
		}

		public static function get_past_events() {
			global $wpdb;

			$events = (array) $wpdb->get_col(
				"SELECT $wpdb->posts.ID
                    FROM $wpdb->posts
                    LEFT JOIN $wpdb->postmeta ON $wpdb->posts.ID = $wpdb->postmeta.post_id
                    WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'noo_event' AND $wpdb->postmeta.meta_key = '_noo_event_end_date' AND $wpdb->postmeta.meta_value <= UNIX_TIMESTAMP()" );
			return $events;
		}
	}

	new Noo__Timetable__Event();
}
