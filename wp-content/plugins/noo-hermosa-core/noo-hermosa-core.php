<?php
/**
 * @package noo-hermosa-core
 */
/*
Plugin Name: NOO Hermosa Core
Plugin URI: http://nootheme.com/
Description: Plugin that adds all post types needed by our theme.
Version: 1.4.3
Author: NooTheme
Author URI: http://nootheme.com/
Text Domain: noo-hermosa-core
License: GPLv2 or later
*/

$themee = array(
    'NOO Hermosa',
    'NOO Hermosa Child Theme'
);

if ( ! in_array(wp_get_theme(), $themee) ) {
    return;
}



if ( !class_exists('Noo_Hermosa_Core') ):

    class Noo_Hermosa_Core{

        /*
         * This method loads other methods of the class.
         */
        public function __construct(){
            /* load languages */
            $this -> load_languages();

            /*load all nootheme*/
            $this -> load_nootheme();

            /*auto update version*/
            $this->load_check_version();
        }

        /*
         * Load the languages before everything else.
         */
        private function load_languages(){
            add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        }

        /*
         * Load the text domain.
         */
        public function load_textdomain(){

            load_plugin_textdomain(  'noo-hermosa-core', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
        }

        /*
         * Load Nootheme on the 'after_setup_theme' action. Then filters will
         */
        public function load_nootheme(){

            $this -> constants();

            $this -> includes();
        }

        /*
         * Load Nootheme on the 'after_setup_theme' action. Then filters will
         */
        public function load_check_version(){

            if( !class_exists('Noo_Check_Version_Child') ) {
                require_once( NOO_PLUGIN_SERVER_PATH.'/admin/noo-check-version-child.php' );
            }

            $check_version = new Noo_Check_Version_Child(
                'noo-hermosa-core',
                'NOO hermosa Core',
                'noo-hermosa',
                'http://update.nootheme.com/api/license-manager/v1',
                'plugin',
                __FILE__
            );
        }

        /**
         * Constants
         */
        private function constants(){

            if( !defined( 'NOO_PLUGIN_PATH' ) ) define('NOO_PLUGIN_PATH', plugin_dir_url( __FILE__ ));

            if( !defined( 'NOO_PLUGIN_ASSETS_URI' ) ) define('NOO_PLUGIN_ASSETS_URI', plugin_dir_url( __FILE__ ) . 'assets');

            if( !defined( 'NOO_PLUGIN_SERVER_PATH' ) ) define('NOO_PLUGIN_SERVER_PATH', dirname( __FILE__ ) );

            if( !defined( 'NOO_FRAMEWORK' ) ) define('NOO_FRAMEWORK', dirname( __FILE__ ) . '/framework' );

            if( !defined( 'NOO_FRAMEWORK_URI' ) ) define('NOO_FRAMEWORK_URI', plugin_dir_url( __FILE__ ) . 'framework' );

            if( !defined( 'NOO_PLUGIN_WIDGETS' ) ) define( 'NOO_PLUGIN_WIDGETS', dirname( __FILE__ ) . '/widgets' );

        }

        /*
         * Require file
         */
        private function  includes(){
            require_once NOO_PLUGIN_SERVER_PATH . '/admin/importer/noo-setup-install.php';
            require_once NOO_PLUGIN_SERVER_PATH . '/admin/post-type/function-init.php';
            require_once NOO_PLUGIN_SERVER_PATH . '/admin/vc_extension/vc_init.php';

            // Init Framework.
            require_once NOO_FRAMEWORK . '/_init.php';

            // Init Widgets
            require_once NOO_PLUGIN_WIDGETS . '/widgets_init.php';
        }


    }
    $oj_nooplugin = new Noo_Hermosa_Core();

endif;

// Add NOO-Customizer Menu
function noo_hermosa_add_customizer_menu() {
    $customizer_icon = 'dashicons-admin-customizer';

    add_menu_page( esc_html__( 'Customizer', 'noo-hermosa' ), esc_html__( 'Customizer', 'noo-hermosa' ), 'edit_theme_options', 'customize.php', null, $customizer_icon, 61 );
    add_submenu_page( 'options.php', '', '', 'edit_theme_options', 'export_settings', 'noo_hermosa_customizer_export_theme_settings' );
}
add_action( 'admin_menu', 'noo_hermosa_add_customizer_menu' );

require_once dirname( __FILE__ ) . '/admin/smk-sidebar-generator/smk-sidebar-generator.php';
require_once dirname( __FILE__ ) . '/admin/twitter/twitteroauth.php';


if( !function_exists('noo_get_instagram_data') ) :
     // using standard_resolution / thumbnail / low_resolution
    function noo_get_instagram_data($username){
        $username = strtolower( $username );
        $username = str_replace( '@', '', $username );
        if ( false === ($instagram = get_transient( 'noo_instagram_'.sanitize_title_with_dashes( $username ) )) ) {
            $remote = wp_remote_get( 'http://instagram.com/'.trim( $username ) );

            if ( is_wp_error( $remote ) )
                return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'noo-hermosa' ) );

            if ( 200 != wp_remote_retrieve_response_code( $remote ) )
                return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'noo-hermosa' ) );

            $shards = explode( 'window._sharedData = ', $remote['body'] );
            $insta_json = explode( ';</script>', $shards[1] );
            $insta_array = json_decode( $insta_json[0], TRUE );

            if ( ! $insta_array )
                return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'noo-hermosa' ) );

            if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
                $images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
            } elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
                $images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
            } else {
                return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'noo-hermosa' ) );
            }
            
            if ( ! is_array( $images ) )
                return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'noo-hermosa' ) );
            $instagram = array();
            
            foreach ( $images as $image ) {

                if ( isset( $image['is_video'] ) ) {
                    $type = 'video';
                } else {
                    $type = 'image';
                }

                $caption = esc_html__( 'Instagram Image', 'noo-hermosa' );
                if ( ! empty( $image['caption'] ) ) {
                    $caption = $image['caption'];
                }
                $instagram[] = array(
                    'description' => $caption,
                    'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
                    'time'        => $image['node']['taken_at_timestamp'],
                    'comments'    => $image['node']['edge_media_to_comment']['count'],
                    'likes'       => $image['node']['edge_liked_by']['count'],
                    'thumbnail'     => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
                    'small'         => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
                    'large'         => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
                    'original'      => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
                    'type'          => $type
                );
            }
            // do not set an empty transient - should help catch private or empty accounts
            if ( ! empty( $instagram ) ) {
                $instagram = base64_encode( serialize( $instagram ) );
                set_transient( 'noo_instagram_' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
            }
        }
        if ( ! empty( $instagram ) ) {

            return unserialize( base64_decode( $instagram ) );

        } else {

            return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'noo-hermosa' ) );

        }
    }
endif;
