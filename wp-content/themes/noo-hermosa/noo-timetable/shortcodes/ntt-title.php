<?php
/**
 * The template for displaying title template tag default
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/shortcodes/ntt-title.php.
 *
 * @author      NooTheme
 * @package     NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


global $title_var;

if ( $title_var )
    extract($title_var, EXTR_PREFIX_SAME, "ntt");
?>

<?php if ( !empty( $title ) || !empty( $sub_title ) ) : ?>
<!-- Section title -->
    <div class="noo-theme-wraptext">
        <div class="wrap-title">

        <?php if ( !empty( $title ) ) : ?>
            <div class="noo-theme-title-bg"></div>

            <h3 class="noo-theme-title">
                <?php
                    $title = explode( ' ', $title );
                    $title[0] = '<span class="first-word">' . esc_html( $title[0] ) . '</span>';
                    $title = implode( ' ', $title );
                ?>
                <?php echo wp_kses($title,noo_hermosa_allowed_html()); ?>
            </h3>
        <?php endif; ?>

        <?php if ( !empty( $sub_title ) ) : ?>
            <p class="noo-theme-sub-title">
                <?php echo esc_html( $sub_title ); ?>
            </p>
        <?php endif; ?>

        </div> <!-- /.wrap-title -->    
    </div>
<?php endif; ?>