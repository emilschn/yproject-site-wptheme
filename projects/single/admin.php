<?php
global $campaign, $language_list;
$WDGUser_current = WDGUser::current();
$page_dashboard = home_url( '/tableau-de-bord/?campaign_id=' . $campaign->ID );	// Tableau de bord
?>
<div class="project-admin">
	<a href="<?php echo $page_dashboard; ?>" class="btn-dashboard"><?php _e('Tableau de bord', 'yproject'); ?></a>

	<?php if ( $WDGUser_current->is_admin() || $campaign->is_preparing() || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_vote || $campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte ): ?>
		<div id="wdg-edit-project" class="btn-edit"></div>
		<?php if ( $WDGUser_current->is_admin() ): ?>
		<button id="wdg-send-project-notification-to-project" class="wdg-send-project-notification"><?php _e( "J'ai fini ma relecture", 'yproject'); ?></button>
		<?php else: ?>
		<button id="wdg-send-project-notification-to-wdg" class="wdg-send-project-notification"><?php _e( "J'ai fini ma présentation", 'yproject'); ?></button>
		<?php endif; ?>
		<form id="wdg-edit-project-add-lang" method="POST" action="<?php echo get_permalink($campaign->ID); ?>">
			<span>+ <?php _e('Nouvelle langue', 'yproject'); ?></span>
			<select name="selected-language">
				<?php foreach ($language_list as $language_key => $language_label): if ($language_key != 'fr_FR'): ?>
				<option value="<?php echo $language_key; ?>"><?php echo $language_label; ?></option>
				<?php endif; endforeach; ?>
			</select>
			<button type="submit" class="add-button">+</button>
		</form>
	<?php endif; ?>
</div>