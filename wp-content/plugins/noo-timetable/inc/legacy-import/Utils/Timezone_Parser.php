<?php


/**
 * Class Noo_Timetable_Ical_Importer__Utils__Timezone_Parser
 *
 * Parses and validates timezones informations.
 */
class Noo_Timetable_Ical_Importer__Utils__Timezone_Parser {

	/**
	 * Parses and returns the specified calendar timezone.
	 *
	 * @param vcalendar $calendar
	 *
	 * @return bool|string Either the timezone string read from one of the supported properties or `false`.
	 */
	public function parse_timezone( vcalendar $calendar ) {
		$timezone = $calendar->getProperty( 'X-WR-TIMEZONE' );

		if ( ! empty( $timezone[1] ) ) {
			return $timezone[1];
		}

		$vtimezone = $calendar->getComponent( 'VTIMEZONE' );

		if ( empty( $vtimezone ) ) {
			return false;
		}

		$tzid = $vtimezone->getProperty( 'TZID' );

		if ( ! empty( $tzid ) && $this->is_valid_php_timezone( $tzid ) ) {
			return $tzid;
		}

		return $guessed_timezone = $this->guess_timezone( $vtimezone, $tzid );
	}

	/**
	 * Whether the specified timezone string is a valid PHP supported timezone or not.
	 *
	 * @param string $timezone
	 *
	 * @return bool
	 */
	public function is_valid_php_timezone( $timezone ) {
		return in_array( $timezone, DateTimeZone::listIdentifiers() ) ? true : false;
	}

	/**
	 * Guesses the timezone for the given calendar.
	 *
	 * @link https://gist.github.com/SimonSimCity/9950755
	 *
	 * @param iCalVTIMEZONE $vtimezone
	 * @param string|bool   $timezone_identifier
	 *
	 * @return bool|string The PHP valid timezone string if found, `false` otherwise.
	 * @throws Exception
	 */
	public function guess_timezone( iCalVTIMEZONE $vtimezone, $timezone_identifier ) {
		$found  = null;
		$cached = wp_cache_get( $timezone_identifier, __CLASS__, null, $found );

		if ( $found ) {
			return $cached;
		}

		$timezones = DateTimeZone::listIdentifiers();

		$daylight = $vtimezone->getComponent( 'DAYLIGHT' );
		$standard = $vtimezone->getComponent( 'STANDARD' );

		if ( empty( $daylight ) || empty( $standard ) ) {
			return false;
		}

		$vtimezone_information   = array();
		$vtimezone_information[] = $this->parseOffsetToInteger( $daylight->getProperty( 'TZOFFSETFROM' ) );
		$vtimezone_information[] = $this->parseOffsetToInteger( $daylight->getProperty( 'TZOFFSETTO' ) );
		$vtimezone_information[] = $this->parseOffsetToInteger( $standard->getProperty( 'TZOFFSETFROM' ) );
		$vtimezone_information[] = $this->parseOffsetToInteger( $standard->getProperty( 'TZOFFSETTO' ) );

		$rrules = array(
			'daylight' => $this->parse_rrule_into_array( $daylight->getProperty( 'RRULE' ) ),
			'standard' => $this->parse_rrule_into_array( $standard->getProperty( 'RRULE' ) ),
		);

		$match = false;

		foreach ( $timezones as $timezone ) {
			// Reduce the list of timezones to those, who're valuable to take a closer look at.
			$now = new DateTime( "now", new DateTimeZone( $timezone ) );
			if ( ! in_array( $now->getOffset(), $vtimezone_information ) ) {
				continue;
			}

			// Guess timezone based on DTSTART
			$transitions = timezone_transitions_get( new DateTimeZone( $timezone ) );
			foreach ( $transitions as $transition ) {
				foreach ( array( $daylight, $standard ) as $definition ) {
					try {
						$start_datetime      = new DateTime( $definition->getProperty( 'DTSTART' ) );
						$transition_datetime = new DateTime( $transition[ 'time' ] );

						if ( $start_datetime->format( 'c' ) == $transition_datetime->format( 'c' ) && $transition[ 'offset' ] === $this->parseOffsetToInteger( $definition->getProperty( 'TZOFFSETTO' ) ) ) {
							$match = $timezone;
						}
					} catch ( Exception $e ) {
						if ( strpos( $e->getMessage(), "DateTime::__construct(): Failed to parse time string" ) !== 0 ) {
							$match = false;
						}
					}
				}
			}
			// Guess timezone based on RULES for now
			foreach ( array( 'daylight' => $daylight, 'standard' => $standard ) as $key => $definition ) {
				$rule = $rrules[ $key ];
				if ( $rule['FREQ'] !== "YEARLY" ) {
					continue;
				}
				$dayMap = array(
					"MO" => 1,
					"TU" => 2,
					"WE" => 3,
					"TH" => 4,
					"FR" => 5,
					"SA" => 6,
					"SU" => 7,
				);
				date_default_timezone_set( 'UTC' );
				$date = date( DATE_ATOM, $this->get_date( $rule["BYMONTH"], date( "Y" ), $rule["BYDAY"][1], // Count of weeks to go back or forth
					$dayMap[ $rule['BYDAY'][2] . $rule['BYDAY'][3] ], // Weekday as number
					$rule['BYDAY'][0] === "-" ? - 1 : 1 ) );

				$dtstart = new DateTime( $definition->getProperty( 'DTSTART' ) );

				$dateTime = new DateTime( $date );
				$dateTime->setTime( $dtstart->format( "H" ) + 0, $dtstart->format( "i" ) + 0, $dtstart->format( "s" ) + 0 );
				// Set time back for 1sec to get behind the time-transition
				$dateTime->sub( new DateInterval( "PT1S" ) );
				// Try the first offset
				$dateTime->setTimezone( new DateTimeZone( $timezone ) );
				if ( $dateTime->getOffset() !== $this->parseOffsetToInteger( $definition->getProperty( 'TZOFFSETFROM' ) ) ) {
					// Maybe the system set one hour too late for the switch to StandardTime? Like M$ does in the provided example ... :)
					if ( $this->parseOffsetToInteger( $definition->getProperty( 'TZOFFSETFROM' ) ) < $this->parseOffsetToInteger( $definition->getProperty( 'TZOFFSETTO' ) ) ) {
						// Set it back to UTC, add two seconds to get past the time-transition
						$dateTime->setTimezone( new DateTimeZone( "UTC" ) );
						$dateTime->sub( new DateInterval( "PT1H" ) );
						// Try again for the next offset
						$dateTime->setTimezone( new DateTimeZone( $timezone ) );
						if ( $dateTime->getOffset() !== $this->parseOffsetToInteger( $definition->getProperty( 'TZOFFSETTO' ) ) ) {
							continue 2;
						}
					}
				}
				// Set it back to UTC, add two seconds to get past the time-transition
				$dateTime->setTimezone( new DateTimeZone( "UTC" ) );
				$dateTime->add( new DateInterval( "PT2S" ) );

				// Try again for the next offset
				$dateTime->setTimezone( new DateTimeZone( $timezone ) );

				if ( $dateTime->getOffset() !== $this->parseOffsetToInteger( $definition->getProperty( 'TZOFFSETTO' ) ) ) {
					continue 2;
				}

				$match = $timezone;
			}
		}

		wp_cache_set( $timezone_identifier, $match, __CLASS__ );

		return $match;
	}

	/**
	 * @param string $offset
	 *
	 * @return string
	 */
	private function parseOffsetToInteger( $offset ) {
		$time = ( $offset[1] . $offset[2] * 60 ) + ( $offset[3] . $offset[4] );

		// in seconds please ..
		$time = $time * 60;
		if ( $offset[0] === "-" ) {
			$time = $time * - 1;
		}

		return $time;
	}

	/**
	 * @param $month
	 * @param $year
	 * @param $week
	 * @param $day
	 * @param $direction
	 *
	 * @return int
	 */
	private function get_date( $month, $year, $week, $day, $direction ) {
		if ( $direction > 0 ) {
			$startday = 1;
		} else {
			$startday = date( 't', mktime( 0, 0, 0, $month, 1, $year ) );
		}
		$start   = mktime( 0, 0, 0, $month, $startday, $year );
		$weekday = date( 'N', $start );
		if ( $direction * $day >= $direction * $weekday ) {
			$offset = - $direction * 7;
		} else {
			$offset = 0;
		}
		$offset += $direction * ( $week * 7 ) + ( $day - $weekday );

		return mktime( 0, 0, 0, $month, $startday + $offset, $year );
	}

	/**
	 * @param string $rrule
	 */
	private function parse_rrule_into_array( $rrule ) {
		$parts  = explode( ';', $rrule );
		$parsed = array();

		foreach ( $parts as $part ) {
			list( $key, $value ) = explode( '=', $part );

			if ( $key === 'BYDAY' ) {
				$value = preg_match( '/^(\\+|-)/', $value ) ? $value : '+' . $value;
			}

			$parsed[ $key ] = $value;
		}

		return $parsed;
	}
}
