<?php
/**
 * The Template for displaying all single classes
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/single-noo_class.php.
 *
 * @author 		NooTheme
 * @package 	NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

do_action( 'before_noo_timetable_wrap' );
do_action( 'before_noo_timetable_main_wrap' );

?>

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				
				<?php noo_timetable_post_thumbnail(); ?>

				<!-- <header class="entry-header">
					<div class="loop-item-category"><?php echo get_the_term_list(get_the_ID(), 'class_category',' ',', ')?></div>
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header> -->

				<header class="entry-header">

					<?php if($category_list = get_the_term_list(get_the_ID(), 'class_category',' ',', ')):?>
						<span>
							<?php _e('Category:','noo-timetable')?>
							<?php echo ($category_list)?>
						</span>
					<?php endif;?>

					<?php if($level_list = get_the_term_list(get_the_ID(), 'class_level',' ',', ')):?>
						<span>
							<?php _e('Level:','noo-timetable')?>
							<?php echo ($level_list)?>
						</span>
					<?php endif;?>

					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

				</header>
			
				<?php noo_timetable_excerpt(); ?>

				<div class="entry-content">
					<?php
						the_content();
						wp_link_pages();
						// the_meta();
						Noo__Timetable__Class::get_timetable();
					?>
				</div>
				
				<footer class="entry-footer"></footer>

			</article>

		<?php
		// End of the loop.
		endwhile;
		?>

		<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		?>

<?php
do_action( 'after_noo_timetable_main_wrap' );
noo_timetable_get_sidebar( 'noo-class-sidebar' );
do_action( 'after_noo_timetable_wrap' );
?>
<?php get_footer();