<?php
// Use Custom Headings Font
$noo_typo_use_custom_headings_font = noo_hermosa_get_option( 'noo_typo_use_custom_headings_font', false );
// Use Custom Body Font
$noo_typo_use_custom_body_font     = noo_hermosa_get_option( 'noo_typo_use_custom_body_font', false );
// Use Custom Special Font
$noo_typo_use_custom_special_font     = noo_hermosa_get_option( 'noo_typo_use_custom_special_font', false );


if( $noo_typo_use_custom_headings_font ) :
    $noo_typo_headings_uppercase = noo_hermosa_get_option( 'noo_typo_headings_uppercase', false );
    $noo_typo_text_transform = !empty( $noo_typo_headings_uppercase ) ? 'uppercase' : 'none';
    $noo_typo_headings_font = noo_hermosa_get_option( 'noo_typo_headings_font', noo_hermosa_get_theme_default( 'headings_font_family' ) );
    $noo_typo_headings_font_color =  noo_hermosa_get_option( 'noo_typo_headings_font_color', noo_hermosa_get_theme_default( 'headings_color' ) );
?>
    /* Headings */
    /* ====================== */
    h1, h2, h3, h4, h5, h6,
    .h1, .h2, .h3, .h4, .h5, .h6 {
        font-family:    "<?php echo esc_html( $noo_typo_headings_font ); ?>", sans-serif;
        color:          <?php echo esc_html( $noo_typo_headings_font_color ); ?>;
        text-transform: <?php echo esc_html( $noo_typo_text_transform ); ?>;
    }
<?php endif; ?>
<?php
if( $noo_typo_use_custom_body_font ) :
    $noo_typo_body_font = noo_hermosa_get_option( 'noo_typo_body_font', noo_hermosa_get_theme_default( 'font_family' ) );
    $noo_typo_body_font_color = noo_hermosa_get_option( 'noo_typo_body_font_color', noo_hermosa_get_theme_default( 'text_color' ) );
    $noo_typo_body_font_size = noo_hermosa_get_option( 'noo_typo_body_font_size', noo_hermosa_get_theme_default( 'font_size' ) );
?>
    /* Body style */
    /* ===================== */
     body {
        font-family: "<?php echo esc_html( $noo_typo_body_font ); ?>", sans-serif;
        color:        <?php echo esc_html( $noo_typo_body_font_color ); ?>;
        font-size:    <?php echo esc_html( $noo_typo_body_font_size ) . 'px'; ?>;
    }

    <?php if( class_exists('Hc_Insert_Html_Widget') ) :?>
        
        .site div.healcode{
            font-size: <?php echo esc_html($noo_typo_body_font_size) . 'px'; ?>;
        }
        .site div.healcode select{
            font-size: <?php echo esc_html($noo_typo_body_font_size) . 'px'; ?>;
        }
        
    <?php endif;?>
    
<?php endif; ?>

<?php
if( $noo_typo_use_custom_special_font ) :
    $noo_typo_special_font = noo_hermosa_get_option( 'noo_typo_special_font', 'Droid Serif' );
?>
    /* Special style */
    /* ===================== */
    .noo-counter-wrap .noo-counter-content h4,
    .single-post .entry-content blockquote,
    .category .entry-content blockquote,
    .blog .entry-content blockquote,
    .text-pricetable,
    .noo-pricetable .noo-pricetable-header .item-price,    
    .noo-pricetable .noo-pricetable-header span,    
    .noo-testimonial .noo-testimonial-item .noo-testimonial-content,
    .our-blog-item .noo-blog-footer span,
    .noo-mailchimp .noo-mailchimp-left .noo-sub-title,
    .noo-video p,    
    .noo-short-intro p,
    .noo-event-calendar-wrap .fc-basic-view .fc-day-number,    
    .sc-noo-product-wrap > .noo-product-head .noo-product-sub-title,
    .noo-class-schedule-shortcode .fc-month-view .fc-day-number span,
    .noo-theme-wraptext .wrap-title .noo-theme-sub-title,
    .single-noo_trainer .noo-progress-bar .noo-single-bar .label-bar .noo-progress-label,
    .single-noo_trainer .trainer-content .content blockquote,
    .single-noo_trainer .welcome-text,
    .single-noo_class .hentry .content-wrap blockquote,
    .post_list_widget li a .post-date,
     .noo-page-heading .page-description {
        font-family: "<?php echo esc_html( $noo_typo_special_font ); ?>", sans-serif;
    }
    
<?php endif; ?>