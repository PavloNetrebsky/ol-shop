<?php
/**
 * The Template for displaying trainer archives
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/archive-noo_trainer.php.
 *
 * @author      NooTheme
 * @package     NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

get_header();

$layout_style = NOO_Settings()->get_option('noo_trainer_style', 'grid');
$columns = NOO_Settings()->get_option('noo_trainer_columns', 4);

$class_layout = ($layout_style == 'grid') ? ' grid' : ' list';
$class_shortcode = 'noo-trainer-shortcode' . $class_layout;

wp_enqueue_script('imagesloaded');
wp_enqueue_script('isotope');

?>


<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">

        <div class="noo-row">

            <div class="<?php noo_hermosa_main_class(); ?>">

                <?php
                $post_class = 'noo-xs-6 noo-sm-6 noo-md-' . absint((12 / $columns));

                if (have_posts()) : ?>

                    <div class="masonry">
                        <?php
                        // Masonry Grid
                        $category_arr = get_terms('class_category', array('orderby' => 'NAME', 'order' => 'ASC'));

                        $filter = true;

                        if (count($category_arr) > 0 && $filter):
                            ?>

                            <div class="masonry-header noo-filters trainer-filters">
                                <div class="masonry-filters">
                                    <ul data-option-key="filter">
                                        <li>
                                            <a class="selected" href="#"
                                               data-option-value="*"><?php echo esc_html__('All Category', 'noo-hermosa') ?></a>
                                        </li>
                                        <?php
                                        foreach ($category_arr as $category): ?>
                                            <li>
                                                <a href="#"
                                                   data-option-value=".<?php echo 'mansonry-filter-' . $category->slug ?>"><?php echo esc_attr($category->name); ?></a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>

                        <?php endif; ?>

                        <div class="mansonry-content">
                            <div class="trainer-tag-wrap noo-row">
                                <div id="masonry-container" data-masonry-gutter="0"
                                     data-masonry-column="'<?php echo esc_attr($columns); ?>'"
                                     class="masonry-container columns-'<?php echo esc_attr($columns); ?>'">
                                    <?php

                                    while (have_posts()) : the_post(); ?>
                                        <?php

                                        $cat_class = array();
                                        foreach ((array)get_the_terms(get_the_ID(), 'class_category') as $cat) {
                                            if (empty($cat->slug))
                                                continue;
                                            $cat_class[] = 'mansonry-filter-' . sanitize_html_class($cat->slug, $cat->term_id);
                                        }
                                        $item_class = 'masonry-item ' . implode(' ', $cat_class);

                                        ?>
                                        <div
                                            class="trainer-item <?php echo esc_attr($post_class); ?>  <?php echo esc_attr($item_class); ?>">
                                            <div class="trainer-bio">
                                                <a class="trainer-avatar" href="<?php the_permalink(); ?>">
                                                    <?php the_post_thumbnail('noo-thumbnail-trainer'); ?>
                                                </a>
                                                <div class="trainer-info">
                                                    <h4>
                                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                    </h4>
                                                    <div class="trainer-category">
                                                        <?php
                                                        echo get_the_term_list(get_the_ID(), 'class_category', ' ', ', ');
                                                        ?>
                                                    </div>
                                                    <?php
                                                    $facebook = noo_timetable_get_post_meta(get_the_ID(), "_noo_trainer_facebook", '');
                                                    $google = noo_timetable_get_post_meta(get_the_ID(), "_noo_trainer_google", '');
                                                    $twitter = noo_timetable_get_post_meta(get_the_ID(), "_noo_trainer_twitter", '');
                                                    $pinterest = noo_timetable_get_post_meta(get_the_ID(), "_noo_trainer_pinterest", '');
                                                    ?>
                                                    <?php if (!empty($facebook) || !empty($twitter) || !empty($google) || !empty($linkedin) || !empty($pinterest)): ?>
                                                        <div class="trainer-social all-social-share">
                                                            <?php echo(!empty($facebook) ? '<a href="' . $facebook . '" class="fa fa-facebook"></a>' : ''); ?>
                                                            <?php echo(!empty($google) ? '<a href="' . $google . '" class="fa fa-google-plus"></a>' : ''); ?>
                                                            <?php echo(!empty($twitter) ? '<a href="' . $twitter . '" class="fa fa-twitter"></a>' : ''); ?>
                                                            <?php echo(!empty($pinterest) ? '<a href="' . $pinterest . '" class="fa fa-pinterest"></a>' : ''); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <a class="btn view-profile"
                                                       title="<?php printf(esc_html__('Post by %s', 'noo-hermosa'), get_the_title()); ?>"
                                                       href="<?php the_permalink(); ?>">
                                                        <?php echo esc_html__('View Profile', 'noo-hermosa'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div> <!-- /.masonry-container -->
                            </div> <!-- /.trainer-tag-wrap -->
                        </div><!-- /.mansonry-content -->
                    </div><!-- /.masonry -->
                    <?php
                endif; ?>

            </div><!-- /.main_class -->
            <?php get_sidebar(); ?>

        </div>

    </main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>