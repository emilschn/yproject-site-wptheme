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


<?php
$page_edit_orga = get_page_by_path('editer-une-organisation');
$can_edit = (bp_displayed_user_id() == bp_loggedin_user_id() || current_user_can('manage_options'));
global $current_user;
$wdg_current_user = new WDGUser( bp_displayed_user_id() );
$api_user_id = $wdg_current_user->get_api_id();
$organizations_list = WDGWPREST_Entity_User::get_organizations_by_role($api_user_id, WDGWPREST_Entity_Organization::$link_user_type_creator);
if (!empty($organizations_list)) {
	foreach ($organizations_list as $organization_item) {
		$str_organizations .= '<li>';
		if ($can_edit) { $str_organizations .= '<a href="'.  get_permalink($page_edit_orga->ID) .'?orga_id='.$organization_item->wpref.'">'; }
		$str_organizations .= $organization_item->name; 
		if ($can_edit) { $str_organizations .= '</a>'; }
		$str_organizations .= '</li>';
	}
}
if ($str_organizations != ''): ?>
	<ul><?php echo $str_organizations; ?></ul>

<?php else: ?>
	<?php _e('Aucune organisation.', 'yproject'); ?>

<?php endif; ?>
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