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

$show_meta_date    = noo_hermosa_get_option('noo_classes_meta_date', true);
$show_meta_time    = noo_hermosa_get_option('noo_classes_meta_time', true);
$show_meta_trainer = noo_hermosa_get_option('noo_classes_meta_trainer', true);
$show_meta_address = noo_hermosa_get_option('noo_classes_meta_address', false);

?>

<article <?php post_class($post_class); ?>>
    <div class="loop-item-wrap">
        <?php if(has_post_thumbnail()):?>
        <?php
            $feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), array(400, 300) );
        ?>
        <a class="loop-item-featured" href="<?php the_permalink(); ?>" aria-hidden="true" style="background-image:url('<?php echo esc_url($feat_image_url[0]); ?>');">
            <?php the_post_thumbnail( array(150, 150), array( 'alt' => the_title_attribute( 'echo=0' ), 'class' => 'sr-only' ) ); ?>
        </a>
        <?php endif;?>
        <div class="loop-item-content">
            <div class="loop-item-content-summary">
                <div class="loop-item-category"><?php echo get_the_term_list(get_the_ID(), 'class_category',' ',', ')?></div>
                <h2 class="loop-item-title">
                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( wp_kses( __( 'Permanent link to: "%s"','noo-hermosa' ), noo_hermosa_allowed_html() ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="content-meta">
                    
                    <?php
                        $open_date  = noo_timetable_get_post_meta(get_the_ID(),'_open_date');
                        $open_time  = noo_timetable_get_post_meta( get_the_ID(), "_open_time", '' );
                        $close_time = noo_timetable_get_post_meta( get_the_ID(), "_close_time", '' );
                    ?>
                    <?php
                        $number_days        = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );
                        $class_dates = Noo__Timetable__Class::get_open_date_display(array(
                            'open_date'       => $open_date,
                            'number_of_weeks' => noo_timetable_get_post_meta( get_the_ID(), "_number_of_weeks", '1'),
                            'number_days'     => $number_days
                        ));
                        
                        $address = noo_timetable_get_post_meta(get_the_ID(),'_address');
                    ?>
                    <?php if( $show_meta_date && !empty( $class_dates['open_date']) ) : ?>
                        <span class="meta-date">
                            <time datetime="<?php echo mysql2date( 'c',$open_date) ?>">
                                <i class="icon ion-calendar"></i>
                                <?php echo esc_html__('Open:', 'noo-hermosa'); ?> <?php echo esc_html(date_i18n(get_option('date_format'),$open_date)); ?>
                            </time>
                        </span>
                    <?php endif;?>
                    <?php if( $show_meta_date && !empty( $class_dates['next_date'] ) ) : ?>
                        <span class="meta-date">
                            <time datetime="<?php echo mysql2date( 'c', $class_dates['next_date']) ?>">
                                <i class="icon ion-calendar"></i>
                                <?php echo esc_html__('Next:', 'noo-hermosa'); ?> <?php echo esc_html(date_i18n(get_option('date_format'),$class_dates['next_date'])); ?>
                            </time>
                        </span>
                    <?php endif;?>
                    <?php if( $show_meta_time && !empty( $open_time ) && !empty( $close_time ) && is_numeric( $open_time ) && is_numeric( $close_time ) ) : ?>
                        <span class="meta-time">
                            <i class="icon ion-android-alarm-clock"></i>&nbsp;<?php echo date_i18n(get_option('time_format'),$open_time).' - '. date_i18n(get_option('time_format'),$close_time); ?>
                        </span>
                    <?php endif;?>
                    <?php if( $show_meta_address && !empty( $address ) ):?>
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
            <div class="loop-item-action">
                <a class="btn" href="<?php the_permalink()?>"><?php echo esc_html__('Learn More','noo-hermosa')?></a>
            </div>
            
            <?php
                $trainer_ids = noo_timetable_get_post_meta(get_the_ID(),'_trainer');
                $class_tr = '';

                if( $show_meta_trainer && !empty( $trainer_ids ) ):
                foreach ($trainer_ids as $k => $trid) :
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
                <?php
                endforeach;
            endif;?>
        </div>
    </div>
</article>