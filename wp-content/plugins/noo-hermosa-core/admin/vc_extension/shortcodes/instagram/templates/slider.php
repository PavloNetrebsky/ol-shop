<?php
$instagram_id = uniqid();

if(is_rtl()) $rtl = 'true';
 wp_enqueue_script('carousel');
 wp_enqueue_style('carousel');

?>
<div id = "<?php echo esc_attr($instagram_id);?>" class="noo-instagram owl-carousel">

    <?php
    if(!empty($data)){
        foreach ($data as $value) {
        ?>
            <div class="instagram-info col-xs-<?php echo 12/intval($images_display); ?>">
                <?php if( isset($value['link']) && !empty($value['link']) ) :?>
                    <a class="instagram-image-link" target="_blank" href="<?php echo esc_url( $value['link'] ); ?>">
                        <div class="instagram-image-around">
                            <img src="<?php echo esc_url( $value[$image_size] ); ?>" alt="<?php echo esc_html( $value['description'] ) ?>">
                            <a class="instagram-link" href="https://instagram.com/<?php echo esc_html($username); ?>"><?php echo esc_html_e('#', 'noo-hermosa'); ?><?php echo esc_html($username); ?></a>
                        </div>
                    </a>
                <?php endif; ?>
            </div>
        <?php
        }
    }?>
</div>
<script type="text/javascript">
            jQuery(document).ready(function($) {
                $( '#<?php echo $instagram_id; ?>' ).owlCarousel({
                    items: <?php echo  esc_attr($images_columns) ; ?>,
                    loop:true,
                    autoplayTimeout: 5000,
                    autoplay:<?php echo esc_attr($autoplay);  ?>,
                    nav: <?php echo esc_attr($show_navigation); ?>,
                    dots:<?php echo esc_attr($show_pagination); ?>,
                    dotsEach:<?php echo esc_attr($show_pagination); ?>,
                     responsive:{
                        0:{
                            items:1,
                        },
                        320:{
                            items:2,
                        },
                        480:{
                            items:2,
                        },
                        568:{
                            items:3,
                        },
                        768:{
                            items:3,
                        },
                        992:{
                            items:4,
                        },
                        1200:{
                            items:<?php echo esc_attr($images_columns); ?>,
                        }
                    }
                });
            });
        </script>

