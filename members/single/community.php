 
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
<?php
	for ($i = 0; $i < $followers_count; $i++) {
		$user_temp = get_userdata($followers_list[$i]);
		$user_avatar=get_user_avatar($user_temp->ID);
		echo '<li><a href="' . bp_core_get_userlink($user_temp->ID, false, true) . '">'. $user_avatar . $user_temp->display_name . '</a></li>';
	}
}
?>	

<?php
if($following_count>0){ ?>
<h2 class="underlined">Abonnements</h2>
<?php
	for ($i = 0; $i < $following_count; $i++) {
			$user_temp = get_userdata($following_list[$i]);
			$user_avatar=get_user_avatar($user_temp->ID);
			echo '<li><a href="' . bp_core_get_userlink($user_temp->ID, false, true) . '">'. $user_avatar . $user_temp->display_name . '</a></li>';

	}
}
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
?>	