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
		<h3><?php _e( 'account.bank.BANK_CARD', 'yproject' ); ?></h3>

		<p class="align-justify">
			<?php _e( 'account.bank.YOUR_BANK_CARD_INFO_ON_LEMON_WAY', 'yproject' ); ?>
			<?php _e( 'account.bank.WEDOGOOD_KEEPS_EXPIRATION_DATE', 'yproject' ); ?><br><br>
		</p>

		<?php $lemonway_registered_cards = $WDGOrganization->get_lemonway_registered_cards(); ?>
		<?php if ( !empty( $lemonway_registered_cards ) ): ?>

			<div class="align-justify">
				<strong><?php _e( 'account.bank.MY_REGISTERED_BANK_CARDS', 'yproject' ); ?></strong><br>

				<?php foreach ( $lemonway_registered_cards as $registered_card ): ?>
					<div class="user-registered-card">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( 'account.bank.BANK_CARD', 'yproject' ); ?>" width="120">
						<span>
							<?php echo $registered_card[ 'number' ]; ?><br>
							Exp <?php echo $registered_card[ 'expiration' ]; ?>
						</span>
						<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=remove_user_registered_card' ); ?>" class="db-form v3">
							<input type="hidden" name="orga_id" value="<?php echo $WDGOrganization->get_wpref(); ?>">
							<input type="hidden" name="card_id" value="<?php echo $registered_card[ 'id' ]; ?>">
							<button type="submit" class="button blue"><?php _e( 'common.REMOVE', 'yproject' ); ?></button>
						</form>
					</div>
				<?php endforeach; ?>
			</div>

		<?php endif; ?>

		<br><br>
	<?php endif; ?>


	<div class="align-justify">
		<h3><?php _e( 'account.bank.orga.BANK_DETAILS', 'yproject' ); ?></h3>
	</div>
	<p class="align-justify">
		<?php if ( !$WDGOrganization->can_register_lemonway() ): ?>
			<?php _e( 'account.bank.orga.NEED_ORGANIZATION_INFORMATION', 'yproject' ); ?><br><br>
			<a href="#orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>" class="button blue go-to-tab" data-tab="orga-parameters-<?php echo $WDGOrganization->get_wpref(); ?>"><?php _e( 'account.bank.orga.ORGANIZATION_INFORMATION', 'yproject' ); ?></a><br>
			<br>
		<?php endif; ?>

		<?php _e( 'account.bank.BANK_DETAILS_TO_BE_CHECKED', 'yproject' ); ?><br>
		<?php _e( 'account.bank.orga.BANK_DETAILS_WITH_ORGA_NAME', 'yproject' ); ?><br>
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
			* <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
		</p>

		<div id="user-bank-form-buttons">
			<button type="submit" class="button save red"><?php _e( 'common.SAVE', 'yproject' ); ?></button>
		</div>
		
	</form>
</div>