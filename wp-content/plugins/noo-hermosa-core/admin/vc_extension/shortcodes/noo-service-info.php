<?php 

if( ! function_exists('noo_service_info_shortcode')):
	function noo_service_info_shortcode($atts, $content = null){
		$atts  = vc_map_get_attributes( 'noo_service_info', $atts );
		extract(shortcode_atts(array(
			'icon' 				=> '',
			'icon_size'         => '',
			'icon_color'        => '',
			'text_same_size'	=> '',
			'text_size'       	=> '',
			'text_same_color'   => '',
			'text_color'        => '',
			'title'				=> '',
			
		),$atts));

	
		$custom_style	= '';
		$icon_style  	= '';

		$icon_class 	= ( $icon != '')  ? 'fa ' . esc_attr( $icon ) : '' ;
		$custom_style 	= ( $custom_style != '' ) ? esc_attr( $custom_style ) : '';

		
		$custom_style  	= ( $custom_style != '' ) ? ' style="' . esc_attr( $custom_style ) .'"' : '';
		$icon_class		= ( $icon_class != '' ) ? 'class="' .esc_attr( $icon_class ) . '"' : '' ;
		$title 			= ( $title != '') ? esc_attr( $title ) : '';

		$icon_style   .= ( $icon_size != '' ) ? ' font-size: ' . $icon_size . 'px;' : '';
		$icon_style   .= ( $icon_color != '' ) ? ' color: ' . $icon_color . ';' : '';


		if ( $text_same_size == 'true' ) {
			$custom_style .= ( $icon_size != '' ) ? ' font-size: ' . $icon_size . 'px;' : '';
		} else {
			$custom_style .= ( $text_size != '' ) ? ' font-size: ' . $text_size . 'px;' : '';
			
		}
		if ( $text_same_color == 'true' ) {
			$custom_style .= ( $icon_color != '' ) ? ' color: ' . $icon_color . ';' : '';
		} else {
			$custom_style .= ( $text_color != '' ) ? ' color: ' . $text_color . ';' : '';
		}

		ob_start();
		?>
		<div class="service-info-block">
			<?php if( isset($icon_class) && !empty($icon_class)) :?>
				<div class="service-icon-buzz"><i style="<?php echo $icon_style;?>" <?php echo $icon_class; ?>></i></div>
			<?php endif; ?>
			<?php if( isset( $title ) or isset( $content )): ?>
				<div class="service-info-content">
				 	<?php if(isset( $title) && !empty( $title )): ?>
				 		<span class="noo-offered-title"  style="<?php echo $custom_style; ?>" >
					 		<?php echo $title; ?>
					 	</span>
				 	<?php endif; ?>
				 	<?php if(isset($content) && !empty( $content )) :?>
						<p><?php echo $content ; ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>

	<?php 
		return ob_get_clean();
}
	add_shortcode('noo_service_info','noo_service_info_shortcode');

endif; 