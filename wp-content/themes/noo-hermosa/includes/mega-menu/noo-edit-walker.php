<?php
/**
 * Edit Walker Detail
 * @since 	1.0
 * @uses 	Walker_Nav_Menu
 */

class Noo_Hermosa_Walker_Edit extends Walker_Nav_Menu {

	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	 
	
	 
	function start_lvl(&$output, $depth = 0, $args = array()) {	

	}
	
	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth = 0, $args = array()) {

	}
	
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	    global $_wp_nav_menu_max_depth;
	   
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	    ob_start();
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	
	    $title = $item->title;
	
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( esc_html__( '%s (Invalid)','noo-hermosa' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( esc_html__('%s (Pending)','noo-hermosa'), $item->title );
	    }
	
	    $title = empty( $item->label ) ? $title : $item->label;
	
	    ?>
	    <li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url( esc_url( add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' )))
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up','noo-hermosa'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
								esc_url( add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down','noo-hermosa'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item','noo-hermosa'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : esc_url( add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ) );
	                    ?>"><?php esc_html_e( 'Edit Menu Item','noo-hermosa' ); ?></a>
	                </span>
	            </dt>
	        </dl>
	
	        <div class="menu-item-settings description description-wide" id="menu-item-settings-<?php echo esc_attr($item_id); ?>" style="padding: 11px;">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url">
	                    <label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
	                        <?php esc_html_e( 'URL','noo-hermosa' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p>
	                <label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Navigation Label','noo-hermosa' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p>
	                <label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Title Attribute','noo-hermosa' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target">
	                <label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php esc_html_e( 'Open link in a new window/tab','noo-hermosa' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes">
	                <label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'CSS Classes (optional)','noo-hermosa' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn">
	                <label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Link Relationship (XFN)','noo-hermosa' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description">
	                <label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
	                    <?php esc_html_e( 'Description','noo-hermosa' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php esc_html_e('The description will be displayed in the menu if the current theme supports it.','noo-hermosa'); ?></span>
	                </label>
	            </p>        
	            <?php
	            /* New fields insertion starts here */
	            ?>  

	            <p class="fly_menu fly fly-wide">
					<label for="menu-item-fly_menu-<?php echo esc_attr($item->ID); ?>">
						<?php esc_html_e( 'Fly Menu', 'noo-hermosa' ); ?>
						<br/>
						<select id="menu-item-fly_menu-<?php echo esc_attr($item->ID); ?>" name="menu-item-fly_menu[<?php echo esc_attr($item->ID); ?>]" class="widefat code edit-menu-item-custom">
							<option <?php selected( $item->fly_menu, '' ) ?> value=""><?php esc_html_e( 'None', 'noo-hermosa' ) ?></option>
							<option <?php selected( $item->fly_menu, 'fly-right' ) ?> value="fly-right"><?php esc_html_e( 'Fly Right', 'noo-hermosa' ) ?></option>
							<option <?php selected( $item->fly_menu, 'fly-left' ) ?> value="fly-left"><?php esc_html_e( 'Fly Left', 'noo-hermosa' ) ?></option>
						</select>
					</label>
				</p> 

	            <div class="noo-mega-menu-icon">
	            	<label for="edit-menu-item-megamenu_icon-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Icon','noo-hermosa' );    ?></label>
	            	<div class="noo-icon">
	            		<span class="icon">
	            			<i class="fa fa-cogs"></i>
	            		</span>
	            		<span class="select_icon" data-id="<?php echo esc_attr($item_id); ?>">
	            			<i class="fa fa-arrow-down"></i>
	            		</span>
	            		<span class="select_color" data-id="<?php echo esc_attr($item_id); ?>">
	            			<i class="fa fa-magic"></i>
	            		</span>
	            		<span class="<?php echo empty($item->megamenu_icon) ? 'hide ': ''; ?>display icon-<?php echo esc_attr($item_id); ?>" style="color: <?php echo esc_attr( $item->megamenu_icon_color ); ?>;<?php echo empty( $item->megamenu_icon_size ) ? 'font-size: 13px' : "font-size: {$item->megamenu_icon_size}px"; ?>">
	            			<i class="fa <?php echo esc_attr( $item->megamenu_icon ); ?>"></i>
	            		</span>
	            		<div class="mega-entry list-entry-<?php echo esc_attr($item_id); ?>"  data-id="<?php echo esc_attr($item_id); ?>">
	            			<span class="megamenu-search">
	            				<input type="text" class="box-search search-<?php echo esc_attr($item_id); ?>" placeholder="<?php esc_html_e( 'Ex: balance-scale', 'noo-hermosa' ); ?>" />
	            				<i class="fa-search fip-fa fa"></i>
	            			</span>
	            			<p class="mega-list-icon list-entry-<?php echo esc_attr($item_id); ?>"></p>
	            		</div>
	            		<div class="mega-entry box-set color-<?php echo esc_attr($item_id); ?>">
	            			
	            			<div class="size">
	            				<label for="edit-menu-item-megamenu_icon_size-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Size', 'noo-hermosa' ); ?></label>
	            				<input type="number" id="edit-menu-item-megamenu_icon_size-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu_icon_size[<?php echo esc_attr($item_id); ?>]" value="<?php echo empty( $item->megamenu_icon_size ) ? '13' : $item->megamenu_icon_size; ?>">
	            			</div>

	            			<input type="text" data-id="<?php echo esc_attr($item_id); ?>" id="edit-menu-item-megamenu_icon_color-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu_icon_color[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->megamenu_icon_color ); ?>" class="color-picker-<?php echo esc_attr($item_id); ?>" />
	            			
	            			<div>
	            				<label for="edit-menu-item-megamenu_icon_alignment-<?php echo esc_attr($item_id); ?>"><?php esc_html_e( 'Icon Alignment', 'noo-hermosa' ); ?></label>
		            			<select class="select_alignment" id="edit-menu-item-megamenu_icon_alignment-<?php echo esc_attr($item_id); ?>" name="menu-item-megamenu_icon_alignment[<?php echo esc_attr($item_id); ?>]">
		            				<option value="left"<?php selected( $item->megamenu_icon_alignment, 'left' ); ?>><?php esc_html_e( 'Left', 'noo-hermosa' ); ?></option>
		            				<option value="right"<?php selected( $item->megamenu_icon_alignment, 'right' ); ?>><?php esc_html_e( 'Right', 'noo-hermosa' ); ?></option>
		            				<option value="center"<?php selected( $item->megamenu_icon_alignment, 'center' ); ?>><?php esc_html_e( 'Center', 'noo-hermosa' ); ?></option>
		            			</select>
		            		<div>

	            		</div>
	            		<input type="hidden" id="edit-menu-item-megamenu_icon-<?php echo esc_attr($item_id); ?>" data-icon="<?php echo esc_attr( $item->megamenu_icon ); ?>" value="<?php echo esc_attr( $item->megamenu_icon ); ?>" name="menu-item-megamenu_icon[<?php echo esc_attr($item_id); ?>]" />
	            	</div>
	            </div>
	            <p class="megamenu-status" style="margin-top: 15px;">
	                <label for="edit-menu-item-megamenu-<?php echo esc_attr($item_id); ?>">
                    <input class="enable_megamenu" data-id="<?php echo esc_attr($item_id); ?>" type="checkbox" id="edit-menu-item-megamenu-<?php echo esc_attr($item_id); ?>" value="megamenu" name="menu-item-megamenu[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->megamenu, 'megamenu' ); ?> />
                    <?php esc_html_e( 'Enable megamenu','noo-hermosa' );    ?>
	                </label>
	            </p>

	            <script type="text/javascript">
	            	jQuery(document).ready(function($) {
	            		
	            		if ( $('input[name="menu-item-megamenu[<?php echo esc_attr($item_id); ?>]"]:checked').serialize() != '' ) {
		            		$('.enable_megamenu_child-<?php echo esc_attr($item_id); ?>').show();
		            	} else {
		            		$('.enable_megamenu_child-<?php echo esc_attr($item_id); ?>').hide();
		            	}

	            	});
	            </script>

	            <p class="megamenu_columns megamenu-child-options enable_megamenu_child-<?php echo esc_attr( $item_id ); ?>">
					<label for="menu-item-megamenu_columns-<?php echo esc_attr($item->ID); ?>">
						<?php esc_html_e( 'Megamenu columns', 'noo-hermosa' ); ?>
						<br/>
						<select id="menu-item-megamenu_columns-<?php echo esc_attr($item->ID); ?>" name="menu-item-megamenu_columns[<?php echo esc_attr($item->ID); ?>]" class="widefat code edit-menu-item-custom">
							<option <?php selected( $item->megamenu_col, 'columns-2' ) ?> value="columns-2"><?php esc_html_e( 'Two', 'noo-hermosa' ) ?></option>
							<option <?php selected( $item->megamenu_col, 'columns-3' ) ?> value="columns-3"><?php esc_html_e( 'Three', 'noo-hermosa' ) ?></option>
							<option <?php selected( $item->megamenu_col, 'columns-4' ) ?> value="columns-4"><?php esc_html_e( 'Four', 'noo-hermosa' ) ?></option>
							<option <?php selected( $item->megamenu_col, 'columns-5' ) ?> value="columns-5"><?php esc_html_e( 'Five', 'noo-hermosa' ) ?></option>
							<option <?php selected( $item->megamenu_col, 'columns-6' ) ?> value="columns-6"><?php esc_html_e( 'Six', 'noo-hermosa' ) ?></option>
						</select>
					</label>
				</p>    

	            <p class="noo-mega-menu-heading enable_megamenu_child-<?php echo esc_attr( $item_id ); ?>">
	                <label for="edit-menu-item-megamenu_heading-<?php echo esc_attr($item_id); ?>">
                    <input type="checkbox" id="edit-menu-item-megamenu_heading-<?php echo esc_attr($item_id); ?>" value="megamenu_heading" name="menu-item-megamenu_heading[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->megamenu_heading, 'megamenu_heading' ); ?> />
                    <?php esc_html_e( 'Hide Mega menu heading?','noo-hermosa' );    ?>
	                </label>
	            </p>
                
                <p class="noo-mega-menu-widgetarea enable_megamenu_child-<?php echo esc_attr( $item_id ); ?>">
					<label for="edit-menu-item-megamenu_widgetarea-<?php echo esc_attr($item_id); ?>">
						<?php esc_html_e( 'Mega Menu Widget Area', 'noo-hermosa' ); ?>
						<select id="edit-menu-item-megamenu_widgetarea-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-custom" name="menu-item-megamenu_widgetarea[<?php echo esc_attr($item_id); ?>]">
							<option value="0"><?php esc_html_e( 'Select Widget Area', 'noo-hermosa' ); ?></option>
							<?php
							global $wp_registered_sidebars;
							if( ! empty( $wp_registered_sidebars ) && is_array( $wp_registered_sidebars ) ):
							foreach( $wp_registered_sidebars as $sidebar ):
							?>
							<option value="<?php echo esc_attr($sidebar['id']); ?>" <?php selected( $item->megamenu_widgetarea, $sidebar['id'] ); ?>><?php echo esc_html($sidebar['name']); ?></option>
							<?php endforeach; endif; ?>
						</select>
					</label>
				</p>              
	            <?php
	            /* New fields insertion ends here */
	            ?>
	            <div class="menu-item-actions submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( esc_html__('Original: %s','noo-hermosa'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
	                echo wp_nonce_url(
					esc_url( add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php esc_html_e('Remove','noo-hermosa'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php esc_html_e('Cancel','noo-hermosa'); ?></a>
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
	        </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport description description-wide"></ul>
	    <?php
	    
	    $output .= ob_get_clean();

	    }

}

new Noo_Hermosa_Walker_Edit();