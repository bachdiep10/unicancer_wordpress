<?php
/**
 * Comments Template
 *
 * @package UNI_ASIA
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$comment_count = get_comments_number();
			if ( 1 === $comment_count ) {
				printf(
					/* translators: %s: post title */
					esc_html__( 'Một bình luận cho &ldquo;%s&rdquo;', 'uniasia' ),
					'<span>' . get_the_title() . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count, 2: post title */
					esc_html( _nx( '%1$s bình luận cho &ldquo;%2$s&rdquo;', '%1$s bình luận cho &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'uniasia' ) ),
					number_format_i18n( $comment_count ),
					'<span>' . get_the_title() . '</span>'
				);
			}
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments( array(
				'style'      => 'ol',
				'short_ping' => true,
			) );
			?>
		</ol>

		<?php
		the_comments_pagination( array(
			'prev_text' => '< ' . esc_html__( 'Trước', 'uniasia' ),
			'next_text' => esc_html__( 'Sau', 'uniasia' ) . ' >',
		) );
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="no-comments"><?php esc_html_e( 'Bình luận đã đóng.', 'uniasia' ); ?></p>
		<?php endif; ?>

	<?php endif; ?>

	<?php
	comment_form( array(
		'class_form'         => 'comment-form',
		'title_reply'        => esc_html__( 'Để lại bình luận', 'uniasia' ),
		'title_reply_to'     => esc_html__( 'Trả lời %s', 'uniasia' ),
		'cancel_reply_link'  => esc_html__( 'Hủy trả lời', 'uniasia' ),
		'label_submit'       => esc_html__( 'Gửi bình luận', 'uniasia' ),
		'comment_field'      => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Bình luận', 'uniasia' ) . '</label><textarea id="comment" name="comment" rows="5" required></textarea></p>',
		'fields'             => array(
			'author' => '<p class="comment-form-author"><label for="author">' . esc_html__( 'Tên', 'uniasia' ) . '</label><input id="author" name="author" type="text" value="" size="30" required></p>',
			'email'  => '<p class="comment-form-email"><label for="email">' . esc_html__( 'Email', 'uniasia' ) . '</label><input id="email" name="email" type="email" value="" size="30" required></p>',
			'url'    => '<p class="comment-form-url"><label for="url">' . esc_html__( 'Website', 'uniasia' ) . '</label><input id="url" name="url" type="url" value="" size="30"></p>',
		),
	) );
	?>
</div>