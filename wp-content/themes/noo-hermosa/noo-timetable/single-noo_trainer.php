<?php
/**
 * The Template for displaying all single trainers
 *
 * This template can be overridden by copying it to yourtheme/noo-timetable/single-noo_trainer.php.
 *
 * @author 		NooTheme
 * @package 	NooTimetable/Templates
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

wp_enqueue_script( 'wow' );

// Trainer's info
$welcome_text	= noo_hermosa_get_post_meta( get_the_ID(), "_noo_trainer_welcome_text", '' );
$position		= noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_position", '' );
$experience		= noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_experience", '' );
$email          = noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_email", '' );
$phone          = noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_phone", '' );
$phone_esc		= preg_replace('/\s+/', '', $phone);
$biography		= noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_biography", '' );

$new_skill_label = '_noo_trainer_skill_label';
$new_skill_value = '_noo_trainer_skill_value';

$skill_label = noo_timetable_get_post_meta( get_the_ID(), $new_skill_label, '' );
$skill_value = noo_timetable_get_post_meta( get_the_ID(), $new_skill_value, '' );

$skill_label = (array) noo_timetable_json_decode($skill_label);
$skill_value = (array) noo_timetable_json_decode($skill_value);

?>

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();
		?>

		<div id="primary" class="content-area">
		    <main id="main" class="site-main noo-container">
				
				<?php  if ( $welcome_text != '' ) : ?>
		    	<p class="welcome-text"><?php echo esc_textarea($welcome_text); ?></p>
				<?php endif;  ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="noo-container">

						<div class="noo-md-5">
				        	<header class="content-header clearfix">

								<?php if( has_post_thumbnail() ) : ?>
									<div class="content-featured">
										<?php the_post_thumbnail(); ?>
									</div>
								<?php endif; ?>

								<?php
									$facebook		=   noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_facebook", '' );
									$google     	=   noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_google", '' );
									$twitter		=   noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_twitter", '' );
									$pinterest		=   noo_timetable_get_post_meta( get_the_ID(), "_noo_trainer_pinterest", '' );
								?>
								<?php if(!empty($facebook) || !empty($twitter) || !empty($google) || !empty($linkedin) || !empty($pinterest)):?>
									<div class="trainer-social all-social-share">
										<?php echo ( !empty($facebook) ? '<a href="' . $facebook . '" class="fa fa-facebook"></a>' : '' ); ?>
			                            <?php echo ( !empty($google) ? '<a href="' . $google . '" class="fa fa-google-plus"></a>' : '' ); ?>
			                            <?php echo ( !empty($twitter) ? '<a href="' . $twitter . '" class="fa fa-twitter"></a>' : '' ); ?>
			                            <?php echo ( !empty($pinterest) ? '<a href="' . $pinterest . '" class="fa fa-pinterest"></a>' : '' ); ?>
									</div>
								<?php endif; ?>
								
							</header>
						</div>

						<div class="noo-md-7">
							<div class="trainer-info">
								<h1 class="content-title trainer-name">
									<?php the_title(); ?>
								</h1>
								<div class="trainer-category">
									<?php
										echo get_the_term_list(get_the_ID(), 'class_category',' ',', ');
									?>
								</div>
								<div class="trainer-description">
									<?php if( !empty( $position ) ) : ?>
			                            <div class="trainer-position"><span class="trainer-title"><?php esc_html_e('Position', 'noo-hermosa')?></span><?php echo esc_html($position); ?></div>
			                        <?php endif; ?>
			                        <?php if( !empty( $experience ) ) : ?>
			                            <div class="trainer-experience"><span class="trainer-title"><?php esc_html_e('Experience', 'noo-hermosa')?></span><?php echo esc_html($experience); ?></div>
			                        <?php endif; ?>
			                        <?php if( !empty( $email ) ) : ?>
			                            <div class="trainer-email"><span class="trainer-title"><?php esc_html_e('Email', 'noo-hermosa')?></span><a href="mailto:<?php echo esc_html($email); ?>"><?php echo esc_html($email); ?></a></div>
			                        <?php endif; ?>
			                        <?php if( !empty( $phone ) ) : ?>
			                            <div class="trainer-phone"><span class="trainer-title"><?php esc_html_e('Phone', 'noo-hermosa')?></span><a href="tel:<?php echo esc_html($phone_esc); ?>"><?php echo esc_html($phone); ?></a></div>
			                        <?php endif; ?>
			                        <?php if( !empty( $biography ) ) : ?>
			                            <div class="trainer-biography"><span class="trainer-title"><?php esc_html_e('Biography', 'noo-hermosa')?></span><div class="text-bio"><?php echo esc_textarea($biography); ?></div></div>
			                        <?php endif; ?>

									<?php
									if ( is_array($skill_label) && count($skill_label) > 0 && $skill_label[0] != '' ) : ?>
									<div class="trainer-skill">
										<span class="trainer-title"><?php esc_html_e('My skill', 'noo-hermosa')?></span>

										<div class="noo-progress-bar">
										<?php
										foreach ($skill_label as $k => $label) :
											$lab = isset($skill_label[$k]) ? $skill_label[$k] : '';
											$val = isset($skill_value[$k]) ? $skill_value[$k] : '';
											if ( $lab != '' && $val != ''  ) :
											?>

											<div class="noo-single-bar">
												<small style="width:<?php echo esc_attr( $val ); ?>%" class="label-bar">
													<span class="noo-progress-label"><?php echo esc_attr( $lab ); ?></span>
													<span class="noo-label-units"><?php echo esc_attr( $val ); ?>%</span>
												</small>
												<span class="noo-bar wow loadSkill" data-wow-duration="1.5s" data-wow-delay="0.6s" style="max-width: <?php echo esc_attr( $val ); ?>%;"></span>
											</div>

			                        		<?php
			                        		endif;
			                        	endforeach; ?>
			                        	</div>
				                    </div><!-- .trainer-skill -->
				                    <script>
		                    			jQuery(document).ready(function($) {
		                    				new WOW().init();
				                    	});
				                    </script>
									<?php endif; ?>
			                    </div>
							</div>
						</div>

					</div><!-- .noo-container -->

					<a class="view-class" href="<?php echo esc_url(add_query_arg(array('trainer'=>get_the_ID(), 'load' => 'all'), get_post_type_archive_link('noo_class'))) ?>"><i class="icon ion-android-arrow-dropright-circle text-primary">&nbsp;</i><?php esc_html_e('View Classes', 'noo-hermosa'); ?></a>
								
		    	</article>
		    	
			</main><!-- .site-main -->
		</div><!-- .content-area -->

		<div class="trainer-content">
	
			<div class="noo-container">

				<div class="noo-row">
					<div class="noo-md-12">

						<div class="content">
							<?php the_content(); ?>
							<?php wp_link_pages(); ?>
						</div>

					</div>
				</div>

			</div>
		</div>
		<?php
			// End of the loop.
		endwhile;
		?>

<?php get_footer(); ?>
