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

$columns = NOO_Settings()->get_option('noo_classes_grid_columns', 2);

wp_enqueue_script('imagesloaded');
wp_enqueue_script('isotope');
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">

        <div class="noo-row">

            <div class="<?php noo_hermosa_main_class(); ?>" role="main">
                <?php get_template_part( "noo-timetable/class/content") ; ?>
            </div>
            <?php get_template_part( "noo-timetable/class/sidebar") ; ?>
            
        </div>

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>