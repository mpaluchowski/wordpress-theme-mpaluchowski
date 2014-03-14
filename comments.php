			<section class="comments">
			<?php comment_form(); ?>

			<ol class="comment-list">
				<?php
					wp_list_comments( array(
						'style'      => 'ol',
						'short_ping' => true,
						'avatar_size'=> 40,
					) );
				?>
			</ol>

			<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfourteen' ); ?></p>
			<?php endif; // !comments_open() ?>
			</section>