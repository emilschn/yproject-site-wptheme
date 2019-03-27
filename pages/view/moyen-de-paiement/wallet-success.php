<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="align-center">
	<?php _e( "Votre investissement est valid&eacute;.", 'yproject' ); ?><br>
	
	<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>

		<?php if ( !$page_controler->has_contract_errors() ): ?>

			<?php _e( "Vous allez recevoir deux e-mails cons&eacute;cutifs &agrave; l&apos;adresse", 'yproject' ); ?> <?php echo $page_controler->get_current_user_email(); ?>
			(<?php _e( "pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable) :", 'yproject' ); ?><br><br>

			<?php if ( ATCF_CrowdFunding::get_platform_context() == "wedogood" ): ?>
			- <?php _e( "un e-mail envoy&eacute; par WEDOGOOD pour la confirmation de votre paiement. Cet e-mail contient votre code pour signer le pouvoir", 'yproject' ); ?><br><br>
			<?php else: ?>
			- <?php _e( "un e-mail envoy&eacute; pour la confirmation de votre paiement. Cet e-mail contient votre code pour signer le pouvoir", 'yproject' ); ?><br><br>
			<?php endif; ?>

			- <?php _e( "un e-mail envoy&eacute; par notre partenaire Eversign. Cet e-mail contient un lien vous permettant de signer le pouvoir pour le contrat d&apos;investissement", 'yproject' ); ?><br><br>
			<center><img src="<?php echo $stylesheet_directory_uri; ?>/images/eversign.png" width="150" height="40" /></center><br>

		<?php else: ?>
			<?php _e( "Vous allez recevoir un e-mail de confirmation de paiement.", 'yproject' ); ?><br>
			<span class="errors"><?php _e( "Cependant, il y a eu un probl&egrave;me lors de la g&eacute;n&eacute;ration du contrat. Nos &eacute;quipes travaillent &agrave; la r&eacute;solution de ce probl&egrave;me.", 'yproject' ); ?></span><br><br>

		<?php endif; ?>
			
	<?php endif; ?>
</div>
		
<?php if ( $page_controler->is_preinvestment() ): ?>
	<?php _e( "Nous vous rappelons que les conditions que vous avez accept&eacute;es sont susceptibles d'&ecirc;tre modifi&eacute;es &agrave; l'issue de la phase de vote.", 'yproject' ); ?><br>
	<?php _e( "Si aucun changement ne survient, votre investissement sera valid&eacute; automatiquement.", 'yproject' ); ?><br>
	<?php _e( "Si un changement devait survenir, vous devrez confirmer ou infirmer votre investissement.", 'yproject' ); ?><br><br>
<?php endif; ?>

<div class="align-center">
	<a class="button" href="<?php echo $page_controler->get_success_next_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>
</div>
<br><br>
