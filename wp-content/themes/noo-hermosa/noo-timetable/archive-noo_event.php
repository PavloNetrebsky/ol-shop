<?php
/**
 * The Template for displaying event archives
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/archive-noo_event.php.
 *
 * @author 		NooTheme
 * @package 	NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

/**
 * VAR
 */

$columns        = NOO_Settings()->get_option('noo_event_grid_column', 2);
$layout         = NOO_Settings()->get_option('noo_event_default_layout', 'grid');
$posts_per_page = NOO_Settings()->get_option( 'noo_event_num', '10' );
$hide_past = NOO_Settings()->get_option('noo_event_hide_past', 'no');
$show_category  = noo_hermosa_get_option( 'noo_event_category', false );

$post_class     = 'noo-sm-6 noo-md-'.absint((12 / $columns));

/**
 * Check paged
 */
if( is_front_page() || is_home()) :
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
else :
    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
endif;

/**
 * Create array
 */

$args = array(
    'post_type'      => 'noo_event',
    'post_status'    => 'publish',
    'paged'          => $paged,
    'posts_per_page' => $posts_per_page
);

if( $hide_past == 'yes' ){
    $args['meta_query'] = array(
        'relation' => 'OR',
        array(
            'relation' => 'AND',
            array(
                'key'     => '_recurrence',
                'value'   => '',
                'compare' => '!='
            ),
            array(
                'key'     => '_next_date',
                'value'   => noo_timetable_time_now(),
                'compare' => '>='
            )
        ),
        array(
            'relation' => 'AND',
            array(
                'key'     => '_noo_event_end_date',
                'value'   => strtotime( date( 'Y-m-d', noo_timetable_time_now() ) ),
                'compare' => '>='
            ),
            array(
                'key'     => '_noo_event_end_time',
                'value'   => strtotime( date( 'H:i:s', noo_timetable_time_now() ) ),
                'compare' => '>='
            )
        )
    );
}

$taxonomy = get_query_var( 'taxonomy' );
if ( !empty( $taxonomy ) ) :

    $args['tax_query'] = array(
        array(
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => get_query_var( 'term' ),
        ),
    );

endif;

/**
 * Check search
 */
if ( !empty( $_GET['post_type'] ) ) :

    /**
     * VAR
     */
        $keyword  = ( isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : '' );
        $category = ( isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '' );
        $date     = ( isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '' );
        $address  = ( isset( $_GET['address'] ) ? sanitize_text_field( $_GET['address'] ) : '' );

    /**
     * Process
     */
        if ( !empty( $keyword ) ) :

            $args['s'] = $keyword;

        endif;

        if ( !empty( $address ) ) :
            $args['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key'   => '_noo_event_address',
                    'value' => $address,
                    'compare' => 'LIKE'
                )
            );
        endif;

        if ( !empty( $date ) ) :
            $start_date         = strtotime( $date . ' 23:59' );
            $end_date           = strtotime( $date . ' 00:00' );
            $args['meta_query'] = array(                
                'relation' => 'OR',
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => '_next_date',
                        'value'   => $start_date,
                        'compare' => '<='
                    ),                        
                    array(
                        'key'     => '_next_date_end',
                        'value'   => $end_date,
                        'compare' => '>='
                    )
                ),
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => '_noo_event_start_date',
                        'value'   => $start_date,
                        'compare' => '<='
                    ),
                    array(
                        'key'     => '_noo_event_end_date',
                        'value'   => $end_date,
                        'compare' => '>='
                    )
                )
            );

        endif;

        if ( !empty( $category ) ) :

            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'event_category',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
            );

        endif;


endif;
$wp_query = new WP_Query( $args );

get_header();?>

<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">
            <div class="<?php noo_hermosa_main_class(); ?> event-wrap">

                <div class="archive-noo-event-head">
                    
                    <span class="noo-event-text">
                        <?php echo sprintf( esc_html__( 'We found %s available events for you', 'noo-hermosa' ), '<span>' . $wp_query->found_posts . '</span>' ); ?>
                    </span>

                    <span class="noo-event-button">
                        <i class="fa fa-th-list<?php echo esc_attr( $layout === 'list' ? ' active' : '' ); ?>" data-id="list"></i>
                        <i class="fa fa-th-large<?php echo esc_attr( $layout === 'grid' ? ' active' : '' ); ?>" data-id="grid"></i>
                    </span>

                </div><!-- /.archive-noo-event-head -->
                
                <div class="archive-noo-event-wrap event-<?php echo esc_attr( $layout ); ?> noo-row">
                    <?php if ($wp_query->have_posts()) : ?>
                       
                        <?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
                            
                            <div class="archive-noo-event-item-wrap <?php echo esc_attr($post_class); ?>">

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

                        <?php endwhile; ?>
                        
                        <?php noo_hermosa_pagination_normal( $wp_query->max_num_pages ); ?>

                    <?php else : ?>
                        <div class="noo-md-12">

                            <h4 class="center"><?php echo esc_html__( 'Not Found', 'noo-hermosa' ); ?></h4>
                            <p class="center">
                                <?php echo esc_html__( 'Sorry, but you are looking for something that isn\'t here.', 'noo-hermosa' ); ?>
                            </p>

                        </div>

                    <?php endif; ?>
                </div>
            </div>
            <?php get_sidebar(); ?>
        </div>


    </main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>