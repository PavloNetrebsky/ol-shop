<?php
/**
 * Create shortcode: [noo_product]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		KENT <tuanlv@vietbrain.com> // Create Grid masonry manhnv@vietbrain.com
 * @version 	1.0
 */

if ( ! function_exists( 'shortcode_noo_product' ) ) :
	
	function shortcode_noo_product( $atts ) {
        $atts  = vc_map_get_attributes( 'noo_product', $atts );
		extract( shortcode_atts( array(
            'style_title'    => '',
			'title'          => '',
			'sub_title'      => '',
            'layout_style'   => 'slider',
            'slider_style'   => '',
			'button_link'    => '',
			'product_cat'	 => '',
            'columns'        =>  '4',
			'orderby'        => 'latest',
            'auto_slider'    => 'false',
            'slider_speed'   => '800',
            'show_navigation'   => 'false',
            'show_pagination'   => 'false',
			'posts_per_page' => 10
        ), $atts ) );

        ob_start();

        /**
         * Enqueue library
         */
        
        if ( $layout_style == 'grid' ) {
            $class_wrapper_masonry_or_slider   = 'masonry grid';
            $class_shortcode = 'noo-class-grid-shortcode';
        } else {
            $class_wrapper_masonry_or_slider   = 'grid slider';
            $class_shortcode = 'noo-class-slider-shortcode';

            //$columns = '3';
            
        }

        if ( $layout_style == 'grid' ) {
            wp_enqueue_script('imagesloaded');
            wp_enqueue_script('isotope'); 
            wp_enqueue_script( 'infinitescroll' );
            wp_enqueue_script('noo-product');  
        } else {
            wp_enqueue_style('carousel');
            wp_enqueue_script('carousel');
        }

        /**
         * Check data order
         * @var string
         */
        $order = 'DESC';
        switch ($orderby) {
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
                $orderby = 'date';
                break;
        }

        /**
         * Check paged
         */
        if( is_front_page() || is_home()) :
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
        else :
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        endif;

        /**
         * Create query
         * @var array
         */
        $args = array(
			'post_type'      => 'product',
			'orderby'        => $orderby,
			'order'          => $order,
			'paged'          => $paged,
			'posts_per_page' => $posts_per_page,
        );

        /**
         * Get list category
         */
        $cat_id         = array();
        if( isset($product_cat) && !empty($product_cat) ){
			$categories          = explode(',',$product_cat);
            foreach( $categories as $id ):
                $cat_id[] = intval($id);
            endforeach;
        }
        if( !empty($cat_id) ){
            $args['tax_query'][] = array(
                'taxonomy'  =>  'product_cat',
                'field'     =>  'term_id',
                'terms'     =>   $cat_id
            );
        }

        $id = uniqid();
        ?>

		<div class="sc-noo-product-wrap woocommerce">
            
            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
                <div class="<?php echo esc_attr($style_title) ?> noo-theme-wraptext <?php echo esc_attr($layout_style);?>">
                    <div class="wrap-title">
                    <?php if ( !empty( $title ) ) : ?>
                        <div class="noo-theme-title-bg"></div>
                        <h3 class="noo-theme-title">
                            <?php
                                $title = explode( ' ', $title );
                                $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
                                $title = implode( ' ', $title );
                            ?>
                            <?php echo $title; ?>
                        </h3>
                    <?php endif; ?>

                    <?php if ( !empty( $sub_title ) ) : ?>
                        <p class="noo-theme-sub-title">
                            <?php echo esc_html( $sub_title ); ?>
                        </p>
                    <?php endif; ?>

                    </div> <!-- /.wrap-title -->    
                </div>
            <?php endif; ?>
            <?php if($layout_style == 'grid') : ?>
            <div class="noo-filters noo-filters-product">
                    <ul data-option-key="filter">
                        <li><a href="#" class="selected" data-filter=""><?php esc_html_e('All Category', 'noo-hermosa-core')?></a></li>
                        <?php
                        if( !empty($product_cat)){
                            $cat_id = explode(',',$product_cat);
                            $args['tax_query'][] = array(
                                'taxonomy'  =>  'product_cat',
                                'field'     =>  'term_id',
                                'terms'      =>  $cat_id
                            );
                            foreach( $cat_id as $cat ):
                                if( $cat != 'all' ):
                                    $term_cat = get_term_by('id',$cat, 'product_cat');
                                    if($term_cat):
                                    ?>
                                    <li>
                                        <a data-option-value=".product_cat-<?php echo esc_attr($term_cat->slug); ?>" href="#<?php echo esc_attr($term_cat->slug); ?>"><?php echo esc_html($term_cat->name); ?></a>
                                    </li>
                                    <?php endif; ?>
                                <?php endif;
                            endforeach; ?>
                        <?php } ?>
                    </ul>
            </div>
            <?php endif;?>

			<div class="<?php echo esc_attr( $layout_style); ?>-mansory">
                <div class="noo-product-wraper" >
        			<div id="<?php echo $id; ?>"  class="noo-product-wrap-item products shop-grid columns-<?php echo esc_attr($columns );?> <?php echo esc_attr( $slider_style ); ?> <?php echo ($layout_style == 'slider' ? 'owl-carousel' : ''); ?>">
        				<?php
        	                $query = new WP_Query($args) ;
        	                if( $query->have_posts() ):
        	                    while( $query->have_posts() ): $query->the_post();
        	                        wc_get_template_part( 'content', 'product' );
        	                    endwhile;
        	                endif; wp_reset_postdata();
        				?>
                     
        			</div>

        			<?php if ( !empty( $button_link ) ) : ?>

                        <?php
                            $info_btn = vc_build_link( $button_link );
                            $targer   = !(empty( $info_btn['target'] )) ? " targer='{$info_btn['target']}'" : '';
                            echo "<div class='noo-product-button-wrap'><a class='noo-product-button' href='{$info_btn['url']}' {$targer}>{$info_btn['title']}</a></div>";
                        ?>

                    <?php endif; ?>
                </div>
            </div>

		</div><!-- /.noo-product-wrap -->
		<?php if($layout_style == 'slider') :?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
                $( '#<?php echo $id; ?>' ).owlCarousel({

                    items: <?php echo esc_attr($columns); ?>,
                    loop:true,
                   // margin:30,
                    autoplayTimeout: <?php echo esc_attr($slider_speed); ?>,
                    autoplay:<?php echo esc_attr($auto_slider); ?>,
                    nav: <?php echo esc_attr($show_navigation); ?>,
                    dots:<?php echo esc_attr($show_pagination); ?>,
                    dotsEach:<?php echo esc_attr($show_pagination); ?>,
                    rtl:false,
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
                             items:<?php echo esc_attr($columns); ?>,
                        },
                        992:{
                            items:<?php echo esc_attr($columns); ?>,
                        },
                        1200:{
                            items:<?php echo esc_attr($columns); ?>,
                        }
                    }
                });
			});
		</script>
    <?php endif; ?>

		<?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_product', 'shortcode_noo_product' );

endif;