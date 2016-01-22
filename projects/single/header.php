<?php
global $campaign, $stylesheet_directory_uri, $current_user, $language_list;
$menu_hamburger_pages = array(
	'les-projets'	=> 'Les projets',
	'financement'	=> 'Financer son projet',
	'descriptif'	=> 'Comment ca marche ?',
	'blog'			=> 'Actualit&eacute;s'
);
$menu_project_parts = array (
	'banner'		=> 'R&eacute;sum&eacute;',
	'rewards'		=> 'Contreparties',
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

$class_loggedin = '';
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
	$class_loggedin = 'loggedin';
}
?>

<nav class="project-navigation">
	<div class="center clearfix">
		<ul class="menu-hamburger">
			<li>
				<a href="#" class="trigger-menu" data-target="hamburger"><img src="<?php echo $stylesheet_directory_uri; ?>/images/menu-smartphone.png" title="Burger" /></a>
			</li>

			<li id="triggered-menu-hamburger" class="triggered-menu">
				<ul>
					<li><a href="<?php echo home_url(); ?>"><?php _e('Accueil', 'yproject'); ?></a></li>

					<?php foreach ($menu_hamburger_pages as $menu_page_key => $menu_page_label): $menu_page_object = get_page_by_path($menu_page_key); ?>
						<li><a href="<?php echo get_permalink($menu_page_object->ID); ?>"><?php _e($menu_page_label, 'yproject'); ?></a></li>
					<?php endforeach; ?>

					<?php if (is_user_logged_in()): ?>
						<li><a href="<?php echo bp_loggedin_user_domain(); ?>"><?php _e('Mon compte', 'yproject'); ?></a></li>
					<?php else: $page_connexion = get_page_by_path('connexion'); ?>
						<li><a href="#connexion" class="wdg-button-lightbox-open" data-lightbox="connexion" data-redirect="<?php echo get_permalink(); ?>"><?php _e('Connexion', 'yproject'); ?></a></li>
					<?php endif; ?>
				</ul>
			</li>
		</ul>

		<ul class="menu-project campaign-mobile-hidden">
			<?php foreach ($menu_project_parts as $menu_part_key => $menu_part_label): ?>
				<li><a href="#" data-target="<?php echo $menu_part_key; ?>"><?php _e($menu_part_label, 'yproject'); ?></a></li>
			<?php endforeach; ?>
		</ul>

		<ul class="menu-actions <?php echo $class_loggedin; ?>">
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
			
			<li class="login-item">
				<?php if (!empty($lang_list)): ?>
				<form method="GET" action="<?php the_permalink(); ?>">
					<select name="lang">
						<option value="fr_FR" <?php selected($current_lang , "fr_FR"); ?>>Fran&ccedil;ais</option>
						<?php foreach ($lang_list as $lang): ?>
						<option value="<?php echo $lang; ?>" <?php selected($current_lang, $lang); ?>><?php echo $language_list[$lang]; ?></option>
						<?php endforeach; ?>
					</select>
				</form>
				<?php endif; ?>
			</li>
		</ul>
	</div>
</nav>