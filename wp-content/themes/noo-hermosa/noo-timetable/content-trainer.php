<?php
/**
 * The template for displaying trainer content within loops
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/content-trainer.php.
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

<article <?php post_class($post_class . ' trainer-tag-wrap'); ?>>
	<div class="trainer-bio">
        <a class="trainer-avatar" href="<?php echo get_permalink(get_the_ID()) ; ?>">
            <?php echo get_the_post_thumbnail(get_the_ID(), 'noo-thumbnail-trainer') ?>
        </a>
        
        <div class="trainer-info">
            <h4>
                <a title="<?php printf( esc_html__( 'Post by %s','noo-hermosa'), get_the_title(get_the_ID()) ); ?>" href="<?php echo get_permalink(get_the_ID()) ; ?>" rel="author">
                    <?php echo get_the_title(get_the_ID()) ?>
                </a>
            </h4>
            <div class="trainer-category">
                <?php
                    echo get_the_term_list(get_the_ID(), 'class_category',' ',', ');
                ?>
            </div>
            <?php
                $facebook       =   noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_facebook", '' );
                $google         =   noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_google", '' );
                $twitter        =   noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_twitter", '' );
                $pinterest      =   noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_pinterest", '' );
            ?>
            <?php if(!empty($facebook) || !empty($twitter) || !empty($google) || !empty($linkedin) || !empty($pinterest)):?>
                <div class="trainer-social all-social-share">
                    <?php echo ( !empty($facebook) ? '<a href="' . $facebook . '" class="fa fa-facebook"></a>' : '' ); ?>
                    <?php echo ( !empty($google) ? '<a href="' . $google . '" class="fa fa-google-plus"></a>' : '' ); ?>
                    <?php echo ( !empty($twitter) ? '<a href="' . $twitter . '" class="fa fa-twitter"></a>' : '' ); ?>
                    <?php echo ( !empty($pinterest) ? '<a href="' . $pinterest . '" class="fa fa-pinterest"></a>' : '' ); ?>
                </div>
            <?php endif; ?>

            <div class="trainer-excerpt">
                <?php
                $excerpt = $post->post_excerpt;
                if(empty($excerpt))
                    $excerpt = $post->post_content;
                
                $excerpt = strip_shortcodes($excerpt);
                echo '<p>' . wp_trim_words($excerpt, 28, '...') . '</p>';
                ?>
            </div>

            <a class="btn view-profile" title="<?php printf( esc_html__( 'Post by %s','noo-hermosa'), get_the_title(get_the_ID()) ); ?>" href="<?php echo get_permalink(get_the_ID()); ?>" rel="author">
                <?php echo esc_html__('View Profile', 'noo-hermosa'); ?>
            </a>
        </div>
    </div>
</article>
