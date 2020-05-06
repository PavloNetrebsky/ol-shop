<?php

if ( class_exists( 'woocommerce' ) ) {

	/**
	 * Remove Action/Filter
	 */
	remove_action ( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	
	remove_action ( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

    remove_action ( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

    remove_action ( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
    remove_action ( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

    remove_action ( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

    remove_action ( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

    remove_action( 'noo_product_thumbnail_before', 'woocommerce_show_product_loop_sale_flash', 10 );

    /**
     * Add Action/Filter
     */
    add_action( 'noo_before_action_button_shop', 'woocommerce_template_loop_add_to_cart', 5 );

    add_action( 'woocommerce_product_after_image', 'noo_hermosa_social_share_product', 5 );

    add_action( 'woocommerce_product_after_image', 'woocommerce_show_product_loop_sale_flash', 10 );

	/**
	 * Set number product per page
	 *
	 * @package 	Noo_Hermosa
	 * @author 		KENT <tuanlv@vietbrain.com>
	 * @version 	1.0
	 */
	
	if ( ! function_exists( 'noo_hermosa_set_product_per_page' ) ) :
		
		function noo_hermosa_set_product_per_page() {
	
			if( isset($_GET['product_per']) && !empty($_GET['product_per']) ) :
	            return $_GET['product_per'];
	        else :
	            return noo_hermosa_get_option( 'noo_shop_num', 12 );
	        endif;
	
		}

		add_filter( 'loop_shop_per_page', 'noo_hermosa_set_product_per_page', 20 );
	
	endif;

	/**
	 * Remove class on loop products
	 *
	 * @package 	Noo_Hermosa
	 * @author 		KENT <tuanlv@vietbrain.com>
	 * @version 	1.0
	 */
	
	if ( ! function_exists( 'noo_hermosa_remove_class_loop_product' ) ) :
		
		function noo_hermosa_remove_class_loop_product() {
	
			if ( noo_hermosa_get_option( 'noo_shop_layout', 'fullwidth' ) === 'fullwidth' ) {
				return 4;
			}
			return 3;
	
		}

		add_filter( 'loop_shop_columns', 'noo_hermosa_remove_class_loop_product' );
	
	endif;
	
	/**
	 * Add button list/grid to header archive shop 
	 *
	 * @package 	Noo_Hermosa
	 * @author 		KENT <tuanlv@vietbrain.com>
	 * @version 	1.0
	 */
	
	if ( ! function_exists( 'noo_hermosa_add_button_archive_shop' ) ) :
		
		function noo_hermosa_add_button_archive_shop() {
			$layout = noo_hermosa_get_option( 'noo_shop_default_layout', 'grid' );
			?><p class="noo-button-header-shop">

				<i class="fa fa-th-large<?php echo esc_attr( $layout == 'grid' ? ' active' : '' ); ?>" data-id="grid"></i>
				<i class="fa fa-th-list<?php echo esc_attr( $layout == 'list' ? ' active' : '' ); ?>" data-id="list"></i>

			</p><!-- /.noo-button-header-shop --><?php
	
		}

		add_action( 'woocommerce_before_shop_loop', 'noo_hermosa_add_button_archive_shop', 29 );
	
	endif;	

	/**
	 * Fix size image default
	 *
	 * @package 	Noo_Hermosa
	 * @author 		KENT <tuanlv@vietbrain.com>
	 * @version 	1.0
	 */
	
	if ( ! function_exists( 'noo_hermosa_fix_size_image_default' ) ) :
		
		function noo_hermosa_fix_size_image_default() {
	
			$catalog   = array( 'width' => '500', 'height' => '700', 'crop' => 1 );
			$single    = array( 'width' => '500', 'height' => '700', 'crop' => 1 );
			$thumbnail = array( 'width' => '270', 'height' => '340', 'crop' => 1 );
			update_option( 'shop_catalog_image_size', $catalog );
			update_option( 'shop_single_image_size', $single );
			update_option( 'shop_thumbnail_image_size', $thumbnail );
	
		}

		if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) {
			add_action( 'init', 'noo_hermosa_fix_size_image_default', 1 );
		}
	
	endif;

	/**
	 * Add button action to loop shop
	 *
	 * @package 	Noo_Hermosa
	 * @author 		KENT <tuanlv@vietbrain.com>
	 * @version 	1.0
	 */
	
	if ( ! function_exists( 'noo_hermosa_add_button_action_shop' ) ) :
		
		function noo_hermosa_add_button_action_shop() {
			global $product;
			?><div class="noo-action-button-shop">
				
				<?php do_action( 'noo_before_action_button_shop' ); ?>
				

				<?php
					if ( noo_hermosa_woocommerce_wishlist_is_active() ) {
						echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
					}
				?>
				
				<a class="shop-loop-quickview" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>" href="<?php echo esc_url( $product->get_permalink() ); ?>">
					<i class="ion-search"></i>
				</a>

				<?php do_action( 'noo_after_action_button_shop' ); ?>

			</div><?php
	
		}

		add_action( 'noo_product_thumbnail_after', 'noo_hermosa_add_button_action_shop' );
		add_action( 'woocommerce_after_shop_loop_item', 'noo_hermosa_add_button_action_shop' );
	
	endif;

	/**
	 * Add excerpt to product woocommerce
	 *
	 * @package 	Noo_Hermosa
	 * @author 		KENT <tuanlv@vietbrain.com>
	 * @version 	1.0
	 */
	
	if ( ! function_exists( 'noo_hermosa_excerpt_product' ) ) :
		
		function noo_hermosa_excerpt_product() {
	
			echo '<div class="noo-product-excerpt">';
	        the_excerpt();
	        echo '</div>';
	
		}

		add_action( 'woocommerce_after_shop_loop_item_title', 'noo_hermosa_excerpt_product' );
	
	endif;

	function noo_hermosa_add_to_cart_fragments( $fragments ) {
		$output = noo_hermosa_minicart();
		$fragments['.minicart'] = $output;
		$fragments['.mobile-minicart-icon'] = noo_hermosa_minicart_mobile();
		return $fragments;
	}
	add_filter( 'woocommerce_add_to_cart_fragments', 'noo_hermosa_add_to_cart_fragments' );

	function noo_hermosa_woocommerce_remove_cart_item() {
		global $woocommerce;
		$response = array();
		
		if ( ! isset( $_GET['item'] ) && ! isset( $_GET['_wpnonce'] ) ) {
			exit();
		}
		$woocommerce->cart->set_quantity( $_GET['item'], 0 );
		
		$cart_count = $woocommerce->cart->cart_contents_count;
		$response['count'] = $cart_count != 0 ? $cart_count : "";
		$response['minicart'] = noo_hermosa_minicart( true );
		
		// widget cart update
		ob_start();

		$mini_cart = ob_get_clean();
		$response['widget'] = '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>';
		
		echo json_encode( $response );
		exit();
	}
	add_action( 'wp_ajax_noo_hermosa_woocommerce_remove_cart_item', 'noo_hermosa_woocommerce_remove_cart_item' );
	add_action( 'wp_ajax_nopriv_noo_hermosa_woocommerce_remove_cart_item', 'noo_hermosa_woocommerce_remove_cart_item' );

	function noo_hermosa_product_items_text( $count ) {
		$product_item_text = "";
		
		if ( $count > 1 ) {
			$product_item_text = str_replace( '%', number_format_i18n( $count ), esc_html__( '% items', 'noo-hermosa' ) );
		} elseif ( $count == 0 ) {
			$product_item_text = esc_html__( '0 items', 'noo-hermosa' );
		} else {
			$product_item_text = esc_html__( '1 item', 'noo-hermosa' );
		}
		
		return $product_item_text;
	}

	// Mobile icon
	function noo_hermosa_minicart_mobile() {
		if( ! noo_hermosa_get_option('noo_header_nav_icon_cart', true ) ) {
			return '';
		}

		global $woocommerce;
		
		$cart_output = "";
		$cart_total = $woocommerce->cart->get_cart_total();
		$cart_count = $woocommerce->cart->cart_contents_count;
		$cart_output = '<a href="' . wc_get_cart_url() . '" title="' . esc_html__( 'View Cart', 'noo-hermosa' ) .
			 '"  class="mobile-minicart-icon"><i class="icon ion-bag"></i><span class="noo-cart-wrapper"><span class="noo-cart-count">' . esc_html__( 'Cart', 'noo-hermosa' ) . ' (' . $cart_count . ')</span>' .
			 '<span class="noo-cart-total">' . $woocommerce->cart->get_cart_total() . '</span></span>' .
			 '</a>';
		return $cart_output;
	}
	
	// Menu cart
	function noo_hermosa_minicart( $content = false ) {
		global $woocommerce;
		
		$cart_output = "";
		$cart_total = $woocommerce->cart->get_cart_total();
		$cart_count = $woocommerce->cart->cart_contents_count;
		$cart_count_text = noo_hermosa_product_items_text( $cart_count );
		
		$cart_has_items = '';
		if ( $cart_count != "0" ) {
			$cart_has_items = ' has-items';
		}
		
		$output = '';
		if ( ! $content ) {
			$output .= '<li id="nav-menu-item-cart" class="menu-item noo-menu-item-cart minicart"><a title="' .
				 esc_html__( 'View cart', 'noo-hermosa' ) . '" class="cart-button" href="' . wc_get_cart_url() .
				 '">' . '<span class="cart-item' . $cart_has_items . '"><i class="fa fa-shopping-cart"></i>';
			if ( $cart_count != "0" ) {
				$output .= "<span>" . $cart_count . "</span>";
			}
			$output .= '</span>';
			$output .= '</a>';
			$output .= '<div class="noo-minicart">';
		}
		if ( $cart_count != "0" ) {
			$output .= '<div class="minicart-header">' . $cart_count_text . ' ' .
				 esc_html__( 'in the shopping cart', 'noo-hermosa' ) . '</div>';
			$output .= '<div class="minicart-body">';
			foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) {
				
				$cart_product = $cart_item['data'];
				$product_title = $cart_product->get_title();
				$product_short_title = ( strlen( $product_title ) > 25 ) ? substr( $product_title, 0, 22 ) . '...' : $product_title;
				
				if ( $cart_product->exists() && $cart_item['quantity'] > 0 ) {
					$output .= '<div class="cart-product clearfix">';
					$output .= '<div class="cart-product-image"><a class="cart-product-img" href="' .
						 get_permalink( $cart_item['product_id'] ) . '">' . $cart_product->get_image() . '</a></div>';
					$output .= '<div class="cart-product-details">';
					$output .= '<div class="cart-product-title"><a href="' . get_permalink( $cart_item['product_id'] ) .
						 '">' .
						 apply_filters( 'woocommerce_cart_widget_product_title', $product_short_title, $cart_product ) .
						 '</a></div>';
					$output .= '<div class="cart-product-price">' . __( "Price", 'noo-hermosa' ) . ' ' .
						 wc_price( $cart_product->get_price() ) . '</div>';
					$output .= '<div class="cart-product-quantity">' . esc_html__( 'Quantity', 'noo-hermosa' ) . ' ' .
						 $cart_item['quantity'] . '</div>';
					$output .= '</div>';
					$output .= apply_filters( 
						'woocommerce_cart_item_remove_link', 
						sprintf( 
							'<a href="%s" class="remove" title="%s">&times;</a>', 
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ), 
							esc_html__( 'Remove this item', 'noo-hermosa' ) ),
						$cart_item_key );
					$output .= '</div>';
				}
			}
			$output .= '</div>';
			$output .= '<div class="minicart-footer">';
			$output .= '<div class="minicart-total">' . esc_html__( 'Cart Subtotal', 'noo-hermosa' ) . ' ' . $cart_total .
				 '</div>';
			$output .= '<div class="minicart-actions clearfix">';
			if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
				$cart_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_cart_url() );
				$checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );
				
				$output .= '<a class="button" href="' . esc_url( $cart_url ) . '"><span class="text">' .
					 esc_html__( 'View Cart', 'noo-hermosa' ) . '</span></a>';
				$output .= '<a class="checkout-button button" href="' . esc_url( $checkout_url ) .
					 '"><span class="text">' . esc_html__( 'Proceed to Checkout', 'noo-hermosa' ) . '</span></a>';
			} else {
				
				$output .= '<a class="button" href="' . esc_url( wc_get_cart_url() ) .
					 '"><span class="text">' . esc_html__( 'View Cart', 'noo-hermosa' ) . '</span></a>';
				$output .= '<a class="checkout-button button" href="' . esc_url( 
					wc_get_checkout_url() ) . '"><span class="text">' .
					 esc_html__( 'Proceed to Checkout', 'noo-hermosa' ) . '</span></a>';
			}
			$output .= '</div>';
			$output .= '</div>';
		} else {
			$output .= '<div class="minicart-header">' . esc_html__( 'Your shopping bag is empty.', 'noo-hermosa' ) . '</div>';
			$shop_page_url = "";
			if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {
				$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
			} else {
				$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );
			}
			
			$output .= '<div class="minicart-footer">';
			$output .= '<div class="minicart-actions clearfix">';
			$output .= '<a class="button pull-left" href="' . esc_url( $shop_page_url ) . '"><span class="text">' .
				 esc_html__( 'Go to the shop', 'noo-hermosa' ) . '</span></a>';
			$output .= '</div>';
			$output .= '</div>';
		}
		
		if ( ! $content ) {
			$output .= '</div>';
			$output .= '</li>';
		}
		
		return $output;
	}

	function noo_hermosa_navbar_shop_icons( $items, $args ) {

		if( ! NOO_WOOCOMMERCE_EXIST ) return $items;

		if ( $args->theme_location == 'primary' ) {
            $minicart = noo_hermosa_minicart();
            $items .= $minicart;
			if( noo_hermosa_get_option('noo_header_nav_icon_wishlist', true ) && defined( 'YITH_WCWL' ) ) {
				$wishlist_url = YITH_WCWL()->get_wishlist_url();
				$wishlist = '<li id="nav-menu-item-wishlist" class="menu-item noo-menu-item-wishlist"><a title="' .
				 esc_html__( 'View Wishlist', 'noo-hermosa' ) . '" class="wishlist-button" href="' . $wishlist_url .
				 '"><i class="fa fa-heart"></i></a></li>';

				$items .= $wishlist;
			}
		}
		return $items;
	}
	 //add_filter( 'wp_nav_menu_items', 'noo_hermosa_navbar_shop_icons', 10, 2 );

	
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	
	// Loop thumbnail
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );	
	add_action( 'woocommerce_before_shop_loop_item_title', 'noo_hermosa_template_loop_product_thumbnail', 10 );
	//add_action( 'woocommerce_before_shop_loop_item_title', 'noo_template_loop_product_frist_thumbnail', 11 );

	function noo_hermosa_template_loop_product_thumbnail() {
		global $post;
		$first_image = noo_hermosa_template_loop_product_get_frist_thumbnail();
		$thumbnail   = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'noo-thumbnail-product' );
        $class_gallery = 'noo-one-image';
        if( isset($first_image) && !empty($first_image) ){
            $class_gallery = '';
        }
		echo '<div class="noo-product-head '.esc_attr($class_gallery).'">';
		do_action( 'noo_product_thumbnail_before' );
		echo '	<div class="noo-product-thumbnail">';
		echo '		<a href="' . get_permalink() . '" title="' . get_the_title() . '">';                
						if ( $first_image != '' ) :
							echo '<img src="' . esc_url( $thumbnail[0] ) . '" alt="' . get_the_title() . '" class="noo-thumbnail-product-first" />';
							echo '<img src="' . esc_url( $first_image ) . '" alt="' . get_the_title() . '" class="noo-thumbnail-product-second" />';
						else :
							echo '<img src="' . esc_url( $thumbnail[0] ) . '" alt="' . get_the_title() . '" />';
						endif;
		echo '		</a>';
		echo '	</div><!-- /.noo-product-thumbnail -->';
		do_action( 'noo_product_thumbnail_after' );
		echo '</div><!-- /.noo-product-head -->';
	}


	function noo_hermosa_template_loop_product_get_frist_thumbnail() {
		global $product, $post;
		$image = '';
		if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
			$attachment_ids = $product->get_gallery_image_ids();
			$image_count = 0;
			if ( $attachment_ids ) {
				foreach ( $attachment_ids as $attachment_id ) {
					if ( noo_hermosa_get_post_meta( $attachment_id, '_woocommerce_exclude_image' ) )
						continue;
					
					$image = wp_get_attachment_image_url ( $attachment_id, 'noo-thumbnail-product' );
					
					$image_count++;
					if ( $image_count == 1 )
						break;
				}
			}
		} else {
			$attachments = get_posts( 
				array( 
					'post_type'      => 'attachment', 
					'numberposts'    => - 1, 
					'post_status'    => null, 
					'post_parent'    => $post->ID, 
					'post__not_in'   => array( get_post_thumbnail_id() ), 
					'post_mime_type' => 'image', 
					'orderby'        => 'menu_order', 
					'order'          => 'ASC'
				) 
			);
			$image_count = 0;
			if ( $attachments ) {
				foreach ( $attachments as $attachment ) {
					
					if ( noo_hermosa_get_post_meta( $attachment->ID, '_woocommerce_exclude_image' ) == 1 )
						continue;
					
					$image = wp_get_attachment_image( $attachment->ID, 'noo-thumbnail-product-back' );
					
					$image_count++;
					
					if ( $image_count == 1 )
						break;
				}
			}
		}
		return $image;
	}
	
	// Wishlist
	if ( ! function_exists( 'noo_hermosa_woocommerce_wishlist_is_active' ) ) {

		/**
		 * Check yith-woocommerce-wishlist plugin is active
		 *
		 * @return boolean .TRUE is active
		 */
		function noo_hermosa_woocommerce_wishlist_is_active() {
			$active_plugins = (array) get_option( 'active_plugins', array() );
			
			if ( is_multisite() )
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			
			return in_array( 'yith-woocommerce-wishlist/init.php', $active_plugins ) ||
				 array_key_exists( 'yith-woocommerce-wishlist/init.php', $active_plugins );
		}
	}
	if ( ! function_exists( 'noo_hermosa_woocommerce_compare_is_active' ) ) {

		/**
		 * Check yith-woocommerce-compare plugin is active
		 *
		 * @return boolean .TRUE is active
		 */
		function noo_hermosa_woocommerce_compare_is_active() {
			$active_plugins = (array) get_option( 'active_plugins', array() );
			
			if ( is_multisite() )
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
			
			return in_array( 'yith-woocommerce-compare/init.php', $active_plugins ) ||
				 array_key_exists( 'yith-woocommerce-compare/init.php', $active_plugins );
		}
	}
	
	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash' );
	

	
	// Related products
	add_filter( 'woocommerce_output_related_products_args', 'noo_hermosa_woocommerce_output_related_products_args' );

	function noo_hermosa_woocommerce_output_related_products_args() {
		if ( noo_hermosa_get_option( 'noo_shop_layout', 'fullwidth' ) === 'fullwidth' ) {
			$args = array( 'posts_per_page' => 4, 'columns' => 4 );
			return $args;
		}

		$args = array( 'posts_per_page' => noo_hermosa_get_option('noo_woocommerce_product_related',6), 'columns' => 4 );
		return $args;
	}
	
	// Upsell products
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	add_action( 'woocommerce_after_single_product_summary', 'noo_hermosa_woocommerce_upsell_display', 15 );
	if ( ! function_exists( 'noo_hermosa_woocommerce_upsell_display' ) ) {

		function noo_hermosa_woocommerce_upsell_display() {
			if ( noo_hermosa_get_option( 'noo_shop_layout', 'fullwidth' ) === 'fullwidth' ) {
				woocommerce_upsell_display( - 1, 4 );
			} else {
				woocommerce_upsell_display( - 1, 3 );
			}
		}
	}

	function noo_hermosa_shop_grid_column(){
		if(is_product()){
			$product_layout = noo_hermosa_get_option('noo_woocommerce_product_layout', 'same_as_shop');
			if( $product_layout == 'fullwidth' ){
				$column = 4;
			} else{
				$column = noo_hermosa_get_option('noo_shop_grid_column', 4);
			}
		} else{
			$column = noo_hermosa_get_option('noo_shop_grid_column', 4);
		}
		return $column;
	}
	add_filter('loop_shop_columns', 'noo_hermosa_shop_grid_column');
	add_filter('woocommerce_related_products_columns', 'noo_hermosa_shop_grid_column');

}

