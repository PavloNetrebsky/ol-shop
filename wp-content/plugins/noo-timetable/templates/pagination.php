<?php
/**
 * Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/pagination.php.
 *
 * @author      NooTheme
 * @package     NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Previous/next page navigation.
the_posts_pagination( array(
	'prev_text'          => __( 'Previous page', 'noo-timetable' ),
	'next_text'          => __( 'Next page', 'noo-timetable' ),
	'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'noo-timetable' ) . ' </span>',
) );