<?php

    if( !function_exists('noo_shortcode_banner_image') ){

        function noo_shortcode_banner_image($atts){
            $atts  = vc_map_get_attributes( 'noo_banner_image', $atts );
            extract(shortcode_atts(array(
            	'image'		=>  '',
                'title'     =>  '',
                'link'      =>  '#',
                'desc'      =>  '',
                
            ),$atts));
            ob_start();
            ?>
                <div class="noo-item-banner">
            	   <?php if( isset($image) && !empty($image) ):
                    	echo wp_get_attachment_image(esc_attr($image),'full','',array('class'=>'noo-item-image'));
                    endif; ?>
                    <div class="noo-item-content">
                    
                        <?php if( isset($title) && !empty($title)) :?>
                            <h4 class="noo-item-title"><a href = "<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a></h4>
                        <?php endif;?>
                        <p class="noo-item-desc"><?php echo esc_html($desc); ?></p>
                    </div>
                </div>
            <?php
            $info = ob_get_contents();
            ob_end_clean();
            return $info;

        }
        add_shortcode('noo_banner_image','noo_shortcode_banner_image');
    }