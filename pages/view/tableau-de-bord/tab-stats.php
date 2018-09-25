<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>

<?php
// Préparation des statistiques
global $campaign_stats;
$campaign_stats = $page_controler->get_campaign_stats();
?>

<h2>Statistiques</h2>

<ul class="menu-onglet">
  <li><a href="#stats" class="focus" data-subtab="evaluations"><?php _e( "&Eacute;valuations", 'yproject' ); ?><span></span></a></li>
  <li><a href="#stats" data-subtab="leveedefonds"><?php _e( "Lev&eacute;e de fonds", 'yproject' ); ?><span></span></a></li>
  <li><a href="#stats" data-subtab="visites"><?php _e( "Visites", 'yproject' ); ?><span></span></a></li>
</ul>



<?php
// Inclusion des fichiers externes dans /tab-stats/
locate_template( array( 'pages/view/tableau-de-bord/tab-stats/tab-evaluations.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-stats/tab-levee-de-fonds.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-stats/tab-visites.php'  ), true );