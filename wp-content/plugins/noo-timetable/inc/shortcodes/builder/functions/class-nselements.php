<?php
/**
 * class-nsmap.php
 *
 * @package:
 * @since  : 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'NSElements' ) ) :

	class NSElements {

		/**
		 * @var array
		 */
		protected static $elements = array();

		/**
		 * Map shortcode to NS.
		 *
		 * This method maps shortcode to Noo Shortcodes Builder.
		 * You need to shortcode's tag and settings to map correctly.
		 * The best way is to call this method with "init" action callback function of WP.
		 *
		 *
		 * @static
		 *
		 * @param $tag
		 * @param $attributes
		 *
		 * @return bool
		 */
		public static function addElement( $tag, $attributes ) {
			if ( empty( $attributes['name'] ) ) {
				trigger_error( sprintf( __( 'Wrong name for shortcode:%s. Name required', 'noo-shortcode-builder' ), $tag ) );
			} elseif ( empty( $attributes['base'] ) ) {
				trigger_error( sprintf( __( 'Wrong base for shortcode:%s. Base required', 'noo-shortcode-builder' ), $tag ) );
			} else {
				self::$elements[ $tag ] = $attributes;

				return true;
			}

			return false;
		}

		/**
		 * Get mapped shortcodes.
		 *
		 * @static
		 * @return array
		 */
		public static function getElements() {
			return self::$elements;
		}

	}

endif;

if ( ! function_exists( 'ns_map' ) ) :

	/**
	 * Add new shortcodes to shortcodes list.
	 * Automatically work with Visual Composer
	 *
	 * @since 1.0.0
	 *
	 * @param $attributes
	 */
	function ns_map( $attributes ) {
		if ( ! isset( $attributes['base'] ) ) {
			trigger_error( __( 'Wrong ns_map object. Base attribute is required', 'noo-shortcode-builder' ), E_USER_ERROR );
			die();
		}
		NSElements::addElement( $attributes['base'], $attributes );

		if( function_exists( 'vc_map' ) ) {
			vc_map( $attributes );
		}
    }

endif;

if ( ! function_exists( 'ns_get_elements' ) ) :

	/**
	 * Get all registered elements.
	 *
	 * @since 1.0.0
	 */
	function ns_get_elements() {
		return NSElements::getElements();
    }

endif;