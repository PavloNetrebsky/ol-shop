<?php
/**
 * Style Functions for NOO Framework.
 * This file contains functions for calculating style (normally it's css class) base on settings from admin side.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if (!function_exists('noo_hermosa_body_class')):
	function noo_hermosa_body_class($output) {
		global $wp_customize;
		if (isset($wp_customize)) {
			$output[] = 'is-customize-preview';
		}

		// Preload
		if( noo_hermosa_get_option( 'noo_preload', false ) ) {
			$output[] = 'enable-preload';
		}

		$page_layout = noo_hermosa_get_page_layout();
		if ($page_layout == 'fullwidth') {
			$output[] = ' page-fullwidth';
		} elseif ($page_layout == 'left_sidebar') {
			$output[] = ' page-left-sidebar';
		} else {
			$output[] = ' page-right-sidebar';
		}
		
		switch (noo_hermosa_get_option('noo_site_layout', 'fullwidth')) {
			case 'boxed':
				// if(get_page_template_slug() != 'page-full-width.php')
				$output[] = 'boxed-layout';
			break;
			default:
				$output[] = 'full-width-layout';
			break;
		}

        if ( noo_hermosa_get_option('noo_layout_rtl','no') == 'yes' ){
            $output[] = 'theme-rtl';
        }
		return $output;
	}
endif;
add_filter('body_class', 'noo_hermosa_body_class');

if (!function_exists('noo_hermosa_header_class')):
	function noo_hermosa_header_class() {
		$class = '';
		$navbar_position = noo_hermosa_get_option('noo_header_nav_position', 'fixed_top');
        $menu_style  = noo_hermosa_get_option('noo_header_nav_style','header1');

        if( is_page() ){
            $headerpage   = noo_hermosa_get_post_meta(get_the_ID(),'_noo_wp_page_header_style');
            if( !empty($headerpage) && $headerpage != 'header' ){
                $menu_style = $headerpage;
            }

            $page_nav_position = noo_hermosa_get_post_meta(get_the_ID(), '_noo_wp_page_nav_position');
            if ('default_position' != $page_nav_position && '' != $page_nav_position){
            	$navbar_position = $page_nav_position;
            }
        }

		if ('fixed_top' == $navbar_position) {
			$class = 'fixed_top';
		} elseif ('fixed_scroll' == $navbar_position) {
			$class = 'fixed_scroll';
		}
		
        if ( $menu_style == 'header1' ) {
            $class .= ' header-default';
        } elseif ( $menu_style == 'header2' ) {
            $class .= ' header-logo-transparent';
        } elseif ( $menu_style == 'header3' || $menu_style=='header4' ) {
            $class .= ' header-background-transparent';
        }

		echo esc_attr($class);
	}
endif;


if (!function_exists('noo_hermosa_main_class')):
	function noo_hermosa_main_class() {
		$class = 'noo-main';
		$page_layout = noo_hermosa_get_page_layout();

		if ($page_layout == 'fullwidth') {
			$class.= ' noo-md-12';
		} elseif ($page_layout == 'left_sidebar') {
			$class.= ' noo-md-9 pull-right';
		} else {
			$class.= ' noo-md-9';
		}
		
		echo esc_attr($class);
	}
endif;

if (!function_exists('noo_hermosa_sidebar_class')):
	function noo_hermosa_sidebar_class() {
		$class = ' noo-sidebar noo-md-3';
		$page_layout = noo_hermosa_get_page_layout();
		
		if ( $page_layout == 'left_sidebar' ) {
			$class .= ' noo-sidebar-left pull-left';
		}
		
		echo esc_attr($class);
	}
endif;

if (!function_exists('noo_hermosa_post_class')):
	function noo_hermosa_post_class($output) {
		if (noo_hermosa_has_featured_content()) {
			$output[] = 'has-featured';
		} else {
			$output[] = 'no-featured';
		}
		
		return $output;
	}
	
	add_filter('post_class', 'noo_hermosa_post_class');
endif;
