<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
	<br><br>

	<?php _e( "Votre investissement est valid&eacute;.", 'yproject' ); ?><br>
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

</div>