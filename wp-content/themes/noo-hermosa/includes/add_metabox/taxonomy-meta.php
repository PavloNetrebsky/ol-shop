<?php

if( !function_exists('noo_hermosa_taxonomy_add_new_meta_field') ) :
	// Add term page
	function noo_hermosa_taxonomy_add_new_meta_field() {
		if( ! noo_hermosa_get_option( 'noo_page_heading', true ) ) {
			return '';
		}
		// this will add the custom meta field to the add new term page
		wp_enqueue_media();
		?>
		<hr/>
		<div class="form-field">
			<label for="term_meta-heading_image"><?php echo esc_html__( 'Heading Background Image', 'noo-hermosa' ); ?></label>
			<input type="hidden" name="term_meta[heading_image]" id="term_meta-heading_image" value="" />
			<input type="button" class="button" name="term_meta-heading_image_button_upload" id="term_meta-heading_image_button_upload" value="<?php echo esc_html__( 'Upload/Add Image', 'noo-hermosa' ); ?>" />
			<input type="button" class="button" name="term_meta-heading_image_button_clear" id="term_meta-heading_image_button_clear" value="<?php echo esc_html__( 'Clear Image', 'noo-hermosa' ); ?>" style="display: none;"/>
			<div class="noo-thumb-wrapper">
				<?php if( function_exists('wc_placeholder_img_src') ) : ?>
					<img src="<?php echo wc_placeholder_img_src(); ?>" />
				<?php endif; ?>
			</div>
			<p class="description"><?php echo esc_html__( 'If you leave it blank, it will use the default background image.', 'noo-hermosa' ); ?></p>
			<script>
				jQuery(document).ready(function($) {
					$('#term_meta-heading_image_button_upload').on('click', function(event) {
						event.preventDefault();
						var noo_upload_btn   = $(this);

						// if media frame exists, reopen
						if(wp_media_frame) {
			                wp_media_frame.open();
			                return;
			            }

						// create new media frame
						// I decided to create new frame every time to control the selected images
						var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
							title: "<?php echo esc_html__( 'Select or Upload your Image', 'noo-hermosa' ); ?>",
							button: {
								text: "<?php echo esc_html__( 'Select', 'noo-hermosa' ); ?>"
							},
							library: { type: 'image' },
							multiple: false
						});

						// when open media frame, add the selected image
						wp_media_frame.on('open',function() {
							var selected_id = noo_upload_btn.siblings('#term_meta-heading_image').val();
							if (!selected_id)
								return;
							var selection = wp_media_frame.state().get('selection');
							var attachment = wp.media.attachment(selected_id);
							attachment.fetch();
							selection.add( attachment ? [ attachment ] : [] );
						});

						// when image selected, run callback
						wp_media_frame.on('select', function(){
							var attachment = wp_media_frame.state().get('selection').first().toJSON();
							noo_upload_btn.siblings('#term_meta-heading_image').val(attachment.id);

							noo_thumb_wraper = noo_upload_btn.siblings('.noo-thumb-wrapper');
							noo_thumb_wraper.html('');
							noo_thumb_wraper.append('<img src="' + attachment.url + '"  />');

							noo_upload_btn.attr('value', '<?php echo esc_html__( 'Change Image', 'noo-hermosa' ); ?>');
							$('#term_meta-heading_image_button_clear').css('display', 'inline-block');
						});

						// open media frame
						wp_media_frame.open();
					});

					$('#term_meta-heading_image_button_clear').on('click', function(event) {
						var noo_clear_btn = $(this);
						noo_clear_btn.hide();
						$('#term_meta-heading_image_button_upload').attr('value', '<?php echo esc_html__( 'Upload/Add Image', 'noo-hermosa' ); ?>');
						noo_clear_btn.siblings('#term_meta-heading_image').val('');
						noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
					});
				});
			</script>
		</div>
		<?php
	}

	add_action( 'category_add_form_fields', 'noo_hermosa_taxonomy_add_new_meta_field', 100, 2 );
	add_action( 'post_tag_add_form_fields', 'noo_hermosa_taxonomy_add_new_meta_field', 100, 2 );
	add_action( 'product_cat_add_form_fields', 'noo_hermosa_taxonomy_add_new_meta_field', 100, 2 );

endif;

if( !function_exists('noo_hermosa_taxonomy_edit_meta_field') ) :
	// Edit term page
	function noo_hermosa_taxonomy_edit_meta_field($term) {
		if( ! noo_hermosa_get_option( 'noo_page_heading', true ) ) {
			return '';
		}
		wp_enqueue_media();
		?>
		<hr/>
		<tr class="form-field" >
			<th scope="row" valign="top"><label for="term_meta-heading_image"><?php echo esc_html__( 'Heading Background Image', 'noo-hermosa' ); ?></label></th>
			<td>
				<?php
					$output		= '';
					$btn_text	= '';
					$image_id	= noo_hermosa_get_term_meta( $term->term_id, 'heading_image', '' );
					if( !empty( $image_id ) ) {
						$output		= wp_get_attachment_thumb_url( $image_id );
						$btn_text	= esc_html__( 'Change Image', 'noo-hermosa' );
					} else {
						if( function_exists('wc_placeholder_img_src') ) {
							$output = wc_placeholder_img_src();
						}
						$btn_text	= esc_html__( 'Upload/Add Image', 'noo-hermosa' );
					}
				?>
				<input type="hidden" name="term_meta[heading_image]" id="term_meta-heading_image" value="<?php echo esc_html($image_id); ?>" />
				<input type="button" class="button" name="term_meta-heading_image_button_upload" id="term_meta-heading_image_button_upload" value="<?php echo esc_html($btn_text); ?>" />
				<input type="button" class="button" name="term_meta-heading_image_button_clear" id="term_meta-heading_image_button_clear" value="<?php echo esc_html__( 'Clear Image', 'noo-hermosa' ); ?>" <?php echo esc_attr( $image_id ) ? '' : 'style="display: none;"' ?> />
				<div class="noo-thumb-wrapper"><?php echo !empty( $output ) ? '<img src="' . $output . '" />' : ''; ?></div>
				<p class="description"><?php echo esc_html__( 'If you leave it blank, it will use the image set on Customizer setting for Index page.', 'noo-hermosa' ); ?></p>
				<script>
					jQuery(document).ready(function($) {
						$('#term_meta-heading_image_button_upload').on('click', function(event) {
							event.preventDefault();
							var noo_upload_btn   = $(this);

							// if media frame exists, reopen
							if(wp_media_frame) {
				                wp_media_frame.open();
				                return;
				            }

							// create new media frame
							// I decided to create new frame every time to control the selected images
							var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
								title: "<?php echo esc_html__( 'Select or Upload your Image', 'noo-hermosa' ); ?>",
								button: {
									text: "<?php echo esc_html__( 'Select', 'noo-hermosa' ); ?>"
								},
								library: { type: 'image' },
								multiple: false
							});

							// when open media frame, add the selected image
							wp_media_frame.on('open',function() {
								var selected_id = noo_upload_btn.siblings('#term_meta-heading_image').val();
								if (!selected_id)
									return;
								var selection = wp_media_frame.state().get('selection');
								var attachment = wp.media.attachment(selected_id);
								attachment.fetch();
								selection.add( attachment ? [ attachment ] : [] );
							});

							// when image selected, run callback
							wp_media_frame.on('select', function(){
								var attachment = wp_media_frame.state().get('selection').first().toJSON();
								noo_upload_btn.siblings('#term_meta-heading_image').val(attachment.id);

								noo_thumb_wraper = noo_upload_btn.siblings('.noo-thumb-wrapper');
								noo_thumb_wraper.html('');
								noo_thumb_wraper.append('<img src="' + attachment.url + '"  />');

								noo_upload_btn.attr('value', '<?php echo esc_html__( 'Change Image', 'noo-hermosa' ); ?>');
								$('#term_meta-heading_image_button_clear').css('display', 'inline-block');
							});

							// open media frame
							wp_media_frame.open();
						});

						$('#term_meta-heading_image_button_clear').on('click', function(event) {
							var noo_clear_btn = $(this);
							noo_clear_btn.hide();
							$('#term_meta-heading_image_button_upload').attr('value', '<?php echo esc_html__( 'Select Image', 'noo-hermosa' ); ?>');
							noo_clear_btn.siblings('#term_meta-heading_image').val('');
							noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
						});
					});
				</script>
			</td>
		</tr>
		<?php
	}

	add_action( 'category_edit_form_fields', 'noo_hermosa_taxonomy_edit_meta_field', 100, 2 );
	add_action( 'post_tag_edit_form_fields', 'noo_hermosa_taxonomy_edit_meta_field', 100, 2 );
	add_action( 'product_cat_edit_form_fields', 'noo_hermosa_taxonomy_edit_meta_field', 100, 2 );

endif;

if( !function_exists('noo_hermosa_save_taxonomy_custom_meta') ) :
	// Save extra taxonomy fields callback function.
	function noo_hermosa_save_taxonomy_custom_meta( $term_id ) {
		if ( isset( $_POST['term_meta'] ) ) {
			$t_id = $term_id;
			$term_meta = get_option( "taxonomy_{$t_id}" );
			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}

			// Save the option array.
			update_option( "taxonomy_{$t_id}", $term_meta );
		}
	}

	add_action( 'edited_category', 'noo_hermosa_save_taxonomy_custom_meta', 10, 2 );
	add_action( 'create_category', 'noo_hermosa_save_taxonomy_custom_meta', 10, 2 );
	add_action( 'edited_post_tag', 'noo_hermosa_save_taxonomy_custom_meta', 10, 2 );
	add_action( 'create_post_tag', 'noo_hermosa_save_taxonomy_custom_meta', 10, 2 );
	add_action( 'edited_product_cat', 'noo_hermosa_save_taxonomy_custom_meta', 10, 2 );
	add_action( 'create_product_cat', 'noo_hermosa_save_taxonomy_custom_meta', 10, 2 );

endif;