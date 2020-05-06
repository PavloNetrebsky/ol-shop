<?php
/**
 * Create shortcode: [noo_opent_hours]
 *
 * @package     Noo_Hermosa_Core
 * @author      KENT <manhnv@vietbrain.com>
 * @version     1.0
 */
if( !function_exists( 'shortcode_noo_pricing_plan' ) ){

    function shortcode_noo_pricing_plan( $atts ){
        $atts  = vc_map_get_attributes( 'noo_pricing_plan', $atts );
        extract( shortcode_atts( array(
            'title'             =>  '',
            'desc'              =>  '',
            'button'            =>  '',
            'position'          =>  'left',
            'pricing_item'      =>  ''
        ), $atts ) );
        $style_position = '';

        $style_position   .= ( $position != '' ) ? ' text-align: ' . $position . ';' : '';

        ob_start();
        ?>
            <div class="pricing_plan ">
                <div class="header-icon-title pricing-plan-header"  style="<?php echo $style_position; ?>">
                    <h4 class="icon-title"><?php echo esc_html( $title );?></h4>
                </div>
                <?php if(isset($desc) && !empty($desc)) :?>
                    <p class="item-desc">
                        <?php echo esc_html($desc); ?>
                    </p>
                <?php endif; ?>

                <div class="pricing-plan-content">
                    <?php
                        $new_pricing_item = vc_param_group_parse_atts( $pricing_item );
                        foreach( $new_pricing_item as $item ) :
                            echo '<div class="item">
                                    <div class="item-content-left"><div class="item-content-title">' . esc_html( $item['title'] ).'</div><div class="item-content-desc">'. esc_html( $item['sub_title'] ). '</div></div><div class="item-content-right">'. esc_html( $item['price'] ).'</div></div>';
                        endforeach;
                    ?>
                </div>

                <?php if ( !empty( $button ) ) : ?>
                    <?php
                        $info_btn = vc_build_link( $button );
                        $targer   = !(empty( $info_btn['target'] )) ? " targer='" . esc_attr( $info_btn['target'] ) . "'" : '';
                        echo '<a class="button" href="' . esc_url( $info_btn['url'] ) . '" ' . $targer . '>' . esc_html( $info_btn['title'] ) . '</a>';
                    ?>
                <?php endif; ?>
            </div>

        <?php
        $pricetable = ob_get_contents();
        ob_end_clean();
        return $pricetable;
    }

    add_shortcode( 'noo_pricing_plan', 'shortcode_noo_pricing_plan' );

}