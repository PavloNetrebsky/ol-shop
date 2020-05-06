<?php
/*
Template Name: Page with Right Sidebar
*/
?>
<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">
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
            <?php get_sidebar(); ?>
        </div><!--/.row-->


    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
