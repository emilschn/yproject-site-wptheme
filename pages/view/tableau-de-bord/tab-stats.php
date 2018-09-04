<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>

<?php
// Préparation des statistiques
global $campaign_stats;
$campaign_stats = $page_controler->get_campaign_stats();
?>

<?php
// Inclusion des fichiers externes dans /tab-stats/
print_r( $campaign_stats );