<?php
global $WDGOrganization;
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();

$lw_wallet_amount = $WDGOrganization->get_available_rois_amount();
$pending_amount = $WDGOrganization->get_pending_rois_amount();

?>

<h2><?php _e( 'account.wallet.orga.TITLE', 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h2>


<div class="db-form v3 align-left" id="item-body-wallet">

	<div class="wallet-preview">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-invest/picto-porte-monnaie.png" alt="porte-monnaie" width="100" height="69">
		<div>
			<span><?php echo UIHelpers::format_number( $lw_wallet_amount ); ?> &euro;</span><br>
			<span><?php _e( 'common.AVAILABLE.P', 'yproject' ); ?></span>
		</div>
		<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'les-projets' ); ?>?source=account" class="button red half account-button">
			<span class="button-text">
				<?php _e( 'common.INVEST', 'yproject' ); ?>
			</span>
			<span class="button-loading loading align-center hidden">
				<img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30" alt="chargement" /><?php _e( 'common.NEXT', 'yproject' ); ?>			
			</span>
		</a>
	</div>


	<?php if ( !$WDGOrganization->is_registered_lemonway_wallet() ): ?>
		<div class="wdg-message error msg-authentication-alert">
			<?php if ( $pending_amount > 0 ): ?>
				<?php echo sprintf( __( 'account.wallet.orga.AWAITING_AUTHENTICATION', 'yproject' ), UIHelpers::format_number( $pending_amount ) ); ?><br><br>
			<?php endif; ?>

			<?php _e( 'account.wallet.orga.AUTHENTICATION_NECESSARY', 'yproject' ); ?>
		</div>

		<a href="#authentication" class="button red go-to-tab" data-tab="orga-authentication-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( 'account.wallet.VIEW_AUTHENTICATION_STATUS', 'yproject' ); ?></a>

	<?php else: ?>
		<h3><?php _e( 'account.wallet.SEND_MONEY_TO_WALLET', 'yproject' ); ?></h3>
		<p class="align-justify">
			<?php _e( 'account.wallet.SEND_MONEY_TO_WALLET_ONLY_BANK_TRANSFER', 'yproject' ); ?><br>
			<?php _e( 'account.wallet.SEND_MONEY_TO_WALLET_WARNINGS', 'yproject' ); ?><br>
			- <?php _e( 'account.wallet.SEND_MONEY_TO_WALLET_ONLY_BANK_TRANSFER_YOUR_NAME', 'yproject' ); ?><br>
			- <?php _e( 'account.wallet.SEND_MONEY_TO_WALLET_IBAN_CHANGED', 'yproject' ); ?><br><br>
		</p>

		<div class="align-center" id="button-load-viban-<?php echo $WDGOrganization->get_wpref(); ?>">
			<button type="button" class="button blue button-load-viban" data-iban-user="<?php echo $WDGOrganization->get_wpref(); ?>" data-alert="<?php _e( 'account.wallet.LOAD_VIBAN_ALERT', 'yproject' ); ?>"><?php _e( 'account.wallet.LOAD_VIBAN', 'yproject' ); ?></button>
			<br><br>
			<img id="ajax-viban-loader-<?php echo $WDGOrganization->get_wpref(); ?>" class="hidden" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement">
		</div>

		<p id="loaded-iban-<?php echo $WDGOrganization->get_wpref(); ?>" class="loaded-iban hidden">
			<strong><?php _e( 'account.wallet.RECIPIENT_BANK_ACCOUNT', 'yproject' ); ?></strong><br>
			<img src="<?php echo $stylesheet_directory_uri; ?>/images/footer/lemonway-gris.png" class="wire-lw right" alt="logo Lemonway" width="250">
			<strong><?php _e( 'account.bank.BANK_ACCOUNT_OWNER', 'yproject' ); ?></strong> <span class="reload-bank-owner">LEMON WAY</span><br>
			<strong><?php _e( 'account.bank.IBAN', 'yproject' ); ?></strong> <span class="reload-bank-iban"></span><br>
			<strong><?php _e( 'account.bank.BIC', 'yproject' ); ?></strong> <span class="reload-bank-bic"></span>
			
		</p>
		<br><br>

		<?php if ( !$WDGOrganization->is_document_lemonway_registered( LemonwayDocument::$document_type_bank ) ): ?>
			<h3><?php _e( 'account.wallet.orga.TRANSFER_MY_BANK_ACCOUNT', 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h3>

			<?php if ( $WDGOrganization->get_document_lemonway_status( LemonwayDocument::$document_type_bank ) == LemonwayDocument::$document_status_waiting ): ?>
				<?php _e( 'account.wallet.orga.RIB_AWAITING_VALIDATION', 'yproject' ); ?><br>
				<br><br>

			<?php else: ?>
				<?php _e( 'account.wallet.orga.INPUT_YOUR_BANK_ACCOUNT_DETAILS', 'yproject' ); ?><br><br>
				<a href="#orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>" class="button blue go-to-tab" data-tab="orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( 'account.menu.organization.BANK_INFO', 'yproject' ); ?></a>
				<br><br>

			<?php endif; ?>

		<?php elseif ( $lw_wallet_amount > 0 ): ?>
			<h3><?php _e( 'account.wallet.orga.TRANSFER_TO_BANK_ACCOUNT', 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></h3>

			<form action="" method="POST" enctype="multipart/form-data" class="db-form v3 full align-left">
				<input type="hidden" name="action" value="user_wallet_to_bankaccount">
				<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>">
				<input type="hidden" name="orga_id" value="<?php echo $WDGOrganization->get_wpref(); ?>" />	

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

				<a href="#orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>" class="button transparent go-to-tab" data-tab="orga-bank-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( 'account.wallet.MODIFY_RIB', 'yproject' ); ?></a>
				<br><br>
				<button type="submit" class="button blue"><?php _e( 'account.wallet.orga.TRANSFER_TO_BANK_ACCOUNT', 'yproject' ); ?> <?php echo $WDGOrganization->get_name(); ?></button>
			</form>
			<br><br>

		<?php endif; ?>


		<h3><?php _e( 'account.wallet.TRANSACTIONS_HISTORY', 'yproject' ); ?></h3>
		<?php
		$transfers = get_posts( array(
			'author'		=> $WDGOrganization->get_wpref(),
			'numberposts'	=> -1,
			'post_type'		=> 'withdrawal_order_lw',
			'post_status'	=> 'any',
			'orderby'		=> 'post_date',
			'order'			=> 'DESC'
		) );
		?>

		<?php if ( $transfers ): ?>
		<ul class="user-history">
			<?php foreach ( $transfers as $transfer_post ): ?>

				<?php
				$post_amount = $transfer_post->post_title;
				?>
				<?php if ( $transfer_post->post_status == 'publish' ): ?>
					<li id="withdrawal-<?php echo $transfer_post->ID; ?>">
						<span><?php echo get_the_date( 'd/m/Y', $transfer_post ); ?></span>
						<span><?php echo UIHelpers::format_number( $post_amount ); ?> &euro;</span>
						<span><?php _e( 'account.wallet.orga.TRANSFERED_TO_BANK_ACCOUNT', 'yproject' ); ?></span>
					</li>
					
				<?php elseif ( $transfer_post->post_status == 'draft' ): ?>
					<li id="withdrawal-<?php echo $transfer_post->ID; ?>">
						<span><?php echo get_the_date( 'd/m/Y', $transfer_post ); ?></span>
						<span><?php echo UIHelpers::format_number( $post_amount ); ?> &euro;</span>
						<span><?php _e( 'account.wallet.orga.CANCELED', 'yproject' ); ?></span>
					</li>

				<?php else: ?>
					<li id="withdrawal-<?php echo $transfer_post->ID; ?>">
						<span><?php echo get_the_date( 'd/m/Y', $transfer_post ); ?></span>
						<span><?php echo UIHelpers::format_number( $post_amount ); ?> &euro;</span>
						<span><?php _e( 'account.wallet.orga.BEING_TRANSFERED', 'yproject' ); ?></span>
					</li>

				<?php endif; ?>

			<?php endforeach; ?>
		</ul>

		<?php else: ?>
			<?php _e( 'account.wallet.orga.NO_MONEY_TRANSFER', 'yproject' ); ?>
		<?php endif; ?>
		
	<?php endif; ?>
	<br><br>
</div>