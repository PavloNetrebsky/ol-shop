<?php
/**
 * Initialize Theme functions for NOO Framework.
 * This file include the framework functions, it should remain intact between themes.
 * For theme specified functions, see file functions-<theme name>.php
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Initialize NOO Libraries
require_once get_template_directory() . '/includes/libs/noo-theme.php';
require_once get_template_directory() . '/includes/libs/noo-layout.php';
require_once get_template_directory() . '/includes/libs/noo-post-type.php';
require_once get_template_directory() . '/includes/libs/noo-css.php';
require_once get_template_directory() . '/includes/libs/noo-customize.php';

// Initialize NOO Customizer
require_once NOO_FRAMEWORK . '/customizer/_init.php';

// Meta Boxes
require_once NOO_FRAMEWORK . '/meta-boxes/_init.php';

// Enqueue style for admin
if ( ! function_exists( 'noo_enqueue_admin_assets' ) ) :
	function noo_enqueue_admin_assets() {

		wp_register_style( 'noo-admin-css', NOO_FRAMEWORK_URI . '/assets/css/noo-admin.css', null, null, 'all' );
		wp_enqueue_style( 'noo-admin-css' );

		wp_register_style( 'font-awesome-css', get_template_directory_uri() . '/assets/vendor/fontawesome/css/font-awesome.min.css',array(),'4.1.0');
		wp_register_style( 'noo-icon-bootstrap-modal-css', NOO_FRAMEWORK_URI . '/assets/css/noo-icon-bootstrap-modal.css', null, null, 'all' );
		wp_register_style( 'noo-jquery-ui-slider', NOO_FRAMEWORK_URI . '/assets/css/noo-jquery-ui.slider.css', null, '1.10.4', 'all' );
		wp_register_style( 'chosen-css', NOO_FRAMEWORK_URI . '/assets/css/noo-chosen.css', null, null, 'all' );

		wp_register_style( 'alertify-core-css', NOO_FRAMEWORK_URI . '/assets/css/alertify.core.css', null, null, 'all' );
		wp_register_style( 'alertify-default-css', NOO_FRAMEWORK_URI . '/assets/css/alertify.default.css', array('alertify-core-css'), null, 'all' );
		
		wp_register_style( 'datetimepicker', get_template_directory_uri() . '/assets/vendor/datetimepicker/jquery.datetimepicker.css', '2.4.0' );
		wp_register_script( 'datetimepicker', get_template_directory_uri() . '/assets/vendor/datetimepicker/jquery.datetimepicker.js', array( 'jquery' ), '2.4.0', true );

		$nooDateTimePicker = array(
			'lang'  => noo_hermosa_getJqueryUII18nLocale(),
        );
        wp_localize_script( 'datetimepicker', 'nooDateTimePicker', $nooDateTimePicker );

		wp_register_style( 'timepicker', get_template_directory_uri() . '/assets/vendor/timepicker/jquery.ui.timepicker.css', '2.4.0' );
		wp_register_script( 'timepicker', get_template_directory_uri() . '/assets/vendor/timepicker/jquery.ui.timepicker.js', array( 'jquery' ), '2.4.0', true );
			
		
		// Main script
		wp_register_script( 'noo-admin-js', NOO_FRAMEWORK_URI . '/assets/js/noo-admin.js', array( 'jquery' ), null, true );
		wp_enqueue_script( 'noo-admin-js' );

		wp_register_script( 'noo-bootstrap-modal-js', NOO_FRAMEWORK_URI . '/assets/js/bootstrap-modal.js', array('jquery'), '2.3.2', true );
		wp_register_script( 'noo-bootstrap-tab-js',NOO_FRAMEWORK_URI . '/assets/js/bootstrap-tab.js',array('jquery'), '2.3.2', true);
		wp_register_script( 'noo-font-awesome-js', NOO_FRAMEWORK_URI . '/assets/js/font-awesome.js', array( 'noo-bootstrap-modal-js', 'noo-bootstrap-tab-js'), null, true );
		wp_register_script( 'chosen-js', NOO_FRAMEWORK_URI . '/assets/js/chosen.jquery.min.js', array( 'jquery'), null, true );
		wp_register_script( 'fileDownload-js', NOO_FRAMEWORK_URI . '/assets/js/jquery.fileDownload.js', array( 'jquery' ), null, true );
		wp_register_script( 'alertify-js', NOO_FRAMEWORK_URI . '/assets/js/alertify.mod.min.js', null, null, true );
		
		


	}
	add_action( 'admin_enqueue_scripts', 'noo_enqueue_admin_assets' );
endif;

// Enqueue style for admin
if ( ! function_exists( 'noo_enqueue_site_assets' ) ) :
	function noo_enqueue_site_assets() {

		wp_register_style( 'datetimepicker', get_template_directory_uri() . '/assets/vendor/datetimepicker/jquery.datetimepicker.css', '2.4.0' );
		wp_register_script( 'datetimepicker', get_template_directory_uri() . '/assets/vendor/datetimepicker/jquery.datetimepicker.js', array( 'jquery' ), '2.4.0', true );
		wp_register_script( 'noo-product', NOO_FRAMEWORK_URI . '/assets/js/noo-product.js', null, null, true );


		$nooDateTimePicker = array(
			'lang'  => noo_hermosa_getJqueryUII18nLocale(),
        );
        wp_localize_script( 'datetimepicker', 'nooDateTimePicker', $nooDateTimePicker );

	}
	add_action( 'wp_enqueue_scripts', 'noo_enqueue_site_assets' );
endif;