<?php
/**
 * Create shortcode: [noo_trainer]
 *
 * @package 	Noo_Hermosa_Core
 * @author      Hung Ngo <hungnt@vietbrain.com>
 */

if ( ! function_exists( 'shortcode_noo_trainer' ) ) :
	
	function shortcode_noo_trainer( $atts ) {

        if ( ! class_exists('Noo__Timetable__Trainer') ) return;

		extract( shortcode_atts( array(
            'title'             => '',
            'sub_title'         => '',
            'columns'           => '4',
            'categories'        => 'all',
            'filter'            => '',
            'orderby'           => 'default',
            'limit'             => '4',
        ), $atts ) );

        wp_enqueue_script('imagesloaded');
        wp_enqueue_script('isotope');

        ob_start();

        $order = 'DESC';
        switch ( $orderby ) {
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
                $orderby = 'default';
                break;
        }

        $args = array(
            'post_type'       => "noo_trainer",
            'posts_per_page'  => $limit,
        );

        if ('default' != $orderby) {
            $args['orderby'] = $orderby;
            $args['order']   = $order;
        }

        if(!empty($categories) && $categories != 'all'){
            $args['tax_query'][] =  array(

                'taxonomy' => 'class_category',
                'terms'    => explode(',', $categories),
                'field'    => 'id'
            );
        }

        $query = new WP_Query( $args );

        
        ?>
        <div class="noo-trainer-shortcode">

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

            <!-- Section content -->

            <?php

            $post_class = 'noo-xs-6 noo-sm-6 noo-md-' .absint((12 / $columns));

            if ( $query->have_posts() ) : ?>

                <div class="masonry">
                    <?php
                    // Masonry Grid

                    $category_arr = explode(',', $categories);

                    if ( $categories == 'all' ) {
                        $category_arr = get_terms( 'class_category', array( 'orderby' => 'NAME', 'order' => 'ASC' ) );
                    }

                    if( count( $category_arr ) > 0 && $filter ):
                    ?>

                    <div class="masonry-header noo-filters trainer-filters">
                        <div class="masonry-filters">
                            <ul data-option-key="filter" >
                                <li>
                                    <a class="selected" href="#" data-option-value= "*"><?php echo esc_html__('All Category', 'noo-hermosa-core') ?></a>
                                </li>
                            <?php
                                foreach ($category_arr as $cat):
                                    if($cat == 'all')
                                        continue;
                                    $category = get_term($cat, 'class_category');
                                    if($category):
                                    ?>
                                    <li>
                                        <a href="#" data-option-value= ".<?php echo 'mansonry-filter-'.$category->slug?>"><?php echo esc_html($category->name); ?></a>
                                    </li>
                                    <?php endif;?>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <?php endif; ?>
                    
                    <div class="mansonry-content">
                        <div class="trainer-tag-wrap noo-row">
                            <div id="masonry-container" data-masonry-gutter="0" data-masonry-column="'<?php echo esc_attr($columns); ?>'" class="masonry-container columns-'<?php echo esc_attr($columns); ?>'">
                                <?php

                                while ( $query->have_posts() ) : $query->the_post(); ?>
                                <?php

                                $cat_class = array();
                                foreach ( (array) get_the_terms(get_the_ID(), 'class_category') as $cat ) {
                                    if ( empty($cat->slug ) )
                                        continue;
                                    $cat_class[] = 'mansonry-filter-' . sanitize_html_class($cat->slug, $cat->term_id);
                                }
                                $item_class = 'masonry-item '.implode(' ', $cat_class);

                                ?>
                                <div class="trainer-item <?php echo esc_attr($post_class); ?>  <?php echo esc_attr($item_class); ?>">
                                    <div class="trainer-bio">
                                        <a class="trainer-avatar" href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('noo-thumbnail-trainer'); ?>
                                        </a>
                                        <div class="trainer-info">
                                            <h4>
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h4>
                                            <div class="trainer-category">
                                                <?php
                                                    echo get_the_term_list(get_the_ID(), 'class_category',' ',', ');
                                                ?>
                                            </div>
                                            <?php
                                                $facebook       =   noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_facebook", '' );
                                                $google         =   noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_google", '' );
                                                $twitter        =   noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_twitter", '' );
                                                $pinterest      =   noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_pinterest", '' );
                                            ?>
                                            <?php if(!empty($facebook) || !empty($twitter) || !empty($google) || !empty($linkedin) || !empty($pinterest)):?>
                                                <div class="trainer-social all-social-share">
                                                    <?php echo ( !empty($facebook) ? '<a href="' . $facebook . '" class="fa fa-facebook"></a>' : '' ); ?>
                                                    <?php echo ( !empty($google) ? '<a href="' . $google . '" class="fa fa-google-plus"></a>' : '' ); ?>
                                                    <?php echo ( !empty($twitter) ? '<a href="' . $twitter . '" class="fa fa-twitter"></a>' : '' ); ?>
                                                    <?php echo ( !empty($pinterest) ? '<a href="' . $pinterest . '" class="fa fa-pinterest"></a>' : '' ); ?>
                                                </div>
                                            <?php endif; ?>
                                            <a class="btn view-profile" title="<?php printf( esc_html__( 'Post by %s','noo-hermosa-core'), get_the_title() ); ?>" href="<?php the_permalink(); ?>">
                                                <?php echo esc_html__('View Profile', 'noo-hermosa-core'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div> <!-- /.masonry-container -->
                        </div> <!-- /.trainer-tag-wrap -->
                    </div><!-- /.mansonry-content -->
                </div><!-- /.masonry -->
            <?php
            endif; ?>
        </div> <!-- /.noo-trainer-shortcode -->


		<?php $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_trainer', 'shortcode_noo_trainer' );

endif;