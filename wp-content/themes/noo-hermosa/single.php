<?php
get_header();

/**
 * VAR
 */
$noo_blog_post_author_bio = noo_hermosa_get_option( 'noo_blog_post_author_bio', true );
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">
            <div class="<?php noo_hermosa_main_class(); ?>">
                <?php
                    // Start the loop.
                    while ( have_posts() ) : the_post();
                        /*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part( 'content', get_post_format() );

                        if( !empty( $noo_blog_post_author_bio ) ):
                            
                            noo_hermosa_bio_author();

                        endif;
                        ?>

                        <div class="noo-post-nav">

                            <span class="prev-post">
                                <?php previous_post_link('%link', esc_html__( 'Previous Post', 'noo-hermosa' ) ) ?>
                            </span>

                            <span class="fa fa-th-large"></span>

                            <span class="next-post">
                                <?php next_post_link('%link', esc_html__( 'Next Post', 'noo-hermosa' ) ) ?>
                            </span>

                        </div><!-- /.noo-post-nav -->

                        <?php

                        // If comments are open or we have at least one comment, load up the comment template.
                        if ( comments_open() || get_comments_number() ) :
                            comments_template();
                        endif;

                        // End the loop.
                    endwhile;
                ?>
            </div>
            <?php get_sidebar(); ?>
        </div>


    </main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>