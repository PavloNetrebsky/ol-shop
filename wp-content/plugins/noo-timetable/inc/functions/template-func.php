<?php
/**
 * Provide core processing functions.
 *
 * @author      NooTheme
 * @category    Library
 * @package     NooTimetable/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !function_exists( 'noo_timetable_get_post_meta' ) ) {
	// Normal get option
	function noo_timetable_get_post_meta( $post_ID = null, $meta_key, $default = null ) {
		$post_ID = (null === $post_ID) ? get_the_ID() : $post_ID;

		$value = get_post_meta( $post_ID, $meta_key, true );

		// Sanitize for on/off checkbox
		$value = ( $value == 'off' ? false : $value );
		$value = ( $value == 'on' ? true : $value );
		if( ( $value === null || $value === '' ) && ( $default != null && $default != '' ) ) {
			$value = $default;
		}

		return apply_filters( 'noo_timetable_post_meta', $value, $post_ID, $meta_key, $default );
	}
}

if( !function_exists( 'noo_timetable_get_option' ) ) {
	if( isset( $_POST['noo_customize_ajax'] ) ) {
		
		// AJAX customizer get option
		function noo_timetable_get_option( $option, $default = null ) {
			global $noo_customizer;
			if( !isset( $noo_customizer ) || empty( $noo_customizer ) ) {
				if ( isset( $_POST['customized'] ) )
					$noo_customizer  = json_decode( wp_unslash( $_POST['customized'] ), true );
				else
					$noo_customizer  = false;
			}

			$value = isset( $noo_customizer[ $option ] ) ? $noo_customizer[ $option ] : get_theme_mod( $option, $default );
			$value = ( $value === null || $value === '' ) ? $default : $value;

			return apply_filters( 'noo_timetable_settings', $value, $option, $default );
		}

	} else {
		
		// Normal get option
		function noo_timetable_get_option( $option, $default = null ) {
			$value = get_theme_mod( $option, $default );
			// $value = get_option( $option, $default );
			$value = ( $value === null || $value === '' ) ? $default : $value;

			return apply_filters( 'noo_timetable_settings', $value, $option, $default );
		}

	}
}


// Get allowed HTML tag.
if( !function_exists('noo_timetable_allowed_html') ) {
	function noo_timetable_allowed_html() {
		return apply_filters( 'noo_timetable_allowed_html', array(
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
}

// Allow only unharmed HTML tag.
if( !function_exists('noo_timetable_html_content_filter') ) {
	function noo_timetable_html_content_filter( $content = '' ) {
		return wp_kses( $content, noo_timetable_allowed_html() );
	}
}

// escape language with HTML.
if( !function_exists('noo_timetable_kses') ) {
	function noo_timetable_kses( $text = '' ) {
		return wp_kses( $text, noo_timetable_allowed_html() );
	}
}

if (!function_exists('noo_timetable_get_sidebar_name')) {
	function noo_timetable_get_sidebar_name($id = '') {
		if (empty($id)) return '';
		
		global $wp_registered_sidebars;
		if ($wp_registered_sidebars && !is_wp_error($wp_registered_sidebars)) {
			foreach ($wp_registered_sidebars as $sidebar) {
				if ($sidebar['id'] == $id) return $sidebar['name'];
			}
		}
		
		return '';
	}
}

if( !function_exists('noo_timetable_get_term_meta') ) {
	function noo_timetable_get_term_meta( $term_id = null, $meta_key = '', $default = null ) {
		if( empty( $term_id ) || empty( $meta_key) ) {
			return null;
		}

		$term_meta = get_option( 'taxonomy_' . $term_id );
		$value = isset( $term_meta[$meta_key] ) ? $term_meta[$meta_key] : null;

		if( ( $value === null || $value === '' ) && ( $default != null && $default != '' ) ) {
			$value = $default;
		}

		return apply_filters( 'noo_timetable_term_meta', $value, $term_id, $meta_key, $default );
	}
}

if( !function_exists('noo_timetable_body_class') ) {
	function noo_timetable_body_class( $classes ) {
		$classes = (array) $classes;

		if ( get_post_type() == 'noo_trainer' ) {
			$classes[] = 'noo-timetable-page';
			$classes[] = 'trainer-page';
		}

		elseif ( get_post_type() == 'noo_class' ) {
			$classes[] = 'noo-timetable-page';
			$classes[] = 'class-page';
		}

		elseif ( get_post_type() == 'noo_event' ) {
			$classes[] = 'noo-timetable-page';
			$classes[] = 'event-page';
		}

		return array_unique( $classes );
	}
}

if( !function_exists('noo_timetable_require_file') ) {
	function noo_timetable_require_file( $template_name, $template_path = '', $default_path = '' ) {

		$located = noo_timetable_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			return;
		}

		require $located;
	}
}

if( !function_exists('noo_timetable_get_template') ) {
	function noo_timetable_get_template( $template_name, $template_path = '', $default_path = '' ) {

		$located = noo_timetable_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			return;
		}

		include( $located );
	}
}

if( !function_exists('noo_timetable_locate_template') ) {
	function noo_timetable_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = Noo__Timetable__Main::template_path();
		}

		if ( ! $default_path ) {
			$default_path = Noo__Timetable__Main::plugin_path() . '/templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);

		// Get default template/
		if ( ! $template  ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'noo_timetable_locate_template', $template, $template_name, $template_path );
	}
}

if( !function_exists('noo_timetable_post_thumbnail') ) {
	function noo_timetable_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
		?>

		<div class="post-thumbnail">
			<?php the_post_thumbnail(); ?>
		</div><!-- .post-thumbnail -->

		<?php else : ?>

		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
			<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
		</a>

		<?php endif; // End is_singular()
	}
}

if( !function_exists('noo_timetable_excerpt') ) {
	function noo_timetable_excerpt( $class = 'entry-summary' ) {
		$class = esc_attr( $class );

		if ( has_excerpt() || is_search() ) : ?>
			<div class="<?php echo $class; ?>">
				<?php the_excerpt(); ?>
			</div><!-- .<?php echo $class; ?> -->
		<?php endif;
	}
}

if( !function_exists('noo_timetable_is_nootheme') ) {
	function noo_timetable_is_nootheme() {

		$template = get_option( 'template' );

		$nootheme = array(
			'noo-citilights',
			'noo-jobmonster',
			'noo-medicus',
			'noo-dreamer',
			'noo-yogi',
			'noo-wemusic',
			'noo-chilli',
			'noo-carle',
			'noo-ivent',
			'noo-organici',
			'noo-emigo',
			'noo-timetable',
			'noo-umbra',
			'noo-visionary',
		);

		return in_array($template, $nootheme);
	}
}

if( !function_exists('noo_timetable_before_main_content') ) {
	function noo_timetable_before_main_content() {
		// $layout_style = NOO_Settings()->get_option('noo_trainer_style', 'grid');
		$template = get_option( 'template' );
		if ( noo_timetable_is_nootheme() ) {
			echo '<div id="content" class="container-wrap"><div class="main-content container"><div class="row">';
		} else {
			switch( $template ) {
				default :
					echo '<div id="noo-content" class="noo-container"><div class="container-wrap noo-row"><div class="noo-timetable-main noo-md-9">';
					break;
			}

		}
	}
}
add_action('before_noo_timetable_main_wrap', 'noo_timetable_before_main_content');

if( !function_exists('noo_timetable_after_main_content') ) {
	function noo_timetable_after_main_content() {
		$template = get_option( 'template' );

		if ( noo_timetable_is_nootheme() ) {
			echo '</div></div></div>';
		} else {
			switch( $template ) {
				default :
					echo '</div>';
					break;
			}
		}
	}
}
add_action('after_noo_timetable_main_wrap', 'noo_timetable_after_main_content');


if( !function_exists('noo_timetable_after_content') ) {
	function noo_timetable_after_content() {
		$template = get_option( 'template' );

		if ( noo_timetable_is_nootheme() ) {
			echo '</div></div></div>';
		} else {
			switch( $template ) {
				default :
					echo '</div>';
					break;
			}
		}
	}
}
add_action('after_noo_timetable_wrap', 'noo_timetable_after_content');


if( !function_exists('noo_timetable_get_sidebar') ) {
	function noo_timetable_get_sidebar($sidebar_id) {
		global $wp_locale;
		// if ( ! $sidebar_id ) return;
		
		$template = get_option( 'template' ) . '_sidebar';
		$template .= ( noo_timetable_is_nootheme() ) ? ' noo-sidebar col-md-3' : ' sidebar widget-area';
		$template = apply_filters( 'noo_timetable_sidebar_class', $template );

		// Update
		/*------ Class filter ------*/
		$show_level_class_filter    = !is_tax('class_level') && ($levels = get_terms('class_level'));
		$show_cat_class_filter = !is_tax('class_category') && ($class_categories = get_terms('class_category',array('hide_empty'=>0)));
		$trainers = get_posts(array('post_type'=>'noo_trainer','posts_per_page'=>-1,'suppress_filters'=>0));
		/*------ Event Filter ------*/
		$show_event_class_filter = !is_tax('event_category') && ($event_categories = get_terms('event_category',array('hide_empty'=>0)));
		$show_event_organizer_filter = !is_tax('event_organizers') && ($event_organizers = get_posts(array('post_type'=>'event_organizers','posts_per_page'=>-1,'suppress_filters'=>0)));
		?>
			<div class=" noo-sidebar noo-md-3">
				<?php if ( get_post_type() === 'noo_class' && !is_single()):?>
					<?php if($show_level_class_filter || $show_cat_class_filter || !empty($trainers)):?>
						<h2 class="widget-title"><?php echo esc_html_e('Class Filter', 'noo-timetable')?></h2>
					<?php endif;?>
					<?php if($show_level_class_filter):?>
						<div class="widget-class-filter search-class-level" data-group="level">
		                    <select class="widget-class-filter-control">
		                        <option value=""><?php esc_html_e('Select Level','noo-timetable')?></option>
		                        <?php foreach ((array)$levels as $level):?>
		                            <option value="filter-level-<?php echo esc_attr($level->term_id)?>"><?php echo esc_html($level->name)?></option>
		                        <?php endforeach;?>
		                    </select>
		                </div>
		            <?php endif;?>
		            <?php if($show_cat_class_filter):?>
		                <div class="widget-class-filter search-class-category" data-group="category">
		                    <select class="widget-class-filter-control">
		                        <option value=""><?php esc_html_e('Select Category','noo-timetable')?></option>
		                        <?php foreach ((array)$class_categories as $category):?>
		                            <option value="filter-cat-<?php echo esc_attr($category->term_id)?>"><?php echo esc_html($category->name)?></option>
		                        <?php endforeach;?>
		                    </select>
		                </div>
		            <?php endif;?>
	                <?php if(!empty($trainers)):
	                $current_trainer = isset( $_GET['trainer'] ) && !empty( $_GET['trainer'] ) ? $_GET['trainer'] : '';
	                ?>
		                <div class="widget-class-filter search-class-trainer" data-group="trainer">
		                    <select class="widget-class-filter-control">
		                        <option value=""><?php esc_html_e('Select Trainer','noo-timetable')?></option>
		                        <?php foreach ((array)$trainers as $trainer):?>
		                            <option <?php selected( $current_trainer, $trainer->ID ); ?> value="filter-trainer-<?php echo esc_attr($trainer->ID)?>"><?php echo esc_html($trainer->post_title)?></option>
		                        <?php endforeach;?>
		                    </select>
		                </div>
	            	<?php endif;?>
	                <div class="widget-class-filter search-class-weekday" data-group="day">
	                    <h4><?php _e('Filter class by days:','noo-timetable')?></h4>
	                    <?php for ($day_index = 0; $day_index <= 6; $day_index++) : ?>
	                    <label class="noo-xs-6">
	                        <input type="checkbox" class="widget-class-filter-control" value="filter-day-<?php echo esc_attr($day_index)?>"> <?php echo esc_html($wp_locale->get_weekday($day_index)) ?>
	                    </label>
	                    <?php
	                    endfor;
	                    ?>
	                </div>
	             <?php elseif(get_post_type() === 'noo_event' && !is_single()):?>
	             	<h2 class="widget-tile"><?php echo esc_html_e('Event Filter')?></h2>
	             	<?php if($show_cat_class_filter):?>
		                <div class="widget-event-filter search-event-category" data-group="category">
		                    <select class="widget-event-filter-control">
		                        <option value=""><?php esc_html_e('Select Category','noo-timetable')?></option>
		                        <?php foreach ((array)$event_categories as $category):?>
		                            <option value="event_category-<?php echo esc_attr($category->slug)?>"><?php echo esc_html($category->name)?></option>
		                        <?php endforeach;?>
		                    </select>
		                </div>
		            <?php endif;?>
		            <?php if($show_event_organizer_filter):
	                ?>
		                <div class="widget-event-filter search-event-organizer" data-group="organizer">
		                    <select class="widget-event-filter-control">
		                        <option value=""><?php esc_html_e('Select Organizer','noo-timetable')?></option>
		                        <?php foreach ((array)$event_organizers as $event_organizer):?>
		                            <option value="filter-organizer-<?php echo esc_attr($event_organizer->ID)?>"><?php echo esc_html($event_organizer->post_title)?></option>
		                        <?php endforeach;?>
		                    </select>
		                </div>
	            	<?php endif;?>
	            <?php endif;?>

				<?php
					if ( ! $sidebar_id ) return;
					// If noo_event....
					if ( get_post_type() === 'noo_event' ) {
						Noo__Timetable__Event::load_sidebar_info();
					}

					// If noo_class..... 
					if ( get_post_type() === 'noo_class' ) {
						Noo__Timetable__Class::load_sidebar_info();
					}
				?>
	
				<?php dynamic_sidebar( $sidebar_id ); ?>

			</div>
		</div>

		<?php
	}
}

if ( ! function_exists( 'noo_timetable_social_share' ) ) {
	function noo_timetable_social_share( $post_id = null, $prefix = 'noo_blog' ) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$post_type =  get_post_type($post_id);

		if(noo_timetable_get_option("{$prefix}_social", true ) === false) {
			return '';
		}

		$share_url     = urlencode( get_permalink() );
		$share_title   = urlencode( get_the_title() );
		$share_source  = urlencode( get_bloginfo( 'name' ) );
		$share_content = urlencode( get_the_content() );
		$share_media   = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
		$popup_attr    = 'resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0';

		$share_title  = noo_timetable_get_option( "{$prefix}_social_title", '' );
		$facebook     = noo_timetable_get_option( "{$prefix}_social_facebook", true );
		$twitter      = noo_timetable_get_option( "{$prefix}_social_twitter", true );
		$google		  = noo_timetable_get_option( "{$prefix}_social_google", true );
		$pinterest    = noo_timetable_get_option( "{$prefix}_social_pinterest", false );
		$linkedin     = noo_timetable_get_option( "{$prefix}_social_linkedin", false );
		$html = array();

		if ( $facebook || $twitter || $google || $pinterest || $linkedin ) {
			$html[] = '<div class="content-share">';
			if( $share_title !== '' ) {
				$html[] = '<p class="social-title">';
				$html[] = '  ' . $share_title;
				$html[] = '</p>';
			}
			$html[] = '<div class="noo-social social-share all-social-share">';

			if($facebook) {
				$html[] = '<a href="#share" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" class="fa fa-facebook noo-share"'
							. ' title="' . esc_html__( 'Share on Facebook', 'noo-timetable' ) . '"'
							. ' onclick="window.open(' 
								. "'http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}','popupFacebook','width=650,height=270,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			if($twitter) {
				$html[] = '<a href="#share" class="fa fa-twitter noo-share"'
							. ' title="' . esc_html__( 'Share on Twitter', 'noo-timetable' ) . '"'
							. ' onclick="window.open('
								. "'https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}','popupTwitter','width=500,height=370,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			if($google) {
				$html[] = '<a href="#share" class="fa fa-google-plus noo-share"'
							. ' title="' . esc_html__( 'Share on Google+', 'noo-timetable' ) . '"'
								. ' onclick="window.open('
								. "'https://plus.google.com/share?url={$share_url}','popupGooglePlus','width=650,height=226,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			if($pinterest) {
				$html[] = '<a href="#share" class="fa fa-pinterest noo-share"'
							. ' title="' . esc_html__( 'Share on Pinterest', 'noo-timetable' ) . '"'
							. ' onclick="window.open('
								. "'http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}','popupPinterest','width=750,height=265,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			if($linkedin) {
				$html[] = '<a href="#share" class="fa fa-linkedin noo-share"'
							. ' title="' . esc_html__( 'Share on LinkedIn', 'noo-timetable' ) . '"'
							. ' onclick="window.open('
								. "'http://www.linkedin.com/shareArticle?mini=true&amp;url={$share_url}&amp;title={$share_title}&amp;summary={$share_content}&amp;source={$share_source}','popupLinkedIn','width=610,height=480,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			$html[] = '</div>'; // .noo-social.social-share
			$html[] = '</div>'; // .share-wrap
		}

		echo implode("\n", $html);
	}
}

if( !function_exists('noo_timetable_pagination_normal') ) {
	function noo_timetable_pagination_normal( $args = array(), $query = null ) {
        global $wp_rewrite, $wp_query;
        if ( empty($query)) {
            $query = $wp_query;
        }
        if ( 1 >= $query->max_num_pages )
            return;


        $paged = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );

        $max_num_pages = intval( $query->max_num_pages );

        $defaults = array(
            'base'                   => esc_url( add_query_arg( 'paged', '%#%' ) ),
            'format'                 => '',
            'total'                  => $max_num_pages,
            'current'                => $paged,
            'prev_next'              => true,
            'prev_text'              => '<i class="fa fa-long-arrow-left"></i>',
            'next_text'              => '<i class="fa fa-long-arrow-right"></i>',
            'show_all'               => false,
            'end_size'               => 1,
            'mid_size'               => 1,
            'add_fragment'           => '',
            'type'                   => 'plain',
            'before'                 => '<div class="pagination list-center">',
            'after'                  => '</div>',
            'echo'                   => true,
            'use_search_permastruct' => true
        );

        $defaults = apply_filters( 'noo_timetable_pagination_args_defaults', $defaults );

        if( $wp_rewrite->using_permalinks() && ! is_search() ) {
            if ( !empty( $args['link'] ) ) {
                $defaults['base'] = $args['link'] . 'page/%#%';
            } else {
                $defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
            }
        }

        if ( is_search() )
            $defaults['use_search_permastruct'] = false;

        if ( is_search() ) {
            if ( class_exists( 'BP_Core_User' ) || $defaults['use_search_permastruct'] == false ) {
                $search_query = get_query_var( 's' );
                $paged = get_query_var( 'paged' );
                $base = esc_url( add_query_arg( 's', urlencode( $search_query ) ) );
                $base = esc_url( add_query_arg( 'paged', '%#%' ) );
                $defaults['base'] = $base;
            } else {
                $search_permastruct = $wp_rewrite->get_search_permastruct();
                if ( ! empty( $search_permastruct ) ) {
                    $base = get_search_link();
                    $base = esc_url( add_query_arg( 'paged', '%#%', $base ) );
                    $defaults['base'] = $base;
                }
            }
        }

        $args = wp_parse_args( $args, $defaults );

        $args = apply_filters( 'noo_timetable_pagination_args', $args );

        if ( 'array' == $args['type'] )
            $args['type'] = 'plain';

        $pattern = '/\?(.*?)\//i';

        preg_match( $pattern, $args['base'], $raw_querystring );

        if(!empty($raw_querystring)){
            if( $wp_rewrite->using_permalinks() && $raw_querystring )
                $raw_querystring[0] = str_replace( '', '', $raw_querystring[0] );
            $args['base'] = str_replace( $raw_querystring[0], '', $args['base'] );
            $args['base'] .= substr( $raw_querystring[0], 0, -1 );
        }
        $page_links = paginate_links( $args );

        $page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );

        $page_links = $args['before'] . $page_links . $args['after'];

        $page_links = apply_filters( 'noo_timetable_pagination', $page_links );

        $allow_tag_pagination = array(
			'a' => array(
				'href' => array(),
				'target' => array(),
				'title' => array(),
				'rel' => array(),
				'class' => array(),
				'style' => array(),
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'span' => array(
				'class' => array(),
				'style' => array()
			),
		);

        if ( $args['echo'] )
            echo wp_kses( $page_links, $allow_tag_pagination );
        else
            return wp_kses( $page_links, $allow_tag_pagination );
	}
}

if( !function_exists('noo_timetable_getJqueryUII18nLocale') ) {
	function noo_timetable_getJqueryUII18nLocale() {

	    $locale = str_replace( '_', '-', get_locale() );
	    $locale = substr($locale, 0, strpos($locale, '-'));
	    $allLoc = array(
	    	'ar',
	    	'id',
	    	'bg',
	    	'fa',
	    	'ru',
	    	'uk',
	    	'en',
	    	'el',
	    	'de',
	    	'nl',
	    	'lt',
	    	'tr',
	    	'fr',
	    	'es',
	    	'th',
	    	'pl',
	    	'pt',
	    	'ch',
	    	'se',
	    	'sv',
	    	'kr',
	    	'it',
	    	'da',
	    	'no',
	    	'ja',
	    	'vi',
	    	'sl',
	    	'cs',
	    	'hu'
	    );
	    if ( ! in_array($locale, $allLoc) ) {
	    	$locale = 'en';
	    }
	    $locale = apply_filters( 'noo_timetable_get_locale', $locale );
	    if ( $locale == '' ) {
	    	$locale = 'en';
	    }

	    return $locale;
	}
}

if( !function_exists('noo_timetable_get_presets_color') ) {
	function noo_timetable_get_presets_color() {
		$presets_1 = array('#a5dff9', '#ef5285', '#60c5ba', '#feee7d');
        $presets_2 = array('#ff7473', '#ffc952', '#47b8e0', '#34314c');
        $presets_3 = array('#eb9f9f', '#f1bbba', '#a79c8e');
        $presets_4 = array('#6d819c', '#55967e', '#263959');
        $presets_5 = array('#E3E36A', '#C16200', '#881600', '#49010F');
        $presets   = array_merge($presets_1, $presets_2, $presets_3, $presets_4, $presets_5);

	    return $presets;
	}
}

if( !function_exists('noo_timetable_time_now') ){
	function noo_timetable_time_now() {

		$time_now = time();
        // Check setting timezone
        if( get_option('gmt_offset') != 0 )
            $time_now = current_time( 'timestamp', 0 );

        return $time_now;
	}
}

/** Filter Classes Using Custom WP_Query */

add_filter( 'posts_groupby', 'noo_classes_groupby');
function noo_classes_groupby($groupby) {
	global $wpdb;
	$groupby = "{$wpdb->posts}.ID";
	return $groupby;
}