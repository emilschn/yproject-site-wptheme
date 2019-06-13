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
	
	<?php if ( $page_controler->is_card_shortcut_displayed() ): ?>
		<button type="submit" name="action" value="paywithcard" class="button red"><?php _e( "Payer par carte", 'yproject' ); ?></button>
		<div class="clear"><br></div>

		<button type="submit" name="action" value="paywithmandate" class="button transparent"><?php _e( "Payer par pr&eacute;l&eacute;vement bancaire", 'yproject' ); ?></button>
		<div class="clear"><br></div>
	
	<?php else: ?>
		<button type="submit" name="action" value="paywithmandate" class="button red"><?php _e( "Payer par pr&eacute;l&eacute;vement bancaire", 'yproject' ); ?></button>
		<div class="clear"><br></div>

		<button type="submit" name="action" value="paywithcard" class="button transparent"><?php _e( "Payer par carte", 'yproject' ); ?></button>
		<div class="clear"><br></div>
	<?php endif; ?>

</form>