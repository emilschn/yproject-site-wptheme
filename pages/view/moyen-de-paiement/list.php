<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center">
	
	<h2><?php _e( 'invest.mean-payment.TITLE', 'yproject' ); ?></h2>
	
	<div class="mean-payment-list">

		<?php if ( $page_controler->can_use_wallet() ): ?>
			<a href="#" id="mean-payment-wallet" class="mean-payment mean-payment-button alert-confirm" data-alertconfirm="<?php _e( 'invest.mean-payment.alert.TRANSFER', 'yproject' ); ?>">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-porte-monnaie.png" alt="<?php _e( 'invest.mean-payment.list.WALLET', 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( 'invest.mean-payment.list.WALLET', 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( 'invest.mean-payment.I_OWN', 'yproject' ), $page_controler->get_lemonway_amount() ); ?></span>
				</div>
			</a>

		<?php elseif ( $page_controler->can_use_card_and_wallet() ): ?>
			<a href="#" id="mean-payment-cardwallet" class="mean-payment mean-payment-button">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb-pm.png" alt="<?php _e( "Carte bancaire et porte-monnaie WEDOGOOD", 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( 'invest.mean-payment.list.CARD_WALLET', 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( 'invest.mean-payment.I_OWN', 'yproject' ), $page_controler->get_lemonway_amount() ); ?></span><br>
					<span><?php echo sprintf( __( 'invest.mean-payment.I_WILL_PAY_WITH_CARD', 'yproject' ), $page_controler->get_remaining_amount() ); ?></span><br>
					<span><?php _e( 'invest.mean-payment.CARD_DETAILS', 'yproject' ); ?></span>
				</div>

				<?php global $mean_of_payment; $mean_of_payment = 'cardwallet'; ?>
				<?php locate_template( array( 'pages/view/moyen-de-paiement/card-choice.php'  ), true, false ); ?>
			</a>
		<?php endif; ?>
		
		
		<?php if ( $page_controler->can_use_card() ): ?>
			<a href="#" id="mean-payment-card" class="mean-payment mean-payment-button">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( 'invest.mean-payment.list.CARD', 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( 'invest.mean-payment.list.CARD', 'yproject' ); ?></span><br>
					<span><?php _e( 'invest.mean-payment.CARD_DETAILS', 'yproject' ); ?></span><br>
					<span><?php _e( 'invest.mean-payment.CARD_DETAILS_DEBIT', 'yproject' ); ?></span>
					<?php if ( $page_controler->display_card_amount_alert() ): ?>
					<br>
					<span><?php _e( 'invest.mean-payment.CARD_DETAILS_ALERT', 'yproject' ); ?></span>
					<?php endif; ?>
				</div>

				<?php global $mean_of_payment; $mean_of_payment = 'card'; ?>
				<?php locate_template( array( 'pages/view/moyen-de-paiement/card-choice.php'  ), true, false ); ?>
			</a>
		
		<?php else: ?>
			<p class="disabled mean-payment">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( 'invest.mean-payment.list.CARD', 'yproject' ); ?>" width="120">
				<span>
					<span class="mean-payment-name"><?php _e( 'invest.mean-payment.list.CARD', 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( 'invest.mean-payment.error.PAYMENT_NUMBER', 'yproject' ), ATCF_Campaign::$invest_amount_min_wire ); ?></span>
				</span>
			</p>
		<?php endif; ?>

		
		<?php if ( $page_controler->can_use_wire() ): ?>
			<a href="#" id="mean-payment-wire" class="mean-payment mean-payment-button">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( 'invest.mean-payment.list.WIRE', 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( 'invest.mean-payment.list.WIRE', 'yproject' ); ?></span><br>
					<?php if ( $page_controler->can_use_wallet() ): ?>
						<span><?php _e( "Vous pouvez directement choisir votre porte-monnaie &eacute;lectronique comme moyen de paiement, pour gagner du temps.", 'yproject' ); ?></span><br>
					<?php endif; ?>
					<span><?php _e( 'account.wallet.SEND_MONEY_TO_WALLET_ONLY_BANK_TRANSFER_YOUR_NAME', 'yproject' ); ?></span><br>
					<span><?php _e( 'invest.mean-payment.WIRE_DETAILS', 'yproject' ); ?></span>
				</div>
			</a>
		
		<?php elseif ( $page_controler->display_inactive_wire() ): ?>
			<p class="disabled mean-payment">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( 'invest.mean-payment.list.WIRE', 'yproject' ); ?>" width="120">
				<span>
					<span class="mean-payment-name"><?php _e( 'invest.mean-payment.list.WIRE', 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( 'invest.mean-payment.error.WIRE_AMOUNT', 'yproject' ), ATCF_Campaign::$invest_amount_min_wire ); ?></span>
				</span>
			</p>
		<?php endif; ?>

			
		<?php if ( $page_controler->can_use_check() ): ?>
			<a href="#" id="mean-payment-check" class="mean-payment mean-payment-button">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cheque.png" alt="<?php _e( 'invest.mean-payment.list.CHECK', 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( 'invest.mean-payment.list.CHECK', 'yproject' ); ?></span><br>
					<span><?php _e( 'invest.mean-payment.CHECK_DETAILS_FRANCE', 'yproject' ); ?></span><br>
					<span><?php _e( 'invest.mean-payment.CHECK_DETAILS', 'yproject' ); ?></span><br>
					<span><?php _e( 'invest.mean-payment.CHECK_DETAILS_PENDING', 'yproject' ); ?></span>
				</div>
			</a>
			
		<?php elseif ( $page_controler->display_inactive_check() ): ?>
			<p class="disabled mean-payment">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cheque.png" alt="<?php _e( 'invest.mean-payment.list.CHECK', 'yproject' ); ?>" width="120">
				<span>
					<span class="mean-payment-name"><?php _e( 'invest.mean-payment.list.CHECK', 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( 'invest.mean-payment.error.CHECK_AMOUNT', 'yproject' ), ATCF_Campaign::$invest_amount_min_check ); ?></span>
				</span>
			</p>
		<?php endif; ?>
	</div>
	
	<form id="form-navigation" action="<?php echo $page_controler->get_form_action(); ?>" method="post" class="db-form v3 full bg-white investment-form">

		<input type="hidden" id="input-meanofpayment" name="meanofpayment" value="">
		<input type="hidden" id="input-meanofpayment-card-type" name="meanofpayment-card-type" value="">
		<input type="hidden" id="input-meanofpayment-card-save" name="meanofpayment-card-save" value="">
		<a href="<?php echo $page_controler->get_previous_url(); ?>" class="button half left transparent">
			<span class="button-text">
				<?php _e( 'common.PREVIOUS', 'yproject' ); ?>
			</span>
			<span class="button-loading loading align-center hidden">
				<img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="chargement" /><?php _e( 'common.PREVIOUS', 'yproject' ); ?>...
			</span>
		</a>
		<button type="submit" class="button half right red hidden">
			<span class="button-text">
				<?php _e( 'invest.mean-payment.PAY', 'yproject' ); ?>
			</span>
			<span class="button-loading loading align-center hidden">
				<img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30" alt="chargement" /><?php _e( 'common.PAYING', 'yproject' ); ?>
			</span>
		</button>
		<div class="clear"></div>
	</form>
	
</div>
