<?php
/**
 * Helper functions for NOO Framework.
 * Function for getting view files. There's two kind of view files,
 * one is default view from framework, the other is view from specific theme.
 * File from specific theme will override that from framework.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Shorthand function get predefined layout
if ( ! function_exists( 'noo_hermosa_get_layout' ) ) :
	function noo_hermosa_get_layout( $slug, $name = '' ) {
		get_template_part( 'layouts/' . $slug, $name );
	}
endif;

// Function for getting theme option. There are two kind of functions
// 1. Normal get option: essentially is the get_theme_mod but add the default 
// value when it's empty. It'll help when first installed the theme.
// 
// 2. AJAX customizer get option: used when live customizer preview request AJAX.
// It'll return the value user's currently choosing.
if( !function_exists( 'noo_hermosa_get_option' ) ) {
	if( isset( $_POST['noo_customize_ajax'] ) ) {
		
		// AJAX customizer get option
		function noo_hermosa_get_option( $option, $default = null ) {
			global $noo_customizer;
			if( !isset( $noo_customizer ) || empty( $noo_customizer ) ) {
				if ( isset( $_POST['customized'] ) )
					$noo_customizer  = json_decode( wp_unslash( $_POST['customized'] ), true );
				else
					$noo_customizer  = false;
			}

			$value = isset( $noo_customizer[ $option ] ) ? $noo_customizer[ $option ] : get_theme_mod( $option, $default );
			// $value = isset( $noo_customizer[ $option ] ) ? $noo_customizer[ $option ] : get_option( $option, $default );
			$value = ( $value === null || $value === '' ) ? $default : $value;

			return apply_filters( 'noo_theme_settings', $value, $option, $default );
		}

	} else {
		
		// Normal get option
		function noo_hermosa_get_option( $option, $default = null ) {
			$value = get_theme_mod( $option, $default );
			// $value = get_option( $option, $default );
			$value = ( $value === null || $value === '' ) ? $default : $value;

			return apply_filters( 'noo_theme_settings', $value, $option, $default );
		}

	}
}

// Function for getting image from theme option
// This function is initially created because of change from WordPress 4.1
if (!function_exists('noo_hermosa_get_image_option')):
	function noo_hermosa_get_image_option( $option, $default ) {
		$image = noo_hermosa_get_option( $option );

		$image = ( $image === null || $image === '' ) ? $default : $image;
		$image = ( !empty( $image ) && is_int($image) ) ? wp_get_attachment_url( $image ) : $image;

		return $image;
	}
endif;

if( !function_exists('noo_hermosa_init_theme_default') ) :
	function noo_hermosa_init_theme_default( $keys = null ) {
		global $noo_theme;
		if( empty( $noo_theme ) ) {
			$noo_theme = apply_filters( 'noo_theme_default', array( 
				'primary_color' => '#337ab7',
				'secondary_color' => '#000',
				'font_family' => 'Open Sans',
				'text_color' => '#000',
				'font_size' => '14',
				'font_weight' => '400',
				'headings_font_family' => 'Open Sans',
				'headings_color' => '#000',
				'logo_color' => '#000',
				'logo_font_family' => 'Open Sans',
			) );
		}
	}
endif;

if( !function_exists('noo_hermosa_set_theme_default') ) :
	function noo_hermosa_set_theme_default( $keys = null, $value = null ) {
		global $noo_theme;
		noo_hermosa_init_theme_default();
		if( is_null( $keys ) ) return;
		if( is_array( $keys ) ) {
			$noo_theme = array_merge( $noo_theme, $keys );
		}

		if( is_string( $keys ) && !is_null( $value ) ) {
			$noo_theme[$keys] = $value;
		}
	}
endif;

if( !function_exists('noo_hermosa_get_theme_default') ) :
	function noo_hermosa_get_theme_default( $key = '' ) {
		global $noo_theme;
		noo_hermosa_init_theme_default();

		$return = '';
		if( isset( $noo_theme[$key] ) ) $return = $noo_theme[$key];
		
		return apply_filters( 'noo_theme_default_' . $key, $return );
	}
endif;