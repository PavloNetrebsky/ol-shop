<?php
/*
Template Name: Full Width
*/
?>
<?php get_header(); ?>
    <div class="page_fullwidth">
        <!-- Begin The loop -->
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; ?>
        <?php endif; ?>
        <!-- End The loop -->
    </div> <!-- /.main -->
	
<?php get_footer(); ?>