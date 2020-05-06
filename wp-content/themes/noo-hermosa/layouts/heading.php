<?php

$hide_page_heading = false;
if ( is_page() ) {
	$hide_page_heading = noo_hermosa_get_post_meta(get_the_ID(), '_noo_wp_page_hide_page_heading', false);
}

if( noo_hermosa_get_option( 'noo_page_heading', true ) && ! $hide_page_heading ) :
	// Get heading and archive title
	list($heading, $archive_title, $archive_desc) = noo_hermosa_get_page_heading();

	if ( ! noo_hermosa_get_option( 'noo_page_description', true ) ) {
		$archive_title = '';
	}

	if ( !empty($heading) && '' != $heading ) {
			
		$title = explode( ' ', $heading );
		$title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
		$heading = implode( ' ', $title );
		
	}
	// Get heading image
	$heading_image = noo_hermosa_get_page_heading_image();
	
?>
	<section class="noo-page-heading" style="background-image: url('<?php echo esc_url($heading_image) ?>')">
		<div class="noo-container">

			<div class="wrap-page-title">

		        <h1 class="page-title"><?php echo noo_hermosa_html_content_filter($heading); ?></h1>
		        <p class="page-description"><?php echo esc_html($archive_title); ?></p>

	        </div> <!-- /.wrap-page-title -->
			
			<?php
				if( noo_hermosa_get_option( 'noo_breadcrumbs', true ) && !is_search() ):
					noo_hermosa_get_layout('breadcrumb');
				endif;
			?>

		</div><!-- /.container-boxed -->
	</section>

<?php endif; ?>
