<?php

if (!function_exists('noo_hermosa_get_featured_content')):
	function noo_hermosa_get_featured_content($post_id = null, $post_type = '', $post_format = '') {
		
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$post_type = ('' === $post_type) ? get_post_type($post_id) : $post_type;
		$prefix = '';
		
		if ($post_type == 'post') {
			$prefix = '_noo_wp_post';
			$post_format = ('' === $post_format) ? get_post_format($post_id) : $post_format;
		}
		
		switch ($post_format) {
			case 'image':
				return noo_hermosa_get_featured_image($prefix, $post_id);
			case 'gallery':
				return noo_hermosa_get_featured_gallery($prefix, $post_id);
			case 'video':
				return noo_hermosa_get_featured_video($prefix, $post_id);
			case 'audio':
				return noo_hermosa_get_featured_audio($prefix, $post_id);
			case 'quote':
				return noo_hermosa_get_featured_quote($prefix, $post_id);
			case 'link':
				return noo_hermosa_get_featured_link($prefix, $post_id);
			default: // standard post format
				return noo_hermosa_get_featured_default($post_id);
		}
		
		return '';
	}
endif;

if (!function_exists('noo_hermosa_featured_content')):
	function noo_hermosa_featured_content($post_id = null, $post_type = '', $post_format = '') {
		echo noo_hermosa_get_featured_content( $post_id, $post_type, $post_format );
	}
endif;

if (!function_exists('noo_hermosa_get_featured_image')):
	function noo_hermosa_get_featured_image($prefix = '_noo_wp_post', $post_id = null, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		$thumb = '';
		$post_thumbnail_id = 0;
		$main_image = noo_hermosa_get_post_meta($post_id, "{$prefix}_main_image", 'featured');
		if( $main_image == 'featured') {
			$post_thumbnail_id = get_post_thumbnail_id( $post_id );
		} else {
			if (!is_singular() || $is_shortcode) {
				$preview_content = noo_hermosa_get_post_meta($post_id, "{$prefix}_image_preview", 'image');
				if ($preview_content == 'featured') {
					$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				}
			}

			if(empty($thumb)) {
				$post_thumbnail_id = (int) noo_hermosa_get_post_meta($post_id, "{$prefix}_image", '');
				
			}
		}
		
		$thumb = !empty($post_thumbnail_id) ? wp_get_attachment_image( $post_thumbnail_id, 'noo-post-thumbnail') : '';
		$post_thumbnail_src= '';
		if(!empty($post_thumbnail_id)){
			$image = wp_get_attachment_image_src($post_thumbnail_id,'full');
			$post_thumbnail_src = $image[0];
		}
		if(!empty($thumb)) {
			if (!is_singular() || $is_shortcode) {
				$html[] = '<a class="content-thumb" href="' . esc_url(get_permalink()) . '" title="' . esc_attr(sprintf(esc_html__('"%s"', 'noo-hermosa') , the_title_attribute('echo=0'))) . '">';
				$html[] = $thumb;
				$html[] = '</a>';
			} else {
				$html[] = '<div class="content-thumb">';
				$html[] = $thumb;
				$html[] = '</div>';
			}
		}
		
		return implode("\n", $html);
	}
endif;

if (!function_exists('noo_hermosa_featured_image')):
	function noo_hermosa_featured_image($prefix = '_noo_wp_post', $post_id = null,$is_shortcode = false) {
		echo noo_hermosa_get_featured_image($prefix, $post_id, $is_shortcode);
	}
endif;

if (!function_exists('noo_hermosa_get_featured_gallery')):
	function noo_hermosa_get_featured_gallery($prefix = '_noo_wp_post', $post_id = null, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		$post_thumbnail_id = 0;
		
		if (!is_single()) {
			$preview_content = noo_hermosa_get_post_meta($post_id, "{$prefix}_gallery_preview", 'slideshow');
			if ($preview_content == 'featured' && has_post_thumbnail( $post_id )) {
				$post_thumbnail_id = get_post_thumbnail_id( $post_id );
				
				$thumb = !empty($post_thumbnail_id) ? wp_get_attachment_image( $post_thumbnail_id, 'large') : '';
				
				$post_thumbnail_src= '';
				if(!empty($post_thumbnail_id)){
					$image = wp_get_attachment_image_src($post_thumbnail_id,'full');
					$post_thumbnail_src = $image[0];
				}
				
				if(!empty($thumb)) {
					$html[] = '<a class="content-thumb" href="' . esc_url( get_permalink() ) . '" title="' . esc_attr(sprintf(esc_html__('Permalink to: "%s"', 'noo-hermosa') , the_title_attribute('echo=0'))) . '">';
					$html[] = $thumb;
					$html[] = '</a>';
				}

				echo implode("\n", $html);

				return;
			}

			if( $preview_content == 'first_image' ) {
				$gallery_ids = noo_hermosa_get_post_meta($post_id, "{$prefix}_gallery", '');
				if(!empty($gallery_ids)) {
					$gallery_arr = explode(',', $gallery_ids);
					$image_id = (int) $gallery_arr[0];
					
					$thumb = !empty($image_id) ? wp_get_attachment_image( $image_id, 'large') : '';
					
					$post_thumbnail_src= '';
					if(!empty($image_id)){
						$image = wp_get_attachment_image_src($image_id,'full');
						$post_thumbnail_src = $image[0];
					}
					
					if(!empty($thumb)) {
						$html[] = '<a class="content-thumb" href="' .esc_url( get_permalink() ) . '" title="' . esc_attr(sprintf(esc_html__('Permalink to: "%s"', 'noo-hermosa') , the_title_attribute('echo=0'))) . '">';
						$html[] = $thumb;
						$html[] = '</a>';
					}

					echo implode("\n", $html);

					return;
				}
			}
		}

		$gallery_ids = noo_hermosa_get_post_meta($post_id, "{$prefix}_gallery", '');
		if(!empty($gallery_ids)) {			
			
			$html[] = '<div id="noo-gallery-' . $post_id . '" class="noo-slider owl-carousel">';
			// $html[] = '<ul class="sliders">';
			$gallery_arr = explode(',', $gallery_ids);
			foreach ($gallery_arr as $index => $image_id) {
				$thumb = !empty($image_id) ? wp_get_attachment_image( $image_id, 'large') : '';
				
				$post_thumbnail_src= '';
				if(!empty($image_id)){
					$image = wp_get_attachment_image_src($image_id,'full');
					$post_thumbnail_src = $image[0];
				}
				
				if(!empty($thumb)) {
					$html[] = '<div class="slide-item">';
					$html[] = $thumb;
					$html[] = '</div>';
				}
			}

			$html[] = '</div>';
			
			wp_enqueue_script( 'carousel' );
			wp_enqueue_style( 'carousel' );

			$html[] = '
				<script type="text/javascript">
					jQuery(document).ready(function() {
 
						jQuery(".noo-slider").owlCarousel({
						    
							items: 1,
						    loop:true,
						    margin:30,
						    autoplayTimeout: 300,
						    autoplay:false,
						    nav: true,
						    dots:false,
						    dotsEach:false,
						    autoHeight: true,
						    rtl: false,
						});
				 
					});
				</script>';
		}

		return implode("\n", $html);
	}
endif;

if (!function_exists('noo_hermosa_featured_gallery')):
	function noo_hermosa_featured_gallery( $prefix = '_noo_wp_post', $post_id = null,$is_shortcode = false) {
		echo noo_hermosa_get_featured_gallery( $prefix, $post_id, $is_shortcode);
	}
endif;

if (!function_exists('noo_hermosa_get_featured_video')):
	function noo_hermosa_get_featured_video($prefix = '_noo_wp_post', $post_id = null, $is_shortcode = false) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$html = array();
		
		$embed 	= noo_hermosa_get_post_meta( $post_id, "{$prefix}_video_embed", '' );

		// @TODO: add poster to embedded video.
		if ( $embed != '' ) {

			$html[] = '<div class="noo-video-container">';
			$html[] = '	<div class="video-inner">';
			
			$html[] = stripslashes( wp_specialchars_decode( $embed ) );
				
			$html[] = '	</div>';
			$html[] = '</div>';
		}
		
		return implode("\n", $html);
	}
endif;

if (!function_exists('noo_hermosa_featured_video')):
	function noo_hermosa_featured_video($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_hermosa_get_featured_video( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_hermosa_get_featured_audio')):
	function noo_hermosa_get_featured_audio($prefix = '_noo_wp_post', $post_id = null) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;

		$mp3   = noo_hermosa_get_post_meta( $post_id, "{$prefix}_audio_mp3", '' );
		$oga   = noo_hermosa_get_post_meta( $post_id, "{$prefix}_audio_oga", '' );
		$embed = noo_hermosa_get_post_meta( $post_id, "{$prefix}_audio_embed", '' );
		$html  = array();

		if ( $embed != '' ) :

			$html[] = '<div class="noo-audio-embed">';
			$html[] = stripslashes( wp_specialchars_decode( $embed ) );
			$html[] = '</div>';

		endif; // if - $embed

		return implode("\n", $html);
	}
endif;
if (!function_exists('noo_hermosa_get_featured_quote')):
	function noo_hermosa_get_featured_quote($prefix = '_noo_wp_post', $post_id = null) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$twitter_url   = noo_hermosa_get_post_meta( $post_id, "{$prefix}_quote", '' );
		$html  = array();
		if ( $twitter_url != '' ) :
			$bg_url= '';
			if(has_post_thumbnail()){
				// $bg_url = wp_get_attachment_url(get_post_thumbnail_id());
				$bg_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'noo-full');
				$bg_url = $bg_url[0];
			} else {
				$bg_url = get_template_directory_uri() . '/assets/images/bg-quote.jpg';
			}
			$html[] = '<div class="content-thumb"'.(!empty($bg_url) ?' style="background-image:url('.$bg_url.')"':'').'>';
			$html[] = '</div>';
		endif;
		return implode("\n", $html);
	}
endif;

if (!function_exists('noo_hermosa_featured_audio')):
	function noo_hermosa_featured_audio($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_hermosa_get_featured_audio( $prefix, $post_id );
	}
endif;

if (!function_exists('noo_hermosa_get_featured_quote')):
	function noo_hermosa_get_featured_quote($prefix = '_noo_wp_post', $post_id = null) {
		return noo_hermosa_get_featured_default($post_id);
	}
endif;

if (!function_exists('noo_hermosa_get_featured_link')):
	function noo_hermosa_get_featured_link($prefix = '_noo_wp_post', $post_id = null) {
		return noo_hermosa_get_featured_default($post_id);
	}
endif;

if (!function_exists('noo_hermosa_featured_link')):
	function noo_hermosa_featured_link($prefix = '_noo_wp_post', $post_id = null) {
		echo noo_hermosa_get_featured_link($prefix, $post_id);
	}
endif;

if (!function_exists('noo_hermosa_get_featured_default')):
	function noo_hermosa_get_featured_default($post_id = null,$is_shortcode = false) {
		$html = array();
		
		if (has_post_thumbnail()) {
			$thumb = get_the_post_thumbnail($post_id, 'full');
			if (is_singular() && !$is_shortcode) {
				$html[] = '<div class="content-thumb">';
				$html[] = $thumb;
				$html[] = '</div>';
			} else {
				$html[] = '<a class="content-thumb" href="' . esc_url(get_permalink()) . '" title="' . esc_attr(sprintf(esc_html__('Permalink to: "%s"', 'noo-hermosa') , the_title_attribute('echo=0'))) . '">';
				$html[] = $thumb;
				$html[] = '</a>';
			}
		}
		
		return implode("\n", $html);
	}
endif;


if (!function_exists('noo_hermosa_featured_default')):
	function noo_hermosa_featured_default($post_id = null,$is_shortcode = false) {
		echo noo_hermosa_get_featured_default($post_id,$is_shortcode);
	}
endif;
