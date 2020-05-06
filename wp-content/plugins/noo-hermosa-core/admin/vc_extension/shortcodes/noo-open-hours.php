<?php
/**
 * Create shortcode: [noo_opent_hours]
 *
 * @package     Noo_Hermosa_Core
 * @author      KENT <manhnv@vietbrain.com>
 * @version     1.0
 */
if( !function_exists( 'shortcode_noo_open_hours' ) ){

    function shortcode_noo_open_hours( $atts ){
        $atts  = vc_map_get_attributes( 'noo_open_hours', $atts );
        extract( shortcode_atts( array(
            'title'             =>  '',
            'position'          =>  '',
            'desc'              =>  '',
            'button'            =>  '',
            'open_hour_item'    =>  ''
        ), $atts ) );

        $style_position = '';
        $style_position   .= ( $position != '' ) ? ' text-align: ' . $position . ';' : '';

        ob_start();
        ?>
            <div class="open-hours ">
                <div class="header-icon-title open-hours-header" style="<?php echo $style_position; ?>">
                    <h4 class="icon-title"><?php echo esc_html( $title );?></h4>
                </div>
                <p class="item-desc">
                    <?php echo esc_html($desc); ?>
                </p>

                <div class="open-hours-content">
                    <?php
                        $new_open_hour_item = vc_param_group_parse_atts( $open_hour_item );
                        foreach( $new_open_hour_item as $item ) :
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

    add_shortcode( 'noo_open_hours', 'shortcode_noo_open_hours' );

}