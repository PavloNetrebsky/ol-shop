<?php
// Variables

// Heading attr
$noo_page_heading_spacing                 =     noo_hermosa_get_option( 'noo_page_heading_spacing', 155);

// header attr
$noo_header_fix_logo_image_height         =     noo_hermosa_get_option( 'noo_header_fix_logo_image_height', true );
$noo_header_logo_image_height             =     noo_hermosa_get_option( 'noo_header_logo_image_height', '76' );
$noo_header_logo_image_transparent_height =     noo_hermosa_get_option( 'noo_header_logo_image_transparent_height', '114' );
$noo_header_nav_height                    =     noo_hermosa_get_option( 'noo_header_nav_height', '120' );
$noo_header_nav_link_spacing              =     noo_hermosa_get_option( 'noo_header_nav_link_spacing', 20);
$noo_header_bg_color                      =     noo_hermosa_get_option( 'noo_header_bg_color', '#fff');
$noo_header_fixed_bg_color                =     noo_hermosa_get_option( 'noo_header_fixed_bg_color', '#fff');


// logo text
$noo_header_use_image_logo    =     noo_hermosa_get_option( 'noo_header_use_image_logo', false );
$noo_header_logo_font         =     noo_hermosa_get_option( 'noo_header_logo_font', noo_hermosa_get_theme_default( 'logo_font_family' ) );
$noo_header_logo_font_size    =     noo_hermosa_get_option( 'noo_header_logo_font_size', '30' );
$noo_header_logo_font_color   =     noo_hermosa_get_option( 'noo_header_logo_font_color', noo_hermosa_get_theme_default( 'logo_color' ) );
$noo_header_logo_font_subset  =     noo_hermosa_get_option( 'noo_header_logo_font_subset', 'latin' );
$noo_header_logo_uppercase    =     noo_hermosa_get_option( 'noo_header_logo_uppercase', false ) ? 'uppercase': 'initial';

// navigation
$noo_header_custom_nav_font   =     noo_hermosa_get_option( 'noo_header_custom_nav_font', false );

if ( $noo_header_custom_nav_font ):

$noo_header_nav_font          =     noo_hermosa_get_option( 'noo_header_nav_font', noo_hermosa_get_theme_default( 'headings_font_family' ) );
$noo_header_nav_color         =     noo_hermosa_get_option( 'noo_header_nav_link_color', '' );
$noo_header_nav_hover_color   =     noo_hermosa_get_option( 'noo_header_nav_link_hover_color');
$noo_header_nav_font_size     =     noo_hermosa_get_option( 'noo_header_nav_font_size', 15 );
$noo_header_nav_uppercase     =     noo_hermosa_get_option( 'noo_header_nav_uppercase', true ) ? 'uppercase': 'initial';

?>

    /*
    * Typography for menu
    * ===============================
    */

    header .noo-main-menu .navbar-nav li > a{
        font-family:     "<?php echo esc_html($noo_header_nav_font); ?>", sans-serif;
        font-size:       <?php echo esc_attr( $noo_header_nav_font_size ) . 'px'; ?>;
        text-transform:  <?php echo esc_attr( $noo_header_nav_uppercase ) ; ?>;
        <?php if ( $noo_header_nav_color != '' ): ?>
            color: <?php echo esc_attr( $noo_header_nav_color ) ; ?>;
        <?php endif; ?>
        
    }

    .noo-header.fixed_top.fixed-top-eff .noo-main-menu .navbar-nav li > a {
        font-size:       <?php echo esc_attr( $noo_header_nav_font_size - 1 ) . 'px'; ?>;
    }
    <?php if( $noo_header_nav_hover_color != '' ): ?>
        header .noo-main-menu .navbar-nav li > a:hover,
        header .noo-main-menu .navbar-nav li > a:focus,
        header .noo-main-menu .navbar-nav li > a:active{
            color: <?php echo esc_attr( $noo_header_nav_hover_color ) ; ?>;
        }
        header .noo-main-menu .navbar-nav li > .sub-menu li a:hover{
            color: <?php echo esc_attr( $noo_header_nav_hover_color ) ; ?>;
        }
    <?php endif; ?>

    .noo-main-menu .navbar-nav li.noo_megamenu > .sub-menu > li > a{
        text-transform:  <?php echo esc_attr( $noo_header_nav_uppercase ) ; ?>;
    }

<?php endif; ?>

/*
* Heading spacing
* ===============================
*/

.noo-page-heading {
    padding-top:    <?php echo esc_attr( $noo_page_heading_spacing ).'px'; ?>;
    padding-bottom:   <?php echo esc_attr( $noo_page_heading_spacing ).'px'; ?>;
}

/*
* Background for menu
* ===============================
*/

.noo-header .navbar-wrapper {
    background-color: <?php echo esc_attr( $noo_header_bg_color ) ; ?>;
}

.noo-header.fixed_top.fixed-top-eff .navbar-wrapper {
    background-color: <?php echo esc_attr( $noo_header_fixed_bg_color ) ; ?>;
}

/*
* Alignment for menu
* ===============================
*/

header .noo-main-menu .navbar-nav li{
    padding-left:    <?php echo esc_attr( $noo_header_nav_link_spacing ).'px'; ?>;
    padding-right:   <?php echo esc_attr( $noo_header_nav_link_spacing ).'px'; ?>;
}
header .noo-main-menu .navbar-nav li > a{
    line-height:     <?php echo esc_attr( $noo_header_nav_height - 20 ).'px'; ?>;
}

/*
* Typography for Logo text
* ===============================
*/
<?php if ( $noo_header_fix_logo_image_height ) : ?>
header .navbar-brand .noo-logo-img{
    height: <?php echo esc_attr( $noo_header_logo_image_height ). 'px'; ?>;
}

header .navbar-brand .noo-logo-img-transparent{
    height: <?php echo esc_attr( $noo_header_logo_image_transparent_height ). 'px'; ?>;
}
<?php endif; ?>

header .navbar{
    min-height: <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
}
header .navbar-brand{
    
    <?php if( $noo_header_use_image_logo == false): ?>
        font-family:    <?php echo esc_attr( $noo_header_logo_font ); ?>, sans-serif;
        font-size:      <?php echo esc_attr( $noo_header_logo_font_size ) .'px'; ?>;
        color:          <?php echo esc_attr( $noo_header_logo_font_color ); ?>;
        text-transform: <?php echo esc_attr( $noo_header_logo_uppercase ); ?>;
        line-height: <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
    <?php else : ?>
        line-height: <?php echo esc_attr( $noo_header_nav_height ).'px'; ?>;
        margin-top: 0;
    <?php endif; ?>

}
