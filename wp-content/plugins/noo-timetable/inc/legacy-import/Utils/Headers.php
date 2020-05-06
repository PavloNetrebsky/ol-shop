<?php


/**
 * Class Noo_Timetable_Ical_Importer__Utils__Headers
 *
 * Providers iCal Importer specific headers to be used in requests.
 * Use in a `foreach` loop.
 */
class Noo_Timetable_Ical_Importer__Utils__Headers implements Iterator {

	/**
	 * The plugin version, static cached for performance reasons.
	 *
	 * @var string
	 */
	protected static $version = '';

	/**
	 * @var array
	 */
	protected $headers;

	/**
	 * @var int
	 */
	protected $position;

	/**
	 * Noo_Timetable_Ical_Importer__Utils__Headers constructor.
	 */
	public function __construct() {
		global $wp_version;

		$this->headers = array(
			array( 'User-Agent' => 'iCalImporter/' . $this->version() . ' (WordPress ' . $wp_version . '; PHP ' . PHP_VERSION . ') wp_remote_get()', ),
			array( 'User-Agent' => '' ),
		);

		$this->position = 0;
	}

	/**
	 * @return string
	 */
	protected function version() {
		if ( empty( self::$version ) ) {

			$plugin_file = dirname( dirname( dirname( dirname( __FILE__ ) ) ) ) . '/noo-timetable.php';
			
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
			$data = get_plugin_data( $plugin_file );

			self::$version = ! empty( $data['Version'] ) ? $data['Version'] : 'latest';
		}

		return self::$version;
	}

	/**
	 * Sets some custom headers to be used.
	 *
	 * @param array $headers
	 */
	public function set_headers( array $headers ) {
		$this->headers = $headers;
		$this->rewind();
	}

	/**
	 * Return the current element
	 *
	 * @link  http://php.net/manual/en/iterator.current.php
	 * @return mixed Can return any type.
	 * @since 5.0.0
	 */
	public function current() {
		return $this->headers[ $this->position ];
	}

	/**
	 * Move forward to next element
	 *
	 * @link  http://php.net/manual/en/iterator.next.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function next() {
		++ $this->position;
	}

	/**
	 * Return the key of the current element
	 *
	 * @link  http://php.net/manual/en/iterator.key.php
	 * @return mixed scalar on success, or null on failure.
	 * @since 5.0.0
	 */
	public function key() {
		return $this->position;
	}

	/**
	 * Checks if current position is valid
	 *
	 * @link  http://php.net/manual/en/iterator.valid.php
	 * @return boolean The return value will be casted to boolean and then evaluated.
	 *        Returns true on success or false on failure.
	 * @since 5.0.0
	 */
	public function valid() {
		return isset( $this->headers[ $this->position ] );
	}

	/**
	 * Rewind the Iterator to the first element
	 *
	 * @link  http://php.net/manual/en/iterator.rewind.php
	 * @return void Any returned value is ignored.
	 * @since 5.0.0
	 */
	public function rewind() {
		$this->position = 0;
	}
}
