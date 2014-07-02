<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

$current_user = get_user_by('id', bp_displayed_user_id());
?>

<?php do_action( 'bp_before_member_header' ); ?>
<?php if(bp_displayed_user_id()==bp_loggedin_user_id()){?>
<a id="settings-img" href='modifier-mon-compte'> <img src="<?php echo get_stylesheet_directory_uri() . "/images/settings.png";?>"></a>
<?php } ?>
<span id="user-id" data-value="<?php echo bp_displayed_user_id(); ?>"></span>
<div id="item-header-avatar" class="left">
    <a href="<?php bp_displayed_user_link(); ?>">
	<?php print_user_avatar(bp_displayed_user_id()); ?>
    </a>
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="left">

    <h1>
	<a href="<?php bp_displayed_user_link(); ?>"><?php echo $current_user->display_name; ?></a>
    </h1>
     <?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
	<span class="user-nicename">@<?php bp_displayed_user_username(); ?></span>
    <?php 
    endif; ?>
    <?php 
   
    $user_meta = get_userdata(bp_displayed_user_id());
			echo($user_meta->description);
			?>

   

	<?php do_action( 'bp_before_member_header_meta' ); ?>

	<div id="item-meta">

		<div id="item-buttons">

			<?php do_action( 'bp_member_header_actions' ); ?>

		</div><!-- #item-buttons -->

		<?php
		// If you'd like to show specific profile fields here use:  bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
		 do_action( 'bp_profile_header_meta' );
		 ?>

	</div><!-- #item-meta -->

</div><!-- #item-header-content -->

<?php /*
<div id="item-header-stats" class="left">
    <?php  
	global $wp_query;
	
	$nb_project_created = 0;
	$posts_temp = query_posts( array(
	    'post_type' => 'download',
	    'author' => bp_displayed_user_id()
	) );
	if ($posts_temp) $nb_project_created = $wp_query->found_posts;
	
	$nb_project_founded = 0;
	$purchases = edd_get_users_purchases(bp_current_user_id());
	if ($purchases) $nb_project_founded = count($purchases);
    ?>
    <ul>
	<li><strong><?php echo $nb_project_created; ?></strong> <?php _e("projet(s) lanc&eacute;(s)", "yproject"); ?></li>
	<li><strong><?php echo $nb_project_founded; ?></strong> <?php _e("projet(s) soutenu(s)", "yproject"); ?></li>
    </ul>
    <?php bp_follow_add_follow_button(); ?>
</div>*/ ?>
	<?php /*<li><strong><?php echo mycred_get_users_cred(bp_displayed_user_id()); ?></strong> <?php _e("points", "yproject"); ?></li>*/ ?>


<div style="clear: both"></div>

<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>