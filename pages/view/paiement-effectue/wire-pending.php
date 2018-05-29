<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<br><br>

<?php _e( "Dans l'attente de votre virement, vous recevrez un e-mail rappelant les informations &agrave; nous fournir.", 'yproject' ); ?><br><br>

<?php _e( "Une fois valid&eacute;, vous recevrez un e-mail confirmant votre paiement. Votre contrat d'investissement sera joint &agrave; cet e-mail.", 'yproject' ); ?><br><br>
<?php if ( $page_controler->get_current_investment()->get_session_amount() > WDGInvestmentContract::$signature_minimum_amount ): ?>
	<?php _e( "Sur la page suivante, un cadre sp&eacute;cifique vous invitera &agrave; signer votre contrat.", 'yproject'); ?><br>
<?php endif; ?>
	
<?php if ( $page_controler->is_preinvestment() ): ?>
	<?php _e( "Nous vous rappelons que les conditions que vous avez accept&eacute;es sont susceptibles d'&ecirc;tre modifi&eacutes;es &agrave; l'issue de la phase de vote.", 'yproject' ); ?><br>
	<?php _e( "Si aucun changement ne survient, votre investissement sera valid&eacute; automatiquement.", 'yproject' ); ?><br>
	<?php _e( "Si un changement devait survenir, vous devrez confirmer ou infirmer votre investissement.", 'yproject' ); ?><br><br>
<?php endif; ?>

<?php if ( !$page_controler->get_current_investment()->has_token() ): ?>
	<?php _e( "Merci de vous rendre sur la page", 'yproject' ); ?> <a href="<?php echo home_url( '/mon-compte/' ); ?>"><?php _e( "Mon compte", 'yproject' ); ?></a> <?php _e( "pour suivre l&apos;&eacute;volution de votre paiement.", 'yproject' ); ?><br><br>
<?php endif; ?>

<div class="db-form full v3">
	<a class="button red" href="<?php echo $page_controler->get_pending_next_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>
</div>
<br><br>
</div>