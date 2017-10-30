<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<br><br>

<?php _e( "Dans l'attente de votre virement, vous recevrez un e-mail rappelant les informations &agrave; nous fournir.", 'yproject' ); ?><br><br>

<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>
	<?php _e( "Une fois valid&eacute;, vous recevrez deux e-mails :", 'yproject' ); ?><br><br>
	
	<?php if ( ATCF_CrowdFunding::get_platform_context() == "wedogood" ): ?>
	- <?php _e( "un e-mail envoy&eacute; par WEDOGOOD pour la confirmation de votre paiement. Cet e-mail contient votre code pour signer le pouvoir", 'yproject' ); ?><br><br>
	<?php else: ?>
	- <?php _e( "un e-mail envoy&eacute; pour la confirmation de votre paiement. Cet e-mail contient votre code pour signer le pouvoir", 'yproject' ); ?><br><br>
	<?php endif; ?>
	
	- <?php _e( "un e-mail envoy&eacute; par notre partenaire Signsquid. Cet e-mail contient un lien vous permettant de signer le pouvoir pour le contrat d'investissement", 'yproject' ); ?><br><br>

<?php else: ?>
	<?php _e( "Une fois valid&eacute;, vous recevrez un e-mail confirmant votre paiement. Votre contrat d'investissement sera joint &agrave; cet e-mail.", 'yproject' ); ?><br><br>

<?php endif; ?>

<?php if ( !$page_controler->get_current_investment()->has_token() ): ?>
	<?php _e( "Merci de vous rendre sur la page", 'yproject' ); ?> <a href="<?php echo home_url( '/mon-compte' ); ?>"><?php _e( "Mon compte", 'yproject' ); ?></a> <?php _e( "pour suivre l&apos;&eacute;volution de votre paiement.", 'yproject' ); ?><br><br>
<?php endif; ?>

<div class="db-form full v3">
	<a class="button red" href="<?php echo $page_controler->get_pending_next_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>
</div>
<br><br>
</div>