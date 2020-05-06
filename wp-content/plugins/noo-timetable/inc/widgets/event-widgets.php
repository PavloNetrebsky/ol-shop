<?php
/**
 * Widget for Event
 *
 * @author 		NooTheme
 * @category    Widgets
 * @package 	NooTimetable/Widgets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Create Widget: Noo Event Slider
 */

if ( !class_exists( 'Noo__Timetable_Widget_Event_Slider' ) ) :

class Noo__Timetable_Widget_Event_Slider extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname'   => 'noo_event_slider',
            'description' => esc_html__( 'Noo Timetable: Event Slider', 'noo-timetable' )
        );
        parent::__construct(
            'noo_event_slider',
            esc_html__( 'Noo Timetable: Event Slider', 'noo-timetable' ),
            $widget_ops
        );
        $this->alt_option_name = 'noo_event_slider';

    }

    public function widget($args, $instance) {

    	global $post;

    	/**
    	 * Enqueue library
    	 */
    	wp_enqueue_style( 'carousel' );
		wp_enqueue_style( 'carousel-theme' );
		wp_enqueue_script( 'carousel' );

    	/**
    	 * VAR
    	 * @var [type]
    	 */
			$prefix         = '_noo_event';
			$title          = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
			$number         = ( ! empty( $instance['number'] ) ) ? $instance['number'] : '10';
			$event_category = ( ! empty( $instance['event_category'] ) ) ? $instance['event_category'] : 'all';
			$orderby        = ( ! empty( $instance['orderby'] ) ) ? $instance['orderby'] : 'latest';

		// ----- Creat array
			$event_args = array(
				'posts_per_page'      => $number,
				'post_status'         => 'publish',
				'post_type'			  => 'noo_event',
			);

		/**
		 * Process choose event category
		 */
		if ( $event_category != 'all' ) :

			$args['tax_query'] = array('relation' => 'AND');
			if( !empty($event_category) ) :
				$event_args['tax_query'][] = array(
					'taxonomy'     => 'event_category',
					'field'        => 'slug',
					'terms'        => $event_category
				);
			endif;

		endif;

		/**
		 * Process choose orderby
		 * @var [type]
		 */
			if( $orderby == 'latest' ) :

				$event_args['orderby'] = 'modified';
				$event_args['order'] = 'DESC';

			elseif( $orderby == 'oldest' ) :

				$event_args['orderby'] = 'modified';
				$event_args['order'] = 'ASC';

			elseif( $orderby == 'alphabet' ) :

				$event_args['orderby'] = 'title';
				$event_args['order'] = 'ASC';

			elseif( $orderby == 'ralphabet' ) :

				$event_args['orderby'] = 'title';
				$event_args['order'] = 'DESC';

			elseif( $orderby == 'featured' ) :

				$event_args['orderby'] = 'meta_value_num';
				$event_args['meta_key'] = 'event_is_featured';
				$event_args['meta_value'] = '1';

			elseif( $orderby == 'random' ) :

				$event_args['orderby'] = 'rand';

			endif;

		/**
		 * Create new query
		 * @var WP_Query
		 */
			$wp_query = new WP_Query( $event_args );

		/**
		 * Process
		 */
	        echo $args['before_widget'];

	        if ( $title ) :
				echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
			endif;

			echo '<div class="noo-event-slider-wrap owl-carousel owl-theme">';

			while ( $wp_query->have_posts() ) : $wp_query->the_post();
				$address_author = get_post_meta( get_the_ID(), $prefix . "_address", true );
				$image_url      = wp_get_attachment_url( get_post_thumbnail_id() ); ?>

				<div class="noo-event-slider-item">

					<div class="item-thumb">
					    	<a href="<?php the_permalink() ?>">
						    	<?php the_post_thumbnail() ?>
						    </a>
					    </div>

					<div class="noo-event-slider-body">
						<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_title(); ?>
						</a></h4>
						<?php if (!empty($address_author)) { ?>
							<span class="address">
								<i class="fa fa-map-marker"></i>
								<?php echo esc_html( $address_author ); ?>
							</span>
						<?php } ?>
					</div>

				</div>

	        <?php endwhile;
	        echo '</div><!-- /.noo-event-slider-wrap -->';
	        ?>

	        <script type="text/javascript">
            jQuery(document).ready(function(){

                jQuery('.noo-event-slider-wrap').each(function(){
                    jQuery(this).owlCarousel({
						items : 1,
                        dragEndSpeed : 600,
						dotsSpeed: 600,
						navSpeed: 600,
                        dots: true,
                        autoHeight: true
					});
                });
            });
            </script>

	        <?php
        	// -- Restore original Post Data
			wp_reset_postdata();
			echo $args['after_widget'];

    }

    public function update( $new_instance, $old_instance ) {

		$instance                   = $old_instance;
		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['event_category'] = strip_tags( $new_instance['event_category'] );
		$instance['number']         = strip_tags( $new_instance['number'] );
		$instance['orderby']        = strip_tags( $new_instance['orderby'] );

        return $instance;
    }

    public function form( $instance ) {
    	// -- Defaults
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title'          => '',
					'event_category' => 'all',
					'number'         => '10',
					'orderby'        => 'latest',
				)
			);
		// -- Get var
			$title          = esc_attr( $instance['title'] );
			$event_category = esc_attr( $instance['event_category'] );
			$number         = esc_attr( $instance['number'] );
			$orderby        = esc_attr( $instance['orderby'] );
		?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html_e( 'Title', 'noo-timetable' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'event_category' ); ?>"><?php esc_html_e( 'Event Category','noo-timetable' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'event_category' ); ?>" name="<?php echo $this->get_field_name( 'event_category' ); ?>" class="widefat">
				<option value="all"><?php esc_html_e( 'All Categories', 'noo-timetable' ); ?></option>
				<?php
					// === << get list category

					foreach ((array) get_terms( 'event_category', array('hide_empty'=>0)) as $category){
						?>
							<option value="<?php echo $category->slug ?>"<?php selected( $event_category,  $category->slug ); ?>><?php echo esc_html($category->name) ?></option>
						<?php
					}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts to show:','noo-timetable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo empty($number) ? 10 : $number; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_html_e( 'Orderby','noo-timetable' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat">

				<option value="latest"<?php selected( $orderby, 'latest' ); ?>><?php esc_html_e( 'Recent First', 'noo-timetable' ); ?></option>
				<option value="oldest"<?php selected( $orderby, 'oldest' ); ?>><?php esc_html_e( 'Older First', 'noo-timetable' ); ?></option>
				<option value="alphabet"<?php selected( $orderby, 'alphabet' ); ?>><?php esc_html_e( 'Title Alphabet', 'noo-timetable' ); ?></option>
				<option value="ralphabet"<?php selected( $orderby, 'ralphabet' ); ?>><?php esc_html_e( 'Title Reversed Alphabet', 'noo-timetable' ); ?></option>
				<option value="featured"<?php selected( $orderby, 'featured' ); ?>><?php esc_html_e( 'Featured', 'noo-timetable' ); ?></option>
				<option value="random"<?php selected( $orderby, 'random' ); ?>><?php esc_html_e( 'Random', 'noo-timetable' ); ?></option>

			</select>
		</p>

    <?php }
}

endif;

/**
 * Create Widget: Noo Event Filters
 */

if ( !class_exists( 'Noo__Timetable_Widget_Event_Filters' ) ) :

class Noo__Timetable_Widget_Event_Filters extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname'   => 'noo_event_filters',
            'description' => esc_html__( 'Noo Timetable: Event Info', 'noo-timetable' )
        );
        parent::__construct(
            'noo_event_filters',
            esc_html__( 'Noo Event Filters', 'noo-timetable' ),
            $widget_ops
        );
        $this->alt_option_name = 'noo_event_filters';

    }

    public function widget($args, $instance) {
    	if (
    		!is_post_type_archive( 'noo_event' ) &&
            !is_tax( 'event_category' ) &&
            !is_tax( 'event_location' )  ) :
    		return;
    	endif;

    	/**
         * Enqueue library
         */
	        wp_enqueue_script( 'datetimepicker' );
	        wp_enqueue_style( 'datetimepicker' );

    	/**
    	 * VAR
    	 * @var [type]
    	 */
    	global $wp_query;
		$title       = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

        echo $args['before_widget'];

        if ( $title ) :
			echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		endif;

		echo '<div class="noo-event-filter-wrap">';

		if ( !empty( $instance['on_search'] ) ) :
			$curent_search = ( !empty( $_GET['s'] ) ? $_GET['s'] : '' );
			echo '<div class="noo-event-filter-search">';
			echo '<input type="text" name="keyword" class="filter-keyword" placeholder="' . esc_html__( 'Search', 'noo-timetable' ) . '" value="' . esc_html( $curent_search ) . '"/>';
			echo '</div>';

		endif;

		if ( !empty( $instance['on_calendar'] ) ) :
			$curent_calendar = ( !empty( $_GET['date'] ) ? $_GET['date'] : '' );
			echo '<div class="noo-event-filter-calendar">';
			echo '<input type="text" name="calendar" class="filter-calendar" placeholder="' . esc_html__( 'Select calendar', 'noo-timetable' ) . '" value="' . esc_html( $curent_calendar ) . '"/>';
			echo '</div>';

		endif;

		if ( !empty( $instance['on_category'] ) ) :
			$current_tax = $wp_query->get_queried_object_id();

			if ( !empty( $current_tax ) ) :
				$term = get_term( $current_tax, 'event_category' );
			endif;

			$curent_cat  = ( !empty( $_GET['category'] ) ? $_GET['category'] : ( ( !empty( $current_tax ) && isset( $term->slug ) ) ? $term->slug : '' ) );
			echo '<select name="category" class="filter-category">';
			echo '<option value="none">' . esc_html__( 'Select Category', 'noo-timetable' ) . '</option>';
			$categories = get_terms( 'event_category', 'orderby=count&hide_empty=0' );
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) :

				foreach ($categories as $cat) :

					echo '<option value="' . esc_attr( $cat->term_id ) . '"' . selected( $curent_cat, $cat->slug ) . '>' . esc_html( $cat->name ) . '</option>';

				endforeach;

			endif;
			echo '</select>';
		endif;

		echo '</div><!-- /.noo-event-filter-wrap -->';

        echo $args['after_widget'];

    }

    public function update( $new_instance, $old_instance ) {

		$instance                = $old_instance;
		$instance['title']       = strip_tags( $new_instance['title'] );
		$instance['on_calendar'] = strip_tags( $new_instance['on_calendar'] );
		$instance['on_search']   = strip_tags( $new_instance['on_search'] );
		$instance['on_category'] = strip_tags( $new_instance['on_category'] );

        return $instance;
    }

    public function form( $instance ) {
		$title       = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$on_calendar = isset( $instance['on_calendar'] ) ? esc_attr( $instance['on_calendar'] ) : '';
		$on_search   = isset( $instance['on_search'] ) ? esc_attr( $instance['on_search'] ) : '';
		$on_category = isset( $instance['on_category'] ) ? esc_attr( $instance['on_category'] ) : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html_e( 'Title', 'noo-timetable' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'on_calendar' ); ?>" name="<?php echo $this->get_field_name( 'on_calendar' ); ?>" type="checkbox" value="1" <?php checked( $on_calendar, '1' ); ?> />
            <label for="<?php echo $this->get_field_id( 'on_calendar' ); ?>">
                <?php esc_html_e( 'Show box calendar', 'noo-timetable' ); ?>
            </label>
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'on_search' ); ?>" name="<?php echo $this->get_field_name( 'on_search' ); ?>" type="checkbox" value="1" <?php checked( $on_search, '1' ); ?> />
            <label for="<?php echo $this->get_field_id( 'on_search' ); ?>">
                <?php esc_html_e( 'Show box search', 'noo-timetable' ); ?>
            </label>
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'on_category' ); ?>" name="<?php echo $this->get_field_name( 'on_category' ); ?>" type="checkbox" value="1" <?php checked( $on_category, '1' ); ?> />
            <label for="<?php echo $this->get_field_id( 'on_category' ); ?>">
                <?php esc_html_e( 'Show box category', 'noo-timetable' ); ?>
            </label>
        </p>

    <?php }
}

endif;
