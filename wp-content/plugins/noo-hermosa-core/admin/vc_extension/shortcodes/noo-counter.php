<?php
/**
 * Create shortcode: [noo_counter]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		Tu Nguyen
 * @version 	1.0
 */
    if( !function_exists('noo_shortcode_count') ):

        function noo_shortcode_count($attr){

            extract( shortcode_atts( array(
                'style_counter' =>  '',
                'icon'          => '',
                'number'        =>  '',
                'title'         =>  '',
            ), $attr ) );
            ob_start();
            wp_enqueue_script( 'countTo');
            ?>
            <div class="noo-counter-wrap">
                <?php echo wp_get_attachment_image($icon); ?>
                <div class="noo-counter-content">
                    <h4 class="noo-counter"><?php echo esc_html($number); ?></h4>
                    <span><?php echo esc_html($title); ?></span>
                </div>
            </div>
            <?php
            $count = ob_get_contents();
            ob_end_clean();
            return $count;
        }
        add_shortcode('noo_counter','noo_shortcode_count');
    endif;