<?php date_default_timezone_set("Europe/Paris"); ?>
<?php $page_list_projects = get_page_by_path('les-projets'); ?>


<?php 
//Affichage utilisateur pour prévenir qu'il est déjà connecté (pour ne pas retourner sur la page de connexion)
if (is_user_logged_in() && isset($_GET['alreadyloggedin']) && $_GET['alreadyloggedin'] === '1'): ?>
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

<header class="wdg-component-slider">
    <div class="slider-container"> 
        <div class="slider-choice">
            <span class="num-slide active-slide" id="span-1">1</span>
            <span class="num-slide inactive-slide" id="span-2">2</span>  
            <span class="num-slide inactive-slide" id="span-3">3</span>
        </div>  
        <div id="slider">
            <?php
            $tabImg = array(1 => '/images/slider/Small-business-start-up-team-briefing-with-notes.jpg',
                            2 => '/images/slider/fotolia_abeille.jpg',
                            3 => '/images/slider/fotolia_nature.jpg'
                            );
            for ($ii = 1; $ii <= count($tabImg); $ii++):
                ?>
                <div class="slider-item slide-2buttons" id="slide-<?php echo $ii?>" >
                    <img class="slider-motif-left" src="<?php echo $stylesheet_directory_uri; ?>/images/slider/slider-motif-trame-gauche.png"/>
                    <img class="slide" id="img-slide-<?php echo $ii?>" src="<?php echo $stylesheet_directory_uri; ?><?php echo $tabImg[$ii] ?>"/> 
                    <img class="slider-motif-right" src="<?php echo $stylesheet_directory_uri; ?>/images/slider/slider-motif-trame-droite.png"/>
                    <?php
                    if($ii === 1):?>
                        <div class="message-banner">
                                <p class="mobile_hidden screen-message">Nous activons</br>une finance à impact positif</br>en développant<br/>les levées de fonds en royalties</p>
                                <p class="only_on_mobile inline mobile-message">Nous activons</br>une finance<br/>à impact positif</br>en développant<br/>les levées</br>de fonds</br>en royalties</p>                                                 
                        </div>
                        <div id="button-container">
                            <a class="button-slide" href=""><?php _e("Financer son projet", "yproject") ?></a>
                            <a class="button-slide" href=""><?php _e("Investir sur un projet", "yproject") ?></a>
                        </div>
                    <?php endif;?>                         
                    <?php if($ii !== 1): ?> 
                        <div class="message-banner">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
    
</header> <!-- .wdg-component-slider -->
<!--<header class="header_home">
	<div class="center">
		<div id="welcome_text">
			<hr class="mobile_hidden" />
			<p class="mobile_hidden welcome">Bienvenue<br />sur WEDOGOOD !</p>
			<?php the_content(); ?>
			<?php if ( is_user_logged_in() ) { ?>
				<?php 
				global $current_user;
				get_currentuserinfo();
				$user_name_str = $current_user->user_firstname;
				if ($user_name_str == '') {
					$user_name_str = $current_user->user_login;
				}
				?>
				<p class="hello">Bonjour <?php echo $user_name_str; ?> !</p>
			<?php } else { ?>
				<div id="header_homepage_link" class="mobile_hidden">
				    <a href="#register" class="wdg-button-lightbox-open button" data-lightbox="register">Inscription</a>
				    <a href="#connexion" class="wdg-button-lightbox-open button" data-lightbox="connexion">Connexion</a>
                                   
		                </div>

			<?php } ?>
			<hr class="mobile_hidden" />
			<p class="align-center only_on_mobile"><br /><a href="<?php echo get_permalink($page_list_projects->ID); ?>" class="button big">D&eacute;couvrir les projets</a></p>
		</div>

		<div class="home_video right mobile_hidden">
			<div class="video-container hidden"><?php echo wp_oembed_get('https://youtu.be/QJmhrCG5acU', array("width" => 570)); ?></div>
			<div class="button-video"><img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button.jpg" /></div>
			<div class="button-video-shadows">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-shadow2.png" />
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-shadow1.png" />
			</div>
		</div>
	</div>
</header>-->

<?php 
if ( !is_user_logged_in() ) {
	echo do_shortcode('[yproject_register_lightbox]');
	echo do_shortcode('[yproject_connexion_lightbox]');
}
?>

<?php
//*******************
//CACHE HOME
$cache_home = $WDG_cache_plugin->get_cache('home', 2);
if ($cache_home !== FALSE) { echo $cache_home; }
else {
	ob_start();
$page_finance = get_page_by_path('financement');
$page_how = get_page_by_path('descriptif');
?>

<!-- SECTION NOS DERNIERS PROJETS -->

<?php
global $WDG_cache_plugin;
date_default_timezone_set("Europe/London");
?>

<section class="wdg-component-projects-preview">
    <h1><?php _e("/ les projets /", "yproject") ?></h1>
    <div id="bloc-projects">

        <?php
        $nb_projects = 3;
        // Affiche les 3 projets les plus récents entre ceux en cours, en vote et financés
        $all_projects = ATCF_Campaign::get_list_most_recent( $nb_projects );

        foreach ($all_projects as $project_id) {
                $one_project = new ATCF_Campaign( $project_id );
                $img = $one_project->get_home_picture_src();

            require('projects/preview.php');//insère html de la page preview 
        }
        ?>
    </div>  <!-- #bloc-projects --> 
    <a class="home-button-project see-more red" href=""><?php _e("découvrir tous les projets","yproject" ) ?></a>
</section> <!-- .wdg-component-projects-preview -->

<!-- fin de SECTION NOS DERNIERS PROJETS -->


<!-- SECTION VIDEO -->

<section id="home-video-ref">
    <div id="video-content">
        <h1><?php _e("/ comment ça marche ? /", "yproject")?></h1>
        <div class="home_video">
            <div class="video-container hidden"><?php echo wp_oembed_get('https://youtu.be/QJmhrCG5acU', array("width" => 570)); ?></div>
            <div class="button-video"><img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-nb.jpg" /></div>
            <div class="button-video-shadows mobile_hidden">
                    <img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-shadow2.png" />
                    <img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-shadow1.png" />
            </div>
        </div>
    </div>
</section>
<!-- fin de SECTION VIDEO -->

<!-- SECTION PRESSE -->
<section class="mobile_hidden" id="home-press">
    <img id="press-banner" src="<?php echo $stylesheet_directory_uri; ?>/images/bandeau-presse.jpg"/>   
</section>   
<!-- fin de SECTION PRESSE -->



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
