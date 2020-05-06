<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'ns_load_template' ) ) :

	/**
	 * Load and output the template
	 *
	 * @param string $template Template path
	 * @param array  $vars     Variables that should be passed to the template
	 */
	function ns_load_template( $template, $vars = null ) {
		global $ns_dir;

		if ( is_array( $vars ) AND ! empty( $vars ) ) {
			extract( $vars );
		}

		if ( ! file_exists( $ns_dir . 'templates/' . $template . '.php' ) ) {
			wp_die( 'File not found: ' . $ns_dir . 'templates/' . $template . '.php' );
		}
		include $ns_dir . 'templates/' . $template . '.php';
	}

endif;

if ( ! function_exists( 'ns_get_template' ) ) :

	/**
	 * Get and return the template output
	 *
	 * @param string $template Template path
	 * @param array  $vars     Variables that should be passed to the template
	 *
	 * @return string Template output
	 */
	function ns_get_template( $template, $vars = null ) {
		ob_start();
		ns_load_template( $template, $vars );

		return ob_get_clean();
	}

endif;

if ( ! function_exists( 'ns_enqueue_param_scripts' ) ) :

	/**
	 * Enqueue js scripts required by shortcode params.
	 *
	 * @return string
	 */
	function ns_enqueue_param_scripts() {
		$output  = '';
		$scripts = apply_filters( 'ns_form_scripts', NSShortcodeParams::getScripts() );
		foreach ( $scripts as $script ) {
			$output .= "\n\n" . '<script type="text/javascript" src="' . $script . '"></script>';
		}

		return $output;
	}

endif;

if ( ! function_exists( 'ns_maybe_load_wysiwyg' ) ) :

	/**
	 * Load WordPress TinyMCE wysiwyg editor configuration
	 * The configuration will be available in JavaScript: tinyMCEPreInit.mceInit['nootheme']
	 */
	function ns_maybe_load_wysiwyg() {
		global $ns_html_editor_loaded;
		if ( ! isset( $ns_html_editor_loaded ) OR ! $ns_html_editor_loaded ) {
			$screen = get_current_screen();
			if ( $screen !== null AND $screen->base == 'customize' ) {
				ns_load_wysiwyg();
			} else {
				// Support for 3-rd party plugins that customize mce_buttons during the admin_head action
				add_action( 'admin_head', 'ns_load_wysiwyg', 50 );
			}
			$ns_html_editor_loaded = true;
		}
	}

endif;

if ( ! function_exists( 'ns_load_wysiwyg' ) ) :

	function ns_load_wysiwyg() {
		if ( ! class_exists( '_WP_Editors' ) ) {
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		}
		_WP_Editors::editor_settings( 'nootheme', _WP_Editors::parse_settings( 'content', array(
			'dfw'               => true,
			'tabfocus_elements' => 'insert-media-button',
			'editor_height'     => 360,
		) ) );
	}

endif;

if ( ! function_exists( 'ns_pass_data_to_js' ) ) :

	/**
	 * Transform some variable to elm's onclick attribute, so it could be obtained from JavaScript as:
	 * var data = elm.onclick()
	 *
	 * @param mixed $data Data to pass
	 *
	 * @return string Element attribute ' onclick="..."'
	 */
	function ns_pass_data_to_js( $data ) {
		return ' onclick=\'return ' . str_replace( "'", '&#39;', json_encode( $data ) ) . '\'';
	}

endif;

if ( ! function_exists( 'ns_check_visible_by_dependency' ) ) :

	/**
	 * Checks if the field is visible base on the values of dependent param
	 *
	 * Note: at any possible syntax error we choose to show the field so it will be functional anyway.
	 *
	 * @param array $dependency Showing condition:
	 *                          element   -  String    Param name (linked field) which will be observed for changes.
	 *                          value     -  Array     List of linked element's values which will allow to display param
	 *                          not_empty -  Boolean   Display field if value of linked field is not empty
	 *                          callback  -  String    javascript function name to be called when value of linked field is
	 *                          changed
	 * @param array $values
	 *
	 * @return bool
	 */
	function ns_check_visible_by_dependency( $dependency, $values ) {
		if ( ! is_array( $dependency ) OR count( $dependency ) < 2 OR ! isset( $dependency['element'] ) OR empty( $dependency['element'] ) ) {
			// Wrong condition
			$result = true;
		} else {
			$param = $dependency['element'];
			$check = $values[ $param ];
			$check = is_array( $check ) ? current( $check ) : $check;
			if ( isset( $dependency['not_empty'] ) ) {
				$result = ! empty( $check );
			} elseif ( isset( $dependency['value'] ) ) {
				$value = ! is_array( $dependency['value'] ) ? array( $dependency['value'] ) : $dependency['value'];

				$result = in_array( $check, $value );
			} else {
				$result = true;
			}
		}

		return $result;
	}

endif;

if ( ! function_exists( 'ns_map_get_param_default' ) ) :

	/**
	 * Function to get default value for shortcode param
	 *
	 * @since 1.0.0
	 *
	 * @param $param
	 *
	 * @return string
	 */
	function ns_map_get_param_default( $param ) {
		$value = '';

		if ( isset( $param['param_name'] ) && 'content' !== $param['param_name'] ) {
			if ( isset( $param['std'] ) ) {
				$value = $param['std'];
			} elseif ( isset( $param['value'] ) ) {
				if ( is_array( $param['value'] ) ) {
					$value = current( $param['value'] );
					if ( is_array( $value ) ) {
						// in case if two-dimensional array provided (vc_basic_grid)
						$value = current( $value );
					}
					// return first value from array (by default)
				} else {
					$value = $param['value'];
				}
			}

			if ( 'checkbox' === $param['type'] ) {
				$value = '';
				if ( isset( $param['std'] ) ) {
					$value = $param['std'];
				}
			}
		}

		return $value;
	}

endif;

if ( ! function_exists( 'ns_parse_multi_attribute' ) ) :

	/**
	 * Parse string like "title:Hello world|weekday:Monday" to array('title' => 'Hello World', 'weekday' => 'Monday')
	 * Copy of vc_parse_multi_attribute function
	 *
	 * @param       $value
	 * @param array $default
	 *
	 * @return array
	 */
	function ns_parse_multi_attribute( $value, $default = array() ) {
		$result       = $default;
		$params_pairs = explode( '|', $value );
		if ( ! empty( $params_pairs ) ) {
			foreach ( $params_pairs as $pair ) {
				$param = preg_split( '/\:/', $pair );
				if ( ! empty( $param[0] ) && isset( $param[1] ) ) {
					$result[ $param[0] ] = rawurldecode( $param[1] );
				}
			}
		}

		return $result;
	}

endif;

if ( ! function_exists( 'ns_get_dropdown_option' ) ) :

	/**
	 * Copy of vc_get_dropdown_option function.
	 * Used for with or without Visual Composer
	 *
	 * @since 1.0.0
	 *
	 * @param $param
	 * @param $value
	 *
	 * @return mixed|string
	 */
	function ns_get_dropdown_option( $param, $value ) {
		if ( '' === $value && is_array( $param['value'] ) ) {
			$value = array_shift( $param['value'] );
		}
		if ( is_array( $value ) ) {
			reset( $value );
			$value = isset( $value['value'] ) ? $value['value'] : current( $value );
		}
		$value = preg_replace( '/\s/', '_', $value );

		return ( '' !== $value ? $value : '' );
	}

endif;

if ( ! function_exists( 'ns_value_from_safe' ) ) :

	/**
	 * Copy of vc_value_from_safe function.
	 * Get value from encoded data
	 *
	 * @param bool $encode
	 *
	 * @return string
	 */
	function ns_value_from_safe( $value, $encode = false ) {
		$value = preg_match( '/^#E\-8_/', $value ) ? rawurldecode( base64_decode( preg_replace( '/^#E\-8_/', '',
			$value ) ) ) : $value;
		if ( $encode ) {
			$value = htmlentities( $value, ENT_COMPAT, 'UTF-8' );
		}

		return $value;
	}

endif;
