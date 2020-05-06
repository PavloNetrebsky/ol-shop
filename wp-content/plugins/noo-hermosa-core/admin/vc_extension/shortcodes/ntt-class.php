<?php
/**
 * Shortcode Noo Class
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/shortcodes/ntt-class.php.
 *
 * @author      NooTheme
 * @package     NooTimetable/Templates/Shortcodes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'shortcode_ntt_class' ) ) :
	
	function shortcode_ntt_class( $atts ) {
       //$atts  = vc_map_get_attributes( 'ntt_class', $atts );
		extract( shortcode_atts( array(
            'title'             => '',
            'sub_title'         => '',
            'color'             => '#696969',
            'layout_style'      => 'grid',
            'sliders_style'     => 'style1',
            'autoplay'          => 'true',
            'slider_speed'      => '800',
            'show_navigation'   => 'false',
            'show_pagination'   => 'false',
            'show_info'         => 'all',
            'columns'           => '4',
            'cat'               => 'all',
            'orderby'           => 'default',
            'limit'             => '4',
            'class'             => '',
        ), $atts ) );
        $class_cat = $cat;
        // 
        // Style Control Class
        if ( $layout_style == 'grid' ) {
            $class_wrapper_masonry_or_slider   = 'masonry grid';
            $class_shortcode = 'noo-class-grid-shortcode';
        } else {
            $class_wrapper_masonry_or_slider   = 'grid slider';
            $class_shortcode = 'noo-class-slider-shortcode';

            //$columns = '3';
            
        }
            $class_slider_style = $sliders_style;
            
        //
        // Enqueue
        //
        if ( $layout_style == 'grid' ) {
            wp_enqueue_script('imagesloaded');
            wp_enqueue_script('isotope');   
        } else {
            wp_enqueue_style('noo-carousel');
            wp_enqueue_script('noo-carousel');
        }
        
        

        ob_start();



        $orderkey = '';
        $order = 'DESC';
        switch ( $orderby ) {
            case 'open_date':
                $orderby  = 'meta_value_num';
                $order    = 'ASC';
                $orderkey = '_open_date';
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

        $args = array(
            'post_type'           => 'noo_class',
            'posts_per_page'      => $limit,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        );

        if ('default' != $orderby) {
            $args['orderby'] = $orderby;
            $args['order']   = $order;
        }

        if ('meta_value_num' == $orderby) {
            $args['meta_key'] = $orderkey;
        }

        if ( !empty( $class_cat ) && $class_cat != 'all' ) {
            $args['tax_query'][]  = array(
                'taxonomy' =>  'class_category',
                'field'    =>  'id',
                'terms'    => explode(',', $class_cat),
            );
        }
        
        $query = new WP_Query( $args );

        
        ?>
        <div class="<?php echo esc_attr( $class_shortcode ); ?> <?php echo esc_attr( $class_slider_style ); ?>" >
            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
            <!-- Section title -->
                <div class="noo-theme-wraptext ">
                    <div class="wrap-title">

                    <?php if ( !empty( $title ) ) : ?>
                        <div class="noo-theme-title-bg"></div>

                        <h3 class="noo-theme-title">
                            <?php
                                $title = explode( ' ', $title );
                                $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
                                $title = implode( ' ', $title );
                            ?>
                            <?php echo wp_kses($title,noo_hermosa_allowed_html()); ?>
                        </h3>
                    <?php endif; ?>

                    <?php if ( !empty( $sub_title ) ) : ?>
                        <p class="noo-theme-sub-title">
                            <?php echo esc_html( $sub_title ); ?>
                        </p>
                    <?php endif; ?>

                    </div> <!-- /.wrap-title -->    
                </div>
            <?php endif; ?>

            <!-- Section content -->

            <?php

            if ( $query->have_posts() ) :

                $show_meta_address = noo_hermosa_get_option('noo_classes_meta_address', false);

                // show_info control
                switch ($show_info) {
                    case 'null':
                        $show_date = false;
                        $show_time = false;
                        break;
                    case 'date':
                        $show_date = true;
                        $show_time = false;
                        break;
                    case 'time':
                        $show_date = false;
                        $show_time = true;
                        break;
                    default:
                        $show_date = true;
                        $show_time = true;
                        break;
                }

                //
                // Class item masonry or slider
                //
                $post_class = '';
                if ( $layout_style == 'grid' ) {
                    $post_class = 'loadmore-item masonry-item noo-sm-6 noo-md-6 noo-lg-'.absint((12 / $columns));    
                }
            ?>
                <div class="<?php echo esc_attr( $class_wrapper_masonry_or_slider ); ?> ">
                    <div class="posts-loop-content noo-row">

                        <div class="masonry-container <?php echo esc_attr($layout_style == 'slider' ? 'owl-carousel' : ''); ?>">
                            <?php while ( $query->have_posts() ) : $query->the_post(); global $post; ?>
                            <article <?php post_class($post_class); ?>>
                                <div class="loop-item-wrap">
                                    <?php if(has_post_thumbnail()):?>
                                    <?php
                                        $feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
                                    ?>
                                    <a class="loop-item-featured" href="<?php the_permalink(); ?>" aria-hidden="true" style="background-image:url('<?php echo esc_url($feat_image_url[0]); ?>');">
                                        <?php the_post_thumbnail( 'noo-thumbnail-class', array( 'alt' => the_title_attribute( 'echo=0' ), 'class' => 'sr-only' ) ); ?>
                                    </a>
                                    <?php endif;?>
                                    <div class="loop-item-content">
                                        <div class="loop-item-content-summary">
                                            <?php if($sliders_style == 'style1'): ?>
                                                <div class="loop-item-category"><?php echo get_the_term_list(get_the_ID(), 'class_category',' ',', ')?></div>
                                            <?php endif; ?>
                                            <h2 class="loop-item-title">
                                                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( wp_kses( __( 'Permanent link to: "%s"','noo-hermosa' ), noo_hermosa_allowed_html() ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
                                            </h2>
                                            <div class="content-meta">
                                                
                                                <?php
                                                    $open_date  = noo_hermosa_get_post_meta(get_the_ID(),'_open_date');
                                                    $open_time  = noo_hermosa_get_post_meta( get_the_ID(), "_open_time", '' );
                                                    $close_time = noo_hermosa_get_post_meta( get_the_ID(), "_close_time", '' );
                                                ?>
                                                <?php
                                                    $number_days        = (array) noo_hermosa_json_decode( noo_hermosa_get_post_meta( get_the_ID(), "_number_day", '' ) );

                                                    $class_dates = Noo__Timetable__Class::get_open_date_display(array(
                                                        'open_date'       => $open_date,
                                                        'number_of_weeks' => noo_timetable_get_post_meta( get_the_ID(), "_number_of_weeks", '1'),
                                                        'number_days'     => $number_days
                                                    ));

                                                    $address = noo_hermosa_get_post_meta(get_the_ID(), '_address');
                                                ?>
                                                <?php if( $show_date && !empty( $class_dates['open_date']) ) : ?>
                                                    <span class="meta-date">
                                                        <time datetime="<?php echo mysql2date( 'c',$open_date) ?>">
                                                            <i class="icon ion-calendar"></i>
                                                            <?php echo esc_html__('Open:', 'noo-hermosa'); ?> <?php echo esc_html(date_i18n(get_option('date_format'), $open_date)); ?>
                                                        </time>
                                                    </span>
                                                <?php endif;?>
                                                <?php if( $show_date && !empty( $class_dates['next_date'] ) ) : ?>
                                                    <span class="meta-date">
                                                        <time datetime="<?php echo mysql2date( 'c', $class_dates['next_date']) ?>">
                                                            <i class="icon ion-calendar"></i>
                                                            <?php echo esc_html__('Next:', 'noo-hermosa'); ?> <?php echo esc_html(date_i18n(get_option('date_format'),$class_dates['next_date'])); ?>
                                                        </time>
                                                    </span>
                                                <?php endif;?>
                                                <?php if( $show_time && !empty( $open_time ) && !empty( $close_time ) && is_numeric( $open_time ) && is_numeric( $close_time ) ) : ?>
                                                    <span class="meta-time">
                                                        <i class="icon ion-android-alarm-clock"></i>&nbsp;<?php echo date_i18n(get_option('time_format'),$open_time).' - '. date_i18n(get_option('time_format'),$close_time); ?>
                                                    </span>
                                                <?php endif;?>
                                                <?php if( $show_meta_address && !empty( $address ) && $sliders_style == 'style1' ):?>
                                                <span class="meta-address">
                                                    <i class="fa fa-map-marker"></i>
                                                     <?php echo esc_html($address); ?>
                                                </span>
                                                <?php endif;?>
                                            </div>
                                            <div class="loop-item-excerpt">
                                                <?php
                                                $excerpt = $post->post_excerpt;
                                                if(empty($excerpt))
                                                    $excerpt = $post->post_content;
                                                
                                                $excerpt = strip_shortcodes($excerpt);
                                                echo '<p>' . wp_trim_words($excerpt, 18, '...') . '</p>';
                                                ?>
                                            </div>
                                        </div>
                                        <?php if($sliders_style == 'style1') :?>
                                        <div class="loop-item-action">
                                            <a class="btn" href="<?php the_permalink()?>"><?php echo esc_html__('Learn More','noo-hermosa')?></a>
                                        </div>
                                        <?php endif; ?>
                                        <?php
                                            $trainer_ids = noo_hermosa_get_post_meta(get_the_ID(),'_trainer');
                                            $class_tr = '';

                                            if( true && !empty( $trainer_ids ) ):
                                            foreach ($trainer_ids as $k => $trid) :
                                                if ( $layout_style == 'slider' &&  $k==1 ) break;
                                                // limit 2 trainer to show
                                                if ( $k==2 ) break;

                                                if (count($trainer_ids) > 1) {
                                                    if ( $k == 0 ) {
                                                        $class_tr = 'first';
                                                    } else {
                                                        $class_tr = 'second';
                                                    }
                                                } else {
                                                    $class_tr = '';
                                                }
                                                $trainer_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($trid), array(90, 90) ); ?>
                                                <div class="loop-item-trainer <?php echo esc_attr( $class_tr ); ?>" style="background-image:url('<?php echo esc_url($trainer_image_url[0]); ?>');"></div>

                                                <?php if ( ($layout_style == 'slider') && ($sliders_style == 'style1') ) :  ?>
                                                    <div class="loop-item-trainer-name"><?php echo get_the_title( $trid ); ?></div>
                                                <?php endif; ?>

                                            <?php
                                            endforeach;
                                        endif;?>
                                    </div>
                                </div>
                            </article>
                            <?php endwhile;?>
                        </div>

                    </div> <!-- /.posts-loop-content -->
                </div>
                
                <?php if ( $layout_style == 'slider' ) :  ?>
                    <?php 
                     ?>
                    <script type="text/javascript">
                    jQuery(document).ready(function(){
                        jQuery('.noo-class-slider-shortcode .masonry-container').owlCarousel({
                                items: <?php echo esc_attr($columns); ?>,
                                loop:true,
                                margin:30,
                                autoplayTimeout: <?php echo esc_attr($slider_speed); ?>,
                                autoplay:<?php echo esc_attr($autoplay); ?>,
                                nav: <?php echo esc_attr($show_navigation); ?>,
                                dots: <?php echo esc_attr($show_pagination); ?>,
                                dotsEach:<?php echo esc_attr($show_pagination); ?>,
                                rtl: true,
                                responsive:{
                                    0:{
                                        items:1,
                                    },
                                    320:{
                                        items:1,
                                    },
                                    480:{
                                        items:1,
                                    },
                                    568:{
                                        items:1,
                                    },
                                    768:{
                                        items:2,
                                    },
                                    992:{
                                        items:3,
                                    },
                                    1200:{
                                        items:<?php echo esc_attr($columns); ?>,
                                    }
                                }
                            });
                        });
                    </script>

                <?php endif; ?>
            <?php
            endif; ?>
        </div> <!-- /.noo-trainer-shortcode -->


        <?php $html = ob_get_contents();
        ob_end_clean();
        wp_reset_query();
        return $html;

	}

	add_shortcode( 'ntt_class', 'shortcode_ntt_class' );

endif;