<?php

do_action('noo_before_page');
$data_section_id = uniqid();
$portfolio_type = get_post_meta(get_the_ID(), 'noo_media_type', true);

$gallery_values = get_post_meta(get_the_ID(), 'noo_data_format_gallery', true);
$meta_values = explode(',', $gallery_values);
$social_profile=noo_hermosa_get_option('noo_portfolio_social');

?>
<div class="portfolio-full detail-03" id="content">
    <div class="noo-container">
        <div class="row">
            <div class="noo-md-8">
                <!-- VIDEO TYPE -->
                <?php if( $portfolio_type == 'video' ) : ?>
                    <?php 
                        $video = get_post_meta( get_the_ID(), 'noo_data_format_video', true );
                        $html ='';

                        if (count($video) > 0) {
                            $html .= '<div class="embed-responsive embed-responsive-16by9 embed-responsive-' . '' . '">';
                            // If URL: show oEmbed HTML
                            if (filter_var($video, FILTER_VALIDATE_URL)) {
                                $args = array(
                                    'wmode' => 'transparent'
                                );
                                $html .= wp_oembed_get($video, $args);
                            } // If embed code: just display
                            else {
                                $html .= $video;
                            }
                            $html .= '</div>';
                        }
                        echo($html);
                    ?>
                <?php endif; ?>

                <!-- IMAGE TYPE -->
                <?php if( $portfolio_type == 'image' ) : ?>
                    <?php 
                        the_post_thumbnail( get_the_ID(), 'full' );
                    ?>
                <?php endif; ?>

                <!-- GALLERY TYPE -->
                <?php if( $portfolio_type == 'gallery' ) : ?>
                <div class="post-slideshow owl-carousel" id="post_slideshow_<?php echo esc_attr($data_section_id); ?>">
                    <?php if( count($meta_values) > 0 ) :
                        $index = 0;
                        foreach($meta_values as $image) :
                            $urls = wp_get_attachment_image_src($image,'full');
                            $img  = '';
                            if(count($urls)>0) {
                                $resize = matthewruddy_image_resize($urls[0],1080,768);
                                if( $resize != null && is_array($resize) )
                                    $img = $resize['url'];
                            }

                    ?>
                    <div class="item">
                        <a class="nav-post-slideshow" href="javascript:;" data-section-id="<?php echo esc_attr($data_section_id) ?>" data-index="<?php echo esc_attr($index++) ?>">
                            <img alt="portfolio" src="<?php echo esc_url($img) ?>" width = "<?php echo esc_attr($resize['width'])?>" height = "<?php echo esc_attr($resize['height'])?>"/>
                        </a>
                    </div>
                        <?php endforeach; ?>
                    <?php else : if( count($imgThumbs) > 0 ) : ?>
                        <div class="item"><img alt="portfolio" src="<?php echo esc_url($imgThumbs[0]); ?>" width = "<?php echo esc_attr($imgThumbs[1])?>" width = "<?php echo esc_attr($imgThumbs[2])?>"/></div>
                    <?php       endif;
                    endif;
                    ?>
                </div>
                <?php endif; ?>

                <?php if( $portfolio_type == 'gallery' ) : ?>
                <div class="paging-wrap">
                                <div class="slideshow-paging owl-carousel" data-current-index="0" data-total-items="<?php echo esc_attr(count($meta_values)) ?>" id="slideshow_paging_<?php echo esc_attr($data_section_id) ?>">
                                    <?php if (count($meta_values) > 0) {
                                        $index = 0;
                                        foreach ($meta_values as $image) {
                                            $urls = wp_get_attachment_image_src($image, 'full');
                                            $img = '';
                                            if (count($urls) > 0) {
                                                $resize = matthewruddy_image_resize($urls[0], 180, 130);
                                                if ($resize != null && is_array($resize))
                                                    $img = $resize['url'];
                                            }
                                            ?>
                                            <div class="item">
                                                <a href="javascript:;" class="nav-slideshow"
                                                   data-section-id="<?php echo esc_attr($data_section_id) ?>"
                                                   data-index="<?php echo esc_attr($index++) ?>">
                                                    <img alt="portfolio" src="<?php echo esc_url($img) ?>"
                                                         width="<?php echo esc_attr($resize['width']) ?>"
                                                         height="<?php echo esc_attr($resize['height']) ?>"/>
                                                </a>
                                            </div>
                                        <?php }
                                    }else { if(count($imgThumbs)>0) {?>
                                        <div class="item">
                                            <img alt="portfolio" src="<?php echo esc_url($imgThumbs[0])?>" />
                                        </div>
                                    <?php }
                                    }
                                    ?>

                    </div>
                </div>
                <?php endif; ?>

            </div>

            <div class="noo-md-4">
                <div class="portfolio-info portfolio-content">
                    <h2 class="portfolio-title p-font"><?php the_title() ?></h2>
                    <?php the_content(); ?>
                </div>
                <div class="portfolio-info">
                    <?php
                    $meta = get_post_meta(get_the_ID(), 'portfolio_custom_fields', TRUE);
                    if(isset($meta) && is_array($meta) && count($meta['portfolio_custom_fields'])>0){
                        for($i=0; $i<count($meta['portfolio_custom_fields']);$i++){
                            ?>
                            <div class="portfolio-info-box">
                                <h6 class="p-color p-font"><?php echo wp_kses_post($meta['portfolio_custom_fields'][$i]['custom-field-title']) ?>: </h6>
                                <div class="portfolio-term-custom"><?php echo wp_kses_post($meta['portfolio_custom_fields'][$i]['custom-field-description']) ?></div>
                            </div>
                        <?php }
                    }
                    ?>

                    <div class="portfolio-info-box">
                        <h6 class="p-color p-font"><?php echo esc_html__( 'Category','noo-hermosa-core' ); ?> </h6>
                        <div class="portfolio-term-cat"><?php echo wp_kses_post($cat); ?></div>
                    </div>
                    <div class="portfolio-info-box">
                        <h6 class="p-color p-font"><?php echo esc_html__( 'Date','noo-hermosa-core' ); ?> </h6>
                        <div class="portfolio-term-date"><?php echo date(get_option('date_format'),strtotime($post->post_date)) ?></div>
                    </div>
                    <div class="portfolio-info-box">
                        <h6 class="p-color p-font"><?php echo esc_html__( 'Tags','noo-hermosa-core' ); ?> </h6>
                        <div class="portfolio-term-tag"><?php echo wp_kses_post($tag); ?></div>
                    </div>
                    <?php if($social_profile ): ?>
                    <div class="portfolio-info-box">
                        <h6 class="p-color p-font"><?php echo esc_html__( 'Follow Us','noo-hermosa-core' ); ?> </h6>
                        <?php
                        if($social_profile )
                            include_once(plugin_dir_path( __FILE__ ).'social.php');
                        ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    (function($) {

        "use strict";
        $(document).ready(function(){

            var sync1    = $(".post-slideshow","#content");
            var sync2    = $(".slideshow-paging","#content");
            var flag     = false;
            var duration = 500;


            sync1
                .owlCarousel({
                    items: 1,
                    loop:true,
                    margin: 0,
                    <?php if(is_rtl()) : ?> rtl : true, <?php endif;?>
                    pagination:false,
                    nav: true,
                    navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                    dots: true,
                    autoPlay:5000,
                    responsiveClass: true,
                })
                .on('changed.owl.carousel', function (e) {
                    if (!flag) {
                        flag = true;
                        sync2.trigger('to.owl.carousel', [e.item.index, duration, true]);
                        flag = false;
                    }

                    // Add class synced to current slide
                    var current = e.item.index;
                    $(".slideshow-paging")
                        .find(".owl-item")
                        .removeClass("synced")
                        .eq(current)
                        .addClass("synced");
                });

            sync2
                .owlCarousel({
                    margin: 0,
                    <?php if(is_rtl()) : ?> rtl : true, <?php endif;?>
                    items: 6,
                    loop:true,
                    pagination:false,
                    nav: true,
                    navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
                    center: false,
                    dots: true,
                    responsiveClass: true,
                    responsive: {
                        0: {
                            items: 3
                        },
                        500: {
                            items: 3
                        },
                        991: {
                            items: 3
                        },
                        1200: {
                            items: 5
                        },
                        1300: {
                            items: 6
                        }
                    },
                    onInitialized : function(){
                        sync2.find(".owl-item").eq(0).addClass("synced");
                    }
                })
                .on('click', '.owl-item', function () {
                    sync1.trigger('to.owl.carousel', [$(this).index(), duration, true]);
                })
                .on('changed.owl.carousel', function (e) {
                    if (!flag) {
                        flag = true;        
                        sync1.trigger('to.owl.carousel', [e.item.index, duration, true]);
                        flag = false;
                    }
                });

        })

    })(jQuery);
</script>