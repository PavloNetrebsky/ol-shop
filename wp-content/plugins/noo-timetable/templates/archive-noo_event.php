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
$layout_style = NOO_Settings()->get_option('noo_event_default_layout', 'grid');
$columns = NOO_Settings()->get_option('noo_event_grid_column', 2);

$class_layout = ($layout_style == 'grid') ? ' grid' : ' list';
$class_shortcode = 'noo-event-shortcode' . $class_layout;

wp_enqueue_script( 'isotope' );
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script('noo-event');

do_action( 'before_noo_timetable_wrap' );
do_action( 'before_noo_timetable_main_wrap' );
?>
<div class="<?php echo esc_attr( $class_shortcode ); ?>">
	<div class="posts-loop-content noo-row">
		<?php if ( have_posts() ) : ?>
			<?php
			global $wp_query;

			if ( $layout_style == 'list' ) {
                $columns = 1;
            }
            
            $post_class = 'noo-sm-6 noo-md-'.absint((12 / $columns));

			$show_time_start = NOO_Settings()->get_option('noo_event_time_start', true);
			$show_time_end   = NOO_Settings()->get_option('noo_event_time_end', true);
			$show_address    = NOO_Settings()->get_option('noo_event_address', true);

            $post_options = compact('post_class', 'show_time_start', 'show_time_end', 'show_address');

			while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

				<?php noo_timetable_get_template( 'content-event.php' ); ?>

			<?php endwhile; ?>

		<?php 
		else :
			noo_timetable_get_template( 'content-none.php' );
		endif;
		?>
	</div>
</div> <!-- /.noo-event-shortcode -->
<?php noo_timetable_get_template( 'pagination.php' ); ?>
<?php
do_action( 'after_noo_timetable_main_wrap' );
noo_timetable_get_sidebar( 'noo-event-sidebar' );
do_action( 'after_noo_timetable_wrap' );
?>
<?php get_footer();