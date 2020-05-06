<?php
/**
 * Utilities Functions for NOO Framework.
 * This file contains various functions for getting and preparing data.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

if (!function_exists('noo_hermosa_get_page_heading')):
	function noo_hermosa_get_page_heading() {
		$heading       = '';
		$archive_title = '';
		$archive_desc  = '';
		if( ! noo_hermosa_get_option( 'noo_page_heading', true ) ) {
			return array($heading, $archive_title, $archive_desc);
		}
		if ( is_home() ) {
			$heading = noo_hermosa_get_option( 'noo_blog_heading_title', esc_html__( 'Blog', 'noo-hermosa' ) );
			$archive_title = noo_hermosa_get_option( 'noo_blog_heading_desc', '' );
		} elseif ( NOO_WOOCOMMERCE_EXIST && is_shop() ) {
			if( is_search() ) {
				$heading = esc_html__( 'Search', 'noo-hermosa' );
			} else {
				$heading = noo_hermosa_get_option( 'noo_shop_heading_title', esc_html__( 'Shop', 'noo-hermosa' ) );
				$archive_title = noo_hermosa_get_option( 'noo_shop_heading_desc', '' );
			}
		} elseif ( is_search() ) {
			$heading = esc_html__( 'Search', 'noo-hermosa' );
			// $archive_title = noo_hermosa_get_option( 'noo_shop_heading_desc', '' );

			global $wp_query;
			if(!empty($wp_query->found_posts)) {
				if($wp_query->found_posts > 1) {
					$archive_title =  $wp_query->found_posts ." ". esc_html__('Search Results for:','noo-hermosa')." ".esc_attr( get_search_query() );
				} else {
					$archive_title =  $wp_query->found_posts ." ". esc_html__('Search Result for:','noo-hermosa')." ".esc_attr( get_search_query() );
				}
			} else {
				if(!empty($_GET['s'])) {
					$archive_title = esc_html__('Search Results for:', 'noo-hermosa')." ".esc_attr( get_search_query() );
				} else {
					$archive_title = esc_html__('To search the site please enter a valid term', 'noo-hermosa');
				}
			}

		} elseif ( is_author() ) {
			$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
			$heading = esc_html__( 'Author Archive','noo-hermosa');
			if( isset($curauth->nickname) )
				$heading .= ' ' . esc_html__('for:', 'noo-hermosa'). ' ' . $curauth->nickname;

		} elseif ( is_year() ) {
    		$heading = esc_html__( 'Post Archive by Year: ', 'noo-hermosa' ) . get_the_date( 'Y' );

		} elseif ( is_month() ) {
    		$heading = esc_html__( 'Post Archive by Month: ', 'noo-hermosa' ) . get_the_date( 'F,Y' );

		} elseif ( is_day() ) {
    		$heading = esc_html__( 'Post Archive by Day: ', 'noo-hermosa' ) . get_the_date( 'F j, Y' );

		} elseif ( is_404() ) {
    		$heading = esc_html__( 'Oops! We could not find anything.', 'noo-hermosa' );
    		$archive_title =  esc_html__( 'Would you like going else where to find your stuff.', 'noo-hermosa' );

		} elseif ( is_category() ) {
			$heading = single_cat_title( '', false );

		} elseif ( is_tag() ) {
			$heading = single_cat_title( '', false );

		} elseif ( is_tax() ) {
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$heading = $term->name;

			if ( is_tax('class_category') ) {
				$archive_title = noo_hermosa_get_option( 'noo_class_heading_desc', '' );
			}
			elseif ( is_tax('event_category') ) {
				$archive_title = noo_hermosa_get_option( 'noo_event_heading_desc', '' );
			}

		} elseif (is_singular('noo_portfolio')){
            $heading = noo_hermosa_get_option( 'noo_portfolio_heading_title', esc_html__( 'Portfolio', 'noo-hermosa' ) );
            $archive_title = noo_hermosa_get_option( 'noo_portfolio_heading_desc', '' );
        } elseif ( is_singular( 'product' ) ) {
			$heading = get_the_title();
			$archive_title = noo_hermosa_get_option( 'noo_shop_heading_desc', '' );

		}  elseif ( is_single() ) {
			if(is_singular('post')){
				$heading = esc_html__('Blog Detail', 'noo-hermosa');
				$archive_title = noo_hermosa_get_option( 'noo_blog_heading_desc', '' );
			}
			elseif(is_singular('noo_event')){
				$heading = esc_html__('Event Detail', 'noo-hermosa');
				$archive_title = noo_hermosa_get_option( 'noo_event_heading_desc', '' );
			}
			elseif(is_singular('noo_class')) {
				$heading = esc_html__('Class Detail', 'noo-hermosa');
				$archive_title = noo_hermosa_get_option( 'noo_class_heading_desc', '' );
			}
			elseif(is_singular('noo_trainer')){
				$heading = esc_html__('Trainer Profile', 'noo-hermosa');
				$archive_title = noo_hermosa_get_option( 'noo_trainer_heading_desc', '' );
			}
			else{
				$heading = get_the_title();
			}
			

		} elseif( is_page() ) {
			$heading = get_the_title();
			$archive_title = noo_hermosa_get_post_meta(get_the_ID(), '_noo_wp_page_page_description', '');

		} elseif( is_post_type_archive('noo_event') ) {
			$heading = noo_hermosa_get_option( 'noo_event_heading_title', esc_html__( 'Events List', 'noo-hermosa' ) );
			$archive_title = noo_hermosa_get_option( 'noo_event_heading_desc', '' );

		} elseif( is_post_type_archive('noo_class') ) {

			$heading = noo_hermosa_get_option( 'noo_class_heading_title', esc_html__( 'Class List', 'noo-hermosa' ) );
			$archive_title = noo_hermosa_get_option( 'noo_class_heading_desc', '' );

		} elseif( is_post_type_archive('noo_trainer') ) {
			$heading = noo_hermosa_get_option( 'noo_trainer_heading_title', esc_html__( 'Trainer List', 'noo-hermosa' ) );
			$archive_title = noo_hermosa_get_option( 'noo_trainer_heading_desc', '' );

		}
		return array($heading, $archive_title, $archive_desc);
	}
endif;

if (!function_exists('noo_hermosa_get_page_heading_image')):
	function noo_hermosa_get_page_heading_image() {
		$image = '';
		if( ! noo_hermosa_get_option( 'noo_page_heading', true ) ) {
			return $image;
		}
		if( NOO_WOOCOMMERCE_EXIST && is_shop() ) {
			$image = noo_hermosa_get_image_option( 'noo_shop_heading_image', '' );
		} elseif ( is_home() ) {
			$image = noo_hermosa_get_image_option( 'noo_blog_heading_image', '' );
		} elseif( is_category() || is_tag() ) {
			$queried_object = get_queried_object();
			$image			= noo_hermosa_get_term_meta( $queried_object->term_id, 'heading_image', '' );
			$image			= empty( $image ) ? noo_hermosa_get_image_option( 'noo_blog_heading_image', '' ) : $image;
		} elseif( NOO_WOOCOMMERCE_EXIST && ( is_product_category() || is_product_tag() ) ) {
			$queried_object = get_queried_object();
			$image			= noo_hermosa_get_term_meta( $queried_object->term_id, 'heading_image', '' );
			$image			= empty( $image ) ? noo_hermosa_get_image_option( 'noo_shop_heading_image', '' ) : $image;
		} elseif ( is_singular('product' ) || is_page() ) {
			$image = noo_hermosa_get_post_meta(get_the_ID(), '_heading_image', '');
			if (  is_singular('product' ) ) {
				$image= empty( $image ) ? noo_hermosa_get_image_option( 'noo_woocommerce_product_heading_image', '' ) : $image;
			}
		} elseif (is_singular('noo_class')) {
			$image = noo_hermosa_get_image_option( 'noo_class_heading_image', '' );
		} elseif (is_singular('noo_trainer')) {
			$image = noo_hermosa_get_image_option( 'noo_trainer_heading_image', '' );
			$image = empty( $image ) ? noo_hermosa_get_image_option( 'noo_blog_heading_image', '' ) : $image;
		} elseif (is_singular('noo_event')) {
			$image = noo_hermosa_get_image_option( 'noo_event_heading_image', '' );
			$image = empty( $image ) ? noo_hermosa_get_image_option( 'noo_blog_heading_image', '' ) : $image;
		}elseif (is_singular('noo_portfolio')){
	        $image=noo_hermosa_get_image_option('noo_portfolio_heading_image', '');
        } elseif (is_single()) {
			$image = noo_hermosa_get_image_option( 'noo_blog_heading_image', '' );
		} elseif( is_post_type_archive('noo_event') ) {
			$image = noo_hermosa_get_image_option( 'noo_event_heading_image', '' );
		} elseif( is_post_type_archive('noo_class') ) {
			$image = noo_hermosa_get_image_option( 'noo_classes_heading_image', '' );
		} elseif( is_post_type_archive('noo_trainer') ) {
			$image = noo_hermosa_get_image_option( 'noo_trainer_heading_image', '' );
		} elseif( is_tax('class_category') ) {
			$image = noo_hermosa_get_image_option( 'noo_class_heading_image', '' );
		}

		if( !empty( $image ) && is_numeric( $image ) ) $image = wp_get_attachment_url( $image );

		return $image;
	}
endif;

if (!function_exists('noo_hermosa_has_featured_content')):
	function noo_hermosa_has_featured_content($post_id = null) {
		$post_id = (null === $post_id) ? get_the_ID() : $post_id;

		$post_type = get_post_type($post_id);
		$prefix = '';
		$post_format = '';
		
		if ($post_type == 'post') {
			$prefix = '_noo_wp_post';
			$post_format = get_post_format($post_id);
		}
		
		switch ($post_format) {
			case 'image':
				$main_image = noo_hermosa_get_post_meta($post_id, "{$prefix}_main_image", 'featured');
				if( $main_image == 'featured') {
					return has_post_thumbnail($post_id);
				}

				return has_post_thumbnail($post_id) || ( (bool)noo_hermosa_get_post_meta($post_id, "{$prefix}_image", '') );
			case 'gallery':
				if (!is_singular()) {
					$preview_content = noo_hermosa_get_post_meta($post_id, "{$prefix}_gallery_preview", 'slideshow');
					if ($preview_content == 'featured') {
						return has_post_thumbnail($post_id);
					}
				}
				
				return (bool)noo_hermosa_get_post_meta($post_id, "{$prefix}_gallery", '');
			case 'video':
				if (!is_singular()) {
					$preview_content = noo_hermosa_get_post_meta($post_id, "{$prefix}_preview_video", 'both');
					if ($preview_content == 'featured') {
						return has_post_thumbnail($post_id);
					}
				}
				
				$m4v_video = (bool)noo_hermosa_get_post_meta($post_id, "{$prefix}_video_m4v", '');
				$ogv_video = (bool)noo_hermosa_get_post_meta($post_id, "{$prefix}_video_ogv", '');
				$embed_video = (bool)noo_hermosa_get_post_meta($post_id, "{$prefix}_video_embed", '');
				
				return $m4v_video || $ogv_video || $embed_video;
			case 'link':
			case 'quote':
				return false;
				
			case 'audio':
				$mp3_audio = (bool)noo_hermosa_get_post_meta($post_id, "{$prefix}_audio_mp3", '');
				$oga_audio = (bool)noo_hermosa_get_post_meta($post_id, "{$prefix}_audio_oga", '');
				$embed_audio = (bool)noo_hermosa_get_post_meta($post_id, "{$prefix}_audio_embed", '');
				return $mp3_audio || $oga_audio || $embed_audio;
			default: // standard post format
				return has_post_thumbnail($post_id);
		}
		
		return false;
	}
endif;

// Get allowed HTML tag.
if( !function_exists('noo_hermosa_allowed_html') ) :
	function noo_hermosa_allowed_html() {
		return apply_filters( 'noo_hermosa_allowed_html', array(
			'a' => array(
				'href' => array(),
				'target' => array(),
				'title' => array(),
				'rel' => array(),
				'class' => array(),
				'style' => array(),
			),
			'img' => array(
				'src' => array(),
				'class' => array(),
				'style' => array(),
				'data-*' => array(),
			),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'p' => array(
				'class' => array(),
				'style' => array()
			),
			'br' => array(
				'class' => array(),
				'style' => array()
			),
			'hr' => array(
				'class' => array(),
				'style' => array()
			),
			'span' => array(
				'class' => array(),
				'style' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'em' => array(
				'class' => array(),
				'style' => array()
			),
			'strong' => array(
				'class' => array(),
				'style' => array()
			),
			'small' => array(
				'class' => array(),
				'style' => array()
			),
			'b' => array(
				'class' => array(),
				'style' => array()
			),
			'i' => array(
				'class' => array(),
				'style' => array()
			),
			'u' => array(
				'class' => array(),
				'style' => array()
			),
			'ul' => array(
				'class' => array(),
				'style' => array()
			),
			'ol' => array(
				'class' => array(),
				'style' => array()
			),
			'li' => array(
				'class' => array(),
				'style' => array()
			),
			'blockquote' => array(
				'class' => array(),
				'style' => array()
			),
		) );
	}
endif;

// Allow only unharmed HTML tag.
if( !function_exists('noo_hermosa_html_content_filter') ) :
	function noo_hermosa_html_content_filter( $content = '' ) {
		return wp_kses( $content, noo_hermosa_allowed_html() );
	}
endif;

// escape language with HTML.
if( !function_exists('noo_hermosa_kses') ) :
	function noo_hermosa_kses( $text = '' ) {
		return wp_kses( $text, noo_hermosa_allowed_html() );
	}
endif;

/* -------------------------------------------------------
 * Create functions noo_hermosa_get_page_id_by_template
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_hermosa_get_page_id_by_template' ) ) :
	
	function noo_hermosa_get_page_id_by_template( $page_template = '' ) {

		$pages = get_pages(array(
			'meta_key' => '_wp_page_template',
			'meta_value' => $page_template
		));

		if( $pages ){
			return $pages[0]->ID;
		}
		return false;

	}

endif;

/** ====== END noo_hermosa_get_page_id_by_template ====== **/


/**
 * Custom filter widget title
 *
 * @package 	Noo_Hermosa
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_hermosa_custom_widget_title' ) ) :
	
	function noo_hermosa_custom_widget_title( $title ) {

		/**
		 * Process string
		 */
		if ( !empty($title) && '' != $title ) {
			
			$title = explode( ' ', $title );
			$title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
			$title = implode( ' ', $title );	
			$title = '<span class="wrap-title">' . $title . '</span>';
			
		}
		
		return $title;

	}

	add_filter( 'widget_title', 'noo_hermosa_custom_widget_title' );

endif;

/* -------------------------------------------------------
 * Create functions noo_hermosa_relative_time
 * 
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_hermosa_relative_time' ) ) :
	
	function noo_hermosa_relative_time() {

		return human_time_diff(get_comment_time('U'), current_time('timestamp'));

	}

endif;

/** ====== END noo_hermosa_relative_time ====== **/

if( !function_exists('noo_hermosa_get_instagram_data') ) :
    // using standard_resolution / thumbnail / low_resolution
    function noo_hermosa_get_instagram_data($username = 'nootheme', $cache_hours = '5', $nr_images = '4', $resolution = 'thumbnail', $randomise = false) {
	    $opt_name    = 'noo_insta_'.md5( $username );
        $instaData 	 = get_transient( $opt_name );
        $user_opt    = get_option( $opt_name );

        if( !in_array($resolution, array( 'low_resolution', 'thumbnail', 'standard_resolution' ) ) ) $resolution = 'thumbnail';
               if ( false === $instaData
            || $user_opt['username']    != $username
            || $user_opt['cache_hours'] != $cache_hours
            || $user_opt['nr_images']   != $nr_images
            || $user_opt['resolution']  != $resolution
        ) {
            $instaData    = array();
            $insta_url    = 'https://instagram.com/';
            $user_profile = $insta_url.$username;
            $json     	  = wp_remote_get( $user_profile, array( 'sslverify' => false, 'timeout'=> 60 ) );
            if ( !is_wp_error( $json ) && $json['response']['code'] == 200 ) {
                $json 	  = $json['body'];
                $json     = strstr( $json, 'window._sharedData = ' );
                $json     = str_replace('window._sharedData = ', '', $json);

                // Compatibility for version of php where strstr() doesnt accept third parameter
                if ( version_compare( phpversion(), '5.3.10', '<' ) ) {
                    $json = substr( $json, 0, strpos($json, '</script>' ) );
                } else {
                    $json = strstr( $json, '</script>', true );
                }

                $json     = rtrim( $json, ';' );

                // Function json_last_error() is not available before PHP * 5.3.0 version
                if ( function_exists( 'json_last_error' ) ) {

                    ( $results = json_decode( $json, true ) ) && json_last_error() == JSON_ERROR_NONE;

                } else {

                    $results = json_decode( $json, true );
                }

                if ( ( $results ) && is_array( $results ) && isset( $results[ 'entry_data' ][ 'ProfilePage' ] ) && is_array( $results[ 'entry_data' ][ 'ProfilePage' ] ) ) {

				    $results = $results[ 'entry_data' ][ 'ProfilePage' ][ 0 ][ 'graphql' ][ 'user' ][ 'edge_owner_to_timeline_media' ][ 'edges' ];

				    foreach ( $results as $result ) {

					    $edge = $result[ 'node' ];

					    $caption = __( 'Instagram Image', 'noo-hermosa' );
					    if ( ! empty( $egde[ 'edge_media_to_caption' ][ 'edges' ][ 0 ][ 'node' ][ 'text' ] ) ) {
						    $caption = wp_kses( $edge[ 'edge_media_to_caption' ][ 'edges' ][ 0 ][ 'node' ][ 'text' ], array() );
					    }

					    $image = $edge[ 'display_url' ];
					    $id    = $edge[ 'id' ];
					    $link  = trailingslashit( '//instagram.com/p/' . $edge[ 'shortcode' ] );
					    $text  = noo_hermosa_utf8_4byte_to_3byte( $caption );

					    if ( ! $edge[ 'is_video' ] == true ) {
						    array_push( $instaData, array(
							    'id'        => $id,
							    'user_name' => $username,
							    'user_url'  => $user_profile,
							    'text'      => $text,
							    'image'     => $image,
							    'link'      => $link,
						    ) );
					    }
				    } // end -> foreach

			    } // end -> ( $results ) && is_array( $results ) )
                if ( $instaData ) {
                    set_transient( $opt_name, $instaData, $cache_hours * 60 * 60 );
                    $user_options = compact('username', 'cache_hours', 'nr_images', 'resolution');
                    update_option( $opt_name, $user_options );
                } else {
                    delete_option( $opt_name );
                    delete_transient( $opt_name );
                }// end -> true $instaData
            } else {
                delete_option( $opt_name );
                delete_transient( $opt_name );
            }
        }

        if( $randomise ) shuffle( $instaData );
        return array_slice($instaData, 0, $nr_images, true);
    }
endif;

if ( !function_exists( 'noo_hermosa_utf8_4byte_to_3byte' ) ) :
function noo_hermosa_utf8_4byte_to_3byte( $input ) {

    if (!empty($input)) {
        $utf8_2byte = 0xC0 /*1100 0000*/; $utf8_2byte_bmask = 0xE0 /*1110 0000*/;
        $utf8_3byte = 0xE0 /*1110 0000*/; $utf8_3byte_bmask = 0XF0 /*1111 0000*/;
        $utf8_4byte = 0xF0 /*1111 0000*/; $utf8_4byte_bmask = 0xF8 /*1111 1000*/;

        $sanitized = "";
        $len = strlen($input);
        for ($i = 0; $i < $len; ++$i) {
            $mb_char = $input[$i]; // Potentially a multibyte sequence
            $byte = ord($mb_char);
            if (($byte & $utf8_2byte_bmask) == $utf8_2byte) {
                $mb_char .= $input[++$i];
            }
            else if (($byte & $utf8_3byte_bmask) == $utf8_3byte) {
                $mb_char .= $input[++$i];
                $mb_char .= $input[++$i];
            }
            else if (($byte & $utf8_4byte_bmask) == $utf8_4byte) {
                // Replace with ? to avoid MySQL exception
                $mb_char = '?';
                $i += 3;
            }

            $sanitized .=  $mb_char;
        }

        $input= $sanitized;
    }

    return $input;
}
endif;


/* -------------------------------------------------------
 * Create functions noo_hermosa_bio_author
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_hermosa_bio_author' ) ) :
	
	function noo_hermosa_bio_author() {

		?>
			<div class="meta-author">
	            <?php echo get_avatar( get_the_author_meta( 'user_email', 145 ) ) ; ?>
	            <div class="box-author-info">
	                <h5>
	                    <a title="<?php printf( esc_html__( 'Post by %s','noo-hermosa'), get_the_author() ); ?>" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
	                        <?php echo get_the_author() ?>
	                    </a>
	                </h5>
	                <p>
	                    <?php the_author_meta( 'description' ) ?>
	                </p>
	                <ul class="author-social">
						<?php
							$google_profile = get_the_author_meta( 'google_profile' );
							if ( !empty( $google_profile ) ) {
								echo '<li class="google"><a href="' . esc_url($google_profile) . '" rel="author"><i class="fa fa-google"></i></a></li>';
							}
							
							$facebook_profile = get_the_author_meta( 'facebook_profile' );
							if ( !empty( $facebook_profile ) ) {
								echo '<li class="facebook"><a href="' . esc_url($facebook_profile) . '"><i class="fa fa-facebook"></i></a></li>';
							}
							
							$twitter_profile = get_the_author_meta( 'twitter_profile' );
							if ( !empty( $twitter_profile ) ) {
								echo '<li class="twitter"><a href="' . esc_url($twitter_profile) . '"><i class="fa fa-twitter"></i></a></li>';
							}
							
							$linkedin_profile = get_the_author_meta( 'linkedin_profile' );
							if ( !empty( $linkedin_profile ) ) {
								echo '<li class="linkedin"><a href="' . esc_url($linkedin_profile) . '"><i class="fa fa-linkedin"></i></a></li>';
							}
						?>
					</ul>
	            </div><!-- /.box-info-author -->
	        </div>
		<?php

	}

endif;

/** ====== END noo_hermosa_bio_author ====== **/


/* -------------------------------------------------------
 * Create functions noo_hermosa_ajax_like_post
 * ------------------------------------------------------- */

if ( ! function_exists( 'noo_hermosa_ajax_like_post' ) ) :
	
	function noo_hermosa_ajax_like_post() {

		/**
		 * Verify ajax
		 */
		check_ajax_referer( 'noo-new', 'security', esc_html__( 'Not verify like post', 'noo-hermosa' ) );

		/**
		 * Process
		 */
		if ( isset( $_POST['id'] ) && is_numeric( $_POST['id'] ) ) :
			$id       = $_POST['id'];
			$noo_like = noo_hermosa_get_post_meta( $id, 'noo_like' );

			if ( empty( $noo_like ) ) :

				update_post_meta( $id, 'noo_like', 1 );

			else :

				update_post_meta( $id, 'noo_like', $noo_like + 1 );

			endif;

			$count_like = noo_hermosa_get_post_meta( $id, 'noo_like' );

			$response = array(
				'status' => 'success',
				'msg'    => esc_html__( 'Update like post successfully.', 'noo-hermosa' ),
				'count'	 => $count_like
			);

		else :
			$response = array(
				'status' => 'error',
				'msg'    => esc_html__( 'Not support format in post. Please check again!', 'noo-hermosa' )
			);
		endif;

		wp_send_json( $response );

	}

	add_action( 'wp_ajax_like_post', 'noo_hermosa_ajax_like_post' );
	add_action( 'wp_ajax_nopriv_like_post', 'noo_hermosa_ajax_like_post' );

endif;

/** ====== END noo_hermosa_ajax_like_post ====== **/