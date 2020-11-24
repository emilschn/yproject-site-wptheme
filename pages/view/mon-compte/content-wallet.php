<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();

$override_current_user = filter_input( INPUT_GET, 'override_current_user' );
$suffix = '';
if ( !empty( $override_current_user ) ) {
	$suffix = '?override_current_user=' .$override_current_user;
}
$lw_wallet_amount = $WDGUser_displayed->get_lemonway_wallet_amount();
$pending_amount = $WDGUser_displayed->get_pending_rois_amount();
?>

<h2><?php _e( 'account.wallet.TITLE', 'yproject' ); ?></h2>

<div class="db-form v3 align-left"  id="item-body-wallet">

	<div class="wallet-preview">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-invest/picto-porte-monnaie.png" alt="porte-monnaie" width="100" height="69">
		<div>
			<span><?php echo UIHelpers::format_number( $lw_wallet_amount ); ?> &euro;</span><br>
			<span><?php _e( 'common.AVAILABLE.P', 'yproject' ); ?></span>
		</div>
		<a href="<?php echo home_url( '/les-projets/' ); ?>?source=account" class="button red half"><?php _e( 'common.INVEST', 'yproject' ); ?></a>
	</div>

	<?php if ( !$WDGUser_displayed->is_lemonway_registered() ): ?>
		<div class="wdg-message error msg-authentication-alert">
			<?php if ( $pending_amount > 0 ): ?>
				<?php echo sprintf( __( 'account.wallet.AWAITING_AUTHENTICATION', 'yproject' ), UIHelpers::format_number( $pending_amount ) ); ?><br><br>
			<?php endif; ?>

			<?php _e( 'account.wallet.AUTHENTICATION_NECESSARY', 'yproject' ); ?>
		</div>

		<a href="#authentication" class="button red go-to-tab" data-tab="authentication"><?php _e( 'account.wallet.VIEW_AUTHENTICATION_STATUS', 'yproject' ); ?></a>

	<?php else: ?>
		<h3><?php _e( 'account.wallet.SEND_MONEY_TO_WALLET', 'yproject' ); ?></h3>
		<p class="align-justify">
			<?php _e( 'account.wallet.SEND_MONEY_TO_WALLET_ONLY_BANK_TRANSFER', 'yproject' ); ?><br>
			<?php _e( 'account.wallet.SEND_MONEY_TO_WALLET_ONLY_BANK_TRANSFER_YOUR_NAME', 'yproject' ); ?><br><br>
		</p>

		<strong><?php _e( 'account.wallet.RECIPIENT_BANK_ACCOUNT', 'yproject' ); ?></strong><br>
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/footer/lemonway-gris.png" class="wire-lw right" alt="logo Lemonway" width="250">
		<strong><?php _e( 'account.bank.BANK_ACCOUNT_OWNER', 'yproject' ); ?></strong> LEMON WAY<br>
		<strong><?php _e( 'account.bank.IBAN', 'yproject' ); ?></strong> FR76 3000 4025 1100 0111 8625 268<br>
		<strong><?php _e( 'account.bank.BIC', 'yproject' ); ?></strong> BNPAFRPPIFE
		<br><br>
		
		<p class="align-justify">
			<strong><?php _e( 'account.wallet.TRANSFER_ID_CODE', 'yproject' ); ?></strong> <span id="clipboard-user-lw-code">wedogood-<?php echo $WDGUser_displayed->get_lemonway_id(); ?></span><br>
			<div class="align-center">
				<button type="button" class="button blue copy-clipboard" data-clipboard="clipboard-user-lw-code"><?php _e( 'account.wallet.COPY_CODE', 'yproject' ); ?></button>
				<span class="hidden"><?php _e( 'account.wallet.CODE_COPIED', 'yproject' ); ?></span>
			</div>
			<br><br>
			<i><?php _e( 'account.wallet.WHERE_TO_COPY_THE_CODE', 'yproject' ); ?></i>
			<br><br>
		</p>

		<?php if ( !$page_controler->is_iban_validated() ): ?>
			<h3><?php _e( 'account.wallet.TRANSFER_TO_MY_BANK_ACCOUNT', 'yproject' ); ?></h3>

			<?php if ( $page_controler->is_iban_waiting() ): ?>
				<?php _e( 'account.wallet.RIB_AWAITING_VALIDATION', 'yproject' ); ?>
				<br><br>

			<?php else: ?>
				<?php if ( $WDGUser_displayed->get_lemonway_iban_status() == WDGUser::$iban_status_rejected ): ?>
					<?php _e( 'account.wallet.RIB_REJECTED', 'yproject' ); ?><br>
				<?php endif; ?>
				<?php _e( 'account.wallet.INPUT_YOUR_BANK_ACCOUNT_DETAILS', 'yproject' ); ?><br><br>
				<a href="#bank" class="button blue go-to-tab" data-tab="bank"><?php _e( 'account.menu.MY_BANK_INFO', 'yproject' ); ?></a>
				<br><br>

			<?php endif; ?>

		<?php elseif ( $lw_wallet_amount > 0 ): ?>
			<h3><?php _e( 'account.wallet.TRANSFER_TO_MY_BANK_ACCOUNT', 'yproject' ); ?></h3>

			<form action="" method="POST" enctype="multipart/form-data" class="db-form v3 full align-left">
				<input type="hidden" name="action" value="user_wallet_to_bankaccount">
				<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>">

				<div id="field-amount_to_bank" class="field field-text-money">
					<label for="amount_to_bank"><?php echo sprintf( __( 'account.wallet.AMOUNT_TO_TRANSFER', 'yproject' ), UIHelpers::format_number( $lw_wallet_amount ) ); ?></label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="amount_to_bank" id="amount_to_bank" value="<?php echo $lw_wallet_amount; ?>" class="format-number">
							<span class="field-money">&euro;</span>
						</span>
					</div>
				</div>

				<?php $WDGUser_lw_bank_info = $page_controler->get_current_user_iban(); ?>
				<strong><?php _e( 'account.wallet.ACCOUNT_LINKED', 'yproject' ); ?></strong><br>
				<?php echo $WDGUser_lw_bank_info->HOLDER; ?><br>
				<?php echo $WDGUser_lw_bank_info->DATA; ?><br>
				<?php echo $WDGUser_lw_bank_info->SWIFT; ?><br>
				<br><br>

				<a href="#bank" class="button transparent go-to-tab" data-tab="bank"><?php _e( 'account.wallet.MODIFY_RIB', 'yproject' ); ?></a>
				<br><br>
				<button type="submit" class="button blue"><?php _e( 'account.wallet.TRANSFER_TO_MY_BANK_ACCOUNT', 'yproject' ); ?></button>
			</form>
			<br><br>

		<?php endif; ?>



		<h3><?php _e( 'account.wallet.TRANSACTIONS_HISTORY', 'yproject' ); ?></h3>
		<div class="user-transactions-init db-form v3 align-left">
			<button type="submit" class="button blue" data-userid="<?php echo $page_controler->get_current_user()->get_wpref(); ?>"><?php _e( 'account.wallet.VIEW_TRANSACTIONS_HISTORY', 'yproject' ); ?></button>
			<div class="loading align-center hidden">
				<br>
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="chargement" />
			</div>
		</div>
		
	<?php endif; ?>

	<br><br>
</div>