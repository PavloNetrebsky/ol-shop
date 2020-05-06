<?php
/**
 * Shortcode Noo Trainer
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/shortcodes/ntt-trainer.php.
 *
 * @author      NooTheme
 * @package     NooTimetable/Templates/Shortcodes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( ! function_exists( 'shortcode_ntt_trainer' ) ) :
	
	function shortcode_ntt_trainer( $atts ) {

		extract( shortcode_atts( array(
            'title'             => '',
            'sub_title'         => '',
            'layout_style'      => 'grid',
            'columns'           => '4',
            'cat'               => 'all',
            'orderby'           => 'default',
            'limit'             => '4',
            'class'             => '',
        ), $atts ) );

        ob_start();

        // 
        // Style Control Class
        // 

        $class = ($class != '') ? $class . ' ' : '';
        $class_layout = ($layout_style == 'grid') ? ' grid' : ' list';
        $class_shortcode = $class . 'noo-trainer-shortcode' . $class_layout;

        $order = 'DESC';
        switch ( $orderby ) {
            case 'latest':
                $orderby = 'date';
                break;
            case 'oldest':
                $orderby = 'date';
                $order = 'ASC';
                break;
            case 'alphabet':
                $orderby = 'title';
                $order = 'ASC';
                break;
            case 'ralphabet':
                $orderby = 'title';
                break;
            default:
                $orderby = 'default';
                break;
        }

        $args = array(
            'post_type'       => "noo_trainer",
            'posts_per_page'  => $limit,
        );

        if ('default' != $orderby) {
            $args['orderby'] = $orderby;
            $args['order']   = $order;
        }

        if(!empty($cat) && $cat != 'all'){
            $args['tax_query'][] =  array(

                'taxonomy' => 'class_category',
                'terms'    => explode(',', $cat),
                'field'    => 'id'
            );
        }

        $args = apply_filters( 'noo_timetable_ntt_trainer_query_args', $args );

        $query = new WP_Query( $args );

        ?>
        <div class="<?php echo esc_attr( $class_shortcode ); ?>">

            <?php
                global $title_var;
                $title_var = compact('title', 'sub_title');
                noo_timetable_get_template( 'shortcodes/ntt-title.php' );
            ?>

            <!-- Section content -->

            <?php

            if ( $query->have_posts() ) :

                global $post_options;
                
                if ( $layout_style == 'list' ) {
                    $columns = 1;
                }

                $post_class = 'loadmore-item masonry-item noo-sm-6 noo-md-'.absint((12 / $columns)) . ' ' . $layout_style;

                $post_options = compact('post_class');
            ?>
                <div class="grid">
                    <div class="posts-loop-content noo-row">

                        <div class="masonry-container">
                            <?php while ( $query->have_posts() ) : $query->the_post(); ?>

                            <?php noo_timetable_get_template( 'content-trainer.php' ); ?>

                            <?php endwhile;?>
                        </div>

                    </div> <!-- /.posts-loop-content -->
                </div>
            <?php
            endif; ?>
        </div> <!-- /.noo-trainer-shortcode -->

        <?php
		$html = ob_get_contents();
        ob_end_clean();
        wp_reset_query();
        return $html;

	}

	add_shortcode( 'ntt_trainer', 'shortcode_ntt_trainer' );

endif;