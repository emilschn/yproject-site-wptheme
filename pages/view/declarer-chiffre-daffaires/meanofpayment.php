<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<form action="<?php echo $page_controler->get_form_action(); ?>" method="post" class="db-form v3 full bg-white">
	
	<?php if ( $page_controler->can_display_payment_error() ): ?>
		<div class="wdg-message error">
			<?php _e( "Une erreur s'est produite pendant la tentative de paiement", 'yproject' ); ?>
		</div>
		<br><br>
	<?php endif; ?>
	
	<?php echo sprintf( __( "Vous allez proc&eacute;der &agrave; un r&egrave;glement de %s &euro;.", 'yproject' ), YPUIHelpers::display_number( $page_controler->get_current_declaration_amount() ) ); ?>
	<br><br>
	
	<div class="mean-payment-list">
		<div class="mean-payment" data-meanofpayment="card">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( "Carte bancaire", 'yproject' ); ?>" width="120">
			<div>
				<span class="mean-payment-name"><?php _e( "Carte bancaire", 'yproject' ); ?></span><br>
				<span><?php _e( "CB, Visa, Mastercard ; e-carte bleue provisoire non accept&eacute;e", 'yproject' ); ?></span><br>
				<span><?php _e( "D&eacute;bit imm&eacute;diat.", 'yproject' ); ?></span>
			</div>

			<?php global $mean_of_payment; $mean_of_payment = 'card'; ?>
			<?php locate_template( array( 'pages/view/moyen-de-paiement/card-choice.php'  ), true, false ); ?>
		</div>

		<?php if ( $page_controler->has_sign_mandate() ): ?>
		<div class="mean-payment" data-meanofpayment="mandate">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( "Pr&eacute;l&egrave;vement bancaire", 'yproject' ); ?>" width="120">
			<div>
				<span class="mean-payment-name">
					<?php if ( $page_controler->get_current_campaign_organization()->is_mandate_b2b() ): ?>
						<?php _e( "Pr&eacute;l&egrave;vement bancaire (mandat de type B2B)", 'yproject' ); ?>
					<?php else: ?>
						<?php _e( "Pr&eacute;l&egrave;vement bancaire (mandat de type Core)", 'yproject' ); ?>
					<?php endif; ?>
				</span><br>
				<span><?php _e( "Le pr&eacute;l&egrave;vement bancaire s'effectuera sur le compte bancaire dont l'IBAN est le suivant :", 'yproject' ); ?> <strong><?php echo $page_controler->get_mandate_infos(); ?></strong></span><br><br>
				<span><?php _e( "Merci de vous assurer que les fonds sont suffisants sur ce compte. En cas d'annulation, notre prestataire de services de paiement nous pr&eacute;l&egrave;ve des frais que <strong>nous vous refacturerons &agrave; hauteur de 30 € HT</strong>.", 'yproject' ); ?></span><br><br>
				<span><?php _e( "De plus, l'utilisation de ce moyen de paiement provoque un d&eacute;lai de versement &agrave; vos investisseurs de <strong>10 jours</strong> &agrave; compter de la r&eacute;ception de votre paiement.", 'yproject' ); ?></span>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( $page_controler->can_display_wire() ): ?>
		<div class="mean-payment" data-meanofpayment="wire">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( "Virement bancaire", 'yproject' ); ?>" width="120">
			<div>
				<span class="mean-payment-name"><?php _e( "Virement bancaire", 'yproject' ); ?></span><br>
				<span><?php _e( "Doit &ecirc;tre fait depuis le compte bancaire de l'entreprise vers l'IBAN de Lemon Way : FR76 3000 4025 1100 0111 8625 268.", 'yproject' ); ?></span><br>
				<span><?php _e( "Il faut imp&eacute;rativement indiquer le code destinataire ou b&eacute;n&eacute;ficiaire suivant :", 'yproject' ); ?></span><br>
				<strong><span id="clipboard-user-lw-code">wedogood-<?php echo $page_controler->get_current_campaign_organization_wallet_id(); ?></span></strong>
				<br><br>
				<div class="align-center">
					<button type="button" class="button blue copy-clipboard" data-clipboard="clipboard-user-lw-code"><?php _e( "Copier le code", 'yproject' ); ?></button>
					<span class="hidden"><?php _e( "Code copi&eacute;", 'yproject' ); ?></span>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
	
	
	<input type="hidden" id="input-meanofpayment" name="meanofpayment" value="">
	<input type="hidden" id="input-meanofpayment-card-type" name="meanofpayment-card-type" value="">
	<input type="hidden" id="input-meanofpayment-card-save" name="meanofpayment-card-save" value="">
	<button type="submit" name="action" value="gobacktosummary" class="button half left transparent"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
	<button type="submit" name="action" value="proceedpayment" class="button half right red hidden"><?php _e( "Payer", 'yproject' ); ?></button>
	<div class="clear"></div>

</form>

<br><br>
