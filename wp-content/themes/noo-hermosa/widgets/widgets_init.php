<?php
/**
 * This file initialize widgets area used in this theme.
 *
 *
 * @package    NOO Framework
 * @subpackage Widget Initiation
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if ( ! function_exists( 'noo_hermosa_widgets_init' ) ) :

	function noo_hermosa_widgets_init() {
		
		/**
		 * Register Default Sidebar (WP main sidebar)
		 */
		register_sidebar(
			array(
				'name'          => esc_html__( 'Main Sidebar', 'noo-hermosa' ),
				'id'            => 'sidebar-main', 
				'description'   => esc_html__( 'Default Blog Sidebar.', 'noo-hermosa' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">', 
				'after_widget'  => '</div>', 
				'before_title'  => '<h4 class="widget-title">', 
				'after_title'   => '</h4>'
			)
		);

		if ( class_exists( 'woocommerce' ) ) :
			/**
			 * Register Shop Sidebar
			 */
			register_sidebar(
				array(
					'name'          => esc_html__( 'Shop Sidebar', 'noo-hermosa' ),
					'id'            => 'sidebar-shop', 
					'description'   => esc_html__( 'Shop Sidebar.', 'noo-hermosa' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">', 
					'after_widget'  => '</div>', 
					'before_title'  => '<h4 class="widget-title">', 
					'after_title'   => '</h4>'
				)
			);
		endif;

		/**
		 * Register Top Footer (Widgetized)
		 */
		register_sidebar(
			array(
				'name'          => esc_html__( 'NOO - Top Footer', 'noo-hermosa' ),
				'id'            => 'noo-top-footer', 
				'before_widget' => '<div id="%1$s" class="widget %2$s">', 
				'after_widget'  => '</div>', 
				'before_title'  => '<h4 class="widget-title">', 
				'after_title'   => '</h4>'
			)
		);
		
		/**
		 * Footer Columns (Widgetized)
		 */
		$num = ( noo_hermosa_get_option( 'noo_footer_widgets' ) == '' ) ? 4 : noo_hermosa_get_option( 'noo_footer_widgets' );
		for ( $i = 1; $i <= $num; $i++ ) :
			register_sidebar( 
				array( 
					'name'          => esc_html__( 'NOO - Footer Column #', 'noo-hermosa' ) . $i,
					'id'            => 'noo-footer-' . $i, 
					'before_widget' => '<div id="%1$s" class="widget %2$s">', 
					'after_widget'  => '</div>', 
					'before_title'  => '<h4 class="widget-title">', 
					'after_title'   => '</h4>'
				)
			);
		endfor;
	}
	add_action( 'widgets_init', 'noo_hermosa_widgets_init' );

endif;