<?php
if (!class_exists('Noo_Framework_Shortcode_Portfolio')) {

    class Noo_Framework_Shortcode_Portfolio
    {

        function __construct()
        {
            add_shortcode('noo_portfolio', array($this, 'noo_portfolio_shortcode'));
            add_filter('single_template', array($this, 'get_portfolio_single_template')); // Load custom template: https://codex.wordpress.org/Plugin_API/Filter_Reference/single_template

            $this->includes();
        }

        function portfolio_front_scripts()
        {

            wp_enqueue_style('prettyPhoto');
            wp_enqueue_style('ladda-css');
            wp_enqueue_script('prettyPhoto');
            wp_enqueue_script('ladda-spin');
            wp_enqueue_script('ladda');
            wp_enqueue_script('jquery-hoverdir');
            wp_enqueue_script('imagesloaded');
            wp_enqueue_script('infinitescroll');
            wp_enqueue_style('isotope-css');
            wp_enqueue_script('isotope-new');
            wp_enqueue_script('cbpGridPortfolio');
            wp_enqueue_script('noo-portfolio');
            wp_enqueue_script('noo-portfolio-ajax-action');
        }

        private function includes()
        {
            include_once('utils/ajax-action.php');
        }

        function noo_portfolio_shortcode($atts)
        {
            $this->portfolio_front_scripts();

            $portfolio_thumbnail = $portfolio_title = $overlay_style = $overlay_effect = $hover_dir = $show_filter = $filter_style = $columns =
            $categories_portfolio = $show_pagging = $item = $show_all_filter = $limit = $order = $orderby = $current_page = $data_section_id = '';

            extract(shortcode_atts(array(
                'portfolio_thumbnail' => 'landscape',
                'portfolio_title' => '',
                'columns' => '4',
                'overlay_style' => 'icon',
                'overlay_effect' => 'effect_1',
                'hover_dir' => 'on',
                'data_source' => '',
                'category' => '',
                'portfolio_ids' => '',
                'portfolio_tag' => '',
                'show_filter' => '',
                'show_all_filter' => 'show',
                'show_pagging' => '',
                'filter_by' => 'tag',
                'item' => '4',
                'order' => 'DESC',
                'padding' => '',
                'image_size' => '',
                'el_class' => '',
                'css_animation' => '',
                'duration' => '',
                'delay' => '',
                'current_page' => '1',
                'filter_style' => '',
                'data_section_id' => ''

            ), $atts));
            if ($item == '') {
                $offset = 0;
                $post_per_page = -1;
            } else {
                $post_per_page = $item;
                $offset = ($current_page - 1) * $item;
            }
            $overlay_align = 'hover-align-center';


            $plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
            ob_start();
            ?>
            <?php
            if ($portfolio_thumbnail == 'packery') {
                include($plugin_path . '/templates/portfolio-packery.php');
            } else {
                include($plugin_path . '/templates/portfolio-default.php');
            }

            ?>
            <?php
            $ret = ob_get_contents();

            ob_end_clean();
            return $ret;

        }

        function get_portfolio_single_template($single)
        {
            global $post;
            /* Checks for single template by post type */
            if ($post->post_type == 'noo_portfolio') {
                $plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
                $template_path = $plugin_path . '/templates/single/single-portfolio.php';
                if (file_exists($template_path)) {
                    return $template_path;
                }
            }

            return $single;
        }
    }

    new Noo_Framework_Shortcode_Portfolio();
}