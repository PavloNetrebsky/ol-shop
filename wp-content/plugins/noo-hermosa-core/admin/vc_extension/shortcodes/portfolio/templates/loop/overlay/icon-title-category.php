<?php
$cat = '';
foreach ( $terms as $term ){
    $cat .= $term->name.', ';
}
$cat = rtrim($cat,', ');

$disable_link = false;
if( noo_hermosa_get_option('noo_show_portfolio_link')) {
    $disable_link = true;
}

// Lightbox render
switch ($portfolio_type) {
    case 'image':
        $data_rel = $url_origin;
        break;
    case 'gallery':
        $data_rel = $url_origin;
        break;
    case 'link':
        $data_rel = get_post_meta(get_the_ID(), 'noo_data_format_link_url', true);
        break;
    case 'video':
        $data_rel = get_post_meta(get_the_ID(), 'noo_data_format_video', true);
        break;
    
    default:
        $data_rel = $url_origin;
        break;
}

?>
<div class="entry-thumbnail icon-title-category <?php echo $overlay_effect; ?>">
    <img width="<?php echo esc_attr($width) ?>" height="<?php echo esc_attr($height) ?>" src="<?php echo esc_url($thumbnail_url) ?>" alt="<?php echo get_the_title() ?>"/>
    <div class="entry-thumbnail-hover p-bg-rgba-color">
        <div class="entry-hover-wrapper">
            <div class="entry-hover-inner">
                <?php if ($disable_link) : ?>
                    <i class="fa fa-search fc-white"></i>
                <?php else : ?>
                    <?php 

                    switch ($portfolio_type) {
                        case 'image':
                            echo sprintf('<a href="%s" data-rel="prettyPhoto[pp_gal_%u]" title="%s">', esc_url($data_rel), esc_attr(get_the_ID()), esc_attr(get_the_title()) );
                            break;
                        case 'gallery':
                            echo sprintf('<a href="%s" data-rel="prettyPhoto[pp_gal_%u]" title="%s">', esc_url($data_rel), esc_attr(get_the_ID()), esc_attr(get_the_title()) );
                            break;
                        case 'link':
                            echo sprintf('<a href="%s" title="%s">', esc_url($data_rel), esc_attr(get_the_title()) );
                            break;
                        case 'video':
                            echo sprintf('<a href="%s" data-rel="prettyPhoto" title="%s">', esc_url($data_rel), esc_attr(get_the_title()) );
                            break;
                        
                        default:
                            echo sprintf('<a href="%s" data-rel="prettyPhoto[pp_gal_%u]" title="%s">', $data_rel, get_the_ID(), get_the_title() );
                            break;
                    }
                    ?>
                        <?php
                            switch ($portfolio_type) {
                                case 'image':
                                    echo '<i class="fa fa-search fc-white"></i>';
                                    break;
                                case 'gallery':
                                    echo '<i class="fa fa-expand fc-white"></i>';
                                    break;
                                case 'link':
                                    echo '<i class="fa fa-link fc-white"></i>';
                                    break;
                                case 'video':
                                    echo '<i class="fa fa-play-circle fc-white"></i>';
                                    break;
                                 
                                default:
                                    echo '<i class="fa fa-search fc-white"></i>';
                                    break;
                            } 
                            
                        ?>
                    </a>
                    <a href="<?php echo get_permalink(get_the_ID()); ?>" class="overlay-title"><div class="title fc-white"><?php the_title(); ?></div></a>
                    <span class="category"><?php echo wp_kses_post($cat); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
