<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<div class="view-share center">
		<br><br>
		
		<?php _e( "Afin de proc&eacute;d&eacute;r &agrave; la signature de votre contrat, veuillez suivre les instructions de le cadre ci-dessous.", 'yproject' ); ?><br><br>
		<?php _e( "Avant de commencer, merci de vous munir du t&eacute;l&eacute;phone mobile que vous avez renseign&eacute; pr&eacute;c&eacute;demment.", 'yproject' ); ?><br><br><br>
		
		<?php if ( $page_controler->get_signature_link() ): ?>
			<iframe id="yousign-container" src="<?php echo $page_controler->get_signature_link(); ?>" style="width: 100%; height: 500px;"></iframe>
			<br><br>
			
			<?php _e( "Une fois le contrat ci-dessus sign&eacute; (lorsqu'il est &eacute;crit F&eacute;licitations), merci de cliquer sur Suivant.", 'yproject' ); ?><br>
			<?php _e( "Ce clic enclenchera la v&eacute;rification que la signature a bien &aecute;t&eacute; effectu&eacute;e chez notre prestataire.", 'yproject' ); ?><br>
			<?php _e( "Si la signature est valid&eacute;e, vous serez automatiquement redirig&eacute; vers la page suivante.", 'yproject' ); ?><br>
			<?php _e( "Il est possible que quelques secondes soient nécessaires pour avoir le bon r&eacute;sultat, n'h&eacute;sitez pas &agrave; cliquer &agrave; nouveau sur le bouton Suivant si vous n'&ecirc;tes pas redirig&eacute;.", 'yproject' ); ?><br>
			<?php _e( "Si le probl&egrave;me persiste, contactez-nous via le livechat ou &agrave; l'adresse investir@wedogood.co.", 'yproject' ); ?><br>
			<br>
		
		<?php else: ?>
		
			<?php _e( "Il y a un probl&egrave;me de connexion avec notre prestataire de signature &eacute;lectronique." ); ?><br>
			<?php _e( "Merci de nous contacter &agrave; l'adresse investir@wedogood.co." ); ?><br><br>
		
		<?php endif; ?>
		
		
		<div class="db-form v3 full">
			<a class="button transparent next" href="<?php echo $page_controler->get_success_next_link(); ?>" data-paymentid="<?php echo $page_controler->get_current_investment()->get_id(); ?>"><?php _e( "Suivant", 'yproject' ); ?></a>
			
			<div class="loading align-center hidden">
				<img src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading" />
			</div>
		</div>
		<br><br>
		
	</div>
	
</div><!-- #content -->