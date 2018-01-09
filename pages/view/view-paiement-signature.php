<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<div class="view-share center">
		<br><br>
		
		<?php _e( "Afin de proc&eacute;d&eacute;r &agrave; la signature de votre contrat, veuillez suivre les instructions de la fen&ecirc;tre ci-dessous." ); ?><br><br>
		<?php _e( "Avant de commencer, merci de vous munir du t&eacute;l&eacute;phone mobile que vous avez renseign&eacute; pr&eacute;c&eacute;demment." ); ?><br><br><br>
		
		<?php if ( $page_controler->get_signature_link() ): ?>
			<iframe src="<?php echo $page_controler->get_signature_link(); ?>" style="width: 100%; height: 500px;"></iframe>
			
			<br><br>
			<?php _e( "Une fois le contrat sign&eacute; (lorsqu'il est &eacute;crit F&eacute;licitations), vous pouvez cliquer sur Suivant :" ); ?><br><br>
		
		<?php else: ?>
		
			<?php _e( "Il y a un probl&egrave;me de connexion avec notre prestataire de signature &eacute;lectronique." ); ?><br>
			<?php _e( "Merci de nous contacter &agrave; l'adresse investir@wedogood.co." ); ?><br><br>
		
		<?php endif; ?>
		
		
		<div class="db-form v3 full">
			<a class="button transparent" href="<?php echo $page_controler->get_success_next_link(); ?>"><?php _e( "Suivant", 'yproject' ); ?></a>
		</div>
		<br><br>
		
	</div>
	
</div><!-- #content -->