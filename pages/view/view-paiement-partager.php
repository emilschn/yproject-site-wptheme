<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<div class="view-share center align-justify">
		<br><br>
		
		<?php _e( "Votre investissement a bien &eacute;t&eacute; pris en compte, merci !" ); ?><br><br>
		
		<?php if ( $page_controler->can_display_form() ): ?>
		
			<?php
			$fields_hidden = $page_controler->get_form()->getFields( WDG_Form_Invest_Poll::$field_group_hidden );
			$fields_poll_source = $page_controler->get_form()->getFields( WDG_Form_Invest_Poll::$field_group_poll_source );
			?>
		
			<div><?php _e( "WE DO GOOD envisage avec son partenaire Le Fonds Compagnon de proposer une protection des investissements en cas de cession d'activit&eacute; de l'entreprise.", 'yproject' ); ?></div>
			
			<form action="<?php echo $page_controler->get_form_action(); ?>" method="post" class="db-form v3 full bg-white">

				<div class="align-left">
					<?php $form_errors = $page_controler->get_form_errors(); ?>
					<?php if ( $form_errors ): ?>
						<?php foreach ( $form_errors as $form_error ): ?>
							<span class="invest_error"><?php echo $form_error[ 'text' ]; ?></span>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<?php foreach ( $fields_hidden as $field ): ?>
					<?php global $wdg_current_field; $wdg_current_field = $field; ?>
					<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
				<?php endforeach; ?>

				<?php foreach ( $fields_poll_source as $field ): ?>
					<?php global $wdg_current_field; $wdg_current_field = $field; ?>
					<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
				<?php endforeach; ?>

				<div id="fieldgroup-to-display">
					<button type="submit" class="button half right transparent"><?php _e( "Suivant", 'yproject' ); ?></button>
				</div>

				<div class="clear"></div>

			</form>
		
		<?php else: ?>
		
			<strong><?php _e( "Pour augmenter les chances de r&eacute;ussite de ce projet, je passe le mot sur :" ); ?></strong><br><br>

			<div class="align-center">
				<?php locate_template( 'projects/common/share-buttons.php', true ); ?>
			</div>
			<br><br>
		
			<div class="db-form v3 full">
				<a class="button transparent" href="<?php echo $page_controler->get_campaign_link(); ?>"><?php _e( "Retour au projet", 'yproject' ); ?></a>
			</div>
		
		<?php endif; ?>
		<br><br>
		
	</div>
	
</div><!-- #content -->