<?php
$WDGUser_current = WDGUser::current();
$displayed_user = get_user_by('id', $WDGUser_current->wp_user->ID);
?>

<div id="item-header-avatar" class="left">
    
	<a href="<?php echo home_url('/mon-compte/'); ?>"><?php UIHelpers::print_user_avatar($WDGUser_current->wp_user->ID); ?></a>
	
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="left">

	<h1><a href="<?php echo home_url('/mon-compte/'); ?>"><?php echo $displayed_user->display_name; ?></a></h1>

	<span class="user-nicename" id="user-id" data-value="<?php echo $WDGUser_current->wp_user->ID; ?>"><?php echo $WDGUser_current->wp_user->user_login; ?></span>

	<?php
	$user_meta = get_userdata($WDGUser_current->wp_user->ID);
	echo $user_meta->description;
	?>

</div><!-- #item-header-content -->

<div style="clear: both"></div>

<?php do_action( 'template_notices' ); ?>