<?php
global $campaign, $language_list;
$page_dashboard = get_page_by_path('tableau-de-bord');	// Tableau de bord
$page_wallet = get_page_by_path('gestion-financiere');	// Gestion financière
$campaign_id_param = '?campaign_id=' . $campaign->ID;
?>
<div class="project-admin" data-link-project-settings="<?php echo get_permalink(get_page_by_path('parametres-projet')->ID) . $campaign_id_param; ?>">
	<a href="<?php echo get_permalink($page_dashboard->ID) . $campaign_id_param; ?>" class="btn-dashboard"><?php _e('Tableau de bord', 'yproject'); ?></a>
	<a href="<?php echo get_permalink($page_wallet->ID) . $campaign_id_param; ?>" class="btn-wallet"><?php _e('Gestion financi&egrave;re', 'yproject'); ?></a>
	<div id="wdg-edit-project" class="edit-button"></div>
	<form id="wdg-edit-project-add-lang" method="POST" action="<?php echo get_permalink($campaign->ID); ?>">
		<span>+ <?php _e('Nouvelle langue', 'yproject'); ?></span>
		<select name="selected-language">
			<?php foreach ($language_list as $language_key => $language_label): if ($language_key != 'fr_FR'): ?>
			<option value="<?php echo $language_key; ?>"><?php echo $language_label; ?></option>
			<?php endif; endforeach; ?>
		</select>
		<button type="submit" class="add-button">+</button>
	</form>
</div>