<?php
/**
 * class-nsparams.php
 *
 * @package:
 * @since  : 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'NSShortcodeParams' ) ) :

	class NSShortcodeParams {
		/**
		 * @var array - store all params
		 */
		protected static $params = array();

		/**
		 * @var array - store shortcode javascript files urls
		 */
		protected static $scripts = array();

		/**
		 * Create new field type
		 */
		public static function addField( $name = '', $form_field_callback, $base_type = '', $script_url = '' ) {

			if ( ! empty( $name ) && ! empty( $form_field_callback ) ) {
				self::$params[ $name ] = array(
					'callbacks' => array(
						'form' => $form_field_callback,
					),
				);

				self::$params[ $name ]['base'] = ! empty( $base_type ) ? $base_type : $name;

				if ( is_string( $script_url ) && ! in_array( $script_url, self::$scripts ) ) {
					self::$scripts[] = $script_url;
				}

				return true;
			}

			return false;
		}

		public static function renderField( $name, $field, $value, $tag = '' ) {
			if ( isset( self::$params[ $name ]['callbacks']['form'] ) ) {
				return call_user_func( self::$params[ $name ]['callbacks']['form'], $field, $value, $tag );
			}

			do_action( 'ns_render_param_' . $name, $field, $value, $tag );

			return '';
		}

		public static function getFieldBase( $name ) {
			if ( isset( self::$params[ $name ]['base'] ) ) {
				return self::$params[ $name ]['base'];
			}

			return $name;
		}

		/**
		 * List of javascript files urls for shortcode attributes.
		 *
		 * @return array - list of js scripts
		 */
		public static function getScripts() {
			return self::$scripts;
		}
	}

endif;

if ( ! function_exists( 'ns_add_shortcode_param' ) ) :

	/**
	 * Shorthand function to register hook for the new shortcode param.
	 *
	 * @param $name                - param name
	 * @param $form_field_callback - hook, will be called when settings form is shown and param added to shortcode
	 *                             param list
	 * @param $base_type           - the type this new param is base on
	 *
	 * @param $script_url          - javascript file url which will be attached at the end of settings form.
	 */
	function ns_add_shortcode_param( $name, $form_field_callback, $base_type = '', $script_url = null ) {
		$result = NSShortcodeParams::addField( $name, $form_field_callback, $base_type, $script_url );

		if ( function_exists( 'vc_add_shortcode_param' ) ) {
			global $vc_params_list;
			if ( empty( $vc_params_list ) || ! in_array( $name, $vc_params_list ) ) {
				vc_add_shortcode_param( $name, $form_field_callback, $script_url );
			}
		}

		return $result;
	}

endif;

if ( ! function_exists( 'ns_render_param' ) ) :

	/**
	 * Shorthand function to render new shortcode param.
	 *
	 * @param $name
	 * @param $settings
	 * @param $value
	 *
	 * @return string - html string
	 */
	function ns_render_param( $name, $settings, $value, $tag = '' ) {

		return NSShortcodeParams::renderField( $name, $settings, $value, $tag );
	}

endif;

if ( ! function_exists( 'ns_get_param_base_type' ) ) :

	/**
	 * Shorthand function to get the type of the shortcode param.
	 *
	 * @param string $name - param type name
	 *
	 * @return string - param type, base on a standard type or a new type.
	 */
	function ns_get_param_base_type( $name ) {

		return NSShortcodeParams::getFieldBase( $name );
	}

endif;

global $ns_dir;

// Default
require_once $ns_dir . 'params/input.php';
require_once $ns_dir . 'params/textfield.php';
require_once $ns_dir . 'params/dropdown.php';
require_once $ns_dir . 'params/checkbox.php';
require_once $ns_dir . 'params/textarea.php';
require_once $ns_dir . 'params/textarea_html.php';
require_once $ns_dir . 'params/dropdown_group.php';
require_once $ns_dir . 'params/radio/radio.php';
require_once $ns_dir . 'params/attach_images.php';
require_once $ns_dir . 'params/ui_slider.php';

// WordPress
require_once $ns_dir . 'params/posttypes.php';
require_once $ns_dir . 'params/taxonomies.php';
require_once $ns_dir . 'params/post_categories.php';
require_once $ns_dir . 'params/post_tags.php';
require_once $ns_dir . 'params/user_list.php';
require_once $ns_dir . 'params/widgetised_sidebars.php';

// Advanced
require_once $ns_dir . 'params/href.php';
require_once $ns_dir . 'params/vc_link.php';
require_once $ns_dir . 'params/colorpicker.php';
require_once $ns_dir . 'params/iconpicker.php';
require_once $ns_dir . 'params/google_fonts.php';
require_once $ns_dir . 'params/params_preset.php';

// Remain params from VC

// 	'font_container',
// 	'autocomplete',
// 	'loop',
// 	'param_group',

// 	'options',
// 	'sorted_list',
// 	'css_editor',
// 	'animation_style',
// 	'custom_markup',
