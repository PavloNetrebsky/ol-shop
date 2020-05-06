<?php
/**
 * Create shortcode: [noo_multiple_service]
 *
 * @package 	Noo_Hermosa_Core
 * @author      KENT <tuanlv@vietbrain.com>
 */

if ( ! function_exists( 'shortcode_noo_multiple_service' ) ) :
	
	function shortcode_noo_multiple_service( $atts ) {

		extract( shortcode_atts( array(
            'title'        => '',
            'sub_title'    => '',
            'image'        => '',
            'service_item' => ''
        ), $atts ) );

        ob_start();
 
        ?>

        <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
        
            <div class="noo-theme-wraptext">
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

        <?php endif; ?>

        <div class="service-wrap">
            <?php 
                if ( !empty( $image ) ) :

                    echo '<div class="noo-image">';
                    echo wp_get_attachment_image( $image, 'full' );
                    echo '</div>';

                endif;
            ?>
            <div class="service-item-wrap">
                
            <?php

                $new_service_item = vc_param_group_parse_atts( $service_item );
                $i = 0;
                foreach( $new_service_item as $item ) :
                    $class = ( $i % 2 === 0 ? 'start' : 'end' );
                    ?>
                    <div class="noo-icon-wrap <?php echo esc_attr( $class ); ?>">
                        
                        <div class="noo-icon-body">
                            
                            <h3 class="icon-title"><?php echo esc_html( $item['title'] ); ?></h3>
                            <p class="icon-text"><?php echo esc_html( $item['description'] ); ?></p>

                        </div>

                        <div class="noo-icon-button">
                            <span style="background-color: <?php echo esc_attr( $item['color'] ); ?>; box-shadow: 0 0 12px 0 <?php echo esc_attr( $item['color'] ); ?>;">
                                <i class="<?php echo esc_attr( $item['icon'] ); ?>"></i>
                            </span>
                        </div>

                    </div><?php $i++;
                endforeach;
            ?>
            </div>

        </div>

		<?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_multiple_service', 'shortcode_noo_multiple_service' );

endif;