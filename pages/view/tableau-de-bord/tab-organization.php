<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $organization_obj;
$current_organization = $page_controler->get_campaign_organization();
$organization_obj = $current_organization;

?>

<h2><?php _e( "Organisation", 'yproject' ); ?></h2>

<ul class="menu-onglet">
  <li><a href="#organization" class="focus" data-subtab="informations"><?php _e( "Informations", 'yproject' ); ?><span></span></a></li>
  <li><a href="#organization" data-subtab="justificatifs"><?php _e( "Justificatifs", 'yproject' ); ?><span></span></a></li>
  <li><a href="#organization" data-subtab="bank"><?php _e( "Coordonnées bancaires", 'yproject' ); ?><span></span></a></li>
  <li><a href="#organization" data-subtab="author"><?php _e( "Représentant légal", 'yproject' ); ?><span></span></a></li>
  <li><a href="#organization" data-subtab="justificatifs-representant"><?php _e( "Justificatifs du RL", 'yproject' ); ?><span></span></a></li>
</ul>

<?php
// Inclusion des fichiers externes dans /tab-organization/
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-informations.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-justificatifs.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-bank.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-author.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-justificatifs-representant.php'  ), true );

