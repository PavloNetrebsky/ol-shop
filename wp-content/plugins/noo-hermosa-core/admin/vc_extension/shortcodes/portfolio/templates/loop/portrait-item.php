<?php
$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
$arrImages = wp_get_attachment_image_src($post_thumbnail_id, 'full');

$portfolio_type = get_post_meta(get_the_ID(), 'noo_media_type', true);
$portfolio_thumbnail_size = get_post_meta(get_the_ID(), 'noo_thumbnail_size', true);

$width = 500;
$height = 700;


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

<div class="portfolio-item hover-dir <?php echo sprintf('%s %s',$filter_slug,$overlay_align) ?>">
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