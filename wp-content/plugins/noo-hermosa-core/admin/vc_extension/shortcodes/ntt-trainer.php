<?php
/**
 * Shortcode Noo Trainer
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/shortcodes/ntt-trainer.php.
 *
 * @author      NooTheme
 * @package     NooTimetable/Templates/Shortcodes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'shortcode_ntt_trainer' ) ) :
	
	function shortcode_ntt_trainer( $atts ) {

		extract( shortcode_atts( array(
            'style_title'       => '',
            'title'             => '',
            'sub_title'         => '',
            'layout_style'      => 'masonry',
            'autoplay'          => 'true',
            'columns'           => '4',
            'cat'               => 'all',
            'filter'            => '',
            'orderby'           => 'default',
            'limit'             => '4',
            'slider_speed'      => '800',
            'show_navigation'   => 'true',
            'show_pagination'   => 'false',
            'class'             => '',
        ), $atts ) );

        $categories = $cat;

        if( $layout_style == 'masonry'){
            $class_wrapper_masonry_or_slider = 'masonry';
        } else{
             $class_wrapper_masonry_or_slider = 'slider';
        }

        if ( $layout_style == 'masonry' ) {
            wp_enqueue_script('imagesloaded');
            wp_enqueue_script('isotope');   
        } else {
            wp_enqueue_style('noo-carousel');
            wp_enqueue_script('noo-carousel');
        }

        ob_start();

        $order = 'DESC';
        switch ( $orderby ) {
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
            'post_type'       => "noo_trainer",
            'posts_per_page'  => $limit,
        );

        if ('default' != $orderby) {
            $args['orderby'] = $orderby;
            $args['order']   = $order;
        }

        if(!empty($categories) && $categories != 'all'){
            $args['tax_query'][] =  array(

                'taxonomy' => 'class_category',
                'terms'    => explode(',', $categories),
                'field'    => 'id'
            );
        }

        $query = new WP_Query( $args );

        $id = uniqid();

        ?>
        <div class="noo-trainer-shortcode">

            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
            <!-- Section title -->
                <div class="<?php echo esc_attr( $style_title ); ?> noo-theme-wraptext" >
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

            $post_class = 'noo-xs-6 noo-sm-6 noo-md-' .absint((12 / $columns));

            if ( $query->have_posts() ) : ?>

                <div class="<?php echo esc_attr( $class_wrapper_masonry_or_slider); ?>">
                    <?php
                    // Masonry Grid

                    $category_arr = explode(',', $categories);

                    if ( $categories == 'all' ) {
                        $category_arr = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
                    }

                    if( count( $category_arr ) > 0 && $filter ):
                    ?>

                    <div class="masonry-header noo-filters trainer-filters">
                        <div class="masonry-filters">
                            <ul data-option-key="filter" >
                                <li>
                                    <a class="selected" href="#" data-option-value= "*"><?php echo esc_html__('All Category', 'noo-hermosa') ?></a>
                                </li>
                            <?php
                                foreach ($category_arr as $cat):
                                    if($cat == 'all')
                                        continue;
                                    $category = get_term($cat, 'class_category');
                                    if($category):
                                    ?>
                                    <li>
                                        <a href="#" data-option-value= ".<?php echo 'mansonry-filter-'.$category->slug?>"><?php echo esc_html($category->name); ?></a>
                                    </li>
                                    <?php endif;?>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <?php endif; ?>
                    
                    <div class="mansonry-content">
                        <div class="trainer-tag-wrap noo-row">
                            <div id="<?php echo esc_attr($id); ?>" data-masonry-gutter="0" data-masonry-column="'<?php echo esc_attr($columns); ?>'" class="<?php echo esc_attr($layout_style == 'slider' ? 'owl-carousel' : '');?> masonry-container columns-'<?php echo esc_attr($columns); ?>'">
                                <?php

                                while ( $query->have_posts() ) : $query->the_post(); ?>
                                <?php

                                $cat_class = array();
                                foreach ( (array) get_the_terms(get_the_ID(), 'class_category') as $cat ) {
                                    if ( empty($cat->slug ) )
                                        continue;
                                    $cat_class[] = 'mansonry-filter-' . sanitize_html_class($cat->slug, $cat->term_id);
                                }
                                $item_class = 'masonry-item '.implode(' ', $cat_class);

                                ?>
                                <div class="trainer-item <?php echo esc_attr($post_class); ?>  <?php echo esc_attr($item_class); ?>">
                                    <div class="trainer-bio">
                                        <?php if($layout_style =='masonry'): ?>
                                            <a class="trainer-avatar" href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('noo-thumbnail-trainer'); ?>
                                            </a>
                                        <?php else: ?>
                                            <a class="trainer-avatar" href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail( 'large' ); ?>
                                                <span class="first"></span>
                                                <span class="second"></span>
                                                <span class="third"></span>
                                            </a>
                                        <?php endif; ?>
                                        <div class="trainer-info">
                                            <h4>
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h4>
                                            <div class="trainer-category">
                                                <?php
                                                    echo get_the_term_list(get_the_ID(), 'class_category',' ',', ');
                                                ?>
                                            </div>
                                            <?php
                                                $facebook       =   noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_facebook", '' );
                                                $google         =   noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_google", '' );
                                                $twitter        =   noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_twitter", '' );
                                                $pinterest      =   noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_pinterest", '' );
                                            ?>
                                            <?php if(!empty($facebook) || !empty($twitter) || !empty($google) || !empty($linkedin) || !empty($pinterest)):?>
                                                <div class="trainer-social all-social-share">
                                                    <?php echo ( !empty($facebook) ? '<a href="' . $facebook . '" class="fa fa-facebook"></a>' : '' ); ?>
                                                    <?php echo ( !empty($google) ? '<a href="' . $google . '" class="fa fa-google-plus"></a>' : '' ); ?>
                                                    <?php echo ( !empty($twitter) ? '<a href="' . $twitter . '" class="fa fa-twitter"></a>' : '' ); ?>
                                                    <?php echo ( !empty($pinterest) ? '<a href="' . $pinterest . '" class="fa fa-pinterest"></a>' : '' ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if($layout_style =='masonry'): ?>
                                            <a class="btn view-profile" title="<?php printf( esc_html__( 'Post by %s','noo-hermosa'), get_the_title() ); ?>" href="<?php the_permalink(); ?>">
                                                <?php echo esc_html__('View Profile', 'noo-hermosa'); ?>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div> <!-- /.masonry-container -->
                        </div> <!-- /.trainer-tag-wrap -->
                    </div><!-- /.mansonry-content -->

                    <?php if ( $layout_style == 'slider' ) :  ?>

                        <script type="text/javascript">
                        jQuery(document).ready(function(){

                            jQuery('#<?php echo esc_attr($id); ?>').each(function(){
                                jQuery(this).owlCarousel({
                                    items: <?php echo esc_attr($columns); ?>,
                                    loop:true,
                                    margin:30,
                                    autoplayTimeout: <?php echo esc_attr($slider_speed); ?>,
                                    autoplay:<?php echo esc_attr($autoplay) ?>,
                                    nav:<?php echo esc_attr($show_navigation); ?> ,
                                    dots:<?php echo esc_attr($show_pagination); ?>,
                                    dotsEach:<?php echo esc_attr($show_pagination); ?>,
                                    rtl: false,
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
                        });
                        </script>

                <?php endif; ?>
                </div><!-- /.masonry -->
            <?php
            endif; ?>
        </div> <!-- /.noo-trainer-shortcode -->


        <?php $html = ob_get_contents();
        ob_end_clean();
        wp_reset_query();
        return $html;

	}

	add_shortcode( 'ntt_trainer', 'shortcode_ntt_trainer' );

endif;