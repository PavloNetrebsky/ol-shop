<?php
/**
 * The Template for displaying all single events
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/single-noo_event.php.
 *
 * @author 		NooTheme
 * @package 	NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
$show_filter = noo_hermosa_get_option('noo_post_event_filter', true);
if( !empty($show_filter) ) :
    echo '<div class="head-single-find-event">';
    echo do_shortcode( '[vc_row container_width="yes"][vc_column][noo_find_event][/vc_column][/vc_row]' );
    echo '</div>';
endif;
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">
            <div class="<?php noo_hermosa_main_class(); ?>">
                <?php
                    // Start the loop.
                    while ( have_posts() ) : the_post();
                        /*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        ?>

                        <div class="noo-single-event-wrap">

                            <div class="noo-single-event-head">
                                <h1 class="noo-title">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h1>
                                <?php Noo__Timetable__Event::show_meta(); ?>
                            </div>

                            <div class="noo-single-event-body">
                                
                                <?php Noo__Timetable__Event::show_featured(); ?>
                                
                                <?php the_content(); ?>

                            </div><!-- /.noo-single-event-body -->

                            <div class="noo-single-event-footer">
                                <?php noo_hermosa_social_share(); ?>
                            </div>

                        </div><!-- /.noo-event-content -->

                        <?php

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;

                        // End the loop.
                    endwhile;
                ?>
            </div>
            <?php get_sidebar(); ?>
        </div>


    </main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>
