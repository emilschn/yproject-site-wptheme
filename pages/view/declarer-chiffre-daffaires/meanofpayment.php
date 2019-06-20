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
		</div>

		<?php if ( $page_controler->has_sign_mandate() ): ?>
		<div class="mean-payment" data-meanofpayment="mandate">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( "Pr&eacute;l&egrave;vement bancaire", 'yproject' ); ?>" width="120">
			<div>
				<span class="mean-payment-name"><?php _e( "Pr&eacute;l&egrave;vement bancaire", 'yproject' ); ?></span><br>
				<span><?php _e( "Le virement n'est d&eacute;finitivement pris en compte que lors de l'authentification de votre compte par notre prestataire de paiement. Une copie de votre pi&egrave;ce d'identit&eacute; et un justificatif de domicile seront n&eacute;cessaires", 'yproject' ); ?></span>
			</div>
		</div>

		<?php elseif ( !$page_controler->is_card_shortcut_displayed() ): ?>
		<div class="mean-payment" data-meanofpayment="wire">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( "Virement bancaire", 'yproject' ); ?>" width="120">
			<div>
				<span class="mean-payment-name"><?php _e( "Virement bancaire", 'yproject' ); ?></span><br>
				<span><?php _e( "Doit &ecirc;tre fait depuis le compte bancaire de l'entreprise vers l'IBAN de Lemon Way : FR76 3000 4025 1100 0111 8625 268.", 'yproject' ); ?></span><br>
				<span><?php _e( "Il faut imp&eacute;rativement indiquer le code destinataire ou b&eacute;n&eacute;ficiaire suivant :", 'yproject' ); ?> <?php echo $page_controler->get_current_campaign_organization_wallet_id(); ?></span>
			</div>
		</div>
		<?php endif; ?>
	</div>
	
	
	<input type="hidden" id="input-meanofpayment" name="meanofpayment" value="">
	<button type="submit" name="action" value="gobacktodeclaration" class="button half left transparent"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
	<button type="submit" name="action" value="proceedpayment" class="button half right red hidden"><?php _e( "Payer", 'yproject' ); ?></button>
	<div class="clear"></div>

</form>