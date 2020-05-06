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

if( !function_exists('noo_hermosa_registered_post_type') ) :
	function noo_hermosa_registered_post_type( $post_type = '', $args = array() ) {
		global $noo_post_types;
		if( empty( $noo_post_types ) ) $noo_post_types = array();

		if( in_array($post_type, array('post','page','attachment','revision','nav_menu_item','product','product_variation','shop_order','shop_order_refund','shop_coupon','shop_webhook','wpcf7_contact_form', 'mc4wp-form') ) ) {
			return;
		}

		$info = isset( $noo_post_types[$post_type] ) ? $noo_post_types[$post_type] : array();

		$info['name'] = isset( $args->labels->name ) ? $args->labels->name : ( isset( $args->label ) ? $args->label : '' );
		$info['singular_name'] = isset( $args->labels->singular_name ) ? $args->labels->singular_name : $info['name'];

		$info['taxonomy'] = array();

		$noo_post_types[$post_type] = $info;
	}

	add_action( 'registered_post_type', 'noo_hermosa_registered_post_type', 10, 2 );
endif;

if( !function_exists('noo_hermosa_registered_taxonomy') ) :
	function noo_hermosa_registered_taxonomy( $taxonomy = '', $object_type = '', $args = array() ) {
		global $noo_post_types;
		if( empty( $noo_post_types ) ) $noo_post_types = array();

		if( in_array($object_type, array('post','page','attachment','revision','nav_menu_item','product','product_variation','shop_order','shop_order_refund','shop_coupon','shop_webhook','wpcf7_contact_form') ) ) {
			return;
		}

		if( is_array( $object_type ) ) {
			foreach ($object_type as $post_type) {
				$info = isset( $noo_post_types[$post_type] ) ? $noo_post_types[$post_type] : array();

				$info['taxonomy'] = isset( $info['taxonomy'] ) ? $info['taxonomy'] : array();
				$info['taxonomy'][] = $taxonomy;

				$noo_post_types[$post_type] = $info;
			}
		} else {
			$info = isset( $noo_post_types[$object_type] ) ? $noo_post_types[$object_type] : array();

			$info['taxonomy'] = isset( $info['taxonomy'] ) ? $info['taxonomy'] : array();
			$info['taxonomy'][] = $taxonomy;

			$noo_post_types[$object_type] = $info;
		}
	}

	add_action( 'registered_taxonomy', 'noo_hermosa_registered_taxonomy', 10, 3 );
endif;

if( !function_exists( 'noo_hermosa_get_post_meta' ) ) {
	// Normal get option
	function noo_hermosa_get_post_meta( $post_ID = null, $meta_key, $default = null ) {
		$post_ID = (null === $post_ID) ? get_the_ID() : $post_ID;

		$value = get_post_meta( $post_ID, $meta_key, true );

		// Sanitize for on/off checkbox
		$value = ( $value == 'off' ? false : $value );
		$value = ( $value == 'on' ? true : $value );
		if( ( $value === null || $value === '' ) && ( $default != null && $default != '' ) ) {
			$value = $default;
		}

		return apply_filters( 'noo_hermosa_post_meta', $value, $post_ID, $meta_key, $default );
	}
}

if( !function_exists( 'noo_hermosa_is_archive' ) ) {
	function noo_hermosa_is_archive( $post_type = '' ) {
		if( empty( $post_type ) ) return false;
		if( is_post_type_archive( $post_type ) ) 
			return true;

		global $noo_post_types;
		if( empty( $noo_post_types ) || !isset($noo_post_types[$post_type]) || !isset($noo_post_types[$post_type]['taxonomy']) ) {
			return false;
		}

		foreach ($noo_post_types[$post_type]['taxonomy'] as $taxonomy) {
			if( is_tax($taxonomy) ) return true;
		}
	}
}

if( !function_exists('noo_hermosa_get_term_meta') ) :
	function noo_hermosa_get_term_meta( $term_id = null, $meta_key = '', $default = null ) {
		if( empty( $term_id ) || empty( $meta_key) ) {
			return null;
		}

		$term_meta = get_option( 'taxonomy_' . $term_id );
		$value = isset( $term_meta[$meta_key] ) ? $term_meta[$meta_key] : null;

		if( ( $value === null || $value === '' ) && ( $default != null && $default != '' ) ) {
			$value = $default;
		}

		return apply_filters( 'noo_hermosa_term_meta', $value, $term_id, $meta_key, $default );
	}
endif;