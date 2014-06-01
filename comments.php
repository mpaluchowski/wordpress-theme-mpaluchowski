	<?php
		if ( have_comments() ):

			comment_form();
	?>

			<ol class="comment-list">
				<?php
					wp_list_comments( array(
						'style'      => 'ol',
						'short_ping' => true,
						'avatar_size'=> 80,
					) );
				?>
			</ol>

	<?php
			if ( !comments_open() ) {
				echo '<p class="no-comments">' . __( 'Comments are closed.', 'mpaluchowski' ) . '</p>';
			}

		endif; // have_comments()
	?>
