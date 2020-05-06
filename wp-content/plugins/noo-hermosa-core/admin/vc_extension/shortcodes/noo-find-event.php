<?php
/**
 * Create shortcode: [noo_find_event]
 *
 * @package 	Noo_Hermosa_Core
 * @author 		KENT <tuanlv@vietbrain.com>
 * @version 	1.0
 */

if ( ! function_exists( 'shortcode_noo_find_event' ) ) :
	
	function shortcode_noo_find_event( $atts ) {

        if ( ! class_exists('Noo__Timetable__Event') ) return;

		extract( shortcode_atts( array(
            'show_date'     => 'yes',
            'show_search'   => 'yes',
            'show_category' => 'yes',
            'show_address' => 'yes'
        ), $atts ) );

        ob_start();

        /**
         * Enqueue library
         */
        wp_enqueue_script( 'datetimepicker' );
        wp_enqueue_style( 'datetimepicker' );

        ?>
        <div class="noo-find-event-wrap">
            
            <form method="get" class="find-event" action="<?php echo home_url( '/' ); ?>">

                <input type="hidden" name="post_type" value="noo_event" />
                

                <?php if ( $show_date === 'yes' ) : ?>
                    <span class="event-date">
                        <input type="text" name="date" id="find-date-event" placeholder="<?php echo esc_html__( 'Event Date', 'noo-hermosa-core' ); ?>" />
                        <i class="ion-calendar"></i>
                    </span>
                <?php endif; ?>

                <?php if ( $show_address === 'yes' ) : ?>
                    <span class="event-address">
                        <input type="text" name="address" placeholder="<?php echo esc_html__( 'Address...', 'noo-hermosa-core' ); ?>" />
                        <i class="ion-location"></i>
                    </span>
                <?php endif; ?>

                <?php if ( $show_search === 'yes' ) : ?>
                    <span class="event-search">
                        <input type="text" name="s" placeholder="<?php echo esc_html__( 'Search...', 'noo-hermosa-core' ); ?>" />
                        <i class="ion-ios-search"></i>
                    </span>
                <?php endif; ?>

                <?php 
                    if ( $show_category === 'yes' ) :
                        $event_category = get_terms( array(
                            'taxonomy' => 'event_category',
                            'hide_empty' => false,
                        ) );

                        echo '<select name="category" class="event-category">';
                        echo '<option value="">' . esc_html__( 'Category', 'noo-hermosa-core' ) . '</option>';
                        if ( ! empty( $event_category ) && ! is_wp_error( $event_category ) ) :
                            
                            foreach ( $event_category as $category ) :

                                echo '<option value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</option>';
                            
                            endforeach;

                        endif;
                        echo '</select>';

                    endif; 
                ?>

                <input type="submit" class="find" value="<?php echo esc_html__( 'Find Event', 'noo-hermosa-core' ); ?>" />
            </form>

            <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#find-date-event').each(function(index, el) {
                        
                        $(this).datetimepicker({
                            format:"m/d/Y",
                            timepicker: false,
                            datepicker: true,
                            scrollInput: false,
                            onChangeDateTime:function(dp,$input){
                                if ((typeof(dp) !== 'undefined') && (dp !== null)) {
                                    $input.next('input[type="hidden"]').val(parseInt(dp.getTime()/1000)-60*dp.getTimezoneOffset()); // correct the timezone of browser.
                                }
                            }
                        });

                    });
                });
            </script>

        </div><?php 
        $html = ob_get_contents();
        ob_end_clean();
        return $html;

	}

	add_shortcode( 'noo_find_event', 'shortcode_noo_find_event' );

endif;
