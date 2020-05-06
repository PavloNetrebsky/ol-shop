<?php
/**
 * Setup for importdemo
 *
 * One Click Demo 
 *
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/Importer
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( !class_exists('Noo__Timetable_Settup_Install') ):

    class Noo__Timetable_Settup_Install {

    	public function __construct() {
	        $this->_init();
	    }

    	public function _init() {

			if ( current_user_can( 'manage_options' ) ) {

				if ( isset($_GET['page']) && $_GET['page'] === 'noo-timetable-import-demo' ) {
					add_action( 'admin_enqueue_scripts', array( $this, 'load_enqueue_script_setup' ) );
				}
	            require dirname( __FILE__ ) . '/noo-import.php';

			}
		}

		public function load_enqueue_script_setup() {

			wp_register_script( 'setup-install-demo', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/js/noo.setup.install.demo.js' );
			wp_enqueue_script( 'setup-install-demo');

			wp_register_style( 'setup-style', Noo__Timetable__Main::plugin_url() . '/inc/framework/assets/css/noo-setup.css' );
			wp_enqueue_style( 'setup-style' );


			wp_localize_script( 'setup-install', 'nooSetup', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
			wp_localize_script( 'setup-install-demo', 'nooSetupDemo', 
				array( 
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'notice'   => esc_html__( 'Do you want to continue this action?', 'noo-timetable' ),
					'warning'  => esc_html__( 'Please waiting, not exit page.', 'noo-timetable' ),
					'ajax_nonce' => wp_create_nonce( 'install-demo' ),
					'img_ajax_load' => Noo__Timetable__Main::plugin_url() . '/images/ajax-loader.gif'
				) 
			);

		}

		public static function output() {

			$list_demo = array(
				array(
	                'name' => esc_html__( 'Timetable', 'noo-timetable' ),
	                'img'  => Noo__Timetable__Main::plugin_url() . '/inc/importer/data-demo/timetable/screenshot.jpg',
	                'file' => 'timetable'
	            )
			);

			?>
				<table class="widefat" cellspacing="0" style="width: 99%; display: none;">
					<thead>
						<tr>
							<th colspan="1" data-export-label="<?php echo esc_html__( 'Settings', 'noo-timetable' ); ?>">
								<label class="hide_main">
									<?php echo esc_html__( 'Settings', 'noo-timetable' ); ?>
								</label>
							</th>
						</tr>
					</thead>
					<tbody id="noo_main_select">
						<tr>
							<td>
								<input type='checkbox' data-id='import_post' id='import_post' value='1' checked /> <?php echo esc_html__( 'Import Post', 'noo-timetable' ); ?>
							</td>
						</tr>
						<tr>
							<td>
								<input type='checkbox' data-id='import_nav' id='import_nav' value='1' checked /> <?php echo esc_html__( 'Import Nav Menu', 'noo-timetable' ); ?>
							</td>
						</tr>
						<tr>
							<td>
								<input type='checkbox' data-id='import_comment' id='import_comment' value='1' checked /> <?php echo esc_html__( 'Import Comment', 'noo-timetable' ); ?>
							</td>
						</tr>
					</tbody>
				</table>
				<h2><?php esc_html_e( 'One Click to Install Demo', 'noo-timetable' ); ?></h2>
				<div id="noo_tools" class="noo_timetable_tools">

					<!-- [ MAIN ] -->
					<div id="message" class="process_import updated inline"><p><strong></strong></p></div>
					<div class="theme-browser rendered" style="margin-top: 20px;">
						<div class="themes">
							<?php foreach ($list_demo as $id => $demo) : ?>
								<div class="theme" tabindex="0">
									<div class="theme-screenshot">
										<img src="<?php echo esc_attr( $demo['img'] ); ?>" alt="" />
									</div>
									<span class="more-details" id="install_<?php echo esc_attr( $demo['file'] ); ?>"><?php esc_html_e( 'Install ' .$demo['name'], 'noo-timetable' ); ?></span>
									<h3 class="theme-name" id="noo-<?php echo esc_attr( $demo['file'] ); ?>-name"><?php echo esc_html( $demo['name'] ); ?></h3>
									<div class="noo-load-ajax"></div>
									<div class="theme-actions">
										<button class="install-demo button button-secondary button-primary activate"
										data-name="<?php echo esc_attr( $demo['file'] ); ?>"
										data-import-post="true"
										data-import-nav="true"
										data-import-comment="true"
										><?php esc_html_e( 'Install Demo', 'noo-timetable' ); ?></button>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<br class="clear">
					</div>
				
				</div><!-- /#noo_tools -->

			<?php

        }

    }
    new Noo__Timetable_Settup_Install();

endif;