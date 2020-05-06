<?php
/**
 * NOO Meta-Boxes Package
 *
 * NOO Meta-Boxes Register Function
 * This file register add_meta_boxes and save_post actions.
 *
 * @package    NOO Framework
 * @subpackage NOO Meta-Boxes
 * @version    1.0.0
 * @author     Kan Nguyen <khanhnq@nootheme.com>
 * @copyright  Copyright (c) 2014, NooTheme
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link       http://nootheme.com
 */
if( !function_exists( 'noo_create_meta_box' ) ) {
	// Create meta box base on inputted value
	function noo_create_meta_box( $post, $meta_box ) {

		if ( ! is_array( $meta_box ) )
			return false;

		$prefix = '_noo_wp_post';

		if ( isset( $meta_box['description'] ) && $meta_box['description'] != '' )
			echo '<p>' . $meta_box['description'] . '</p>';

		wp_nonce_field( basename( __FILE__ ), 'noo_meta_box_nonce' );

		foreach ( $meta_box['fields'] as $field ) {

			if ( !isset( $field['type'] ) || empty( $field['type'] ) )
				continue;

			// If it's divider, add a hr
			if( $field['type'] == 'divider') {
				echo '<hr/>';
				continue;
			}

			if ( !isset( $field['id'] ) || empty( $field['id'] ) )
				continue;

			$id  = $field['id'];
			$meta = noo_hermosa_get_post_meta( $post->ID, $id );
			$label = isset( $field['label'] ) && !empty( $field['label'] ) ? '<strong>' . $field['label'] . '</strong>' : '';
			$std = isset( $field['std'] ) ? $field['std'] : '';
			$class = "noo-control ";
			$class = isset( $field['class'] ) && !empty( $field['class'] ) ? ' class="' . $class . $field['class'] . '"' : ' class="' . $class . '"';
			$value = '';

			echo '<div id="portfolio-select" class="noo-form-group ' . $id . '">';

			if( $field['type'] != 'checkbox' || $meta_box['context'] != 'side' ) {
				if(!empty($label)){
					echo '<label for="' . $field['id'] . '">'.$label;
					if ( isset( $field['desc'] ) && !empty( $field['desc'] ) )
						echo '<div class="field-desc">' . $field['desc'] . '</div>';
					echo '</label>';
				}
			} else {
				$field['inline_label'] = true;
			}

			echo '<div ' . $class . '>';
			
			if( isset($field['callback']) && !empty($field['callback']) ) {
				call_user_func($field['callback'], $post, $id, $field['type'], $meta, $std, $field);
			} else {
				noo_render_metabox_fields( $post, $id, $field['type'], $meta, $std, $field );
			}

			echo '</div>'; // div.noo-control
			echo '</div>'; // div.noo-form-group

		} // foreach - $meta_box['fields']
	} // function - noo_create_meta_box
}
if( !function_exists( 'noo_render_metabox_fields' ) ) {
	function noo_render_metabox_fields ( $post, $id, $type, $meta, $std, $field = null ) {
		switch( $type ) {
			case 'text':
				$value = $meta ? ' value="' . $meta . '"' : '';
				$value = empty( $value ) && ( $std != null && $std != '' ) ? ' placeholder="' . $std . '"' : $value;
				echo '<input id='.$id.' type="text" name="noo_meta_boxes[' . $id . ']" ' . $value . ' />';
				break;

			case 'textarea':
				echo '<textarea id='.$id.' name="noo_meta_boxes[' . $id . ']" placeholder="' . $std . '">' . ( $meta ? $meta : $std ) . '</textarea>';
				break;

			case 'gallery':
				$meta = $meta ? $meta : $std;
				$output = '';
				if ( $meta != '' ) {
					$image_ids = explode( ',', $meta );
					foreach ( $image_ids as $image_id ) {
						$output .= wp_get_attachment_image( $image_id, 'thumbnail');
					}
				}

				$btn_text = !empty( $meta ) ? esc_html__( 'Edit Gallery', 'noo-hermosa-core' ) : esc_html__( 'Add Images', 'noo-hermosa-core' );
				echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' . $meta . '" />';
				echo '<input type="button" class="button button-primary" name="' . $id . '_button_upload" id="' . $id . '_upload" value="' . $btn_text . '" />';
				echo '<input type="button" class="button" name="' . $id . '_button_clear" id="' . $id . '_clear" value="' . esc_html__( 'Clear Gallery', 'noo-hermosa-core' ) . '" />';
				echo '<div class="noo-thumb-wrapper">' . $output . '</div>';
	?>
				<script>
					jQuery(document).ready(function($) {

						// gallery state: add new or edit.
						var gallery_state = '<?php echo empty ( $meta ) ? 'gallery-library' : 'gallery-edit'; ?>';

						// Hide the Clear Gallery button if there's no image.
						<?php if ( empty ( $meta ) ) : ?> $('#<?php echo esc_attr($id); ?>_clear').hide(); <?php endif; ?>

						$('#<?php echo esc_attr($id); ?>_upload').on('click', function(event) {
							event.preventDefault();

							var noo_upload_btn   = $(this);

							// if media frame exists, reopen
							if(wp_media_frame) {
								wp_media_frame.setState(gallery_state);
								wp_media_frame.open();
								return;
							}

							// create new media frame
							// I decided to create new frame every time to control the Library state as well as selected images
							var wp_media_frame = wp.media.frames.wp_media_frame = wp.media({
								title: 'NOO Gallery', // it has no effect but I really want to change the title
								frame: "post",
								toolbar: 'main-gallery',
								state: gallery_state,
								library: { type: 'image' },
								multiple: true
							});

							// when open media frame, add the selected image to Gallery
							wp_media_frame.on('open',function() {
								var selected_ids = noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val();
								if (!selected_ids)
									return;
								selected_ids = selected_ids.split(',');
								var library = wp_media_frame.state().get('library');
								selected_ids.forEach(function(id) {
									attachment = wp.media.attachment(id);
									attachment.fetch();
									library.add( attachment ? [ attachment ] : [] );
								});
							});

							// when click Insert Gallery, run callback
							wp_media_frame.on('update', function(){

								var library = wp_media_frame.state().get('library');
								var images	= [];
								var noo_thumb_wraper = noo_upload_btn.siblings('.noo-thumb-wrapper');
								noo_thumb_wraper.html('');

								library.map( function( attachment ) {
									attachment = attachment.toJSON();
									images.push(attachment.id);
									noo_thumb_wraper.append('<img src="' + attachment.url + '" alt="" />');
								});

								gallery_state = 'gallery-edit';

								noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val(images.join(','));

								noo_upload_btn.attr('value', '<?php echo esc_html__( 'Edit Gallery', 'noo-hermosa-core' ); ?>');
								$('#<?php echo esc_attr($id); ?>_clear').css('display', 'inline-block');
							});

							// open media frame
							wp_media_frame.open();
						});

						// Clear button, clear all the images and reset the gallery
						$('#<?php echo esc_attr($id); ?>_clear').on('click', function(event) {
							gallery_state = 'gallery-library';
							var noo_clear_btn = $(this);
							noo_clear_btn.hide();
							$('#<?php echo esc_attr($id); ?>_upload').attr('value', '<?php echo esc_html__( 'Add Images', 'noo-hermosa-core' ); ?>');
							noo_clear_btn.siblings('#<?php echo esc_attr($id); ?>').val('');
							noo_clear_btn.siblings('#<?php echo esc_attr($id); ?>_ids').val('');
							noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
						});
					});
				</script>

				<?php
				break;
			case 'application_upload':
			case 'media':
				if(function_exists( 'wp_enqueue_media' )){
					wp_enqueue_media();
				}else{
					wp_enqueue_style('thickbox');
					wp_enqueue_script('media-upload');
					wp_enqueue_script('thickbox');
				}
				$val = $meta ? $meta : $std;
				$btn_text = !empty( $val ) ? esc_html__( 'Change File', 'noo-hermosa-core' ) : esc_html__( 'Select File', 'noo-hermosa-core' );
				echo '<input type="text" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' . ( $meta ? $meta : $std ) . '" style="margin-bottom:10px" />';
				echo '<input type="button" class="button button-primary" name="' . $id . '_button_upload" id="' . $id . '_upload" value="' . $btn_text . '" />';
				echo '<input type="button" class="button" name="' . $id . '_button_clear" id="' . $id . '_clear" value="' . esc_html__( 'Clear File', 'noo-hermosa-core' ) . '" />';
				?>
				<script>
					jQuery(document).ready(function($) {

						<?php if ( empty ( $meta ) ) : ?> $('#<?php echo esc_attr($id); ?>_clear').css('display', 'none'); <?php endif; ?>

						$('#<?php echo esc_attr($id); ?>_upload').on('click', function(event) {
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
								title: "<?php echo esc_html__( 'Select or Upload your File', 'noo-hermosa-core' ); ?>",
								button: {
									text: "<?php echo esc_html__( 'Select', 'noo-hermosa-core' ); ?>"
								},
								<?php if($type == 'media'):?>
								library: { type: 'video,audio' },
								<?php endif;?>
								<?php if($type == 'application_upload'):?>
								library: { type: 'application' },
								<?php endif;?>
								multiple: false
							});

							// when image selected, run callback
							wp_media_frame.on('select', function(){
								var attachment = wp_media_frame.state().get('selection').first().toJSON();
								noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val(attachment.url);
								noo_upload_btn.attr('value', '<?php echo esc_html__( 'Change File', 'noo-hermosa-core' ); ?>');
								$('#<?php echo esc_attr($id); ?>_clear').css('display', 'inline-block');
							});

							// open media frame
							wp_media_frame.open();
						});

						$('#<?php echo esc_attr($id); ?>_clear').on('click', function(event) {
							var noo_clear_btn = $(this);
							noo_clear_btn.hide();
							$('#<?php echo esc_attr($id); ?>_upload').attr('value', '<?php echo esc_html__( 'Select File', 'noo-hermosa-core' ); ?>');
							noo_clear_btn.siblings('#<?php echo esc_attr($id); ?>').val('');
						});
					});
				</script>
				<?php
			break;
			case 'image':
				if(function_exists( 'wp_enqueue_media' )){
					wp_enqueue_media();
				}else{
					wp_enqueue_style('thickbox');
					wp_enqueue_script('media-upload');
					wp_enqueue_script('thickbox');
				}
				$image_id = $meta ? $meta : $std;
				$image = wp_get_attachment_image( $image_id, 'thumbnail');
				$output = !empty( $image_id ) ? $image : '';
				$btn_text = !empty( $image_id ) ? esc_html__( 'Change Image', 'noo-hermosa-core' ) : esc_html__( 'Select Image', 'noo-hermosa-core' );
				echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' . ( $meta ? $meta : $std ) . '" />';
				echo '<input type="button" class="button button-primary" name="' . $id . '_button_upload" id="' . $id . '_upload" value="' . $btn_text . '" />';
				echo '<input type="button" class="button" name="' . $id . '_button_clear" id="' . $id . '_clear" value="' . esc_html__( 'Clear Image', 'noo-hermosa-core' ) . '" />';
				echo '<div class="noo-thumb-wrapper">' . $output . '</div>';
	?>
				<script>
					jQuery(document).ready(function($) {

						<?php if ( empty ( $meta ) ) : ?> $('#<?php echo esc_attr($id); ?>_clear').css('display', 'none'); <?php endif; ?>

						$('#<?php echo esc_attr($id); ?>_upload').on('click', function(event) {
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
								title: "<?php echo esc_html__( 'Select or Upload your Image', 'noo-hermosa-core' ); ?>",
								button: {
									text: "<?php echo esc_html__( 'Select', 'noo-hermosa-core' ); ?>"
								},
								library: { type: 'image' },
								multiple: false
							});

							// when open media frame, add the selected image
							wp_media_frame.on('open',function() {
								var selected_id = noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val();
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
								noo_upload_btn.siblings('#<?php echo esc_attr($id); ?>').val(attachment.id);

								noo_thumb_wraper = noo_upload_btn.siblings('.noo-thumb-wrapper');
								noo_thumb_wraper.html('');
								noo_thumb_wraper.append('<img src="' + attachment.url + '" alt="" />');

								noo_upload_btn.attr('value', '<?php echo esc_html__( 'Change Image', 'noo-hermosa-core' ); ?>');
								$('#<?php echo esc_attr($id); ?>_clear').css('display', 'inline-block');
							});

							// open media frame
							wp_media_frame.open();
						});

						$('#<?php echo esc_attr($id); ?>_clear').on('click', function(event) {
							var noo_clear_btn = $(this);
							noo_clear_btn.hide();
							$('#<?php echo esc_attr($id); ?>_upload').attr('value', '<?php echo esc_html__( 'Select Image', 'noo-hermosa-core' ); ?>');
							noo_clear_btn.siblings('#<?php echo esc_attr($id); ?>').val('');
							noo_clear_btn.siblings('.noo-thumb-wrapper').html('');
						});
					});
				</script>

				<?php
				break;
			case 'datepicker':
			case 'datetimepicker':
				wp_enqueue_script( 'datetimepicker' );
				wp_enqueue_style( 'datetimepicker' );
				$date_format = get_option('date_format');
				if( $type == 'datetimepicker' ) {
					$date_format = $date_format . ' ' . get_option('time_format');
				}

				$date_text = !empty( $meta ) ? date( $date_format, $meta ) : '';

				echo '<div>';
				echo '<input type="text" readonly class="input_text" name="noo_meta_boxes[' . $id . ']" id="' . $id . '" value="' .
					 esc_attr( $date_text ) . '" /> ';
				echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="' .
					 esc_attr( $meta ) . '" /> ';
				echo '</div>';
				?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#<?php echo $id; ?>').datetimepicker({
							format:"<?php echo esc_html( $date_format ); ?>",
							step:15,
							<?php if( $type == 'datepicker' ) : ?>
							timepicker:false,
							<?php endif; ?>
							onChangeDateTime:function(dp,$input){
								$input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset());
							}
						});
					});
				</script>
				<?php
				break;

			case 'select':
				$meta = $meta ? $meta : $std;
				echo'<select id='.$id.' name="noo_meta_boxes[' . $id . ']" >';
				foreach ( $field['options'] as $option ) {
					$opt_value  = @$option['value'];
					$opt_label  = @$option['label'];
					echo '<option';
					echo ' value="'.$opt_value.'"';
					if ( $meta == $opt_value ) echo ' selected="selected"';
					echo '>' . $opt_label . '</option>';
				}
				echo '</select>';
				break;

			case 'radio':
				$meta = $meta ? $meta : $std;
				foreach ( $field['options'] as $index => $option ) {
					$opt_value  = $option['value'];
					$opt_label  = $option['label'];
					$opt_checked = '';

					if ( $meta == $opt_value ) $opt_checked = ' checked="checked"';

					$opt_id   = isset( $option['id'] ) ? ' '.$option['id'] : $id . '_' . $index;
					$opt_value_for = ' for="' . $opt_id . '"';
					$opt_class  = isset( $option['class'] ) ? ' class="'.$option['class'].'"' : '';
					echo '<input id="' . $opt_id . '" type="radio" name="noo_meta_boxes[' . $id . ']" value="' . $opt_value . '" class="radio"' . $opt_checked .'/>';
					echo '<label' . $opt_value_for . $opt_class . '>' . $opt_label . '</label>';
					echo '<br/>';
				}

				if ( !empty( $field['child-boxes'] ) && is_array( $field['child-boxes'] ) ) :
					$child_boxes = $field['child-boxes'];
	?>
	        <script>
	          jQuery(document).ready(function($) {
	            <?php
				foreach ( $child_boxes as $option_value => $boxes ) :
					if ( empty( $boxes ) ) continue;
					$boxes = explode( ',', $boxes );
				foreach ( $boxes as $child_box ) :
					if ( trim( $child_box ) == "" ) continue;
	?>
	                $('#<?php echo trim( $child_box ); ?>').addClass('child_<?php echo esc_attr($id); ?> val_<?php echo esc_attr($option_value); ?>');
	                $('label[for="<?php echo trim( $child_box ); ?>-hide"]').addClass('child_<?php echo esc_attr($id); ?> val_<?php echo esc_attr($option_value); ?>');
	                <?php
				endforeach;
				endforeach;
	?>

				$('.child_<?php echo esc_attr($id); ?>').hide();
				var parentField    = $('.<?php echo esc_attr($id); ?>');
				var checkedElement = parentField.find('input:checked');
				$('.child_<?php echo esc_attr($id); ?>.val_' + checkedElement.val()).show();

				parentField.find('input').click( function() {
					$this = $(this);
					$('.child_<?php echo esc_attr($id); ?>').hide();
					$('.child_<?php echo esc_attr($id); ?>.val_' + $this.val()).show();
	            });
	          });
	        </script>
	        <?php endif;

				if ( !empty( $field['child-fields'] ) && is_array( $field['child-fields'] ) ) :
					$child_fields = $field['child-fields'];
	?>
	        <script>
	          jQuery(document).ready(function($) {
	            <?php
				foreach ( $child_fields as $option_value => $fields ) :
					if ( empty( $fields ) ) continue;
					$fields = explode( ',', $fields );
				foreach ( $fields as $child_field ) :
					if ( trim( $child_field ) == "" ) continue;
	?>
	                $('.<?php echo trim( $child_field ); ?>').addClass('child_<?php echo esc_attr($id); ?> val_<?php echo esc_attr($option_value); ?>');
	                <?php
				endforeach;
				endforeach;
	?>

				$('.child_<?php echo esc_attr($id); ?>').hide();
				var parentField    = $('.<?php echo esc_attr($id); ?>');
				var checkedElement = parentField.find('input:checked');
				$('.child_<?php echo esc_attr($id); ?>.val_' + checkedElement.val()).show();

				parentField.find('input').click( function() {
					$this = $(this);
					$('.child_<?php echo esc_attr($id); ?>').hide();
					$('.child_<?php echo esc_attr($id); ?>.val_' + $this.val()).show();
				});
	        });
	        </script>
	        <?php endif;
				break;

			case 'checkbox':
				$opt_value = '';
				
				if ( $meta === null || $meta === '' ) {
					if ( $std && $std !== 'off' )
						$opt_value = ' checked="checked"';				
				} else {
					if ( $meta && $meta !== 'off' )
						$opt_value = ' checked="checked"';
				}

				echo '<input type="hidden" name="noo_meta_boxes[' . $id . ']" value="0" />';
				if( isset($field['inline_label']) && $field['inline_label'] ) {
					echo '<label>';
					echo '<input type="checkbox" id="' . $id . '" name="noo_meta_boxes[' . $id . ']" value="1"' . $opt_value . ' /> ';
					echo ( isset( $field['label'] ) && !empty( $field['label'] ) ? '<strong>' . $field['label'] . '</strong>' : '' );
					echo '</label>';
				} else {
					echo '<input type="checkbox" id="' . $id . '" name="noo_meta_boxes[' . $id . ']" value="1"' . $opt_value . ' /> ';
				}

				if ( !empty( $field['child-fields'] ) && is_array( $field['child-fields'] ) ) :
					$child_fields = $field['child-fields'];
	?>
		        <script>
		          jQuery(document).ready(function($) {
		            <?php
				if ( isset( $child_fields['on'] ) ) :
					$fields = explode( ',', $child_fields['on'] );
				foreach ( $fields as $child_field ) :
					if ( trim( $child_field ) == "" ) continue;
	?>
		                $('.<?php echo trim( $child_field ); ?>').addClass('child_<?php echo esc_attr($id); ?> val_on');
		                <?php
				endforeach;
				endif;

				if ( isset( $child_fields['off'] ) ) :
					$fields = explode( ',', $child_fields['off'] );
				foreach ( $fields as $child_field ) :
					if ( trim( $child_field ) == "" ) continue;
	?>
		                $('.<?php echo trim( $child_field ); ?>').addClass('child_<?php echo esc_attr($id); ?> val_off');
		                <?php
				endforeach;
				endif;
	?>
					$('.child_<?php echo esc_attr($id); ?>').hide();
					var checkboxEl    = $('.<?php echo esc_attr($id); ?>').find('input:checkbox');
					if(checkboxEl.is( ':checked' )) {
						$('.child_<?php echo esc_attr($id); ?>.val_on').show();
					} else {
						$('.child_<?php echo esc_attr($id); ?>.val_off').show();
					}

					checkboxEl.click( function() {
						$this = $(this);
						$('.child_<?php echo esc_attr($id); ?>').hide();
						if($this.is( ':checked' )) {
							$('.child_<?php echo esc_attr($id); ?>.val_on').show();
						} else {
							$('.child_<?php echo esc_attr($id); ?>.val_off').show();
						}
					});
				});
		        </script>
	        	<?php endif;
				break;

			case 'label':
				$value = empty( $meta ) && ( $std != null && $std != '' ) ? $std : $meta;
				echo '<label id='.$id.' >'. $value . '</label>';
				break;

			case 'page_layout':
				$post_layout = noo_hermosa_get_option('noo_blog_post_layout', 'same_as_blog');
				$sidebar = '';
				if ($post_layout == 'same_as_blog') {
					$post_layout = noo_hermosa_get_option( 'noo_blog_layout', 'sidebar' );
					$sidebar = noo_hermosa_get_option('noo_blog_sidebar', 'sidebar-main');
				} else {
					$sidebar = noo_hermosa_get_option('noo_blog_post_sidebar', 'sidebar-main');
				}

				$post_layout_text = '';
				switch( $post_layout ) {
					case 'fullwidth':
						$post_layout_text = esc_html__( 'Full-Width Page', 'noo-hermosa-core' );
						break;
					case 'sidebar':
						$post_layout_text = esc_html__( 'Page With Right Sidebar', 'noo-hermosa-core' );
						break;
					case 'left_sidebar':
						$post_layout_text = esc_html__( 'Page With Left Sidebar', 'noo-hermosa-core' );
						break;
				}
				
				echo '<p>' . sprintf( noo_hermosa_kses( __( 'Global setting for the Layout of Single Post page is: <strong>%s</strong>', 'noo-hermosa-core') ), $post_layout_text ) . '</p>';
				if ( $post_layout != 'fullwidth' ) {
					$sidebar_text = noo_hermosa_get_sidebar_name( $sidebar );
					echo '<p>' . sprintf( noo_hermosa_kses( __( 'And the Sidebar is: <strong>%s</strong>', 'noo-hermosa-core') ), $sidebar_text ) . '</p>';
				}

				break;

			case 'sidebars':
				$meta = !empty($meta) ? $meta : $std;
				$widget_list = smk_get_all_sidebars();
				echo'<select name="noo_meta_boxes[' . $id . ']" >';
				foreach ( $widget_list as $widget_id => $name ) {
					echo'<option value="' . $widget_id . '"';
					if ( $meta == $widget_id ) echo ' selected="selected"';
					echo '>' . $name . '</option>';
				}
				echo '</select>';

				break;

			case 'menus':
				$meta = !empty($meta) ? $meta : $std;
				$menu_list = get_terms('nav_menu');

				echo'<select name="noo_meta_boxes[' . $id . ']" >';
				echo'	<option value="" '. selected( $meta, '', true ) . '>' . esc_html__( 'Don\'t Need Menu', 'noo-hermosa-core') . '</option>';
				foreach ( $menu_list as $menu ) {
					echo'<option value="' . $menu->term_id . '"';
					selected( $meta, $menu->term_id, true );
					echo '>' . $menu->name . '</option>';
				}
				echo '</select>';

				break;

			case 'users':
				$meta = !empty($meta) ? $meta : $std;
				$user_list = get_users();

				echo'<select name="noo_meta_boxes[' . $id . ']" >';
				echo'	<option value="" '. selected( $meta, '', true ) . '>' . esc_html__( 'No User', 'noo-hermosa-core') . '</option>';
				foreach ( $user_list as $user ) {
					echo'<option value="' . $user->id . '"';
					selected( $meta, $user->id, true );
					echo '>' . $user->display_name . '</option>';
				}
				echo '</select>';

				break;

			case 'pages':
				$meta = !empty($meta) ? $meta : $std;
				$dropdown = wp_dropdown_pages(
					array(
						'name'              => 'noo_meta_boxes[' . $id . ']',
						'echo'              => 0,
						'show_option_none'  => ' ',
						'option_none_value' => '',
						'selected'          => $meta,
					)
				);

				echo $dropdown;

			case 'rev_slider':
				$rev_slider = new RevSlider();
				$sliders    = $rev_slider->getArrSliders();
				echo '<select name="noo_meta_boxes[' . $id . ']">';
				echo '<option value="">' . esc_html__( ' - No Slider - ', 'noo-hermosa-core') . '</option>';
				foreach ( $sliders as $slider ) {
					echo '<option value="' . $slider->getAlias() . '"';
					if ( $meta == $slider->getAlias() ) echo ' selected="selected"'; 
					echo '>' . $slider->getTitle() . '</option>';
				}
				echo '</select>';

				break;

			} // switch - $field['type']
	}
}

if( !function_exists( 'noo_save_meta_box' ) ) {
	// Save the Post Meta Boxes
	function noo_save_meta_box( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( ! isset( $_POST['noo_meta_boxes'] ) || ! isset( $_POST['noo_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['noo_meta_box_nonce'], basename( __FILE__ ) ) )
			return;

		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) return;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) return;
		}

		foreach ( $_POST['noo_meta_boxes'] as $key=>$val ) {
			update_post_meta( $post_id, $key, $val);
		}

	}
	add_action( 'save_post', 'noo_save_meta_box' );
}


if (!function_exists('noo_hermosa_json_decode')) :
	function noo_hermosa_json_decode( $json_str = '' ) {
		if( !is_string($json_str) ) return $json_str;
		$maybe_json = json_decode($json_str);
		if( empty( $maybe_json ) && !is_array( $maybe_json ) ) return array( $json_str );

		return $maybe_json;
	}
endif;

if(!function_exists('noo_hermosa_enable_vc_auto_theme_update')):
function noo_hermosa_enable_vc_auto_theme_update() {
    if( function_exists('vc_updater') ) {
        $vc_updater = vc_updater();
        remove_filter( 'upgrader_pre_download', array( $vc_updater, 'preUpgradeFilter' ), 10 );
        if( function_exists( 'vc_license' ) ) {
            if( !vc_license()->isActivated() ) {
                remove_filter( 'pre_set_site_transient_update_plugins', array( $vc_updater->updateManager(), 'check_update' ), 10 );
            }

            remove_filter( 'admin_notices', array( vc_license(), 'adminNoticeLicenseActivation' ) );
        }
    }
}
add_action('vc_after_init', 'noo_hermosa_enable_vc_auto_theme_update');
endif;

if (!function_exists('noo_hermosa_post_meta_boxes')):
	function noo_hermosa_post_meta_boxes() {
		// Declare helper object
		$prefix = '_noo_wp_post';
		$helper = new NOO_Meta_Boxes_Helper($prefix, array(
			'page' => 'post'
		));

		// Post type: Gallery
		$meta_box = array(
			'id' => "{$prefix}_meta_box_gallery",
			'title' => esc_html__( 'Gallery Settings', 'noo-hermosa'),
			'fields' => array(
				array(
					'id' => "{$prefix}_gallery",
					// 'label' => esc_html__( 'Your Gallery', 'noo-hermosa' ),
					'type' => 'gallery',
				),
				array(
					'type' => 'divider',
				),
				array(
					'id' => "{$prefix}_gallery_preview",
					'label' => esc_html__( 'Preview Content', 'noo-hermosa'),
					'type' => 'radio',
					'std' => 'featured',
					'options' => array(
						array(
							'label' => esc_html__( 'Featured Image', 'noo-hermosa'),
							'value' => 'featured',
						),
						array(
							'label' => esc_html__( 'First Image on Gallery', 'noo-hermosa'),
							'value' => 'first_image',
						),
						array(
							'label' => esc_html__( 'Image Slideshow', 'noo-hermosa'),
							'value' => 'slideshow',
						),
					)
				)
			)
		);

		$helper->add_meta_box($meta_box);

		// Post type: Video
		$meta_box = array(
			'id' => "{$prefix}_meta_box_video",
			'title' => esc_html__( 'Video Settings', 'noo-hermosa'),
			'fields' => array(
				array(
					'id' => "{$prefix}_video_embed",
					'label' => esc_html__( 'Embedded Video Code', 'noo-hermosa'),
					'desc' => esc_html__( 'If you are using videos from online sharing sites (YouTube, Vimeo, etc.) paste the embedded code here. This field will override the above settings.', 'noo-hermosa'),
					'type' => 'textarea',
					'std' => ''
				),
				array(
					'id' => "{$prefix}_video_ratio",
					'label' => esc_html__( 'Video Aspect Ratio', 'noo-hermosa'),
					'desc' => esc_html__( 'Choose the aspect ratio for your video.', 'noo-hermosa'),
					'type' => 'select',
					'std' => '16:9',
					'options' => array(
						array('value'=>'16:9','label'=>'16:9'),
						array('value'=>'5:3','label'=>'5:3'),
						array('value'=>'5:4','label'=>'5:4'),
						array('value'=>'4:3','label'=>'4:3'),
						array('value'=>'3:2','label'=>'3:2')
					)
				),
				array(
					'label' => esc_html__( 'Preview Content', 'noo-hermosa'),
					'id' => "{$prefix}_video_preview",
					'type' => 'radio',
					'std' => 'video',
					'options' => array(
						array(
							'label' => esc_html__( 'Featured Image', 'noo-hermosa'),
							'value' => 'featured',
						),
						array(
							'label' => esc_html__( 'Video', 'noo-hermosa'),
							'value' => 'video',
						)
					)
				)
			)
		);
		
		$helper->add_meta_box($meta_box);

		// Post type: Audio
		$meta_box = array(
			'id' => "{$prefix}_meta_box_audio",
			'title' => esc_html__( 'Audio Settings', 'noo-hermosa'),
			'fields' => array(
				array(
					'id' => "{$prefix}_audio_embed",
					'label' => esc_html__( 'Embedded Audio Code', 'noo-hermosa'),
					'desc' => esc_html__( 'If you are using videos from online sharing sites (like Soundcloud) paste the embedded code here. This field will override above settings.', 'noo-hermosa'),
					'type' => 'textarea',
					'std' => ''
				)
			)
		);
		
		$helper->add_meta_box($meta_box);

		// Post type: Quote
		$meta_box = array(
			'id' => "{$prefix}_meta_box_quote",
			'title' => esc_html__( 'Quote Settings', 'noo-hermosa'),
			'fields' => array(
				array(
					'id' => "{$prefix}_quote",
					'label' => esc_html__( 'The Quote', 'noo-hermosa'),
					'desc' => esc_html__( 'Input your quote.', 'noo-hermosa'),
					'type' => 'textarea',
				),
				array(
					'id' => "{$prefix}_quote_citation",
					'label' => esc_html__( 'Citation', 'noo-hermosa'),
					'desc' => esc_html__( 'Who originally said the quote?', 'noo-hermosa'),
					'type' => 'text',
				)
			)
		);
		
		$helper->add_meta_box($meta_box);

		// Post type: Link
		$meta_box = array(
			'id' => "{$prefix}_meta_box_link",
			'priority' => 'core',
			'title' => esc_html__( 'Link Settings', 'noo-hermosa'),
			'fields' => array(
				array(
					'id' => "{$prefix}_link",
					'label' => esc_html__( 'The Link', 'noo-hermosa'),
					'type' => 'text',
					'std' => 'http://nootheme.com',
				)
			)
		);
		
		$helper->add_meta_box($meta_box);

		// Page Settings: Single Post
		$meta_box = array(
			'id' => "{$prefix}_meta_box_single_page",
			'title' => esc_html__( 'Page Settings: Single Post', 'noo-hermosa'),
			'description' => esc_html__( 'Choose various setting for your Single Post page.', 'noo-hermosa'),
			'fields' => array(
				array(
					'label' => esc_html__( 'Page Layout', 'noo-hermosa'),
					'id' => "{$prefix}_global_setting",
					'type' => 'page_layout',
				),
				array(
					'label' => esc_html__( 'Override Global Settings?', 'noo-hermosa'),
					'id' => "{$prefix}_override_layout",
					'type' => 'checkbox',
					'child-fields' => array(
						'on' => "{$prefix}_layout,{$prefix}_sidebar"
					),
				),
				array(
					'label' => esc_html__( 'Page Layout', 'noo-hermosa'),
					'id' => "{$prefix}_layout",
					'type' => 'radio',
					'std' => 'sidebar',
					'options' => array(
						'fullwidth' => array(
							'label' => esc_html__( 'Full-Width', 'noo-hermosa'),
							'value' => 'fullwidth',
						),
						'sidebar' => array(
							'label' => esc_html__( 'With Right Sidebar', 'noo-hermosa'),
							'value' => 'sidebar',
						),
						'left_sidebar' => array(
							'label' => esc_html__( 'With Left Sidebar', 'noo-hermosa'),
							'value' => 'left_sidebar',
						),
					),
					// 'child-fields' => array(
					// 	'sidebar' => "{$prefix}_sidebar",
					// 	'left_sidebar' => "{$prefix}_sidebar",
					// ),
					
				),
				array(
					'label' => esc_html__( 'Post Sidebar', 'noo-hermosa'),
					'id' => "{$prefix}_sidebar",
					'type' => 'sidebars',
					'std' => 'sidebar-main'
				),
			)
		);

		if( noo_hermosa_get_option('noo_page_heading', true) ) {
			$meta_box['fields'][] = array( 'type' => 'divider' );
			$meta_box['fields'][] = array(
								'id'    => '_heading_image',
								'label' => esc_html__( 'Heading Background Image', 'noo-hermosa' ),
								'desc'  => esc_html__( 'An unique heading image for this post. If leave it blank, the default heading image of Blog ( in Customizer settings ) will be used.', 'noo-hermosa'),
								'type'  => 'image',
							);
		}

		$helper->add_meta_box( $meta_box );
	}
	
endif;

add_action('add_meta_boxes', 'noo_hermosa_post_meta_boxes');

if (!function_exists('noo_hermosa_page_meta_boxes')):
	function noo_hermosa_page_meta_boxes() {
		// Declare helper object
		$prefix = '_noo_wp_page';
		$helper = new NOO_Meta_Boxes_Helper($prefix, array(
			'page' => 'page'
		));

		// Page Settings
		$meta_box = array(
			'id' => "{$prefix}_meta_box_page",
			'title' => esc_html__( 'Page Settings', 'noo-hermosa') ,
			'description' => esc_html__( 'Choose various setting for your Page.', 'noo-hermosa') ,
			'fields' => array(
				array(
					'label' => esc_html__( 'Hide Page Heading', 'noo-hermosa') ,
					'id' => "{$prefix}_hide_page_heading",
					'type' => 'checkbox',
				),
				array(
					'label'    =>  esc_html__( 'Page Description', 'noo-hermosa' ), 
					'id' 	   => "{$prefix}_page_description",
					'type'     => 'text'
				),
				array(
					'type' => 'divider'
				),
				array(
					'id'    => "{$prefix}_menu_logo",
					'label' => esc_html__( 'Menu Logo' , 'noo-hermosa' ),
					'desc'  => esc_html__( 'Menu Logo for this page.', 'noo-hermosa' ),
					'type'  => 'image',
				),
                array(
					'type' => 'divider'
				),
				array(
                    'id'    => "{$prefix}_header_style",
                    'label' => esc_html__( 'Header Setting' , 'noo-hermosa' ),
                    'desc'  => esc_html__( 'Header Setting for this page.', 'noo-hermosa' ),
                    'type'  => 'radio',
                    'std'   => 'header',
                    'options' => array(
                        array('value'=>'header','label' => esc_html__( 'Using Header in customizer', 'noo-hermosa') ),
                        array('value'=>'header1','label' => esc_html__( 'Header Default', 'noo-hermosa') ),
                        array('value'=>'header2','label' => esc_html__( 'Header Logo Transparent (Use Logo Transparent in Logo Section if available)', 'noo-hermosa') ),
                        array('value'=>'header3','label' => esc_html__( 'Header Transparent with Logo Center', 'noo-hermosa') ),
                        array('value'=>'header4','label' => esc_html__( 'Header Transparent With Logo Top Center', 'noo-hermosa') ),
                        array('value'=>'header5','label' => esc_html__( 'Header Logo Left', 'noo-hermosa') )

                    )
                ),
				array(
					'type' => 'divider'
				),
                array(
                    'id'    => "{$prefix}_nav_position",
                    'label' => esc_html__( 'Navbar Position' , 'noo-hermosa' ),
                    'desc'  => esc_html__( 'Navbar Position for Page', 'noo-hermosa' ),
                    'type'  => 'radio',
                    'std'   => 'default_position',
                    'options' => array(
                        array('value'=>'default_position','label'=> esc_html__('Using Navbar Position in customizer', 'noo-hermosa')),
                        array('value'=>'static_top','label'=>esc_html__('Static Top', 'noo-hermosa')),
                        // array('value'=>'fixed_scroll','label'=> esc_html__('Fix When Scroll To Top', 'noo-hermosa')),
                        array('value'=>'fixed_top','label'=>esc_html__('Fixed Top', 'noo-hermosa'))
                    ),
                ),
                array(
					'type' => 'divider'
				),
                array(
					'id'      => "{$prefix}_footer_style",
					'label'   => esc_html__( 'Footer Setting' , 'noo-hermosa' ),
					'desc'    => esc_html__( 'Footer Setting for this page.', 'noo-hermosa' ),
					'type'    => 'radio',
					'std'     => 'same_as_customizer',
					'options' => array(
                        array(
                        	'value' => 'same_as_customizer',
                        	'label' => esc_html__( 'Using Footer in customizer', 'noo-hermosa')
                        ),
                        array(
                        	'value' => 'style-1',
                        	'label' => esc_html__( 'Footer Default', 'noo-hermosa')
                        ),
                        array(
                        	'value' => 'style-2',
                        	'label' => esc_html__( 'Footer With Map', 'noo-hermosa')
                        ),
                        array(
                            'value' =>'style-3',
                            'label' => esc_html__(' Footer Style Dark','noo-hermosa')
                        )
                    ),
                    'child-fields' => array(
						'same_as_customizer' => "",
						'style-1'            => "",
						'style-2'            => "{$prefix}_map_lat,{$prefix}_map_lng,{$prefix}_map_zoom,{$prefix}_map_icon",
                        'style-3'            => "",
	            	)
                ),
                array(
					'label'    =>  esc_html__( 'Latitude', 'noo-hermosa' ), 
					'id' 	   => "{$prefix}_map_lat",
					'type'     => 'text'
				),
				array(
					'label'    =>  esc_html__( 'Longitude', 'noo-hermosa' ), 
					'id' 	   => "{$prefix}_map_lng",
					'type'     => 'text'
				),
				array(
					'label'    =>  esc_html__( 'Zoom', 'noo-hermosa' ), 
					'id' 	   => "{$prefix}_map_zoom",
					'type'     => 'text'
				),
				array(
					'label'    => esc_html__( 'Icon Map', 'noo-hermosa' ),
					'id'       => "{$prefix}_map_icon",
					'type'     => 'image',
				),
                array(
					'type' => 'divider'
				),
			)
		);

		if( noo_hermosa_get_option('noo_page_heading', true) ) {
			$meta_box['fields'][] = array(
				'id'    => '_heading_image',
				'label' => esc_html__( 'Heading Background Image', 'noo-hermosa' ),
				'desc'  => esc_html__( 'An unique heading image for this page', 'noo-hermosa'),
				'type'  => 'image',
			);
		}

		$helper->add_meta_box($meta_box);

		// Page Sidebar
		$meta_box = array(
			'id' => "{$prefix}_meta_box_sidebar",
			'title' => esc_html__( 'Sidebar', 'noo-hermosa'),
			'context'      => 'side',
			'priority'     => 'default',
			'fields' => array(
				array(
					'label' => esc_html__( 'Page Sidebar', 'noo-hermosa') ,
					'id' => "{$prefix}_sidebar",
					'type' => 'sidebars',
					'std' => 'sidebar-main'
				) ,
			)
		);

		$helper->add_meta_box( $meta_box );
	}
endif;

add_action('add_meta_boxes', 'noo_hermosa_page_meta_boxes');

if ( class_exists('Noo__Timetable__Main') )
{
	// Metabox for trainer
	if ( ! function_exists( 'noo_hermosa_trainer_welcome_text' ) ) {
		function noo_hermosa_trainer_welcome_text() {
			$helper = new Noo__Timetable_Meta_Boxes_Helper( '_noo_wp_trainer', array(
				'page' => 'noo_trainer'
			));

			// Trainer Information
			$meta_box = array(
				'id'          => "trainer_info_welcome_text",
				'title'       => esc_html__( 'Welcome Text', 'noo-hermosa') ,
				'fields'      => array(
	                array(
						'id'    => "_noo_trainer_welcome_text",
						'label' => esc_html__( 'Welcome Text', 'noo-hermosa' ),
						'type'  => 'textarea',
	                )
                )
			);

			$helper->add_meta_box($meta_box);
		}
		add_action( 'add_meta_boxes', 'noo_hermosa_trainer_welcome_text', 30 );
	}
}