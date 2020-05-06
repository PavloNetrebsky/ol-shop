<?php
 if( !function_exists('noo_shortcode_gallery') ){

     function noo_shortcode_gallery($atts){
         extract( shortcode_atts( array(
            'style_title'      => 'style_title-1',
            'gallery_style'    => 'style-1',
            'filters_gallery'  => 'show',
            'show_all_filter'    => 'hide',
            'title'            => '',
            'sub_title'        => '',
            'categories'       =>  '',
            'columns'          => '3',
            'order'            => 'desc',
            'orderby'          => 'date',
            'limit'            => 10,
         ), $atts ) );

         ob_start();
         wp_enqueue_script('imagesloaded');
         wp_enqueue_script( 'infinitescroll' );
         wp_enqueue_script('isotope');
         wp_enqueue_script('cbpGridGallery');
         wp_enqueue_script('noo-gallery');
         ?>
         <div class="shortcode-gallery-wrap">
            <?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
                 <!-- Section title -->
                <div class="shortcode-title-gallery">
                    <div class="<?php echo esc_attr( $style_title ); ?> noo-theme-wraptext">
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
            <div class="noo-filters noo-filters-gallery" style="display:<?php echo esc_attr( $filters_gallery); ?>">
                     <ul data-option-key="filter">
                         <?php if( $show_all_filter == 'show' ) : ?>
                            <li><a href="#" class="selected" data-filter=""><?php esc_html_e('All Category', 'noo-hermosa-core')?></a></li>
                         <?php endif;?>
                        <?php

                            if( $show_all_filter == 'hide' ) {
                                $term_gallery  = array();
                                $gallery_cat = array();
                                if( ! empty($categories) ) {
                                    $gallery_cat = explode(',', $categories);
                                    foreach( $gallery_cat as $cat ):
                                            $term_cat = get_term_by('id',$cat, 'gallery_category');
                                            if($term_cat):
                                            ?>
                                            <li>
                                                 <a data-option-value=".<?php echo esc_attr($term_cat->slug); ?>" href="#<?php echo esc_attr($term_cat->slug); ?>"><?php echo esc_html($term_cat->name); ?></a>
                                             </li>
                                             <?php endif;
                                    endforeach; 
                                } else {
                                    $taxonomies = array( 
                                        'product_cat',
                                    );
                                    $args = array(
                                        'hide_empty' => 0,
                                        'order'    => $order,
                                        'orderby'    => $orderby
                                    );
                                    $gallery_categories = get_terms( $taxonomies, $args );
                                    if ( is_array($gallery_categories) ) {
                                        foreach ( $gallery_categories as $cat ) {
                                            $gallery_cat[] = $cat->slug;
                                        }
                                    }
                                }
                                  /* Loop get $per_page noo_gallery each category */
                                foreach($gallery_cat as $key => $value){
                                    $tmp_args = array(
                                        'post_type'      => 'noo_gallery',
                                        'post_status'    => 'publish',
                                        'order'    => $order,
                                        'orderby'    => $orderby,
                                        'posts_per_page' => $limit,
                                        'tax_query'      => array(
                                            array(
                                                'taxonomy' => 'gallery_category',
                                                'terms'    =>  $value,
                                                'field'    => 'id',
                                                'operator' => 'IN'
                                            )
                                        )
                                    );
                                    $term_gallery[$key] = new WP_Query($tmp_args);
                                }
                                 /* Push above noo_gallery into array() */
                                $ids_product = array();
                                foreach ($term_gallery as $key => $val) {
                                    foreach ($val->posts as $k => $v) {
                                        $ids_product[] = $v->ID;
                                    }
                                }

                                $args = array(
                                    'post_type'      => 'noo_gallery',
                                    'post_status'    => 'publish',
                                    'order'    => $order,
                                    'orderby'    => $orderby,
                                    'posts_per_page' => $limit*count($gallery_cat),
                                    'post__in'       => $ids_product
                                );
                            }else {
                                $args = array(
                                    'post_type'           => 'noo_gallery',
                                    'post_status'         => 'publish',
                                    'ignore_sticky_posts' => 1,
                                    'order'    => $order,
                                    'orderby'    => $orderby,
                                    'posts_per_page'      => $limit,
                                );

                                if ( ! empty($categories) ) {
                                    $gallery_cat = explode(',', $categories);
                                    foreach( $gallery_cat as $cat ):
                                            $term_cat = get_term_by('id',$cat, 'gallery_category');
                                            if($term_cat):
                                            ?>
                                            <li>
                                                 <a data-option-value=".<?php echo esc_attr($term_cat->slug); ?>" href="#<?php echo esc_attr($term_cat->slug); ?>"><?php echo esc_html($term_cat->name); ?></a>
                                             </li>
                                             <?php endif;
                                    endforeach; 
                                    $args['tax_query'] = array(
                                        array(
                                            'taxonomy' => 'gallery_category',
                                            'terms'    => array_map('sanitize_title', $gallery_cat),
                                            'field'    => 'id',
                                            'operator' => 'IN'
                                        )
                                    );
                                }else{
                                    $gallery_cat = get_terms('gallery_category');
                                    foreach( $gallery_cat as $cat ):
                                            $term_cat = get_term_by('id',$cat, 'gallery_category');
                                            if($term_cat):
                                            ?>
                                            <li>
                                                 <a data-option-value=".<?php echo esc_attr($term_cat->slug); ?>" href="#<?php echo esc_attr($term_cat->slug); ?>"><?php echo esc_html($term_cat->name); ?></a>
                                             </li>
                                             <?php endif;
                                    endforeach;
                                }
                            }
                        ?>

                     </ul>
             </div>
            
             <!-- Section content -->
             <?php
             $rand_id = rand(0,1000);
             $query = new WP_Query( $args );

             if( $query->have_posts() ):
                echo '<div id="grid-gallery" class="grid-gallery">';
                 echo '<section class="grid-wrap"><ul class="grid noo-gallery-wraper">';
                 while( $query->have_posts() ):
                     $query->the_post();
                     $cats = get_the_terms(get_the_ID(),'gallery_category');

                     $class_term = '';
                     if( isset($cats) && !empty($cats) ){
                         foreach($cats as $term):
                             $class_term .= ' '.$term->slug;
                         endforeach;
                     }

                     $feafured = get_post_meta(get_the_ID(),'_noo_gallery_feafured',true);
                     if( $feafured == 1 ){
                         $columns2 = $columns*2;
                     }else{
                         $columns2 = $columns;
                     }
             ?>
                    
                    <li class="noo-gallery-item noo-sm-6 noo-md-<?php echo esc_attr($columns2).' '.esc_attr($class_term); ?>">

                        <div class="noo-gallery-inner <?php echo esc_attr($gallery_style); ?>">
                            <div class="noo-gallery-inner-content">
                                <?php the_post_thumbnail('large'); ?>
                                <div class="noo-gallery-content">
                                    <div class="noo-gallery-center">
                                        <h3><?php the_title(); ?></h3>
                                    <span>
                                    <?php echo esc_html($class_term).' '; ?>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
            <?php
                 endwhile; wp_reset_postdata();
                 echo '</ul></section>'; ?>


                 <section class="slideshow"><ul>
             <?php

                 while( $query->have_posts() ):
                     $query->the_post();
                     $cats = get_the_terms(get_the_ID(),'gallery_category');

                     ?>
                     <li>
                         <?php the_post_thumbnail('full'); ?>
                     </li>
                 <?php
                 endwhile; wp_reset_postdata();
                 ?>
                 </ul>
                 <nav>
                    <span class="nav-prev"></span>
                    <span class="nav-next"></span>
                    <span class="nav-close"></span>
                </nav>
                </section>
                 <?php

                 echo '</div>';
             endif;
             ?>
         
             <script>
                 jQuery(document).ready(function(){
                      new CBPGridGallery(document.getElementById('grid-gallery'));
                 });
             </script>
         </div>
        <?php
         $gallery = ob_get_contents();
         ob_end_clean();
         return $gallery;
     }
     add_shortcode('noo_gallery','noo_shortcode_gallery');

 }