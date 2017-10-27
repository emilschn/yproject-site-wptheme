<?php global $page_controler, $stylesheet_directory_uri; ?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<div class="padder">
		
		<?php _e( "Votre investissement a bien &eacute;t&eacute; pris en compte, merci !" ); ?><br><br>
		
		<strong><?php _e( "Pour augmenter les chances de r&eacute;ussite de ce projet, je passe le mot sur :" ); ?></strong><br><br>
		
		<?php locate_template( 'projects/common/share-buttons.php', true ); ?>
		
		<div class="align-center">
			<a class="button transparent" href="<?php echo $page_controler->get_campaign_link(); ?>"><?php _e( "Retour au projet", 'yproject' ); ?></a>
		</div>
		
	</div>
	
</div><!-- #content -->