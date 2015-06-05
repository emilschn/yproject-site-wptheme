<?php
if (isset($_GET['campaign_id'])){
    $campaign_id = $_GET['campaign_id'];
    $post_camp = get_post($campaign_id);
}

$stats_views = 0;
$stats_views_30days = 0;
$stats_views_7days = 0;
$stats_views_today = 0;
if (function_exists('stats_get_csv')) {
	global $wpdb;
	
	//Nombres de vues
	$stats_views = stats_get_csv( 'postviews', array( 'post_id' => $post_camp->ID, 'days' => 365 ) );
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
$nb_users = 0;
if (function_exists('bbp_get_forum_topic_count')) {
	//Forum
	$table_name = $wpdb->prefix . "posts";
	$forum_results = $wpdb->get_results("SELECT ID FROM ".$table_name." WHERE post_type='forum' AND post_name=".$campaign_id."");
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

//Stats facebook 
$fb_share_count = 0;
$fb_like_count = 0;
$ch = curl_init();

$url_fb_stats = 'https://graph.facebook.com/fql?q=SELECT%20url,%20normalized_url,%20share_count,%20like_count,%20comment_count,%20total_count,commentsbox_count,%20comments_fbid,%20click_count%20FROM%20link_stat%20WHERE%20url=%27'
        .get_permalink($campaign_id).'%27';

curl_setopt($ch, CURLOPT_URL, $url_fb_stats);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$json = curl_exec($ch);

if(!curl_errno($ch)){
        $json_fb = (json_decode($json));
        if(isset($json_fb->data[0])){
            $stats_fb = ($json_fb->data[0]);
            if(isset($stats_fb->share_count)){
                    $fb_share_count = $stats_fb->share_count;
            }
            if(isset($stats_fb->like_count)){
                    $fb_like_count = $stats_fb->like_count;
            }
        }
}
curl_close($ch);

?>

<h2>Audience et interactions</h2>
Votre projet a &eacute;t&eacute; vu<br />
<strong><?php echo $stats_views[0]['views']; ?></strong> fois en cette année, dont :<br />
<strong><?php echo $stats_views_30days[0]['views']; ?></strong> fois sur les 30 derniers jours.<br />
<strong><?php echo $stats_views_7days[0]['views']; ?></strong> fois sur les 7 derniers jours.<br />
<strong><?php echo $stats_views_today[0]['views']; ?></strong> fois aujourd&apos;hui.<br />

<h2>Forum</h2>
<strong><?php echo $forum_topic_count; ?></strong> sujets ont &eacute;t&eacute; ouverts.<br />
<?php if ($forum_topic_count > 0): ?>
<strong><?php echo $forum_post_count; ?></strong> messages ont &eacute;t&eacute; post&eacute;s.<br />
<strong><?php echo $nb_users; ?></strong> personnes ont particip&eacute;.<br />
<a href="<?php echo $forum_last_reply_url; ?>">Derni&egrave;re r&eacute;ponse</a> par <?php echo $forum_last_user_name; ?>. <?php echo $forum_last_activity; ?>.
<br /><br />
<?php endif; ?>

<h2>R&eacute;seaux sociaux</h2>
<h3>Facebook</h3>
La page projet a été partagée <strong><?php echo $fb_share_count?></strong> fois
et a receuilli <strong><?php echo $fb_like_count ?> "J'aime"</strong> <br />

<?php if (current_user_can('manage_options')) { ?>
<h2>[ADMIN] E-mails des utilisateurs qui croient ou qui ont vot&eacute;</h2>

<div id="ajax-email-selector-load" class="ajax-investments-load" style="text-align: center;" data-value="<?php echo $campaign_id ?>">
    <img id="ajax-email-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

<?php } ?>
<br /><br />
