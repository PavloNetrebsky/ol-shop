<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;
$attachment_ids = $product->get_gallery_image_ids();
?>
<div class="noo-images noo-row">
	
	<div class="noo-image-big-wrap owl-carousel ">
		<?php
            wp_enqueue_style( 'carousel' );
			wp_enqueue_script( 'carousel' );
			wp_enqueue_script( 'noo_event' );

			if ( $attachment_ids ) :
				foreach ( $attachment_ids as $attachment_id ) :

					$image_link = wp_get_attachment_url( $attachment_id );

					if ( ! $image_link )
						continue;

					$image_title 	= esc_attr( get_the_title( $attachment_id ) );
					$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );

					$image       = wp_get_attachment_image( $attachment_id, 'noo-thumbnail-big', 0, $attr = array(
						'title'	=> $image_title,
						'alt'	=> $image_title
					) );

					echo sprintf( '<div class="%s">%s</div>', 'noo-image-big-item', $image );

				endforeach;

			elseif ( has_post_thumbnail() ) :
				$image_caption = get_post( get_post_thumbnail_id() )->post_excerpt;
				$image_link    = wp_get_attachment_url( get_post_thumbnail_id() );
				$image         = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'noo-thumbnail-big' ), array(
					'title'	=> get_the_title( get_post_thumbnail_id() )
				) );

				$attachment_count = count( $product->get_gallery_image_ids() );

				if ( $attachment_count > 0 ) {
					$gallery = '[product-gallery]';
				} else {
					$gallery = '';
				}

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" class="noo-image-big-item" title="%s">%s</a>', $image_link, $image_caption, $image ), $post->ID );

			else :

				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'noo-hermosa' ) ), $post->ID );

			endif;
		?>
	</div><!-- /.noo-image-big-wrap -->
	
	<div class="noo-image-slider-wrap">
		<div class="noo-thumbnails">
			<?php
				if ( $attachment_ids ) :
					$loop 		= 0;

					foreach ( $attachment_ids as $attachment_id ) :

						$classes = array( 'noo-thumbnail-small' . ( $loop == 0 ? ' active' : '' ) );

						$image_link = wp_get_attachment_url( $attachment_id );

						if ( ! $image_link )
							continue;

						$image_title 	= esc_attr( get_the_title( $attachment_id ) );
						$image_caption 	= esc_attr( get_post_field( 'post_excerpt', $attachment_id ) );

						$image       = wp_get_attachment_image( $attachment_id, 'noo-thumbnail-small', 0, $attr = array(
							'title'	=> $image_title,
							'alt'	=> $image_title
						) );

						$image_class = esc_attr( implode( ' ', $classes ) );

						echo sprintf( '<div class="%s">%s</div>', $image_class, $image );

						$loop++;

					endforeach;

				elseif ( has_post_thumbnail() ) :
					$image_caption = get_post( get_post_thumbnail_id() )->post_excerpt;
					$image_link    = wp_get_attachment_url( get_post_thumbnail_id() );
					$image         = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'noo-thumbnail-small' ), array(
						'title'	=> get_the_title( get_post_thumbnail_id() )
					) );

					$attachment_count = count( $product->get_gallery_image_ids() );

					if ( $attachment_count > 0 ) {
						$gallery = '[product-gallery]';
					} else {
						$gallery = '';
					}

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<a href="%s" itemprop="image" title="%s">%s</a>', $image_link, $image_caption, $image ), $post->ID );

				else :

					echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" class="noo-thumbnail-small active" />', wc_placeholder_img_src(), __( 'Placeholder', 'noo-hermosa' ) ), $post->ID );

				endif;
			?>
		</div><!-- /.noo-thumbnails -->
	</div><!-- /.noo-image-slider-wrap -->

	<?php do_action( 'woocommerce_product_after_image' ); ?>
</div>
