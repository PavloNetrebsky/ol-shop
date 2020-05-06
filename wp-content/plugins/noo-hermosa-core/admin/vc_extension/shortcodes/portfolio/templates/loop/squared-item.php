<?php
$post_thumbnail_id = get_post_thumbnail_id(get_the_ID());
$arrImages = wp_get_attachment_image_src($post_thumbnail_id, 'full');


$portfolio_type = get_post_meta(get_the_ID(), 'noo_media_type', true);
$portfolio_thumbnail_size = get_post_meta(get_the_ID(), 'noo_thumbnail_size', true);

// shortcode settings
$width = 400;
$height = 400;

?>

<div class="portfolio-item hover-dir <?php echo sprintf('%s %s',$filter_slug,$overlay_align) ?>">
    <!-- Title top -->
    	<?php if(isset($portfolio_title) && $portfolio_title == 'top') : ?>
			<div class="portfolio-title-wrap <?php echo $portfolio_title; ?>">
		        <a href="<?php echo get_permalink(get_the_ID()); ?>" class="portfolio-title"><?php the_title(); ?></a>
		        <div class="portfolio-tag"><?php echo esc_html($class_term).' '; ?></div>
		    </div>
    	<?php endif; ?>
    	<?php
    		//the_post_thumbnail('large');

			$thumbnail_url = '';
			if (count($arrImages) > 0) {
		        $resize = matthewruddy_image_resize($arrImages[0], $width, $height);
		        if ($resize != null && is_array($resize))
		            $thumbnail_url = $resize['url'];
		    }
		    $url_origin = $arrImages[0];
        include(plugin_dir_path(__FILE__) . '/overlay/' . $overlay_style . '.php');
    	?>
    <!-- End Title top -->

    <!-- Title bottom -->
		<?php if( isset($portfolio_title) && $portfolio_title == 'bottom' ) : ?>
		    <div class="portfolio-title-wrap <?php echo $portfolio_title; ?>">
		        <a href="<?php echo get_permalink(get_the_ID()); ?>" class="portfolio-title"><?php the_title(); ?></a>
		        <div class="portfolio-term"><?php echo esc_html($class_term).' '; ?></div>
		    </div>
	    <?php endif; ?>
	<!-- End Title bottom -->

	<!-- @TODO: process media type -->
    <?php
    include_once(plugin_dir_path( __FILE__ ).'gallery.php');
    ?>
</div>

