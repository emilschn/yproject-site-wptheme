<?php
global $campaign, $stylesheet_directory_uri, $current_user;
$menu_project_parts = array (
	'banner'		=> 'R&eacute;sum&eacute;',
	'rewards'		=> 'Investissement',
	'description'	=> 'Pr&eacute;sentation',
	'news'			=> 'Actualit&eacute;s'
);

$WDGUser_current = WDGUser::current();
$invest_url = home_url( '/investir/?campaign_id=' .$campaign->ID. '&amp;invest_start=1' );
$invest_url_href = home_url( '/connexion/' ) . '?source=project&redirect-invest=' .$campaign->ID;
$user_name_str = '';

if (is_user_logged_in()) {
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
					<?php if ( is_user_logged_in() && $WDGUser_current->has_voted_on_campaign( $campaign->ID ) ): ?>
						<a href="#preinvest-warning" class="button red wdg-button-lightbox-open" data-lightbox="preinvest-warning"><?php _e( "Pr&eacute;-investir", 'yproject' ); ?></a>

					<?php elseif ( $campaign->time_remaining_str() != '-' ): ?>
						<?php if ( !is_user_logged_in() ): ?>
							<a href="<?php echo home_url( '/connexion/' ) . '?source=project'; ?>" class="button red">
								<?php _e('&Eacute;valuer', 'yproject'); ?>
							</a>

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
					<?php if ( $campaign->is_investable() ): ?>
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