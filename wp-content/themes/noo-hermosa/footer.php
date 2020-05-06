<?php
$noo_bottom_bar_content = noo_hermosa_get_option( 'noo_bottom_bar_content', noo_hermosa_html_content_filter( __( '&copy; 2015. Designed with <i class="fa fa-heart text-primary" ></i> by NooTheme', 'noo-hermosa' ) ) );
$footer_style           = noo_hermosa_get_option( 'noo_footer_style', 'style-1' );
$num                    = noo_hermosa_get_option( 'noo_footer_widgets', '3' );

$latitude               = noo_hermosa_get_option( 'noo_map_lat', '51.508742' );
$longitude              = noo_hermosa_get_option( 'noo_map_lng', '-0.120850' );
$zoom                   = noo_hermosa_get_option( 'noo_map_zoom', '14' );
$icon                   = noo_hermosa_get_option( 'noo_map_icon', NOO_ASSETS_URI . '/images/Map-marker.png' );

/**
 * Check setting page
 */
if ( is_page() ) {

    $page_footer_style = get_post_meta( get_the_id(), '_noo_wp_page_footer_style', true );
    if ( !empty( $page_footer_style ) && $page_footer_style !== 'same_as_customizer' ) {
        $footer_style = $page_footer_style;

        if ( $footer_style === 'style-2' ) {
            $latitude  = noo_hermosa_get_post_meta( get_the_id(), '_noo_wp_page_map_lat', $latitude );
            $longitude = noo_hermosa_get_post_meta( get_the_id(), '_noo_wp_page_map_lng', $longitude );
            $zoom      = noo_hermosa_get_post_meta( get_the_id(), '_noo_wp_page_map_zoom', $zoom );
            $icon      = noo_hermosa_get_post_meta( get_the_id(), '_noo_wp_page_map_icon', $icon );
            if( !empty( $icon ) && is_numeric( $icon ) )
                $icon = wp_get_attachment_url( $icon );
        }
    }
}

if ( $footer_style === 'style-2' ) :
    wp_enqueue_script( 'noo-maps' );
endif;
?>
    <div class="footer-<?php echo esc_attr( $footer_style ); ?>">
        <footer class="wrap-footer">

            <?php if ( $footer_style === 'style-2' ) : ?>
                
                <?php
                    $google_map_api_key = '';
                    if ( class_exists('Noo__Timetable__Main') ) {
                        $google_map_api_key = NOO_Settings()->get_option( 'noo_google_map_api_key', '' );
                    }
                ?>
                

                <?php if ( !empty($google_map_api_key) ): ?>
                    <div class="noo-maps-wrap">
                        <div class="noo-maps" data-icon="<?php echo esc_attr( $icon ); ?>" data-zoom="<?php echo esc_attr( $zoom ); ?>" data-lat="<?php echo esc_attr( $latitude ); ?>" data-lng="<?php echo esc_attr( $longitude ); ?>"></div>
                    </div>
                <?php else: ?>
                    <iframe width="100%" height="483px" frameborder="0" scrolling="no" marginheight="0"
                            marginwidth="0"
                            src="https://maps.google.com/maps?q=<?php echo esc_attr($latitude); ?>,<?php echo esc_attr($longitude); ?>&hl=es;z=14&amp;output=embed"></iframe>
                <?php endif; ?>

            <?php endif; ?>

            <div class="footer-content">
                <?php if($footer_style=='style-2'|| $footer_style=='style-1'): ?>
                <div class="noo-top-footer">
                    <div class="noo-container">
                        <?php dynamic_sidebar( 'noo-top-footer' ); ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ( $num != 0 ) : ?>
                    <!--Start footer widget-->
                    <div class="colophon wigetized">
                        <div class="noo-container">
                            <div class="noo-row">
                                <?php

                                $i = 0; while ( $i < $num ) : $i ++;
                                    switch ( $num ) {
                                        case 4 : $class = 'noo-md-3 noo-sm-6';  break;
                                        case 3 :
                                            $class = 'noo-md-4 noo-sm-4';
                                            break;
                                        case 2 : $class = 'noo-md-6 noo-sm-12';  break;
                                        case 1 : $class = 'noo-md-12'; break;
                                    }
                                    echo '<div class="' . $class . '">';
                                    dynamic_sidebar( 'noo-footer-' . $i );
                                    echo '</div>';
                                endwhile;

                                ?>
                            </div>
                        </div>
                    </div>
                    <!--End footer widget-->
                <?php endif; ?>
            </div>

            <?php if ( !empty( $noo_bottom_bar_content ) ) : ?>
                <div class="noo-bottom-bar-content">
                   <div class="noo-container">
                       <div class="noo-row">
                           <div class="noo-footer-content-filter">
                               <?php echo noo_hermosa_html_content_filter($noo_bottom_bar_content); ?>
                           </div>
                           <?php if ($footer_style == 'style-3'): ?>
                               <?php dynamic_sidebar('noo-top-footer'); ?>
                           <?php endif; ?>
                       </div>
                   </div>
                </div>
            <?php endif; ?>
        </footer>
    </div>
</div>
<!--End .site -->


<?php wp_footer(); ?>

</body>
</html>
