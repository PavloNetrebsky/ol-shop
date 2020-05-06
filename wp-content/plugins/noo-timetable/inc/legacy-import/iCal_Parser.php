<?php

class Noo_Timetable_iCal_Feed_Parser extends vcalendar {
	public $events = array();
	public $calendar = array();
	public $raw_events = array();

	public $source;
	public $args = array(
		'start'     => 'now',
		'end'       => '+2 year',
		'count'     => '',
		'keywords'  => '',
		'location'  => '',
		'category'  => '',
		'manual'    => false,
		'post_type' => 'noo_class',
	);
	protected $utils;

	public $previous_parents = array();
	public $used_uids = array();

	public $timezone;

	public static $retrieved_geo_coordinates = array();

	public $matched_search_criteria = array(
		'startDate' => false,
		'keywords'  => false,
		'location'  => false,
		'category'  => false,
	);

	private $criteria = array(
		'start'    => false,
		'end'      => false,
		'category' => false,
	);

	public $lat;
	public $long;

	public function __construct( $ical, $args = array()) {
		$this->utils = Noo_Timetable_Ical_Importer__Utils::instance();
		$this->args = wp_parse_args( $args, $this->args );
		$is_remote  = false;
		$ical = str_replace( 'webcal://', 'http://', $ical );

		parent::__construct();

		if ( file_exists( $ical ) ) {
			$content = file_get_contents( $ical );
		} else {
			$is_remote = true;
			$content = $this->get_remote_content( $ical );
		}

		// Content could not be loaded: an appropriate error should already have been logged
		
		if ( false === $content ) {
			return;
		}

		$calendar = $this->parse( $content );
		$this->calendar = $calendar;
		
		// Report errors according to whether the source was local or remote
		if ( ! $calendar && $is_remote ) {
			$this->events = new WP_Error( 1, __( 'No iCal data was found at this URL.', 'noo-timetable' ) );
		} elseif ( ! $calendar ) {
			$this->events = new WP_Error( 1, __( 'Not a valid .ics file', 'noo-timetable' ) );
		}

		// If we did hit an error, do not go any further
		if ( ! $calendar ) {
			return;
		}

		// Set the source to be used later
		if ( ! empty( $this->source[1] ) ) {
			$this->source = $this->source[1];
		} else {
			$this->source = $this->getProperty( 'X-WR-CALNAME' );
		}

		$this->parse_events();
	}

	protected function get_remote_content( $url ) {
		$url = str_replace( 'webcal://', 'http://', $url );
		$timeout_in_seconds = 5;

		$response = null;

		foreach ( $this->utils->headers() as $headers ) {
		$request_args = array(
			'timeout'     => $timeout_in_seconds,
			'sslverify'   => false,
			'redirection' => 4,
			'method'      => 'GET',
			'headers'     => $headers,
		);

		$response = wp_remote_get( $url, $request_args );

			if ( ! is_wp_error( $response ) ) {
				break;
			}

		if ( is_wp_error( $response ) ) {
			// let's try again with `sslverify` set to `true`
			$request_args['sslverify'] = true;
				$response                  = wp_remote_head( $url, $request_args );

				if ( ! is_wp_error( $response ) ) {
					break;
				}
			}
		}

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) != 200 ) {
			$this->events = new WP_Error( 1, __( 'Unable to retrieve content from the provided URL.', 'noo-timetable' ) );

			return false;
		}

		return $response['body'];
	}

	public function check_for_existing( $event ) {
		global $wpdb;

		$posts = get_posts( array(
			'post_type'   => $this->post_type(),
			'post_status' => get_post_stati(),
			'meta_query'  => array(
				array(
					'key'   => '_uid',
					'value' => $event['_uid'],
				),
			),
			'fields'      => 'ids',
			'numberposts' => '1',
		) );

		if ( ! empty( $posts[0] ) ) {
			return $posts[0];
		}
		return false;
	}

	public function check_if_event_is_in_radius( $geo, $radius ) {
		if ( empty( $this->lat ) || empty( $this->long ) ) {
			return false;
		}

		list( $lat, $long ) = explode( ';', $geo );

		if ( (float) $this->lat == (float) $lat && (float) $this->long == (float) $long ) {
			return true;
		}

		//take our km and convert back to miles
		$radius = $radius * 0.621371;

		$longitude = (float) $this->long;
		$latitude  = (float) $this->lat;

		$minLng = $longitude - $radius / abs( cos( deg2rad( $latitude ) ) * 69 );
		$maxLng = $longitude + $radius / abs( cos( deg2rad( $latitude ) ) * 69 );
		$minLat = $latitude - ( $radius / 69 );
		$maxLat = $latitude + ( $radius / 69 );


		if ( ( (float) $lat > (float) $minLat ) && ( (float) $lat < (float) $maxLat ) ) {

			if ( ( (float) $long > (float) $minLng ) && ( (float) $long < (float) $maxLng ) ) {
				return true;
			}
		}

		return false;
	}

	public function get_geo_coordinates( $event ) {
		$geo = $event->getProperty( 'GEO' );
		if ( ! empty( $geo ) ) {
			 return $geo;
		}

		$location = $event->getProperty( 'LOCATION' );
		$location = $this->replace_linebreaks_with_spaces($location);
		if ( empty( $location ) ) {
			return false;
		}

		//get previously fetched coordinates
		if ( empty( self::$retrieved_geo_coordinates ) ) {
			self::$retrieved_geo_coordinates = (array) get_transient( 'noo-ical-importer-geo' );
		}


		if ( array_key_exists( $location, self::$retrieved_geo_coordinates ) ) {
			return self::$retrieved_geo_coordinates[ $location ];
		}

		//get a new location coordinates
		$geo = wp_remote_get( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $location ) );

		if ( is_wp_error( $geo ) ) {
			self::$retrieved_geo_coordinates[ $location ] = false;

			return false;
		}

		// Ensure $geo contains the expected array (it could be a WP_Error) before tring to parse
		if ( is_array( $geo ) && ! empty( $geo['body'] ) ) {
			$geo = json_decode( $geo['body'] );

			if ( ! empty( $geo->results[0]->geometry->location->lat ) ) {
				$lat  = $geo->results[0]->geometry->location->lat;
				$long = $geo->results[0]->geometry->location->lng;

				return self::$retrieved_geo_coordinates[ $location ] = "$lat;$long";
			}
		} else {
			self::$retrieved_geo_coordinates[ $location ] = false;

			return false;
		}
	}

	public function lat_long_set( $location ) {
		$geo = wp_remote_get( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $location ) );

		if ( ! empty( $geo['body'] ) ) {
			$geo = json_decode( $geo['body'] );
		}

		if ( ! empty( $geo->results[0]->geometry->location->lat ) ) {
			$this->lat = $geo->results[0]->geometry->location->lat;
		}

		if ( ! empty( $geo->results[0]->geometry->location->lng ) ) {
			$this->long = $geo->results[0]->geometry->location->lng;
		}
	}

	public function get_raw_events_by_range() {

		$date        = strtotime( $this->args['start'] );
		$start_month = date( 'm', $date );
		$start_year  = date( 'Y', $date );
		$start_day   = date( 'd', $date );

		$date      = strtotime( $this->args['end'] );
		$end_month = date( 'm', $date );
		$end_year  = date( 'Y', $date );
		$end_day   = date( 'd', $date );

		return (array) $this->selectComponents( $start_year, $start_month, $start_day, $end_year, $end_month, $end_day, 'vevent' );
	}

	public function manual() {
		if ( $this->args['manual'] ) {
			return true;
		}

		return false;
	}

	public function post_type() {
		return $this->args['post_type'];
	}

	public static function wp_timezone_string() {
		$current_offset = get_option( 'gmt_offset' );
		$tzstring       = get_option( 'timezone_string' );

		// Return the timezone string if already set
		if ( ! empty( $tzstring ) ) {
			return $tzstring;
		}

		// Otherwise return the UTC offset
		if ( 0 == $current_offset ) {
			return 'UTC+0';
		} elseif ( $current_offset < 0 ) {
			return 'UTC' . $current_offset;
		}

		return 'UTC+' . $current_offset;
	}

	private function clean_x_date_str( array $x_date_str_array ) {
		if ( 2 !== count( $x_date_str_array ) ) {
			return $x_date_str_array;
		}

		//remove start and end white space
		$date_str = trim( end( $x_date_str_array ) );

		//remove anything after the first white space in the timestamp:
		// 20160825T130000 2 becomes 20160825T130000
		// 20160825T 2 becomes 20160825T
		$date_str = substr( $date_str, 0, strrpos($date_str, ' '));

		return array( $x_date_str_array[0], $date_str );
	}

	public function inject_linebreaks($string) {
		return preg_replace( '/(?<!\\\\)\\\\n/', "$1\n", $string );
	}

	public function replace_linebreaks_with_spaces($string) {
		return preg_replace( '/(?<!\\\\)\\\\n/', '$1 ', $string );
	}

	private function get_venue_data_array( iCalVEVENT $event ) {
		$google_maps_enabled = 'false';
		$data_array = array(
			'Venue'            => $this->replace_linebreaks_with_spaces( $event->getProperty( 'LOCATION' ) ),
			'ShowMap'          => $google_maps_enabled,
			'ShowMapLink'      => $google_maps_enabled,
			'EventShowMap'     => $google_maps_enabled,
			'EventShowMapLink' => $google_maps_enabled,
		);

		return $data_array;
	}

	public function store_date_as_utc( $event ) {
		foreach ( array( 'Start', 'End' ) as $which ) {
			$date                                  = $event[ 'Event' . $which . 'Date' ];
			$hour                                  = $event[ 'Event' . $which . 'Hour' ];
			$minute                                = $event[ 'Event' . $which . 'Minute' ];
			$date                                  = "{$date} {$hour}:{$minute}:00";
			$event[ 'Event' . $which . 'DateUTC' ] = $date;
		}

		return $event;
	}

	public function convert_vcal_event_to_importable_event( iCalVEVENT $event ) {
		//child events of a recurring event
		$child_event = $event->getProperty( 'X-RECURRENCE' );
		
		$uid = $this->utils->generate_uid_for_event( $event );

		//if we are doing a full import and have PRO installed child events will cause crazy loops of events
		if ( ! $this->manual() ) {
			if ( in_array( $uid, $this->previous_parents ) ) {
				 return;
			}

			$this->previous_parents[] = $uid;

		//if PRO is not installed all we care about is if this event is the first in a series
		} else {
			//if we have not seen this uid before this must not be a child
			if ( ! in_array( $uid, $this->previous_parents ) ) {
				$child_event              = false;
				$this->previous_parents[] = $uid;
			}
		}

		// get a few timezones we care about
		$system_timezone = date_default_timezone_get();
		$wp_timezone     = self::wp_timezone_string();

		$start_data = $event->getProperty( 'DTSTART', false, true );
		$start_str  = $start_data['value'];
		$start      = iCalPropertyFactory::strdate2arr( $start_str );
		$start_time = strtotime( $start_str );

		$end_str  = $event->getProperty( 'DTEND' );
		$end      = iCalPropertyFactory::strdate2arr( $end_str );
		$end_time = strtotime( $end_str );
		// Let's determine if this is an all-day event, whether it's spanning on multiple days or not	

		$is_all_day = ! isset( $start['hour'] );
		
		// let's determine what timezone the event is represented as
		$timezone = null;
		if ( ! empty( $start['tz'] ) ) {
			// if the start date is in UTC time (with the Z at the end) then set the timezone as UTC
			// BUT ONLY IF there isn't an X-WR-TIMEZONE because the iCal importer will convert to the
			// X-WR-TIMEZONE for us when if x_start_time is set
			$timezone = 'UTC';
		} elseif ( preg_match( '/TZID=([^;]+)/', $start_data['params'], $matches ) ) {
			$is_valid = $this->utils->is_valid_php_timezone( $matches[1] );
			if ( $is_valid ) {
				$timezone = $matches[1];
			} else {
				$timezone = $this->timezone;
			}
		} elseif ( ! empty( $this->timezone ) ) {
			// if there is a global timezone in the iCal file, use that
			$timezone = $this->timezone;
		}

		// Set time ID
		$time_id      = $start_str;
		$is_multi_day = ( $start['day'] != $end['day'] );

		// Fetch the generated X-CURRENT-DTSTART|DTEND which is useful primarily for repeating events, but is
		// available for any. If it isn't delcared, it is generated. The PROBLEM with the result is that
		// it ALWAYS assumes that the datetime string that is provided (via the feed and within iCalCreator)
		// is relative to the current system timezone regardless of whether or not the feed is representing
		// it as Zulu time or not. We'll use the maybe_convert_from_system_timezone to correct that
		$x_start_str  = (array) $event->getProperty( 'X-CURRENT-DTSTART' );
		$x_start_str  = $this->clean_x_date_str( $x_start_str );
		// $x_start_str  = $this->maybe_convert_from_system_timezone( end( $x_start_str ), $system_timezone, $timezone );
		$x_start_time = !empty($x_start_str) && !is_array($x_start_str) ? strtotime( $x_start_str ) : '';

		$x_end_str  = (array) $event->getProperty( 'X-CURRENT-DTEND' );
		$x_end_str  = $this->clean_x_date_str( $x_end_str );
		// $x_end_str  = $this->maybe_convert_from_system_timezone( end( $x_end_str ), $system_timezone, $timezone );

		$x_end_time = !empty($x_end_str) && !is_array($x_end_str) ? strtotime( $x_end_str ) : '';

		//if this event has an X-CURRENT-DTSTART the correct date will be X-CURRENT-DTSTART
		if ( ! empty( $x_start_time ) && ! empty( $x_end_time ) ) {
			$start = iCalPropertyFactory::strdate2arr( $x_start_str );

			// iCalCreator will generate the X-CURRENT-DTEND property if not set: sometimes that's wrong.
			// Let's sanity check it against the DTEND.
			$start_time_diff = $x_start_time - $start_time;
			if ( ( $x_end_time - $end_time ) === $start_time_diff ) {
				$end = iCalPropertyFactory::strdate2arr( $x_end_str );
			}

			// Set time ID
			$time_id = $x_start_str;
		}

		//child events need a id unique to event not just series
		if ( ! empty( $child_event ) && ! $this->args['manual'] ) {
			$uid = $event->getProperty( 'UID' ) . '-' . $time_id;
		}

		//there should be no reason we have duplicates
		if ( in_array( $uid, $this->used_uids ) ) {
			return;
		}

		$this->used_uids[] = $uid;

		// All day event
		if ( $is_all_day ) {
			$all_day       = true;
			$start['hour'] = 0;
			$start['min']  = 1;
			$end['hour']   = 23;
			$end['min']    = 59;
			/**
			 * all day events come in as ending the next day
			 * set the end to one day less so multiple day events still work and single
			 * events only span one day
			 */
			if ( $start['day'] != $end['day'] ) {
				$end['day'] = $end['day'] - 1;
			}
		} else {
			$all_day = false;
		}

		/**
		 * If we don't have time, just set it to zero which is a safer call
		 * This will prevent strtotime not understanding the pattern
		 */
		if ( empty( $start['hour'] ) ) {
			$start['hour'] = '0';
		}

		if ( empty( $start['min'] ) ) {
			$start['min'] = '0';
		}

		if ( empty( $start['sec'] ) ) {
			$start['sec'] = '0';
		}

		if ( empty( $end['hour'] ) ) {
			$end['hour'] = '0';
		}

		if ( empty( $end['min'] ) ) {
			$end['min'] = '0';
		}

		if ( empty( $end['sec'] ) ) {
			$end['sec'] = '0';
		}

		$google_maps_enabled = false;
		
		$post                = array(
			'post_title'       => $this->replace_linebreaks_with_spaces( $event->getProperty( 'SUMMARY' ) ),
			'post_type'        => $this->post_type(),
			'post_content'     => $this->inject_linebreaks( $event->getProperty( 'DESCRIPTION' ) ),
			'EventStartDate'   => sprintf( "%'.04d-%'.02d-%'.02d", $start['year'], $start['month'], $start['day'] ),
			'EventEndDate'     => sprintf( "%'.04d-%'.02d-%'.02d", $end['year'], $end['month'], $end['day'] ),
			'EventStartHour'   => $start['hour'],
			'EventEndHour'     => $end['hour'],
			'EventStartMinute' => $start['min'],
			'EventEndMinute'   => $end['min'],
			'EventURL'         => $event->getProperty( 'URL' ),
			'Venue'            => $this->get_venue_data_array( $event ),
			'_uid'             => $uid,
			'dev_start'        => $start,
			'dev_end'          => $end,
			'EventShowMap'     => $google_maps_enabled,
			'EventShowMapLing' => $google_maps_enabled,
		);

		// set the EventAllDay property on the event
		if ( $is_all_day ) {
			$post['EventAllDay'] = 'yes';
		}

		// convert the event's timezone to the WP timezone
		if ( ! empty( $timezone ) && 'UTC' === $timezone ) {
			$post                  = $this->store_date_as_utc( $post );
			// $post                  = $this->generate_timezone_date_from_utc( $post, $wp_timezone );
			$post['EventTimezone'] = $wp_timezone;
		} elseif ( ! empty( $timezone ) ) {
			// $post                  = $this->generate_utc_from_timezone_date( $post, $timezone );
			// $post                  = $this->generate_timezone_date_from_utc( $post, $wp_timezone );
			$post['EventTimezone'] = $wp_timezone;
		} else {
			// $post                  = $this->generate_utc_from_timezone_date( $post, $wp_timezone );
			$post['EventTimezone'] = $wp_timezone;
		}

		// Add geoloc coordinates if we can find them
		$geo = self::get_geo_coordinates( $event );
		if ( ! empty( $geo ) ) {
			list( $lat, $lng ) = explode( ';', $geo );
			$post['Venue']['OverwriteCoords'] = 1;
			$post['Venue']['Lat']             = $lat;
			$post['Venue']['Lng']             = $lng;
		}

		//if we are child event set the parent for referrence
		//$this->manual is no good because non Pro will use this
		if ( ! empty( $child_event ) && ! $this->args['manual'] ) {
			$post['parent_uid'] = $event->getProperty( 'UID' );
		}

		// If this is a recurring event we need to setup the appropriate recurrence meta expected by PRO or
		// else it will not be listed as a recurring event after import. We don't do this for manually imported
		// events (one-time imports) since it may be that only a small subset of events will be imported (and so
		// they will not be representative of the RRULE)
		$rrule = $event->getProperty( 'RRULE' );
		if ( ! empty( $rrule ) && ! $this->args['manual'] ) {
			$post['recurrence'] = $rrule;
		}

		//the organizer
		$organizer = $event->getProperty( 'ORGANIZER', false, true, true );

		if ( ! empty( $organizer ) ) {
			$params = wp_parse_args( str_replace( ';', '&', $organizer['params'] ) );
			foreach ( $params as $k => $param ) {
				if ( $k == 'CN' ) {
					$post['Organizer']['Organizer'] = preg_replace( '/^"(.*)"$/', '\1', $param );
				} else {
					if ( ! empty( $param ) ) {
						$post['Organizer'][ $k ] = $param;
					}
				}
			}

			if ( empty( $post['Organizer']['Organizer'] ) ) {
				$post['Organizer']['Organizer'] = str_replace( 'MAILTO:', '', $organizer['value'] );
			} elseif ( empty( $post['Organizer']['Email'] ) && ! empty( $organizer['value'] ) ) {
				$post['Organizer']['Email'] = str_replace( 'MAILTO:', '', $organizer['value'] );
			}
		}

		//if we have categories - rare
		$categories = array();
		while ( $cat = $event->getProperty( 'CATEGORIES' ) ) {
			$categories[] = $cat;
		}
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $cat ) {
				$cat                  = ucwords( strtolower( $cat ) );
				$post['categories'][] = $cat;
			}
		}


		if ( ! $all_day ) {
			//In case miscrosoft says allday
			$ms_allday = $event->getProperty( 'X-MICROSOFT-CDO-ALLDAYEVENT' );
			if ( ! empty( $ms_allday[1] ) && $ms_allday[1] == 'TRUE' ) {
				$post['EventAllDay'] = 'yes';
			}
		} else {
			$post['EventAllDay'] = 'yes';
		}

		if ( $id = $this->check_for_existing( $post ) ) {
			$post['ID'] = $id;
		}
		if ( empty( $child_event ) ) {
			$this->events[] = $post;
		}
		// $this->events[] = $post;
	}

	public function get_calendar() {
		return $this->calendar;
	}

	public function get_events() {
		return $this->events;
	}

	public function parse_events() {
		$events_count = 0;

		/**
		 * @todo revisit this variables to check which ones are required and it's defaults values
		 */
		$defaults = array(
			'start'    => 'now',
			'end'      => '+2 year',
			'count'    => '',
			'keywords' => '',
			'location' => '',
			'category' => '', //not included in very many ical feeds
			'manual'   => false,
			'paged'    => 1,
			'url'      => '',
			'radius'   => '',
			'schedule' => '',
			'action'   => '',
		);

		// Prevents Notices
		$this->args = wp_parse_args( $this->args, $defaults );

		$start    = $this->args['start'];
		$end      = $this->args['end'];
		$count    = $this->args['count'];
		$keywords = $this->args['keywords'];
		$location = $this->args['location'];
		$category = $this->args['category'];
		$manual   = $this->args['manual'];
		$url      = $this->args['url'];
		$radius   = $this->args['radius'];
		$schedule = $this->args['schedule'];
		$paged    = $this->args['paged'];
		$action   = $this->args['action'];

		$this->timezone = $this->utils->parse_timezone( $this );

		$this->raw_events = $this->get_raw_events_by_range();

		$this->criteria['start'] = strtotime( $this->args['start'] );
		$this->criteria['end']   = strtotime( $this->args['end'] );

		if ( ! empty( $location ) ) {
			$this->lat_long_set( $location );
			if ( empty( $radius ) ) {
				$radius = 25;
			}
		}

		//per year
		foreach ( $this->raw_events as $year => $year_arr ) {
			if ( strtotime( "$year-12-31" ) < $this->criteria['start'] ) {
				continue;
			}

			if ( strtotime( "$year-01-01" ) > $this->criteria['end'] ) {
				continue;
			}

			//per month
			foreach ( $year_arr as $month => $month_arr ) {
				$mm = str_pad( $month, 2, '0', STR_PAD_LEFT );
				$d = new DateTime( "{$year}-{$mm}-01" );
				$d->setDate( $year, $mm, $d->format( 't' ) );

				if ( $d->format( 'U' ) < $this->criteria['start'] ) {
					continue;
				}

				if ( strtotime( "{$year}-{$mm}-01" ) > $this->criteria['end'] ) {
					continue;
				}

				//per day
				foreach ( $month_arr as $day => $day_arr ) {
					$dd = str_pad( $day, 2, '0', STR_PAD_LEFT );

					if ( strtotime( "{$year}-{$mm}-{$dd} 23:59:59" ) < $this->criteria['start'] ) {
						continue;
					}

					if ( strtotime( "{$year}-{$mm}-{$dd}" ) > $this->criteria['end'] ) {
						continue;
					}

					$this->matched_search_criteria['startDate'] = true;

					//each event
					foreach ( $day_arr as $event ) {
						//check count
						if ( ! empty( $count ) && $events_count > $count ) {
							continue;
						}

						//check categories
						if ( ! empty( $category ) ) {
							$categories = array();
							while ( $cat = $event->getProperty( 'CATEGORIES' ) ) {
								$categories[] = $cat;
							}

							if ( empty( $categories ) ) {
								continue;
							}

							if ( ! in_array( strtoupper( $category ), $categories ) ) {
								continue;
							}

							$this->matched_search_criteria['category'] = true;
						}//end if


						//check location
						if ( ! empty( $location ) ) {
							$geo = $this->get_geo_coordinates( $event );
							if ( empty( $geo ) ) {
								continue;
							}

							if ( ! $this->check_if_event_is_in_radius( $geo, $radius ) ) {
								continue;
							}

							$this->matched_search_criteria['location'] = true;
						}//end if


						//check for keywords
						if ( ! empty( $keywords ) ) {
							$fail = false;

							foreach ( (array) $keywords as $word ) {
								if ( false === stripos( $event->getProperty( 'SUMMARY' ), $word ) && false === stripos( $event->getProperty( 'DESCRIPTION' ), $word ) ) {
									$fail = true;
									break;
								}
							}//end foreach

							if ( $fail ) {
								continue;
							}

							$this->matched_search_criteria['keywords'] = true;
						}//end if

						//made it past the general criteria
						$this->convert_vcal_event_to_importable_event( $event );

						$events_count ++;
					} //end foreach
				} //end foreach
			} //end foreach
		} //end foreach

		//if did a location query we have some new location data to cache
		if ( ! empty( $location ) ) {
			set_transient( 'noo-ical-importer-geo', self::$retrieved_geo_coordinates );
		}
	}
}