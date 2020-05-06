<?php

/**
 * Create shortcode: [noo_video]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		Tu Nguyen
 * @version 	1.0
 */

if( !function_exists('noo_shortcode_video') ){
    function noo_shortcode_video($atts){
        extract(shortcode_atts(array(
            'style'       => 'style-1',
            'title'       => '',
            'description' => '',
            'video_id'    => '',
            'video_type'  => 'youtube',
            'thumb_id'    =>  ''
        ),$atts));
        ob_start();
        ?>
        <div class="noo-video <?php echo esc_attr( $style ); ?>">
            <?php if( isset($title) && !empty($title) && $style === 'style-1' ): ?>
                <h3>
                    <?php
                    $title = explode( ' ', $title );
                    $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
                    $title = implode( ' ', $title );
                    ?>
                    <?php echo noo_hermosa_html_content_filter($title); ?>
                </h3>
            <?php endif; ?>
            <?php if( isset($description) && !empty($description) && $style === 'style-1' ): echo '<p>'.esc_html($description).'</p>'; endif; ?>
            <div class="thumb_image">
                <?php if( isset($thumb_id) && !empty($thumb_id) ): echo wp_get_attachment_image($thumb_id,'full'); endif; ?>
                <?php if ( ($style === 'style-1') || ($style === 'style-3')   ) : ?>
                    <span data-id="<?php echo esc_attr($video_id) ?>" data-type="<?php echo esc_attr($video_type); ?>" class="noo-control-video"><i class="fa fa-play"></i><i class="fa fa-play"></i></span>
                <?php endif; ?>
            </div>
            <?php if ( $style === 'style-2' ) : ?>
                <div class="noo-video-action">
                    <span data-id="<?php echo esc_attr($video_id) ?>" data-type="<?php echo esc_attr($video_type); ?>" class="noo-control-video"><i class="fa fa-play"></i><i class="fa fa-play"></i></span>
                    <div class="video-info">
                        
                        <?php 

                            if ( isset( $title ) && !empty( $title ) ) :
                                echo '<h3>' . noo_hermosa_html_content_filter($title) . '</h3>';
                            endif;

                            if ( isset( $description ) && !empty( $description ) ): 
                                echo '<p>' . esc_html( $description ) . '</p>';
                            endif;
                        ?>
                    
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
        $video = ob_get_contents();
        ob_end_clean();
        return $video;
    }
    add_shortcode('noo_video','noo_shortcode_video');
}