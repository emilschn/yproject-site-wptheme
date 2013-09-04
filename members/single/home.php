<?php

/**
 * BuddyPress - Users Home
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

get_header( 'buddypress' ); ?>

	<div id="content" class="center">
		<div class="padder">

			<?php do_action( 'bp_before_member_home_content' ); ?>

			<div id="item-header" role="complementary">

				<?php locate_template( array( 'members/single/member-header.php' ), true ); ?>

			</div><!-- #item-header -->

			<?php
			/*
			<div id="item-nav">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>

						<?php bp_get_displayed_user_nav(); ?>

						<?php do_action( 'bp_member_options_nav' ); ?>

					</ul>
				</div>
			</div><!-- #item-nav -->
			 * 
			 */
			?>
			
			<?php
			if (is_user_logged_in() && get_current_user_id() == bp_current_user_id()) {
			    echo 'Vous disposez de ' . ypcf_mangopay_get_user_personalamount_by_wpid(bp_current_user_id()) . edd_get_currency() . ' dans votre porte-monnaie.';
			}
				
			
			$purchases = edd_get_users_purchases(bp_current_user_id());
			if ( $purchases ) : ?>
				<table id="edd_user_history">
					<thead>
						<tr class="edd_purchase_row">
							<?php do_action('edd_purchase_history_header_before'); ?>
							<th class="edd_purchase_date"><?php _e('Date', 'edd'); ?></th>
							<th class="edd_purchase_project"><?php _e('Project', 'edd'); ?></th>
							<th class="edd_purchase_status"><?php _e('Status', 'edd'); ?></th>
							<?php do_action('edd_purchase_history_header_after'); ?>
						</tr>
					</thead>
					<?php foreach ( $purchases as $post ) : setup_postdata( $post ); ?>
						<?php $purchase_data = edd_get_payment_meta( $post->ID ); ?>
						
						<tr class="edd_purchase_row">
							<?php do_action( 'edd_purchase_history_row_start', $post->ID, $purchase_data ); ?>
							<td class="edd_purchase_date"><?php echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $post->ID ) ) ); ?></td>
							<td class="edd_purchase_project">
								<?php 
								$downloads = edd_get_payment_meta_downloads($post->ID); 
								$download_id = '';
								if (is_array($downloads[0])) $download_id = $downloads[0]["id"]; 
								else $download_id = $downloads[0];
								
								$post_camp = get_post($download_id);
								$campaign = atcf_get_campaign( $post_camp );
								echo '<a href="' . get_permalink($campaign->ID) . '">' . $post_camp->post_title . '</a>';
								?>
							</td>
							<td class="edd_purchase_status">
							    <?php echo edd_get_payment_status( $post, true ); ?>
							</td>
							<?php do_action( 'edd_purchase_history_row_end', $post->ID, $purchase_data ); ?>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
			
			<div id="item-body">

				<?php do_action( 'bp_before_member_body' );

				if ( bp_is_user_activity() || !bp_current_component() ) :
					locate_template( array( 'members/single/activity.php'  ), true );

				 elseif ( bp_is_user_blogs() ) :
					locate_template( array( 'members/single/blogs.php'     ), true );

				elseif ( bp_is_user_friends() ) :
					locate_template( array( 'members/single/friends.php'   ), true );

				elseif ( bp_is_user_groups() ) :
					locate_template( array( 'members/single/groups.php'    ), true );

				elseif ( bp_is_user_messages() ) :
					locate_template( array( 'members/single/messages.php'  ), true );

				elseif ( bp_is_user_profile() ) :
					locate_template( array( 'members/single/profile.php'   ), true );

				elseif ( bp_is_user_forums() ) :
					locate_template( array( 'members/single/forums.php'    ), true );

				elseif ( bp_is_user_settings() ) :
					locate_template( array( 'members/single/settings.php'  ), true );

				// If nothing sticks, load a generic template
				else :
					locate_template( array( 'members/single/plugins.php'   ), true );

				endif;

				do_action( 'bp_after_member_body' ); ?>

			</div><!-- #item-body -->

			<?php do_action( 'bp_after_member_home_content' ); ?>

		</div><!-- .padder -->
	</div><!-- #content -->

<?php get_footer( 'buddypress' ); ?>
