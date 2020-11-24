<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>

<?php
// Préparation des statistiques
global $campaign_stats;
$campaign_stats = $page_controler->get_campaign_stats();

$status = $page_controler->get_campaign_status();

?>

<h2>Statistiques</h2>

<?php DashboardUtility::add_help_item( $page_controler->get_current_user(), 'stats', 1 ); ?>

<ul class="menu-onglet">

  <li><a href="#stats" data-subtab="evaluations" id="evaluations" class="<?php if ( $status == ATCF_Campaign::$campaign_status_vote ): ?> focus<?php endif; ?>"><?php _e( "&Eacute;valuations", 'yproject' ); ?></a></li>
  <li><a href="#stats" data-subtab="leveedefonds" id="leveedefonds" class="<?php if ( $status != ATCF_Campaign::$campaign_status_vote ): ?> focus<?php endif; ?>"><?php _e( "Investissements", 'yproject' ); ?></a></li>
  <li><a href="#stats" data-subtab="visites"><?php _e( "Visites", 'yproject' ); ?><span></span></a></li>
</ul>



<?php
// Inclusion des fichiers externes dans /tab-stats/
locate_template( array( 'pages/view/tableau-de-bord/tab-stats/tab-evaluations.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-stats/tab-levee-de-fonds.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-stats/tab-visites.php'  ), true );