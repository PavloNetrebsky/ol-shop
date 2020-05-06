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

?>

<div <?php post_class($post_class . " archive-noo-event-item-wrap"); ?>>

    <div class="archive-noo-event-item">

        <div class="noo-archive-event-body">
            
            <?php Noo__Timetable__Event::show_featured(); ?>

            <div class="noo-single-event-head">

                <?php if ( !empty( $show_category ) ) : ?>
                    <span class="event-category">
                        <?php echo get_the_term_list( $post->ID, 'event_category', '',', ' );?>
                    </span>
                <?php endif; ?>
                
                <h3 class="noo-title">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h3>
                <?php Noo__Timetable__Event::show_meta( array( 'address' => false ) ); ?>
            </div>
            
            <?php the_excerpt(); ?>

        </div><!-- /.noo-archive-event-body -->

        <div class="noo-archive-event-footer">
            <?php noo_hermosa_social_share(); ?>
            <a class="readmore" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                <?php echo esc_html__( 'Read More', 'noo-hermosa' ); ?>
            </a>
        </div>

    </div>

</div><!-- /.archive-noo-event-item -->