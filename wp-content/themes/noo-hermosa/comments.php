<?php

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() )
	return;

$comment_title    = __( 'There are <em>%</em> comments on this post', 'noo-hermosa' );
?>

<div id="comments" class="comments-area hidden-print">

	<?php if ( have_comments() ) : ?>

		<h2 class="comments-title"><?php comments_number( esc_html__('No Comments','noo-hermosa'), esc_html__( 'One Comment', 'noo-hermosa'), $comment_title );?></h2>
		<ol class="comments-list">
			<?php
			wp_list_comments( array(
				'callback'	 => 'noo_hermosa_list_comments',
				'style'      => 'ol',
				'avatar_size'=> 70,
				) );
				?>
		</ol> <!-- /.comments-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<nav id="comment-nav-below" class="navigation">
				<h1 class="sr-only"><?php echo esc_html__( 'Comment navigation', 'noo-hermosa' ); ?></h1>
				<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'noo-hermosa' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'noo-hermosa' ) ); ?></div>
			</nav>
		<?php endif; ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
			<p class="nocomments"><?php echo esc_html__( 'Comments are closed.' , 'noo-hermosa' ); ?></p>
		<?php endif; ?>

	<?php endif; // end have_comments() ?>
		<?php
		noo_hermosa_comment_form( array(
			'comment_notes_after' => '',
			'id_submit'           => 'entry-comment-submit',
			'label_submit'        => esc_html__( 'Submit' , 'noo-hermosa' )
			) );
			?>
</div> <!-- /#comments.comments-area -->