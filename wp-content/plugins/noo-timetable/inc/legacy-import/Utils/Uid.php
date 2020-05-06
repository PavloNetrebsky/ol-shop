<?php


/**
 * Class Noo_Timetable_Ical_Importer__Utils__Uid
 *
 * Events Uniquey IDentifier generation and manipulation utilities.
 */
class Noo_Timetable_Ical_Importer__Utils__Uid {

	/**
	 * Generates a Unique IDentifier for an event.
	 *
	 * Within this method, the UID includes as least the UID property - but may be
	 * expanded into a concatenation that also includes the RECURRENCE-ID and
	 * SEQUENCE properties (when available). This is because all three properties
	 * are required to uniquely identify a recurring event.
	 *
	 * @see http://tools.ietf.org/html/rfc2445#section-4.8.4.4
	 *
	 * @param iCalVEVENT $event
	 *
	 * return string
	 */
	public function generate_uid_for_event( iCalVEVENT $event ) {
		$recurrence_id = $event->getProperty( 'RECURRENCE-ID' );
		if ( false === $recurrence_id && false !== $event->getProperty( 'X-RECURRENCE' ) ) {
			$current_dt_start = $event->getProperty( 'X-CURRENT-DTSTART' );
			$recurrence_id    = isset( $current_dt_start[1] ) ? $current_dt_start[1] : false;
		}

		return $event->getProperty( 'UID' ) . $recurrence_id . $event->getProperty( 'SEQUENCE' );
	}
}