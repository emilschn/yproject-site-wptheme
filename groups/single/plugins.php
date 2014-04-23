<?php get_header( 'buddypress' ); ?>

	<div id="content">
		<div class="padder">
			<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>
	    
			<?php
			    global $post;
			    $campaign_id = groups_get_groupmeta(bp_get_group_id(), 'campaign_id');
			    $save_post = $post;
			    $post = get_post($campaign_id);
			?>
	    
			<?php locate_template( array( 'projects/single-admin-bar.php' ), true ); ?>
	    
			<?php locate_template( array( 'projects/single-header.php' ), true ); ?>

			<div id="item-nav" class="center">
				<div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
					<ul>
						<?php bp_get_options_nav(); ?>

						<?php do_action( 'bp_group_plugin_options_nav' ); ?>
					</ul>
				</div>
			</div><!-- #item-nav -->

			<?php do_action( 'bp_before_group_plugin_template' ); ?>

			<div id="item-header" class="center">
				<?php locate_template( array( 'groups/single/group-header.php' ), true ); ?>
			</div><!-- #item-header -->

			<div id="item-body" class="center">

				<?php do_action( 'bp_before_group_body' ); ?>

				<?php do_action( 'bp_template_content' ); ?>

				<?php do_action( 'bp_after_group_body' ); ?>
			</div><!-- #item-body -->

			<?php do_action( 'bp_after_group_plugin_template' ); ?>

			<?php endwhile; endif; ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar( 'buddypress' ); ?>

<?php get_footer( 'buddypress' ); ?>