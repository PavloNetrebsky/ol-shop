<?php

if( !function_exists('noo_shortcode_blog') ){

    function noo_shortcode_blog($atts){
        extract(shortcode_atts(array(
            'title'             =>  '',
            'sub_title'         =>  '',
            'style'             =>  'style_1',
            'type_query'        =>  'cate',
            'categories'        =>  '',
            'tags'              =>  '',
            'include'           =>  '',
            'columns'           =>  3,
            'orderby'           =>  'latest',
            'posts_per_page'    =>  10,
            'limit_excerpt'     =>   20,
            'box_shadow'        =>  'yes_shadow',
            'style_button'      =>  'hide_button',
            'custom_link'       =>  ''
        ),$atts));
        ob_start();
        wp_enqueue_script('imagesloaded');
        wp_enqueue_script( 'infinitescroll' );
        wp_enqueue_script('isotope');
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

        if( is_front_page() || is_home()) {
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
        } else {
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        }

        $args = array(
            'orderby'           =>   $orderby,
            'order'             =>   $order,
            'paged'             =>   $paged,
            'posts_per_page'    =>   $posts_per_page,
        );

        // get post by post category
        if($type_query == 'cate'){
            $args['cat']   =  $categories ;
        }

        // get post by tags
        if($type_query == 'tag'){
            if($tags != 'all'):
                $tag_id = explode (',' , $tags);
                $args['tag__in'] = $tag_id;
            endif;
        }

        // get post by post id
        if($type_query == 'post_id'){
            $posts_var = '';
            if ( isset($include) && !empty($include) ){
                $posts_var = explode (',' , $include);
            }
            $args['post__in'] = $posts_var;
        }
        ?>
        <div class="noo_sh_blog_wraper">
            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
                <!-- Section title -->
                <div class="noo-theme-wraptext">
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
            <?php
            $query = new WP_Query( $args );
            if( $query->have_posts() ):
            ?>
            <div class="noo-blog-content">
                <?php
                    while( $query->have_posts() ):
                        $query->the_post();

                        $format     = get_post_format();
                        $classes    = '';

                        if( $columns == 4 ){

                            $classes .= ' noo-md-3 noo-sm-6';

                        }elseif( $columns == 3 ){

                            $classes .= ' noo-md-4 noo-sm-6';

                        }elseif( $columns == 2 ){

                            $classes .= ' noo-md-6 noo-sm-6';

                        }else{

                            $classes .= ' noo-md-12';

                        }
                        $classes     .= ' '.$style;
                        $classes     .= ' '.$box_shadow;
                        $count_like                   = noo_hermosa_get_post_meta( get_the_ID(), 'noo_like' );
                        $comments_count               = get_comments_number(get_the_ID());
                ?>
                            <div id="post-<?php the_ID(); ?>" class="masonry-item <?php echo esc_attr($classes); ?>">
                                <div class="our-blog-item">
                                    <div class="blog-item-header">
                                        <?php
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
                                    <?php if( $style == 'style_1' ): ?>
                                        <div class="noo-blog-meta">
                                            <?php
                                            $cats_list = get_the_category();
                                            if ( $cats_list ) {
                                                echo '<span class="cats-links"><a href="'.esc_url(get_category_link($cats_list[0]->term_id)).'">'.esc_html($cats_list[0]->name).'</a></span>';
                                            }
                                            ?>
                                            <?php
                                                echo '<span class="comment-meta">';
                                                    echo '<span><i class="ion-heart"></i>'.intval($count_like).'</span>';
                                                    echo '<span><i class="ion-chatbubble-working"></i>'.esc_html($comments_count).'</span>';
                                                echo '</span>';
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="noo-blog-entry">
                                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        <p class="blog_excerpt">
                                            <?php
                                            $excerpt = get_the_excerpt();
                                            $trim_ex = wp_trim_words($excerpt,esc_attr($limit_excerpt),'...');
                                            echo esc_html($trim_ex);
                                            ?>
                                        </p>
                                    </div><!--end .noo-blog-entry-->
                                    <div class="noo-blog-footer">
                                        <?php if( $style == 'style_1' ): ?><span><?php echo get_the_date(); ?></span><?php endif; ?>
                                        <a class="noo-readmore" href="<?php the_permalink() ?>"><?php esc_html_e('Learn More','noo-hermosa-core'); ?></a>
                                        <?php
                                            if( $style == 'style_2' ):
                                                echo '<span class="comment-meta">';
                                                echo '<span><i class="ion-heart"></i>'.intval($count_like).'</span>';
                                                echo '<span><i class="ion-chatbubble-working"></i>'.esc_html($comments_count).'</span>';
                                                echo '</span>';
                                            endif;
                                        ?>
                                    </div><!--end .noo-blog-footer-->
                                </div><!--end .our-blog-item-->
                            </div>
                <?php endwhile; wp_reset_postdata();   ?>
            </div><!--end .noo-blog-content-->

            <?php if( $style_button != 'hide_button' ): ?>

                <div class="blog-pagination">
                    <?php if( $style_button == 'infini' ): ?>
                    <div class="noo-load-image"></div>
                    <?php
                    if( function_exists('noo_hermosa_pagination_normal') ):
                        noo_hermosa_pagination_normal(array(),$query);
                    endif;
                    ?>
                    <?php else: ?>
                        <?php
                        if( isset( $custom_link ) && !empty( $custom_link )){
                            $link = vc_build_link( $custom_link );
                            ?>
                            <a class="custom_link" href="<?php echo esc_url($link['url']) ?>" <?php if( isset($link['target']) && !empty( $link['target'] ) ): ?>target="_blank" <?php endif; ?>><?php echo esc_html($link['title']) ?></a>
                        <?php
                        }
                        ?>
                    <?php endif; ?>
                </div><!--end .blog-pagination-->

            <?php endif; ?>

            <?php   endif; ?>
        </div>
        <?php
        $noo_shblog = ob_get_contents();
        ob_end_clean();
        return $noo_shblog;
    }
    add_shortcode('noo_blog','noo_shortcode_blog');

}
