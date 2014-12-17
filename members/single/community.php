<?php 
$following_count = 0;
$following_list_str = bp_get_following_ids();
if ($following_list_str) { 
	$following_list = explode(',' , $following_list_str);
	$following_count = count($following_list);
}
$followers_count = 0;
$followers_list_str = bp_get_follower_ids();
if ($followers_list_str) { 
	$followers_list = explode(',' , bp_get_follower_ids());
	$followers_count = count($followers_list);
}
if($followers_count>0){ ?>
	<h2 class="underlined">Abonnés</h2>
	<ul>
<?php
	for ($i = 0; $i < $followers_count; $i++) {
		$user_temp = get_userdata($followers_list[$i]);
		$user_avatar = UIHelpers::get_user_avatar($user_temp->ID, "thumb");
		if (isset($user_temp->ID)) echo '<li><a href="' . bp_core_get_userlink($user_temp->ID, false, true) . '">'. $user_avatar . $user_temp->display_name . '</a></li>';
	}
?>
	</ul>
<?php } ?>

<?php
if($following_count>0){ ?>
<h2 class="underlined">Abonnements</h2>
<ul>
<?php
	for ($i = 0; $i < $following_count; $i++) {
		$user_temp = get_userdata($following_list[$i]);
		$user_avatar = UIHelpers::get_user_avatar($user_temp->ID, "thumb");
		if (isset($user_temp->ID)) echo '<li><a href="' . bp_core_get_userlink($user_temp->ID, false, true) . '">'. $user_avatar . $user_temp->display_name . '</a></li>';
	}
?>
</ul>
<?php } ?>

<h2 class="underlined">Groupes</h2>
<?php
$groups_list = groups_get_user_groups(bp_displayed_user_id());
$str_groups = '';
if ($groups_list['total'] > 0) { 
	$display_loggedin_user = (bp_loggedin_user_id() == bp_displayed_user_id());
	foreach ($groups_list['groups'] as $group_item) {
		$group_object = groups_get_group(array('group_id' => $group_item));
		$group_link = bp_get_group_permalink($group_object);
		if (($group_object->status == 'public' || $display_loggedin_user) && groups_get_groupmeta($group_item, 'group_type') !== 'organisation') {
			$str_groups .= '<li><a href="' . $group_link . '">' . $group_object->name . '</a></li>';
		}
	}
}
	
if ($str_groups != '') {
	echo '<ul>' . $str_groups . '</ul>';
} else {
?>
	Aucun groupe.
<?php
}
?>


<h2 class="underlined">Organisations</h2>

<?php $page_new_orga = get_page_by_path('creer-une-organisation'); ?>
<div class="right">
	<a href="<?php echo get_permalink($page_new_orga->ID); ?>" class="button right">Cr&eacute;er une organisation</a>
</div>


<?php /*
global $current_user;
$group_ids = BP_Groups_Member::get_group_ids( $current_user->ID );
$page_edit_orga = get_page_by_path('editer-une-organisation');
$str_organisations = '';
foreach ($group_ids['groups'] as $group_id) {
	$group = groups_get_group( array( 'group_id' => $group_id ) );
	$group_type = groups_get_groupmeta($group_id, 'group_type');
	if ($group->status == 'private' && $group_type == 'organisation' && BP_Groups_Member::check_is_admin($current_user->ID, $group_id)) {
		$group = groups_get_group( array( 'group_id' => $group_id ) );
		$str_organisations .= '<li><a href="'.  get_permalink($page_edit_orga->ID) .'?orga_id='.$group_id.'">' .$group->name. '</a></li>';
	}
}
if ($str_organisations != ''): ?>
	<ul><?php echo $str_organisations; ?></ul>

<?php else: ?>
	<?php _e('Aucune organisation.', 'yproject'); ?>

<?php endif; */ ?>

<div class="clear"></div>

<?php 
/*
$default_query = array(
    'author'         => $user_id,
    'show_stickies'  => false,
    'order'          => 'DESC',
);

// Try to get the topics
	$query = bbp_has_topics( array(
		'author' => bp_displayed_user_id()
	) );

print_r( $query);
//print_r(bbp_get_user_topics_started(bp_displayed_user_id()));
 * 
 */
?>

<br /><br />