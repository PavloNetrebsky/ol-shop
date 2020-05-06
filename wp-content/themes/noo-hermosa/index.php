<?php
/**
 * The main template file
 *
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">
            <div class="<?php noo_hermosa_main_class(); ?>">
                <?php if ( have_posts() ) : ?>


                    <?php
                    // Start the loop.
                    while ( have_posts() ) : the_post();
                        /*
                         * Include the Post-Format-specific template for the content.
                         * If you want to override this in a child theme, then include a file
                         * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                         */
                        get_template_part( 'content', get_post_format() );

                        // End the loop.
                    endwhile;

                    noo_hermosa_pagination_normal();

                // If no content, include the "No posts found" template.
                else :
                    get_template_part( 'content', 'none' );
                endif;
                ?>
            </div>
            <?php get_sidebar(); ?>
        </div>


    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
