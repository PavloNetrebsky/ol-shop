<?php
/**
 * This file register the required and recommended plugins to used in this theme.
 *
 *
 * @package    NOO Blank
 * @subpackage Plugin Registration
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if ( ! function_exists( 'noo_hermosa_register_theme_plugins' ) ) :
	function noo_hermosa_register_theme_plugins() {

		$plugins = array(
            
			array(
                'name'               => 'WPBakery Visual Composer',
                'slug'               => 'js_composer',
                'source'             => 'http://wp.nootheme.com/plugin-files/js_composer.zip',
                'required'           => true,
                'version'            => '6.1',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
            ),

            array(
                'name'               => 'Revslider',
                'slug'               => 'revslider',
                'source'             => 'http://wp.nootheme.com/plugin-files/revslider.zip',
                'required'           => false,
                'version'            => '6.1.5',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
            ),

            array(
                'name'               => 'Noo Hermosa Core',
                'slug'               => 'noo-hermosa-core',
                'source'             => 'http://wp.nootheme.com/plugin-files/noo-hermosa-core.zip',
                'required'           => true,
                'version'            => '1.4.0',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
            ),

            array(
                'name'               => 'Noo Timetable',
                'slug'               => 'noo-timetable',
                'source'             => 'http://wp.nootheme.com/plugin-files/noo-timetable.zip',
                'required'           => true,
                'version'            => '2.0.6.1',
                'force_activation'   => false,
                'force_deactivation' => false,
                'external_url'       => '',
            ),

            array(
                'name'    => 'Contact Form 7',
                'slug'    => 'contact-form-7',
                'required'  => false,
            ),
            array(
                'name'    => 'Mailchimp For WP',
                'slug'    => 'mailchimp-for-wp',
                'required'  => false,
            ),
            array(
                'name'    => 'WooCommerce',
                'slug'    => 'woocommerce',
                'required'  => false,
            ),
		);

		$config = array(
            'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
            'default_path' => '',                      // Default absolute path to bundled plugins.
            'menu'         => 'tgmpa-install-plugins', // Menu slug.
            'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
            'has_notices'  => true,                    // Show admin notices or not.
            'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
            'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
            'is_automatic' => false,                   // Automatically activate plugins after installation or not.
            'message'      => '',                      // Message to output right before the plugins table.
            'notice_ask_to_update'            => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'noo-hermosa'
            ),
            'notice_ask_to_update_maybe'      => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'noo-hermosa'
            ),
        );

		tgmpa( $plugins, $config );

	}

	add_action( 'tgmpa_register', 'noo_hermosa_register_theme_plugins' );
endif;

