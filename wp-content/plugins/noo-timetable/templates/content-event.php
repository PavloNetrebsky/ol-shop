<?php
/**
 * The template for displaying event content within loops
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/content-event.php.
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

$color = get_post_meta( get_the_ID(), "_noo_event_bg_color", '#fff' );
$class_color = str_replace('#', 'event_', $color);
$mansory_filter_class = array();
$mansory_filter_class[] = $class_color;
$mansory_filter_class[] = $post_class;
foreach ( (array) get_the_terms($post->ID,'class_category') as $cat ) {
    if ( empty($cat->slug ) )
        continue;
    $mansory_filter_class[] =  'filter-cat-'.$cat->term_id;
}
$event_organizers = (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), '_noo_event_organizers') );
foreach ($event_organizers as $event_organizer_id) {
    $mansory_filter_class[]='filter-organizer-'.$event_organizer_id;
}
?>
<article <?php post_class($mansory_filter_class); ?>>
    <div class="loop-item-wrap">
        
        <?php Noo__Timetable__Event::show_featured(); ?>

        <div class="loop-item-content">
            <div class="loop-item-content-summary">
                <div class="loop-item-category"><?php echo get_the_term_list(get_the_ID(), 'event_category',' ',', ')?></div>
                <h2 class="loop-item-title">
                    <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( wp_kses( __( 'Permanent link to: "%s"','noo-timetable' ), noo_timetable_allowed_html() ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
                </h2>

                <?php Noo__Timetable__Event::show_meta(); ?>
                <?php Noo__Timetable__Event::show_repeat_info(); ?>

                <div class="loop-item-excerpt">
                    <?php
                    $excerpt = $post->post_excerpt;
                    if(empty($excerpt))
                        $excerpt = $post->post_content;
                    
                    $excerpt = strip_shortcodes($excerpt);
                    $exc_length = NOO_Settings()->get_option('noo_event_excerpt_length', 18);
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