<?php
global $campaign;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}

if (isset($campaign)): ?>
    		
	<?php
	global $current_breadcrumb_step; $current_breadcrumb_step = 4;
	locate_template( 'invest/breadcrumb.php', true );
	$campaign_organization = $campaign->get_organization();
	$organization_obj = new WDGOrganization( $campaign_organization->wpref );
	$current_user = wp_get_current_user();
	$amount = $_SESSION['redirect_current_amount_part'];
	$check_return = filter_input( INPUT_GET, 'check-return' );
	$share_page = get_page_by_path('paiement-partager');
	?>

	
	<?php if ( !empty( $check_return ) ): ?>

		<?php _e( "Merci pour votre investissement de", 'yproject' ); ?>
		<?php echo $amount; ?> &euro;
		<?php _e( "par chèque pour", 'yproject' ); ?>
		<?php echo $organization_obj->get_name(); ?>.<br /><br />
		
		<?php if ( $check_return == 'post_invest_check' ): ?>

		<?php endif; ?>

		<?php if ( $check_return == 'post_confirm_check' ): ?>

			<?php _e( "Pour que celui-ci soit comptabilis&eacute; dans la campagne, vous devez nous envoyer une photo par mail &agrave; l'adresse investir@wedogood.co.", 'yproject' ); ?>

		<?php endif; ?>

		<?php _e( "Une fois re&ccedil;u et confirm&eacute;, nous vous enverrons une validation de votre investissement par e-mail &agrave; l'adresse", 'yproject' ); ?>
		<?php echo $current_user->user_email; ?>.<br /><br />

		<?php if ( $amount > 1500 ): ?>
		<?php _e( "Nous vous rappelons que pour que celui-ci soit valid&eacute;, vous devez signer le contrat électronique qui vous a &eacute;t&eacute; envoy&eacute; &agrave; l'adresse", 'yproject' ); ?>
		<?php echo $current_user->user_email; ?>.
		<?php _e( "Pensez &agrave; v&eacute;rifier votre dossier de courrier ind&eacute;sirable (spam).", 'yproject' ); ?><br /><br />
		<?php else: ?>
		<?php _e( "Cet e-mail contiendra le contrat d'investissement correspondant.", 'yproject' ); ?><br /><br />
		<?php endif; ?>

		<?php _e( "Pensez aussi &agrave; nous faire parvenir le ch&egrave;que de", 'yproject' ); ?>
		<?php echo $amount; ?> &euro;
		<?php _e( "&agrave; l'ordre de", 'yproject' ); ?>
		<?php echo $organization_obj->get_name(); ?>
		<?php _e( "&agrave; l'adresse suivante :", 'yproject' ); ?><br />
		WE DO GOOD<br />
		8 rue Kervégan<br />
		44000 Nantes<br /><br />

		<div class="align-center"><a class="button" href="<?php echo get_permalink( $share_page->ID ); ?>?campaign_id=<?php echo $campaign->ID; ?>"><?php _e("Suivant", 'yproject'); ?></a></div><br /><br />

	<?php endif; ?>
	
    
    <?php
    if (isset($_SESSION['redirect_current_campaign_id'])) unset($_SESSION['redirect_current_campaign_id']);
    if (isset($_SESSION['redirect_current_amount_part'])) unset($_SESSION['redirect_current_amount_part']);
    ?>
	
<?php endif;