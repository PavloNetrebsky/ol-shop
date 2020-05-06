<?php
/**
 * The template for displaying class content within loops
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/content-class.php.
 *
 * @author      NooTheme
 * @package     NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

global $post_options, $post;

if ( $post_options )
    extract($post_options, EXTR_PREFIX_SAME, "ntt");

$color = Noo__Timetable__Class::get_color_by_category(get_the_ID());
$mansory_filter_class = array();
$class_color = str_replace('#', 'class_category_', $color);
$mansory_filter_class[] = $class_color;
$mansory_filter_class[] = $post_class;
foreach ( (array) get_the_terms($post->ID,'class_category') as $cat ) {
    if ( empty($cat->slug ) )
        continue;
    $mansory_filter_class[] =  'filter-cat-'.$cat->term_id;
}
foreach ( (array) get_the_terms($post->ID,'class_level') as $level ) {
    if ( empty($level->slug ) )
        continue;
    $mansory_filter_class[] =  'filter-level-'.$level->term_id;
}

$trainer = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), '_trainer') );
foreach ($trainer as $trainer_id) {
    $mansory_filter_class[]='filter-trainer-'.$trainer_id;
}
foreach ((array)noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) ) as $dayindex){
    $mansory_filter_class[] = 'filter-day-'.$dayindex;
}
?>
<article <?php post_class($mansory_filter_class); ?>>
    <div class="loop-item-wrap">
        <?php if(has_post_thumbnail()):?>
        <a class="loop-item-featured" href="<?php the_permalink(); ?>" aria-hidden="true" >
            <?php the_post_thumbnail( 'noo-thumbnail-class', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
        </a>
        <?php endif;?>
        <div class="loop-item-content">
            <div class="loop-item-content-summary">
                <?php if(in_array('class_level',$show_class_meta)):?>
                    <div class="loop-item-level"><?php echo get_the_term_list(get_the_ID(), 'class_level',' ',', ')?></div>
                <?php endif;?>
                <?php if(in_array('class_category',$show_class_meta)):?>
                    <div class="loop-item-category"><?php echo get_the_term_list(get_the_ID(), 'class_category',' ',', ')?></div>
                <?php endif;?>
                <h2 class="loop-item-title">
                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( wp_kses( __( 'Permanent link to: "%s"','noo-timetable' ), noo_timetable_allowed_html() ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="content-meta">
                    
                    <?php

                        $open_date  = noo_timetable_get_post_meta(get_the_ID(),'_open_date');
                        $open_time  = noo_timetable_get_post_meta( get_the_ID(), "_open_time", '' );
                        $close_time = noo_timetable_get_post_meta( get_the_ID(), "_close_time", '' );

                        $use_manual_settings = noo_timetable_get_post_meta( get_the_ID(), "_use_manual_settings", false );
                        $use_advanced_multi_time = noo_timetable_get_post_meta( get_the_ID(), "_use_advanced_multi_time", false );
                        $use_advanced_multi_time = $use_manual_settings ? false : $use_advanced_multi_time;
                    ?>
                    <?php
                        $number_days        = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );
                        $manual_date        = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_manual_date", '' ) );
                        $class_dates = Noo__Timetable__Class::get_open_date_display(array(
                            'open_date'       => $open_date,
                            'number_of_weeks' => noo_timetable_get_post_meta( get_the_ID(), "_number_of_weeks", '1'),
                            'number_days'     => $number_days,
                            'manual_date'     => $manual_date,
                        ));
                        $address = noo_timetable_get_post_meta(get_the_ID(), '_address');
                    ?>
                    <?php if( in_array('open_date',$show_class_meta) && !empty( $class_dates['open_date']) ) : ?>
                        <span class="meta-date open-date">
                            <time datetime="<?php echo mysql2date( 'c', $open_date) ?>">
                                <i class="fa fa-calendar"></i>
                                <?php echo esc_html__('Open:', 'noo-timetable'); ?>
                                <?php echo esc_html(date_i18n(get_option('date_format'), $open_date)); ?>
                            </time>
                        </span>
                    <?php endif;?>
                    <?php if( in_array('next_date',$show_class_meta) && !empty( $class_dates['next_date']) ) : ?>
                        <span class="meta-date next-date">
                            <time datetime="<?php echo mysql2date( 'c', $class_dates['next_date']) ?>">
                                <i class="fa fa-calendar"></i>
                                <?php echo esc_html__('Next:', 'noo-timetable'); ?>
                                <?php echo esc_html(date_i18n(get_option('date_format'), $class_dates['next_date'])); ?>
                            </time>
                        </span>
                    <?php endif;?>
                    <?php if (in_array('start_time',$show_class_meta)): ?>
                        <?php if( !$use_advanced_multi_time && !$use_manual_settings ) : ?>
                            <?php if( !empty( $open_time ) && !empty( $close_time ) ) : ?>
                                <span class="meta-time open-time">
                                    <i class="fa fa-clock-o"></i>&nbsp;<?php echo date_i18n(get_option('time_format'),$open_time).' - '. date_i18n(get_option('time_format'),$close_time); ?>
                                </span>
                            <?php endif;?>
                         <?php else : ?>
                            <div class="clearfix open-time"><i class="fa fa-clock-o"></i>&nbsp;<?php echo esc_html__('Multiple time', 'noo-timetable'); ?> <i class="icon ion-android-arrow-forward"></i></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if( in_array('address',$show_class_meta) && !empty( $address ) ):?>
                    <span class="meta-address">
                        <i class="fa fa-map-marker"></i>
                         <?php echo esc_html($address); ?>
                    </span>
                    <?php endif;?>

                    <?php
                    $number_days         = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );
                    $use_manual_settings = noo_timetable_get_post_meta( get_the_ID(), "_use_manual_settings", false );
                    if( in_array('day_of_week',$show_class_meta) && !empty( $number_days ) && !$use_manual_settings ) :
                        global $wp_locale;
                        $start_of_week = get_option('start_of_week');
                        $ndays1 = array();
                        $ndays2 = array();
                        asort( $number_days );
                        foreach ($number_days as $k => $nday) {
                            if ( $nday >= $start_of_week ) {
                                $ndays1[] = $nday;
                            } else {
                                $ndays2[] = $nday;
                            }
                        }
                        $number_days = array_merge($ndays1, $ndays2);
                        ?>
                        <div class="tag-days">
                            <i class="fa fa-check"></i>&nbsp;<?php echo esc_html__('Days:','noo-timetable');?>
                            <span class="wrap-days">
                                <?php foreach ($number_days as $number_day) : ?>
                                    <?php if ( is_numeric($number_day) ) : ?>
                                        <span><?php echo esc_html__($wp_locale->get_weekday_abbrev($wp_locale->get_weekday($number_day))) ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php
                    if(in_array('trainer', $show_class_meta)){
                        $trainer_ids = noo_timetable_get_post_meta(get_the_ID(), '_trainer');
                        Noo__Timetable__Class::get_trainer_list($trainer_ids);
                    }
                    ?>
                </div>
                <div class="loop-item-excerpt">
                    <?php
                    $excerpt = $post->post_excerpt;
                    if(empty($excerpt))
                        $excerpt = $post->post_content;
                    
                    $excerpt = strip_shortcodes($excerpt);
                    $exc_length = NOO_Settings()->get_option('noo_classes_excerpt_length', 18);
                    echo '<p>' . wp_trim_words($excerpt, $exc_length, '...') . '</p>';
                    ?>
                </div>
            </div>
            <div class="loop-item-action">
                <a class="button" href="<?php the_permalink()?>"><?php echo esc_html__('Learn More','noo-timetable')?></a>
            </div>

        </div>
    </div>
</article>