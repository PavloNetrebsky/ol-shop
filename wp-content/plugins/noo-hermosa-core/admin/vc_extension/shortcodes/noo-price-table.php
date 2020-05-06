<?php
/**
 * Create shortcode: [noo_find_event]
 *
 * @package     Noo_Hermosa_Core
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */
if( !function_exists( 'shortcode_noo_price_table' ) ){

    function shortcode_noo_price_table( $atts ){
        extract( shortcode_atts( array(
            'style'      =>  'style-1',
            'title'      =>  '',
            'sub_title'  =>  '',
            'price'      =>  '',
            'button'     =>  '',
            'price_item' =>  ''
        ), $atts ) );

        ob_start();
        ?>
            <div class="noo-pricetable <?php echo esc_attr($style); ?>">
                <div class="noo-pricetable-header">
                    <span>
                        <?php echo esc_html( $sub_title ); ?>
                    </span>
                    
                    <h4><?php echo esc_html( $title );?></h4>
                    
                    <span class="item-price">
                        <?php echo esc_html($price); ?>
                    </span>
                </div>


                <div class="noo-pricetable-content">
                    <?php
                        $new_price_item = vc_param_group_parse_atts( $price_item );
                        foreach( $new_price_item as $item ) :
                            echo '<div class="item">' . esc_html( $item['title'] ) . '</div>';
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

    add_shortcode( 'noo_pricetable', 'shortcode_noo_price_table' );

}