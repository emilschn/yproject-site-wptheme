<?php

/**
 * BuddyPress Notification Settings
 *
 * @package BuddyPress
 * @subpackage bp-default
 */
 
get_header( 'buddypress' ); 
locate_template( array( 'members/single/admin-bar.php' ), true ); ?>

	<div id="content">
		<div class="padder center">

			<?php do_action( 'bp_before_member_settings_template' ); ?>

			

			
				<h2 class="underlined"><?php _e( 'Email Notification', 'buddypress' ); ?></h2>

				<?php do_action( 'bp_template_content' ); ?>

				<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/notifications'; ?>" method="post" class="standard-form " id="settings-form">
					<p><?php _e( 'Send an email notice when:', 'buddypress' ); ?></p>

					<?php do_action( 'bp_notification_settings' ); ?>

					<?php do_action( 'bp_members_notification_settings_before_submit' ); ?>

					<div class="submit">
						<input type="submit" name="submit" value="<?php _e( 'Save Changes', 'buddypress' ); ?>" id="submit" class="auto"  />
					</div>

					<?php do_action( 'bp_members_notification_settings_after_submit' ); ?>

					<?php wp_nonce_field('bp_settings_notifications'); ?>

				</form>

				<?php do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_settings_template' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->


<?php get_footer( 'buddypress' ); ?>