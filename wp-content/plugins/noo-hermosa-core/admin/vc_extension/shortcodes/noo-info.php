<?php
/**
 * Create shortcode: [noo_info]
 *
 * @package 	Noo_Hermosa_Core
 * @author      KENT <tuanlv@vietbrain.com>
 */

if ( ! function_exists( 'shortcode_noo_info' ) ) :
	
	function shortcode_noo_info( $atts ) {

		extract( shortcode_atts( array(
            'description' => '',
            'icon'        => ''
        ), $atts ) );

        ob_start();
 
        ?>

		<div class="noo-info-wrap">
            
            <?php if ( !empty( $icon ) ) : ?>

                <span class="icon">
                    <span class="icon-child"></span>
                    <i class="<?php echo esc_attr( $icon ); ?>"></i>
                </span>

            <?php endif; ?>

            <?php if ( !empty( $description ) ) : ?>

                <p class="content">
                    <?php echo noo_hermosa_html_content_filter( $description ); ?>
                </p>

            <?php endif; ?>

        </div>

		<?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_info', 'shortcode_noo_info' );

endif;