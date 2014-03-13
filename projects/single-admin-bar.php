<?php 
// La barre d'admin n'apparait que pour l'admin du site et pour l'admin de la page
$current_user = wp_get_current_user();
$current_user_id = $current_user->ID;

if (isset($_GET['campaign_id'])) $campaign_id = $_GET['campaign_id'];
else $campaign_id = get_the_ID();
$post = get_post($campaign_id);

if ($current_user_id == $post->post_author || current_user_can('manage_options')) {
	$campaign_id_param = '?campaign_id=';
	$campaign_id_param .= $campaign_id;	    // Page projet
	$page_manage = get_page_by_path('gerer');   // Gérer le projet
	$page_add_news = get_page_by_path('ajouter-une-actu');	// Ajouter une actualité
	$vote = get_page_by_path('vote');	    // Statistiques avancées
?>
	<div id="yp_admin_bar">
		<div class="center">
			<a href="<?php echo get_permalink($campaign_id); ?>"><?php echo __('Page projet', 'yproject'); ?></a>
			&nbsp; &nbsp; &nbsp;
			<a href="<?php echo get_permalink($page_manage->ID); ?><?php echo $campaign_id_param; ?>">G&eacute;rer le projet</a>
			&nbsp; &nbsp; &nbsp;
			<a href="<?php echo get_permalink($page_add_news->ID); ?><?php echo $campaign_id_param; ?>"><?php echo __('Ajouter une actualit&eacute', 'yproject'); ?></a>
			 &nbsp; &nbsp; &nbsp;
			<a href="<?php echo get_permalink($vote->ID); ?><?php echo $campaign_id_param; ?>">Statistiques avanc&eacute;es</a>
		</div>
	</div>
<?php } ?>