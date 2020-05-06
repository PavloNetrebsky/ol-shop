<?php
/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
class Noo_Hermosa_Megamenu_Walker extends Walker_Nav_Menu {

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        global $wp_query;
        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
        $heading = '';
        $widget = '';
        $class_names = $value = '';

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );

        $megamenu = ! empty( $item->megamenu ) ? 'noo-menu noo_megamenu mega-col-' . esc_attr( $item->megamenu_col ) : 'noo-menu';

        if ( !empty ( $item->fly_menu ) ) :
            $class_fly = $item->fly_menu;
        else :
            $class_fly = '';
        endif;

        if( !empty  ( $item->megamenu_heading ) )
            $heading = 'noo_heading';

        if( $item->megamenu_widgetarea && is_active_sidebar( $item->megamenu_widgetarea ))
            $widget = 'noo_widget_area';

        $class_names = ' class="'. $megamenu .' '. $heading .' '. $widget .' '. esc_attr( $class_names ).' '. esc_attr( $class_fly ).'"';

        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

        $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
        $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
        $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
        $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

        $prepend     = '';
        $append      = '';
        $description = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

        if($depth != 0) $description = $append = $prepend = "";

        // --- Set tag style default empty
        $style = "";

        // --- check icon
        if ( !empty ( $item->megamenu_icon_color ) )
            $style .= "color: {$item->megamenu_icon_color};";

        // --- check size icon
        if ( !empty ( $item->megamenu_icon_size ) )
            $style .= "font-size: {$item->megamenu_icon_size}px;";

        // --- check alignment icon
        // if ( !empty ( $item->megamenu_icon_alignment ) )
        // 	$style .= "float: {$item->megamenu_icon_alignment}; padding: 0 6px;";

        // --- check megamenu icon
        if( !empty ( $item->megamenu_icon ) )
            $prepend .= '<i class="fa ' . esc_attr( $item->megamenu_icon ) .'" style="' . $style . '"></i>';

        // --- Set default
        $item_output = '';

        // ---
        if( $item->megamenu_widgetarea && is_active_sidebar( $item->megamenu_widgetarea ) ) :
            $item_output .= '<div class="noo_megamenu_widget_area">';
            ob_start();

            dynamic_sidebar( $item->megamenu_widgetarea );

            $item_output .= ob_get_clean() . '</div>';
        else :

            if( isset($args->before) ) :

                $item_output = $args->before;

            endif;

            $item_output .= '<a'. $attributes .'>';

            if(isset($args->link_before)){

                $item_output .= $args->link_before . $prepend . apply_filters( 'the_title', $item->title, $item->ID ) . $append;
            }

            if(isset($args->link_after)){

                $item_output .= $description.$args->link_after;
            }

            $item_output .= '</a>';


            if(isset($args->before)){

                $item_output .= $args->after;
            }

        endif;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id );
    }
}