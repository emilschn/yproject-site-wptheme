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

<header class="wdg-component-slider home-slider">
    <div class="slider-container"> 
        <div class="slider-choice">
            <span class="num-slide active-slide" id="span-1">1</span>
            <span class="num-slide inactive-slide" id="span-2">2</span>  
            <span class="num-slide inactive-slide" id="span-3">3</span>
        </div>  
        <div id="slider">
            <?php
            $tabImg = array(1 => '/images/slider/slider-01.jpg',
                            2 => '/images/slider/fotolia_abeille.jpg',
                            3 => '/images/slider/fotolia_nature.jpg'
                            );
            for ($ii = 1; $ii <= count($tabImg); $ii++):
                ?>
				<div class="slider-item" id="slide-<?php echo $ii?>" style="<?php if ($ii > 1){ ?>display: none;<?php } else { ?>left: 0px;<?php } ?>">
                    <img class="slider-motif-left-haut" src="<?php echo $stylesheet_directory_uri; ?>/images/slider/slider-trame-haut-gauche-01.png"/>
                    <img class="slider-motif-right" src="<?php echo $stylesheet_directory_uri; ?>/images/slider/slider-motif-trame-droite.png"/>
                    <img class="slide" id="img-slide-<?php echo $ii?>" src="<?php echo $stylesheet_directory_uri; ?><?php echo $tabImg[$ii] ?>"/> 
                    
                    <?php
                    if($ii === 1):?>
                        <div class="message-banner">
							<p class="screen-message">Nous activons</br>une finance à impact positif</br>en développant<br/>les levées de fonds en royalties</p>
							<img class="slider-motif-left-bas" src="<?php echo $stylesheet_directory_uri; ?>/images/slider/slider-trame-bas-gauche-01.png"/>
                        </div>
                    <?php endif; ?>
                    <?php if($ii !== 1): ?>
                        <div class="message-banner">
                        </div>
                    <?php endif; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
	
	<div id="button-container">
		<a class="button-slide" href="<?php echo home_url( '/financement' ); ?>"><?php _e("Financer son projet", "yproject") ?></a>
		<a class="button-slide" href="<?php echo home_url( '/investissement' ); ?>"><?php _e("Investir sur un projet", "yproject") ?></a>
	</div>
</header> <!-- .wdg-component-slider -->

<?php
//*******************
//CACHE HOME
$cache_home = $WDG_cache_plugin->get_cache('home-projects', 1);
if ($cache_home !== FALSE) { echo $cache_home; }
else {
	ob_start();
	date_default_timezone_set("Europe/London");
?>

<section class="wdg-component-projects-preview">
    <h2 class="standard">/ <?php _e("les projets", "yproject") ?> /</h2>
	<div class="project-slider">
		<div class="block-projects">
			<?php
			$nb_projects = 3;
			// Affiche les 3 projets les plus récents entre ceux en cours, en vote et financés
			$all_projects = ATCF_Campaign::get_list_most_recent( $nb_projects );
			foreach ($all_projects as $project_id) {
				require('projects/preview.php');
			}
			?>
		</div>
	</div>
    <a class="home-button-project see-more red" href="<?php echo home_url( '/les-projets' ); ?>"><?php _e("D&eacute;couvrir tous les projets","yproject" ) ?></a>
</section> <!-- section.wdg-component-projects-preview -->

<?php
	$cache_home = ob_get_contents();
	$WDG_cache_plugin->set_cache('home-projects', $cache_home, 60*2, 1);  //MAJ 2min
	ob_end_clean();
	echo $cache_home;
}
//FIN CACHE HOME
//*******************
?>


<section id="home-video">
    <div id="video-content">
        <h2 class="standard">/ <?php _e("Comment &ccedil;a marche ?", "yproject")?> /</h2>
        <div class="home_video">
            <div class="video-container w570 hidden"><?php echo wp_oembed_get('https://youtu.be/QJmhrCG5acU', array("width" => 570)); ?></div>
            <div class="video-container w320 hidden"><?php echo wp_oembed_get('https://youtu.be/QJmhrCG5acU', array("width" => 320)); ?></div>

            <div class="button-video"><img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-nb.jpg" /></div>
            <div class="button-video-shadows hidden-inf997">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-shadow2.png" />
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-shadow1.png" />
            </div>
        </div>
    </div>
</section> <!-- section#home-video -->


<section id="home-press" class="hidden-inf997">
	<a href="<?php echo home_url( '/espace-presse' ); ?>"><img id="press-banner" src="<?php echo $stylesheet_directory_uri; ?>/images/bandeau-presse.jpg" alt="presse" /></a>
</section> <!-- section#home-press -->