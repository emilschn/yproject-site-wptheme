<?php
global $campaign;
$page_dashboard = get_page_by_path('tableau-de-bord');	// Tableau de bord
$page_wallet = get_page_by_path('gestion-financiere');	// Gestion financière
$campaign_id_param = '?campaign_id=' . $campaign->ID;
?>
<div class="project-admin"
		data-link-project-settings="<?php echo get_permalink(get_page_by_path('parametres-projet')->ID) . $campaign_id_param; ?>">
	<a href="<?php echo get_permalink($page_dashboard->ID) . $campaign_id_param; ?>"><?php _e('Tableau de bord', 'yproject'); ?></a>
	<a href="<?php echo get_permalink($page_wallet->ID) . $campaign_id_param; ?>"><?php _e('Gestion financi&egrave;re', 'yproject'); ?></a>
</div>