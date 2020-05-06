<?php
/**
 * Create shortcode: [noo_mailchimp]
 *
 * @package     Noo_Hermosa_Core
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( ! function_exists( 'noo_shortcode_mailchimp' ) ) :
    
    function noo_shortcode_mailchimp( $atts ){

        extract( shortcode_atts(array(
            'title'     =>  '',
            'sub_title' =>  ''
        ), $atts ) );

        ob_start();

        ?>
        <div class="noo-mailchimp">
            <div class="noo-mailchimp-left">
                <?php
                    if ( !empty( $title ) ) : 

                        echo '<h3 class="noo-title">' . esc_html($title) . '</h3>';

                    endif;

                    if ( !empty( $sub_title ) ) : 

                        echo '<span class="noo-sub-title">' . esc_html( $sub_title ) . '</span>';

                    endif;
                ?>
            </div>
            <div class="noo-mailchimp-right">
                <?php
                    if( function_exists('mc4wp_show_form') ){
                        mc4wp_show_form();
                    }
                ?>
            </div>
        </div>

        <?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

    }

    add_shortcode( 'noo_mailchimp', 'noo_shortcode_mailchimp' );

endif;