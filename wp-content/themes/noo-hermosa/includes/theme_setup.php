<?php
/**
 * Initialize Theme functions for NOO Themes.
 *
 * @package    NOO Themes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Content Width
if ( ! isset( $content_width ) ) :
	$content_width = 970;
endif;

// Initialize Theme
if (!function_exists('noo_hermosa_init_theme')):
	function noo_hermosa_init_theme() {
		load_theme_textdomain( 'noo-hermosa', get_template_directory() . '/languages' );

		require_once( NOO_INCLUDES . '/libs/noo-check-version.php' );
 
        if ( is_admin() ) {     
            $license_manager = new Noo_Hermosa_Check_Version(
                'noo-hermosa',
                'Noo Hermosa',
                'http://update.nootheme.com/api/license-manager/v1',
                'theme',
                '',
                false
            );
        }

		// Title Tag -- From WordPress 4.1.
		add_theme_support('title-tag');
		// @TODO: Automatic feed links.
		add_theme_support('automatic-feed-links');
		// Add support for some post formats.
		add_theme_support('post-formats', array(
			'image',
			'gallery',
			'video',
			'audio',
			'quote'
		));

		add_theme_support( 'noo-hermosa' );

		// WordPress menus location.
		$menu_list = array();
		
		$menu_list['primary-menu']  = esc_html__( 'Primary Menu', 'noo-hermosa');
		$menu_list['primary-left']  = esc_html__( 'Primary Menu Left', 'noo-hermosa');
		$menu_list['primary-right'] = esc_html__( 'Primary Menu Right', 'noo-hermosa');
		$menu_list['top-menu']      = esc_html__( 'Top Menu', 'noo-hermosa');
		
		
		if (noo_hermosa_get_option('noo_footer_top', false)) {
			$menu_list['footer-menu'] = esc_html__( 'Footer Menu', 'noo-hermosa');
		}

		// Register Menu
		register_nav_menus($menu_list);

		// Define image size
		add_theme_support('post-thumbnails');
		
		add_image_size( 'noo-thumbnail-product', 270, 340, true );
		add_image_size( 'noo-thumbnail-small', 70, 88, true );
		add_image_size( 'noo-thumbnail-medium', 270, 340, true );
		add_image_size( 'noo-thumbnail-big', 540, 680, true );

		add_image_size( 'noo-thumbnail-trainer', 220, 220, true );

		$default_values = array( 
				'primary_color'         => '#e182a8',
				'secondary_color'       => '#5f41a5',
				'font_family'           => 'Lato',
				'text_color'            => '#696969',
				'font_size'             => '16',
				'font_weight'           => '400',
				'headings_font_family'  => 'Lato',
				'nav_font_family'       => 'Lato',
				'headings_color'        => '#696969',
				'logo_color'            => '#696969',
				'logo_font_family'      => 'Lato',
			);
		noo_hermosa_set_theme_default( $default_values );
	}
	add_action('after_setup_theme', 'noo_hermosa_init_theme');
endif;