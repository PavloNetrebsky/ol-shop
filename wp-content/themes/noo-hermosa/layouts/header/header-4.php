<?php
$blog_name		= noo_hermosa_get_option( 'blogname', get_bloginfo( 'name' ) );
$blog_desc		= get_bloginfo( 'description' );
$image_logo		= '';
$page_logo		= '';
$class_logo     = '';

/**
 * Show Hide Top Bar
 */
$use_topbar = noo_hermosa_get_option( 'noo_header_topbar_on', false);

/**
 * Show Hide Top Menu
 */
$use_top_menu         = noo_hermosa_get_option('noo_header_nav_top_menu', true);
$hide_top_menu_scroll = noo_hermosa_get_option('noo_header_nav_top_menu_hide_scroll', false);
$class_top_menu_hide_fix = ($use_top_menu && $hide_top_menu_scroll) ? ' top-menu-hide-fix' : '';

/**
 * Show Hide Icon Search
 */
$use_search         = noo_hermosa_get_option('noo_header_nav_icon_search', true);
$hide_search_scroll = noo_hermosa_get_option('noo_header_nav_icon_search_hide_scroll', false);
$class_search_hide_fix = ($use_search && $hide_search_scroll) ? ' search-hide-fix' : '';

/**
 * Show Hide Icon Cart
 */
$woocommerce_active = ( class_exists( 'woocommerce' ) ? true : false );
$use_cart           = ( !empty( $woocommerce_active ) ? noo_hermosa_get_option('noo_header_nav_icon_cart', true) : false );
$hide_cart_scroll   = noo_hermosa_get_option('noo_header_nav_icon_cart_hide_scroll', false);
$class_cart_hide_fix = ($use_cart && $hide_cart_scroll) ? ' cart-hide-fix' : '';

/**
 * Header Style in Customize
 */
$menu_style     = noo_hermosa_get_option('noo_header_nav_style', 'header1');
if( is_page() ) {

    $header_page = noo_hermosa_get_post_meta(get_the_ID(), '_noo_wp_page_header_style');

    if( !empty( $header_page ) && $header_page != 'header' ){
        $menu_style = $header_page;
    }

}
/**
 * Header Style in Page
 */
if ( noo_hermosa_get_option( 'noo_header_use_image_logo', false ) ) {
    if ( noo_hermosa_get_image_option( 'noo_header_logo_image', '' ) !=  '' ) {
        $image_logo = noo_hermosa_get_image_option( 'noo_header_logo_image', '' );
    }
    if ('header4' == $menu_style) {
        $trans_logo = noo_hermosa_get_image_option('noo_header_logo_image_in_top', '');

        if (!empty($trans_logo) && '' != $trans_logo) {
            $image_logo = $trans_logo;
            $class_logo = 'noo-logo-img-transparent';
        }

    }



    if( is_page() ) {
        $page_logo = noo_hermosa_get_post_meta(get_the_ID(), '_noo_wp_page_menu_logo');

        if (!empty( $page_logo ) ){
            $image_logo = wp_get_attachment_url( esc_attr($page_logo) );
            $class_logo = '';
        }

    }
}

?>
<div class="navbar navbar-header4">
    <?php if ($use_search) : ?>
        <div class="noo-top-left-widget <?php echo esc_attr($class_search_hide_fix); ?>"><a href="#"
                                                                                            class="noo-search-button noo-icon-search"><i
                        class="icon ion-ios-search-strong"></i><span><?php esc_html_e('Search ...', 'noo-hermosa'); ?></span></a>
        </div>
    <?php endif; ?>
    <div class="navbar-header pull-left">
        <?php if (is_front_page()) : echo '<h1 class="sr-only">' . $blog_name . '</h1>'; endif; ?>
        <a href="<?php echo home_url('/'); ?>" class="logo-image" title="<?php echo esc_attr($blog_desc); ?>">
            <?php echo esc_attr($image_logo == '') ? $blog_name : '<img class="noo-logo-img noo-logo-normal ' . esc_attr($class_logo) . '" src="' . esc_url($image_logo) . '" alt="' . esc_attr($blog_desc) . '">'; ?>
        </a>
    </div>
    <?php if ($use_cart) : ?>
        <div
                class="noo-top-right-widget <?php echo esc_attr($class_cart_hide_fix); ?>"><?php echo noo_hermosa_minicart_mobile(); ?></div>
    <?php endif; ?>

    <div class="menu-position">
        <?php
        $wp_nav_menu_to_count = wp_nav_menu( array(
            'theme_location' => 'primary-left',
            'walker'         => new Noo_Hermosa_Megamenu_Walker,
            'echo'           => false,
            'depth'          => '1'
        ) );
        $wp_nav_menu_counter = substr_count( $wp_nav_menu_to_count, 'noo-menu' );
        ?>

        <nav class="noo-main-menu noo-left-menu <?php echo esc_attr( $wp_nav_menu_counter >= 6 ) ? 'menu-less' : ''; ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary-left',
                'container'      => false,
                'menu_class'     => 'nav-collapse navbar-nav',
                'fallback_cb'    => 'noo_hermosa_notice_set_menu',
                'walker'         => new Noo_Hermosa_Megamenu_Walker
            ) );
            ?>
        </nav>

        <?php
        $wp_nav_menu_to_count = wp_nav_menu( array(
            'theme_location' => 'primary-right',
            'walker'         => new Noo_Hermosa_Megamenu_Walker,
            'echo'           => false,
            'depth'          => '1'
        ) );
        $wp_nav_menu_counter = substr_count( $wp_nav_menu_to_count, 'noo-menu' );
        ?>

        <nav class="noo-main-menu noo-right-menu  <?php echo esc_attr( $wp_nav_menu_counter >= 6 ) ? 'menu-less' : ''; ?>">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary-right',
                'container'      => false,
                'menu_class'     => 'nav-collapse navbar-nav',
                'fallback_cb'    => 'noo_hermosa_notice_set_menu',
                'walker'         => new Noo_Hermosa_Megamenu_Walker
            ) );
            ?>
        </nav>

        <button data-target=".nav-collapse" class="btn-navbar noo_icon_menu" type="button">
            <i class="icon ion-android-menu"></i>
        </button>
    </div>
</div>
