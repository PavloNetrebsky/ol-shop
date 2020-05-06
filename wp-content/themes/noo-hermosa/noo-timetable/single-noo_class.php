<?php
/**
 * The Template for displaying all single classes
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/single-noo_class.php.
 *
 * @author 		NooTheme
 * @package 	NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

$use_advanced_multi_time = noo_timetable_get_post_meta( get_the_ID(), "_use_advanced_multi_time", false );
$show_meta_address  = noo_hermosa_get_option('noo_classes_meta_address', false);

function noo_hermosa_class_meta_address($bool) {
	$show_meta_address  = noo_hermosa_get_option('noo_classes_meta_address', false);
	return $show_meta_address;
}
add_filter( 'noo_timetable_classes_meta_address', 'noo_hermosa_class_meta_address');

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main noo-container">
        <div class="noo-row">

            <div class="<?php noo_hermosa_main_class(); ?>">
                <?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="content-header clearfix">
							<h1 class="content-title">
								<?php the_title(); ?>
							</h1>
							<p class="content-meta">
								<?php
									if($trainer_ids = noo_timetable_get_post_meta(get_the_ID(),'_trainer')):
										Noo__Timetable__Class::get_trainer_list($trainer_ids);
									endif;
								?>

								<?php if($category_list = get_the_term_list(get_the_ID(), 'class_category',' ',', ')):?>
								<span title="<?php echo esc_html__('Category', 'noo-hermosa'); ?>">
									<i class="icon ion-android-clipboard"></i>
									<?php echo wp_kses($category_list,noo_hermosa_allowed_html())?>
								</span>
								<?php endif;?>
								<?php if($level_list = get_the_term_list(get_the_ID(), 'class_level',' ',', ')):?>
								<span title="<?php echo esc_html__('Level', 'noo-hermosa'); ?>" class="level-info">
									<i class="icon ion-social-buffer-outline"></i>
									<?php echo wp_kses($level_list,noo_hermosa_allowed_html())?>
								</span>
								<?php endif;?>
							</p>
						</header>
						<?php if( has_post_thumbnail() ) : ?>
							<div class="content-featured">
								<?php the_post_thumbnail(); ?>
							</div>
						<?php endif; ?>
						<div class="content-wrap">
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</div>
						
						<?php
							Noo__Timetable__Class::get_timetable();
						?>

						<?php echo noo_hermosa_social_share(get_the_ID(), 'noo_class'); ?>
					</article> <!-- /#post- -->
					<?php wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'noo-hermosa' ), 'after' => '</div>' ) ); ?>
					<?php if ( comments_open() ) : ?>
						<?php comments_template( '', true ); ?>
					<?php endif; ?>
				<?php endwhile; ?>
            </div>

			<div class="<?php noo_hermosa_sidebar_class(); ?>">
    			<div class="noo-sidebar-wrap">

					<?php
			        //Class Information Location

			        $class_address		= noo_timetable_get_post_meta( get_the_ID(), "_address", '' );
			        $number_of_weeks	= noo_timetable_get_post_meta( get_the_ID(), "_number_of_weeks", '' );
			        $open_date		    = noo_timetable_get_post_meta( get_the_ID(), "_open_date", '' );
			        $number_days		= (array) noo_timetable_json_decode( noo_timetable_get_post_meta( get_the_ID(), "_number_day", '' ) );
			        $open_time		    = noo_timetable_get_post_meta( get_the_ID(), "_open_time", '' );
			        $close_time		    = noo_timetable_get_post_meta( get_the_ID(), "_close_time", '' );
			        $register_link		= noo_timetable_get_post_meta( get_the_ID(), "_register_link", '' );

			        // Get Open Date & Next Date
			        $args = array(
						'open_date'       => $open_date,
						'number_of_weeks' => $number_of_weeks,
						'number_days'     => $number_days
			        );
			        $class_dates = Noo__Timetable__Class::get_open_date_display( $args );
			        ?>
			        <div class="widget widget-single-class-sidebar single-sidebar">
			            <h4 class="widget-title">
		                    <?php echo apply_filters( 'widget_title', esc_html__('Class Information','noo-hermosa') ); ?>
		                </h4>
			            <?php Noo__Timetable__Class::show_information(); ?>
			        </div>

					<div class="widget widget-single-class-trainer">
			            <h4 class="widget-title">
		                    <?php echo apply_filters( 'widget_title', esc_html__('Class Trainer','noo-hermosa') ); ?>
		                </h4>
		                <div class="trainer-tag-wrap">
							<?php
							if (  $trainer_ids && count($trainer_ids) > 0 && $trainer_ids[0] != '' ) :
								$trainer_ids = (array) noo_timetable_json_decode($trainer_ids);
								foreach ( $trainer_ids as $trainer_id ) : ?>
								<div class="trainer-bio">
									<a class="trainer-avatar" href="<?php echo get_permalink($trainer_id) ; ?>">
			                            <?php echo get_the_post_thumbnail($trainer_id, 'noo-thumbnail-trainer') ?>
									</a>
									<div class="trainer-info">
										<h4>
											<a title="<?php printf( esc_html__( 'Post by %s','noo-hermosa'), get_the_title($trainer_id) ); ?>" href="<?php echo get_permalink($trainer_id) ; ?>" rel="author">
												<?php echo get_the_title($trainer_id) ?>
											</a>
										</h4>
										<div class="trainer-category">
											<?php
												echo get_the_term_list($trainer_id, 'class_category',' ',', ');
											?>
										</div>
										<?php
											$facebook		=   noo_timetable_get_post_meta( $trainer_id, "_noo_trainer_facebook", '' );
											$google     	=   noo_timetable_get_post_meta( $trainer_id, "_noo_trainer_google", '' );
											$twitter		=   noo_timetable_get_post_meta( $trainer_id, "_noo_trainer_twitter", '' );
											$pinterest		=   noo_timetable_get_post_meta( $trainer_id, "_noo_trainer_pinterest", '' );
										?>
										<?php if(!empty($facebook) || !empty($twitter) || !empty($google) || !empty($linkedin) || !empty($pinterest)):?>
											<div class="trainer-social all-social-share">
												<?php echo ( !empty($facebook) ? '<a href="' . $facebook . '" class="fa fa-facebook"></a>' : '' ); ?>
		                                        <?php echo ( !empty($google) ? '<a href="' . $google . '" class="fa fa-google-plus"></a>' : '' ); ?>
		                                        <?php echo ( !empty($twitter) ? '<a href="' . $twitter . '" class="fa fa-twitter"></a>' : '' ); ?>
		                                        <?php echo ( !empty($pinterest) ? '<a href="' . $pinterest . '" class="fa fa-pinterest"></a>' : '' ); ?>
											</div>
										<?php endif; ?>
			                            <a class="btn view-profile" title="<?php printf( esc_html__( 'Post by %s','noo-hermosa'), get_the_title($trainer_id) ); ?>" href="<?php echo get_permalink($trainer_id); ?>" rel="author">
			                            	<?php echo esc_html__('View Profile', 'noo-hermosa'); ?>
			                            </a>
									</div>
								</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
		            </div>

    				<?php
			        $sidebar = noo_hermosa_get_sidebar_id();
			        if( ! empty( $sidebar ) ) :
			        ?>
			            <?php // Dynamic Sidebar
			            if ( ! function_exists( 'dynamic_sidebar' ) || ! dynamic_sidebar( $sidebar ) ) : ?>
			                <!-- Sidebar fallback content -->

			            <?php endif; // End Dynamic Sidebar sidebar-main ?>
			        <?php endif; // End sidebar ?> 
    			</div>
    		</div>

        </div>
    </main><!-- .site-main -->
</div><!-- .content-area -->


<?php get_footer(); ?>