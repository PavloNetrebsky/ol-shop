<?php
wp_enqueue_style( 'prettyPhoto' );
wp_enqueue_script( 'prettyPhoto' );

$index        = 0;
$column       = '';
$image_size   = '600x400';
$show_pagging = '2';
$item         = 4;
$args = array(
    'post__not_in'           => array($post_id),
    'posts_per_page'         => -1,
    'orderby'                => 'rand',
    'post_type'              => 'noo_portfolio',
    'portfolio_category__in' => $arrCatId,
    'post_status'            => 'publish'
);
$posts_array = new WP_Query( $args );
$total_post = $posts_array->found_posts;
$data_plugin_options = $owl_carousel_class = '';
$column = ( noo_hermosa_get_option('noo_portfolio_related_column')) ? noo_hermosa_get_option('noo_portfolio_related_column')  : '4';
if ($total_post / $item > 1) {
    $data_plugin_options = 'data-plugin-options='. $column;
    $owl_carousel_class = 'owl-carousel';
}


$overlay_style = 'icon';
$columns = 'hermosa-noo-md-4';
if(noo_hermosa_get_option('noo_portfolio_related_column'))
    $overlay_style = noo_hermosa_get_option('noo_portfolio_related_overlay');
if(noo_hermosa_get_option('noo_portfolio_related_column'))
    $columns = 'hermosa-noo-md-'.noo_hermosa_get_option('noo_portfolio_related_column');

// $layout = 'title';
$layout = 'default';
if(noo_hermosa_get_option('noo_portfolio_related_style'))
    $layout = noo_hermosa_get_option('noo_portfolio_related_style');
$overlay_effect = 'effect_1';
if(noo_hermosa_get_option('noo_portfolio_related_effect'))
    $overlay_effect=noo_hermosa_get_option('noo_portfolio_related_effect');
if ($overlay_style == 'left-title-excerpt-link')
    $overlay_align = 'hover-align-left';
else
    $overlay_align = 'hover-align-center';

?>

<div class="noo-container">
    <div class="portfolio-related-wrap">
        <div class="heading-wrap border-primary-color">
            <nav class="post-navigation">
                <div class="nav-links">
                    <?php
                    previous_post_link('<div class="nav-previous">%link</div>', _x('<div class="post-navigation-left"><i class="post-navigation-icon fa fa-long-arrow-left"></i> </div> <div class="post-navigation-content"> <div class="post-navigation-title">%title </div> </div> ', 'Previous post link', 'noo-hermosa'));
                    echo '<i class="fa fa-th-large"></i>';
                    next_post_link('<div class="nav-next">%link</div>', _x('<div class="post-navigation-content"> <div class="post-navigation-title">%title</div></div> <div class="post-navigation-right"><i class="post-navigation-icon fa fa-long-arrow-right"></i> </div>', 'Next post link', 'noo-hermosa'));
                    ?>
                </div>
                <!-- .nav-links -->
            </nav><!-- .navigation -->
            <div class="heading s-font">
                <?php echo esc_html__('Related Projects','noo-hermosa'); ?>
                <div class="heading-icon">
                    <i class="fa fa-circle-o"></i>
                </div>
            </div>
        </div>
        <div class="portfolio-related portfolio-wrapper <?php echo sprintf('%s %s',$columns, $owl_carousel_class)?> " <?php echo wp_kses_post($data_plugin_options) ?> >
            <?php
            while ( $posts_array->have_posts() ) : $posts_array->the_post();
                $index++;
                $permalink   = get_permalink();
                $title_post  = get_the_title();
                $terms       = wp_get_post_terms( get_the_ID(), array( 'portfolio_category'));
                $filter_name = $filter_slug = '';
                foreach ( $terms as $term ){
                    $filter_slug .= preg_replace('/\s+/', '', $term->name) .' ';
                    $filter_name .= $term->name.', ';
                }
                $filter_name = rtrim($filter_name,', ');

                ?>
                <?php include(WP_PLUGIN_DIR.'/noo-hermosa-core/admin/vc_extension/shortcodes/portfolio/templates/loop/'.$layout.'-item.php');
                ?>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){
        var $window = $(window),
            $body = $('body'),
            isRTL = $body.hasClass('rtl') ? true : false,
            deviceAgent = navigator.userAgent.toLowerCase();
        $('.owl-carousel').each(function () {
            var slider = $(this);
            var columns = slider.data("plugin-options");
            var configs = {
                rtl: isRTL ? isRTL : false,
                items: columns,
                margin: 0,
                loop: true,
                nav:true,
                pagination:false,
                autoplay: true,
                autoPlayHoverPause: true,
                responsiveClass: true,
                responsive:{
                    0:{
                        items:1,
                    },
                    320:{
                        items:1,
                    },
                    480:{
                        items:1,
                    },
                    568:{
                        items:1,
                    },
                    768:{
                        items:columns,
                    },
                    992:{
                        items:columns,
                    },
                    1200:{
                        items:columns,
                    }
                }
            };
            slider.owlCarousel(configs);
        });

    });
    jQuery(document).ready(function ($) {
        var prettyPhoto = $("a[data-rel^='prettyPhoto']");
        if(prettyPhoto.length > 0){
            prettyPhoto.prettyPhoto({
                hook:'data-rel',
                social_tools:'',
                animation_speed:'normal',
                theme:'light_square'
            });
        }
    });
</script>


