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
		<div id="welcome_text">
			<?php the_content(); ?>
			<?php $page_list_projects = get_page_by_path('les-projets'); ?>
			<?php if ( is_user_logged_in() ) { ?>
				<?php global $current_user; get_currentuserinfo(); ?>
				<p class="hello">Bonjour <?php echo $current_user->user_firstname;?> !</p>
			<?php } else {
				$page_connexion_register = get_page_by_path('register'); ?>
				<div id="header_homepage_link" class="mobile_hidden">
				    <a href="<?php echo get_permalink($page_connexion_register->ID); ?>" class="button">Inscription</a>
				    <a href="#connexion" class="wdg-button-lightbox-open button" data-lightbox="connexion">Connexion</a>
		                    <?php echo do_shortcode('[yproject_connexion_lightbox]'); ?>
		                </div>

			<?php } ?>
			<p class="align-center only_on_mobile"><br /><a href="<?php echo get_permalink($page_list_projects->ID); ?>" class="button big">D&eacute;couvrir les projets</a></p>
		</div>
	</div>
</header>

<?php
//*******************
//CACHE HOME
$cache_home = $WDG_cache_plugin->get_cache('home', 2);
if ($cache_home !== FALSE) { echo $cache_home; }
else {
	ob_start();
?>


<?php 
$page_list_projects = get_page_by_path('les-projets');
$page_finance = get_page_by_path('financement');
$page_how = get_page_by_path('descriptif');
?>
<div id="home_middle_top" class="center mobile_hidden">
	<div id="home_middle_top_left" class="home_middle_top_content">
		<a href="<?php echo get_permalink($page_finance->ID); ?>" style="display: block;">
			<div class="round_title">
				<strong>Proposez</strong><br/>un projet
			</div>
		</a>
		<p>
			B&eacute;n&eacute;ficiez d'un financement souple<br />
			et adapt&eacute; &agrave; vos besoins
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/sous.png" alt="logo euro" /><br />
			Trouvez un <b>financement</b> pour votre projet
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/hautparleur.png" alt="logo megaphone" /><br />
			<b>Faites conna&icirc;tre votre projet</b>
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/communaute.png" alt="logo communaute" /><br />
			F&eacute;d&eacute;rez une <b>communaut&eacute;</b> sur la dur&eacute;e
		</p>
		<p>
			<br /><br />
			<a href="<?php echo get_permalink($page_finance->ID); ?>" class="button red big">Financez votre projet</a>
			<br /><br />
		</p>
	</div>
	<div id="home_middle_top_right" class="home_middle_top_content">
		<a href="<?php echo get_permalink($page_list_projects->ID); ?>" style="display: block;">
			<div class="round_title">
				<strong>Investissez</strong><br />sur un projet
			</div>
		</a>
		<p>
			Soyez acteurs et influenceurs<br />
			de la communaut&eacute; !
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/sous.png" alt="logo piece monnaie" /><br />
			Investissez <b>&agrave; partir de 10&euro;</b>
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/main.png" alt="logo main" /><br />
			<b>Participez &agrave; l&apos;aventure</b>
		</p>
		<p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/fusee.png" alt="logo fusee" /><br />
			Boostez l&apos;<b>&eacute;conomie positive</b>
		</p>
		<p>
			<br /><br />
			<a href="<?php echo get_permalink($page_how->ID); ?>" class="button red big">Comment &ccedil;a marche ?</a>
			<br /><br />
		</p>
	</div>
</div>

<div id="home_bottom" class="center mobile_hidden">
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
?>


<?php
//*******************
//CACHE HOME RESPONSIVE
$cache_home_responsive = $WDG_cache_plugin->get_cache('home-responsive', 1);
if ($cache_home_responsive !== FALSE) { echo $cache_home_responsive; }
else {
	ob_start();
?>
<?php
$page_finance = get_page_by_path('financement');
$page_how = get_page_by_path('descriptif');
?>
<div id="home-responsive" class="only_on_mobile align-center">
	<h2>Vous avez un projet ?</h2>
	<p>Avec WEDOGOOD, b&eacute;n&eacute;ficiez d&apos;un financement souple et adapt&eacute; &agrave; vos besoins.</p>
	<a href="<?php echo get_permalink($page_finance->ID); ?>" class="button red big">Financez votre projet</a>
	<br /><br /><br />
	<hr />
	<h2>Comment investir dans un projet ?</h2>
	<p>Soyez acteurs et influenceurs de la communaut&eacute; !</p>
	<a href="<?php echo get_permalink($page_finance->ID); ?>" class="button red big">Comment &ccedil;a marche ?</a>
	<br /><br /><br /><br />
</div>
<?php
	$cache_home_responsive = ob_get_contents();
	$WDG_cache_plugin->set_cache('home-responsive', $cache_home_responsive, 60*60*24, 1);
	ob_end_clean();
	echo $cache_home_responsive;
}
//FIN CACHE HOME
//*******************