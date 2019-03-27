<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<div id="content">
	
	<?php locate_template( array( 'pages/view/investir/header.php'  ), true ); ?>
	
	<div class="view-share center align-justify">
		<br><br>
		
		<?php if ( $page_controler->has_refused_eversign() ): ?>
			<?php _e( "Nous sommes navr&eacute;s de votre refus de signer ce contrat.", 'yproject' ); ?><br><br>
			<?php _e( "Nous allons proc&eacute;der &agrave; l'annulation de votre investissement.", 'yproject' ); ?><br><br>
		
		<?php else: ?>
			<?php _e( "Votre investissement a bien &eacute;t&eacute; pris en compte, merci !", 'yproject' ); ?><br><br>

			<?php if ( $page_controler->has_accepted_eversign() ): ?>
				<?php _e( "Merci pour votre signature &eacute;lectronique !", 'yproject' ); ?><br><br>
			<?php endif; ?>
		
			<?php if ( $page_controler->can_display_form() ): ?>

				<?php
				$fields_hidden = $page_controler->get_form()->getFields( $page_controler->get_form_fields_hidden_slug() );
				$fields_displayed = $page_controler->get_form()->getFields( $page_controler->get_form_fields_displayed_slug() );
				?>

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

					<?php foreach ( $fields_displayed as $field ): ?>
						<?php global $wdg_current_field; $wdg_current_field = $field; ?>
						<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
					<?php endforeach; ?>

					<div id="fieldgroup-to-display">
						<button type="submit" class="button half right transparent"><?php _e( "Suivant", 'yproject' ); ?></button>
					</div>

					<div class="clear"></div>

				</form>

			<?php else: ?>

				<?php if ( $page_controler->get_current_campaign()->is_positive_savings() ): ?>
					<strong><?php _e( "Pour d&eacute;multiplier mon impact et permettre &agrave; plus de projets positifs de voir le jour, je passe le mot sur :" ); ?></strong><br><br>
				<?php else: ?>
					<strong><?php _e( "Pour augmenter les chances de r&eacute;ussite de ce projet, je passe le mot sur :" ); ?></strong><br><br>
				<?php endif; ?>

				<div class="align-center">
					<?php locate_template( 'projects/common/share-buttons.php', true ); ?>
				</div>
				<br><br>

				<div class="db-form v3 full">
					<?php if ( $page_controler->get_current_campaign()->is_positive_savings() ): ?>
						<a class="button transparent" href="<?php echo $page_controler->get_current_campaign()->get_public_url(); ?>"><?php _e( "Retour &agrave; la page &Eacute;pargne positive", 'yproject' ); ?></a>
					<?php else: ?>
						<a class="button transparent" href="<?php echo $page_controler->get_current_campaign()->get_public_url(); ?>"><?php _e( "Retour au projet", 'yproject' ); ?></a>
					<?php endif; ?>
				</div>

			<?php endif; ?>
				
		<?php endif; ?>
				
		<br><br>
		
	</div>
	
</div><!-- #content -->