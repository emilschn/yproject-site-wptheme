<?php
global $campaign, $stylesheet_directory_uri, $current_user;
$menu_project_parts = array (
	'banner'		=> 'R&eacute;sum&eacute;',
	'rewards'		=> 'Investissement',
	'description'	=> 'Pr&eacute;sentation',
	'news'			=> 'Actualit&eacute;s'
);


$page_invest = get_page_by_path('investir');
$campaign_id_param = '?campaign_id=' . $campaign->ID;
$invest_url = get_permalink($page_invest->ID) . $campaign_id_param . '&amp;invest_start=1';
$invest_url_href = home_url( '/connexion/' ) . '?source=project';
$user_name_str = '';

if (is_user_logged_in()) {
	get_currentuserinfo();
	$user_name_str = $current_user->user_firstname;
	if ($user_name_str == '') {
		$user_name_str = $current_user->user_login;
	}
	
	$invest_url_href = $invest_url;
}
?>

<nav class="project-navigation">
	<div class="padder">
		<ul class="menu-project campaign-mobile-hidden">
			<li class="project-navigation-logo"><a href="#" data-target="banner"><img src="<?php echo $stylesheet_directory_uri; ?>/images/navbar/grenade-gris-fonce.png" alt="logo noir" style="width: 36px"/></a></li>
			<li class="project-navigation-title"><?php echo $campaign->data->post_title; ?></li>
			<?php foreach ($menu_project_parts as $menu_part_key => $menu_part_label): ?>
				<li class="slashed"><a href="#" id="target-<?php echo $menu_part_key; ?>" data-target="<?php echo $menu_part_key; ?>"><?php _e($menu_part_label, 'yproject'); ?></a></li>
			<?php endforeach; ?>
		</ul>

		<ul class="menu-actions">
			<li class="action-item">
			<?php
			$campaign_status = $campaign->campaign_status();
			switch ($campaign_status) {
				case ATCF_Campaign::$campaign_status_vote: ?>
					<?php if ($campaign->time_remaining_str() != '-'): ?>
					<?php
					$table_name = $wpdb->prefix . "ypcf_project_votes";
					$campaign_id=$campaign->ID;
					$user_id = wp_get_current_user()->ID;

					$hasvoted_results = $wpdb->get_results( 'SELECT id FROM '.$table_name.' WHERE post_id = '.$campaign_id.' AND user_id = '.$user_id );
					$has_voted = false;
					if ( !empty($hasvoted_results[0]->id) ) $has_voted = true;
					?>

					<?php if (!is_user_logged_in()): ?>
						<a href="<?php echo home_url( '/connexion/' ) . '?source=project'; ?>" class="button red"
							data-redirect="<?php echo get_permalink($page_invest->ID) . $campaign_id_param; ?>&amp;invest_start=1#invest-start">
							<?php _e('&Eacute;valuer', 'yproject'); ?>
						</a>

					<?php elseif ($has_voted): ?>
						<div class="disabled">
							<?php _e('Merci pour votre &eacute;valuation !', 'yproject'); ?>
						</div>

					<?php else: ?>
					<div>
						<a href="#vote" class="button red wdg-button-lightbox-open" data-lightbox="vote" data-thankyoumsg="<?php _e( "Merci pour votre &eacute;valuation !", 'yproject' ); ?>">
							<?php _e('&Eacute;valuer', 'yproject'); ?>
						</a>
					</div>
					<?php endif; ?>

					<?php endif; ?>

				<?php
				break;
				case ATCF_Campaign::$campaign_status_collecte:
				?>
					<?php if ( $campaign->time_remaining_str() != '-' && $campaign->percent_completed( false ) < 100 ): ?>
					<a href="<?php echo $invest_url_href; ?>" class="button red">
						<?php _e( "Investir", 'yproject' ); ?>
					</a>
					<?php endif; ?>
				<?php break;
			} ?>
			</li>
		</ul>
	</div>
</nav>