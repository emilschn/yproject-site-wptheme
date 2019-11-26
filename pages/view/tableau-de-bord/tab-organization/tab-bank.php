<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();

global $campaign_id, $organization_obj;
if ( isset( $organization_obj ) ) {
    $WDGOrganization = $organization_obj;
    $WDGUser_current = WDGUser::current();
    $WDGOrganizationDetailsForm = new WDG_Form_Organization_Details( $WDGOrganization->get_wpref(), TRUE );
    $fields_hidden = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_hidden );
    $fields_complete = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_complete );
    $fields_dashboard = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_dashboard );
    $fields_address = $WDGOrganizationDetailsForm->getFields( WDG_Form_Organization_Details::$field_group_address );

?>

<div id="stat-subtab-bank" class="stat-subtab">

<?php
		/**
		 * Informations bancaires
		 */
        ?>
        
        
	<?php if ( $WDGUser_current->is_admin() ): ?>
		<br><br>
		<h3><?php _e( "Lemonway", 'yproject' ); ?></h3>

		<?php $organization_lemonway_authentication_status = $organization_obj->get_lemonway_status(); ?>
		<?php if ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_blocked): ?>
			<?php _e( "Afin de s'authentifier chez notre partenaire Lemonway, les informations suivantes sont n&eacute;cessaires : e-mail, description, num&eacute;ro SIRET. Ainsi que les 5 documents suivis d'une &eacute;toile ci-dessus.", 'yproject' ); ?><br />
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_ready): ?>
			<form action="" method="POST">
				<input type="hidden" name="authentify_lw" value="1" />
				<input type="submit" class="button" value="<?php _e( "Authentifier chez Lemonway", 'yproject' ); ?>" />
			</form>
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_waiting): ?>
			<?php _e( "L'organisation est en cours d'authentification aupr&egrave;s de notre partenaire.", 'yproject' ); ?>
			<form action="" method="POST">
				<input type="hidden" name="authentify_lw" value="1" />
				<input type="submit" class="button" value="<?php _e( "Authentifier chez Lemonway", 'yproject' ); ?>" />
			</form>
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_incomplete): ?>
			<?php _e( "L'organisation n'est que partiellement authentifi&eacute;e.", 'yproject' ); ?>
			<form action="" method="POST">
				<input type="hidden" name="authentify_lw" value="1" />
				<input type="submit" class="button" value="<?php _e( "Authentifier chez Lemonway", 'yproject' ); ?>" />
			</form>
		<?php elseif ($organization_obj->is_registered_lemonway_wallet()): ?>
			<?php _e( "L'organisation est bien authentifi&eacute;e aupr&egrave;s de notre partenaire.", 'yproject' ); ?>
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_rejected): ?>
			<?php _e( "L'organisation a &eacute;t&eacute; refus&eacute;e par notre partenaire.", 'yproject' ); ?>
		<?php endif; ?>

    <?php endif; ?>
    

		<br><br>
        <h3><?php _e( "Informations bancaires", 'yproject' ); ?></h3>
        
        <form id="orgaedit_form" action="" method="POST" enctype="multipart/form-data" class="db-form v3 full center bg-white" data-action="save_edit_organization" novalidate>

		<div class="field">
			<label for="org_bankownername"><?php _e( "Nom du propri&eacute;taire du compte", 'yproject' ); ?></label>
			<div class="field-description"><?php _e( "Le nom de votre organisation", 'yproject' ); ?></div>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankownername" value="<?php echo $organization_obj->get_bank_owner(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankowneraddress"><?php _e( "Adresse du compte", 'yproject' ); ?></label>
			<div class="field-description"><?php _e( "En g&eacute;n&eacute;ral, le nom de l'agence", 'yproject' ); ?></div>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankowneraddress" value="<?php echo $organization_obj->get_bank_address(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankowneriban"><?php _e( "IBAN", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankowneriban" value="<?php echo $organization_obj->get_bank_iban(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankownerbic"><?php _e( "BIC", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankownerbic" value="<?php echo $organization_obj->get_bank_bic(); ?>">
				</span>
			</div>
        </div>
        <p class="align-left">
			<?php _e( "* Champs obligatoires", 'yproject' ); ?><br>
		</p>

		<div id="organization-details-form-buttons">
			<button type="submit" class="button save red"><?php _e( "Enregistrer les modifications", 'yproject' ); ?></button>
		</div>
    </form>
</div>
<?php
}