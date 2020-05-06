<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Output elements builder
 *
 * @var $titles array Elements titles
 * @var $body   string Body inner HTML
 */
$titles = ( isset( $titles ) AND is_array( $titles ) ) ? $titles : array();
$body   = isset( $body ) ? $body : '';

?>
<div class="ns-builder">
    <div class="ns-builder-header">
        <div class="ns-builder-title"<?php echo ns_pass_data_to_js( $titles ) ?>></div>
        <div class="ns-builder-closer">&times;</div>
    </div>
    <div class="ns-builder-body"><?php echo $body ?></div>
    <div class="ns-builder-footer">
        <div class="ns-builder-btn for_close button"><?php _e( 'Close', 'noo-shortcode-builder' ) ?></div>
        <div class="ns-builder-btn for_save button button-primary"><?php _e( 'Save changes',
				'noo-shortcode-builder' ) ?></div>
    </div>
</div>
