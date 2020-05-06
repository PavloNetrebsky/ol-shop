<?php
/**
 * Create shortcode: [noo_event_slider]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'shortcode_noo_event_slider' ) ) :
	
	function shortcode_noo_event_slider( $atts ) {

        if ( ! class_exists('Noo__Timetable__Event') ) return;

		extract( shortcode_atts( array(
            'title'          => '',
            'sub_title'      => '',
            'event_cat'      => '',
            'event_location' => '',
            'button_link'    => '',
            'orderby'        => 'latest',
            'posts_per_page' => 10,
            'hide_past_event'      => '',
        ), $atts ) );

        ob_start();

        /**
         * Enqueue library
         */
        wp_enqueue_style( 'swiper' );
		wp_enqueue_script( 'swiper' );

        /**
         * Check data order
         * @var string
         */
        $order = 'DESC';
        switch ( $orderby ) {
            case 'next_date':
                $orderby  = 'meta_value_num';
                $order    = 'ASC';
                $orderkey = '_next_date';
                break;
            case 'start_date':
                $orderby  = 'meta_value_num';
                $order    = 'ASC';
                $orderkey = '_noo_event_start_date';
                break;
            case 'latest':
                $orderby = 'date';
                break;
            case 'oldest':
                $orderby = 'date';
                $order = 'ASC';
                break;
            case 'alphabet':
                $orderby = 'title';
                $order = 'ASC';
                break;
            case 'ralphabet':
                $orderby = 'title';
                break;
            default:
                $orderby = 'default';
                break;
        }

        /**
         * Check paged
         */
        if( is_front_page() || is_home()) :
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
        else :
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        endif;

        /**
         * Create query
         * @var array
         */
        $args = array(
			'post_type'      => 'noo_event',
			'orderby'        => $orderby,
			'order'          => $order,
			'paged'          => $paged,
			'posts_per_page' => $posts_per_page,
        );


        if ('default' != $orderby) {
            $args['orderby'] = $orderby;
            $args['order']   = $order;
        }

        if ('meta_value_num' == $orderby) {
            $args['meta_key'] = $orderkey;
        }

        if ( !empty( $event_cat ) && $event_cat != 'all' ) {
            $args['tax_query'][]  = array(
                'taxonomy' =>  'event_category',
                'field'    =>  'id',
                'terms'    => explode(',', $event_cat),
            );
        }

        if ( $hide_past_event ) {
            $args['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key'     => '_noo_event_end_date',
                    'value'   => strtotime(date('Y-m-d')),
                    'compare' => '>='
                ),
                // array(
                //     'key'     => '_noo_event_end_time',
                //     'value'   => strtotime(date('H:i:s')),
                //     'compare' => '>='
                // ),
            );
        }
        /**
         * Get list category
         */
        
        ?>

		<div class="sc-noo-event-slider-wrap">
			
			<div class="noo-theme-wraptext">
                <div class="wrap-title">

                <?php if ( !empty( $title ) ) : ?>
                    <div class="noo-theme-title-bg"></div>

                    <h3 class="noo-theme-title">
                        <?php
                            $title = explode( ' ', $title );
                            $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
                            $title = implode( ' ', $title );
                        ?>
                        <?php echo $title; ?>
                    </h3>
                <?php endif; ?>

                <?php if ( !empty( $sub_title ) ) : ?>
                    <p class="noo-theme-sub-title">
                        <?php echo esc_html( $sub_title ); ?>
                    </p>
                <?php endif; ?>

                </div> <!-- /.wrap-title -->    
            </div>
			
            <div class="swiper-container noo-event-slider-wrap-item">
    			<div class="swiper-wrapper">
    				<?php
    	                $query = new WP_Query( $args );
    	                if( $query->have_posts() ):
    	                    while( $query->have_posts() ): $query->the_post();
                                $image_url      = wp_get_attachment_url( get_post_thumbnail_id() );
    	                        ?>
                                <div class="swiper-slide noo-event-slider-item">
                                    <div class="wrap">
                                        <div class="item-thumb" style="background-image:url('<?php echo esc_url( $image_url ); ?>');"></div>
                                        <div class="item-body">
                                            <h3 class="noo-title">
                                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>
                                            <?php Noo__Timetable__Event::show_meta(); ?>
                                            <a class="learn-more" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                                <?php echo esc_html__( 'Learn More', 'noo-hermosa-core' ); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div><?php
    	                    endwhile;
    	                endif; 
                        wp_reset_postdata();
    				?>
                </div>
                <div class="swiper-paging">
                    <span class="swiper-button-prev"></span>
                    <span class="swiper-button-next"></span>
                </div>
			</div>

            <script>        
              
                jQuery(document).ready(function($) {
                    
                    var swiper = new Swiper('.swiper-container', {
                        effect: 'coverflow',
                        grabCursor: true,
                        centeredSlides: true,
                        paginationClickable: false,
                        nextButton: '.swiper-button-next',
                        prevButton: '.swiper-button-prev',
                        slidesPerView: 5,
                        spaceBetween: 30,
                        speed: 800,
                        loop: true,
                        autoplay: false,
                        autoplayDisableOnInteraction: true,
                        coverflow: {
                            rotate: 50,
                            stretch: 0,
                            depth: 100,
                            modifier: 1,
                            slideShadows : true
                        },
                        // Responsive breakpoints
                        breakpoints: {
                            // when window width is <= 1024px
                            1024: {
                              slidesPerView: 1,
                              spaceBetweenSlides: 10
                            }
                        }
                    });
                    // swiper.slideTo(-1, 800, true);
                    // $('.swiper-button-prev').one('click', function(event) {
                    //     event.preventDefault();
                    //     swiper.slideTo(1, 800, true);
                    // });

                    // $('.swiper-button-next').one('click', function(event) {
                    //     event.preventDefault();
                    //     swiper.slideTo(1, 800, true);
                    // });
                });
                
            </script>

            <?php if ( !empty( $button_link ) ) : ?>

                <?php
                    $info_btn = vc_build_link( $button_link );
                    $targer   = !(empty( $info_btn['target'] )) ? " targer='{$info_btn['target']}'" : '';
                    echo "<a class='noo-event-slider-button' href='{$info_btn['url']}' {$targer}>{$info_btn['title']}</a>";
                ?>

            <?php endif; ?>

		</div><!-- /.noo-event-slider-wrap -->

		<?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_event_slider', 'shortcode_noo_event_slider' );

endif;