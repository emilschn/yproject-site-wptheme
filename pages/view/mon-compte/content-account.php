<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$lw_wallet_amount = $WDGUser_displayed->get_lemonway_wallet_amount();
$validated_investments = $WDGUser_displayed->get_validated_investments();
$count_validated_investments = count( $validated_investments );
$list_subscriptions = $page_controler->get_active_subscriptions_list();
$count_subscriptions = count( $list_subscriptions );

$can_register_lemonway = $WDGUser_displayed->can_register_lemonway();
$user_contact_if_deceased = $WDGUser_displayed->get_contact_if_deceased();
$is_lemonway_registered = $WDGUser_displayed->is_lemonway_registered();
if ( $WDGUser_displayed->has_valid_conformity_data() ) {
	$conformity_data = $WDGUser_displayed->get_conformity_data();
	$yearly_capacity_amount = $conformity_data->financial_result_in_cents / 100;
	$is_sophisticated = ($conformity_data->knowledge_result == 'sophisticated');
	$user_conformity_type = $is_sophisticated ? __( 'account.SOPHISTICATED_INVESTOR', 'yproject' ) : __( 'account.NONSOPHISTICATED_INVESTOR', 'yproject' );
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
		<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/common/picto-contrat.png" alt="montgolfiere" width="50" height="50"></div>
		<div class="account-dashboard-data"><?php echo $count_subscriptions; ?></div>
		<div class="account-dashboard-text">abonnement<?php if ($count_subscriptions > 1) { echo 's'; } ?></div>
		<div><a href="#subscriptions" class="button blue go-to-tab" data-tab="subscriptions">Voir mes abonnements</a></div>
		<div><a href="https://support.wedogood.co/fr/quest-ce-quun-abonnement" target="_blank">Aide</a></div>
	</div>

	<div>
		<?php if ( $can_register_lemonway && !empty( $user_contact_if_deceased ) ): ?>
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

	<div>
		<?php if ( $WDGUser_displayed->has_valid_conformity_data() ): ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data"><?php echo UIHelpers::format_number( $yearly_capacity_amount ); ?> &euro; / an</div>
			<div class="account-dashboard-text"><?php echo $user_conformity_type; ?></div>
		<?php else: ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data">Complétez</div>
			<div class="account-dashboard-text">votre profil</div>
		<?php endif; ?>
		<div><a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mon-compte/capacite' ); ?>" class="button blue">Editer mon profil</a></div>
		<div><a href="https://support.wedogood.co/fr/quest-ce-quun-investisseur-averti" target="_blank">Aide</a></div>
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