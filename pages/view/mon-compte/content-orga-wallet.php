<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
global $WDGOrganization;
?>

<h2 class="underlined">Porte-monnaie électronique de <?php echo $WDGOrganization->get_name(); ?></h2>

Vous disposez de <?php echo $WDGOrganization->get_rois_amount(); ?> &euro; sur un total de <?php echo $WDGOrganization->get_lemonway_balance(); ?> &euro; dans votre porte-monnaie.
<br><br>

<?php if ( !$WDGOrganization->is_document_lemonway_registered( LemonwayDocument::$document_type_bank ) ): ?>
	<?php _e( "Afin de lutter contre la fraude et le blanchiment d'argent, il est n&eacute;cessaire que votre RIB soit contr&ocirc;l&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
	<?php _e( "Le compte bancaire qui vous permettra de r&eacute;cup&eacute;rer l'argent doit &ecirc;tre &agrave; votre nom.", 'yproject' ); ?><br>
	
	<?php if ( $WDGOrganization->get_document_lemonway_status( LemonwayDocument::$document_type_bank ) == LemonwayDocument::$document_status_waiting ): ?>
		<br>
		<?php _e( "Votre RIB est en cours de validation par notre prestataire de paiement. Merci de revenir d'ici 48h pour vous assurer de sa validation.", 'yproject' ); ?><br>
	
	<?php else: ?>
		<?php if ( $WDGOrganization->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
			<?php echo $WDGOrganization->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?><br>
		<?php endif; ?>
		<br>
		<form action="" method="POST" enctype="multipart/form-data">
			<label for="holdername" class="large-label"><?php _e( "Nom du propri&eacute;taire du compte :", 'yproject' ); ?></label>
				<input type="text" id="holdername" name="holdername" value="<?php echo $WDGOrganization->get_bank_owner(); ?>">
				<br>
			<label for="address" class="large-label"><?php _e( "Adresse du compte :", 'yproject' ); ?></label>
				<input type="text" id="address" name="address" value="<?php echo $WDGOrganization->get_bank_address(); ?>">
				<br>
			<label for="iban" class="large-label"><?php _e( "IBAN :", 'yproject' ); ?></label>
				<input type="text" id="iban" name="iban" value="<?php echo $WDGOrganization->get_bank_iban(); ?>">
				<br>
			<label for="bic" class="large-label"><?php _e( "BIC :", 'yproject' ); ?></label>
				<input type="text" id="bic" name="bic" value="<?php echo $WDGOrganization->get_bank_bic(); ?>">
				<br>
			<label for="rib" class="large-label"><?php _e( "Fichier de votre RIB :", 'yproject' ); ?></label>
				<input type="file" id="rib" name="rib">
				<br>
				<br>
			<p class="align-center">
				<input type="submit" class="button" value="<?php _e( "Enregistrer", 'yproject' ); ?>" />
			</p>
			<input type="hidden" name="action" value="register_rib" />
			<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
			<input type="hidden" name="orga_id" value="<?php echo $WDGOrganization->get_wpref(); ?>" />
		</form>
	<?php endif; ?>

<?php elseif ($amount > 0): ?>
	<form action="" method="POST" enctype="multipart/form-data">
		<p class="align-center">
			<input type="submit" class="button" value="Reverser sur mon compte bancaire" />
		</p>
		<input type="hidden" name="action" value="user_wallet_to_bankaccount" />
		<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
		<input type="hidden" name="orga_id" value="<?php echo $WDGOrganization->get_wpref(); ?>" />
	</form>
	<br><br>
	
<?php endif; ?>
