<?php
/**
 * Widget for Class
 *
 * @author 		NooTheme
 * @category    Widgets
 * @package 	NooTimetable/Widgets
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! class_exists('Noo__Timetable_Widget_Popular_Class') ) {

	/**
	 * Register Noo Timetable Widget Popular Class
	 */
	
	class Noo__Timetable_Widget_Popular_Class extends WP_Widget {

		public function __construct() {

			parent::__construct(
				'noo_timetable_popular_class',
				esc_html__( 'Noo Timetable: Popular Class Slider', 'noo-timetable'),
				array(
					'classname' => 'widget-popular-class',
					'description' => esc_html__( "Slider to show popular class", 'noo-timetable' )
				)
			);
		}

		public function widget( $args, $instance ) {

			extract($instance);

			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			
			$class_cat    = ( ! empty( $instance['class_category'] ) ) ? $instance['class_category'] : 'all';
			$orderby      = ( ! empty( $instance['orderby'] ) ) ? $instance['orderby'] : 'default';
			$number       = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
			$show_trainer = isset( $instance['show_trainer'] ) ? $instance['show_trainer'] : false;
			$autoplay     = isset( $instance['autoplay'] ) && $instance['autoplay'] ? 'true' : 'false';

			wp_enqueue_style('carousel');
			wp_enqueue_script('carousel');

			echo $args['before_widget'];

			if ($title)
				echo $args['before_title'] . $title . $args['after_title'];
	        ?>
	        
	        <div class="noo-class-slider-wrap owl-carousel">
	            <?php

	            $orderkey = '';
		        $order = 'DESC';

		        switch ( $orderby ) {
		            case 'open_date':
		                $orderby  = 'meta_value_num';
		                $order    = 'ASC';
		                $orderkey = '_open_date';
		                break;
		            case 'latest':
		                $orderby = 'date';
		                break;
		            case 'oldest':
		                $orderby = 'date';
		                $order = 'ASC';
		                break;
		            case 'alphabet':
		                $orderby = 'title';
		                $order = 'ASC';
		                break;
		            case 'ralphabet':
		                $orderby = 'title';
		                break;
		            default:
		                $orderby = 'default';
		                break;
		        }

	            $arg = array(
	                'post_type'         =>  'noo_class',
	                'orderby'           =>  'start_date',
	                'order'             =>  'desc',
	                'posts_per_page'    =>  esc_attr( $number ),
	                'ignore_sticky_posts' => true
	            );

	            if ('default' != $orderby) {
		            $arg['orderby'] = $orderby;
		            $arg['order']   = $order;
		        }

		        if ('meta_value_num' == $orderby) {
		            $arg['meta_key'] = $orderkey;
		        }

	            if ( !empty( $class_cat ) && $class_cat != 'all' ) {
		            $arg['tax_query'][]  = array(
		                'taxonomy' =>  'class_category',
		                'field'    =>  'id',
		                'terms'    => explode(',', $class_cat),
		            );
		        }

	            $query = new WP_Query($arg);
	            

	            if( $query->have_posts() ):
	                while( $query->have_posts() ):
	                    $query->the_post();
		            ?>
		            <div class="noo-class-slider-item">

					    <div class="item-thumb">
					    	<a href="<?php the_permalink() ?>">
						    	<?php the_post_thumbnail() ?>
						    </a>
					    </div>

		                <div class="item-info">
			                <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
							<?php
			                	if ( $show_trainer ) :
			                		$trainer_ids = noo_timetable_get_post_meta(get_the_ID(), '_trainer');
			                		Noo__Timetable__Class::get_trainer_list($trainer_ids);
			                	endif;
			                ?>
		                </div>
		            </div>
	                <?php 
	               	endwhile; ?>
					
	            <?php endif;
	        		wp_reset_postdata();
	            ?>
			</div>

			<script type="text/javascript">
				jQuery(document).ready(function(){

					jQuery('.noo-class-slider-wrap').each(function(){
				        jQuery(this).owlCarousel({
				            loop:false,
			                margin:10,
			                navigation:false,
			                dots: true,
			                autoplay: <?php echo esc_attr( $autoplay ); ?>, //Set AutoPlay to 3 seconds,
			                autoplayTimeout:5000,
			                autoplayHoverPause:false,
			                pagination: true,
				            autoHeight: true,
			                responsive:{
			                    0:{
			                        items:1
			                    },
			                    600:{
			                        items:1
			                    },
			                    1000:{
			                        items:1,
			                    }
			                }

				        });
				    });
			    });
			</script>
	    	<?php
	        echo $args['after_widget'];
		}

		public function update( $new_instance, $old_instance ) {
			$instance                   = $old_instance;
			$instance['title']          = strip_tags($new_instance['title']);
			$instance['class_category'] = strip_tags( $new_instance['class_category'] );
			$instance['orderby'] 		= strip_tags( $new_instance['orderby'] );
			$instance['number']         = (int) $new_instance['number'];
			$instance['show_trainer']   = isset( $new_instance['show_trainer'] ) ? (bool) $new_instance['show_trainer'] : false;
			$instance['autoplay']       = isset( $new_instance['autoplay'] ) ? (bool) $new_instance['autoplay'] : false;

			return $instance;
		}

		public function form( $instance ) {
			$title          = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
			$class_category = isset( $instance['class_category'] ) ? esc_attr( $instance['class_category'] ) : '';
			$orderby        = isset( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : '';
			$number         = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
			$show_trainer   = isset( $instance['show_trainer'] ) ? (bool) $instance['show_trainer'] : false;
			$autoplay       = isset( $instance['autoplay'] ) ? (bool) $instance['autoplay'] : false;
			?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:','noo-timetable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

			<p>
				<label for="<?php echo $this->get_field_id( 'class_category' ); ?>"><?php echo esc_html__( 'Class Category:','noo-timetable' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'class_category' ); ?>" name="<?php echo $this->get_field_name( 'class_category' ); ?>" class="widefat">
					<option value="all"><?php esc_html_e( 'All Categories', 'noo-timetable' ); ?></option>
					<?php
						// === << get list category
						
						foreach ((array) get_terms( 'class_category', array('hide_empty'=>0)) as $category){
							?>
								<option value="<?php echo $category->term_id ?>"<?php selected( $class_category,  $category->term_id ); ?>><?php echo esc_html($category->name) ?></option>
							<?php
						}
					?>
				</select>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'orderby' ); ?>"><?php echo esc_html__( 'Order By:','noo-timetable' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>" class="widefat">
					<option value="default"<?php selected( $orderby, 'default' ); ?>><?php esc_html_e( 'Default', 'noo-timetable' ); ?></option>
					<option value="open_date"<?php selected( $orderby, 'open_date' ); ?>><?php esc_html_e( 'Open Date', 'noo-timetable' ); ?></option>
					<option value="latest"<?php selected( $orderby, 'latest' ); ?>><?php esc_html_e( 'Recent First', 'noo-timetable' ); ?></option>
					<option value="oldest"<?php selected( $orderby, 'oldest' ); ?>><?php esc_html_e( 'Older First', 'noo-timetable' ); ?></option>
					<option value="alphabet"<?php selected( $orderby, 'alphabet' ); ?>><?php esc_html_e( 'Title Alphabet', 'noo-timetable' ); ?></option>
					<option value="ralphabet"<?php selected( $orderby, 'ralphabet' ); ?>><?php esc_html_e( 'Title Reversed Alphabet', 'noo-timetable' ); ?></option>
				</select>
			</p>

			<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo esc_html__( 'Number of classes to show:','noo-timetable' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $show_trainer ); ?> id="<?php echo $this->get_field_id( 'show_trainer' ); ?>" name="<?php echo $this->get_field_name( 'show_trainer' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_trainer' ); ?>"><?php echo esc_html__( 'Display Trainer?', 'noo-timetable' ); ?></label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $autoplay ); ?> id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'autoplay' ); ?>"><?php echo esc_html__( 'Auto Play Slider?', 'noo-timetable' ); ?></label></p>
			<?php
		}
	}

}

if( ! class_exists('Noo__Timetable_Widget_Popular_Class_Coming') ) {

	/**
	 * Register Noo Timetable Widget Popular Class
	 */
	
	class Noo__Timetable_Widget_Popular_Class_Coming extends WP_Widget {

		public function __construct() {

			parent::__construct(
				'noo_timetable_popular_class_coming',
				esc_html__( 'Noo Timetable: Upcoming Class Slider', 'noo-timetable'),
				array(
					'classname' => 'widget-incoming-class',
					'description' => esc_html__( "Slider to show upcoming class", 'noo-timetable' )
				)
			);
		}

		public function widget( $args, $instance ) {

			extract($instance);

			$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
			$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			
			$class_cat    = ( ! empty( $instance['class_category'] ) ) ? $instance['class_category'] : 'all';
			$number       = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
			$show_trainer = isset( $instance['show_trainer'] ) ? $instance['show_trainer'] : false;
			$autoplay     = isset( $instance['autoplay'] ) && $instance['autoplay'] ? 'true' : 'false';

			wp_enqueue_style('carousel');
			wp_enqueue_script('carousel');

			echo $args['before_widget'];

			if ($title)
				echo $args['before_title'] . $title . $args['after_title'];
	        ?>
	        
	        <div class="noo-class-slider-wrap owl-carousel">
	            <?php

	            $comming_class_ids = Noo__Timetable__Class::get_coming_class_ids();

	            $arg = array(
					'post_type'           =>  'noo_class',
					'posts_per_page'      =>  esc_attr( $number ),
					'ignore_sticky_posts' => true,
					'meta_key'            => '_next_date',
					'orderby'             => 'meta_value_num',
					'order'               => 'ASC',
					'post__in'            => $comming_class_ids
	            );

	            if ( !empty( $class_cat ) && $class_cat != 'all' ) {
		            $arg['tax_query'][]  = array(
		                'taxonomy' =>  'class_category',
		                'field'    =>  'id',
		                'terms'    => explode(',', $class_cat),
		            );
		        }

	            $query = new WP_Query($arg);
	            

	            if( $query->have_posts() ):
	                while( $query->have_posts() ):
	                    $query->the_post();
		            ?>
		            <div class="noo-class-slider-item">

					    <div class="item-thumb">
					    	<a href="<?php the_permalink() ?>">
						    	<?php the_post_thumbnail() ?>
						    </a>
					    </div>

		                <div class="item-info">
			                <h4><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h4>
							<?php
			                	if ( $show_trainer ) :
			                		$trainer_ids = noo_timetable_get_post_meta(get_the_ID(), '_trainer');
			                		Noo__Timetable__Class::get_trainer_list($trainer_ids);
			                	endif;
			                ?>
		                </div>
		            </div>
	                <?php 
	               	endwhile; ?>
					
	            <?php endif;
	        		wp_reset_postdata();
	            ?>
			</div>

			<script type="text/javascript">
				jQuery(document).ready(function(){

					jQuery('.noo-class-slider-wrap').each(function(){
				        jQuery(this).owlCarousel({
				            loop:false,
			                margin:10,
			                navigation:false,
			                dots: true,
			                autoplay: <?php echo esc_attr( $autoplay ); ?>, //Set AutoPlay to 3 seconds,
			                autoplayTimeout:5000,
			                autoplayHoverPause:false,
			                pagination: true,
				            autoHeight: true,
			                responsive:{
			                    0:{
			                        items:1
			                    },
			                    600:{
			                        items:1
			                    },
			                    1000:{
			                        items:1,
			                    }
			                }

				        });
				    });
			    });
			</script>
	    	<?php
	        echo $args['after_widget'];
		}

		public function update( $new_instance, $old_instance ) {
			$instance                   = $old_instance;
			$instance['title']          = strip_tags($new_instance['title']);
			$instance['class_category'] = strip_tags( $new_instance['class_category'] );
			$instance['number']         = (int) $new_instance['number'];
			$instance['show_trainer']   = isset( $new_instance['show_trainer'] ) ? (bool) $new_instance['show_trainer'] : false;
			$instance['autoplay']       = isset( $new_instance['autoplay'] ) ? (bool) $new_instance['autoplay'] : false;

			return $instance;
		}

		public function form( $instance ) {
			$title          = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
			$class_category = isset( $instance['class_category'] ) ? esc_attr( $instance['class_category'] ) : '';
			$number         = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
			$show_trainer   = isset( $instance['show_trainer'] ) ? (bool) $instance['show_trainer'] : false;
			$autoplay       = isset( $instance['autoplay'] ) ? (bool) $instance['autoplay'] : false;
			?>
			<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:','noo-timetable' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

			<p>
				<label for="<?php echo $this->get_field_id( 'class_category' ); ?>"><?php echo esc_html__( 'Class Category:','noo-timetable' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'class_category' ); ?>" name="<?php echo $this->get_field_name( 'class_category' ); ?>" class="widefat">
					<option value="all"><?php esc_html_e( 'All Categories', 'noo-timetable' ); ?></option>
					<?php
						// === << get list category
						
						foreach ((array) get_terms( 'class_category', array('hide_empty'=>0)) as $category){
							?>
								<option value="<?php echo $category->term_id ?>"<?php selected( $class_category,  $category->term_id ); ?>><?php echo esc_html($category->name) ?></option>
							<?php
						}
					?>
				</select>
			</p>

			<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo esc_html__( 'Number of classes to show:','noo-timetable' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $show_trainer ); ?> id="<?php echo $this->get_field_id( 'show_trainer' ); ?>" name="<?php echo $this->get_field_name( 'show_trainer' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_trainer' ); ?>"><?php echo esc_html__( 'Display Trainer?', 'noo-timetable' ); ?></label></p>

			<p><input class="checkbox" type="checkbox" <?php checked( $autoplay ); ?> id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'autoplay' ); ?>"><?php echo esc_html__( 'Auto Play Slider?', 'noo-timetable' ); ?></label></p>
			<?php
		}
	}

}