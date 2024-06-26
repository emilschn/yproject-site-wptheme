<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();

$override_current_user = filter_input(INPUT_GET, 'override_current_user');
$suffix = '';
if (!empty($override_current_user)) {
	$suffix = '?override_current_user=' . $override_current_user;
}
$lw_wallet_amount = $WDGUser_displayed->get_lemonway_wallet_amount();
$pending_amount = $WDGUser_displayed->get_pending_rois_amount();
?>

<h2><?php _e('account.wallet.TITLE', 'yproject'); ?></h2>

<div class="db-form v3 align-left">

	<div class="wallet-preview">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-invest/picto-porte-monnaie.png"
			alt="porte-monnaie" width="100" height="69">
		<div>
			<span><?php echo UIHelpers::format_number($lw_wallet_amount); ?> &euro;</span><br>
			<span><?php _e('common.AVAILABLE.P', 'yproject'); ?></span>
		</div>
		<a href="<?php echo WDG_Redirect_Engine::override_get_page_url('les-projets'); ?>?source=account"
			class="button red half account-button">
			<span class="button-text">
				<?php _e('common.INVEST', 'yproject'); ?>
			</span>
			<span class="button-loading loading align-center hidden">
				<img class="alignverticalmiddle marginright"
					src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30"
					alt="chargement" /><?php _e('common.NEXT', 'yproject'); ?>
			</span>
		</a>
	</div>

	<?php if (!$WDGUser_displayed->is_lemonway_registered()): ?>
		<div class="wdg-message error msg-authentication-alert">
			<?php if ($pending_amount > 0): ?>
				<?php echo sprintf(__('account.wallet.AWAITING_AUTHENTICATION', 'yproject'), UIHelpers::format_number($pending_amount)); ?><br><br>
			<?php endif; ?>

			<?php _e('account.wallet.AUTHENTICATION_NECESSARY', 'yproject'); ?>
		</div>

		<a href="#account" class="button red go-to-tab"
			data-tab="account"><?php _e('account.wallet.VIEW_AUTHENTICATION_STATUS', 'yproject'); ?></a>

	<?php else: ?>
		<h3><?php _e('account.wallet.SEND_MONEY_TO_WALLET', 'yproject'); ?></h3>
		<p class="align-justify">
			<?php _e('account.wallet.SEND_MONEY_TO_WALLET_ONLY_BANK_TRANSFER', 'yproject'); ?><br>
			<?php _e('account.wallet.SEND_MONEY_TO_WALLET_WARNINGS', 'yproject'); ?><br>
			- <?php _e('account.wallet.SEND_MONEY_TO_WALLET_ONLY_BANK_TRANSFER_YOUR_NAME', 'yproject'); ?><br>
			- <?php _e('account.wallet.SEND_MONEY_TO_WALLET_IBAN_CHANGED', 'yproject'); ?><br><br>
		</p>

		<div class="align-center" id="button-load-viban-<?php echo $WDGUser_displayed->get_wpref(); ?>">
			<button type="button" class="button blue button-load-viban"
				data-iban-user="<?php echo $WDGUser_displayed->get_wpref(); ?>"
				data-alert="<?php _e('account.wallet.LOAD_VIBAN_ALERT', 'yproject'); ?>"><?php _e('account.wallet.LOAD_VIBAN', 'yproject'); ?></button>
			<br><br>
			<img id="ajax-viban-loader-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="hidden"
				src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement">
		</div>

		<p id="loaded-iban-<?php echo $WDGUser_displayed->get_wpref(); ?>" class="loaded-iban hidden">
			<strong><?php _e('account.wallet.RECIPIENT_BANK_ACCOUNT', 'yproject'); ?></strong><br>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/footer/lemonway-gris.png" class="wire-lw right"
				alt="logo Lemonway" width="250">
			<strong><?php _e('account.bank.BANK_ACCOUNT_OWNER', 'yproject'); ?></strong> <span
				class="reload-bank-owner">LEMON WAY</span><br>
			<strong><?php _e('account.bank.IBAN', 'yproject'); ?></strong> <span class="reload-bank-iban"></span><br>
			<strong><?php _e('account.bank.BIC', 'yproject'); ?></strong> <span class="reload-bank-bic"></span><br>
			<span class="reload-bank-lwid-container hidden"><strong><?php _e('account.bank.CODE', 'yproject'); ?></strong>
				<span class="reload-bank-lwid"></span></span>

		</p>
		<br><br>

		<?php if (!$page_controler->is_iban_validated()): ?>
			<h3><?php _e('account.wallet.TRANSFER_TO_MY_BANK_ACCOUNT', 'yproject'); ?></h3>

			<?php if ($page_controler->is_iban_waiting()): ?>
				<?php _e('account.wallet.RIB_AWAITING_VALIDATION', 'yproject'); ?>
				<br><br>

			<?php else: ?>
				<?php if ($WDGUser_displayed->get_lemonway_iban_status() == WDGUser::$iban_status_rejected): ?>
					<?php _e('account.wallet.RIB_REJECTED', 'yproject'); ?><br>
				<?php endif; ?>
				<?php _e('account.wallet.INPUT_YOUR_BANK_ACCOUNT_DETAILS', 'yproject'); ?><br><br>
				<a href="#bank" class="button blue go-to-tab"
					data-tab="bank"><?php _e('account.menu.MY_BANK_INFO', 'yproject'); ?></a>
				<br><br>

			<?php endif; ?>

		<?php elseif ($lw_wallet_amount > 0): ?>
			<h3><?php _e('account.wallet.TRANSFER_TO_MY_BANK_ACCOUNT', 'yproject'); ?></h3>

			<form action="" method="POST" enctype="multipart/form-data" class="db-form v3 full align-left">
				<input type="hidden" name="action" value="user_wallet_to_bankaccount">
				<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>">

				<div id="field-amount_to_bank" class="field field-text-money">
					<label
						for="amount_to_bank"><?php echo sprintf(__('account.wallet.AMOUNT_TO_TRANSFER', 'yproject'), UIHelpers::format_number($lw_wallet_amount)); ?></label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="amount_to_bank" id="amount_to_bank"
								value="<?php echo $lw_wallet_amount; ?>" class="format-number">
							<span class="field-money">&euro;</span>
						</span>
					</div>
				</div>

				<?php $WDGUser_lw_bank_info = $page_controler->get_current_user_iban(); ?>
				<strong><?php _e('account.wallet.ACCOUNT_LINKED', 'yproject'); ?></strong><br>
				<?php echo $WDGUser_lw_bank_info->HOLDER; ?><br>
				<?php echo $WDGUser_lw_bank_info->DATA; ?><br>
				<?php echo $WDGUser_lw_bank_info->SWIFT; ?><br>
				<br><br>

				<a href="#bank" class="button transparent go-to-tab"
					data-tab="bank"><?php _e('account.wallet.MODIFY_RIB', 'yproject'); ?></a>
				<br><br>
				<button type="submit"
					class="button blue"><?php _e('account.wallet.TRANSFER_TO_MY_BANK_ACCOUNT', 'yproject'); ?></button>
			</form>
			<br><br>

		<?php endif; ?>



		<h3><?php _e('account.wallet.TRANSACTIONS_HISTORY', 'yproject'); ?></h3>
		<p id="transactions-note" style="display:none">Seules vos 10 dernières transactions sont affichées ici</p>

		<span class="hidden">
			<span
				id="transaction-trans-download_history"><?php _e('account.wallet.transactions.DOWNLOAD_HISTORY', 'yproject'); ?></span>
			<span
				id="transaction-trans-info_elements"><?php _e('account.wallet.transactions.VIEW_ELEMENT_ON_ELEMENTS', 'yproject'); ?></span>
			<span
				id="transaction-trans-info_elements_empty"><?php _e('account.wallet.transactions.VIEW_ELEMENT_ON_ELEMENTS_EMPTY', 'yproject'); ?></span>
			<span
				id="transaction-trans-nav_previous"><?php _e('account.wallet.transactions.NAV_PREVIOUS', 'yproject'); ?></span>
			<span id="transaction-trans-nav_next"><?php _e('account.wallet.transactions.NAV_NEXT', 'yproject'); ?></span>
		</span>
		<div class="user-transactions-init db-form v3 align-left">
			<a type="submit" id="history-download" class="button blue" style="display:none;"
				data-userid="<?php echo $page_controler->get_current_user()->get_wpref(); ?>">TELECHARGER MON HISTORIQUE
				COMPLET</a>
			<button type="submit" class="button blue"
				data-userid="<?php echo $page_controler->get_current_user()->get_wpref(); ?>"><?php _e('account.wallet.VIEW_TRANSACTIONS_HISTORY', 'yproject'); ?></button>
			<div class="loading align-center hidden">
				<br>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="chargement" />
			</div>
		</div>
		<div class="modal-download-transaction"
			style="display:none;position: fixed;z-index: 100;width: 700;height: 200px;background: white;border: 2px solid #00879b;align-items: center;justify-content: center;flex-direction: column;top: 50%;left: 50%;transform: translate(-50%, -50%);padding: 15px;text-align: center;">
			<p>Votre export est en cours de génération, cela prendra jusqu'à 5 minutes.<br>Veillez à ne pas quitter ou recharger cet page tant que votre export n'a pas été téléchargé.</p>
			<p>Néanmoins vous pouvez toujours naviguer dans d'autres onglets.</p>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="70" alt="chargement" />
		</div>
	<?php endif; ?>

	<br><br>
</div>