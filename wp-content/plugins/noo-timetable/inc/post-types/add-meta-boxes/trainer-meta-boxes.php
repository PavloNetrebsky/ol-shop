<?php
/**
 * Trainer Meta Boxes
 *
 * Setting the information is used on the calendar, archive and single pages.
 * 
 * @author      NooTheme
 * @category    Admin
 * @package     NooTimetable/PostTypes/MetaBoxes
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if( ! class_exists('Noo__Timetable__Trainer_Meta_Boxes') ) {

    class Noo__Timetable__Trainer_Meta_Boxes {

    	public function __construct() {

    		if ( is_admin() ) {
    			// Metabox
				add_action( 'add_meta_boxes', array( &$this, 'add_meta_boxes' ), 30 );

				// Columns
				add_filter( 'manage_edit-noo_trainer_columns', array($this, 'manage_edit_columns') );
				add_filter( 'manage_noo_trainer_posts_custom_column',  array($this, 'manage_custom_column'), 2 );
    		}

    	}

		public function add_meta_boxes() {

			// Class Category
	        add_meta_box( '_noo_trainer_class_category', esc_html__( 'Trainer Class Category', 'noo-timetable' ), array( &$this, 'trainer_class_category' ), 'noo_trainer', 'normal', 'high');

			// Declare helper object
			// 
			$helper = new Noo__Timetable_Meta_Boxes_Helper( '_noo_wp_trainer', array(
				'page' => 'noo_trainer'
			));

			// Trainer Information
			$meta_box = array(
				'id'          => "trainer_information",
				'title'       => esc_html__( 'Trainer Information', 'noo-timetable') ,
				'fields'      => array(
	                array(
						'id'    => "_noo_trainer_position",
						'label' => esc_html__( 'Position', 'noo-timetable' ),
						'type'  => 'text',
	                ),
	                array(
						'id'    => "_noo_trainer_experience",
						'label' => esc_html__( 'Experience', 'noo-timetable' ),
						'type'  => 'text',
	                ),
	                array(
						'id'    => "_noo_trainer_email",
						'label' => esc_html__( 'Email', 'noo-timetable' ),
						'type'  => 'text',
	                ),
	                array(
						'id'    => "_noo_trainer_phone",
						'label' => esc_html__( 'Phone', 'noo-timetable' ),
						'type'  => 'text',
	                ),
	                array(
						'id'    => "_noo_trainer_biography",
						'label' => esc_html__( 'Biography', 'noo-timetable' ),
						'type'  => 'textarea',
	                ),
	                array( 
						'id'       => '_noo_trainer_skill', 
						'label'    => esc_html__( 'Skill', 'noo-timetable' ), 
						'type'     => 'select_multiple',
						'callback' => array( &$this, 'meta_box_skill' )
					),
				)
			);

			$helper->add_meta_box($meta_box);

			// Trainer Social 
			$meta_box = array(
	            'id'           => "trainer_social",
	            'title'        => esc_html__( 'Trainer Social Information', 'noo-timetable' ),
	            'fields'       => array(
	                array(
						'id'    => "_noo_trainer_facebook",
						'label' => esc_html__( 'Facebook URL', 'noo-timetable' ),
						'type'  => 'text',
	                ),
	                array(
						'id'    => "_noo_trainer_google",
						'label' => esc_html__( 'Google URL', 'noo-timetable' ),
						'type'  => 'text',
	                ),
	                array(
						'id'    => "_noo_trainer_twitter",
						'label' => esc_html__( 'Twitter URL', 'noo-timetable' ),
						'type'  => 'text',
	                ),
	                array(
						'id'    => "_noo_trainer_pinterest",
						'label' => esc_html__( 'Pinterest URL', 'noo-timetable' ),
						'type'  => 'text',
	                ),
	            )
	        );
	        $helper->add_meta_box($meta_box);

		}

		public function trainer_class_category( $post, $meta_box ) {

		    $taxonomy = 'class_category';
		    $tax      = get_taxonomy( $taxonomy );
		    $selected = wp_get_object_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );

		    ?>
		    <div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">

		        <input type="hidden" name="tax_input[<?php echo $taxonomy; ?>][]" value="0" />

		        <ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy; ?> categorychecklist form-no-clear">
		            <?php
		                wp_terms_checklist( $post->ID, array(
		                    'taxonomy'      => $taxonomy,
		                    'selected_cats' => $selected
		                ) );
		            ?>
		        </ul>

		    </div>
		    <?php
		}

		public function meta_box_skill( $post, $id, $type, $meta, $std, $field ) {
			
			?>
			<div id="skills">
				<?php

				$new_skill_label = '_noo_trainer_skill_label';
				$new_skill_value = '_noo_trainer_skill_value';

				$skill_label = noo_timetable_get_post_meta( $post->ID, $new_skill_label, '' );
				$skill_value = noo_timetable_get_post_meta( $post->ID, $new_skill_value, '' );
				
				$skill_label = (array) noo_timetable_json_decode($skill_label);
				$skill_value = (array) noo_timetable_json_decode($skill_value);
				
				?>
				<div class="noo-control">
					<div class="row-append">
						<table class="row-schedule-item" cellpadding="0" cellspacing="0">
							<thead>
								<tr>
									<th></th>
									<th></th>
									<th><?php echo esc_html__('Label', 'noo-timetable'); ?></th>
									<th><?php echo esc_html__('Value', 'noo-timetable') . ' (%)' ?></th>
								</tr>
							</thead>
							<tbody>
							<?php
							if ( is_array($skill_label) && count($skill_label) > 0 ) : 
								foreach ($skill_label as $k => $label) :
							?>
							<tr>
								<td><div class="button-action minus">-</div></td>
								<td class="sort"><i class="dashicons-grid-view dashicons-before"></i></td>

								<td><input type="text" name="noo_meta_boxes[<?php echo $new_skill_label; ?>][]" class="input_text" id="" value="<?php echo isset($skill_label[$k]) ? esc_attr($skill_label[$k]) : ''; ?>"></td>
								<td><input type="text" name="noo_meta_boxes[<?php echo $new_skill_value; ?>][]" class="input_text" id="" value="<?php echo isset($skill_value[$k]) ? esc_attr($skill_value[$k]) : ''; ?>"></td>
							</tr>
							<?php endforeach;
							else:
								?>
							<tr>
								<td><div class="button-action minus">-</div></td>
								<td class="sort"><i class="dashicons-grid-view dashicons-before"></i></td>
								
								<td><input type="text" name="noo_meta_boxes[<?php echo $new_skill_label; ?>][]" class="input_text" id="" value=""></td>
								<td><input type="text" name="noo_meta_boxes[<?php echo $new_skill_value; ?>][]" class="input_text" id="" value=""></td>
							</tr>
							</tbody>
							<?php
							endif; ?>
						</table>
					</div>
					<div class="button-action add">+</div>
				</div>
			</div> <!--# Skills -->

			<script type="text/javascript">
				jQuery(document).ready(function($) {

					$('.row-schedule-item tbody').sortable({
				        items:'tr',
				        cursor:'move',
				        axis:'y',
				        handle: 'td.sort',
				        scrollSensitivity:40,
				        forcePlaceholderSize: true,
				        helper: 'clone',
				        opacity: 0.65
				    });

					$('.button-action.add').click(function(){
						var _html = $(this).closest('.noo-control').find('table tbody tr:last-child').html();
						$(this).closest('.noo-control').find('table tbody').append('<tr>'+_html+'</tr>');
						$(this).closest('.noo-control').find('table tbody tr:last-child').find('input, select').val('');


						$('.button-action.minus').click(function(){
							if ($(this).closest('.noo-control').find('table tbody tr').length > 1){
								$(this).closest('tr').hide(300, function(){
									$(this).remove();
								});
							}
						});

					});

					$('.button-action.minus').click(function(){
						if ($(this).closest('.noo-control').find('table tbody tr').length > 1){
							$(this).closest('tr').hide(300, function(){
								$(this).remove();
							});
						}
					});

				});
			</script>

		<?php
		}

		// Add Thumbnail Column to the Trainer Admin page
		function manage_edit_columns($columns) {

			$new_columns                   = array();
			$new_columns['cb']             = $columns['cb'];
			$new_columns['thumbnail']      = esc_html__('Thumbnail', 'noo-timetable');
			$new_columns['title']          = $columns['title'];
			$new_columns['class_category'] = esc_html__('Class Category', 'noo-timetable');
			unset( $columns['cb'] );
			unset( $columns['title'] );
			return array_merge( $new_columns, $columns );
		}

		function manage_custom_column($column) {

			global $post;
			
			if ($column == 'thumbnail') {
				$prefix = '_noo_trainer';
				$admin_thumb = 'thumbnail';
				$post_id = get_the_ID();
				$post_format = noo_timetable_get_post_meta($post_id, "{$prefix}_media_type", 'image');
				$thumb = '';

				switch ($post_format) {
					case 'image':
						$main_image = noo_timetable_get_post_meta($post_id, "{$prefix}_main_image", 'featured');
						if( $main_image == 'featured') {
							$thumb = get_the_post_thumbnail($post_id, array( 80, 80));
						} else {
							$image_id = (int) noo_timetable_get_post_meta($post_id, "{$prefix}_image", '');
							$thumb = !empty($image_id) ? wp_get_attachment_image( $image_id, $admin_thumb) : '';
						}

						break;
					case 'link':
						$link = noo_timetable_get_post_meta($post_id, "{$prefix}_url", '#');
						$thumb = get_the_post_thumbnail($post_id, $admin_thumb);
						break;
					case 'gallery':
						$gallery_ids = noo_timetable_get_post_meta($post_id, "{$prefix}_gallery", '');
						if(!empty($gallery_ids)) {
							$gallery_arr = explode(',', $gallery_ids);
							$image_id = (int) $gallery_arr[0];
							$thumb = !empty($image_id) ? wp_get_attachment_image( $image_id, $admin_thumb) : '';
						}

						break;
					default:
						$thumb = get_the_post_thumbnail($post_id, array( 80, 80));
						break;
				}

				echo '<a href="' . get_edit_post_link() . '">' . $thumb . '</a>';
			} elseif ($column === 'class_category') {
				$class_cate = wp_get_post_terms(get_the_ID(), 'class_category');
				foreach ($class_cate as $itd => $tid) {
					echo edit_post_link($tid->name,'','',$tid);
					if ( ($itd + 1) < count($class_cate) ) echo ', ';
				}
			}
		}

    }

    new Noo__Timetable__Trainer_Meta_Boxes();
}
