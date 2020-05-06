<?php
/**
 * Setup for legacy Import
 *
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/Legacy Import
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !class_exists('Noo__Timetable_Legacy_Import') ):

    class Noo__Timetable_Legacy_Import {

    	public function __construct() {
	        $this->_init();
	    }

    	public function _init() {
    		add_action( 'init', array( $this, 'register_post_type' ) );
    		add_action( 'init', array( $this, 'action_handle_cron' ) );
    		add_filter( 'cron_schedules', array( $this, 'filter_add_cron_schedules' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_enqueue_script' ) );
			add_action( 'wp_ajax_noo_preview_all_classes', array( $this, 'ajax_preview_all_classes' ) );
			add_action( 'wp_ajax_noo_import_all_classes', array( $this, 'ajax_import_all_classes' ) );
			add_action( 'wp_ajax_noo_recurring_classes', array( $this, 'ajax_recurring_classes' ) );
			add_action( 'noo-recurring-schedule-import', array( $this, 'processImport' ), 10, 1 );
			add_action( 'save_post', array( $this, 'noo_recurring_save_post' ), 5, 2 );
            add_action( 'wp_trash_post', array( $this, 'noo_recurring_trash_post' ), 10, 1 );
            add_action( 'untrash_post', array( $this, 'noo_recurring_untrash_post' ), 10, 1 );
			//add_action( 'transition_post_status', array( $this, 'noo_recurring_status_transitions' ), 10, 3 );
			if ( is_admin() ) {
				add_action( 'admin_menu', array( &$this, 'sync_import_menu' ), 50 );
				add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ), 30 );
			}
		}

		public function get_recurring_post_meta( $post ) {
			if ( ! $post )
				return false;
			$args = array();
			$post_type = noo_timetable_get_post_meta(  $post->ID, '_import_post_type', 'class' );

			$args['url']              = noo_timetable_get_post_meta(  $post->ID, '_import_url', '' );
			$args['start']            = noo_timetable_get_post_meta(  $post->ID, '_import_start', '' );
			$args['class_category']   = (array) noo_timetable_json_decode( noo_timetable_get_post_meta(  $post->ID, '_import_category', '' ) );
			if ( $post_type == 'class' ) {
				$args['trainer']          = (array) noo_timetable_json_decode( noo_timetable_get_post_meta(  $post->ID, '_import_trainer', '' ) );
			} else {
				$args['color']          = noo_timetable_get_post_meta(  $post->ID, '_import_color', '' );
			}
			$args['post_type_import'] = $post_type;
			$args['domain']           = noo_timetable_get_post_meta(  $post->ID, '_import_domain', '' );
			$args['schedule']         = noo_timetable_get_post_meta(  $post->ID, '_import_frequency', 'every30mins' );
			$args['key']              = intval( noo_timetable_get_post_meta(  $post->ID, '_import_key', '' ) );
			return $args;
		}

		public function noo_recurring_save_post( $post_id, $post ) {
			if ( 'noo_recurring' === get_post_type( $post ) ) {
				if ( isset( $_POST['noo_meta_boxes'] ) ) {
					$args = $this->get_recurring_post_meta( $post );

					// Update schedule
					$this->noo_clear_schedule_event( $args );
					$new_args = $_POST['noo_meta_boxes'];
					$args['start']          = $new_args['_import_start'];
					$args['class_category'] = array_map( 'intval', $new_args['_import_category'] );
					if ( $args['post_type_import'] == 'class' ) {
						$args['trainer']        = array_map( 'intval', $new_args['_import_trainer'] );
					}
					$args['schedule']       = $new_args['_import_frequency'];
					$this->noo_create_schedule_event( $args );
				}
			}
		}

        public function noo_recurring_trash_post( $post_id) {
            $post_type = get_post_type( $post_id );
            if( $post_type !== 'noo_recurring' ) {
                return;
            }
            $post = get_post( $post_id );
            $args = $this->get_recurring_post_meta( $post );
            wp_clear_scheduled_hook( 'noo-recurring-schedule-import-' . $post_id, array( $args ) );
            return true;
        }

        public function noo_recurring_untrash_post($post_id) {
            $post_type = get_post_type( $post_id );
            if( $post_type !== 'noo_recurring' ) {
                return;
            }

            $post = get_post( $post_id );

            $args = $this->get_recurring_post_meta( $post );

            wp_clear_scheduled_hook( 'noo-recurring-schedule-import-' . $post_id, array( $args ) );

            $this->noo_create_schedule_event( $args, $post_id );

            return true;
        }

		public function noo_recurring_status_transitions( $new_status, $old_status, $post ) {
            if ( 'noo_recurring' === get_post_type( $post ) ) {
                return;
            }
				$args = $this->get_recurring_post_meta( $post );
				if ( $new_status == 'publish' && $old_status == 'publish' ) {
					// Do nothing
				} elseif ( $new_status == 'publish' && $old_status == 'new' ) {
					// Do nothing
				} else {
					if ( $new_status != 'publish' ) {
				    	//Clear schedule hook
				    	$this->noo_clear_schedule_event( $args );
				    }
				    else {
				    	//Create schedule hook
				    	$this->noo_create_schedule_event( $args );
				    }
				}
		}

		public function noo_create_schedule_event( $args = array(), $post_id = '' ) {
			// $cron_schedule = self::get_schedule_cron();
			// $time_to_run = noo_timetable_time_now() + intval($cron_schedule[$args['schedule']]['interval']);
			$time_to_run = noo_timetable_time_now();
            if($post_id == '') {
    	    	wp_schedule_event( $time_to_run, $args[ 'schedule' ], 'noo-recurring-schedule-import', array( $args ) );
            } else {
                wp_schedule_event( $time_to_run, $args[ 'schedule' ], 'noo-recurring-schedule-import-' . $post_id, array( $args ) );
            }

		}

		public function noo_clear_schedule_event( $args = array() ) {
			wp_clear_scheduled_hook( 'noo-recurring-schedule-import', array( $args ) );
		}

		public function sync_import_menu() {
			add_submenu_page( 'edit.php?post_type=noo_class', false, '<span class="ntt_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #525252;"></span>', 'manage_options', '#', false);
			add_submenu_page( 'edit.php?post_type=noo_class', __( 'Sync & Import', 'noo-timetable' ),  __( 'Sync & Import', 'noo-timetable' ) , 'edit_theme_options', 'noo-timetable-import-class', array( $this, 'class_output' ) );
			add_submenu_page( 'edit.php?post_type=noo_event', false, '<span class="ntt_menu_section" style="display: block; margin: 1px 0 1px -5px; padding: 0; height: 1px; line-height: 1px; background: #525252;"></span>', 'manage_options', '#', false);
			add_submenu_page( 'edit.php?post_type=noo_event', __( 'Sync & Import', 'noo-timetable' ),  __( 'Sync & Import', 'noo-timetable' ) , 'edit_theme_options', 'noo-timetable-import-event', array( $this, 'event_output' ) );
		}

		public function load_enqueue_script() {
			wp_enqueue_media();
			wp_enqueue_style( 'noo-legacy-import', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/css/noo.legacy.import.css', array(), null, null);
			wp_enqueue_script( 'noo-legacy-import', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/js/noo.legacy.import.js', array(), null, null);
			$nooIcalImport = array(
				'security'         => wp_create_nonce( 'noo-ical-import-nonce' ),
				'ajaxurl'          => admin_url( 'admin-ajax.php', 'relative' ),
				'import_class_url' => get_admin_url( false, '/edit.php?post_type=noo_class&page=noo-timetable-import-class' ),
				'import_event_url' => get_admin_url( false, '/edit.php?post_type=noo_event&page=noo-timetable-import-event' )
			);
			wp_localize_script( 'noo-legacy-import', 'nooIcalImport', $nooIcalImport );
		}

		public function action_handle_cron() {
			if ( isset( $_GET['action'] ) && 'noo-run-cron' == $_GET['action'] ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					wp_die( esc_html__( 'You are not allowed to run cron events.', 'noo-timetable' ) );
				}
				$type = (isset( $_GET['post_type'] ) && $_GET['post_type'] == 'noo_event' ) ? 'event' : 'class';
				$id = wp_unslash( $_GET['id'] );
				$sig = wp_unslash( $_GET['sig'] );
				check_admin_referer( "noo-run-cron_{$id}_{$sig}" );
				if ( $this->run_cron( $id, $sig ) ) {
					$redirect = array(
						'post_type'    => "noo_{$type}",
						'page'         => "noo-timetable-import-{$type}",
						'new_run_cron' => '1',
					);
					wp_redirect( add_query_arg( $redirect, admin_url( 'edit.php' ) ) );
					exit;
				} else {
					$redirect = array(
						'post_type'    => "noo_{$type}",
						'page'         => "noo-timetable-import-{$type}",
						'new_run_cron' => '-1',
					);
					wp_redirect( add_query_arg( $redirect, admin_url( 'edit.php' ) ) );
					exit;
				}
			}
		}

		public static function get_cron_events() {
			$crons  = _get_cron_array();
			$events = array();
			if ( empty( $crons ) ) {
				return new WP_Error(
					'no_events',
					__( 'You currently have no scheduled cron events.', 'noo-timetable' )
				);
			}
			foreach ( $crons as $time => $cron ) {
				foreach ( $cron as $hook => $dings ) {
					foreach ( $dings as $sig => $data ) {
                        if ( strpos( $hook, 'noo-recurring-schedule-import' ) !== false ) {
							$events[ "$hook-$sig-$time" ] = (object) array(
								'hook'     => $hook,
								'time'     => $time,
								'sig'      => $sig,
								'args'     => $data['args'],
								'schedule' => $data['schedule'],
								'interval' => isset( $data['interval'] ) ? $data['interval'] : null,
							);
						}
					}
				}
			}
			return $events;
		}

		public static function time_since( $older_date, $newer_date ) {
			return self::interval( $newer_date - $older_date );
		}

		public static function interval( $since ) {
			// array of time period chunks
			$chunks = array(
				array( 60 * 60 * 24 * 365, _n_noop( '%s year', '%s years', 'noo-timetable' ) ),
				array( 60 * 60 * 24 * 30, _n_noop( '%s month', '%s months', 'noo-timetable' ) ),
				array( 60 * 60 * 24 * 7, _n_noop( '%s week', '%s weeks', 'noo-timetable' ) ),
				array( 60 * 60 * 24, _n_noop( '%s day', '%s days', 'noo-timetable' ) ),
				array( 60 * 60, _n_noop( '%s hour', '%s hours', 'noo-timetable' ) ),
				array( 60, _n_noop( '%s minute', '%s minutes', 'noo-timetable' ) ),
				array( 1, _n_noop( '%s second', '%s seconds', 'noo-timetable' ) ),
			);

			if ( $since <= 0 ) {
				return __( 'now', 'noo-timetable' );
			}

			// we only want to output two chunks of time here, eg:
			// x years, xx months
			// x days, xx hours
			// so there's only two bits of calculation below:

			// step one: the first chunk
			for ( $i = 0, $j = count( $chunks ); $i < $j; $i++ ) {
				$seconds = $chunks[ $i ][0];
				$name = $chunks[ $i ][1];

				// finding the biggest chunk (if the chunk fits, break)
				if ( ( $count = floor( $since / $seconds ) ) != 0 ) {
					break;
				}
			}

			// set output var
			$output = sprintf( translate_nooped_plural( $name, $count, 'noo-timetable' ), $count );

			// step two: the second chunk
			if ( $i + 1 < $j ) {
				$seconds2 = $chunks[ $i + 1 ][0];
				$name2 = $chunks[ $i + 1 ][1];

				if ( ( $count2 = floor( ( $since - ( $seconds * $count ) ) / $seconds2 ) ) != 0 ) {
					// add to output var
					$output .= ' ' . sprintf( translate_nooped_plural( $name2, $count2, 'noo-timetable' ), $count2 );
				}
			}

			return $output;
		}

		public function run_cron( $hookname, $sig ) {
			$crons = _get_cron_array();
			foreach ( $crons as $time => $cron ) {
				if ( isset( $cron[ $hookname ][ $sig ] ) ) {
					$args = $cron[ $hookname ][ $sig ]['args'];
					delete_transient( 'doing_cron' );
					wp_schedule_single_event( noo_timetable_time_now() - 1, $hookname, $args );
					spawn_cron();
					return true;
				}
			}
			return false;
		}

		public function filter_add_cron_schedules( array $schedules ) {

			$schedules['every30mins'] = array(
				'interval' => MINUTE_IN_SECONDS * 30,
				'display'  => __( 'Every 30 minutes', 'noo-timetable' ),
			);
			$schedules['weekly'] = array(
				'interval' => 604800,
				'display' => __( 'Once Weekly', 'noo-timetable' ),
			);
			$schedules['monthly'] = array(
				'interval' => 2635200,
				'display' => __( 'Once Monthly', 'noo-timetable' ),
			);

			return (array) apply_filters( 'noo_timetable_add_cron_schedules', $schedules );
		}

		public static function get_schedule_cron() {

			$cron_schedules = array(
				'every30mins' => array( 'interval' => MINUTE_IN_SECONDS * 30, 'display' => __( 'Every 30 Minutes', 'noo-timetable' ) ),
				'hourly'      => array( 'interval' => HOUR_IN_SECONDS, 'display' => __( 'Hourly', 'noo-timetable' ) ),
				'daily'       => array( 'interval' => DAY_IN_SECONDS, 'display' => __( 'Daily', 'noo-timetable' ) ),
				'weekly'      => array( 'interval' => 604800, 'display' => __( 'Weekly', 'noo-timetable' ) ),
				'monthly'     => array( 'interval' => 2635200, 'display' => __( 'Monthly', 'noo-timetable' ) ),
			);

			return apply_filters( 'noo_timetable_get_cron_schedules', $cron_schedules );
		}

		public function ajax_recurring_classes() {
			check_ajax_referer( 'noo-ical-import-nonce' );
			$args = $_POST;

			$import_type = $args['import_type'];

			$domain = @parse_url( trim( $args[ 'url' ] ) );
			$args['domain']   = ! empty( $domain[ 'host' ] ) ? $domain[ 'host' ] : '';
			unset( $args['action'] );
			unset( $args['_wpnonce'] );

			$array_category = array();
			$array_trainer = array();
			if ( isset($args['class_category']) ) {
				$array_category = array_map('intval', $args['class_category']);
				if ( ! empty($array_category) )
					$args['class_category'] = $array_category;
				else
					$args['class_category'] = array();
			}
			if ( isset($args['trainer']) ) {
				$array_trainer = array_map('intval', $args['trainer']);
				if ( ! empty($array_trainer) )
					$args['trainer'] = $array_trainer;
				else
					$args['trainer'] = array();
			}

			if ( isset($import_type) && $import_type != '0' && $import_type != '1' ) {
				$args['schedule'] = $import_type;
				$args['key'] = noo_timetable_time_now();
				unset( $args['import_type'] );
				$scheduled = $this->noo_create_schedule_event( $args, $post_id );
				if ( $scheduled === false ) {
					return false;
				}
			}

			$recurring = array();
			$recurring['post_status'] = 'publish';
			$recurring['post_type']   = 'noo_recurring';
			$recurring['post_title']  = 'noo_recurring_' . noo_timetable_time_now();
			$recurring['ID'] = wp_insert_post( $recurring, true );

			if ( $recurring['ID'] ) {

				update_post_meta( $recurring['ID'], '_import_post_type', $args['post_type_import'] );
				update_post_meta( $recurring['ID'], '_import_domain', $args['domain'] );
				update_post_meta( $recurring['ID'], '_import_url', $args['url'] );
				update_post_meta( $recurring['ID'], '_import_frequency', $import_type );
				update_post_meta( $recurring['ID'], '_import_start', $args['start'] );
				update_post_meta( $recurring['ID'], '_import_category', $array_category );
				update_post_meta( $recurring['ID'], '_import_trainer', $array_trainer );
				update_post_meta( $recurring['ID'], '_import_color', $args['color'] );
				update_post_meta( $recurring['ID'], '_import_init_date', date('Y-m-d H:i:s') );
				update_post_meta( $recurring['ID'], '_import_key', noo_timetable_time_now() );
				wp_send_json( $recurring );
			}
		}

		public function ajax_import_all_classes() {
			check_ajax_referer( 'noo-ical-import-nonce' );
			$num_imported = $this->processImport();
			wp_send_json( $num_imported );
		}

		public function ajax_preview_all_classes() {
			check_ajax_referer( 'noo-ical-import-nonce' );
			$response = array();

			if ( empty( $args ) ) {
				$args = $_POST;
			}
			$ical = $args['url'];

			$calendar = new Noo_Timetable_iCal_Feed_Parser($ical, $args);
			$events = $calendar->get_events();

			// if ( empty( $events ) ) {
			// 	$errors = array(
			// 		'error' => __( 'Your search returned no events.', 'noo-timetable' ),
			// 	);
			// 	wp_send_json( $errors );
			// }

			$response['body'] = $this->output_preview( $events );
			wp_send_json( $response );
		}

		public function processImport( $args = array() ) {
			if ( empty( $args ) ) {
				$args = $_POST;
			}
			$ical      = $args['url'];
			$post_type = $args['post_type_import'];
			if ( $post_type == 'class' ) {
				$args['post_type'] = 'noo_class';
			} else {
				$args['post_type'] = 'noo_event';
			}
			$calendar = new Noo_Timetable_iCal_Feed_Parser($ical, $args);
			$events = $calendar->get_events();
			if ( isset($events->errors) ) {
				return $events;
			} else {
				if ( $post_type == 'class' ) {
					$num_imported = $this->saveClasses( $events, $args );
				} else {
					$num_imported = $this->saveEvents( $events, $args );
				}
				return $num_imported;
			}
		}

		protected function saveClasses( $events, $args = array() ) {
			$array_category = array();
			$array_trainer = array();
			if ( isset($args['class_category']) ) {
				$array_category = array_map('intval', $args['class_category']);
			}
			if ( isset($args['trainer']) ) {
				$array_trainer = array_map('intval', $args['trainer']);
			}
			$count = 0;
			foreach ( $events as $event ) {
				$event['post_status'] = 'publish';
				$event['post_type']   = 'noo_class';

				if ( ! empty( $event['ID'] ) ) {
					wp_update_post($event);
				} else {
					$event['ID'] = wp_insert_post( $event, true );
				}
				$event_start_date = $event['EventStartDate'];
				$event_open_time = $event['EventStartHour'] .':'. $event['EventStartMinute'];
				$event_close_time = $event['EventEndHour'] .':'. $event['EventEndMinute'];
				if ( $event['EventAllDay'] == 'yes' ) {
					$event_open_time = '00:00';
				}
				update_post_meta( $event['ID'], '_uid', $event['_uid'] );
				update_post_meta( $event['ID'], '_recurrence', $event['recurrence'] );
				update_post_meta( $event['ID'], '_address', $event['Venue']['Venue'] );
				update_post_meta( $event['ID'], '_number_of_weeks', 1 );
				update_post_meta( $event['ID'], '_number_day', array(date('w', strtotime($event_start_date))) );
				update_post_meta( $event['ID'], '_open_date', strtotime($event_start_date) );
				update_post_meta( $event['ID'], '_open_time', strtotime($event_open_time) );
				update_post_meta( $event['ID'], '_close_time', strtotime($event_close_time) );
				update_post_meta( $event['ID'], '_register_link', $event['EventURL'] );
				update_post_meta( $event['ID'], '_trainer', $array_trainer );
				wp_set_object_terms( $event['ID'], $array_category, 'class_category', true );
				$count++;
			}
	        return $count;
		}

		protected function saveEvents( $events, $args = array() ) {
			$array_category = array();
			if ( isset($args['class_category']) ) {
				$array_category = array_map('intval', $args['class_category']);
			}
			$count = 0;
			foreach ( $events as $event ) {
				$event['post_status'] = 'publish';
				$event['post_type']   = 'noo_event';

				if ( ! empty( $event['ID'] ) ) {
					wp_update_post($event);
				} else {
					$event['ID'] = wp_insert_post( $event, true );
				}
				$event_start_date = $event['EventStartDate'];
				$event_end_date = $event['EventEndDate'];
				$event_open_time = $event['EventStartHour'] .':'. $event['EventStartMinute'];
				$event_close_time = $event['EventEndHour'] .':'. $event['EventEndMinute'];
				if ( isset($event['EventAllDay']) && $event['EventAllDay'] == 'yes' ) {
					$event_open_time = '00:00';
				}
				$recurrence = isset($event['recurrence']) ? $event['recurrence'] : '';
				
				update_post_meta( $event['ID'], '_uid', $event['_uid'] );
				update_post_meta( $event['ID'], '_recurrence', $recurrence );
				update_post_meta( $event['ID'], '_noo_event_address', $event['Venue']['Venue'] );
				update_post_meta( $event['ID'], '_noo_event_start_date', strtotime($event_start_date) );
				update_post_meta( $event['ID'], '_noo_event_start_time', strtotime($event_open_time) );
				update_post_meta( $event['ID'], '_noo_event_end_date', strtotime($event_end_date) );
				update_post_meta( $event['ID'], '_noo_event_end_time', strtotime($event_close_time) );
				update_post_meta( $event['ID'], '_noo_event_register_link', $event['EventURL'] );
				update_post_meta( $event['ID'], '_noo_event_bg_color', $args['color'] );
				wp_set_object_terms( $event['ID'], $array_category, 'event_category', true );
				$count++;
			}
	        return $count;
		}

		public static function class_output() {
			$type = 'class';
			include 'html-settings-output.php';
        }

        public static function event_output() {
        	$type = 'event';
			include 'html-settings-output.php';
        }

        public static function list_recurring_imports( $type = 'class' ) {
        	$recurrings = get_posts( array(
				'post_type'        => 'noo_recurring',
				'posts_per_page'   => -1,
				'suppress_filters' => 0,
				'meta_key'         => '_import_post_type',
				'meta_value'       => $type
        	) );
        	$events = self::get_cron_events();

        	?>
        	<div id="col-right" class="recurring-import-ical">
				<h2><?php esc_html_e( 'Saved Recurring Imports', 'noo-timetable' ); ?></h2>
				<table class="widefat striped">
					<thead>
					<tr>
						<th scope="col">&nbsp;</th>
						<th scope="col"><?php esc_html_e( 'Domain', 'noo-timetable' ); ?></th>
						<th scope="col" width="14%"><?php esc_html_e( 'Import Start', 'noo-timetable' ); ?></th>
						<th scope="col" width="14%"><?php esc_html_e( 'Create Date', 'noo-timetable' ); ?></th>
						<th scope="col" width="14%"><?php esc_html_e( 'Next Run', 'noo-timetable' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Recurrence', 'noo-timetable' ); ?></th>
						<th scope="col" width="10%"><?php esc_html_e( 'Action', 'noo-timetable' ); ?></th>
					</tr>
					</thead>
					<tbody>
						<?php
							if ( $recurrings ) {
								foreach ( $recurrings as $key => $recurring ) {
									$post_type = noo_timetable_get_post_meta( $recurring->ID, '_import_post_type', 'class' );
									if ( $post_type == $type ) {
										$url            = noo_timetable_get_post_meta( $recurring->ID, '_import_url', '' );
										$init_date      = noo_timetable_get_post_meta(  $recurring->ID, '_import_init_date', '-' );
										$start          = noo_timetable_get_post_meta(  $recurring->ID, '_import_start', '' );
										$domain         = noo_timetable_get_post_meta(  $recurring->ID, '_import_domain', '' );
										$schedule       = noo_timetable_get_post_meta(  $recurring->ID, '_import_frequency', 'every30mins' );
										$import_key     = intval( noo_timetable_get_post_meta(  $recurring->ID, '_import_key', '' ) );

										$event_hook = $event_sig = '';
										$next_run = noo_timetable_time_now();
										foreach ( $events as $event ) {
							        		if ( $event->args[0]['key'] == $import_key ) {
												$next_run   = $event->time;
												$event_hook = $event->hook;
												$event_sig  = $event->sig;
						        				break;
						        			}
							        	}

										echo '<tr>';
											echo '<td>' . ($key + 1) . '</td>';
											echo '<td>';
												echo $domain;
											echo '</td>';
											echo '<td>' . $start . '</td>';
											echo '<td>' . $init_date . '</td>';
											echo '<td>';
												printf( '%s (%s)',
													esc_html( get_date_from_gmt( date( 'Y-m-d H:i:s', $next_run ), 'Y-m-d H:i:s' ) ),
													esc_html( self::time_since( noo_timetable_time_now(), $next_run ) )
												);
											echo '</td>';
											echo '<td>' . $schedule . '</td>';
											echo '<td>';
												$link = array(
													'post_type' => "noo_{$type}",
													'page'      => "noo-timetable-import-{$type}",
													'action'    => 'noo-run-cron',
													'id'        => urlencode( $event_hook ),
													'sig'       => urlencode( $event_sig ),
												);
												$link = add_query_arg( $link, admin_url( 'edit.php' ) );
												$link = wp_nonce_url( $link, "noo-run-cron_{$event_hook}_{$event_sig}" );

												echo '<a href="'. esc_url( $link ) .'">' . esc_html__('Run Now', 'noo-timetable') . '</a><br/>';

												$link = array(
													'post_type' => "noo_{$type}",
													'action'    => 'edit',
													'post'      => $recurring->ID,
												);
												$link = add_query_arg( $link, admin_url( 'post.php' ) );

												echo '<a href="'. esc_url( $link ) .'">' . esc_html__('Edit', 'noo-timetable') . '</a>';
											echo '</td>';
										echo '</tr>';
									}
					        	}
					        } else {
								echo '<tr>';
									echo '<td></td>';
									echo '<td colspan="5"><em>'. esc_html__('No data.', 'noo-timetable') .'</em></td>';
								echo '</tr>';
							}
						?>
					</tbody>
				</table>
			</div>
        	<?php
        }

        public function output_preview( $events = array() ) {
    		ob_start();
    		?>
			<div class="tablenav top">
				<div class="tablenav-pages one-page">
					<span class="displaying-num">
					<?php
						if ( $events )
							printf( '%s Items', count($events) );
					?>
					</span>
				</div>
			</div>
			<table class="widefat striped">
				<thead>
				<tr>
					<th scope="col">&nbsp;</th>
					<th scope="col"><?php esc_html_e( 'Event', 'noo-timetable' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Start Date', 'noo-timetable' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Time', 'noo-timetable' ); ?></th>
				</tr>
				</thead>
				<tbody>
					<?php
					if ( $events ) :
					foreach ( $events as $k => $event ) :
						$event_start_date = isset($event['EventStartDate']) ? $event['EventStartDate'] : '';

						$event_start_hour = isset($event['EventStartHour']) ? $event['EventStartHour'] : '';
						$event_start_min = isset($event['EventStartMinute']) ? $event['EventStartMinute'] : '';

						$event_end_hour = isset($event['EventEndHour']) ? $event['EventEndHour'] : '';
						$event_end_min = isset($event['EventEndMinute']) ? $event['EventEndMinute'] : '';

						$event_open_time  = $event_start_hour .':'. $event_start_min;
						$event_close_time = $event_end_hour .':'. $event_end_min;

						if ( isset($event['EventAllDay']) && $event['EventAllDay'] == 'yes' ) {
							$event_time = esc_html__('All day', 'noo-timetable');
						} else {
							$event_time = $event_open_time . ' - ' . $event_close_time;
						}
					?>
					<tr>
						<td><?php echo ($k + 1); ?></td>
						<td><?php echo isset($event['post_title']) ? $event['post_title'] : ''; ?></td>
						<td><?php echo $event_start_date; ?></td>
						<td><?php echo $event_time ?></td>
					</tr>
					<?php
					endforeach;
					else:
						?>
					<tr>
						<td>&nbsp;</td>
						<td><?php echo __( 'No events.', 'noo-timetable' ); ?></td>
						<td></td>
						<td></td>
					</tr>
					<?php
					endif;
					?>
				</tbody>
			</table>
    		<?php
        	return ob_get_clean();
        }

        public function register_post_type() {
        	if ( post_type_exists('noo_recurring') ) {
				return;
			}

			register_post_type( 'noo_recurring',
				array(
					'labels'              => array(
							'name'                  => __( 'Recurring Imports', 'noo-timetable' ),
							'singular_name'         => __( 'Recurring Import', 'noo-timetable' ),
							'menu_name'             => _x( 'Recurring Imports', 'Admin menu name', 'noo-timetable' ),
							'add_new'               => __( 'Add Recurring Import', 'noo-timetable' ),
							'add_new_item'          => __( 'Add New Recurring Import', 'noo-timetable' ),
							'edit'                  => __( 'Edit', 'noo-timetable' ),
							'edit_item'             => __( 'Edit Recurring Import', 'noo-timetable' ),
							'new_item'              => __( 'New Recurring Import', 'noo-timetable' ),
							'view'                  => __( 'View Recurring Import', 'noo-timetable' ),
							'view_item'             => __( 'View Recurring Import', 'noo-timetable' ),
							'search_items'          => __( 'Search Recurring Imports', 'noo-timetable' ),
							'not_found'             => __( 'No Recurring Imports found', 'noo-timetable' ),
							'not_found_in_trash'    => __( 'No Recurring Imports found in trash', 'noo-timetable' ),
							'parent'                => __( 'Parent Recurring Import', 'noo-timetable' ),
							'featured_image'        => __( 'Recurring Import Image', 'noo-timetable' ),
							'set_featured_image'    => __( 'Set Recurring Import image', 'noo-timetable' ),
							'remove_featured_image' => __( 'Remove Recurring Import image', 'noo-timetable' ),
							'use_featured_image'    => __( 'Use as Recurring Import image', 'noo-timetable' ),
							'insert_into_item'      => __( 'Insert into Recurring Import', 'noo-timetable' ),
							'uploaded_to_this_item' => __( 'Uploaded to this Recurring Import', 'noo-timetable' ),
							'filter_items_list'     => __( 'Filter Recurring Imports', 'noo-timetable' ),
							'items_list_navigation' => __( 'Recurring Imports navigation', 'noo-timetable' ),
							'items_list'            => __( 'Recurring Imports list', 'noo-timetable' ),
						),
					'description'         => __( 'This is where you can add new recurring import.', 'noo-timetable' ),
					'public'              => true,
                    'show_in_nav_menus'   => false,
                    'show_in_menu'   	  => false,
					'show_ui'             => true,
					'map_meta_cap'        => true,
					'publicly_queryable'  => true,
					'exclude_from_search' => false,
					'rewrite'             => false,
					'query_var'           => true,
					'supports'            => array( 'title' ),
					'has_archive'         => true
				)
			);
        }

        public function add_meta_boxes() {

        	$category_options = array();
        	$categories = get_terms('event_category');
        	if ( !empty($categories) ) {
				foreach ($categories as $cate){
					$category_options[$cate->term_id] = $cate->name;
				}
			}

			$trainers = get_posts(
				array(
					'post_type'        => 'noo_trainer',
					'posts_per_page'   => -1,
					'post_status'      => 'publish',
					'suppress_filters' => 0
				)
			);
			if ( !empty($trainers) ) {
				foreach ($trainers as $trainer){
					$trainer_options[$trainer->ID] = $trainer->post_title;
				}
			}

        	$helper = new Noo__Timetable_Meta_Boxes_Helper( '_noo_wp_recurring', array(
				'page' => 'noo_recurring'
			));

			$meta_box = array(
				'id'          => "recurring_settings",
				'title'       => esc_html__( 'Recurring Import Settings', 'noo-timetable') ,
				'fields'      => array(
					array(
						'id'       => '_import_post_type',
						'label'    => esc_html__( 'Post Type', 'noo-timetable' ),
						'type'     => 'text',
						'callback' => array( &$this, 'meta_box_readonly' )
					),
					array(
						'id'       => '_import_init_date',
						'label'    => esc_html__( 'Initiated', 'noo-timetable' ),
						'type'     => 'text',
						'callback' => array( &$this, 'meta_box_readonly' )
					),
					array(
						'id'       => '_import_domain',
						'label'    => esc_html__( 'Domain', 'noo-timetable' ),
						'type'     => 'text',
						'callback' => array( &$this, 'meta_box_readonly' )
					),
					array(
						'id'       => '_import_url',
						'label'    => esc_html__( 'URL', 'noo-timetable' ),
						'type'     => 'text',
						'callback' => array( &$this, 'meta_box_readonly' )
					),
					array(
						'id'       => '_import_frequency',
						'label'    => esc_html__( 'Frequency', 'noo-timetable' ),
						'type'     => 'dropdown',
						'options'  => self::get_schedule_cron(),
						'callback' => array( &$this, 'meta_box_frequency' )
					),
					array(
						'id'       => '_import_start',
						'label'    => esc_html__( 'Start', 'noo-timetable' ),
						'type'     => 'datepicker',
						'callback' => array( &$this, 'meta_box_datepicker' )
					),
					array(
						'id'       => '_import_category',
						'label'    => esc_html__( 'Categories', 'noo-timetable' ),
						'type'     => 'select_multiple_chosen',
						'options'  => $category_options,
						'callback' => array( &$this, 'meta_box_select_multiple' )
					),
					array(
						'id'       => '_import_trainer',
						'label'    => esc_html__( 'Trainers', 'noo-timetable' ),
						'type'     => 'select_multiple_chosen',
						'options'  => $trainer_options,
						'callback' => array( &$this, 'meta_box_select_multiple' )
					),

				)
			);

			$helper->add_meta_box($meta_box);
        }

        public function meta_box_readonly( $post, $id, $type, $meta, $std, $field ) {
        	if ( ! empty($meta) ) {
        		echo $meta;
        	} else {
        		echo '-';
        	}
        }

        public function meta_box_datepicker( $post, $id, $type, $meta, $std, $field ) {

			wp_enqueue_script( 'datetimepicker' );
			wp_enqueue_style( 'datetimepicker' );
			$date_format = 'Y-m-d';
			$date_text = is_numeric( $meta ) ? date( $date_format, $meta ) : $meta;

			echo '<div>';
			echo '<input type="text" readonly class="input_text" id="' . $id . '" name="noo_meta_boxes[' . $id . ']" value="' .
				esc_attr( $date_text ) . '" /> ';
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
					});
				});
			</script>
		<?php
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

		public function meta_box_frequency($post, $id, $type, $meta, $std, $field) {
			$cron_schedules = self::get_schedule_cron();
			$meta = $meta ? $meta : $std;
			echo'<select id='.$id.' name="noo_meta_boxes[' . $id . ']" >';
				foreach ( $cron_schedules as $key => $value ) {
					?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php if ( $meta == $key ) echo ' selected="selected"'; ?>>
						<?php echo esc_html__( 'Recurring', 'noo-timetable' ) . ': ' . $value[ 'display' ]; ?>
					</option>
					<?php
				}
			echo '</select>';
		}

    }
    new Noo__Timetable_Legacy_Import();

endif;

add_action( 'after_setup_theme', 'noo_timetable_ical_load' );
if ( ! function_exists('noo_timetable_ical_load') ) {
	function noo_timetable_ical_load() {
		$plugin_path = Noo__Timetable__Main::plugin_path();
		if ( ! class_exists('iCalVCALENDAR') ) {
			require( $plugin_path . '/inc/framework/add-ons/ical_sync/vendor/iCalcreator_3.0/iCalcreator.php' );
		}
		require_once( $plugin_path . '/inc/legacy-import/Utils.php' );
		require_once( $plugin_path . '/inc/legacy-import/Utils/Uid.php' );
		require_once( $plugin_path . '/inc/legacy-import/Utils/Timezone_Parser.php' );
		require_once( $plugin_path . '/inc/legacy-import/Utils/Headers.php' );
		require_once( $plugin_path . '/inc/legacy-import/iCal_Parser.php' );
	}
}
