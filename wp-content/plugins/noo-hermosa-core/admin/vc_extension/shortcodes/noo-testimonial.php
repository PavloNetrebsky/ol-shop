<?php
/**
 * Shortcode Visual: Noo Testimonial
 * Function show post in blog
 * 
 * @package     Noo Library
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

/* -------------------------------------------------------
 * Create functions noo_shortcode_testimonial
 * Function show all comment
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_shortcode_testimonial' ) ) :
	
	function noo_shortcode_testimonial( $atts ) {
        $atts  = vc_map_get_attributes( 'noo_testimonial', $atts );
		extract( shortcode_atts( array(
            'style'          => 'style-1',
            'title'          => '',
            'image'          => '',
            'posts_per_page' => 10,
            'autoplay'       => 'false',
            'pagination'     => 'false',
            'slider_speed'   => '800',
            'navigation'     => 'true',
        ), $atts ) );

        ob_start();
        $id = uniqid();

        /**
         * Required library
         */
        wp_enqueue_style( 'carousel' );
        wp_enqueue_script( 'carousel' );

        /**
         * Create query
         * @var array
         */
        $args = array(
            'post_type'      => 'testimonial',
            'posts_per_page' => $posts_per_page,
        );

        /**
         * VAR
         */
        // $pagination = ( $style === 'style-2' ? 'true' : 'false' );
        // $navigation = ( $style === 'style-2' ? 'false' : 'true' );

        /**
         * new query
         * @var WP_Query
         */
        $testimonial_query = new WP_Query( $args );

        /**
         * Check and loop
         */
        if ( $testimonial_query->have_posts() ) :
            $prefix = '_noo_wp_testimonial'; ?>

            <div class="noo-testimonial  <?php echo esc_attr( $style ); ?>">
                <?php if( isset($title) && !empty($title)) :?>
                    <h3 class="title-testimonial"><?php echo esc_attr( $title ); ?></h3>
                <?php endif; ?>
            <div class='noo-row'>
            <?php
            /**
             * Process data
             */
            if ( $style === 'style-1' ) :?>
                
                <div class="noo-testimonial-image">
                <?php echo wp_get_attachment_image( esc_attr($image), 'full' ); ?>
                </div>         

            <?php endif; ?>

           <div id="<?php echo $id; ?>" class="noo-testimonial-wrap owl-carousel" >
                
            <?php while ( $testimonial_query->have_posts() ) : $testimonial_query->the_post();
                /**
                 * VAR
                 */
                $testimonial_id = get_the_id();
                $content        = get_the_content();
                $image          = (int)noo_hermosa_get_post_meta( $testimonial_id, "{$prefix}_image" );
                $name           = noo_hermosa_get_post_meta( $testimonial_id, "{$prefix}_name" );
                $position       = noo_hermosa_get_post_meta( $testimonial_id, "{$prefix}_position" ); ?>

               
                <div class='noo-testimonial-item'>
                    
                    <p class="noo-testimonial-content">
                        <?php echo esc_html( $content ); ?>
                        <span class="icon-wrap">
                            <span class="icon-item-1"></span>
                            <span class="icon-item-2"></span>
                            <span class="icon-item-3"></span>
                        </span>
                    </p>

                    <div class="box-user">
                        <span class="box-avatar">
                            <?php echo wp_get_attachment_image( $image, 'thumbnail' ); ?>
                        </span>
                        <div class="box-info">
                            <h5 class="noo-testimonial-name">
                                <?php echo esc_html( $name ); ?>
                            </h5>

                            <span class="noo-testimonial-position">
                                <?php echo esc_html( $position ); ?>
                            </span>
                        </div>
                    </div>

                </div><!-- /.noo-testimonial-item -->

            <?php  endwhile; ?>
            </div><!-- /.noo-testimonial-wrap -->

            </div><!-- /.noo-row -->
            </div><!-- /.noo-testimonial -->
        <?php
            wp_reset_postdata();

            ?> <script>
                jQuery(document).ready(function($){
                    $('#<?php echo $id; ?>').owlCarousel({
                        items:1,
                        loop:true,
                        margin:10,
                        autoHeight: true,
                        autoplayTimeout: <?php echo esc_attr($slider_speed) ?>,
                        autoplay:<?php echo esc_attr($autoplay)?>,
                        nav: <?php echo esc_attr($navigation) ?>,
                        dots:<?php echo esc_attr($pagination) ?>,
                        dotsEach:<?php echo esc_attr($pagination) ?>
                    });
                });
            </script>
             <?php

        endif;

        $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_testimonial', 'noo_shortcode_testimonial' );

endif;

/** ====== END noo_shortcode_testimonial ====== **/