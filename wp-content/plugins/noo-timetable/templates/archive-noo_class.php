<?php
/**
 * The Template for displaying class archives
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/archive-noo_class.php.
 *
 * @author 		NooTheme
 * @package 	NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

$layout_style = NOO_Settings()->get_option('noo_classes_style', 'grid');
$columns = NOO_Settings()->get_option('noo_classes_grid_columns', 2);
$show_class_meta = NOO_Settings()->get_option('show_class_meta',array('open_date','next_date','address',));

$class_layout = ($layout_style == 'grid') ? ' grid' : ' list';
$class_shortcode = 'noo-class-shortcode' . $class_layout;
wp_enqueue_script( 'isotope' );
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script('noo-class');
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

            // $show_date = true;
            // $show_time = true;
            // $post_options = compact('post_class', 'show_date', 'show_time');

            $post_options = compact('post_class', 'show_class_meta');

			while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>

				<?php noo_timetable_get_template( 'content-class.php' ); ?>

			<?php endwhile; ?>

		<?php 
		else :
			noo_timetable_get_template( 'content-none.php' );
		endif;
		?>
	</div>
</div> <!-- /.noo-class-shortcode -->
<?php noo_timetable_get_template( 'pagination.php' ); ?>
<?php

do_action( 'after_noo_timetable_main_wrap' );
noo_timetable_get_sidebar( 'noo-class-sidebar' );
do_action( 'after_noo_timetable_wrap' );
?>
<?php get_footer();