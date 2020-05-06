<?php

add_action("wp_ajax_nopriv_nooframework_portfolio_load_more", "nooframework_portfolio_load_more");
add_action("wp_ajax_nooframework_portfolio_load_more", "nooframework_portfolio_load_more");
function nooframework_portfolio_load_more() {

    $current_page        = $_REQUEST['current_page'];
    $overlay_style       = $_REQUEST['overlay_style'];
    $overlay_effect      = $_REQUEST['overlay_effect'];
    $hover_dir           = $_REQUEST['hover_dir'];
    $portfolio_title     = $_REQUEST['portfolio_title'];
    $offset              = $_REQUEST['offset'];
    $category            = $_REQUEST['category'];
    $portfolioIds        = $_REQUEST['portfolioIds'];
    $dataSource          = $_REQUEST['data_source'];
    $posts_per_page      = $_REQUEST['postsPerPage'];
    $portfolio_thumbnail = $_REQUEST['thumbnail'];
    $portfolio_tag       = $_REQUEST['tag'];
    $column              = $_REQUEST['columns'];
    $padding             = $_REQUEST['colPadding'];
    $filter_by           = $_REQUEST['filter_by'];
    $order               = $_REQUEST['order'];
    $short_code          = sprintf('[noo_portfolio portfolio_thumbnail="%s" portfolio_title="%s" portfolio_tag="%s" show_category="" column="%s" column_masonry="%s" item="%s" show_pagging="1" overlay_style="%s" overlay_effect="%s" padding="%s" current_page="%s" order="%s" data_source="%s" category="%s" portfolio_ids ="%s" item="%s" filter_by="%s"]',$portfolio_thumbnail, $portfolio_title, $portfolio_tag, $column, $column, $posts_per_page, $overlay_style, $overlay_effect, $padding, $current_page, $order, $dataSource, $category, $portfolioIds, $posts_per_page,$filter_by);
    echo do_shortcode($short_code);
}
