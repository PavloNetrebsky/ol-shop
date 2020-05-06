<?php
/**
 * Create shortcode: [noo_service]
 *
 * @package 	Noo_Hermosa_Core
 * @author      KENT <tuanlv@vietbrain.com>
 */

if ( ! function_exists( 'shortcode_noo_service' ) ) :
	
	function shortcode_noo_service( $atts ) {
        $atts  = vc_map_get_attributes( 'noo_service', $atts );
		extract( shortcode_atts( array(
            'title'       => '',
            'style'       => 'style-1',
            'description' => '',
            'icon'        => '',
            'align'       => 'left',
            'image'       => '',
            'color'       => '#5ccbaa'
        ), $atts ) );

        ob_start();
        ?>

		<div class="noo-icon-wrap <?php echo esc_attr($align); ?> <?php echo esc_attr( $style );  ?>  ">
            
            <div class="noo-icon-body">
                <?php if(isset($title) && !empty($title)): ?>
                    <h3 class="icon-title"><?php echo esc_html( $title ); ?></h3>
                <?php endif; ?>
                <?php if(isset($description) && !empty($description)) :?>
                    <p class="icon-text"><?php echo esc_html( $description ); ?></p>
                <?php endif; ?>
            </div>
            <?php if($style != 'style-3') : ?>
                <div class="noo-icon-button">
                    <span style="background-color: <?php echo esc_attr( $color ); ?>; box-shadow: 0 0 12px 0 <?php echo esc_attr( $color ); ?>;">
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                    </span>
                </div>
            <?php else: ?>
                <div class="noo-img">
                    <?php if(isset($image)): 
                        echo wp_get_attachment_image(esc_attr($image),'full','',array('class'=>'noo-img-icon'));
                    endif; ?>
                </div>
            <?php endif; ?>

        </div>

		<?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_service', 'shortcode_noo_service' );

endif;