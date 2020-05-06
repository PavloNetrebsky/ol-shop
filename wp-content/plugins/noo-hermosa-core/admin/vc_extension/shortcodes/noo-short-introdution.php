<?php
/**
 * Create shortcode: [noo_short_intro]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		Tu Nguyen
 * @version 	1.0
 */

if( !function_exists('noo_shortcode_short_intro') ){
    function noo_shortcode_short_intro($atts){
        extract(shortcode_atts(array(
            'icon'         =>  '',
            'title'            =>  '',
            'description'      =>  '',
            'custom_link'      =>  ''
        ),$atts));
        ob_start();
        ?>
        <div class="noo-short-intro">
            <?php if( isset($icon) && !empty($icon) ): echo wp_get_attachment_image($icon,'full'); endif; ?>

            <?php if( isset($title) && !empty($title) ):
                $title = explode( ' ', $title );
                $count = count($title);
                $title[$count-1] = '<span>' . esc_html( $title[$count-1] ) . '</span>';
                $title = implode( ' ', $title );
                echo '<h3>'.noo_hermosa_html_content_filter($title).'</h3>';  endif; ?>

            <?php if( isset($description) && !empty($description) ): echo '<p>'.esc_html($description).'</p>';  endif; ?>
            <?php
            if( isset( $custom_link ) && !empty( $custom_link )){
                $link = vc_build_link( $custom_link );
                ?>
                <a class="custom_link" href="<?php echo esc_url($link['url']) ?>" <?php if( isset($link['target']) && !empty( $link['target'] ) ): ?>target="_blank" <?php endif; ?>><?php echo esc_html($link['title']) ?></a>
            <?php
            }
            ?>
        </div>
        <?php
        $short = ob_get_contents();
        ob_end_clean();
        return $short;
    }
    add_shortcode('noo_short_intro','noo_shortcode_short_intro');
}