<?php 
global $post, $campaign_id, $can_modify;
if (!isset($campaign_id)) {
    if (isset($_GET['campaign_id'])) $campaign_id = $_GET['campaign_id'];
    else $campaign_id = get_the_ID();
}
$post_campaign = get_post($campaign_id);

locate_template( array("requests/projects.php"), true );
$can_modify = YPProjectLib::current_user_can_edit($campaign_id);

if ($can_modify) {
	$params_full = ''; $params_partial = '';
	if (isset($_GET['preview']) && $_GET['preview'] = 'true') { $params_full = '?preview=true'; $params_partial = '&preview=true'; }
	$campaign_id_param = '?campaign_id=';
	$campaign_id_param .= $campaign_id;				// Page projet
	$page_dashboard = get_page_by_path('tableau-de-bord');		// Tableau de bord
	$page_manage = get_page_by_path('gerer');			// Gérer le projet
	$page_add_news = get_page_by_path('ajouter-une-actu');		// Ajouter une actualité
	$page_manage_team = get_page_by_path('projet-gerer-equipe');	// Editer l'équipe
	// Statistiques avancées
	if (strtotime($post_campaign->post_date) < strtotime('2014-02')) {
	    $pages_stats = get_page_by_path('vote'); 
	} else {
	    $pages_stats = get_page_by_path('statistiques-avancees');
	}
	
	
	//Lien vers le groupe d'investisseurs du projet
	//Visible si le groupe existe et que l'utilisateur est bien dans ce groupe
	$investors_group_id = get_post_meta($campaign_id, 'campaign_investors_group', true);
	$group_link = '';
	$group_exists = (is_numeric($investors_group_id) && ($investors_group_id > 0));
	$is_user_group_member = groups_is_user_member(bp_loggedin_user_id(), $investors_group_id);
	if ($group_exists && $is_user_group_member) {
	    $group_obj = groups_get_group(array('group_id' => $investors_group_id));
	    $group_link = bp_get_group_permalink($group_obj);
	}
	
	//Récupération de la page en cours
	$current_page = 'project';
	if (isset($post->post_name)) $current_page = $post->post_name;
	if (bp_is_group()) $current_page = 'group';
?>
	<div id="single_project_admin_bar">
		<div class="center">
			<a href="<?php echo get_permalink($page_dashboard->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'tableau-de-bord') { echo 'class="selected"'; } ?>><?php echo __('Tableau de bord', 'yproject'); ?></a>
			|
			<a href="<?php echo get_permalink($campaign_id) . $params_full; ?>" <?php if ($current_page == $post_campaign->post_name) { echo 'class="selected"'; } ?>><?php echo __('Page projet', 'yproject'); ?></a>
			|
			<a href="<?php echo get_permalink($page_manage->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'gerer') { echo 'class="selected"'; } ?>>Editer le projet</a>
			|
			<a href="<?php echo get_permalink($page_manage_team->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'projet-gerer-equipe') { echo 'class="selected"'; } ?>><?php echo __('&Eacute;quipe', 'yproject'); ?></a>
			|
			<a href="<?php echo get_permalink($pages_stats->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'vote' || $current_page == 'statistiques-avancees') { echo 'class="selected"'; } ?>>Statistiques avanc&eacute;es</a>
			<?php if ($group_link != '') : ?>
			|
			<a href="<?php echo $group_link; ?>" <?php if ($current_page == 'group') { echo 'class="selected"'; } ?>>Groupe d&apos;investisseurs</a>
			<?php endif; ?>
		</div>
	    
		<?php
		//Sous-menu avec statistiques avancées
		if ($current_page == 'statistiques-avancees' || $current_page == 'statistiques-avancees-votes' || $current_page == 'statistiques-avancees-investissements') {
			$pages_stats_votes = get_page_by_path('statistiques-avancees-votes');
			$pages_stats_investments = get_page_by_path('statistiques-avancees-investissements');
		?>
			<div class="center">
				<a href="<?php echo get_permalink($pages_stats->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'statistiques-avancees') { echo 'class="selected"'; } ?>>G&eacute;n&eacute;rales</a>
				&nbsp; &nbsp; &nbsp;
				<a href="<?php echo get_permalink($pages_stats_votes->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'statistiques-avancees-votes') { echo 'class="selected"'; } ?>>Votes</a>
				&nbsp; &nbsp; &nbsp;
				<a href="<?php echo get_permalink($pages_stats_investments->ID) . $campaign_id_param . $params_partial; ?>" <?php if ($current_page == 'statistiques-avancees-investissements') { echo 'class="selected"'; } ?>>Investissements</a>
			</div>
		<?php	
		}
		?>
	</div>
<?php } ?>