<?php
	global $WDGOrganization;
	$page_controler = WDG_Templates_Engine::instance()->get_controler();
	$WDGUserBankForm = new WDG_Form_User_Bank( $WDGOrganization->get_wpref(), TRUE );
	$fields_hidden = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_hidden );
	$fields_iban = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_iban );
	$fields_file = $WDGUserBankForm->getFields( WDG_Form_User_Bank::$field_group_file );
?>

<div class="<?php echo $page_controler->get_form_css_classes();?>">
	<?php if ( $WDGOrganization->has_saved_card_expiration_date() ): ?>
		<h3><?php _e( "Carte bancaire", 'yproject' ); ?></h3>

		<p class="align-justify">
			<?php _e( "Les informations de vos cartes bancaires sont stock&eacute;es par Lemon Way, prestataire de service de paiement agr&eacute;&eacute;.", 'yproject' ); ?>
			<?php _e( "WE DO GOOD ne stocke que la date d'expiration afin de vous pr&eacute;venir quand la date approche.", 'yproject' ); ?><br><br>
		</p>

		<?php $lemonway_registered_cards = $WDGOrganization->get_lemonway_registered_cards(); ?>
		<?php if ( !empty( $lemonway_registered_cards ) ): ?>

			<div class="align-justify">
				<strong><?php _e( "Cartes bancaires enregistr&eacute;es", 'yproject' ); ?></strong><br>

				<?php foreach ( $lemonway_registered_cards as $registered_card ): ?>
					<div class="user-registered-card">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( "Carte bancaire", 'yproject' ); ?>" width="120">
						<span>
							<?php echo $registered_card[ 'number' ]; ?><br>
							Exp <?php echo $registered_card[ 'expiration' ]; ?>
						</span>
						<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=remove_user_registered_card' ); ?>" class="db-form v3">
							<input type="hidden" name="orga_id" value="<?php echo $WDGOrganization->get_wpref(); ?>">
							<input type="hidden" name="card_id" value="<?php echo $registered_card[ 'id' ]; ?>">
							<button type="submit" class="button blue"><?php _e( "Supprimer", 'yproject' ); ?></button>
						</form>
					</div>
				<?php endforeach; ?>
			</div>

		<?php endif; ?>

		<br><br>
	<?php endif; ?>


	<div class="align-justify">
		<h3><?php _e( "Relev&eacute; d'identit&eacute; bancaire", 'yproject' ); ?></h3>
	</div>
	<p class="align-justify">
		<?php if ( !$WDGOrganization->can_register_lemonway() ): ?>
			<?php _e( "Pensez &agrave; renseigner les informations de l'organisation pour que notre prestataire puisse valider votre RIB.", 'yproject' ); ?><br><br>
			<a href="#orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" class="button blue go-to-tab" data-tab="orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( "Informations de l'organisation" ); ?></a><br>
			<br>

		<?php endif; ?>

		<?php _e( "Afin de lutter contre la fraude et le blanchiment d'argent, il est n&eacute;cessaire que le RIB soit contr&ocirc;l&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
		<?php _e( "Le compte bancaire qui vous permettra de r&eacute;cup&eacute;rer l'argent doit &ecirc;tre &agrave; votre nom.", 'yproject' ); ?><br>
		<br>
	</p>

	<?php if ( $WDGOrganization->has_document_lemonway_error( LemonwayDocument::$document_type_bank ) ): ?>
		<?php echo $WDGOrganization->get_document_lemonway_error( LemonwayDocument::$document_type_bank ); ?><br>
	<?php endif; ?>

	<form method="POST" enctype="multipart/form-data" class="<?php echo $page_controler->get_form_css_classes();?>" action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_bank' ); ?>">
			
		<?php foreach ( $fields_hidden as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_iban as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>

		<?php foreach ( $fields_file as $field ): ?>
			<?php global $wdg_current_field; $wdg_current_field = $field; ?>
			<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
		<?php endforeach; ?>
		
		<p class="align-left">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>

		<div id="user-bank-form-buttons">
			<button type="submit" class="button save red"><?php _e( "Enregistrer", 'yproject' ); ?></button>
		</div>
		
	</form>
</div>