<?php
/**
 * Create shortcode: [noo_form]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		Tu Nguyen
 * @version 	1.0
 */
    if( !function_exists('noo_shortcode_form') ){

        function noo_shortcode_form($attrs){
            $attrs  = vc_map_get_attributes( 'noo_form', $attrs );
            extract(shortcode_atts(array(
                'style_contact_form'  =>  'style-default',
                'title_1'             =>  '',
                'title_2'             =>  '',
                'description'         =>  '',
                'custom_form'         =>  '',
                'color'               =>  '',
                'color_text'          =>  '',
            ),$attrs));
            ob_start();

            wp_enqueue_script( 'datetimepicker' );
            wp_enqueue_style( 'datetimepicker' );


            ?>
            <div class="noo-contact-form-7 <?php echo esc_attr($style_contact_form); ?>">
                <?php if( $style_contact_form == 'style-default'): ?>
                    <?php if ( !empty( $title_1 ) ) : ?>
                        <h3 class="noo-theme-title">

                            <!-- <?php
                            //$title_1 = explode( '/', $title_1 );
                            //$title_1[0] = '<span class="first-word">' . esc_html( $title_1[0] ) . '</span>';
                            //$title_1 = implode( ' ', $title_1 );
                            ?>  -->
                            <!-- <?php //echo noo_hermosa_html_content_filter($title_1); ?> -->
                            <?php
                                $title_1 = explode( ' ', $title_1 );
                                $title_1[0] = '<span class="first-word">' . esc_html( $title_1[0] ) . '</span>';
                                $title_1 = implode( ' ', $title_1 );
                            ?>
                            <?php echo $title_1; ?>
                        </h3>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ( !empty( $title_2 ) ) : ?>
                        <h3 class="noo-theme-title" style="color:<?php echo esc_attr( $color ); ?>">
                            <?php echo $title_2; ?>
                        </h3>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if( isset($description) && !empty($description) ): echo '<p class="desc">'.esc_html($description).'</p>'; endif; ?>
            <?php echo do_shortcode('[contact-form-7 id="'.esc_attr($custom_form).'"]'); ?>
            </div>
            <style type="text/css">

                .noo-contact-form-7.style-2 .wpcf7-form input:not([type='submit']),
                .noo-contact-form-7.style-2 .wpcf7-form select:not([multiple])
                {
                    border-bottom: 1px solid <?php  echo esc_attr($color)?>;
                }
                .noo-contact-form-7.style-2 .wpcf7-form input[type='submit']{
                    background-color: <?php echo esc_attr( $color ); ?>;
                }
                .noo-contact-form-7.style-2 .wpcf7-form input[type='submit']:hover{
                    background-color: <?php echo esc_attr( $color ); ?>;
                    opacity: 0.8;
                }
                .noo-contact-form-7.style-2 .wpcf7-form input[type='submit']{
                    color:<?php  echo esc_attr( $color_text ) ?>;
                }
            </style>
            <script>
                jQuery(document).ready(function(){
                    jQuery( ".hermosa-date .wpcf7-form-control" ).datetimepicker({
                        format:"m/d/Y",
                        timepicker: false,
                        datepicker: true,
                        scrollInput: false,
                    });
                });
            </script>
            <?php
            $form = ob_get_contents();
            ob_end_clean();
            return $form;



        }
        add_shortcode('noo_form','noo_shortcode_form');

    }
?>