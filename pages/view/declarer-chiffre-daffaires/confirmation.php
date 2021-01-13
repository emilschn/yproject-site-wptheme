<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
	
	<br>
	
	<?php _e( "Merci pour votre d&eacute;claration de chiffre d'affaires.", 'yproject' ); ?><br>
	<?php _e( "Le versement des royalties sera fait dans les plus brefs d&eacute;lais.", 'yproject' ); ?><br><br>
	
	<?php _e( "D&egrave;s que le versement des royalties sera effectu&eacute;, vous pourrez retrouver un justificatif de d&eacute;claration au sein de votre tableau de bord.", 'yproject' ); ?><br><br>
	
	<?php if ( $page_controler->has_commission() ): ?>
		<?php _e( "Nous vous enverrons la facture des frais de gestion au cours de ce mois, lorsque nous l'aurons &eacute;dit&eacute;e.", 'yproject' ); ?><br><br>
	<?php endif; ?>
		
	<?php if ( $page_controler->can_display_has_added_declaration() ): ?>
		<?php _e( "Le montant minimum de versement n'ayant pas encore &eacute;t&eacute; atteint, nous avons automatiquement ajout&eacute; une d&eacute;claration de chiffre d'affaires pour le prochain trimestre.", 'yproject' ); ?><br>
		<?php _e( "Vous pouvez cependant d&egrave;s &agrave; pr&eacute;sent verser le reste du montant d&ucirc; &agrave; vos investisseurs.", 'yproject' ); ?><br><br>
		
		<form action="<?php echo $page_controler->get_form_action_to_added_declaration(); ?>" method="post" class="db-form v3 full bg-white">
			<button class="button red"><?php _e( "Clore les versements", 'yproject' ); ?></button>
		</form>
	
		
	<?php elseif ( $page_controler->get_current_declaration_royalties_amount() > 0 ): ?>
		<?php
		$campaign_url = $page_controler->get_current_campaign()->get_public_url();
		$message = __( "Nous sommes fiers d'avoir vers&eacute; des royalties &agrave; nos investisseurs ce trimestre !", 'yproject' );
		$twitter_hashtags = 'royalty, crowdfunding';
		?>
		<div class="align-center">
			<button class="sharer button" data-sharer="twitter" data-title="<?php echo $message; ?>" data-hashtags="<?php echo $twitter_hashtags; ?>" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/twitter.png" /></button>
			<button class="sharer button" data-sharer="facebook" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/facebook.png" /></button>
			<button class="sharer button" data-sharer="linkedin" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/linkedin.png" /></button>
			<button class="sharer button" data-sharer="email" data-title="<?php echo $message; ?>" data-url="<?php echo $campaign_url; ?>" data-subject="<?php _e( "Versement de royalties trimestrielles !", 'yproject' ); ?>" data-to=""><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/mail.jpg" /></button>
			<button class="sharer button only_on_mobile inline" data-sharer="whatsapp" data-title="<?php echo $message; ?>" data-url="<?php echo $campaign_url; ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/partage/whatsapp.jpg" /></button>
		</div>
	<?php endif; ?>
	
	<br><br><br>
	
	<div class="db-form v3 full bg-white">
		<a href="<?php echo $page_controler->get_dashboard_url(); ?>" class="button red"><?php _e( "Retour au tableau de bord du projet", 'yproject' ); ?></a>
	</div>
	<br><br>

</div>