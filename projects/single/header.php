<?php
global $campaign, $stylesheet_directory_uri, $current_user;
$menu_hamburger_pages = array(
	'les-projets'	=> 'Les projets',
	'financement'	=> 'Financer son projet',
	'descriptif'	=> 'Comment ca marche ?',
	'blog'		=> 'Actualit&eacute;s'
);
$menu_project_parts = array (
	'pitch'		=> 'R&eacute;sum&eacute;',
	'rewards'	=> 'Contreparties',
	'description'	=> 'Pr&eacute;sentation',
	'news'		=> 'Actualit&eacute;s'
);

$user_name_str = '';
if (is_user_logged_in()) {
	get_currentuserinfo();
	$user_name_str = $current_user->user_firstname;
	if ($user_name_str == '') {
		$user_name_str = $current_user->user_login;
	}
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
						<li><a href="<?php echo get_permalink($page_connexion->ID); ?>"><?php _e('Connexion', 'yproject'); ?></a></li>
					<?php endif; ?>
				</ul>
			</li>
		</ul>

		<ul class="menu-project">
			<?php foreach ($menu_project_parts as $menu_part_key => $menu_part_label): ?>
				<li><a href="#" data-target="<?php echo $menu_part_key; ?>"><?php _e($menu_part_label, 'yproject'); ?></a></li>
			<?php endforeach; ?>
		</ul>

		<ul class="menu-actions">
			<li class="login-item">
			<?php if (!empty($user_name_str)): ?>
			<a href="<?php echo bp_loggedin_user_domain(); ?>"><?php _e('Bonjour', 'yproject'); ?> <?php echo $user_name_str; ?></a>
			<?php endif; ?>
			</li>

			<li><a href="">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/good_gris.png" alt="<?php _e('Suivre', 'yproject'); ?>" title="<?php _e('Suivre', 'yproject'); ?>" />
				<?php _e('Suivre', 'yproject'); ?>
			</a></li>

			<li>
			<?php
			$campaign_status = $campaign->campaign_status();
			switch ($campaign_status) {
				case 'vote': ?>
			<a href="">
				<img src="" alt="<?php _e('Voter', 'yproject'); ?>" title="<?php _e('Voter', 'yproject'); ?>" />
				<?php _e('Voter', 'yproject'); ?>
			</a>

				<?php
				break;
				case 'collecte':
				?>
			<a href="">
				<img src="" alt="<?php _e('Contribuer', 'yproject'); ?>" title="<?php _e('Contribuer', 'yproject'); ?>" />
				<?php if ($campaign->funding_type() == 'fundingdonation'): ?>
				<?php _e('Soutenir', 'yproject'); ?>
				<?php else: ?>
				<?php _e('Investir', 'yproject'); ?>
				<?php endif; ?>
			</a>
				<?php break;
			} ?>
			</li>

			<li><a href="">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/goodmains_gris.png" alt="<?php _e('Partager', 'yproject'); ?>" title="<?php _e('Partager', 'yproject'); ?>" />
				<?php _e('Partager', 'yproject'); ?>
			</a></li>
		</ul>
	</div>
</nav>