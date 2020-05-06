<?php 
$count_like                   = noo_hermosa_get_post_meta( get_the_id(), 'noo_like' );
$is_like                      = ( isset( $_COOKIE['like-post-' . get_the_id()] ) ? ' disable-like' : '' );
$noo_blog_post_show_post_meta = noo_hermosa_get_option( 'noo_blog_post_show_post_meta', true );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <!--Start Header-->
    <header class="entry-header">
        
        <?php if ( is_single() ) : ?>
            <h1 class="item-title">
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permanent link to: "%s"','noo-hermosa' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
            </h1>
        <?php else : ?>
            <h3 class="item-title">
                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permanent link to: "%s"','noo-hermosa' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
            </h3>
        <?php endif; ?>

        <?php if ( !empty( $noo_blog_post_show_post_meta ) ) : ?>  

            <div class="item-info">
            
                <!-- Start Author -->
                    <?php printf( '<span class="author vcard"><i class="ion-android-person"></i> <a class="url fn n" href="%1$s">%2$s</a></span>',
                            esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                            get_the_author()
                        );
                    ?>
                <!-- Start end Author -->

                <!-- Start Date -->
                    <?php
                        printf( '<span class="posted-on"><i class="ion-calendar"></i> %2$s</span>',
                            esc_url( get_permalink() ),
                            get_the_date()
                        );
                    ?>
                <!-- Start end Date -->

                <!-- Start Count Comment -->
                    <?php
                        $comments_count = wp_count_comments(get_the_id());
                        printf( '<span class="count-comment"><i class="ion-chatbubble-working"></i> %1$s %2$s</span>', $comments_count->approved, esc_html__( 'comments', 'noo-hermosa' ) );
                    ?>
                <!-- Start end Count Comment -->

                <!-- Start Button Like -->
                    <span class="action-like ion-ios-heart-outline<?php echo esc_attr($is_like); ?>" data-id="<?php echo get_the_id(); ?>">
                        <strong><?php echo intval( $count_like ); ?></strong>
                    </span>
                <!-- Start end Button Like -->

            </div><!-- /.item-info -->

        <?php endif; ?>

    </header>
    <!--Start end header-->
    

    <!--Start featured-->
    <?php if( noo_hermosa_has_featured_content()) : ?>
        <div class="content-featured">
            <?php noo_hermosa_featured_audio(); ?>
        </div>
    <?php endif; ?>
    <!--Start end featured-->

    <!--Start content-->
    <div class="entry-content">
        <?php if ( is_single() ) : ?>
            <?php the_content(); ?>
            <?php wp_link_pages(); ?>
        <?php else : ?>
            <?php if(get_the_excerpt()):?>
                <?php the_excerpt(); ?>
            <?php endif;?>
        <?php endif; ?>
    </div>
    <!--Start end content-->
    
    <?php if ( is_single() ) : ?>
        <!--Start footer-->
        <footer class="entry-footer">
            <?php noo_hermosa_entry_meta(); ?>
        </footer>
        <!--Start end footer-->
    <?php endif; ?>

</article> <!-- /#post- -->