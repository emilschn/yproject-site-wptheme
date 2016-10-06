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

<header id="header_home_ref">
    <div class="slider-container"> 
        <div class="slider-choice">
            <span class="num-slide active-slide" id="span-1">1</span>
            <span class="num-slide inactive-slide" id="span-2">2</span>  
            <span class="num-slide inactive-slide" id="span-3">3</span>
        </div>  
        <div id="slider">
            <?php
                $tabImg = array(1 => '/images/slider/fotolia_equipe_nb.jpg" alt=""',
                                2 => '/images/slider/fotolia_abeille.jpg" alt=""',
                                3 => '/images/slider/fotolia_nature.jpg" alt=""'
                                );
                for ($ii = 1; $ii <= count($tabImg); $ii++):
                    ?>
                    <div class="slider-item" id="slide-<?=$ii?>" >
                        <img class="slide" src="<?php echo $stylesheet_directory_uri; ?><?php echo $tabImg[$ii] ?>"/> 
                        
                        <?php
                        if($ii === 1):?>
                            <div class="message-banner">
                                    <p class="mobile_hidden screen-message">Nous activons</br>une finance à impact positif</br>en développant<br/>les levées de fonds en royalties</p>
                                    <p class="only_on_mobile inline mobile-message">Nous activons</br>une finance<br/>à impact positif</br>en développant<br/>les levées</br>de fonds</br>en royalties</p>                                                 
                            </div>
                            <div id="button-container">
                                <button class="button red big">Financer son projet<a href=""></a></button>
                                <button class="button red big">Investir sur un projet<a href=""></a></button>
                            </div>
                        <?php endif;?>                         
                        <?php if($ii !== 1): ?> 
                            <div class="message-banner">
                            </div>
                        <?php endif; ?>
                    </div>
            <?php endfor; ?>
<!--            <div class="slider-item" id="slide-1" >
                <img class="slide" src="<?php echo $stylesheet_directory_uri; ?>/images/slider/fotolia_equipe_nb.jpg" alt=""/> 
                <div id="message-banner">
                    <p class="mobile_hidden screen-message">Nous activons</br>une finance à impact positif</br>en développant<br/>les levées de fonds en royalties</p>
                    <p class="only_on_mobile inline mobile-message">Nous activons</br>une finance<br/>à impact positif</br>en développant<br/>les levées</br>de fonds</br>en royalties</p>
                </div>
                <div id="button-container">
                    <button class="button red big">Financer son projet<a href=""></a></button>
                    <button class="button red big">Investir sur un projet<a href=""></a></button>
                </div>
            </div>
            <div class="slider-item" id="slide-2" >
                <img class="slide"  src="<?php echo $stylesheet_directory_uri; ?>/images/slider/fotolia_abeille.jpg" alt=""/>          
            </div>
            <div class="slider-item" id="slide-3" >
                <img class="slide"  src="<?php echo $stylesheet_directory_uri; ?>/images/slider/fotolia_nature.jpg" alt=""/>          
            </div>-->
        </div>
    </div>
    
</header>
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

$nb_projects = 3;
?>

<?php
//Remplace toutes les lignes ci-dessus, $test devrait devenir $all_projects qui sera un tableau d'id de campagnes
$test = ATCF_Campaign::get_list_most_recent( 3 );
print_r($test); echo '<br>';
//Exemple de parcours
foreach ($test as $project_id) {
	$one_project = new ATCF_Campaign( $project_id );
	print_r($one_project); echo '<br>';
}
?>


<section id="home-projects-ref">
    <h1>/ Nos derniers projets /</h1>
    <div id="bloc-projects">
<?php
// Affichage des projets en cours et en vote existants  
    if($all_projects){
        $ii = 1;        
        foreach ($all_projects as $one_project){
            // Gestion de la récupération des images des projets

            $attachment =  get_posts(array(
                                    'post_type' => 'attachment',
                                    'post_parent' => $one_project->ID,
                                    'post_mime_type' => 'image'
                            ));
            //Si on en trouve bien une avec le titre "image_home" on prend celle-là
            $image_obj = '';
            if ($attachment->post_title == 'image_home') $image_obj = wp_get_attachment_image_src($attachment[0]->ID, "full");

            //Sinon on prend la première image rattachée à l'article
            $img_src = '';    
            if ($image_obj == '') {$image_obj = wp_get_attachment_image_src($attachment[0]->ID, "full");}
            if ($image_obj != '') {$img_src = $image_obj[0];}

?>
            <div class="project-container" id="project-<?=$ii?>">
                <div class= "impacts-container" id="impacts-<?=$ii?>">
                    <span class="impact-logo impact-ecologic" id="impact-ecologic-<?=$ii?>"><p>ecl</p></span> <!-- impacts à modifier selon nvl données et nvl fonctions à créer -->
                    <span class="impact-logo impact-social" id="impact-social-<?=$ii?>"><p>soc</p></span>
                    <span class="impact-logo impact-economic" id="impact-economic-<?=$ii?>"><p>ecn</p></span>                   
                </div>
<?php
            require('projects/preview.php');//insère html de la page preview
            
?>
            </div> <!-- project-container -->
            <?php
            if ($ii++ == 3) {break;} //$all_projects peut avoir une longueur > 3
            ?>
<?php  
        }    
        
    }
    //Si moins de 3 projets (en cours+en vote), on affiche les projets financés réussis
    if ($more_projects){
        if($all_projects){ $missing_projects = 3 - count($all_projects); }else { $missing_projects = 3;}
        $ii = 1;        
        foreach ($more_projects as $one_project){
            // Gestion de la récupération des images des projets

            $attachment =  get_posts(array(
                                    'post_type' => 'attachment',
                                    'post_parent' => $one_project->ID,
                                    'post_mime_type' => 'image'
                            ));
            //Si on en trouve bien une avec le titre "image_home" on prend celle-là
            $image_obj = '';
            if ($attachment->post_title == 'image_home') $image_obj = wp_get_attachment_image_src($attachment[0]->ID, "full");

            //Sinon on prend la première image rattachée à l'article
            $img_src = '';    
            if ($image_obj == '') {$image_obj = wp_get_attachment_image_src($attachment[0]->ID, "full");}
            if ($image_obj != '') {$img_src = $image_obj[0];}

?>
            <div class="project-container" id="project-<?=$ii+10?>">
                <div class= "impacts-container" id="impacts-<?=$ii+10?>">
                    <span class="impact-logo impact-ecologic" id="impact-ecologic-<?=$ii+10?>"><p>ecl</p></span> <!-- impacts à modifier selon nvl données et nvl fonctions à créer -->
                    <span class="impact-logo impact-social" id="impact-social-<?=$ii+10?>"><p>soc</p></span>
                    <span class="impact-logo impact-economic" id="impact-economic-<?=$ii+10?>"><p>ecn</p></span>                   
                </div>
<?php
            require('projects/preview.php');//insère html de la page preview
            
?>
            </div> <!-- .project-container-->
<?php  
        if ($ii++ == $missing_projects) {break;} //$all_projects peut avoir une longueur > 3
        }    
    }

?>
    </div>  <!-- #bloc-projects --> 
    <button class="button big red see-more">voir plus de projets<a href=""></a></button>
</section> <!-- #home-projects-ref -->

<section id="home-video-ref">
    <div id="video-titles">
        <h1>/ wedogood, c'est quoi ? /</h1>
        <h2>découvrez notre vidéo</h2>
    </div>
    <div id="video-content">
        <div class="home_video mobile_hidden">
            <div class="video-container hidden"><?php echo wp_oembed_get('https://youtu.be/QJmhrCG5acU', array("width" => 570)); ?></div>
            <div class="button-video"><img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button.jpg" /></div>
            <div class="button-video-shadows">
                    <img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-shadow2.png" />
                    <img src="<?php echo $stylesheet_directory_uri; ?>/images/header-video-button-shadow1.png" />
            </div>
        </div>
    </div>
</section>

<!--<div id="home_middle_top" class="center mobile_hidden">

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
</div>-->

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
