<?php
/**
 * This is file create and using all widget in plugin
 */


/**
 * Create Widget: Noo Event Info
 *
 * @package     Noo_Hermosa
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( !class_exists( 'Noo_Widget_Noo_Event_Info' ) ) :

class Noo_Widget_Noo_Event_Info extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname'   => 'noo_event_info', 
            'description' => __( 'Noo Event Info', 'noo-hermosa-core' )
        );
        parent::__construct( 
            'noo_event_info', 
            esc_html__( 'Noo Event Info', 'noo-hermosa-core' ), 
            $widget_ops
        );
        $this->alt_option_name = 'noo_event_info';

    }

    public function widget($args, $instance) {

    	if ( !is_singular( 'noo_event' ) ) :
    		return;
    	endif;
    	
    	global $post;

    	/**
    	 * VAR
    	 * @var [type]
    	 */
		$prefix        = '_noo_event';
		$title         = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		
		$start_date    = noo_hermosa_get_post_meta( get_the_ID(), "_noo_event_start_date", '' );
		$end_date      = noo_hermosa_get_post_meta( get_the_ID(), "_noo_event_end_date", '' );
		
		$start_time    = noo_hermosa_get_post_meta( get_the_ID(), "_noo_event_start_time", '' );
		$end_time      = noo_hermosa_get_post_meta( get_the_ID(), "_noo_event_end_time", '' );
		
		$register_link = noo_hermosa_get_post_meta( get_the_ID(), "_noo_event_register_link", '' );

        echo $args['before_widget'];
        
        if ( $title ) :
			echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
		endif; ?>
		
		<div class="noo-event-info-wrap">
			
			<?php if ( !empty( $start_date ) ) : ?>
				<div class="noo-event-info-item">
					<label><?php echo esc_html__( 'Start:', 'noo-hermosa-core' ); ?></label>
					<span><?php echo date_i18n( get_option('date_format'), $start_date ); ?> <?php if ( !empty( $start_time ) ) : echo date_i18n( get_option('time_format'), $start_time ); endif; ?></span>
				</div>
			<?php endif; ?>

			<?php if ( !empty( $end_date ) ) : ?>
				<div class="noo-event-info-item">
					<label><?php echo esc_html__( 'End:', 'noo-hermosa-core' ); ?></label>
					<span><?php echo date_i18n( get_option('date_format'), $end_date ); ?> <?php if ( !empty( $end_time ) ) : echo date_i18n( get_option('time_format'), $end_time ); endif; ?></span>
				</div>
			<?php endif; ?>

			<?php if ( !empty( $end_date ) ) : ?>
				<div class="noo-event-info-item">
					<label><?php echo esc_html__( 'Event Categories:', 'noo-hermosa-core' ); ?></label>
					<span><?php echo get_the_term_list( $post->ID, 'event_category', '',', ' );?></span>
				</div>
			<?php endif; ?>

			<div class="noo-event-info-item">
				<?php Noo__Timetable__Event::show_repeat_info(); ?>
			</div>



			<?php if ( !empty( $register_link ) ) : ?>
				<div class="noo-event-info-item">
					<a href="<?php echo esc_url( $register_link );?>" class="btn register_button"><?php echo esc_html__('Register Now', 'noo-hermosa-core');?></a>
				</div>
			<?php endif; ?>

		</div><!-- /.noo-event-info-wrap -->

        <?php echo $args['after_widget'];

    }

    public function update( $new_instance, $old_instance ) {
		
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html_e( 'Title', 'noo-hermosa-core' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />
        </p>

    <?php }
}

endif;

/**
 * Create Widget: Noo Event Box Author
 *
 * @package     Noo_Hermosa
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( !class_exists( 'Noo_Widget_Noo_Event_Box_Author' ) ) :

class Noo_Widget_Noo_Event_Box_Author extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname'   => 'noo_event_box_author', 
            'description' => __( 'Noo Event Box Author', 'noo-hermosa-core' )
        );
        parent::__construct( 
            'noo_event_box_author', 
            esc_html__( 'Noo Event Box Author', 'noo-hermosa-core' ), 
            $widget_ops
        );
        $this->alt_option_name = 'noo_event_box_author';

    }

    public function widget($args, $instance) {

    	if ( !is_singular( 'noo_event' ) ) :
    		return;
    	endif;
    	
    	global $post;

    	/**
    	 * VAR
    	 * @var [type]
    	 */
		$prefix          = '_noo_event';
		$title           = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$organizers_ids   = get_post_meta( $post->ID, $prefix . "_organizers", true );
		if (!empty($organizers_ids)) :
		foreach ($organizers_ids as $organizers_id ):
			$name_author     = get_post_meta( $organizers_id, $prefix . "_author", true );
			$avatar_author   = get_post_meta( $organizers_id, $prefix . "_avatar", true );
			$phone_author    = get_post_meta( $organizers_id, $prefix . "_phone", true );
			$website_author  = get_post_meta( $organizers_id, $prefix . "_website", true );
			$email_author    = get_post_meta( $organizers_id, $prefix . "_email", true );
			$address_author  = get_post_meta( $organizers_id, $prefix . "_address", true );
			$position_author = get_post_meta( $organizers_id, $prefix . "_position", true );

	        echo $args['before_widget'];
	        
	        if ( $title ) :
				echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
			endif; ?>
			
			<div class="noo-event-box-author-wrap">
				
				<div class="noo-box-author-head">
					
					<?php if ( !empty( $avatar_author ) ) : ?>
						<div class="noo-thumbnail-author">
							<?php echo wp_get_attachment_image( $avatar_author, 'thumbnail' ); ?>
						</div>
					<?php endif; ?>
					
					<?php if ( !empty( $name_author ) ) : ?>
						<h3 class="noo-name-author">
							<?php echo esc_html( $name_author ); ?>
						</h3>	
					<?php endif; ?>

					<?php if ( !empty( $position_author ) ) : ?>
						<p class="noo-position-author">
							<?php echo esc_html( $position_author ); ?>
						</p>	
					<?php endif; ?>

				</div>

				<div class="noo-box-author-body">
					
					<?php if ( !empty( $phone_author ) ) : ?>
						<div class="noo-box-author-item">
							<i class="ion-ios-telephone"></i>
							<span class="phone">
								<a href="callto://<?php echo esc_html( $phone_author ); ?>" title="<?php echo esc_html__( 'Call Phone', 'noo-hermosa-core' ) ?>">
									<?php echo esc_html( $phone_author ); ?>
								</a>
							</span>
						</div>
					<?php endif; ?>

					<?php if ( !empty( $email_author ) ) : ?>
						<div class="noo-box-author-item">
							<i class="ion-ios-paperplane"></i>
							<span class="email">
								<a href="mailto:<?php echo esc_html( $email_author ); ?>" title="<?php echo esc_html__( 'Mail To', 'noo-hermosa-core' ) ?>">
									<?php echo esc_html( $email_author ); ?>
								</a>
							</span>
						</div>
					<?php endif; ?>

					<?php if ( !empty( $website_author ) ) : ?>
						<div class="noo-box-author-item">
							<i class="ion-earth"></i>
							<span class="web">
								<a href="<?php echo esc_html( $website_author ); ?>" title="<?php echo esc_html__( 'Visit website', 'noo-hermosa-core' ) ?>">
									<?php echo esc_html( $website_author ); ?>
								</a>
							</span>
						</div>
					<?php endif; ?>

				</div>

			</div><!-- /.noo-event-box-author-wrap -->
		<?php endforeach; ?>
	<?php endif; ?>
        <?php echo $args['after_widget'];

    }

    public function update( $new_instance, $old_instance ) {
		
		$instance          = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

        return $instance;
    }

    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html_e( 'Title', 'noo-hermosa-core' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />
        </p>

    <?php }
}

endif;

/**
 * Create Widget: Noo Event Box Map
 *
 * @package     Noo_Hermosa
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( !class_exists( 'Noo_Widget_Noo_Event_Box_Map' ) ) :

class Noo_Widget_Noo_Event_Box_Map extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname'   => 'noo_event_box_map', 
            'description' => __( 'Noo Event Box Map', 'noo-hermosa-core' )
        );
        parent::__construct( 
            'noo_event_box_map', 
            esc_html__( 'Noo Event Box Map', 'noo-hermosa-core' ), 
            $widget_ops
        );
        $this->alt_option_name = 'noo_event_box_author';

    }

    public function widget($args, $instance) {

    	if ( !is_singular( 'noo_event' ) ) :
    		return;
    	endif;
    	
    	global $post;

    	/**
    	 * VAR
    	 * @var [type]
    	 */
		$prefix = '_noo_event';
		$lat     = get_post_meta( $post->ID, $prefix . "_gmap_latitude", true );
		$lng     = get_post_meta( $post->ID, $prefix . "_gmap_longitude", true );
		$address = get_post_meta( $post->ID, $prefix . "_address", true );

		/**
    	 * Required library
    	 */
    	wp_enqueue_script( 'noo-maps' );

        echo $args['before_widget'];
        ?>
		<?php

	        $google_map_api_key = NOO_Settings()->get_option( 'noo_google_map_api_key', '' );
            $google_map_latitude = NOO_Settings()->get_option( 'noo_google_map_latitude', '51.508742' );
            $google_map_longitude = NOO_Settings()->get_option( 'noo_google_map_longitude', '-0.120850' );
            $google_zoom = NOO_Settings()->get_option( 'noo_google_map_zoom', '11' );

            $latitude = ( $lat != '' ) ? $lat : $google_map_latitude;
            $longitude = ( $lng != '' ) ? $lng : $google_map_longitude;
	    ?>
	    

	    <?php if (!empty($google_map_api_key)): ?>
	        <div class="noo-maps" data-lat="<?php echo esc_attr( $latitude ); ?>" data-lng="<?php echo esc_attr( $longitude ); ?>" data-zoom="<?php echo esc_attr( $google_zoom ); ?>"></div>
	    <?php else: ?>
	        <iframe width="100%" height="483px" frameborder="0" scrolling="no" marginheight="0"
	                marginwidth="0"
	                src="https://maps.google.com/maps?q=<?php echo $latitude; ?>,<?php echo $longitude; ?>&hl=es;z=14&amp;output=embed"></iframe>
	    <?php endif; ?>

		<address><i class="ion-ios-location"></i> <?php echo esc_html( $address ); ?></address>

        <?php echo $args['after_widget'];

    }

    public function update( $new_instance, $old_instance ) {
        return $instance;
    }

    public function form( $instance ) {
    	echo '<p></p>';
    }
}

endif;

/**
 * Create Widget: Noo Event Slider
 *
 * @package     Noo_Hermosa
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( !class_exists( 'Noo_Widget_Noo_Event_Slider' ) ) :

class Noo_Widget_Noo_Event_Slider extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname'   => 'noo_event_slider', 
            'description' => __( 'Noo Event Slider', 'noo-hermosa-core' )
        );
        parent::__construct( 
            'noo_event_slider', 
            esc_html__( 'Noo Event Slider', 'noo-hermosa-core' ), 
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
		wp_enqueue_script( 'carousel' );
		wp_enqueue_script( 'noo_event' );

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

			echo '<div class="noo-event-slider-wrap owl-carousel">';

			while ( $wp_query->have_posts() ) : $wp_query->the_post(); 
				$address_author = get_post_meta( get_the_ID(), $prefix . "_address", true );
				$image_url      = wp_get_attachment_url( get_post_thumbnail_id() ); ?>
			
				<div class="noo-event-slider-item">

					<div class="item-thumb" style="background-image:url('<?php echo esc_url( $image_url ); ?>');"></div>

					<div class="noo-event-slider-body">
						<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_title(); ?>
						</a></h4>
						<span class="address">
							<i class="ion-ios-location"></i>
							<?php echo esc_html( $address_author ); ?>
						</span>
					</div>

				</div>

	        <?php endwhile;
	        echo '</div><!-- /.noo-event-slider-wrap -->';
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
                <?php esc_html_e( 'Title', 'noo-hermosa-core' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'event_category' ); ?>"><?php esc_html_e( 'Event Category','noo-hermosa-core' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'event_category' ); ?>" name="<?php echo $this->get_field_name( 'event_category' ); ?>" class="widefat">
				<option value="all"><?php esc_html_e( 'All Category', 'noo-hermosa-core' ); ?></option>
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
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php esc_html_e( 'Number of posts to show:','noo-hermosa-core' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="number" value="<?php echo empty($number) ? 10 : $number; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php esc_html_e( 'Orderby','noo-hermosa-core' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat">
				
				<option value="latest"<?php selected( $orderby, 'latest' ); ?>><?php esc_html_e( 'Recent First', 'noo-hermosa-core' ); ?></option>
				<option value="oldest"<?php selected( $orderby, 'oldest' ); ?>><?php esc_html_e( 'Older First', 'noo-hermosa-core' ); ?></option>
				<option value="alphabet"<?php selected( $orderby, 'alphabet' ); ?>><?php esc_html_e( 'Title Alphabet', 'noo-hermosa-core' ); ?></option>
				<option value="ralphabet"<?php selected( $orderby, 'ralphabet' ); ?>><?php esc_html_e( 'Title Reversed Alphabet', 'noo-hermosa-core' ); ?></option>
				<option value="featured"<?php selected( $orderby, 'featured' ); ?>><?php esc_html_e( 'Featured', 'noo-hermosa-core' ); ?></option>
				<option value="random"<?php selected( $orderby, 'random' ); ?>><?php esc_html_e( 'Random', 'noo-hermosa-core' ); ?></option>
				
			</select>
		</p>

    <?php }
}

endif;

/**
 * Create Widget: Noo Event Filters
 *
 * @package     Noo_Hermosa
 * @author      KENT <tuanlv@vietbrain.com>
 * @version     1.0
 */

if ( !class_exists( 'Noo_Widget_Noo_Event_Filters' ) ) :

class Noo_Widget_Noo_Event_Filters extends WP_Widget {

    public function __construct() {

        $widget_ops = array(
            'classname'   => 'noo_event_filters', 
            'description' => __( 'Noo Event Info', 'noo-hermosa-core' )
        );
        parent::__construct( 
            'noo_event_filters', 
            esc_html__( 'Noo Event Filters', 'noo-hermosa-core' ), 
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
			echo '<input type="text" name="keyword" class="filter-keyword" placeholder="' . esc_html__( 'Search', 'noo-hermosa-core' ) . '" value="' . esc_html( $curent_search ) . '"/>';
			echo '</div>';

		endif;

		if ( !empty( $instance['on_address'] ) ) :
			$curent_address = ( !empty( $_GET['address'] ) ? $_GET['address'] : '' );
			echo '<div class="noo-event-filter-address">';
			echo '<input type="text" name="address" class="filter-address" placeholder="' . esc_html__( 'Address', 'noo-hermosa-core' ) . '" value="' . esc_html( $curent_address ) . '"/>';
			echo '</div>';

		endif;

		if ( !empty( $instance['on_calendar'] ) ) :
			$curent_calendar = ( !empty( $_GET['date'] ) ? $_GET['date'] : '' );
			echo '<div class="noo-event-filter-calendar">';
			echo '<input type="text" name="calendar" class="filter-calendar" placeholder="' . esc_html__( 'Select calendar', 'noo-hermosa-core' ) . '" value="' . esc_html( $curent_calendar ) . '"/>';
			echo '</div>';

		endif;

		if ( !empty( $instance['on_category'] ) ) :
			$current_tax = $wp_query->get_queried_object_id();

			if ( !empty( $current_tax ) ) :
				$term = get_term( $current_tax, 'event_category' );
			endif;

			$curent_cat  = ( !empty( $_GET['category'] ) ? $_GET['category'] : ( ( !empty( $current_tax ) && isset( $term->slug ) ) ? $term->slug : '' ) );
			echo '<select name="category" class="filter-category">';
			echo '<option value="none">' . esc_html__( 'Select Category', 'noo-hermosa-core' ) . '</option>';
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
		$instance['on_address'] = strip_tags( $new_instance['on_address'] );

        return $instance;
    }

    public function form( $instance ) {
		$title       = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$on_calendar = isset( $instance['on_calendar'] ) ? esc_attr( $instance['on_calendar'] ) : '';
		$on_search   = isset( $instance['on_search'] ) ? esc_attr( $instance['on_search'] ) : '';
		$on_category = isset( $instance['on_category'] ) ? esc_attr( $instance['on_category'] ) : '';
		$on_address = isset( $instance['on_address'] ) ? esc_attr( $instance['on_address'] ) : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>">
                <?php esc_html_e( 'Title', 'noo-hermosa-core' ); ?>
            </label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_html( $title ); ?>" />
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'on_calendar' ); ?>" name="<?php echo $this->get_field_name( 'on_calendar' ); ?>" type="checkbox" value="1" <?php checked( $on_calendar, '1' ); ?> />
            <label for="<?php echo $this->get_field_id( 'on_calendar' ); ?>">
                <?php esc_html_e( 'Show box calendar', 'noo-hermosa-core' ); ?>
            </label>
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'on_search' ); ?>" name="<?php echo $this->get_field_name( 'on_search' ); ?>" type="checkbox" value="1" <?php checked( $on_search, '1' ); ?> />
            <label for="<?php echo $this->get_field_id( 'on_search' ); ?>">
                <?php esc_html_e( 'Show box search', 'noo-hermosa-core' ); ?>
            </label>
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'on_category' ); ?>" name="<?php echo $this->get_field_name( 'on_category' ); ?>" type="checkbox" value="1" <?php checked( $on_category, '1' ); ?> />
            <label for="<?php echo $this->get_field_id( 'on_category' ); ?>">
                <?php esc_html_e( 'Show box category', 'noo-hermosa-core' ); ?>
            </label>
        </p>

        <p>
            <input class="widefat" id="<?php echo $this->get_field_id( 'on_address' ); ?>" name="<?php echo $this->get_field_name( 'on_address' ); ?>" type="checkbox" value="1" <?php checked( $on_address, '1' ); ?> />
            <label for="<?php echo $this->get_field_id( 'on_address' ); ?>">
                <?php esc_html_e( 'Show box address', 'noo-hermosa-core' ); ?>
            </label>
        </p>

    <?php }
}

endif;

if( !class_exists('Noo_Hermosa_Infomation') ):
    class Noo_Hermosa_Infomation extends  WP_Widget{

        public function __construct(){
            parent::__construct(
                'noo_infomation',
                'Noo Infomation',
                array('description', esc_html__('Noo Infomation', 'noo-hermosa'))
            );
            add_action('admin_enqueue_scripts', array($this, 'register_js'));
        }
        public function register_js(){

            wp_enqueue_media();
            wp_register_script('upload_img', get_template_directory_uri() . '/includes/admin_assets/js/upload_img.js', false, false, $in_footer=true);
            wp_enqueue_script('upload_img');

        }
        public function widget($args, $instance){
            extract( $args );
            extract( $instance );
            echo wp_kses($before_widget,noo_hermosa_allowed_html());
            $arg_social = array(
                array('id'       =>  'facebook'),
                array('id'       =>  'google'),
                array('id'       =>  'twitter'),
                array('id'       =>  'youtube'),
                array('id'       =>  'skype'),
                array('id'       =>  'linkedin'),
                array('id'       =>  'dribbble'),
                array('id'       =>  'pinterest'),
                array('id'       =>  'flickr'),
                array('id'       =>  'instagram'),

            ) ;
            // Get menu
            $nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
            ?>
            <div class="noo-info-top">
                <?php if( isset($image) && !empty($image) ): ?>
                    <a href="<?php echo esc_url($link); ?>">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php bloginfo('title'); ?>">
                    </a>
                <?php endif; ?>
            </div>
            <?php
            	if ( !empty( $nav_menu ) ) {

	                $menu = wp_get_nav_menu_object( $nav_menu );

	                $menu_items = wp_get_nav_menu_items($menu->term_id);

	                ?>
	                    <div class="noo-custom-menu">
	                        <?php
	                        foreach ( (array) $menu_items as $key => $menu_item ) :
                                $title = $menu_item->title;
                                $url   = $menu_item->url;
                                echo '<a href="' . esc_url( $url ) . '" title="' . esc_html( $title ) . '">' . esc_html( $title ) . '</a>';
	                        endforeach;
	                        ?>
	                    </div>
	                <?php
	            }
            ?>
            <div class="social-all">
                <?php
                foreach($arg_social as $social):
                    if (!empty($instance[$social['id']])):
                        ?>
                        <a href="<?php echo esc_url($instance[$social['id']]); ?>" class="fa fa-<?php echo esc_attr($social['id']); ?>"></a>
                    <?php
                    endif;
                endforeach;
                ?>
            </div>


            <?php
            echo wp_kses($after_widget,noo_hermosa_allowed_html());
        }

        public function form( $instance ){
            $instance = wp_parse_args( $instance, array(
                'link'            =>  '',
                'image'           =>  '',
                'facebook'        =>  '',
                'nav_menu'        =>  '',
                'google'          =>  '',
                'twitter'         =>  '',
                'youtube'         =>  '',
                'skype'           =>  '',
                'linkedin'        =>  '',
                'dribbble'        =>  '',
                'pinterest'       =>  '',
                'flickr'          =>  '',
                'instagram'       =>  ''
            ) );
            extract($instance);
            ?>
            <p>
                <label for="<?php echo wp_kses($this->get_field_id('link'),noo_hermosa_allowed_html()); ?>"><?php esc_html_e('Link Image:', 'noo-hermosa'); ?></label>
                <input type="text" id="<?php echo wp_kses($this->get_field_id('link'),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name('link'),noo_hermosa_allowed_html()) ?>" class="widefat" value="<?php echo esc_attr($link); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this->get_field_id('image'),noo_hermosa_allowed_html()); ?>"><?php esc_html_e('Image', 'noo-hermosa') ; ?></label>
                <input class="widefat" type="text" name="<?php echo wp_kses($this->get_field_name('image'),noo_hermosa_allowed_html()); ?>" id="<?php echo wp_kses($this->get_field_id('image'),noo_hermosa_allowed_html()) ; ?>" value="<?php echo esc_attr($image); ?>">
                <a href="#" class="noo_upload_button button" rel="image"><?php esc_html_e('Upload', 'noo-hermosa') ?></a>
            </p>
            <p>
            	<?php 
            	// Get menus
		            $menus = wp_get_nav_menus();

		            // If no menus exists, direct the user to go and create some.
		            if ( !$menus ) {
		                echo sprintf( esc_html__( 'No menus have been created yet. <a href="%s">Create some</a>.', 'noo-hermosa' ), esc_url(admin_url('nav-menus.php')) );
		            }
	            ?>
                <label for="<?php echo wp_kses($this->get_field_id('nav_menu'),noo_hermosa_allowed_html()); ?>">
                    <?php esc_html_e( 'Select Menu:', 'noo-hermosa' ); ?>
                </label>
                <select id="<?php echo wp_kses($this->get_field_id('nav_menu'),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name('nav_menu'),noo_hermosa_allowed_html()); ?>">
                    <option value="0"><?php esc_html_e( '&mdash; Select &mdash;', 'noo-hermosa' ) ?></option>
                    <?php
                    foreach ( $menus as $menu ) {
                        echo '<option value="' . ($menu->term_id) . '"'
                            . selected( $nav_menu, $menu->term_id, false )
                            . '>' . esc_html( $menu->name ) . '</option>';
                    }
                    ?>
                </select>
            </p>      
            <p>
                <label for="<?php echo wp_kses($this -> get_field_id('facebook'),noo_hermosa_allowed_html()) ?>" >
                    <?php esc_html_e('Facebook','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('facebook'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('facebook'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($facebook); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this->get_field_id('google-plus'),noo_hermosa_allowed_html()) ?>">
                    <?php esc_html_e('Google','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('google'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('google'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($google); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this -> get_field_id('twitter'),noo_hermosa_allowed_html()) ?>">
                    <?php esc_html_e('Twitter','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('twitter'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('twitter'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($twitter); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this -> get_field_id('youtube'),noo_hermosa_allowed_html()) ?>">
                    <?php esc_html_e('Youtube','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('youtube'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('youtube'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($youtube); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this -> get_field_id('skype'),noo_hermosa_allowed_html()); ?>">
                    <?php  esc_html_e('Skype','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('skype'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('skype'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($skype); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this->get_field_id('linkedin'),noo_hermosa_allowed_html()) ?>">
                    <?php esc_html_e('linkedin','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('linkedin'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('linkedin'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($linkedin); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this->get_field_id('dribbble'),noo_hermosa_allowed_html()) ?>">
                    <?php esc_html_e('Dribbble','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('dribbble'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('dribbble'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($dribbble); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this->get_field_id('pinterest'),noo_hermosa_allowed_html()) ?>">
                    <?php esc_html_e('Pinterest','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('pinterest'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('pinterest'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($pinterest); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this->get_field_id('flickr'),noo_hermosa_allowed_html()) ?>">
                    <?php esc_html_e('Flickr','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('flickr'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('flickr'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($flickr); ?>">
            </p>
            <p>
                <label for="<?php echo wp_kses($this->get_field_id('instagram'),noo_hermosa_allowed_html()) ?>">
                    <?php esc_html_e('Instagram','noo-hermosa') ; ?>
                </label>
                <br>
                <input type="text" name="<?php echo wp_kses($this->get_field_name('instagram'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this->get_field_id('instagram'),noo_hermosa_allowed_html()); ?>" class="widefat" value="<?php echo esc_attr($instagram); ?>">
            </p>

        <?php
        }
        // method update
        public function update( $new_instance, $old_instance ){
            $instance                 =   $old_instance;
            $instance['link']         =   $new_instance['link'];
            $instance['image']        =   $new_instance['image'];            
            $instance['nav_menu']     =   $new_instance['nav_menu'];            
            $instance['facebook']     =   ( ! empty( $new_instance['facebook'] ) ) ? strip_tags( $new_instance['facebook'] ) : ''  ;
            $instance['google']       =   ( ! empty( $new_instance['google'] ) ) ? strip_tags( $new_instance['google'] ) : ''  ;
            $instance['twitter']      =   ( ! empty( $new_instance['twitter'] ) ) ? strip_tags( $new_instance['twitter'] ) : ''  ;
            $instance['youtube']      =   ( ! empty( $new_instance['youtube'] ) ) ? strip_tags( $new_instance['youtube'] ) : ''  ;
            $instance['skype']        =   ( ! empty( $new_instance['skype'] ) ) ? strip_tags( $new_instance['skype'] ) : ''  ;
            $instance['linkedin']     =   ( ! empty( $new_instance['linkedin'] ) ) ? strip_tags( $new_instance['linkedin'] ) : ''  ;
            $instance['dribbble']     =   ( ! empty( $new_instance['dribbble'] ) ) ? strip_tags( $new_instance['dribbble'] ) : ''  ;
            $instance['pinterest']    =   ( ! empty( $new_instance['pinterest'] ) ) ? strip_tags( $new_instance['pinterest'] ) : ''  ;
            $instance['flickr']       =   ( ! empty( $new_instance['flickr'] ) ) ? strip_tags( $new_instance['flickr'] ) : ''  ;
            $instance['instagram']       =   ( ! empty( $new_instance['instagram'] ) ) ? strip_tags( $new_instance['instagram'] ) : ''  ;
            return $instance;
        }
    }
    
endif;

if(!class_exists('Noo_Hermosa_Latest_Ratting')):
	class Noo_Hermosa_Latest_Ratting extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_latest_ratting', 'description' => __( "Your site&#8217;s most recent Posts.",'noo-hermosa') );
		parent::__construct('latest-rating', esc_html__( 'Latest Ratting','noo-hermosa'), $widget_ops);
		$this->alt_option_name = 'widget_latest_ratting';
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_latest_ratting', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo wp_kses($cache[ $args['widget_id'] ],noo_hermosa_allowed_html());
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Latest Rating','noo-hermosa' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_latest_rating_args', array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'meta_key'           =>  'noo_date_rating',
            'orderby'            =>  'meta_value',
            'order'              =>  'DESC',
		) ) );
		wp_enqueue_script('imagesloaded');
		wp_enqueue_script('noo-carousel');
		
		$posts_in_column = 1;
		$columns = 1;
		$noo_post_uid  		= uniqid('noo_post_');
		$class = '';
		$class .= ' '.$noo_post_uid;
		$class = ( $class != '' ) ? ' class="' . esc_attr( $class ) . '"' : '';
		if ($r->have_posts()) :
		?>
		<?php echo wp_kses($args['before_widget'],noo_hermosa_allowed_html()); ?>
		<?php if ( $title ) {
			echo wp_kses($args['before_title'] . $title . $args['after_title'],noo_hermosa_allowed_html());
		} ?>
		<div <?php echo wp_kses($class,noo_hermosa_allowed_html())?>>

			<div class="row">
				<div class="widget-latest_ratting-content ">
					
					<?php $i=0; ?>
					<?php while ($r->have_posts()): $r->the_post(); global $post;
                    ?>
					
						<?php if($i++ % $posts_in_column == 0 ): ?>
						<div class="noo-latest_ratting-item col-sm-<?php echo absint((12 / $columns)) ?>">
						<?php endif; ?>
							<div class="noo-latest_ratting-inner">
								<div class="latest_ratting-featured" >
									<?php the_post_thumbnail('noo-thumbnail-square')?>
									<?php echo NooPost::get_category_label( 'noo-tncat' ); ?>
									<span class="noo_rating_point"><?php echo esc_html(noo_hermosa_get_post_meta(get_the_ID(),'noo_total_point_rating',0)) ?></span>
							    </div>
								<div class="post-slider-content">	
									<h4 class="post-slider-title">
										<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permanent link to: "%s"','noo-hermosa' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
									</h4>
									<div class="noo-ratting-meta">
				                        <span class="noo-post-date"><i class="fa fa-calendar"></i><?php echo get_the_date(); ?></span>
				                        <span class="noo-post-comment"><i class="fa fa-comments-o"></i><?php comments_number('0',1,'%') ?></span>
				                    </div>
				                    <?php
				                    $excerpt = get_the_excerpt();
				                    $excerpt_ex = explode(' ', $excerpt);
				                    $excerpt_slice = array_slice($excerpt_ex,0,15);
				                    $excerpt_content = implode(' ',$excerpt_slice);
				                    ?>
				                    <p><?php echo esc_html($excerpt_content); ?></p>
								</div>
							</div>
						<?php if($i % $posts_in_column == 0  || $i == $r->post_count): ?>
						</div>
						<?php endif;?>
					
					<?php endwhile;?>
				</div>
			</div>
			<div class="noo-post-navi">
				<div class="noo_slider_prev"><i class="fa fa-caret-left"></i></div>
				<div class="noo_slider_next"><i class="fa fa-caret-right"></i></div>
			</div>
		</div>
			<script type="text/javascript">
				jQuery('document').ready(function ($) {
					var postSliderOptions = {
					    infinite: true,
					    circular: true,
					    responsive: true,
					    debug : false,
						width: '100%',
					    height: 'variable',
					    scroll: {
					      items: <?php echo esc_attr($columns);?>,
					      duration: 600,
					      pauseOnHover: "resume",
					      fx: "scroll"
					    },
					    auto: {
					      timeoutDuration: 3000,
					      play: false
					    },

					    prev : {button:".<?php echo esc_attr($noo_post_uid) ?> .noo_slider_prev"},
    					next : {button:".<?php echo esc_attr($noo_post_uid) ?> .noo_slider_next"},
					    swipe: {
					      onTouch: true,
					      onMouse: true
					    },
					    items: {
					        visible: {
						      min: 1,
						      max: <?php echo esc_attr($columns);?>
						    },
						    height:'variable'
						}
					};
					jQuery('.<?php echo  esc_attr($noo_post_uid) ?> .widget-latest_ratting-content').carouFredSel(postSliderOptions);
					imagesLoaded('<?php echo esc_attr($noo_post_uid) ?> .widget-latest_ratting-content',function(){
						jQuery('.<?php echo esc_attr($noo_post_uid) ?> .widget-latest_ratting-content').trigger('updateSizes');
					});
					jQuery(window).resize(function(){
						jQuery('.<?php echo esc_attr($noo_post_uid) ?> .widget-latest_ratting-content').trigger("destroy").carouFredSel(postSliderOptions);
					});
				});
			</script>
		<?php echo wp_kses($args['after_widget'],noo_hermosa_allowed_html()); ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_latest_ratting', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['widget_recent_entries']) )
			delete_option('widget_recent_entries');

		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo wp_kses($this->get_field_id( 'title' ),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Title:','noo-hermosa' ); ?></label>
		<input class="widefat" id="<?php echo wp_kses($this->get_field_id( 'title' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'title' ),noo_hermosa_allowed_html()); ?>" type="text" value="<?php echo wp_kses($title,noo_hermosa_allowed_html()); ?>" /></p>

		<p><label for="<?php echo wp_kses($this->get_field_id( 'number' ),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Number of posts to show:','noo-hermosa' ); ?></label>
		<input id="<?php echo wp_kses($this->get_field_id( 'number' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'number' ),noo_hermosa_allowed_html()); ?>" type="text" value="<?php echo wp_kses($number,noo_hermosa_allowed_html()); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo wp($this->get_field_id( 'show_date' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'show_date' ),noo_hermosa_allowed_html()); ?>" />
		<label for="<?php echo wp_kses($this->get_field_id( 'show_date' ),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Display post date?','noo-hermosa' ); ?></label></p>
<?php
	}
}
endif;

if(!class_exists('Noo_Hermosa_Post_Slider')):

	class Noo_Hermosa_Post_Slider extends WP_Widget {

	public function __construct() {
		$widget_ops = array('classname' => 'widget_post_slider', 'description' => __( "Your site&#8217;s most recent Posts.",'noo-hermosa') );
		parent::__construct('post-slider', esc_html__( 'Post Slider','noo-hermosa'), $widget_ops);
		$this->alt_option_name = 'widget_post_slider';
	}

	public function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'widget_post_slider', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo wp_kses($cache[ $args['widget_id'] ],noo_hermosa_allowed_html());
			return;
		}

		ob_start();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Post Slider','noo-hermosa' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 5;
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		
		$ar =  array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true
		);
		$ar['tax_query'][]= array(
			'taxonomy' => 'post_format',
			'field'    => 'slug',
			'terms'    => 'post-format-gallery'
		);
		$r = new WP_Query( $ar );
		wp_enqueue_script('imagesloaded');
		wp_enqueue_script('noo-carousel');
		$posts_in_column = 1;
		$columns = 1;
		$noo_post_uid  		= uniqid('noo_post_');
		$class = '';
		$class .= ' '.$noo_post_uid;
		$class = ( $class != '' ) ? ' class="' . esc_attr( $class ) . '"' : '';
		if ($r->have_posts()) :
		?>
		<?php echo wp_kses($args['before_widget'],noo_hermosa_allowed_html()); ?>
		<?php if ( $title ) {
			echo wp_kses($args['before_title'] . $title . $args['after_title'],noo_hermosa_allowed_html());
		} ?>
		<div <?php echo wp_kses($class,noo_hermosa_allowed_html())?>>

			<div class="row">
				<div class="widget-post-slider-content gallery">
					
					<?php $i=0; ?>
					<?php while ($r->have_posts()): $r->the_post(); global $post;
                    ?>
					
						<?php if($i++ % $posts_in_column == 0 ): ?>
						<div class="noo-post-slider-item col-sm-<?php echo absint((12 / $columns)) ?>">
						<?php endif; ?>
							<div class="noo-post-slider-inner">
								<div class="post-slider-featured" >
									<?php the_post_thumbnail('noo-thumbnail-square')?>
							    </div>
								<div class="post-slider-content">	
									<h5 class="post-slider-title">
										<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( esc_html__( 'Permanent link to: "%s"','noo-hermosa' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_title(); ?></a>
									</h5>
								</div>
							</div>
						<?php if($i % $posts_in_column == 0  || $i == $r->post_count): ?>
						</div>
						<?php endif;?>
					
					<?php endwhile;?>
				</div>
			</div>
			<div class="noo-post-navi">
				<div class="noo_slider_prev"><i class="fa fa-caret-left"></i></div>
				<div class="noo_slider_next"><i class="fa fa-caret-right"></i></div>
			</div>
		</div>
			<script type="text/javascript">
				jQuery('document').ready(function ($) {
					var postSliderOptions = {
					    infinite: true,
					    circular: true,
					    responsive: true,
					    debug : false,
						width: '100%',
					    height: 'variable',
					    scroll: {
					      items: <?php echo esc_attr($columns);?>,
					      duration: 600,
					      pauseOnHover: "resume",
					      fx: "scroll"
					    },
					    auto: {
					      timeoutDuration: 3000,
					      play: false
					    },

					    prev : {button:".<?php echo esc_attr($noo_post_uid) ?> .noo_slider_prev"},
    					next : {button:".<?php echo esc_attr($noo_post_uid) ?> .noo_slider_next"},
					    swipe: {
					      onTouch: true,
					      onMouse: true
					    },
					    items: {
					        visible: {
						      min: 1,
						      max: <?php echo esc_attr($columns);?>
						    },
						    height:'variable'
						}
					};
					jQuery('.<?php echo esc_attr($noo_post_uid) ?> .widget-post-slider-content').carouFredSel(postSliderOptions);
					imagesLoaded('<?php echo esc_attr($noo_post_uid) ?> .widget-post-slider-content',function(){
						jQuery('.<?php echo esc_attr($noo_post_uid) ?> .widget-post-slider-content').trigger('updateSizes');
					});
					jQuery(window).resize(function(){
						jQuery('.<?php echo esc_attr($noo_post_uid) ?> .widget-post-slider-content').trigger("destroy").carouFredSel(postSliderOptions);
					});
				});
			</script>
		<?php echo wp_kses($args['after_widget'],noo_hermosa_allowed_html()); ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'widget_post_slider', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;

		return $instance;
	}

	public function form( $instance ) {
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
?>
		<p><label for="<?php echo wp_kses($this->get_field_id( 'title' ),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Title:','noo-hermosa' ); ?></label>
		<input class="widefat" id="<?php echo wp_kses($this->get_field_id( 'title' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'title' ),noo_hermosa_allowed_html()); ?>" type="text" value="<?php echo wp_kses($title,noo_hermosa_allowed_html()); ?>" /></p>

		<p><label for="<?php echo wp_kses($this->get_field_id( 'number' ),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Number of posts to show:','noo-hermosa' ); ?></label>
		<input id="<?php echo wp_kses($this->get_field_id( 'number' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'number' ),noo_hermosa_allowed_html()); ?>" type="text" value="<?php echo wp_kses($number,noo_hermosa_allowed_html()); ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo wp_kses($this->get_field_id( 'show_date' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'show_date' ),noo_hermosa_allowed_html()); ?>" />
		<label for="<?php echo wp_kses($this->get_field_id( 'show_date' ),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Display post date?','noo-hermosa' ); ?></label></p>
<?php
	}
}
endif;

if(!class_exists('Noo_Hermosa_Widget_Categories')):
class Noo_Hermosa_Widget_Categories extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'classname' => 'widget_noo_categories', 'description' => __( "A list or dropdown of categories.",'noo-hermosa' ) );
		parent::__construct('noo_categories', esc_html__( 'Noo Categories','noo-hermosa'), $widget_ops);
	}

	public function widget( $args, $instance ) {

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? esc_html__( 'Categories','noo-hermosa' ) : $instance['title'], $instance, $this->id_base );
		$c = ! empty( $instance['count'] ) ? '1' : '0';
		$h = ! empty( $instance['hierarchical'] ) ? '1' : '0';
		$p = ! empty( $instance['parent'] ) ? 0 : '';
		echo esc_attr($args['before_widget']);
		if ( $title ) {
			echo esc_attr($args['before_title'] . $title . $args['after_title']);
		}

		$cat_args = array('orderby' => 'name', 'show_count' => $c, 'parent' => $p, 'hierarchical' => $h);
?>
		<ul>
<?php
		$cat_args['title_li'] = '';

		/**
		 * Filter the arguments for the Categories widget.
		 *
		 * @since 2.8.0
		 *
		 * @param array $cat_args An array of Categories widget options.
		 */
		wp_list_categories( apply_filters( 'widget_noo_categories_args', $cat_args ) );
?>
		</ul>
<?php

		echo esc_attr($args['after_widget']);
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
		$instance['parent'] = !empty($new_instance['parent']) ? 1 : 0;

		return $instance;
	}

	public function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = esc_attr( $instance['title'] );
		$count = isset($instance['count']) ? (bool) $instance['count'] :false;
		$hierarchical = isset( $instance['hierarchical'] ) ? (bool) $instance['hierarchical'] : false;
		$parent = isset( $instance['parent'] ) ? (bool) $instance['parent'] : false;
?>
		<p><label for="<?php echo wp_kses($this->get_field_id('title'),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Title:','noo-hermosa' ); ?></label>
		<input class="widefat" id="<?php echo wp_kses($this->get_field_id('title'),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name('title'),noo_hermosa_allowed_html()); ?>" type="text" value="<?php echo wp_kses($title,noo_hermosa_allowed_html()); ?>" /></p>

		<input type="checkbox" class="checkbox" id="<?php echo wp_kses($this->get_field_id('count'),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name('count'),noo_hermosa_allowed_html()); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo wp_kses($this->get_field_id('count'),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Show post counts','noo-hermosa' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo wp_kses($this->get_field_id('hierarchical'),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name('hierarchical'),noo_hermosa_allowed_html()); ?>"<?php checked( $hierarchical ); ?> />
		<label for="<?php echo wp_kses($this->get_field_id('hierarchical'),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Show hierarchy','noo-hermosa' ); ?></label></p>

		<input type="checkbox" class="checkbox" id="<?php echo wp_kses($this->get_field_id('parent'),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name('parent'),noo_hermosa_allowed_html()); ?>"<?php checked( $parent ); ?> />
		<label for="<?php echo wp_kses($this->get_field_id('parent'),noo_hermosa_allowed_html()); ?>"><?php echo esc_html__( 'Only Show Parent','noo-hermosa' ); ?></label></p>
<?php
	}
}
endif;



if(!class_exists('Noo_Hermosa_Tabs_Widget')):
class Noo_Hermosa_Tabs_Widget extends  WP_Widget{

    /**
     * Resister widget width WordPress
     */
    function __construct(){
       parent::__construct(
            'noo_tabs',
            esc_html__( 'Tabs Widget', 'noo-hermosa'),
           array('description'  =>  esc_html__( 'Display post buy style tabs', 'noo-hermosa'))
       );
    }

    /**
     * Front-end display of widget
     */
     public function widget( $args, $instance ){

         $limit = $instance['limit'];
         ?>
                <div class="noo-widgettab widget">
                    <div class="widget-tabs-header">
                        <h6 data-option-value='noo_topview' class="box-title">
                            <span>
                                <?php echo esc_html__( 'TRENDING', 'noo-hermosa') ?>
                            </span>
                        </h6>
                        <h6 data-option-value='noo_recent' class="box-title noo_widgetab">
                            <span>
                                <?php echo esc_html__( 'RECENT', 'noo-hermosa') ?>
                            </span>
                        </h6>
                        <h6 data-option-value='noo_comment' class="box-title noo_widgetab">
                            <span>
                                <?php echo esc_html__( 'COMMENT', 'noo-hermosa') ?>
                            </span>
                        </h6>
                    </div>
                    <div class="widget_tabs_content">
                        <div class="noo_topview noo_widget_content">
                            <ul>
                                <?php
                                    $args = array(
                                        'posts_per_page' => $limit,
                                        'meta_key'       => 'post_count_indate',
                                        'orderby'        => 'meta_value_num',
                                        'order'          => 'DESC',
                                        'tax_query'      => array(
                                            array(
                                                'taxonomy' => 'post_format',
                                                'field'    => 'slug',
                                                'terms' => array(
                                                    'post-format-aside',
                                                    'post-format-chat',
                                                    'post-format-audio',
                                                    'post-format-link',
                                                    'post-format-quote',
                                                    'post-format-status'
                                                ),
                                                'operator' => 'NOT IN'
                                            )
                                        )
                                    );
                                    $top_query = new WP_Query( $args );
                                    if ( $top_query -> have_posts() ):
                                        while( $top_query -> have_posts() ): $top_query -> the_post();
                                ?>
                                <li>
                                    <?php the_post_thumbnail('thumbnail') ?>
                                    <div class="noo_tb">
                                        <?php echo NooPost::get_category_label( 'cat', true ); ?>
                                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    </div>
                                </li>

                                <?php
                                        endwhile;
                                    endif;
                                    wp_reset_postdata();
                                ?>
                            </ul>
                        </div>
                        <div class="noo_recent noo_widget_content">
                            <ul>
                                <?php
                                    $args = array(
                                        'posts_per_page' => $limit,
                                        'orderby'        => 'date',
                                        'order'          => 'DESC',
                                        'tax_query'      => array(
                                            array(
                                                'taxonomy' => 'post_format',
                                                'field'    => 'slug',
                                                'terms' => array(
                                                    'post-format-aside',
                                                    'post-format-chat',
                                                    'post-format-audio',
                                                    'post-format-link',
                                                    'post-format-quote',
                                                    'post-format-status'
                                                ),
                                                'operator' => 'NOT IN'
                                            )
                                        )
                                    );
                                    $top_query = new WP_Query( $args );
                                    if ( $top_query -> have_posts() ):
                                        while( $top_query -> have_posts() ): $top_query -> the_post();
                                ?>
                                <li>
                                    <?php the_post_thumbnail('thumbnail') ?>
                                    <div class="noo_tb">
                                        <?php echo NooPost::get_category_label( 'cat', true ); ?>
                                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    </div>
                                </li>

                                <?php
                                        endwhile;
                                    endif;
                                    wp_reset_postdata();
                                ?>
                            </ul>
                        </div>
                        <div class="noo_comment noo_widget_content">
                            <?php
                            $comments = get_comments( apply_filters( 'widget_comments_args', array(
                                'number'      => $limit,
                                'status'      => 'approve',
                                'post_status' => 'publish'
                            ) ) );

                            $output = '';

                            $output .= '<ul class="recentcomments">';
                            if ( $comments ) {
                                // Prime cache for associated posts. (Prime post term cache if we need it for permalinks.)
                                $post_ids = array_unique( wp_list_pluck( $comments, 'comment_post_ID' ) );
                                _prime_post_caches( $post_ids, strpos( get_option( 'permalink_structure' ), '%category%' ), false );

                                foreach ( (array) $comments as $comment) {
                                    $output .= '<li class="recentcomments">';
                                    /* translators: comments widget: 1: comment author, 2: post link */
                                    $output .= sprintf( _x( '%1$s on %2$s', 'widgets','noo-hermosa' ),
                                        '<span class="comment-author-link">' . get_comment_author_link($comment->comment_ID) . '</span>',
                                        '<a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '">' . get_the_title( $comment->comment_post_ID ) . '</a>'
                                    );
                                    $output .= '</li>';
                                }
                            }
                            $output .= '</ul>';
                            echo wp_kses($output,noo_hermosa_allowed_html());
                            ?>
                        </div>
                    </div>
                    <script>

                        jQuery(document).ready(function(){
                            "use strict";
                            jQuery('.noo-widgettab').each(function(){
                                jQuery(this).find('.noo_widget_content:first').show();
                                jQuery(this).find('.widget-tabs-header h6:first').addClass('tab-active');
                            });

                            jQuery('.widget-tabs-header h6').click(function(){
                                jQuery(this).parent().find('h6').removeClass('tab-active');
                                jQuery(this).addClass('tab-active');
                                var $id = jQuery(this).attr('data-option-value');
                                jQuery(this).parent().parent().find('.noo_widget_content').fadeOut(0);
                                jQuery('.'+$id).fadeIn(0);

                            }) ;
                        });
                    </script>
                </div>
     <?php



     }

     /**
      * Back-end widget form
      */
     public function form($instance){
            extract(wp_parse_args($instance,array(
                'limit' =>  5
            )));
     ?>
        <p>
            <label for="<?php echo wp_kses($this -> get_field_id('limit'),noo_hermosa_allowed_html()) ?>"><?php echo esc_html__( 'Limit post', 'noo-hermosa') ?></label>
            <input type="text" name="<?php echo wp_kses($this -> get_field_name('limit'),noo_hermosa_allowed_html()) ; ?>" id="<?php echo wp_kses($this -> get_field_id('limit'),noo_hermosa_allowed_html()) ?>" class="widefat" value="<?php echo esc_attr($limit); ?>" />
        </p>
     <?php
     }

    /**
     * Update
     */
    public  function update($new_instance, $old_instance){
        $instance = array();
        $instance['limit']  =   ( !empty($new_instance['limit']) ) ? strip_tags($new_instance['limit']) : '';
        return $instance;
    }

}
endif;














if( !class_exists('Noo_Hermosa_Widget_Instagram') ):
    class Noo_Hermosa_Widget_Instagram extends  WP_Widget{

        public function __construct(){
            parent::__construct(
                'noo_widget_instagram',
                'Noo Instagram',
                array(
                    'description', 
                    esc_html__('Noo Instagram', 'noo-hermosa')
                )
            );
        }

        public function widget($args, $instance){
            extract( $args );
            extract( $instance );
            echo wp_kses($before_widget,noo_hermosa_allowed_html());
            if ( ! empty( $instance['title'] ) ) {
                $title = apply_filters( 'widget_title', $instance['title'] );
            }
            if ( ! empty( $title ) ) {
                echo wp_kses($before_title . $title . $after_title,noo_hermosa_allowed_html());
            }
            ?>

            <div class="noo-instagram">
                <ul>
                    <?php
                    $data = noo_hermosa_get_instagram_data( $instagram_username, $refresh_hour, $number, 'standard_resolution', $randomise );

                    if( isset($data) && is_array($data) && !empty($data)){
                        foreach ($data as $value) {

                            $link = '';
                            $image = '';
                            $text = '';
                            if( isset($value['link']) && !empty($value['link']) ){
                                $link = $value['link'];
                            }
                            if( isset($value['text']) && !empty($value['text']) ){
                                $text = $value['text'];
                            }
                            if( isset($value['image']) && !empty($value['image']) ){
                                $image = $value['image'];
                            }
                            echo '<li><a target="_blank" href="'.esc_url($link).'"><img src="'.esc_url($image).'" alt="'.esc_attr($text).'"></a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>

            <?php
            echo wp_kses($after_widget,noo_hermosa_allowed_html());
        }

        public function form( $instance ){
            $instance = wp_parse_args( $instance, array(
                'title'                         =>  esc_html__('instagram photos', 'noo-hermosa' ),
                'instagram_username'            =>  '',
                'number'                        =>  '8',
                'refresh_hour'                  =>  '4',
                'randomise'                     =>  'true'
            ) );
            extract($instance);
            ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title:', 'noo-hermosa'); ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" class="widefat" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('instagram_username') ); ?>"><?php esc_html_e('Instagram username:', 'noo-hermosa'); ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id('instagram_username') ); ?>" name="<?php echo esc_attr( $this->get_field_name('instagram_username') ); ?>" class="widefat" value="<?php echo esc_attr($instagram_username); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('number') ); ?>"><?php esc_html_e('Number:', 'noo-hermosa'); ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id('number') ); ?>" name="<?php echo esc_attr( $this->get_field_name('number') ); ?>" class="widefat" value="<?php echo esc_attr($number); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('refresh_hour') ); ?>"><?php esc_html_e('Refresh hour:', 'noo-hermosa'); ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id('refresh_hour') ); ?>" name="<?php echo esc_attr( $this->get_field_name('refresh_hour') ); ?>" class="widefat" value="<?php echo esc_attr($refresh_hour); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('randomise') ); ?>"><?php esc_html_e('Randomise:', 'noo-hermosa'); ?></label>
                <input type="text" id="<?php echo esc_attr( $this->get_field_id('randomise') ); ?>" name="<?php echo esc_attr( $this->get_field_name('randomise') ) ?>" class="widefat" value="<?php echo esc_attr($randomise); ?>">
            </p>

        <?php
        }
        // method update
        public function update( $new_instance, $old_instance ){
            $instance                             =   $old_instance;
            $instance['title']                    =   ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : ''  ;
            $instance['instagram_username']       =   ( ! empty( $new_instance['instagram_username'] ) ) ? strip_tags( $new_instance['instagram_username'] ) : ''  ;
            $instance['number']                   =   ( ! empty( $new_instance['number'] ) ) ? strip_tags( $new_instance['number'] ) : ''  ;
            $instance['refresh_hour']             =   ( ! empty( $new_instance['refresh_hour'] ) ) ? strip_tags( $new_instance['refresh_hour'] ) : ''  ;
            $instance['randomise']                =   ( ! empty( $new_instance['randomise'] ) ) ? strip_tags( $new_instance['randomise'] ) : ''  ;
            return $instance;
        }
    }
    
endif;

if ( !class_exists( 'Noo_Hermosa_Widget_Recent_Posts' ) ) :
class Noo_Hermosa_Widget_Recent_Posts extends WP_Widget {

    /**
     * Sets up a new Recent Posts widget instance.
     *
     * @since 2.8.0
     * @access public
     */
    public function __construct() {
        $widget_ops = array(
			'classname'   => 'widget_recent_entries', 
			'description' => esc_html__( 'Your site&#8217;s most recent Posts.','noo-hermosa') 
        );
        parent::__construct(
        	'noo-recent-posts', 
        	esc_html__( 'Noo Recent Posts', 'noo-hermosa' ), 
        	$widget_ops
        );
        $this->alt_option_name = 'widget_recent_entries';
    }

    /**
     * Outputs the content for the current Recent Posts widget instance.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Recent Posts widget instance.
     */
    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }

        $title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : esc_html__( 'Recent Posts','noo-hermosa' );

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        $number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
        if ( ! $number )
            $number = 5;
        $show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;

        /**
         * Filter the arguments for the Recent Posts widget.
         *
         * @since 3.4.0
         *
         * @see WP_Query::get_posts()
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         */
        $r = new WP_Query( apply_filters( 'widget_posts_args', array(
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true
        ) ) );

        if ($r->have_posts()) :
            ?>
            <?php echo wp_kses($args['before_widget'],noo_hermosa_allowed_html()); ?>
            <?php if ( $title ) {
            echo wp_kses($args['before_title'] . $title . $args['after_title'],noo_hermosa_allowed_html());
        } ?>
            <ul class="post_list_widget">
                <?php while ( $r->have_posts() ) : $r->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <?php
                                if ( has_post_thumbnail() ) :
                                    the_post_thumbnail( array( 90, 90 ) );
                                else :
                                    echo '<img src="' . get_template_directory_uri() . '/assets/images/image-size-90x90.png" alt="' . get_the_title() . '" />';
                                endif;
                            ?>

                            <h5 class="post-title"><?php get_the_title() ? the_title() : the_ID(); ?></h5>
                            <?php if ( $show_date ) : ?>
                                <span class="post-date"><?php echo get_the_date(); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php echo wp_kses($args['after_widget'],noo_hermosa_allowed_html()); ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;
    }

    /**
     * Handles updating the settings for the current Recent Posts widget instance.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        return $instance;
    }

    /**
     * Outputs the settings form for the Recent Posts widget.
     *
     * @since 2.8.0
     * @access public
     *
     * @param array $instance Current settings.
     */
    public function form( $instance ) {
        $title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        $number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        ?>
        <p><label for="<?php echo wp_kses($this->get_field_id( 'title' ),noo_hermosa_allowed_html()); ?>"><?php esc_html_e( 'Title:', 'noo-hermosa' ); ?></label>
            <input class="widefat" id="<?php echo wp_kses($this->get_field_id( 'title' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'title' ),noo_hermosa_allowed_html()); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

        <p><label for="<?php echo wp_kses($this->get_field_id( 'number' ),noo_hermosa_allowed_html()); ?>"><?php esc_html_e( 'Number of posts to show:', 'noo-hermosa' ); ?></label>
            <input class="tiny-text" id="<?php echo wp_kses($this->get_field_id( 'number' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'number' ),noo_hermosa_allowed_html()); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox"<?php checked( $show_date ); ?> id="<?php echo wp_kses($this->get_field_id( 'show_date' ),noo_hermosa_allowed_html()); ?>" name="<?php echo wp_kses($this->get_field_name( 'show_date' ),noo_hermosa_allowed_html()); ?>" />
            <label for="<?php echo wp_kses($this->get_field_id( 'show_date' ),noo_hermosa_allowed_html()); ?>"><?php esc_html_e( 'Display post date?' , 'noo-hermosa'); ?></label></p>
    <?php
    }
}
endif;
