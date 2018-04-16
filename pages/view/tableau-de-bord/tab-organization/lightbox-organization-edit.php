<?php

/* 
 * Lightbox d'édition d'une organisation
 * 
 */
global $campaign_id, $current_organization, $organization_obj;

if ( !isset( $organization_obj ) ) {
	if ( !isset( $current_organization ) ) {
		$organization_obj = WDGOrganization::current();
	} else {
		$organization_obj = new WDGOrganization( $current_organization->wpref );
	}
}

$WDGUser_current = WDGUser::current();

?>
	    
<div class="center margin-height">

	<h3><?php _e('&Eacute;diter l\'organisation portant le projet','yproject'); echo "&nbsp;"; ?></h3>

	<form id="orgaedit_form" action="" method="POST" enctype="multipart/form-data" class="wdg-forms db-form v3 full center" data-action="save_edit_organization">

		<?php
		/**
		 * Données générales
		 */
		?>
		<div class="field">
			<label for="org_name"><?php _e( "D&eacute;nomination sociale", 'yproject' ); ?></label>
			<div class="field-container align-left">
				<em id="org_name"><?php echo $organization_obj->get_name(); ?></em>
			</div>
		</div>

		<div class="field">
			<label for="org_email"><?php _e('E-mail de contact', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_email" value="<?php echo $organization_obj->get_email(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_representative_function"><?php _e("Fonction du repr&eacute;sentant", 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_representative_function" value="<?php echo $organization_obj->get_representative_function(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_description"><?php _e("Descriptif de l'activit&eacute;", 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_description" value="<?php echo $organization_obj->get_description(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_website"><?php _e("Site Web", 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_website" value="<?php echo $organization_obj->get_website(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_legalform"><?php _e('Forme juridique', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_legalform" value="<?php echo $organization_obj->get_legalform(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_idnumber"><?php _e('Num&eacute;ro SIREN', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_idnumber" value="<?php echo $organization_obj->get_idnumber(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_rcs"><?php _e('RCS', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_rcs" value="<?php echo $organization_obj->get_rcs(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_capital"><?php _e('Capital social (en euros)', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_capital" value="<?php echo $organization_obj->get_capital(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_ape"><?php _e('Code APE', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_ape" value="<?php echo $organization_obj->get_ape(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_vat"><?php _e('Num&eacute;ro de TVA', 'yproject'); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_vat" value="<?php echo $organization_obj->get_vat(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_fiscal_year_end_month"><?php _e("L'exerice comptable se termine &agrave; la fin du mois", 'yproject'); ?></label>
			<div class="field-container">
				<span class="field-value">
					<?php
					$months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
					$count_months = count( $months );
					?>
					<select name="org_fiscal_year_end_month">
						<option value=""></option>
						<?php for ( $i = 0; $i < $count_months; $i++ ): ?>
						<option value="<?php echo ( $i + 1 ); ?>" <?php selected( $organization_obj->get_fiscal_year_end_month(), $i + 1 ); ?>><?php _e( $months[ $i ] ); ?></option>
						<?php endfor; ?>
					</select>
				</span>
			</div>
		</div>

		<?php if ( $WDGUser_current->is_admin() ): ?>
		<div class="field admin-theme">
			<label for="org_id_quickbooks"><?php _e('ID Quickbooks', 'yproject'); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_id_quickbooks" value="<?php echo $organization_obj->get_id_quickbooks(); ?>">
				</span>
			</div>
		</div>
		<?php endif; ?>

		
		<?php
		/**
		 * Siège social
		 */
		?>
		<br><br>
		<h3><?php _e('Si&egrave;ge social', 'yproject'); ?></h3>
		<div class="field">
			<label for="org_address"><?php _e('Adresse', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_address" value="<?php echo $organization_obj->get_address(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_postal_code"><?php _e('Code postal', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_postal_code" value="<?php echo $organization_obj->get_postal_code(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_city"><?php _e('Ville', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_city" value="<?php echo $organization_obj->get_city(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_nationality"><?php _e('Pays', 'yproject'); ?>*</label>
			<div class="field-container">
				<span class="field-value">
					<select name="org_nationality" id="org_nationality">
						<?php 
						global $country_list;
						$selected_country = $organization_obj->get_nationality();
						foreach ($country_list as $country_code => $country_name): ?>
							<option value="<?php echo $country_code; ?>" <?php if ($country_code == $selected_country) { echo 'selected="selected"'; } ?>><?php echo $country_name; ?></option>
						<?php endforeach; ?>
					</select>
				</span>
			</div>
		</div>


		<?php
		/**
		 * Informations bancaires
		 */
		?>
		<br><br>
		<h3><?php _e('Informations bancaires', 'yproject'); ?></h3>
		<div class="field">
			<label for="org_bankownername"><?php _e('Nom du propri&eacute;taire du compte', 'yproject'); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankownername" value="<?php echo $organization_obj->get_bank_owner(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankowneraddress"><?php _e('Adresse du compte', 'yproject'); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankowneraddress" value="<?php echo $organization_obj->get_bank_address(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankowneriban"><?php _e('IBAN', 'yproject'); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankowneriban" value="<?php echo $organization_obj->get_bank_iban(); ?>">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_bankownerbic"><?php _e('BIC', 'yproject'); ?></label>
			<div class="field-container">
				<span class="field-value">
					<input type="text" name="org_bankownerbic" value="<?php echo $organization_obj->get_bank_bic(); ?>">
				</span>
			</div>
		</div>


		<?php
		/**
		 * Documents
		 */
		$current_filelist_bank = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_bank);
		$current_file_bank = $current_filelist_bank[0];
		$current_filelist_kbis = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_kbis);
		$current_file_kbis = $current_filelist_kbis[0];
		$current_filelist_status = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_status);
		$current_file_status = $current_filelist_status[0];
		$current_filelist_id = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id);
		$current_file_id = $current_filelist_id[0];
		$current_filelist_home = WDGKYCFile::get_list_by_owner_id($organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home);
		$current_file_home = $current_filelist_home[0];
		$current_filelist_capital_allocation = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_capital_allocation );
		$current_file_capital_allocation = $current_filelist_capital_allocation[0];
		$current_filelist_id_2 = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id_2 );
		$current_file_id_2 = $current_filelist_id_2[0];
		$current_filelist_home_2 = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home_2 );
		$current_file_home_2 = $current_filelist_home_2[0];
		$current_filelist_id_3 = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_id_3 );
		$current_file_id_3 = $current_filelist_id_3[0];
		$current_filelist_home_3 = WDGKYCFile::get_list_by_owner_id( $organization_obj->get_wpref(), WDGKYCFile::$owner_organization, WDGKYCFile::$type_home_3 );
		$current_file_home_3 = $current_filelist_home_3[0];
		?>
		<br><br>
		<h3><?php _e('Documents', 'yproject'); ?></h3>
		<p class="align-left">
			<?php _e("Afin de lutter contre le blanchiment d'argent, les organisations doivent transmettre des documents d'identification.", 'yproject'); ?><br />
			<?php _e("Ces fichiers doivent avoir un poids inf&eacute;rieur &agrave; 4Mo et doivent &ecirc;tre dans l'un des formats suivants :", 'yproject'); ?> 
			<?php echo implode( ', ', WDGKYCFile::$authorized_format_list ); ?>
		</p>

		
		<div class="field">
			<label for="org_doc_bank"><?php _e("Scan ou copie d'un RIB*", 'yproject'); ?></label>
			<div class="field-container align-left">
				<span class="field-value">
					<?php if ( isset($current_file_bank) ): ?>
						<a id="org_doc_bank" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_bank->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_bank->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
					<input type="file" name="org_doc_bank">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_doc_kbis"><?php _e("K-BIS ou &eacute;quivalent &agrave; un registre du commerce*", 'yproject'); ?></label>
			<div class="field-container align-left">
				<span class="field-value">
					<?php _e("Datant de moins de 3 mois", 'yproject'); ?><br />
					<?php if ( isset($current_file_kbis) ): ?>
						<a id="org_doc_kbis" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_kbis->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_kbis->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
					<input type="file" name="org_doc_kbis">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_doc_status"><?php _e("Statuts de la soci&eacute;t&eacute;, certifi&eacute;s conformes à l'original par le g&eacute;rant*", 'yproject'); ?></label>
			<div class="field-container align-left">
				<span class="field-value">
					<?php if ( isset($current_file_status) ): ?>
						<a id="org_doc_status" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_status->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_status->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
					<input type="file" name="org_doc_status">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_doc_id"><?php _e("Justificatif d'identit&eacute; du g&eacute;rant ou du pr&eacute;sident*", 'yproject'); ?></label>
			<div class="field-container align-left">
				<span class="field-value">
					<?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br />
					<?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br />
					<?php if ( isset($current_file_id) ): ?>
						<a id="org_doc_id" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_id->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
					<input type="file" name="org_doc_id">
				</span>
			</div>
		</div>

		<div class="field">
			<label for="org_doc_home"><?php _e("Justificatif de domicile du g&eacute;rant ou du pr&eacute;sident*", 'yproject'); ?></label>
			<div class="field-container align-left">
				<span class="field-value">
					<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br />
					<?php if ( isset($current_file_home) ): ?>
						<a id="org_doc_home" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_home->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
					<input type="file" name="org_doc_home">
				</span>
			</div>
		</div>


		<div class="field align-left">
			<span style="color: #EE0000;"><em>--&gt; <?php _e( "Si la r&eacute;partition du capital n'est pas exprim&eacute;e clairement dans les statuts, merci de nous fournir une attestation avec ces pr&eacute;cisions :", 'yproject' ); ?></em></span><br />
			<label for="org_doc_capital_allocation"><?php _e( "Attestation de r&eacute;partition du capital (facultatif)", 'yproject' ); ?></label>
			<div class="field-container">
				<span class="field-value">
					<?php if ( isset( $current_file_capital_allocation ) ): ?>
						<a id="org_doc_capital_allocation" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_capital_allocation->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_capital_allocation->date_uploaded; ?></a>
						<br>
					<?php endif; ?>
					<input type="file" name="org_doc_capital_allocation">
				</span>
			</div>
		</div>


		<p class="align-left" style="color: #EE0000;">
			<em>--&gt; <?php _e( "Si une deuxi&egrave;me personne physique d&eacute;tient au moins 25% du capital, merci de transmettre ces pi&egrave;ces justificatives :", 'yproject' ); ?></em>
		</p>

		<div class="field align-left">
			<label for="org_doc_id_2"><?php _e("Justificatif d'identit&eacute; de la deuxi&egrave;me personne (facultatif)", 'yproject'); ?></label>
			<?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br>
			<?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br>
			<div class="field-container">
				<span class="field-value">
					<?php if ( isset( $current_file_id_2 ) ): ?>
					<a id="org_doc_id_2" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_id_2->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id_2->date_uploaded; ?></a>
					<?php endif; ?>
					<input type="file" name="org_doc_id_2">
				</span>
			</div>
		</div>

		<div class="field align-left">
			<label for="org_doc_home_2"><?php _e("Justificatif de domicile de la deuxi&egrave;me personne (facultatif)", 'yproject'); ?></label>
			<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br>
			<div class="field-container">
				<span class="field-value">
					<?php if ( isset( $current_file_home_2 ) ): ?>
					<a id="org_doc_home_2" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_home_2->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home_2->date_uploaded; ?></a>
					<?php endif; ?>
					<input type="file" name="org_doc_home_2">
				</span>
			</div>
		</div>


		<p class="align-left" style="color: #EE0000;">
			<em>--&gt; <?php _e( "Si une troisi&egrave;me personne physique d&eacute;tient au moins 25% du capital, merci de transmettre ces pi&egrave;ces justificatives :", 'yproject' ); ?></em>
		</p>

		<div class="field align-left">
			<label for="org_doc_id_3"><?php _e("Justificatif d'identit&eacute; de la troisi&egrave;me personne (facultatif)", 'yproject'); ?></label>
			<?php _e("Pour une personne fran&ccedil;aise : carte d'identit&eacute; recto-verso ou passeport fran&ccedil;ais.", 'yproject'); ?><br>
			<?php _e("Sinon : le titre de s&eacute;jour et le passeport d'origine.", 'yproject'); ?><br>
			<div class="field-container">
				<span class="field-value">
					<?php if ( isset( $current_file_id_3 ) ): ?>
					<a id="org_doc_id_3" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_id_3->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_id_3->date_uploaded; ?></a>
					<?php endif; ?>
					<input type="file" name="org_doc_id_3">
				</span>
			</div>
		</div>

		<div class="field align-left">
			<label for="org_doc_home_3"><?php _e("Justificatif de domicile de la troisi&egrave;me personne (facultatif)", 'yproject'); ?></label>
			<?php _e("Datant de moins de 3 mois, provenant d'un fournisseur d'&eacute;nergie (&eacute;lectricit&eacute;, gaz, eau) ou d'un bailleur, ou un relev&eacute; d'imp&ocirc;t datant de moins de 3 mois", 'yproject'); ?><br>
			<div class="field-container">
				<span class="field-value">
					<?php if ( isset( $current_file_home_3 ) ): ?>
					<a id="org_doc_home_3" class="button blue-pale download-file" target="_blank" href="<?php echo $current_file_home_3->get_public_filepath(); ?>"><?php _e("T&eacute;l&eacute;charger le fichier envoy&eacute; le"); ?> <?php echo $current_file_home_3->date_uploaded; ?></a>
					<?php endif; ?>
					<input type="file" name="org_doc_home_3">
				</span>
			</div>
		</div>


		<span style="color: #EE0000;"><em>--&gt; <?php _e( "Si une quatri&egrave;me personne ou une personne morale d&eacute;tient au moins 25% de votre capital, merci de nous le signaler sur support@wedogood.co.", 'yproject' ); ?></em></span><br />



		<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>" />
		<input type="hidden" name="action" value="save_edit_organization" />

		<?php DashboardUtility::create_save_button("orgaedit_form"); ?>
	</form>


	<?php if ( $WDGUser_current->is_admin() ): ?>
		<h3><?php _e('Lemonway', 'yproject'); ?></h3>

		<?php $organization_lemonway_authentication_status = $organization_obj->get_lemonway_status(); ?>
		<?php if ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_blocked): ?>
			<?php _e("Afin de s'authentifier chez notre partenaire Lemonway, les informations suivantes sont n&eacute;cessaires : e-mail, description, num&eacute;ro SIREN. Ainsi que les 5 documents suivis d'une &eacute;toile ci-dessus.", 'yproject'); ?><br />
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_ready): ?>
			<form action="" method="POST">
				<input type="hidden" name="authentify_lw" value="1" />
				<input type="submit" class="button" value="<?php _e("Authentifier chez Lemonway", 'yproject'); ?>" />
			</form>
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_waiting): ?>
			<?php _e("L'organisation est en cours d'authentification aupr&egrave;s de notre partenaire.", 'yproject'); ?>
			<form action="" method="POST">
				<input type="hidden" name="authentify_lw" value="1" />
				<input type="submit" class="button" value="<?php _e("Authentifier chez Lemonway", 'yproject'); ?>" />
			</form>
		<?php elseif ($organization_obj->is_registered_lemonway_wallet()): ?>
			<?php _e("L'organisation est bien authentifi&eacute;e aupr&egrave;s de notre partenaire.", 'yproject'); ?>
		<?php elseif ($organization_lemonway_authentication_status == WDGOrganization::$lemonway_status_rejected): ?>
			<?php _e("L'organisation a &eacute;t&eacute; refus&eacute;e par notre partenaire.", 'yproject'); ?>
		<?php endif; ?>

	<?php endif; ?>


	<?php
	/**
	 * Transferts d'argent
	 */
	?>
	<h2 class="underlined"><?php _e( 'Transferts d&apos;argent', 'yproject' ); ?></h2>
	<?php
	$args = array(
		'author'	    => $organization_obj->get_wpref(),
		'post_type'	    => 'withdrawal_order',
		'post_status'   => 'any',
		'orderby'	    => 'post_date',
		'order'			=>  'ASC'
	);
	$transfers = get_posts($args);
	if ($transfers) :
	?>
	<ul class="user_history">
		<?php foreach ( $transfers as $post ) :
			$post = get_post($post);
			$date_lemonway = new DateTime( '2016-08-03' );
			$date_transfer = new DateTime( $post->post_date );
			$post_amount = ($date_transfer > $date_lemonway) ? $post->post_title : $post->post_title / 100;
			if ($post->post_status == 'publish') {
				?>
				<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Termin&eacute;</li>
				<?php
			} else if ($post->post_status == 'draft') {
				?>
				<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Annul&eacute;</li>
				<?php
			} else {
				?>
				<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- En cours</li>
				<?php
			}
		endforeach; ?>
	</ul>
	<?php else: ?>
		Aucun transfert.
	<?php endif; ?>                      

</div>