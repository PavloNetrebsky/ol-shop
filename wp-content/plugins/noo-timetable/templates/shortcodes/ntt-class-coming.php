<?php
/**
 * Shortcode Noo Class Coming
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/shortcodes/ntt-class-coming.php.
 *
 * @author      NooTheme
 * @package     NooTimetable/Templates/Shortcodes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'shortcode_ntt_class_coming' ) ) :
    
    function shortcode_ntt_class_coming( $atts ) {

        extract( shortcode_atts( array(
            'title'             => '',
            'sub_title'         => '',
            'layout_style'      => 'grid',
            'filter_position'   => '',
            'autoplay'          => 'true',
            'show_info'         => 'all',
            'going_on'          => false,
            'columns'           => '4',
            'cat'               => 'all',
            'limit'             => '4',
            'filter_by_level'   => '',
            'filter_by_cat'     => '',
            'filter_by_trainer' => '',
            'filter_by_day'     => '',
            'class'             => '',
            'pagination'        => 'disable',
        ), $atts ) );

        ob_start();

        // 
        // Style Control Class
        // 
        $class = ($class != '') ? $class . ' ' : '';
        $class_layout = ($layout_style == 'grid') ? ' grid' : ' list';
        $filter_position = !empty($filter_position) ? ' '.$filter_position : '';
        $class_shortcode = $class . 'noo-class-shortcode' . $class_layout . $filter_position;

        if ( $layout_style == 'slider' ) {
            $class_shortcode .= ' noo-data-slider';
        }

        //
        // Enqueue
        //
        if ( $layout_style == 'slider' ) {
            wp_enqueue_style('carousel');
            wp_enqueue_script('carousel');
        }else{
            wp_enqueue_script( 'isotope' );
            wp_enqueue_script( 'imagesloaded' );
            wp_enqueue_script('noo-class');
        }

        /**
         * Check paged
         */
        if (is_front_page() || is_home()) :
            $paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
        else :
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        endif;

        $comming_class_ids = Noo__Timetable__Class::get_coming_class_ids();
        if( $going_on )
            $comming_class_ids = Noo__Timetable__Class::get_coming_class_ids(true);

        $args = array(
            'post_type'           => 'noo_class',
            'posts_per_page'      => $limit,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'meta_key'            => '_next_date',
            'orderby'             => 'meta_value_num',
            'order'               => 'ASC',
            'post__in'            => $comming_class_ids
        );

        if ( $pagination != 'disable' ) {
            $args['paged'] = $paged;
        }

        if ( !empty( $cat ) && $cat != 'all' ) {
            $args['tax_query'][]  = array(
                'taxonomy' =>  'class_category',
                'field'    =>  'id',
                'terms'    => explode(',', $cat),
            );
        }
        
        $args = apply_filters( 'noo_timetable_ntt_class_coming_query_args', $args );

        $query = new WP_Query( $args );

        
        if ( count($comming_class_ids) > 0 && $query->have_posts() ) :
        ?>
        <div class="<?php echo esc_attr( $class_shortcode ); ?>">

            <?php
                global $title_var;
                $title_var = compact('title', 'sub_title');
                noo_timetable_get_template( 'shortcodes/ntt-title.php' );
            ?>

            <!-- Section content -->

            <?php

            if ( $query->have_posts() ) :

                global $post_options;

                $post_class = '';

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
                // Class item masonry
                //
                if ( $layout_style == 'list' ) {
                    $columns = 1;
                }
                

                if ( $layout_style != 'slider' ) {
                    $post_class = 'loadmore-item masonry-item noo-sm-6 noo-md-'.absint((12 / $columns));
                }

                $post_options = compact('post_class', 'show_date', 'show_time');?>
                <div class="noo-class-wrap">
                    <?php if($layout_style != 'slider'):  
                        global $wp_locale;
                        $levels = get_terms('class_level');
                        $categories = get_terms('class_category');
                        $cat2 = explode(',', $cat);
                        $trainers = get_posts(array('post_type'=>'noo_trainer','posts_per_page'=>-1,'suppress_filters'=>0));?>
                        <?php if($filter_by_level || $filter_by_cat || $filter_by_trainer):?>
                            <div class="noo-class-filter">
                                <div class="filter-wrap">
                                    <h2 class="widget-title"><?php echo esc_html_e('Class Filter', 'noo-timetable')?></h2>
                                
                                    <?php if($filter_by_level && !empty($levels)):?>
                                         <div class="widget-class-filter search-class-level" data-group="level">
                                            <select class="widget-class-filter-control">
                                                <option value=""><?php esc_html_e('Select Level','noo-timetable')?></option>
                                                <?php foreach ((array)$levels as $level):?>
                                                    <option value="filter-level-<?php echo esc_attr($level->term_id)?>"><?php echo esc_html($level->name)?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    <?php endif;?>
                                    <?php if($filter_by_cat && !empty($categories)):?>
                                        <div class="widget-class-filter search-class-category" data-group="category">
                                            <select class="widget-class-filter-control">
                                                <option value=""><?php esc_html_e('Select Category','noo-timetable')?></option>
                                                <?php foreach ((array)$categories as $category):?>
                                                    <?php if('all' == $cat):?>
                                                        <option value="filter-cat-<?php echo esc_attr($category->term_id)?>"><?php echo esc_html($category->name)?></option>
                                                    <?php elseif(in_array($category->term_id, $cat2)): ?>
                                                        <option value="filter-cat-<?php echo esc_attr($category->term_id)?>"><?php echo esc_html($category->name)?></option>
                                                    <?php endif;?>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    <?php endif;?>
                                    <?php $current_trainer = isset( $_GET['trainer'] ) && !empty( $_GET['trainer'] ) ? $_GET['trainer'] : '';
                                    if($filter_by_trainer && !empty($trainers)):
                                    ?>
                                        <div class="widget-class-filter search-class-trainer" data-group="trainer">
                                            <select class="widget-class-filter-control">
                                                <option value=""><?php esc_html_e('Select Trainer','noo-timetable')?></option>
                                                <?php foreach ((array)$trainers as $trainer):?>
                                                    <option <?php selected( $current_trainer, $trainer->ID ); ?> value="filter-trainer-<?php echo esc_attr($trainer->ID)?>"><?php echo esc_html($trainer->post_title)?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                    <?php endif;?>
                                    <?php if($filter_by_day):?>
                                        <div class="widget-class-filter search-class-weekday" data-group="day">
                                            <h4><?php _e('Filter class by days:','noo-timetable')?></h4>
                                            <?php for ($day_index = 0; $day_index <= 6; $day_index++) : ?>
                                            <label class="noo-xs-6">
                                                <input type="checkbox" class="widget-class-filter-control" value="filter-day-<?php echo esc_attr($day_index)?>"> <?php echo esc_html($wp_locale->get_weekday($day_index)) ?>
                                            </label>
                                            <?php
                                            endfor;
                                            ?>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                        <?php endif;?>
                    <?php endif;?>
                    <div class="posts-loop-content noo-row <?php if ( $layout_style == 'slider' ) echo 'owl-carousel';?>">

                        <?php while ( $query->have_posts() ) : $query->the_post(); 
                            $open_date = noo_timetable_get_post_meta(get_the_ID(), '_open_date', 1);
                            $open_time = noo_timetable_get_post_meta(get_the_ID(), '_open_time', 1);
                            $close_time = noo_timetable_get_post_meta(get_the_ID(), '_close_time', 1);
                            $use_manually_setting = noo_timetable_get_post_meta(get_the_ID(), '_use_manual_settings', 1);
                            $use_adv_setting = noo_timetable_get_post_meta(get_the_ID(), '_use_advanced_multi_time', 1);
                            $number_week = noo_timetable_get_post_meta(get_the_ID(), '_number_of_weeks', 1);
                            $number_day_of_week = noo_timetable_get_post_meta(get_the_ID(), '_number_day', 1);
                            $cur_date = date( 'Y-m-d', current_time( 'timestamp' ) );
                            $cur_day = date('l', current_time( 'timestamp' ));
                            $cur_time = date( get_option('time_format'), current_time( 'timestamp' ) );
                            $start_of_week = Noo__Timetable__Class::_get_week_day( get_option('start_of_week') );
                            $first_week_day = date( 'Y-m-d', strtotime('last ' . $start_of_week, $open_date) );
                            $end_week_day = date( 'Y-m-d', strtotime($first_week_day . ' +6 days') );
                            $number_day_insert = 6 * $number_week;
                            $end_day = date( 'Y-m-d', strtotime($first_week_day . ' +'.$number_day_insert.' days') );
                            $days_of_week = [];
                            if (is_array($number_day_of_week)) {
                                foreach($number_day_of_week as $nd) {
                                    switch($nd) {
                                        case '1':
                                            $days_of_week[] = 'Monday';
                                            break;
                                        case '2':
                                            $days_of_week[] = 'Tuesday';
                                            break;
                                        case '3':
                                            $days_of_week[] = 'Wednesday';
                                            break;
                                        case '4':
                                            $days_of_week[] = 'Thursday';
                                            break;
                                        case '5':
                                            $days_of_week[] = 'Friday';
                                            break;
                                        case '6':
                                            $days_of_week[] = 'Saturday';
                                            break;
                                        case '0':
                                            $days_of_week[] = 'Sunday';
                                            break;
                                        default:
                                            break;
                                    }
                                }
                            }
                            if($use_manually_setting == 0) {
                                if($use_adv_setting == 0) {
                                    if( strtotime($cur_date) == $open_date) {
                                       // if()
                                    }
                                }
                            }
                        ?>

                        <?php noo_timetable_get_template( 'content-class.php' ); ?>

                        <?php endwhile;?>

                    </div> <!-- /.posts-loop-content -->
                </div>
                <?php if ( $layout_style == 'slider' ) :  ?>
                    
                    <script type="text/javascript">
                    jQuery(document).ready(function(){

                        jQuery('.noo-data-slider .posts-loop-content').each(function(){
                            jQuery(this).owlCarousel({
                                autoplay: <?php echo $autoplay = $autoplay == 'true' ? true : false ?>, //Set AutoPlay to 3 seconds
                                items : <?php echo absint( $columns ); ?>,
                                dots: true,
                                responsive: {
                                    0: {
                                        items: 1
                                    },
                                    500: {
                                        items: 2
                                    },
                                    991: {
                                        items: 2
                                    },
                                    1300: {
                                        items: <?php echo absint( $columns ); ?>
                                    }
                                },

                            });
                        });
                    });
                    </script>

                <?php endif; ?>

                <?php
                    if ( $pagination != 'disable' ) :
                        if ( function_exists('noo_timetable_pagination_normal') ):
                            noo_timetable_pagination_normal( array(), $query );
                        endif;
                    endif;
                ?>

            <?php
            endif; ?>
        </div> <!-- /.noo-trainer-shortcode -->

        <?php endif; ?>

        <?php
        $html = ob_get_contents();
        ob_end_clean();
        wp_reset_query();
        return $html;

    }

    add_shortcode( 'ntt_class_coming', 'shortcode_ntt_class_coming' );

endif;