<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">
            <div class="<?php noo_hermosa_main_class(); ?>">
				
				<div id="error-404">
					<p><?php echo esc_html__( 'Sorry. The page you are looking for does not exist', 'noo-hermosa' ); ?></p>
					<h1><?php echo esc_html__( '404 not found.', 'noo-hermosa' ); ?></h1>
				</div>
				
			</div>
			
		</div><!--/row-->
		

	</main><!-- .site-main -->
</div><!-- .content-area -->
<?php get_footer(); ?>

