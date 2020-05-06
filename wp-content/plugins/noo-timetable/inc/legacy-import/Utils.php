<?php


/**
 * Class Noo_Timetable_Ical_Importer__Utils
 *
 * A façade implementation to allow for easy dependency injection of utils.
 * This class should not implement any logic but merely forward calls to other classes.
 */
class Noo_Timetable_Ical_Importer__Utils {

	/**
	 * @var Noo_Timetable_Ical_Importer__Utils
	 */
	protected static $instance;

	/**
	 * @var Noo_Timetable_Ical_Importer__Utils__Uid
	 */
	protected $uid;

	/**
	 * @var Noo_Timetable_Ical_Importer__Utils__Timezone_Parser
	 */
	protected $timezone_parser;

	/**
	 * The class singleton constructor.
	 *
	 * @return Noo_Timetable_Ical_Importer__Utils
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			$uid             = new Noo_Timetable_Ical_Importer__Utils__Uid();
			$timezone_parser = new Noo_Timetable_Ical_Importer__Utils__Timezone_Parser();

			self::$instance = new self( $uid, $timezone_parser );
		}

		return self::$instance;
	}

	/**
	 * Noo_Timetable_Ical_Importer__Utils constructor.
	 *
	 * @param Noo_Timetable_Ical_Importer__Utils__Uid $uid
	 */
	public function __construct( Noo_Timetable_Ical_Importer__Utils__Uid $uid, Noo_Timetable_Ical_Importer__Utils__Timezone_Parser $timezone_parser ) {
		$this->uid             = $uid;
		$this->timezone_parser = $timezone_parser;
	}

	/**
	 * Generates a Unique IDentifier for an event.
	 *
	 * @uses Noo_Timetable_Ical_Importer__Utils__Uid::generate_uid_for_event
	 *
	 * @param iCalVEVENT $event
	 *
	 * return string
	 */
	public function generate_uid_for_event( iCalVEVENT $event ) {
		return $this->uid->generate_uid_for_event( $event );
	}

	/**
	 * Returns an iteratable array of headers.
	 *
	 * Proxy for the Utils\Headers::__construct method.
	 *
	 * @return array
	 */
	public function headers() {
		return new Noo_Timetable_Ical_Importer__Utils__Headers();
	}

	/**
	 * Whether the specified timezone string is a valid PHP supported timezone or not.
	 *
	 * @uses Noo_Timetable_Ical_Importer__Utils__Timezone_Parser::is_valid_php_timezone
	 *
	 * @param string $timezone
	 *
	 * @return bool
	 */
	public function is_valid_php_timezone( $timezone ) {
		return $this->timezone_parser->is_valid_php_timezone( $timezone );
	}

	/**
	 * Parses and returns the specified calendar timezone.
	 *
	 * @uses Noo_Timetable_Ical_Importer__Utils__Timezone_Parser::parse_timezone
	 *
	 * @param vcalendar $calendar
	 *
	 * @return bool|string Either the timezone string read from one of the supported properties
	 *                     or `false` if it was not possible to determine a timezone.
	 */
	public function parse_timezone( vcalendar $calendar ) {
		return $this->timezone_parser->parse_timezone( $calendar );
	}
}
