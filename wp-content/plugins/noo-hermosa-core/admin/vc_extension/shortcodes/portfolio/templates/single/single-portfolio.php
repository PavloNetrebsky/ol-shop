<?php
/**
 *  
 * @package    NooTheme/Noo Behealth
 * @version    1.0.0
 * @author     Manhnv <mannv@vietbrain.com>
 * @copyright  Copyright (c) 2018, Noo Theme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
*/

get_header();

wp_enqueue_style('noo-carousel');
wp_enqueue_script('noo-carousel');
wp_enqueue_script('jquery-hoverdir');
wp_enqueue_style('prettyPhoto');
wp_enqueue_script('prettyPhoto');
if ( have_posts() ) {
    // Start the Loop.
    while ( have_posts() ) : the_post();
        $post_id    = get_the_ID();
        $categories = get_the_terms($post_id, 'portfolio_category');
        
        $meta_values = get_post_meta( get_the_ID(), 'noo_portfolio_data_format_gallery', false );
        $imgThumbs   = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),'full');
        $cat         = '';
        $arrCatId    = array();
        if($categories) {
            foreach($categories as $category) {
                $cat .= '<span>'.$category->name.'</span>, ';
                $arrCatId[count($arrCatId)] = $category->term_id;
            }
            $cat = trim($cat, ', ');
        }

         $tags     = get_the_terms($post_id, 'portfolio_tag');
         $tag      = '';
         $arrTagId = array();
         if($tags) {
             foreach($tags as $t) {
                 $tag .= '<span>'.$t->name.'</span> ';
                 $arrTagId[count($arrTagId)] = $t->term_id;
             }
             $tag = trim($tag, ', ');
         }
        
        $detail_style =  get_post_meta(get_the_ID(), 'portfolio_detail_style', true);
        if (!isset($detail_style) || $detail_style == 'none' || $detail_style == '') {
            $detail_style = noo_hermosa_get_option('noo_portfolio-single-style');
        }
        ?>
        <div class="<?php noo_hermosa_main_class(); ?>">
    <?php  include_once(plugin_dir_path( __FILE__ ).'/'.$detail_style.'.php'); ?>
        </div>
        <?php
        $page_layout = noo_hermosa_get_page_layout();
        $sidebar = noo_hermosa_get_sidebar_id();
        if( ! empty( $sidebar ) && $page_layout!=='fullwidth' ) :?>
            <div class="<?php noo_hermosa_sidebar_class(); ?>">
                <div class="noo-sidebar-wrap">
                    <?php // Dynamic Sidebar
                    if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) : ?>
                        <!-- Sidebar fallback content -->

                    <?php endif; // End Dynamic Sidebar sidebar-main ?>
                </div>
            </div>
        <?php endif; // End sidebar ?>
<?php
    endwhile;
    }
?>
<?php
if(noo_hermosa_get_option('noo_portfolio_show_related')){
    include_once(plugin_dir_path( __FILE__ ).'related.php');
}


?>
<?php get_footer(); ?>
