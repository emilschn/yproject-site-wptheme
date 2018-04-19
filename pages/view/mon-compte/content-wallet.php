<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
?>

<?php
//Si on a demandé de renvoyer le code
if (isset($_GET['invest_id_resend']) && $_GET['invest_id_resend'] != '') {
	$contractid = ypcf_get_signsquidcontractid_from_invest($_GET['invest_id_resend']);
	// $signsquid_infos = signsquid_get_contract_infos($contractid);
	$signsquid_signatory = signsquid_get_contract_signatory($contractid);
	$current_user = wp_get_current_user();
	if ($signsquid_signatory != '' && $signsquid_signatory->{'email'} == $current_user->user_email) {
		if (ypcf_send_mail_purchase($_GET['invest_id_resend'], "send_code", $signsquid_signatory->{'code'}, $current_user->user_email)) {
			?>
			Votre code de signature de contrat a &eacute;t&eacute; renvoy&eacute; &agrave; l&apos;adresse <?php echo $current_user->user_email; ?>.<br />
			<?php
		} else {
			?>
			<span class="errors">Il y a eu une erreur lors de l&apos;envoi du code. N&apos;h&eacute;sitez pas &agrave; nous contacter.</span><br />
			<?php
		}
	} else {
	?>
	<span class="errors">Nous ne trouvons pas le contrat correspondant.</span><br />
	<?php
	}
}
?>
<h2 class="underlined">Mon porte-monnaie électronique</h2>

<?php $amount = $WDGUser_displayed->get_lemonway_wallet_amount(); ?>
Vous disposez de <?php echo $amount; ?> &euro; dans votre porte-monnaie.
<a href="<?php echo home_url( '/details-des-investissements' ); ?>">Voir le d&eacute;tail de mes royalties</a>
<br><br>

<?php if ( !$WDGUser_displayed->is_document_lemonway_registered( LemonwayDocument::$document_type_bank ) ): ?>
	<?php _e( "Afin de lutter contre la fraude et le blanchiment d'argent, il est n&eacute;cessaire que votre RIB soit contr&ocirc;l&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
	<?php _e( "Le compte bancaire qui vous permettra de r&eacute;cup&eacute;rer l'argent doit &ecirc;tre &agrave; votre nom.", 'yproject' ); ?><br>
	
	<?php if ( $WDGUser_displayed->get_document_lemonway_status( LemonwayDocument::$document_type_bank ) == LemonwayDocument::$document_status_waiting ): ?>
		<br>
		<?php _e( "Votre RIB est en cours de validation par notre prestataire de paiement. Merci de revenir d'ici 48h pour vous assurer de sa validation.", 'yproject' ); ?><br>
	
	<?php else: ?>
		<?php if ( $WDGUser_displayed->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
			<?php echo $WDGUser_displayed->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?><br>
		<?php endif; ?>
		<br>
		<form action="" method="POST" enctype="multipart/form-data">
			<label for="holdername" class="large-label"><?php _e( "Nom du propri&eacute;taire du compte :", 'yproject' ); ?></label>
				<input type="text" id="holdername" name="holdername" value="<?php echo $WDGUser_displayed->get_bank_holdername(); ?>">
				<br>
			<label for="address" class="large-label"><?php _e( "Adresse du compte :", 'yproject' ); ?></label>
				<input type="text" id="address" name="address" value="<?php echo $WDGUser_displayed->get_bank_address(); ?>">
				<br>
			<label for="address2" class="large-label"><?php _e( "Pays :", 'yproject' ); ?></label>
				<input type="text" id="address2" name="address2" value="<?php echo $WDGUser_displayed->get_bank_address2(); ?>">
				<br>
			<label for="iban" class="large-label"><?php _e( "IBAN :", 'yproject' ); ?></label>
				<input type="text" id="iban" name="iban" value="<?php echo $WDGUser_displayed->get_bank_iban(); ?>">
				<br>
			<label for="bic" class="large-label"><?php _e( "BIC :", 'yproject' ); ?></label>
				<input type="text" id="bic" name="bic" value="<?php echo $WDGUser_displayed->get_bank_bic(); ?>">
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
		</form>
	<?php endif; ?>

<?php elseif ($amount > 0): ?>
	<form action="" method="POST" enctype="multipart/form-data">
		<p class="align-center">
			<input type="submit" class="button" value="Reverser sur mon compte bancaire" />
		</p>
		<input type="hidden" name="action" value="user_wallet_to_bankaccount" />
		<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
	</form>
	<br><br>
	
<?php endif; ?>