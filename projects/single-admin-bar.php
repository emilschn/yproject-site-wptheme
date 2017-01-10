<?php
global $post, $campaign_id, $can_modify, $show_admin_bar;
if (!isset($campaign_id)) {
    if (isset($_GET['campaign_id'])) $campaign_id = $_GET['campaign_id'];
    else $campaign_id = get_the_ID();
}
$post_campaign = get_post($campaign_id);

if ($can_modify) {
	$show_admin_bar = TRUE;
	$campaign = atcf_get_campaign($post_campaign);
        $params_full = ''; $params_partial = '';
        if (isset($_GET['preview']) && $_GET['preview'] = 'true') { $params_full = '?preview=true'; $params_partial = '&preview=true'; }
        $campaign_id_param = '?campaign_id=';
        $campaign_id_param .= $campaign_id;                             // Page projet
        $page_dashboard = get_page_by_path('tableau-de-bord');          // Tableau de bord
		$page_wallet = get_page_by_path('gestion-financiere');		// Gestion financière
       
        //Récupération de la page en cours
        $current_page = 'project';
        if (isset($post->post_name)) $current_page = $post->post_name;
?>
        <div id="single_project_admin_bar">
			<div class="center">
				<a href="<?php echo get_permalink($page_dashboard->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'tableau-de-bord') { echo 'class="selected"'; } ?>><?php _e('Tableau de bord', 'yproject'); ?></a>
				|
				<a href="<?php echo get_permalink($campaign_id) . $params_full; ?>" <?php if ($current_page == $post_campaign->post_name) { echo 'class="selected"'; } ?>><?php _e('Page projet', 'yproject'); ?></a>

				|
				<a href="<?php echo get_permalink($page_wallet->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'gestion-financiere') { echo 'class="selected"'; } ?>><?php _e('Gestion financi&egrave;re', 'yproject'); ?></a>
			</div>
        </div>
<?php }