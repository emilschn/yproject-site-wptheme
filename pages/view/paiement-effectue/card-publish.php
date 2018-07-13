<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
	<br><br>

<?php if ( $page_controler->needs_two_contracts() ): ?>
	<?php _e( "Votre investissement est valid&eacute; pour un montant de ", 'yproject' ); ?> <?php echo $page_controler->get_maximum_investable_amount(); ?> &euro;.<br>
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
	<?php _e( "Votre investissement est valid&eacute; pour un montant de ", 'yproject' ); ?> <?php echo $page_controler->get_maximum_investable_amount(); ?> &euro;.<br>
	<?php _e( "Vous allez recevoir un e-mail &agrave; l&apos;adresse", 'yproject' ); ?> <?php echo $page_controler->get_current_user_email(); ?> (<?php _e( "pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable", 'yproject' ); ?>).<br>
	<?php _e( "Votre contrat d&apos;investissement sera joint &agrave; cet e-mail.", 'yproject' ); ?><br><br>
	
	<?php if ( $page_controler->get_current_investment()->get_session_amount() > WDGInvestmentContract::$signature_minimum_amount ): ?>
		<?php _e( "Sur la page suivante, un cadre sp&eacute;cifique vous invitera &agrave; signer votre contrat.", 'yproject'); ?><br>
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