<?php
/*
Template Name: Page with Left Sidebar
*/
?>
<?php get_header(); ?>


<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">
            <?php get_sidebar(); ?>
            <div class="<?php noo_hermosa_main_class(); ?>">
                <?php
                // Start the loop.
                while ( have_posts() ) : the_post();

                    // Include the page content template.
                    get_template_part( 'content', 'page' );

                    // End the loop.
                endwhile;
                ?>
            </div>
        </div><!--/.row-->


    </main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>
