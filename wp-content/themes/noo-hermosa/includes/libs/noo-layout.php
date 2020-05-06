<?php
/**
 * Utilities Functions for NOO Framework.
 * This file contains various functions for getting and preparing data.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if (!function_exists('noo_hermosa_get_page_layout')):
	function noo_hermosa_get_page_layout() {
		$layout = 'fullwidth';

		// Single post page
		if (is_single()) {

			// WP post,
			// check if there's overrode setting in this post.
			$post_id = get_the_ID();
			$override_setting = noo_hermosa_get_post_meta($post_id, '_noo_wp_post_override_layout', false);

			if ( !isset($override_setting) && $override_setting == '' ) {
				$post_layout = noo_hermosa_get_option('noo_blog_post_layout', 'same_as_blog');
				if ($post_layout == 'same_as_blog') {
					$post_layout = noo_hermosa_get_option('noo_blog_layout', 'sidebar');
				}
				$layout = $post_layout;
			}
			// overrode
			$layout = noo_hermosa_get_post_meta($post_id, '_noo_wp_post_layout', 'sidebar-main');

		}

		// Normal Page or Static Front Page
		if (is_page() || (is_front_page() && get_option('show_on_front') == 'page')) {
			// WP page,
			// get the page template setting
			$page_id = get_the_ID();
			$page_template = noo_hermosa_get_post_meta($page_id, '_wp_page_template', 'default');
			
			if (strpos($page_template, 'sidebar') !== false) {
				if (strpos($page_template, 'left') !== false) {
					$layout = 'left_sidebar';
				}
				
				$layout = 'sidebar';
			}
		}

		// Index or Home
		if (is_home() || is_archive() || (is_front_page() && get_option('show_on_front') == 'posts')) {
			
			$layout = noo_hermosa_get_option('noo_blog_layout', 'sidebar');
		}

		// WooCommerce
		if( NOO_WOOCOMMERCE_EXIST ) {
			if( is_shop() || is_product_category() || is_product_tag() ){
				$layout = noo_hermosa_get_option('noo_shop_layout', 'fullwidth');
			}

			if( is_product() ) {
				$product_layout = noo_hermosa_get_option('noo_woocommerce_product_layout', 'same_as_shop');
				if ($product_layout == 'same_as_shop') {
					$product_layout = noo_hermosa_get_option('noo_shop_layout', 'fullwidth');
				}
				$layout = $product_layout;
			}
            if(is_singular('noo_portfolio')){
                $post_layout=noo_hermosa_get_option('noo_portfolio_layout','sidebar');
                $layout=$post_layout;
            }
		}

		/**
		 * Noo Class
		 */
		if ( class_exists( 'Noo__Timetable__Class' ) ) :
			if( is_post_type_archive( 'noo_class' ) || is_tax( 'class_category' ) ) {
				$layout = noo_hermosa_get_option('noo_classes_layout', 'sidebar');
			}
		endif;


		/**
		 * Noo Event
		 */
		if ( class_exists( 'Noo__Timetable__Event' ) ) :

			if ( is_singular( 'noo_event' ) ) :

				$event_layout = noo_hermosa_get_option( 'noo_post_event_layout', 'same_as_event' );
				if ( $event_layout == 'same_as_event' ) {
					$event_layout = noo_hermosa_get_option( 'noo_event_layout', 'fullwidth' );
				}
				$layout = $event_layout;

			endif;

			if ( 
				is_post_type_archive( 'noo_event' ) ||
				is_tax( 'event_category' ) || 
                is_tax( 'event_location' )
            ) :

				$layout = noo_hermosa_get_option( 'noo_event_layout', 'fullwidth' );

			endif;

		endif;


		/**
		 * Noo Trainer
		 */
		if ( class_exists( 'Noo__Timetable__Trainer' ) ) :

			if( is_post_type_archive( 'noo_trainer' ) )  {
				$layout = noo_hermosa_get_option( 'noo_trainer_layout', 'fullwidth' );
			}

		endif;

		return apply_filters( 'noo_page_layout', $layout );
	}
endif;

if (!function_exists('noo_hermosa_get_sidebar_id')):
	function noo_hermosa_get_sidebar_id() {
		$sidebar = '';

		// Normal Page or Static Front Page
		if ( is_page() || (is_front_page() && get_option('show_on_front') == 'page') ) {
			// Get the sidebar setting from
			$sidebar = noo_hermosa_get_post_meta(get_the_ID(), '_noo_wp_page_sidebar', '');
		}

		// Single post page
		if (is_single()) {

			// Check if there's overrode setting in this post.
			$post_id = get_the_ID();
			$override_setting = noo_hermosa_get_post_meta($post_id, '_noo_wp_post_override_layout', false);
			if ($override_setting) {
				// overrode
				$overrode_layout = noo_hermosa_get_post_meta($post_id, '_noo_wp_post_layout', 'fullwidth');
				if ($overrode_layout != 'fullwidth') {
					$sidebar = noo_hermosa_get_post_meta($post_id, '_noo_wp_post_sidebar', 'sidebar-main');
				}
			} else{

				$post_layout = noo_hermosa_get_option('noo_blog_post_layout', 'same_as_blog');
				$sidebar = '';
				if ($post_layout == 'same_as_blog') {
					$post_layout = noo_hermosa_get_option('noo_blog_layout', 'sidebar');
					$sidebar = noo_hermosa_get_option('noo_blog_sidebar', 'sidebar-main');
				} else {
					$sidebar = noo_hermosa_get_option('noo_blog_post_sidebar', 'sidebar-main');
				}
				
				if($post_layout == 'fullwidth'){
					$sidebar = '';
				}
			}
		}

		// Archive, Index or Home
		if (is_home() || is_archive() || (is_front_page() && get_option('show_on_front') == 'posts')) {
			
			$blog_layout = noo_hermosa_get_option('noo_blog_layout', 'sidebar');
			if ($blog_layout != 'fullwidth') {
				$sidebar = noo_hermosa_get_option('noo_blog_sidebar', 'sidebar-main');
			}
		}

		// WooCommerce Product
		if( NOO_WOOCOMMERCE_EXIST ) {
            if(is_singular('noo_portfolio')){
                $post_layout=noo_hermosa_get_option('noo_portfolio_layout','sidebar');
                if($post_layout=='fullwidth'){
                    $sidebar='';
                }else{
                    $sidebar=noo_hermosa_get_option('noo_portfolio_sidebar','sidebar-main');
                }
            }
			if( is_product() ) {
				$product_layout = noo_hermosa_get_option('noo_woocommerce_product_layout', 'same_as_shop');
				$sidebar = '';
				if ( $product_layout == 'same_as_shop' ) {
					$product_layout = noo_hermosa_get_option('noo_shop_layout', 'fullwidth');
					$sidebar = noo_hermosa_get_option('noo_shop_sidebar', '');
				} else {
					$sidebar = noo_hermosa_get_option('noo_woocommerce_product_sidebar', '');
				}
				
				if ( $product_layout == 'fullwidth' ) {
					$sidebar = '';
				}
			}

			// Shop, Product Category, Product Tag, Cart, Checkout page
			if( is_shop() || is_product_category() || is_product_tag() ) {
				$shop_layout = noo_hermosa_get_option('noo_shop_layout', 'fullwidth');
				if($shop_layout != 'fullwidth'){
					$sidebar = noo_hermosa_get_option('noo_shop_sidebar', '');
				}
			}
		}

		/**
		 * Noo Event
		 */
		if ( class_exists( 'Noo__Timetable__Event' ) ) :

			if ( is_singular( 'noo_event' ) ) :

				$event_layout = noo_hermosa_get_option( 'noo_post_event_layout', 'same_as_event' );
				$sidebar = '';
				if ( $event_layout == 'same_as_event' ) {
					$event_layout = noo_hermosa_get_option( 'noo_event_layout', 'fullwidth' );
					$sidebar = noo_hermosa_get_option( 'noo_event_sidebar', '' );
				} else {
					$sidebar = noo_hermosa_get_option( 'noo_post_event_sidebar', '' );
				}
				
				if ( $event_layout == 'fullwidth' ) {
					$sidebar = '';
				}

			endif;

			if ( 
				is_post_type_archive( 'noo_event' )  ||
				is_tax( 'event_category' ) || 
                is_tax( 'event_location' )
			) :

				$event_layout = noo_hermosa_get_option('noo_event_layout', 'fullwidth');
				if($event_layout != 'fullwidth'){
					$sidebar = noo_hermosa_get_option('noo_event_sidebar', '');
				} else {
					$sidebar = '';
				}

			endif;

		endif;

		/**
		 * Noo Class
		 */
		if ( class_exists( 'Noo__Timetable__Class' ) ) :

			if( is_post_type_archive( 'noo_class' ) || is_tax( 'class_category' ) ) {
				$sidebar = '';
				$class_layout = noo_hermosa_get_option('noo_classes_layout', 'sidebar');
				if ($class_layout != 'fullwidth') {
					$sidebar = noo_hermosa_get_option('noo_classes_sidebar', '');
				}

			}
			
			// Single Class
			if (is_singular('noo_class')) {
				$post_layout = noo_hermosa_get_option('noo_class_layout', 'same_as_classes');
				$sidebar = '';
				if ($post_layout == 'same_as_classes') {
					$post_layout = noo_hermosa_get_option('noo_classes_layout', 'sidebar');
					$sidebar = noo_hermosa_get_option('noo_classes_sidebar', '');
				} else {
					$sidebar = noo_hermosa_get_option('noo_class_sidebar', '');
				}
				
				if($post_layout == 'fullwidth'){
					$sidebar = '';
				}
			}

		endif;

		/**
		 * Noo Trainer
		 */
		if ( class_exists( 'Noo__Timetable__Trainer' ) ) :

			if( is_post_type_archive( 'noo_trainer' ) ) {
				$sidebar = '';
				$class_layout = noo_hermosa_get_option('noo_trainer_layout', 'sidebar');
				if ($class_layout != 'fullwidth') {
					$sidebar = noo_hermosa_get_option('noo_trainer_sidebar', '');
				}
			}

		endif;

		return apply_filters( 'noo_sidebar_id', $sidebar );
	}
endif;

if (!function_exists('smk_get_all_sidebars')):
	function smk_get_all_sidebars() {
		global $wp_registered_sidebars;
		$sidebars = array();
		$none_sidebars = array();
		for ($i = 1;$i <= 4;$i++) {
			$none_sidebars[] = "noo-top-{$i}";
			$none_sidebars[] = "noo-footer-{$i}";
		}
		if ($wp_registered_sidebars && !is_wp_error($wp_registered_sidebars)) {
			
			foreach ($wp_registered_sidebars as $sidebar) {
				// Don't include Top Bar & Footer Widget Area
				if (in_array($sidebar['id'], $none_sidebars)) continue;
				
				$sidebars[$sidebar['id']] = $sidebar['name'];
			}
		}
		return $sidebars;
	}
endif;

if (!function_exists('noo_hermosa_get_sidebar_name')):
	function noo_hermosa_get_sidebar_name($id = '') {
		if (empty($id)) return '';
		
		global $wp_registered_sidebars;
		if ($wp_registered_sidebars && !is_wp_error($wp_registered_sidebars)) {
			foreach ($wp_registered_sidebars as $sidebar) {
				if ($sidebar['id'] == $id) return $sidebar['name'];
			}
		}
		
		return '';
	}
endif;
