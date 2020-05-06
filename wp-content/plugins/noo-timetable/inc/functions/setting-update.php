<?php
/**
 * Setting for Key Update
 *
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !function_exists( 'noo_timetable_setting_update' ) ) {
	function noo_timetable_setting_update() {

		$options = array(
			
			array(
				'title'       => esc_html__( 'CodeCanyon Purchase Code:', 'noo-timetable' ),
				'desc'        => '',
				'id'          => 'license_key',
				'class'       => '',
				'default'     => '',
				'placeholder' => '',
				'type'        => 'license_key_update',
			),

		);

		return apply_filters( 'noo_timetable_setting_update', $options);
	}
}

if( !function_exists( 'noo_timetable_admin_setting_field_license_key_update' ) ) {

	function noo_timetable_admin_setting_field_license_key_update( $value ) {

		$option_value = NOO_Settings()->get_option( $value['id'], $value['default'] );
		?>

		<table class="widefat" cellspacing="0" id="client">
            <thead>
            <tr>
                <th colspan="3" data-export-label="<?php esc_html_e( 'Automatic Update Plugin', 'noo-timetable' ); ?>">
                    <strong>
                        <?php esc_html_e( 'Automatic Update Plugin', 'noo-timetable' ); ?>
                    </strong>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td data-export-label="<?php esc_html_e( 'Purchase Code', 'noo-timetable' ); ?>">
                    <?php esc_html_e( 'CodeCanyon Purchase Code:', 'noo-timetable' ); ?><br/>
                    <small><?php esc_html_e( '(Optional)', 'noo-timetable' ); ?></small>
                </td>
                <td class="help">
                    <a href="#" title="<?php _e( 'This purchase code makes sure that our server recognizes your website thus allows you to download and install the new updates.', 'noo-timetable' ); ?>" class="help_tip"><span class="dashicons dashicons-editor-help"></span></a>
                </td>
                <td>
                    <input type='text' name="timetable_settings[<?php echo esc_attr( $value['id'] ); ?>]" value="<?php echo esc_attr( $option_value ); ?>" class='regular-text'>
                    <input type='hidden' name="timetable_settings[email_license]" value='<?php echo str_replace( 'http://', '', home_url( )); ?>' class='regular-text'>
                    <span><?php echo sprintf( wp_kses( __( '<a target="_blank" href="%s">How to get License key?</a>', 'noo-timetable' ), noo_timetable_allowed_html() ), 'http://support.nootheme.com/wp-content/uploads/2015/07/HowToGetPurchaseCode.png' ) ?></span>
                </td>
            </tr>
            </tbody>
        </table>

		<?php
	}

    add_action( 'noo_timetable_admin_field_license_key_update', 'noo_timetable_admin_setting_field_license_key_update' );
}
