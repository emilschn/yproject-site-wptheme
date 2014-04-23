<?php 
// La barre d'admin n'apparait que pour l'admin du site et pour l'admin de la page
$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;

if (isset($_GET['campaign_id'])) $campaign_id = $_GET['campaign_id'];
else $campaign_id = get_the_ID();
$old_post = $post;
$post = get_post($campaign_id);

if ($current_user_id == $post->post_author || current_user_can('manage_options')) {
	$params_full = ''; $params_partial = '';
	if (isset($_GET['preview']) && $_GET['preview'] = 'true') { $params_full = '?preview=true'; $params_partial = '&preview=true'; }
	$campaign_id_param = '?campaign_id=';
	$campaign_id_param .= $campaign_id;	    // Page projet
	$page_manage = get_page_by_path('gerer');   // Gérer le projet
	$page_add_news = get_page_by_path('ajouter-une-actu');	// Ajouter une actualité
	$vote = get_page_by_path('vote');	    // Statistiques avancées
	
	
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
	
	
?>
	<div id="yp_admin_bar">
		<div class="center">
			<a href="<?php echo get_permalink($campaign_id) . $params_full; ?>"><?php echo __('Page projet', 'yproject'); ?></a>
			&nbsp; &nbsp; &nbsp;
			<a href="<?php echo get_permalink($page_manage->ID) . $campaign_id_param . $params_partial; ?>">G&eacute;rer le projet</a>
			&nbsp; &nbsp; &nbsp;
			<a href="<?php echo get_permalink($page_add_news->ID) . $campaign_id_param . $params_partial; ?>"><?php echo __('Ajouter une actualit&eacute', 'yproject'); ?></a>
			 &nbsp; &nbsp; &nbsp;
			<a href="<?php echo get_permalink($vote->ID) . $campaign_id_param . $params_partial; ?>">Statistiques avanc&eacute;es</a>
			<?php if ($group_link != '') : ?>
			 &nbsp; &nbsp; &nbsp;
			<a href="<?php echo $group_link; ?>">Groupe d&apos;investisseurs</a>
			<?php endif; ?>
		</div>
	</div>
<?php }
$post = $old_post; 
?>