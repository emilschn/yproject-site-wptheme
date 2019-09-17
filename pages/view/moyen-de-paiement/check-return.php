<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
	<br><br>
	<?php _e( "Merci pour votre investissement de", 'yproject' ); ?>
	<?php echo $page_controler->get_current_investment()->get_session_amount(); ?> &euro;
	<?php _e( "par chèque pour", 'yproject' ); ?>
	<?php echo $page_controler->get_campaign_organization_name(); ?>.<br><br>

	<?php if ( $page_controler->get_check_return() == 'post_confirm_check' ): ?>

		<?php _e( "Pour que celui-ci soit comptabilis&eacute; dans la lev&eacute;e de fonds, vous devez nous envoyer une photo par mail &agrave; l'adresse investir@wedogood.co.", 'yproject' ); ?>

	<?php endif; ?>

	<?php _e( "Une fois re&ccedil;u et confirm&eacute;, nous vous enverrons une validation de votre investissement par e-mail &agrave; l'adresse", 'yproject' ); ?>
	<?php echo $page_controler->get_current_user_email(); ?>.<br><br>

	<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>
	<?php _e( "Nous vous rappelons que pour que celui-ci soit valid&eacute;, vous devez signer le contrat électronique qui vous a &eacute;t&eacute; envoy&eacute; &agrave; l'adresse", 'yproject' ); ?>
	<?php echo $page_controler->get_current_user_email(); ?>.
	<?php _e( "Pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable (spam).", 'yproject' ); ?><br><br>
	<?php else: ?>
	<?php _e( "Cet e-mail contiendra le contrat d'investissement correspondant.", 'yproject' ); ?><br><br>
	<?php endif; ?>

	<?php _e( "Pensez aussi &agrave; nous faire parvenir le ch&egrave;que de", 'yproject' ); ?>
	<?php echo $page_controler->get_current_investment()->get_session_amount(); ?> &euro;
	<?php _e( "&agrave; l'ordre de", 'yproject' ); ?>
	<?php echo $page_controler->get_campaign_organization_name(); ?>
	<?php _e( "&agrave; l'adresse suivante :", 'yproject' ); ?><br>
	WE DO GOOD<br>
	Tour Bretagne<br>
	Place de Bretagne<br>
	44000 Nantes<br><br>

	<div class="db-form v3 full">
		<a class="button transparent" href="<?php echo $page_controler->get_success_next_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>
	</div>
	<br><br>
</div>