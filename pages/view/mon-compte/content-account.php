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
	$user_conformity_type = $is_sophisticated ? __( 'account.SOPHISTICATED', 'yproject' ) : __( 'account.NONSOPHISTICATED', 'yproject' );
}
?>

<h2><?php _e( 'account.menu.MY_ACCOUNT', 'yproject' ); ?></h2>

<div class="account-dashboard">
	<div>
		<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-invest/picto-porte-monnaie.png" alt="porte-monnaie" width="50" height="50"></div>
		<div class="account-dashboard-data"><?php echo UIHelpers::format_number( $lw_wallet_amount ); ?> &euro;</div>
		<div class="account-dashboard-text"><?php _e( 'account.IN_MY_WALLET', 'yproject' ); ?></div>
		<div><a href="#wallet" class="button blue go-to-tab" data-tab="wallet"><?php _e( 'account.VIEW_MY_WALLET', 'yproject' ); ?></a></div>
		<div class="account-dashboard-help"><a href="https://support.wedogood.co/fr/porte-monnaie-electronique" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
	</div>

	<div>
	<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="montgolfiere" width="50" height="50"></div>
		<div class="account-dashboard-data"><?php echo $count_validated_investments; ?></div>
		<div class="account-dashboard-text"><?php if ($count_validated_investments > 1) { _e( 'account.menu.organization.INVESTMENTS', 'yproject' ); } else { _e( 'common.INVESTMENT', 'yproject' ); } ?></div>
		<div><a href="#investments" class="button blue go-to-tab" data-tab="investments"><?php _e( 'account.authentication.VIEW_MY_INVESTMENTS', 'yproject' ); ?></a><br></div>
		<div class="account-dashboard-help"><a href="https://support.wedogood.co/fr/investir-et-suivre-mes-investissements#investissement" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
	</div>

	<div>
		<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/common/picto-contrat.png" alt="montgolfiere" width="50" height="50"></div>
		<div class="account-dashboard-data"><?php echo $count_subscriptions; ?></div>
		<div class="account-dashboard-text"><?php if ($count_subscriptions > 1) { _e( 'account.menu.organization.SUBSCRIPTIONS', 'yproject' ); } else { _e( 'common.SUBSCRIPTION', 'yproject' ); } ?></div>
		<div><a href="#subscriptions" class="button blue go-to-tab" data-tab="subscriptions"><?php _e( 'account.VIEW_MY_SUBSCRIPTIONS', 'yproject' ); ?></a></div>
		<div class="account-dashboard-help"><a href="https://support.wedogood.co/fr/quest-ce-quun-abonnement" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
	</div>

	<div>
		<?php if ( $can_register_lemonway && !empty( $user_contact_if_deceased ) ): ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data"><?php _e( 'common.COMPLETE', 'yproject' ); ?></div>
		<?php else: ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data"><?php _e( 'common.INCOMPLETE', 'yproject' ); ?></div>
		<?php endif; ?>
		<div class="account-dashboard-text"><?php _e( 'account.authentication.PERSONAL_INFORMATION', 'yproject' ); ?></div>
		<div><a href="#parameters" class="button blue go-to-tab" data-tab="parameters"><?php _e( 'account.authentication.EDIT_MY_INFORMATION', 'yproject' ); ?></a></div>
		<div class="account-dashboard-help"><a href="https://support.wedogood.co/fr/mon-compte-et-donn%C3%A9es-personnelles#connexion-et-gestion-de-mon-compte" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
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
			<div><a href="#identitydocs" class="button blue go-to-tab" data-tab="identitydocs"><?php _e( 'account.authentication.EDIT_IDENTITY_DOCUMENTS', 'yproject' ); ?></a></div>
			<div class="account-dashboard-help"><a href="https://support.wedogood.co/fr/comment-authentifier-mon-compte" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
		<?php endif; ?>
	</div>

	<div>
		<?php if ( $WDGUser_displayed->has_valid_conformity_data() ): ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-checked.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data">
				<?php echo $user_conformity_type; ?><br>
				Max. <?php echo UIHelpers::format_number( $yearly_capacity_amount ); ?> &euro; / <?php _e( 'account.investments.INVESTMENT_DURATION_YEAR', 'yproject' ); ?>
			</div>
		<?php else: ?>
			<div><img src="<?php echo $stylesheet_directory_uri; ?>/images/template-account/check-error.png" alt="check" width="50" height="50"></div>
			<div class="account-dashboard-data"><?php _e( 'common.INCOMPLETE', 'yproject' ); ?></div>
		<?php endif; ?>
		<div class="account-dashboard-text"><?php _e( 'account.INVESTOR_PROFILE', 'yproject' ); ?></div>
		<div><a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mon-compte/capacite' ); ?>" class="button blue"><?php _e( 'account.EDIT_MY_PROFILE', 'yproject' ); ?></a></div>
		<div class="account-dashboard-help"><a href="https://support.wedogood.co/fr/quest-ce-quun-investisseur-averti" target="_blank"><?php _e( 'common.HELP', 'yproject' ); ?></a></div>
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