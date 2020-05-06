<?php
/**
 * Create shortcode: [Noo_Instagram]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		KENT <manhnv@vietbrain.com>
 * @version 	1.0
 */

if ( ! class_exists('Noo_Framework_Shortcode_Instagram') ) {
    class Noo_Framework_Shortcode_Instagram {
        function __construct() {
            add_shortcode( 'noo_instagram', array($this, 'noo_shortcode_instagram') );
        }
        function noo_shortcode_instagram ( $atts, $content = null ) {
        	$atts  = vc_map_get_attributes( 'noo_instagram', $atts );
            extract( shortcode_atts( array(
                'username'          => '',
                'layout_style'      => 'grid',
                'images_display'    => '10',
                'image_size'        => 'large',
                'images_columns'    => '8',
                'autoplay'          => 'false',
                'show_navigation'	=> 'false',
                'show_pagination'	=> 'false',
                'slide_duration'    => '1000',
                'class'             => '',
                ), $atts ) );
            ob_start();
            $class          = ( $class != '' ) ? 'noo-instagram ' . esc_attr( $class ) : 'noo-instagram';
            global $yolo_behealth_options;
            /* using standard_resolution / thumbnail / low_resolution */
            $data = noo_get_instagram_data( $username );

            if(!is_wp_error( $data )){
                $data = array_slice( $data, 0, intval($images_display) );
            }
            $plugin_path = untrailingslashit(plugin_dir_path(__FILE__));?>
            <div class = "noo-instagram-wrap">
                <?php   require_once $plugin_path.'/templates/'.$layout_style.'.php';?>
               <!-- <div class="btn_gallery container">
                    <a href="https://instagram.com/<?php echo esc_html($username); ?>"><?php echo esc_html_e('@', 'noo-hermosa'); ?><?php echo esc_html($username); ?></a>
                </div> -->
            </div>
            <?php
            $content =  ob_get_clean();
            return $content;         
        }
    }
    new Noo_Framework_Shortcode_Instagram();
}
?>