<?php
/* 
 * Lightbox de création de l'organisation
 * 
 */
global $campaign_id
?>
	    
<div class="center margin-height">

	<h3><?php _e('Cr&eacute;er une organisation portant le projet','yproject')?></h3>

	<form id="orgacreate_form" action="" method="POST" enctype="multipart/form-data" class="wdg-forms db-form v3 full center" data-action="save_new_organization">

		<div class="field">
			<label for="org_name"><?php _e( "D&eacute;nomination sociale", 'yproject' ); ?>*</label>
			<div class="field-container align-left">
				<span class="field-value">
					<input type="text" name="org_name" value="<?php echo filter_input( INPUT_POST, 'org_name' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_email"><?php _e( "E-mail de contact", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_email" value="<?php echo filter_input( INPUT_POST, 'org_email' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_representative_function"><?php _e( "Fonction du repr&eacute;sentant", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_representative_function" value="<?php echo filter_input( INPUT_POST, 'org_representative_function' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_description"><?php _e( "Descriptif de l'activit&eacute;", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_description" value="<?php echo filter_input( INPUT_POST, 'org_description' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_legalform"><?php _e( "Forme juridique", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_legalform" value="<?php echo filter_input( INPUT_POST, 'org_legalform' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_idnumber"><?php _e( "Num&eacute;ro SIREN", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_idnumber" value="<?php echo filter_input( INPUT_POST, 'org_idnumber' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_rcs"><?php _e( "RCS", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_rcs" value="<?php echo filter_input( INPUT_POST, 'org_rcs' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_capital"><?php _e( "Capital social (en euros)", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_capital" value="<?php echo filter_input(INPUT_POST, 'org_capital'); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_ape"><?php _e( "Code APE", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_ape" value="<?php echo filter_input( INPUT_POST, 'org_ape' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_vat"><?php _e( "Num&eacute;ro de TVA", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_vat" value="<?php echo filter_input( INPUT_POST, 'org_vat' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_fiscal_year_end_month"><?php _e( "L'exerice comptable se termine &agrave; la fin du mois", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<?php
					$months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
					$count_months = count( $months );
					?>
					<select name="org_fiscal_year_end_month">
						<option value=""></option>
						<?php for ( $i = 0; $i < $count_months; $i++ ): ?>
						<option value="<?php echo ( $i + 1 ); ?>" <?php selected( filter_input( INPUT_POST, 'org_fiscal_year_end_month' ), $i + 1 ); ?>><?php _e( $months[ $i ] ); ?></option>
						<?php endfor; ?>
					</select>
				</span>
			</div>
		</div>

		
		
		<h3><?php _e( "Si&egrave;ge social", 'yproject' ); ?></h3>
		<div class="field">
			<label for="org_address"><?php _e( "Adresse", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_address" value="<?php echo filter_input( INPUT_POST, 'org_address' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_postal_code"><?php _e( "Code postal", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_postal_code" value="<?php echo filter_input( INPUT_POST, 'org_postal_code' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_city"><?php _e( "Ville", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_city" value="<?php echo filter_input(INPUT_POST, 'org_city'); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_nationality"><?php _e( "Pays", 'yproject' ); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<select name="org_nationality" id="org_nationality">
						<?php 
						global $country_list;
						$selected_country = filter_input( INPUT_POST, 'org_nationality' );
						foreach ($country_list as $country_code => $country_name): ?>
							<option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
						<?php endforeach; ?>
					</select>
				</span>
			</div>
		</div>
		


		<h3><?php _e( "Informations bancaires", 'yproject' ); ?></h3>
		<div class="field">
			<label for="org_bankownername"><?php _e( "Nom du propri&eacute;taire du compte", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankownername" value="<?php echo filter_input( INPUT_POST, 'org_bankownername' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankowneraddress"><?php _e( "Adresse du compte", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankowneraddress" value="<?php echo filter_input( INPUT_POST, 'org_bankowneraddress' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankowneriban"><?php _e( "IBAN", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankowneriban" value="<?php echo filter_input( INPUT_POST, 'org_bankowneriban' ); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankownerbic"><?php _e( "BIC", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankownerbic" value="<?php echo filter_input( INPUT_POST, 'org_bankowneriban' ); ?>">
				</span>
			</div>
		</div>
		
		<div class="field">
			<div class="field-container">
				<span class="field-value">
					<label for="new_org_capable-new_org_capable" class="radio-label">
						<input id="new_org_capable-new_org_capable" name="org_capable" type="checkbox"><span></span>
						<?php _e( "Je d&eacute;clare &ecirc;tre en capacit&eacute; de repr&eacute;senter cette organisation", 'yproject' ); ?>*
					</label>
				</span>
			</div>
		</div>

		<p class="required-mention align-left">* <?php _e( "Obligatoire", 'yproject' ); ?></p>

		<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />

		<?php DashboardUtility::create_save_button( 'orgacreate_form' ); ?>
	</form>


</div>