<?php
  $page_controler = WDG_Templates_Engine::instance()->get_controler();
  global $organization_obj;
  $current_organization = $page_controler->get_campaign_organization();
  $organization_obj = $current_organization;
  $WDGUser_current = WDGUser::current();
?>

<h2><?php _e( "Organisation", 'yproject' ); ?></h2>

<?php if ( !$page_controler->can_access_admin() && !$page_controler->can_access_author() ): ?>
	<div class="wdg-message error">
		<?php _e( "Seul le porteur de projet peut &eacute;diter les informations de cet onglet", 'yproject' ); ?>
	</div>


<?php else: ?>

<?php DashboardUtility::add_help_item( $page_controler->get_current_user(), 'organization', 1 ); ?>

<?php if ( !$current_organization->is_registered_lemonway_wallet() ): ?>
	<div class="wdg-message error">
		<?php _e( "L'organisation n'est pas encore authentifi&eacute;e", 'yproject' ); ?>
	</div>
<?php else: ?>
	<div class="wdg-message confirm">
		<?php _e( "L'organisation est authentifi&eacute;e", 'yproject' ); ?>
	</div>
<?php endif; ?>

<?php if ( $WDGUser_current->is_admin() ): ?>
	<?php if ( isset( $current_organization ) ): ?>
		<div class="admin-theme">
			ID LemonWay : <?php echo $current_organization->get_lemonway_id(); ?>
		</div>
	<?php endif; ?>
<?php endif; ?>

<ul class="menu-onglet">
  <li><a href="#organization" data-subtab="orga-parameters" class="focus<?php if ( !$current_organization->has_filled_invest_infos() || !$current_organization->can_register_lemonway()): ?> needs-authentication<?php endif; ?>"><?php _e( "Informations", 'yproject' ); ?></a></li>
  <li><a href="#organization" data-subtab="orga-identitydocs" <?php if ( !$current_organization->has_sent_orga_documents() ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Justificatifs", 'yproject' ); ?></a></li>
  <li><a href="#organization" data-subtab="orga-bank" <?php if ( !$current_organization->has_saved_iban() ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Informations bancaires", 'yproject' ); ?></a></li>
  <li><a href="#organization" data-subtab="parameters" <?php if ( !$page_controler->get_campaign_author()->has_filled_invest_infos($page_controler->get_campaign()->funding_type()) ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Représentant légal", 'yproject' ); ?></a></li>
  <li><a href="#organization" data-subtab="identitydocs" <?php if ( !$page_controler->get_campaign_author()->is_lemonway_registered() ): ?>class="needs-authentication"<?php endif; ?>><?php _e( "Justificatifs du représentant légal", 'yproject' ); ?></a></li>
</ul>

<?php
// Inclusion des fichiers externes dans /tab-organization/
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-orga-parameters.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-orga-identitydocs.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-orga-bank.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-parameters.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-organization/tab-identitydocs.php'  ), true );
?>


<?php endif; ?>