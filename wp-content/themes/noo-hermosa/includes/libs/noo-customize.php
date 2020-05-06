<?php
if (!function_exists('noo_hermosa_current_url')):
	function noo_hermosa_current_url($encoded = false) {
		global $wp;
		$current_url = esc_url( add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		if( $encoded ) {
			return urlencode($current_url);
		}
		return $current_url;
	}
endif;

if (!function_exists('noo_hermosa_upload_dir_name')):
	function noo_hermosa_upload_dir_name() {
		return apply_filters( 'noo_hermosa_upload_dir_name', NOO_THEME_NAME );
	}
endif;

if (!function_exists('noo_hermosa_upload_dir')):
	function noo_hermosa_upload_dir() {
		$upload_dir = wp_upload_dir();

		return $upload_dir['basedir'] . '/' . noo_hermosa_upload_dir_name();
	}
endif;

if (!function_exists('noo_hermosa_upload_url')):
	function noo_hermosa_upload_url() {
		$upload_dir = wp_upload_dir();

		return $upload_dir['baseurl'] . '/' . noo_hermosa_upload_dir_name();
	}
endif;

if (!function_exists('noo_hermosa_create_upload_dir')):
	function noo_hermosa_create_upload_dir( $wp_filesystem = null ) {
		if( empty( $wp_filesystem ) ) {
			return false;
		}

		$upload_dir = wp_upload_dir();
		global $wp_filesystem;

		$noo_upload_dir = $wp_filesystem->find_folder( $upload_dir['basedir'] ) . noo_hermosa_upload_dir_name();
		if ( ! $wp_filesystem->is_dir( $noo_upload_dir ) ) {
			if ( wp_mkdir_p( $noo_upload_dir, 0777 ) ) {
				return $noo_upload_dir;
			}

			return false;
		}

		return $noo_upload_dir;
	}
endif;

if (!function_exists('noo_hermosa_handle_upload_file')):
	function noo_hermosa_handle_upload_file($upload_data) {
		$return = false;
		$uploaded_file = wp_handle_upload($upload_data, array('test_form' => false));

		if (isset($uploaded_file['file'])) {
			$file_loc = $uploaded_file['file'];
			$file_name = basename($upload_data['name']);
			$file_type = wp_check_filetype($file_name);

			$attachment = array(
				'post_mime_type' => $file_type['type'],
				'post_title' => preg_replace('/\.[^.]+$/', '', basename($file_name)),
				'post_content' => '',
				'post_status' => 'inherit'
				);

			$attach_id = wp_insert_attachment($attachment, $file_loc);
			$attach_data = wp_generate_attachment_metadata($attach_id, $file_loc);
			wp_update_attachment_metadata($attach_id, $attach_data);

			$return = array('data' => $attach_data, 'id' => $attach_id);

			return $return;
		}

		return $return;
	}
endif;