<?php date_default_timezone_set("Europe/Paris"); ?>

<?php if (is_user_logged_in() && isset($_GET['alreadyloggedin']) && $_GET['alreadyloggedin'] === '1'): ?>
<div id="already-connected" class="wdg-lightbox">
	<div class="wdg-lightbox-padder">
		<div class="wdg-lightbox-button-close">
			<a href="#" class="button">X</a>
		</div>
		<span>Vous &ecirc;tes d&eacute;j&agrave; inscrit et connect&eacute; !</span><br />
		<hr /><br />
		<a href="<?php echo bp_loggedin_user_domain(); ?>" class="button">Aller sur votre compte</a>
		ou
		<a href="<?php echo home_url(); ?>" class="button">Retourner &agrave; la case d&eacute;part</a>
	</div>
</div>
<?php endif; ?>

<header class="header_home">
	<div class="center">
		<section id="welcome_text">
			<?php the_content();?>
			<?php if ( is_user_logged_in() ) { ?>
				<?php global $current_user; get_currentuserinfo(); ?>
				<p class="hello">Bonjour <?php echo $current_user->user_firstname;?> !</p>
			<?php } else {
				$page_connexion_register = get_page_by_path('register');
				$page_connexion = get_page_by_path('connexion');?>
			<div id="header_homepage_link">
				<a href="<?php echo get_permalink($page_connexion_register->ID); ?>" class="button">Inscription</a>
				<a href="<?php echo get_permalink($page_connexion->ID); ?>" class="button">Connexion</a>
			</div>
			<?php } ?>
		</section>
	</div>
</header>

<?php
//*******************
//CACHE HOME
$cache_home = $WDG_cache_plugin->get_cache('home', 1);
if ($cache_home !== FALSE) { echo $cache_home; }
else {
	ob_start();
?>

<div id="home_middle_top">
	<div id="home_middle_content">
		<div class="center">
			<?php 
			$page_list_projects = get_page_by_path('les-projets');
			$page_finance = get_page_by_path('financement');
			?>
			<a href="<?php echo get_permalink($page_list_projects->ID); ?>" style="display: block;">
				<div class="round_title_left">
					<strong>Participer</strong><br/>&agrave; un projet
				</div>
			</a>
			<a href="<?php echo get_permalink($page_finance->ID); ?>" style="display: block;">
				<div class="round_title_right">
					<strong>Financer</strong><br />son projet
				</div>
			</a>
			<div class="clear"></div>
		</div>
	</div>
</div>
<div class="center">
	<div id="home_middle_desc_left">
		<p>
			Soyez acteurs et influenceurs<br />
			de la communaut&eacute;
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/main.jpg" alt="logo main" /><br />
			Votez pour les projets les plus impactants
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/pieces.jpg" alt="logo piece monnaie" /><br />
			Investissez &agrave; partir de 10&euro;
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/fusee.jpg" alt="logo fusee" /><br />
			Boostez l&apos;&eacute;conomie positive
		</p>
	</div>
	<div id="home_middle_desc_right">
		<p>
			B&eacute;n&eacute;ficiez d'un financement souple<br />
			et adapt&eacute; &agrave; vos besoins
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/sous.jpg" alt="logo euro" /><br />
			Trouvez un financement pour votre projet
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/hp.jpg" alt="logo megaphone" /><br />
			Faites conna&icirc;tre votre projet
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/communaute.jpg" alt="logo communaute" /><br />
			F&eacute;d&eacute;rez une communaut&eacute; sur la dur&eacute;e
		</p>
	</div>
</div>

<div id="home_bottom" class="center">
	<div class="padder">
		<div class="part-title-separator">
			<span class="part-title">Nos partenaires</span>
		</div>
		<?php $page_partners = get_page_by_path('partenaires'); ?>
		<div class="partners_zone">
			<a href="<?php echo get_permalink($page_partners->ID); ?>"><img src="<?php echo $stylesheet_directory_uri; ?>/images/frise_partenaires_wedogood.png" width="3135" height="150" alt="logos partenaires" /></a>
		</div>
	</div>
</div>
<?php
	$cache_home = ob_get_contents();
	$WDG_cache_plugin->set_cache('home', $cache_home, 60*60*24, 1);
	ob_end_clean();
	echo $cache_home;
}
//FIN CACHE HOME
//*******************