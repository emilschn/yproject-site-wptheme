<?php
  $page_controler = WDG_Templates_Engine::instance()->get_controler();
  global $organization_obj;
  $current_organization = $page_controler->get_campaign_organization();
  $organization_obj = $current_organization;
?>

<h2><?php _e( "Organisation", 'yproject' ); ?></h2>

<ul class="menu-onglet">
  <li><a href="#organization" class="focus" data-subtab="orga-parameters" <?php if ( !$page_controler->get_campaign_organization()->has_filled_invest_infos() ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Informations", 'yproject' ); ?><span></span></a></li>
  <li><a href="#organization" data-subtab="orga-identitydocs" <?php if ( !$page_controler->get_campaign_organization()->is_registered_lemonway_wallet() ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Justificatifs", 'yproject' ); ?><span></span></a></li>
  <li><a href="#organization" data-subtab="orga-bank" <?php if ( !$page_controler->get_campaign_organization()->has_saved_iban() ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Coordonnées bancaires", 'yproject' ); ?><span></span></a></li>
  <li><a href="#organization" data-subtab="parameters" <?php if ( !$page_controler->get_campaign_author()->has_filled_invest_infos($page_controler->get_campaign()->funding_type()) ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Représentant légal", 'yproject' ); ?><span></span></a></li>
  <li><a href="#organization" data-subtab="identitydocs" <?php if ( !$page_controler->get_campaign_author()->is_lemonway_registered() ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Justificatifs du RL", 'yproject' ); ?><span></span></a></li>
</ul>

<?php
// Inclusion des fichiers externes dans /tab-organization/
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-orga-parameters.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-orga-identitydocs.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-orga-bank.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-parameters.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-identitydocs.php'  ), true );

