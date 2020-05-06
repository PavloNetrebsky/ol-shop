<?php
if( !function_exists('noo_shortcode_blog_slider')){

	function noo_shortcode_blog_slider($atts){
		$atts  = vc_map_get_attributes( 'noo_blog_slider', $atts );
		extract(shortcode_atts(array(
			'style_title'		=>	'style_1',
			'title'				=>	'',
			'sub_title'			=>	'',
			'style'				=>  'style-1',
			'columns'			=>	'3',
			'posts_per_page'	=>  '6',
			'excerpt_length'	=>	'20',
			'autoplay'          => 'true',
			'slider_speed'		=> '800',
			'show_navigation'   => 'false',
			'show_pagination'   => 'false',
			'class'          	=> '',
			'id'            	=> '',
            'categories'        => '',
		), $atts));
		wp_enqueue_style('noo-carousel');
		wp_enqueue_script('noo-carousel');
		//$format 		= get_post_format( get_the_ID() );
		$limit_excerpt = !isset( $limit_excerpt ) ? 20 : $limit_excerpt;

		$id = ( $id  != '') ? 'id ="'.esc_attr( $id ) .'"' : '';
		$class = ( $class != '') ? 'class ="'.esc_attr( $class ) .'"' : '' ;

		$html = '';
		$cats=($categories== null)? 'all': $categories;
		$r = new WP_Query(array(
			'posts_per_page'	=>		$posts_per_page,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
            'tax_query'           =>array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'term_id',
                        'terms'    => explode(',', $cats),
                    ),

            ),
		));
		ob_start();
		$id = uniqid();


		if( $r -> have_posts()) : ?>
			
			<?php if( !empty( $title ) || !empty( $sub_title )) : ?>
				<!-- Section Title  -->
				<div class="shortcode-noo-recent-new">
					<div class="noo-theme-wraptext <?php echo esc_attr( $style_title ) ?>">
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
				</div>
			<?php endif; ?>
			<?php

            ?>
			<div class="recent-new-wrap">
				<div id="<?php echo $id; ?>" class="recent-new-content owl-carousel colums-<?php echo esc_attr($columns); ?> <?php echo esc_attr( $style ); ?> " >
				 	<?php $i = 0 ; ?>
				 	<?php while( $r -> have_posts()) : $r -> the_post();
				 		global $post ;?>
				 		<div classs="loop-item-wrap ">
				 			<div class="blog-item-header">
                                <?php
	                			$format     = get_post_format();
                                $count_like                   = noo_hermosa_get_post_meta( get_the_ID(), 'noo_like' );
	                			$comments_count               = get_comments_number(get_the_ID());

                                switch($format):
                                    case'audio':
                                        if( function_exists('noo_hermosa_featured_audio') ):
                                            noo_hermosa_featured_audio();
                                        else:
                                            echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'large').'</a>';
                                        endif;
                                        break;
                                    case'gallery':
                                        if( function_exists('noo_hermosa_featured_gallery') ):
                                            noo_hermosa_featured_gallery();
                                        else:
                                            echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'large').'</a>';
                                        endif;
                                        break;
                                        break;
                                    case'video':
                                        if( function_exists('noo_hermosa_featured_video') ):
                                            noo_hermosa_featured_video();
                                        else:
                                            echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'large').'</a>';
                                        endif;
                                        break;
                                    default:
                                        echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail(get_the_ID(),'large').'</a>';
                                        break;
                                endswitch;
                                ?>
                            </div>
                            <div class="noo-blog-meta <?php echo esc_attr($style ); ?>" >
                                <?php
                                $cats_list = get_the_category();
                                if ( $cats_list ) {
                                    echo '<span class="cats-links"><a href="'.esc_url(get_category_link($cats_list[0]->term_id)).'">'.esc_html($cats_list[0]->name).'</a></span>';
                                }
                                ?>
                                <?php
                                    echo '<span class="comment-meta">';
                                        echo '<span><i class="fa fa-heart-o" aria-hidden="true"></i>'.intval($count_like).'</span>';
                                        echo '<span><i class="ion-chatbubble-working"></i>'.esc_html($comments_count).'</span>';
                                    echo '</span>';
                                ?>
                            </div>
                            <div class="noo-blog-entry">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <?php if( $style == 'style-2') : ?>
									<div class="buy-post">
										<?php echo esc_html__('Buy:','noo-hermosa-core'); ?> <?php the_author_posts_link(); ?>
										<span><?php echo get_the_date('F j, Y'); ?></span>
                                	</div>
                                <?php endif; ?>
                                <p class="blog_excerpt">
                                    <?php
                                    $excerpt = get_the_excerpt();
                                    $trim_ex = wp_trim_words($excerpt,esc_attr($limit_excerpt),'...');
                                    echo esc_html($trim_ex);
                                    ?>
                                </p>
                            </div><!--end .noo-blog-entry-->
                            <?php if( $style == 'style-1'): ?>
                            <div class="noo-blog-footer">
                                <span><?php echo get_the_date('F jS, Y'); ?></span>
                                <a class="noo-readmore" href="<?php the_permalink() ?>"><?php esc_html_e('Read More ','noo-hermosa-core'); ?><i class="fa fa-angle-double-right" aria-hidden="true"></i></a>
                            </div><!--end .noo-blog-footer-->
                        	<?php endif; ?>
				 		</div>
				 	<?php endwhile; ?>
			 	</div>
			</div>
			<script type="text/javascript">
                jQuery(document).ready(function ($) {
	               	jQuery('#<?php echo $id; ?>').owlCarousel({
	               		items: <?php echo esc_attr($columns); ?>,
					    loop:true,
					    margin:30,
					    autoplayTimeout: <?php echo esc_attr($slider_speed); ?>,
					    autoplay:<?php echo esc_attr($autoplay); ?>,
					    nav: <?php echo esc_attr($show_navigation); ?>,
					    dots:<?php echo esc_attr($show_pagination); ?>,
					    dotsEach:<?php echo esc_attr($show_pagination); ?>,
					    rtl: false,
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
                                items:2,
                            },
                            992:{
                                items:3,
                            },
                            1200:{
                                items:<?php echo esc_attr($columns); ?>,
                            }
                        }
					})
                });
            </script> 
		<?php endif;
			$html  .= ob_get_clean();
			wp_reset_query();
			return $html;
	}
	add_shortcode('noo_blog_slider','noo_shortcode_blog_slider');
}