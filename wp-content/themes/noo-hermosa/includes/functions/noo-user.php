<?php
/**
 * Add/Remove user fields for NOO Framework.
 *
 * @package    NOO Framework
 * @subpackage NOO Function
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@vietbrain.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
// =============================================================================

if ( ! function_exists( 'noo_hermosa_author_profile_fields' ) ) :
	function noo_hermosa_author_profile_fields ( $contactmethods ) {
		
		$contactmethods['google_profile'] = esc_html__( 'Google+ Profile URL', 'noo-hermosa');
		$contactmethods['twitter_profile'] = esc_html__( 'Twitter Profile URL', 'noo-hermosa');
		$contactmethods['facebook_profile'] = esc_html__( 'Facebook Profile URL', 'noo-hermosa');
		$contactmethods['linkedin_profile'] = esc_html__( 'LinkedIn Profile URL', 'noo-hermosa');
		
		return $contactmethods;
	}
	add_filter( 'user_contactmethods', 'noo_hermosa_author_profile_fields', 10, 1);
endif;