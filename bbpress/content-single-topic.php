<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */
    global $post, $campaign_id;
    $post_topic = $post;
    $post_forum = get_post($post_topic->post_parent);
    $post_project = get_post($post_forum->post_title);
    $campaign = atcf_get_campaign( $post_project );
    $campaign_id = $campaign->ID;
    
    $post = $post_project;
?>

<?php locate_template( array( 'projects/single-admin-bar.php' ), true ); ?>

<?php locate_template( array( 'projects/single-header.php' ), true ); ?>

<div id="post_bottom_bg">
	<div id="post_bottom_content" class="center">
		<div class="left post_bottom_desc">

			<div id="bbpress-forums">

				<?php $post = $post_topic; ?>

				<?php do_action( 'bbp_template_before_single_topic' ); ?>

				<?php if ( post_password_required() ) : ?>

					<?php bbp_get_template_part( 'form', 'protected' ); ?>

				<?php else : ?>

					<?php bbp_topic_tag_list(); ?>

					<?php bbp_single_topic_description(); ?>

					<?php if ( bbp_show_lead_topic() ) : ?>

						<?php bbp_get_template_part( 'content', 'single-topic-lead' ); ?>

					<?php endif; ?>

					<?php ob_start(); if ( bbp_has_replies() ) : $temp = ob_get_clean();?>

						<?php bbp_get_template_part( 'pagination', 'replies' ); ?>

						<?php bbp_get_template_part( 'loop',       'replies' ); ?>

						<?php bbp_get_template_part( 'pagination', 'replies' ); ?>

					<?php else: $temp = ob_get_clean(); endif; ?>

					<?php bbp_get_template_part( 'form', 'reply' ); ?>

				<?php endif; ?>

				<?php do_action( 'bbp_template_after_single_topic' ); ?>

			</div>
		</div>

		<div style="clear: both"></div>
	</div>
</div>
