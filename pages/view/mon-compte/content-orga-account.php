<?php
global $stylesheet_directory_uri, $WDGOrganization;
$page_controler = WDG_Templates_Engine::instance()->get_controler();

$lw_wallet_amount = 0;
$count_validated_investments = 0;
$can_register_lemonway = false;
$is_lemonway_registered = false;
if ( isset( $WDGOrganization ) ) {
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
		<div class="account-dashboard-text">dans votre porte-monnaie</div>
		<div><a href="#wallet" class="button blue go-to-tab" data-tab="wallet">Voir mon porte-monnaie</a></div>
		<div><a href="https://support.wedogood.co/fr/investir-et-suivre-mes-investissements#porte-monnaie-et-s%C3%A9curisation-des-donn%C3%A9es" target="_blank">Aide</a></div>
	</div>

	<div>
	<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="montgolfiere" width="50" height="50"></div>
		<div class="account-dashboard-data"><?php echo $count_validated_investments; ?></div>
		<div class="account-dashboard-text">investissement<?php if ($count_validated_investments > 1) { echo 's'; } ?></div>
		<div><a href="#investments" class="button blue go-to-tab" data-tab="investments">Voir mes investissements</a><br></div>
		<div><a href="https://support.wedogood.co/fr/investir-et-suivre-mes-investissements#investissement" target="_blank">Aide</a></div>
	</div>

	<div>
		<?php if ( $can_register_lemonway ): ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data">Complet</div>
			<div class="account-dashboard-text">Informations personnelles</div>
		<?php else: ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data">Incomplet</div>
			<div class="account-dashboard-text">Informations personnelles</div>
		<?php endif; ?>
		<div><a href="#parameters" class="button blue go-to-tab" data-tab="parameters">Editer mes informations</a></div>
		<div><a href="https://support.wedogood.co/fr/mon-compte-et-donn%C3%A9es-personnelles#connexion-et-gestion-de-mon-compte" target="_blank">Aide</a></div>
	</div>

	<div>
		<?php if ( $is_lemonway_registered ): ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data">Validée</div>
			<div class="account-dashboard-text">Authentification</div>
		<?php else: ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data">Non valide</div>
			<div class="account-dashboard-text">Authentification</div>
		<?php endif; ?>
		<div><a href="#identitydocs" class="button blue go-to-tab" data-tab="identitydocs">Editer mes justificatifs</a></div>
		<div><a href="https://support.wedogood.co/fr/comment-authentifier-mon-compte" target="_blank">Aide</a></div>
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