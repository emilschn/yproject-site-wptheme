<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<div class="view-share center">
		<br><br>
		
		<?php _e( "Votre investissement a bien &eacute;t&eacute; pris en compte, merci !" ); ?><br><br>
		
		<strong><?php _e( "Pour augmenter les chances de r&eacute;ussite de ce projet, je passe le mot sur :" ); ?></strong><br><br>
		
		<div class="align-center">
			<?php locate_template( 'projects/common/share-buttons.php', true ); ?>
		</div>
		<br><br>
		
		<div class="db-form v3 full">
			<a class="button transparent" href="<?php echo $page_controler->get_campaign_link(); ?>"><?php _e( "Retour au projet", 'yproject' ); ?></a>
		</div>
		<br><br>
		
	</div>
	
</div><!-- #content -->