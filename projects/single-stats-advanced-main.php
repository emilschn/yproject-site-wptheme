<?php
if (isset($_GET['campaign_id'])) $post_camp = get_post($_GET['campaign_id']);

$stats_views = 0;
$stats_views_30days = 0;
$stats_views_7days = 0;
$stats_views_today = 0;
if (function_exists('stats_get_csv')) {
	global $wpdb;
	
	//Nombres de vues
	$stats_views = stats_get_csv( 'postviews', array( 'post_id' => $post_camp->ID, 'days' => -1 ) );
	$stats_views_30days = stats_get_csv( 'postviews', array( 'post_id' => $post_camp->ID, 'days' => 30 ) );
	$stats_views_7days = stats_get_csv( 'postviews', array( 'post_id' => $post_camp->ID, 'days' => 7 ) );
	$stats_views_today = stats_get_csv( 'postviews', array( 'post_id' => $post_camp->ID, 'days' => 1 ) );
	
	//Sources
//	$stats_referrers = stats_get_csv( 'referrers', array( 'days' => 1000, 'limit' => 10 ) );
	
}

$forum_topic_count = 0;
$forum_post_count = 0;
$forum_last_reply_url = '';
$forum_last_reply_author = '';
$forum_last_user = '';
$forum_last_user_name = '';
$forum_last_activity = '';
if (function_exists('bbp_get_forum_topic_count')) {
	//Forum
	$table_name = $wpdb->prefix . "posts";
	$forum_results = $wpdb->get_results("SELECT ID FROM ".$table_name." WHERE post_type='forum' AND post_name=".$_GET['campaign_id']."");
	if (isset($forum_results)) $project_forum_id = $forum_results[0]->ID;
	$forum_topic_count = bbp_get_forum_topic_count($project_forum_id);
	$forum_post_count = bbp_get_forum_post_count($project_forum_id);
	$forum_last_reply_url = bbp_get_forum_last_reply_url($project_forum_id);
	$forum_last_reply_author = bbp_get_forum_last_reply_author_id($project_forum_id);
	$forum_last_user = get_user_by('id', $forum_last_reply_author);
	$forum_last_user_name = $forum_last_user->display_name;
	$forum_last_activity = bbp_get_forum_last_active_time($project_forum_id);
	$topic_ids = bbp_forum_query_topic_ids( $project_forum_id );
	if ( !empty( $topic_ids ) ) {
	    $nb_users = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT post_author) FROM {$wpdb->posts} WHERE post_parent IN ( " . join( ',', $topic_ids ) . " ) AND post_status = '%s' AND (post_type = 'topic' OR post_type = 'reply');", bbp_get_public_status_id() ) );
	}
}
?>

<h2>Audience et int&eacute;ractions</h2>
Votre projet a &eacute;t&eacute; vu<br />
<strong><?php echo $stats_views[0]['views']; ?></strong> fois en tout, dont :<br />
<strong><?php echo $stats_views_30days[0]['views']; ?></strong> fois sur les 30 derniers jours.<br />
<strong><?php echo $stats_views_7days[0]['views']; ?></strong> fois sur les 7 derniers jours.<br />
<strong><?php echo $stats_views_today[0]['views']; ?></strong> fois aujourd&apos;hui.<br />

<h2>Forum</h2>
<strong><?php echo $forum_topic_count; ?></strong> sujets ont &eacute;t&eacute; ouverts.<br />
<strong><?php echo $forum_post_count; ?></strong> messages ont &eacute;t&eacute; post&eacute;s.<br />
<strong><?php echo $nb_users; ?></strong> personnes ont particip&eacute;.<br />
<a href="<?php echo $forum_last_reply_url; ?>">Derni&egrave;re r&eacute;ponse</a> par <?php echo $forum_last_user_name; ?>. <?php echo $forum_last_activity; ?>.
<br /><br />