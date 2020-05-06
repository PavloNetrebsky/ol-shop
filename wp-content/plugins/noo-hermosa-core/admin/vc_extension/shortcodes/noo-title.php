<?php
/**
 * Create shortcode: [noo_title]
 *
 * @package 	Noo_Hermosa_Core
 * @author      Hung Ngo <hungnt@vietbrain.com>
 */

if ( ! function_exists( 'shortcode_noo_title' ) ) :
	
	function shortcode_noo_title( $atts ) {
        $atts  = vc_map_get_attributes( 'noo_title', $atts );
		extract( shortcode_atts( array(
            'title'      => '',
            'sub_title'  => '',
            'text_align' => 'center',
            'style'      => 'style-1'
        ), $atts ) );

        ob_start();
        
        $align = array( 'left', 'right', 'center' );
        if ( !in_array($text_align, $align) ) {
            $text_align = 'center';
        }
        ?>

		<div class="noo-theme-wraptext <?php echo esc_attr( $text_align ); ?> <?php echo esc_attr($style); ?>">
            <div class="wrap-title">

            <?php if ( !empty( $title ) ) : ?>
                <div class="noo-theme-title-bg"></div>

                <h3 class="noo-theme-title">
                    <?php
                        $title = explode( ' ', $title );
                        $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
                        $title = implode( ' ', $title );
                    ?>
                    <?php echo $title; ?>
                </h3>
            <?php endif; ?>

            <?php if ( !empty( $sub_title ) ) : ?>
                <p class="noo-theme-sub-title">
                    <?php echo esc_html( $sub_title ); ?>
                </p>
            <?php endif; ?>

            </div> <!-- /.wrap-title -->    
        </div>

		<?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_title', 'shortcode_noo_title' );

endif;