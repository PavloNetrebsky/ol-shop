<?php
/**
 * Show Hide Top Bar
 */
$phone = noo_hermosa_get_option( 'noo_header_topbar_phone', '');
$email = noo_hermosa_get_option( 'noo_header_topbar_email', '');
$cart = noo_hermosa_get_option( 'noo_header_topbar_cart', false);
?>
<div class="noo-topbar">
	<ul class="noo-topbar-left">
		<?php if( isset($phone) && !empty($phone) ): ?>
            <li class="meta-phone">
                <span><i class="fa fa-phone"></i></span>
                <a href="tel:<?php echo str_replace(' ', '', esc_attr($phone)) ?>"><?php echo esc_html($phone) ?></a>
            </li>
        <?php endif; ?>
        <?php if( isset($email) && !empty($email) ): ?>
            <li class="meta-email">
                <span><i class="fa fa-envelope"></i></span>
                <a href="mailto:<?php echo esc_attr($email) ?>"><?php echo esc_html($email) ?></a>
            </li>
        <?php endif; ?>
	</ul>
	<ul class="noo-topbar-right">
		<?php if( $cart ) : ?>
            <li><?php echo noo_hermosa_minicart_mobile(); ?></li>
        <?php endif; ?>
	</ul>
</div>