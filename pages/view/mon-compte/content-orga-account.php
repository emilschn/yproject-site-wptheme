<?php
global $stylesheet_directory_uri, $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();

$orga_wpref = 0;
$lw_wallet_amount = 0;
$count_validated_investments = 0;
$can_register_lemonway = false;
$is_lemonway_registered = false;
if ( isset( $WDGOrganization ) ) {
	$orga_wpref = $WDGOrganization->get_wpref();
	$lw_wallet_amount = $WDGOrganization->get_lemonway_balance();
	$validated_investments = $WDGOrganization->get_validated_investments();
	$count_validated_investments = count( $validated_investments );
	$can_register_lemonway = $WDGOrganization->can_register_lemonway();
	$is_lemonway_registered = $WDGOrganization->is_registered_lemonway_wallet();
}
?>

<h2><?php _e( 'account.menu.MY_ACCOUNT', 'yproject' ); ?></h2>

<div class="account-dashboard">
	<div>
		<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-invest/picto-porte-monnaie.png" alt="porte-monnaie" width="50" height="50"></div>
		<div class="account-dashboard-data"><?php echo UIHelpers::format_number( $lw_wallet_amount ); ?> &euro;</div>
		<div class="account-dashboard-text"><?php _e( 'account.IN_MY_WALLET', 'yproject' ); ?></div>
		<div><a href="#orga-wallet-<?php echo $orga_wpref; ?>" class="button blue go-to-tab" data-tab="orga-wallet-<?php echo $orga_wpref; ?>"><?php _e( 'account.VIEW_MY_WALLET', 'yproject' ); ?></a></div>
		<div><a href="https://support.wedogood.co/fr/investir-et-suivre-mes-investissements#porte-monnaie-et-s%C3%A9curisation-des-donn%C3%A9es" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
	</div>

	<div>
	<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="montgolfiere" width="50" height="50"></div>
		<div class="account-dashboard-data"><?php echo $count_validated_investments; ?></div>
		<div class="account-dashboard-text"><?php if ($count_validated_investments > 1) { _e( 'account.menu.organization.INVESTMENTS', 'yproject' ); } else { _e( 'common.INVESTMENT', 'yproject' ); } ?></div>
		<div><a href="#orga-investments-<?php echo $orga_wpref; ?>" class="button blue go-to-tab" data-tab="orga-investments-<?php echo $orga_wpref; ?>"><?php _e( 'account.authentication.VIEW_MY_INVESTMENTS', 'yproject' ); ?></a><br></div>
		<div><a href="https://support.wedogood.co/fr/investir-et-suivre-mes-investissements#investissement" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
	</div>

	<div>
		<?php if ( $can_register_lemonway ): ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data"><?php _e( 'common.COMPLETE', 'yproject' ); ?></div>
		<?php else: ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data"><?php _e( 'common.INCOMPLETE', 'yproject' ); ?></div>
		<?php endif; ?>
		<div class="account-dashboard-text"><?php _e( 'account.authentication.PERSONAL_INFORMATION', 'yproject' ); ?></div>
		<div><a href="#orga-parameters-<?php echo $orga_wpref; ?>" class="button blue go-to-tab" data-tab="orga-parameters-<?php echo $orga_wpref; ?>"><?php _e( 'account.authentication.EDIT_MY_INFORMATION', 'yproject' ); ?></a></div>
		<div><a href="https://support.wedogood.co/fr/mon-compte-et-donn%C3%A9es-personnelles#connexion-et-gestion-de-mon-compte" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
	</div>

	<div>
		<?php if ( $is_lemonway_registered ): ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data"><?php _e( 'common.VALIDATED', 'yproject' ); ?></div>
			<div class="account-dashboard-text"><?php _e( 'common.AUTHENTICATION', 'yproject' ); ?></div>
		<?php else: ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data"><?php _e( 'common.NOT_VALIDATED', 'yproject' ); ?></div>
			<div class="account-dashboard-text"><?php _e( 'common.AUTHENTICATION', 'yproject' ); ?></div>
			<div><a href="#orga-identitydocs-<?php echo $orga_wpref; ?>" class="button blue go-to-tab" data-tab="orga-identitydocs-<?php echo $orga_wpref; ?>"><?php _e( 'account.authentication.EDIT_IDENTITY_DOCUMENTS', 'yproject' ); ?></a></div>
			<div><a href="https://support.wedogood.co/fr/comment-authentifier-mon-compte" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
		<?php endif; ?>
	</div>
</div>

<br>

<div class="center">
	<?php
	// La récupération de la locale ne devrait pas être nécessaire, ça devrait déjà être fait... mais apparemment non !
	global $locale;
	$locale = WDG_Languages_Helpers::get_current_locale_id();
	echo wpautop( WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_info_lemonway, 'lemonway_generalities' ) );
	?>
</div>