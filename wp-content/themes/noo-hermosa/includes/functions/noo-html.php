<?php
/**
 * HTML Functions for NOO Framework.
 * This file contains various functions used for rendering site's small layouts.
 *
 * @package    NOO Framework
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */

// Featured Content
get_template_part( 'includes/functions/noo-html-featured' );

// Pagination
get_template_part( 'includes/functions/noo-html-pagination' );

// Breadcrumb by Theme
get_template_part( 'includes/functions/noo-html-breadcrumbs' );

if (!function_exists('noo_hermosa_get_readmore_link')):
	function noo_hermosa_get_readmore_link() {
		return '<a href="' . get_permalink() . '" class="read-more">'
		. '<span>'
		. esc_html__( 'Learn More', 'noo-hermosa' )
		. '</span>'
		. '</a>';
	}
endif;

if (!function_exists('noo_hermosa_readmore_link')):
	function noo_hermosa_readmore_link() {
		if( noo_hermosa_get_option('noo_blog_show_readmore', 1 ) ) {
			echo noo_hermosa_get_readmore_link();
		} else {
			echo '';
		}
	}
endif;

if (!function_exists('noo_hermosa_list_comments')):
	function noo_hermosa_list_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		GLOBAL $post;
		$avatar_size = isset($args['avatar_size']) ? $args['avatar_size'] : 60;
		?>
		<li id="li-comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
			<div class="comment-wrap">
				<div class="comment-img">
					<div class="img-thumbnail">
						<?php echo get_avatar($comment, $avatar_size); ?>
					</div>
				</div>
				<div id="comment-<?php comment_ID(); ?>" class="comment-block">
					<header class="comment-header">
						
						<cite class="comment-author"><?php echo get_comment_author_link(); ?></cite>

						<time datetime="<?php echo get_the_date('c'); ?>">
							<?php echo noo_hermosa_relative_time() . esc_html__( ' ago','noo-hermosa' ); ?>
						</time>
						<span class="comment-edit">
							<?php edit_comment_link('<i class="fa fa-edit"></i> ' . esc_html__( 'Edit', 'noo-hermosa')); ?>
						</span>

					</header>
					<div class="comment-content">
						<?php comment_text(); ?>
					</div>
					<span class="pull-left">
						<?php comment_reply_link(array_merge($args, array(
							'reply_text' => ( '<i class="fa fa-mail-reply"></i> ' . esc_html__('Reply', 'noo-hermosa')) ,
							'depth' => $depth,
							'max_depth' => $args['max_depth']
						))); ?>
					</span>
				</div>
			</div>
		<?php
	}
endif;

if ( ! function_exists( 'noo_hermosa_comment_form' ) ) :
	function noo_hermosa_comment_form( $args = array(), $post_id = null ) {
	    global $id;
	    $user = wp_get_current_user();
	    $user_identity = $user->exists() ? $user->display_name : '';

	    if ( null === $post_id ) {
	        $post_id = $id;
	    }
	    else {
	        $id = $post_id;
	    }

	    if ( comments_open( $post_id ) ) :
	    ?>
	    <div id="respond-wrap">
	        <?php 
				$commenter   = wp_get_current_commenter();
				$req         = get_option( 'require_name_email' );
				$aria_req    = ( $req ? " aria-required='true'" : '' );
				
				/**
				 * Process title reply
				 * @var [type]
				 */
				$title_reply    = esc_html__( 'Leave your thought', 'noo-hermosa' );
				$title_reply    = explode( ' ', $title_reply );
				$title_reply[0] = '<span class="first-word">' . esc_html( $title_reply[0] ) . '</span>';
				$title_reply    = implode( ' ', $title_reply );

	            $fields =  array(
	                'author' => '<div class="noo-row comment-form-head"><div class="comment-form-author noo-md-4"><input id="author" name="author" type="text" placeholder="' . esc_html__( 'Name', 'noo-hermosa' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></div>',
	                'email' => '<div class="comment-form-email noo-md-4"><input id="email" name="email" type="text" placeholder="' . esc_html__( 'Email', 'noo-hermosa' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></div>',
	                'url' => '<div class="comment-form-url noo-md-4"><input id="url" name="url" type="text" placeholder="' . esc_html__( 'Website', 'noo-hermosa' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
						    '" size="30" /></div></div>',
	                'comment_field'        => '<div class="comment-form-comment"><textarea placeholder="' . esc_html__( 'Message', 'noo-hermosa' ) . '" id="comment" name="comment" cols="40" rows="6" aria-required="true"></textarea></div>'
	            );
	            $comments_args = array(
	                    'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
	                    'logged_in_as'         => '<p class="logged-in-as">' . sprintf( wp_kses( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>', 'noo-hermosa' ), noo_hermosa_allowed_html() ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '</p>',
	                    'title_reply'          => sprintf('<span>%s</span>', $title_reply ),
	                    'title_reply_to'       => sprintf('<span>%s</span>', esc_html__( 'Leave a reply to %s', 'noo-hermosa' )),
	                    'cancel_reply_link'    => esc_html__( 'Click here to cancel the reply', 'noo-hermosa' ),
	                    'comment_notes_before' => '',
	                    'comment_notes_after'  => '',
	                    'label_submit'         => esc_html__( 'Submit', 'noo-hermosa' ),
	                    'comment_field'        =>'',
	                    'must_log_in'          => ''
	            );
	            if(is_user_logged_in()){
	                $comments_args['comment_field'] = '<p class="comment-form-comment"><textarea class="form-control" placeholder="' . esc_html__( 'Message', 'noo-hermosa' ) . '" id="comment" name="comment" cols="40" rows="6" aria-required="true"></textarea></p>';
	            }
	        comment_form($comments_args); 
	        ?>
	    </div>

	    <?php
	    endif;
	}
endif;

if ( ! function_exists( 'noo_hermosa_social_share' ) ) :
	function noo_hermosa_social_share( $post_id = null, $prefix = 'noo_blog' ) {
		$post_id = (null === $post_id) ? get_the_id() : $post_id;
		$post_type =  get_post_type($post_id);

		if(noo_hermosa_get_option("{$prefix}_social", true ) === false) {
			return '';
		}

		$share_url     = urlencode( get_permalink() );
		$share_title   = urlencode( get_the_title() );
		$share_source  = urlencode( get_bloginfo( 'name' ) );
		$share_content = urlencode( get_the_content() );
		$share_media   = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
		$popup_attr    = 'resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0';

		$share_title  = noo_hermosa_get_option( "{$prefix}_social_title", '' );
		$facebook     = noo_hermosa_get_option( "{$prefix}_social_facebook", true );
		$twitter      = noo_hermosa_get_option( "{$prefix}_social_twitter", true );
		$google		  = noo_hermosa_get_option( "{$prefix}_social_google", true );
		$pinterest    = noo_hermosa_get_option( "{$prefix}_social_pinterest", false );
		$linkedin     = noo_hermosa_get_option( "{$prefix}_social_linkedin", false );
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
							. ' title="' . esc_html__( 'Share on Facebook', 'noo-hermosa' ) . '"'
							. ' onclick="window.open(' 
								. "'http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}','popupFacebook','width=650,height=270,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			if($twitter) {
				$html[] = '<a href="#share" class="fa fa-twitter noo-share"'
							. ' title="' . esc_html__( 'Share on Twitter', 'noo-hermosa' ) . '"'
							. ' onclick="window.open('
								. "'https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}','popupTwitter','width=500,height=370,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			if($google) {
				$html[] = '<a href="#share" class="fa fa-google-plus noo-share"'
							. ' title="' . esc_html__( 'Share on Google+', 'noo-hermosa' ) . '"'
								. ' onclick="window.open('
								. "'https://plus.google.com/share?url={$share_url}','popupGooglePlus','width=650,height=226,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			if($pinterest) {
				$html[] = '<a href="#share" class="fa fa-pinterest noo-share"'
							. ' title="' . esc_html__( 'Share on Pinterest', 'noo-hermosa' ) . '"'
							. ' onclick="window.open('
								. "'http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}','popupPinterest','width=750,height=265,{$popup_attr}');"
								. ' return false;">';
				$html[] = '</a>';
			}

			if($linkedin) {
				$html[] = '<a href="#share" class="fa fa-linkedin noo-share"'
							. ' title="' . esc_html__( 'Share on LinkedIn', 'noo-hermosa' ) . '"'
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
endif;

if (!function_exists('noo_hermosa_social_icons')):
	function noo_hermosa_social_icons($position = 'topbar', $direction = '') {
		if ($position == 'topbar') {
			// Top Bar social
		} else {
			// Bottom Bar social
		}
		
		$class = isset($direction) ? $direction : '';
		$html = array();
		$html[] = '<div class="noo-social social-icons ' . $class . '">';
		
		$social_list = array(
			'facebook' => esc_html__( 'Facebook', 'noo-hermosa') ,
			'twitter' => esc_html__( 'Twitter', 'noo-hermosa') ,
			'google-plus' => esc_html__( 'Google+', 'noo-hermosa') ,
			'pinterest' => esc_html__( 'Pinterest', 'noo-hermosa') ,
			'linkedin' => esc_html__( 'LinkedIn', 'noo-hermosa') ,
			'rss' => esc_html__( 'RSS', 'noo-hermosa') ,
			'youtube' => esc_html__( 'YouTube', 'noo-hermosa') ,
			'instagram' => esc_html__( 'Instagram', 'noo-hermosa') ,
		);
		
		$social_html = array();
		foreach ($social_list as $key => $title) {
			$social = noo_hermosa_get_option("noo_social_{$key}", '');
			if ($social) {
				$social_html[] = '<a href="' . $social . '" title="' . $title . '" target="_blank">';
				$social_html[] = '<i class="fa fa-' . $key . '"></i>';
				$social_html[] = '</a>';
			}
		}
		
		if(empty($social_html)) {
			$social_html[] = esc_html__( 'No Social Media Link','noo-hermosa');
		}
		
		$html[] = implode("\n", $social_html);
		$html[] = '</div>';
		
		echo implode("\n", $html);
	}
endif;

if ( ! function_exists( 'noo_hermosa_entry_meta' ) ) :
    /**
     * Prints HTML with meta information for the categories, tags.
     *
     * @since Twenty Fifteen 1.0
     */
    function noo_hermosa_entry_meta() {

        /**
         * VAR
         */
        $noo_blog_social = noo_hermosa_get_option( 'noo_blog_social', false );

        if ( is_single( ) ) :

        	$tags_list = get_the_tag_list( '', ', ' );
            if ( $tags_list ) :
                printf( '<span class="tags-links"><i class="ion-ios-pricetags"></i>%1$s</span>',
                    $tags_list
                );
            endif;

            if ( !empty( $noo_blog_social ) ) :
                echo '<div class="single-social">';
                    noo_hermosa_social_share();
                echo '</div>';
            endif;

        else :

        	printf( '<span class="posted-on"><a href="%1$s" rel="bookmark">%2$s</a></span>',
	            esc_url( get_permalink() ),
	            get_the_date()
	        );

        	printf( '<span class="author vcard">%1$s %2$s <a class="url fn n" href="%3$s">%4$s</a></span>',
                get_avatar( get_the_author_meta( 'ID' ), 32 ),
                esc_html__( 'by', 'noo-hermosa' ),
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                get_the_author()
            );

            printf( '<span class="readmore"><a href="%1$s" title="%2$s">%3$s</a></span>',
	        	esc_url( get_permalink() ),
	        	get_the_title(),
	        	esc_html__( 'Read more', 'noo-hermosa' )
	        );

        endif;
        
    }

endif;

if(!function_exists('noo_hermosa_gototop')):
	function noo_hermosa_gototop(){
		if( noo_hermosa_get_option( 'noo_back_to_top', true ) ) {
			echo '<a href="#" class="go-to-top hidden-print"><i class="fa fa-angle-up"></i></a>';
		}
		return ;
	}
	add_action('wp_footer','noo_hermosa_gototop');
endif;

/**
 * Show social share icon
 *
 * @package 	Noo_Hermosa
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'noo_hermosa_social_share_product' ) ) :
	
	function noo_hermosa_social_share_product() {

		$share_url     = urlencode( get_permalink() );
        $share_title   = urlencode( get_the_title() );
        $share_media   = wp_get_attachment_thumb_url( get_post_thumbnail_id() );
        $popup_attr    = 'resizable=0, toolbar=0, menubar=0, status=0, location=0, scrollbars=0';


        $html = array();


        $html[] = '<div class="noo-social-share">';

        $html[] = '<span><i class="ion-android-share-alt"></i> ' . esc_html__( 'Share:', 'noo-hermosa' ) . '</span>';

        $html[] = '<a href="#share" data-toggle="tooltip" data-placement="bottom" data-trigger="hover" class="noo-share"'
            . ' title="' . esc_html__( 'Share on Facebook', 'noo-hermosa' ) . '"'
            . ' onclick="window.open('
            . "'http://www.facebook.com/sharer.php?u={$share_url}&amp;t={$share_title}','popupFacebook','width=650,height=270,{$popup_attr}');"
            . ' return false;">';
        $html[] = '<i class="fa fa-facebook"></i>';
        $html[] = '</a>';

        $html[] = '<a href="#share" class="noo-share"'
            . ' title="' . esc_html__( 'Share on Twitter', 'noo-hermosa' ) . '"'
            . ' onclick="window.open('
            . "'https://twitter.com/intent/tweet?text={$share_title}&amp;url={$share_url}','popupTwitter','width=500,height=370,{$popup_attr}');"
            . ' return false;">';
        $html[] = '<i class="fa fa-twitter"></i></a>';

        $html[] = '<a href="#share" class="noo-share"'
            . ' title="' . esc_html__( 'Share on Google+', 'noo-hermosa' ) . '"'
            . ' onclick="window.open('
            . "'https://plus.google.com/share?url={$share_url}','popupGooglePlus','width=650,height=226,{$popup_attr}');"
            . ' return false;">';
        $html[] = '<i class="fa fa-google-plus"></i></a>';

        $html[] = '<a href="#share" class="noo-share"'
            . ' title="' . esc_html__( 'Share on Pinterest', 'noo-hermosa' ) . '"'
            . ' onclick="window.open('
            . "'http://pinterest.com/pin/create/button/?url={$share_url}&amp;media={$share_media}&amp;description={$share_title}','popupPinterest','width=750,height=265,{$popup_attr}');"
            . ' return false;">';
        $html[] = '<i class="fa fa-pinterest"></i></a>';


        $html[] = '</div>'; // .noo-social.social-share


        echo implode("\n", $html);

	}

endif;