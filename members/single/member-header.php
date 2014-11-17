<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

$displayed_user = get_user_by('id', bp_displayed_user_id());
?>

<div id="item-header-avatar" class="left">
    
	<a href="<?php bp_displayed_user_link(); ?>"><?php UIHelpers::print_user_avatar(bp_displayed_user_id()); ?></a>
	
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="left">

	<h1><a href="<?php bp_displayed_user_link(); ?>"><?php echo $displayed_user->display_name; ?></a></h1>

	<span class="user-nicename" id="user-id" data-value="<?php echo bp_displayed_user_id(); ?>">@<?php bp_displayed_user_username(); ?></span>

	<?php
	$user_meta = get_userdata(bp_displayed_user_id());
	echo $user_meta->description;
	?>

</div><!-- #item-header-content -->

<div style="clear: both"></div>

<?php do_action( 'template_notices' ); ?>