<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center">
	
	<h2><?php _e( "Je choisis mon mode de paiement :", 'yproject' ); ?></h2>
	
	<div class="mean-payment-list">

		<?php if ( $page_controler->can_use_wallet() ): ?>
			<a href="#" id="mean-payment-wallet" class="mean-payment mean-payment-button alert-confirm" data-alertconfirm="<?php _e( "Vous allez valider le transfert d'argent de votre porte-monnaie vers celui du projet.", 'yproject' ); ?>">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-porte-monnaie.png" alt="<?php _e( "Porte-monnaie WEDOGOOD", 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( "Porte-monnaie WE DO GOOD", 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( "Je dispose actuellement de %s &euro; sur mon porte-monnaie.", 'yproject' ), $page_controler->get_lemonway_amount() ); ?></span>
				</div>
			</a>

		<?php elseif ( $page_controler->can_use_card_and_wallet() ): ?>
			<a href="#" id="mean-payment-cardwallet" class="mean-payment mean-payment-button">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb-pm.png" alt="<?php _e( "Carte bancaire et porte-monnaie WEDOGOOD", 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( "Porte-monnaie WE DO GOOD et carte bancaire", 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( "Je dispose actuellement de %s &euro; sur mon porte-monnaie.", 'yproject' ), $page_controler->get_lemonway_amount() ); ?></span><br>
					<span><?php echo sprintf( __( "Je paierai %s &euro; via carte bancaire.", 'yproject' ), $page_controler->get_remaining_amount() ); ?></span><br>
					<span><?php _e( "CB, Visa, Mastercard ; e-carte bleue provisoire non accept&eacute;e", 'yproject' ); ?></span>
				</div>

				<?php global $mean_of_payment; $mean_of_payment = 'cardwallet'; ?>
				<?php locate_template( array( 'pages/view/moyen-de-paiement/card-choice.php'  ), true, false ); ?>
			</a>
		<?php endif; ?>
		
		
		<?php if ( $page_controler->can_use_card() ): ?>
			<a href="#" id="mean-payment-card" class="mean-payment mean-payment-button">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( "Carte bancaire", 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( "Carte bancaire", 'yproject' ); ?></span><br>
					<span><?php _e( "CB, Visa, Mastercard ; e-carte bleue provisoire non accept&eacute;e", 'yproject' ); ?></span><br>
					<span><?php _e( "D&eacute;bit imm&eacute;diat, remboursement int&eacute;gral si la lev&eacute;e de fonds &eacute;choue.", 'yproject' ); ?></span>
					<?php if ( $page_controler->display_card_amount_alert() ): ?>
					<br>
					<span><?php _e( "Attention : le montant que vous souhaitez investir risque de d&eacute;passer le plafond de paiement de votre carte. Si vous avez un message d'erreur, contactez votre banque pour augmenter votre plafond de paiement par carte ou choisissez un autre mode de paiement.", 'yproject' ); ?></span>
					<?php endif; ?>
				</div>

				<?php global $mean_of_payment; $mean_of_payment = 'card'; ?>
				<?php locate_template( array( 'pages/view/moyen-de-paiement/card-choice.php'  ), true, false ); ?>
			</a>
		
		<?php else: ?>
			<p class="disabled mean-payment">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( "Carte bancaire", 'yproject' ); ?>" width="120">
				<span>
					<span class="mean-payment-name"><?php _e( "Carte bancaire", 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( "Vous avez d&eacute;pass&eacute; le nombre d'investissement possible par carte", 'yproject' ), ATCF_Campaign::$invest_amount_min_wire ); ?></span>
				</span>
			</p>
		<?php endif; ?>

		
		<?php if ( $page_controler->can_use_wire() ): ?>
			<a href="#" id="mean-payment-wire" class="mean-payment mean-payment-button">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( "Virement bancaire", 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( "Virement bancaire", 'yproject' ); ?></span><br>
					<span><?php _e( "Le virement doit &ecirc;tre fait depuis un compte bancaire &agrave; votre nom.", 'yproject' ); ?></span><br>
					<span><?php _e( "Le RIB de notre prestataire sera indiqu&eacute; sur la page suivante, ainsi qu'un code destinataire sp&eacute;cifique &agrave; transmettre imp&eacute;rativement lors du virement.", 'yproject' ); ?></span>
				</div>
			</a>
		
		<?php elseif ( $page_controler->display_inactive_wire() ): ?>
			<p class="disabled mean-payment">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( "Virement bancaire", 'yproject' ); ?>" width="120">
				<span>
					<span class="mean-payment-name"><?php _e( "Virement bancaire", 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( "Les virements bancaires sont autoris&eacute;s &agrave; partir de %s &euro; d'investissement", 'yproject' ), ATCF_Campaign::$invest_amount_min_wire ); ?></span>
				</span>
			</p>
		<?php endif; ?>

			
		<?php if ( $page_controler->can_use_check() ): ?>
			<a href="#" id="mean-payment-check" class="mean-payment mean-payment-button">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cheque.png" alt="<?php _e( "Ch&egrave;que", 'yproject' ); ?>" width="120">
				<div>
					<span class="mean-payment-name"><?php _e( "Ch&egrave;que", 'yproject' ); ?></span><br>
					<span><?php _e( "Pour une comptabilisation plus rapide, munissez-vous d'une photo de ce ch&egrave;que.", 'yproject' ); ?></span><br>
					<span><?php _e( "Le ch&egrave;que reste en attente d'encaissement jusqu'&agrave; la r&eacute;ussite de la lev&eacute;e de fonds.", 'yproject' ); ?></span>
				</div>
			</a>
			
		<?php elseif ( $page_controler->display_inactive_check() ): ?>
			<p class="disabled mean-payment">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cheque.png" alt="<?php _e("Ch&egrave;que", 'yproject'); ?>" width="120">
				<span>
					<span class="mean-payment-name"><?php _e( "Ch&egrave;que", 'yproject' ); ?></span><br>
					<span><?php echo sprintf( __( "Les paiements par ch&egrave;que sont autoris&eacute;s &agrave; partir de %s &euro; d'investissement. L'authentification de votre compte ne sera n&eacute;cessaire qu'apr&egrave;s le 1er versement de royalties. Vous pouvez par ailleurs n&eacute;gocier la date d'encaissement du ch&egrave;que avec le porteur de projet.", 'yproject' ), ATCF_Campaign::$invest_amount_min_check ); ?></span>
				</span>
			</p>
		<?php endif; ?>
	</div>
	
	<form id="form-navigation" action="<?php echo $page_controler->get_form_action(); ?>" method="post" class="db-form v3 full bg-white">

		<input type="hidden" id="input-meanofpayment" name="meanofpayment" value="">
		<input type="hidden" id="input-meanofpayment-card-type" name="meanofpayment-card-type" value="">
		<input type="hidden" id="input-meanofpayment-card-save" name="meanofpayment-card-save" value="">
		<button type="submit" name="nav" value="previous" class="button half left transparent"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
		<button type="submit" class="button half right red hidden"><?php _e( "Payer", 'yproject' ); ?></button>

		<div class="clear"></div>
	</form>
	
</div>
