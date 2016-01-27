<?php
global $campaign, $stylesheet_directory_uri, $current_user, $language_list;
$menu_project_parts = array (
	'banner'		=> 'R&eacute;sum&eacute;',
	'rewards'		=> 'Investissement',
	'description'	=> 'Pr&eacute;sentation',
	'news'			=> 'Actualit&eacute;s'
);

$page_invest = get_page_by_path('investir');
$campaign_id_param = '?campaign_id=' . $campaign->ID;
$invest_url = get_permalink($page_invest->ID) . $campaign_id_param . '&amp;invest_start=1';
$invest_url_href = "#connexion";
$btn_invest_classes = 'wdg-button-lightbox-open';
$btn_invest_data_lightbox = 'connexion';
$btn_invest_text = ($campaign->funding_type() == 'fundingdonation') ? __('Soutenir', 'yproject') : __('Investir', 'yproject');

$user_name_str = '';

$current_lang = get_locale();
$campaign->set_current_lang($current_lang);
$lang_list = $campaign->get_lang_list();

if (is_user_logged_in()) {
	get_currentuserinfo();
	$user_name_str = $current_user->user_firstname;
	if ($user_name_str == '') {
		$user_name_str = $current_user->user_login;
	}
	
	$invest_url_href = $invest_url;
	
	$btn_invest_classes = '';
	$btn_invest_data_lightbox = '';
}
?>

<nav class="project-navigation">
	<div class="center clearfix">
		<ul class="menu-project campaign-mobile-hidden">
			<?php foreach ($menu_project_parts as $menu_part_key => $menu_part_label): ?>
				<li><a href="#" id="target-<?php echo $menu_part_key; ?>" data-target="<?php echo $menu_part_key; ?>"><?php _e($menu_part_label, 'yproject'); ?></a></li>
			<?php endforeach; ?>
		</ul>

		<ul class="menu-actions <?php if (!empty($lang_list)): ?>haslangs<?php endif;?>">
			<li class="action-item">
			<?php
			$campaign_status = $campaign->campaign_status();
			switch ($campaign_status) {
				case 'vote': ?>
				<a href="#voteform">
					<?php _e('Voter', 'yproject'); ?>
				</a>

				<?php
				break;
				case 'collecte':
				?>
				<a href="<?php echo $invest_url_href; ?>" class="<?php echo $btn_invest_classes; ?>" data-lightbox="<?php echo $btn_invest_data_lightbox; ?>" data-redirect="<?php echo $invest_url; ?>">
					<?php echo $btn_invest_text; ?>
				</a>
				<?php break;
			} ?>
			</li>
			
			<?php if (!empty($lang_list)): ?>
			<li class="lang-item">
				<form method="GET" action="<?php the_permalink(); ?>">
					<select name="lang">
						<option value="fr_FR" <?php selected($current_lang , "fr_FR"); ?>>Fran&ccedil;ais</option>
						<?php foreach ($lang_list as $lang): ?>
						<option value="<?php echo $lang; ?>" <?php selected($current_lang, $lang); ?>><?php echo $language_list[$lang]; ?></option>
						<?php endforeach; ?>
					</select>
				</form>
			</li>
			<?php endif; ?>
		</ul>
	</div>
</nav>