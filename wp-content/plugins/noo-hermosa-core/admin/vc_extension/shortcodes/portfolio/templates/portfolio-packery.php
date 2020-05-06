<?php
//$category=($category==null)? 'all': $category;
//$portfolio_tag=($portfolio_tag==null)? 'all': $portfolio_tag;
$args = array(
    'offset'         => $offset,
    'orderby'        => 'post_date',
    'order'          => $order,
    'posts_per_page' => $post_per_page,
    'post_type'      => 'noo_portfolio',
    'post_status'    => 'publish'
);
// If not choose tag
if( $portfolio_tag == '' ) {
    $args['tax_query'] = array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'portfolio_category',
            'field'    => 'term_id',
            'terms'    => explode(',', $category),
        ),
    );
} else {
    $args['tax_query'] = array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'portfolio_category',
            'field'    => 'term_id',
            'terms'    => explode(',', $category),
        ),
        array(
            'taxonomy' => 'portfolio_tag',
            'field'    => 'term_id',
            'terms'    => explode(',', $portfolio_tag),
        ),
    );
}
$posts_array = new WP_Query($args);
wp_enqueue_script('packery-mode');
$total_post  = $posts_array->found_posts;
$col_class   = '';
$col_class   = 'hermosa-noo-md-' . $columns;
if ($data_section_id == '')
    $data_section_id = uniqid();
$paging_style = 'paging';
$hover_dir_class = 'hover-dir-' . $hover_dir;
switch ($padding) {
    case 'col-padding-5':
        $padding_width = '5';
        break;
    case 'col-padding-10':
        $padding_width = '10';
        break;
    case 'col-padding-15':
        $padding_width = '15';
        break;
    case 'col-padding-20':
        $padding_width = '20';
        break;

    default:
        $padding_width = 'false';
        break;
}

?>
<div class="portfolio-container">
    <div class="portfolio overflow-hidden <?php echo esc_attr( $paging_style) ?>" id="portfolio-<?php echo esc_attr($data_section_id) ?>">
        <!-- Portfolio Filter -->
        <?php if ($show_filter != '' ) : ?>
            <div class="portfolio-tabs clearfix <?php if($show_filter!=''){ echo $filter_by;} ?>">
                <?php
                include(plugin_dir_path(__FILE__) . '/filter/filter-' . $filter_by . '.php');
                ?>
            </div>
        <?php endif; ?>
        <!-- End Portfolio Filter -->

        <!-- Portfolio Wrapper -->
        <div class="portfolio-wrapper <?php echo sprintf('%s %s %s %s', $col_class, $padding, $portfolio_thumbnail, $hover_dir_class) ?>"
             data-section-id="<?php echo esc_attr($data_section_id); ?>"
             id="portfolio-container-<?php echo esc_attr($data_section_id); ?>"
             data-columns="<?php echo esc_attr($columns); ?>">
            <?php
            $index = 0;

            while ($posts_array->have_posts()) : $posts_array->the_post();
                $index++;
                $permalink      = get_permalink();
                $title_post     = get_the_title();
                if( $filter_by == 'category' ) {
                    $terms          = wp_get_post_terms(get_the_ID(), array('portfolio_category'));
                } else {
                    $terms          = wp_get_post_terms(get_the_ID(), array('portfolio_tag'));
                }
                $filter_name            = $filter_slug = '';
                foreach ($terms as $term) {
                    $filter_slug .= $term->slug . ' ';
                    $filter_name .= $term->name . ', ';
                }
                $filter_name = rtrim($filter_name, ', ');
                ?>


                <?php
                include(plugin_dir_path(__FILE__) . '/loop/' . $portfolio_thumbnail . '-item.php');
                ?>

            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
        <!-- End Portfolio Wrap -->

        <!-- Portfolio Paging -->
        <?php
        ?>
        <?php if ($show_pagging == '1' && $post_per_page > 0 && $total_post / $post_per_page > 1 && $total_post > ($post_per_page * $current_page)) : ?>
            <div style="clear: both"></div>
            <div class="paging" id="load-more-<?php echo esc_attr($data_section_id) ?>">
                <a href="javascript:;" class="btn  load-more ladda-button "
                   data-overlay-style   ="<?php echo esc_attr($overlay_style) ?>"
                   data-overlay-effect  ="<?php echo esc_attr($overlay_effect) ?>"
                   data-category        ="<?php echo esc_attr($category) ?>"
                   data-section-id      ="<?php echo esc_attr($data_section_id) ?>"
                   data-column          ="<?php echo esc_attr($columns); ?>"
                   data-offset          ="<?php echo esc_attr($offset) ?>"
                   data-current-page    ="<?php echo esc_attr($current_page + 1) ?>"
                   data-post-per-page   ="<?php echo esc_attr($post_per_page) ?>"
                   data-show-paging     ="<?php echo esc_attr($show_pagging) ?>"
                   data-padding         ="<?php echo esc_attr($padding) ?>"
                   data-thumbnail       ="<?php echo esc_attr($portfolio_thumbnail); ?>"
                   data-tag             ="<?php echo esc_attr($portfolio_tag); ?>"
                   data-filter-by       ="<?php echo esc_attr($filter_by); ?>"
                   data-hover-dir       ="<?php echo esc_attr($hover_dir); ?>"
                   data-portfolio-title ="<?php echo esc_attr($portfolio_title); ?>"
                   data-order           ="<?php echo esc_attr($order) ?>"
                   data-style           ="zoom-out"
                   data-spinner-color   ="#fff"
                >
                    <?php esc_html_e('Load more', 'noo-hermosa') ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript">
    var column = <?php echo $columns; ?>;
    (function ($) {
        "use strict";
        $(document).ready(function () {
            var $tab_container = jQuery('#portfolio-<?php echo esc_attr($data_section_id); ?>');
            $('.portfolio-tabs .isotope-portfolio', $tab_container).off();
            $('.portfolio-tabs .isotope-portfolio', $tab_container).click(function () {
                $('.portfolio-tabs .isotope-portfolio', $tab_container).removeClass('active');
                $('.portfolio-tabs li', $tab_container).removeClass('active');
                $(this).addClass('active');
                $(this).parent().addClass('active');
                var dataSectionId = $(this).attr('data-section-id');
                var filter        = $(this).attr('data-filter');
                var $container    = jQuery('div[data-section-id="' + dataSectionId + '"]').isotope({ filter: filter});
                $container.imagesLoaded(function () {
                    $container.isotope('layout');
                });
            });

            var $container = jQuery('div[data-section-id="<?php echo esc_attr($data_section_id); ?>"]');
            $container.imagesLoaded(function () {
                $container.isotope({
                    itemSelector: '.portfolio-item',
                    layoutMode: 'packery', // packery mode
                    packery: {
                        horizontal: true,
                    }
                }).isotope('layout');
            });

            $(window).resize( function() {
                PortfolioAjaxAction.fixPackeryPadding();
            });
        });

        $(document).ready(function () {
            <?php if ('on' == $hover_dir) : ?>
            $('.portfolio-item.hover-dir > div.entry-thumbnail').hoverdir();
            <?php endif; ?>

            PortfolioAjaxAction.init('<?php echo esc_url(get_site_url() . '/wp-admin/admin-ajax.php') ?>', '<?php echo esc_attr($data_section_id)?>');
        })

    })(jQuery);
</script>
