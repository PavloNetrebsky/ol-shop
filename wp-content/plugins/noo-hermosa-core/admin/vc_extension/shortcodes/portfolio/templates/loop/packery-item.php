<?php
/**
 * Created by PhpStorm.
 * User: cuong
 * Date: 9/10/2018
 * Time: 10:31 AM
 */


$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
$arrImages = wp_get_attachment_image_src($post_thumbnail_id, 'full');
$portfolio_type = get_post_meta(get_the_ID(), 'noo_media_type', true);
$portfolio_thumbnail_size = get_post_meta(get_the_ID(), 'noo_thumbnail_size', true);

if( $portfolio_thumbnail_size == 'small_squared' ) {
    $width = 400;
    $height = 400;
} elseif( $portfolio_thumbnail_size == 'big_squared' ) {
    $width = 800;
    $height = 800;
} elseif( $portfolio_thumbnail_size == 'landscape' ) {
    $width = 800;
    $height = 400;
} elseif( $portfolio_thumbnail_size == 'portrait' ) {
    $width = 400;
    $height = 800;
} elseif( $portfolio_thumbnail_size == '' ) {
    $width = 500;
    $height = 500;
}

// Get tag or category for title
$post_id    = get_the_ID();
$p_categories = get_the_terms($post_id, 'portfolio_category');
$cat         = '';
$arrCatId    = array();
if($p_categories) {
    foreach($p_categories as $p_category) {
        $cat .= '<span>'.$p_category->name.'</span>, ';
        $arrCatId[count($arrCatId)] = $p_category->term_id;
    }
    $cat = trim($cat, ', ');
}

// Get portfolio single tags
$tags     = get_the_terms($post_id, 'portfolio_tag');
$tag      = '';
$arrTagId = array();
if($tags) {
    foreach($tags as $t) {
        $tag .= '<span>'.$t->name.'</span>, ';
        $arrTagId[count($arrTagId)] = $t->term_id;
    }
    $tag = trim($tag, ', ');
}

?>

<div class="portfolio-item hover-dir <?php echo sprintf('%s %s %s',$filter_slug,$overlay_align,$portfolio_thumbnail_size) ?>">
    <!-- Title top -->
    <?php if( isset($portfolio_title) && $portfolio_title == 'top' ) : ?>
    <div class="portfolio-title-wrap <?php echo $portfolio_title; ?>">
        <a href="<?php echo get_permalink(get_the_ID()); ?>" class="portfolio-title"><?php the_title(); ?></a>
        <div class="portfolio-tag"><?php echo wp_kses_post($tag); ?></div>
    </div>
    <?php endif; ?>

    <?php
    $thumbnail_url = '';
    if (count($arrImages) > 0) {
        $resize = matthewruddy_image_resize($arrImages[0], $width, $height);
        if ($resize != null && is_array($resize))
            $thumbnail_url = $resize['url'];
    }

    $url_origin = $arrImages[0];
    if ($overlay_style == 'left-title-excerpt-link')
        $overlay_style = 'title-excerpt-link';
    include(plugin_dir_path(__FILE__) . '/overlay/' . $overlay_style . '.php');
    ?>

    <!-- Title bottom -->
    <?php if( isset($portfolio_title) && $portfolio_title == 'bottom' ) : ?>
    <div class="portfolio-title-wrap <?php echo $portfolio_title; ?>">
        <a href="<?php echo get_permalink(get_the_ID()); ?>" class="portfolio-title"><?php the_title(); ?></a>
        <div class="portfolio-tag"><?php echo wp_kses_post($tag); ?></div>
    </div>
    <?php endif; ?>

    <!-- @TODO: process media type -->
    <?php
    include(plugin_dir_path(__FILE__) . '/gallery.php');
    ?>

</div>
