<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<br><br>

<?php if ( $page_controler->needs_two_contracts() ): ?>
	<?php _e( "Votre investissement est valid&eacute; pour un montant de ", 'yproject' ); ?> <?php echo $page_controler->get_maximum_investable_amount(); ?> &euro;.<br>
	<?php _e( "Votre compte bancaire a &eacute;t&eacute; d&eacute;bit&eacute;.", 'yproject' ); ?><br>
	<?php _e( "Vous allez recevoir un e-mail &agrave; l&apos;adresse", 'yproject' ); ?> <?php echo $page_controler->get_current_user_email(); ?> (<?php _e( "pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable", 'yproject' ); ?>).<br>
	<?php _e( "Votre contrat d&apos;investissement sera joint &agrave; cet e-mail.", 'yproject' ); ?><br><br>
		
	<?php if ( $page_controler->is_preinvestment() ): ?>
		<?php _e( "Nous vous rappelons que les conditions que vous avez accept&eacute;es sont susceptibles d'&ecirc;tre modifi&eacutes;es &agrave; l'issue de la phase de vote.", 'yproject' ); ?><br>
		<?php _e( "Si aucun changement ne survient, votre investissement sera valid&eacute; automatiquement.", 'yproject' ); ?><br>
		<?php _e( "Si un changement devait survenir, vous devrez confirmer ou infirmer votre investissement.", 'yproject' ); ?><br><br>
	<?php endif; ?>
	
	<?php _e( "Lorsque vos justificatifs d'identit&eacute; seront valid&eacute;s, nous d&eacute;clencherons automatiquement un investissement par carte pour le montant restant :", 'yproject' ); ?> <?php echo $page_controler->get_remaining_amount_to_invest(); ?> &euro;.<br><br>
	
	<?php
	$WDGUserIdentityDocsForm = $page_controler->get_identitydocs_form();
	$form_feedback = $page_controler->get_identitydocs_form_feedback();
	$fields_hidden = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_hidden );
	$fields_files = $WDGUserIdentityDocsForm->getFields( WDG_Form_User_Identity_Docs::$field_group_files );
	?>
	
	<h2 class="underlined"><?php _e( "Mes justificatifs d'identit&eacute;", 'yproject' ); ?></h2>
	<?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
	<div class="form-error-general align-left">
		<?php _e( "Certaines erreurs ont bloqu&eacute; l'enregistrement de vos donn&eacute;es :", 'yproject' ); ?><br>
		<?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
			- <?php echo $error[ 'text' ]; ?><br>
		<?php endforeach; ?>
		<br><br>
	</div>
	<?php endif; ?>

	<form method="POST" enctype="multipart/form-data" class="db-form v3 full">

		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_files as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<p class="align-left">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>

		<div id="user-identify-docs-form-buttons">
			<button type="submit" class="button save red"><?php _e( "Envoyer les documents", 'yproject' ); ?></button>
		</div>

	</form>
	

<?php else: ?>

	<?php _e( "Votre compte bancaire a &eacute;t&eacute; d&eacute;bit&eacute;.", 'yproject' ); ?><br>
	<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>

		<?php if ( !$page_controler->has_contract_errors() ): ?>
			<?php _e( "Vous allez recevoir deux e-mails cons&eacute;cutifs &agrave; l&apos;adresse", 'yproject' ); ?> <?php echo $page_controler->get_current_user_email(); ?>
			(<?php _e( "pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable", 'yproject' ); ?>) :<br><br>

			<?php if ( ATCF_CrowdFunding::get_platform_context() == "wedogood" ): ?>
			- <?php _e( "un e-mail envoy&eacute; par WEDOGOOD pour la confirmation de votre paiement. Cet e-mail contient votre code pour signer le pouvoir", 'yproject' ); ?><br><br>
			<?php else: ?>
			- <?php _e( "un e-mail envoy&eacute; pour la confirmation de votre paiement. Cet e-mail contient votre code pour signer le pouvoir", 'yproject' ); ?><br><br>
			<?php endif; ?>

			- <?php _e( "un e-mail envoy&eacute; par notre partenaire Signsquid. Cet e-mail contient un lien vous permettant de signer le pouvoir pour le contrat d&apos;investissement", 'yproject' ); ?><br><br>
			<center><img src="<?php echo $stylesheet_directory_uri; ?>/images/signsquid.png" width="168" height="64" /></center><br>

			<?php if ( $page_controler->get_current_user_phone() != FALSE ): ?>
				<?php _e( "Vous allez aussi recevoir un sms contenant le code au num&eacute;ro que vous nous avez indiqu&eacute; :", 'yproject' ); ?> <?php echo $page_controler->get_current_user_phone(); ?><br><br>
			<?php endif; ?>

		<?php else: ?>
			<?php _e( "Vous allez recevoir un e-mail de confirmation de paiement.", 'yproject' ); ?><br>
			<span class="errors"><?php _e( "Cependant, il y a eu un probl&egrave;me lors de la g&eacute;n&eacute;ration du contrat. Nos &eacute;quipes travaillent &agrave; la r&eacute;solution de ce probl&egrave;me.", 'yproject' ); ?></span><br><br>

		<?php endif; ?>

	<?php else: ?>
		<?php _e( "Votre investissement est valid&eacute;.", 'yproject' ); ?><br>
		<?php _e( "Vous allez recevoir un e-mail &agrave; l&apos;adresse", 'yproject' ); ?> <?php echo $page_controler->get_current_user_email(); ?> (<?php _e( "pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable", 'yproject' ); ?>).<br>
		<?php _e( "Votre contrat d&apos;investissement sera joint &agrave; cet e-mail.", 'yproject' ); ?><br><br>

	<?php endif; ?>

	<?php if ( $page_controler->is_preinvestment() ): ?>
		<?php _e( "Nous vous rappelons que les conditions que vous avez accept&eacute;es sont susceptibles d'&ecirc;tre modifi&eacutes;es &agrave; l'issue de la phase de vote.", 'yproject' ); ?><br>
		<?php _e( "Si aucun changement ne survient, votre investissement sera valid&eacute; automatiquement.", 'yproject' ); ?><br>
		<?php _e( "Si un changement devait survenir, vous devrez confirmer ou infirmer votre investissement.", 'yproject' ); ?><br><br>
	<?php endif; ?>

	<div class="db-form full v3">
		<a class="button red" href="<?php echo $page_controler->get_success_next_link(); ?>"><?php _e( "Suivant", 'yproject' ); ?></a>
	</div>
	<br><br>

<?php endif; ?>

</div>