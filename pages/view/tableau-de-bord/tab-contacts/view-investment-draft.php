<?php
global $stylesheet_directory_uri, $country_list, $address_number_complements;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$investments_drafts = $page_controler->get_campaign()->investment_drafts();
?>

<?php
function view_investment_draft_helper_apply_draft( $is_existing_user, $current_data, $new_data, $data_type, $data_to_save = FALSE ) {
	global $stylesheet_directory_uri;
	if ( empty( $data_to_save ) ) {
		$data_to_save = htmlentities( $new_data );
	}
?>
	<?php if ( $is_existing_user ): ?>
		<?php if ( $current_data == $new_data ): ?>
			<i class="text-green"><?php _e( "OK", 'yproject' ) ?></i>
		<?php else: ?>
			<br>
			-- <i><?php _e( "Donn&eacute;e actuelle :" ); ?></i> <?php echo $current_data; ?>
			<button type="button" class="apply-draft-data button admin-theme" data-type="<?php echo $data_type; ?>" data-value="<?php echo $data_to_save; ?>"><?php _e( "Appliquer", 'yproject' ) ?></button>
			<img id="img-loading-data-<?php echo $data_type; ?>" class="hidden" src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading">
			<br>
		<?php endif; ?>
	<?php endif; ?>
	<br>
<?php
}
?>

<?php if ( !empty( $investments_drafts ) ): ?>
<div id="investment-drafts-list">
	<h3><?php _e( "Ch&egrave;ques en attente de validation (ajout&eacute;s via le Tableau de bord)", 'yproject' ); ?></h3>
	<ul>
		<?php foreach ( $investments_drafts as $investments_drafts_item ): ?>
		<?php
		if ( $investments_drafts_item->status != 'draft' ) {
			continue;
		}
		$investments_drafts_item_data = json_decode( $investments_drafts_item->data );
		?>
		<li>
			<?php echo $investments_drafts_item_data->email .' : '. $investments_drafts_item_data->invest_amount .' €'; ?>
			<?php if ( $page_controler->can_access_admin() ): ?>
				<button class="button admin-theme btn-view-investment-draft" data-draftid="<?php echo $investments_drafts_item->id ; ?>"><?php _e( "Voir", 'yproject' ); ?></button>
					
				<?php
				// Récupération si jamais un utilisateur existe déjà avec ces données
				$user_existing_by_email = get_user_by( 'email', $investments_drafts_item_data->email );
				$is_existing_user = FALSE;
				$WDGUser_existing = FALSE;
				if ( !empty( $user_existing_by_email ) ) {
					$WDGUser_existing = new WDGUser( $user_existing_by_email->ID );
					$is_existing_user = TRUE;
				}
				$is_existing_orga = FALSE;
				$WDGOrganization_existing = FALSE;
				if ( $investments_drafts_item_data->user_type == 'orga' && $investments_drafts_item_data->orga_id != 'new-orga' ) {
					$WDGOrganization_existing = new WDGOrganization( $investments_drafts_item_data->orga_id );
					$is_existing_orga = TRUE;
				}
				?>
				
				<form id="preview-investment-draft-<?php echo $investments_drafts_item->id; ?>" class="db-form hidden">
					<div class="field admin-theme" data-campaignid="<?php echo $page_controler->get_campaign()->ID; ?>" data-draftid="<?php echo $investments_drafts_item->id ; ?>" data-userid="<?php echo ( $is_existing_user ? $WDGUser_existing->get_wpref() : '' ); ?>" data-orgaid="<?php echo ( $is_existing_orga ? $WDGOrganization_existing->get_wpref() : '' ); ?>">
						<b><?php _e( "Montant de l'investissement :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->invest_amount .' €'; ?><br>
						<b><?php _e( "En tant que personne", 'yproject' ) ?> <?php echo ( $investments_drafts_item_data->user_type == 'user' ) ? "physique" : "morale"; ?></b><br>
						<br>
						
						<b><?php _e( "E-mail de l'investisseur :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->email; ?><br>
						<?php if ( $is_existing_user ): ?>
							<i class="text-green"><?php _e( "Utilisateur existant", 'yproject' ) ?></i><br>
						<?php else: ?>
							<i class="text-green"><?php _e( "Aucun utilisateur ne correspond &agrave; cette adresse e-mail.", 'yproject' ) ?></i><br>
						<?php endif; ?>
						<br>
						
						<b><?php _e( "Sexe :", 'yproject' ) ?></b> <?php echo ( $investments_drafts_item_data->gender == 'male' ) ? "Homme" : "Femme"; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? ( $WDGUser_existing->get_gender() == 'male' ) ? "Homme" : "Femme" : '' ), ( $investments_drafts_item_data->gender == 'male' ) ? "Homme" : "Femme", 'gender', $investments_drafts_item_data->gender ); ?>
						
						<b><?php _e( "Pr&eacute;nom :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->firstname; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_firstname() : '' ), $investments_drafts_item_data->firstname, 'firstname' ); ?>
						
						<b><?php _e( "Nom :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->lastname; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_lastname() : '' ), $investments_drafts_item_data->lastname, 'lastname' ); ?>
						
						<b><?php _e( "Date de naissance :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->birthday; ?>
						<?php $WDGUser_existing_birthday = new DateTime( ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_birthday_date() : '' ) ); ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, $WDGUser_existing_birthday->format( 'd/m/Y' ), $investments_drafts_item_data->birthday, 'birthday' ); ?>
						
						<b><?php _e( "Ville de naissance :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->birthplace; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_birthplace() : '' ), $investments_drafts_item_data->birthplace, 'birthplace' ); ?>
						
						<b><?php _e( "Arrondissement dans la ville de naissance :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->birthplace_district; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_birthplace_district() : '' ), $investments_drafts_item_data->birthplace_district, 'birthplace_district' ); ?>
						
						<b><?php _e( "D&eacute;partement de naissance :", 'yproject :' ) ?></b> <?php echo $investments_drafts_item_data->birthplace_department; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_birthplace_department() : '' ), $investments_drafts_item_data->birthplace_department, 'birthplace_department' ); ?>
						
						<b><?php _e( "Pays de naissance :", 'yproject' ) ?></b> <?php echo $country_list[ $investments_drafts_item_data->birthplace_country ]; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, $country_list[ ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_birthplace_country() : '' ) ], $country_list[ $investments_drafts_item_data->birthplace_country ], 'birthplace_country', $investments_drafts_item_data->birthplace_country ); ?>
						
						<b><?php _e( "Nationalit&eacute; :", 'yproject' ) ?></b> <?php echo $country_list[ $investments_drafts_item_data->nationality ]; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, $country_list[ ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_nationality() : '' ) ], $country_list[ $investments_drafts_item_data->nationality ], 'nationality', $investments_drafts_item_data->nationality ); ?>
						
						<b><?php _e( "Num&eacute;ro (adresse) :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->address_number; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_address_number() : '' ), $investments_drafts_item_data->address_number, 'address_number' ); ?>
						
						<b><?php _e( "Compl&eacute;ment de num&eacute;ro :", 'yproject' ) ?></b> <?php echo $address_number_complements[ $investments_drafts_item_data->address_number_complement ]; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, $address_number_complements[ ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_address_number_complement() : '' ) ], $address_number_complements[ $investments_drafts_item_data->address_number_complement ], 'address_number_complement', $investments_drafts_item_data->address_number_complement ); ?>
						
						<b><?php _e( "Adresse :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->address; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_address() : '' ), $investments_drafts_item_data->address, 'address' ); ?>
						
						<b><?php _e( "Code postal :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->postal_code; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_postal_code() : '' ), $investments_drafts_item_data->postal_code, 'postal_code' ); ?>
						
						<b><?php _e( "Ville :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->city; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_city() : '' ), $investments_drafts_item_data->city, 'city' ); ?>
						
						<b><?php _e( "Pays :", 'yproject' ) ?></b> <?php echo $country_list[ $investments_drafts_item_data->country ]; ?>
						<?php view_investment_draft_helper_apply_draft( $is_existing_user, $country_list[ ( !empty( $WDGUser_existing ) ? $WDGUser_existing->get_country() : '' ) ], $country_list[ $investments_drafts_item_data->country ], 'country', $investments_drafts_item_data->country ); ?>
						<br>
						
						<?php if ( $investments_drafts_item_data->user_type == 'orga' ): ?>
							<b><?php _e( "Donn&eacute;es de l'organisation :", 'yproject' ); ?></b><br>
							<?php if ( $is_existing_orga ): ?>
								<i class="text-green"><?php _e( "Organisation existante", 'yproject' ); ?></i><br>
							<?php else: ?>
								<i class="text-green"><?php _e( "Nouvelle organisation", 'yproject' ); ?></i><br>
							<?php endif; ?>
								
							<b><?php _e( "D&eacute;nomination sociale :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_name; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_name() : '' ), $investments_drafts_item_data->orga_name, 'orga_name' ); ?>
						
							<b><?php _e( "E-mail de contact :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_email; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_email() : '' ), $investments_drafts_item_data->orga_email, 'orga_email' ); ?>
						
							<b><?php _e( "Site internet :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_website; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_website() : '' ), $investments_drafts_item_data->orga_website, 'orga_website' ); ?>
						
							<b><?php _e( "Forme juridique :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_legalform; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_legalform() : '' ), $investments_drafts_item_data->orga_legalform, 'orga_legalform' ); ?>
						
							<b><?php _e( "Num&eacute;ro SIRET :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_idnumber; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_idnumber() : '' ), $investments_drafts_item_data->orga_idnumber, 'orga_idnumber' ); ?>
						
							<b><?php _e( "RCS (Ville) :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_rcs; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_rcs() : '' ), $investments_drafts_item_data->orga_rcs, 'orga_rcs' ); ?>
						
							<b><?php _e( "Capital social (en euros) :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_capital; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_capital() : '' ), $investments_drafts_item_data->orga_capital, 'orga_capital' ); ?>
						
							<b><?php _e( "Num&eacute;ro (adresse) :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_address_number; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_address_number() : '' ), $investments_drafts_item_data->orga_address_number, 'orga_address_number' ); ?>
						
							<b><?php _e( "Compl&eacute;ment de num&eacute;ro :", 'yproject' ) ?></b> <?php echo $address_number_complements[ $investments_drafts_item_data->orga_address_number_comp ]; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $address_number_complements[ $WDGOrganization_existing->get_address_number_comp() ] : '' ), $address_number_complements[ $investments_drafts_item_data->orga_address_number_comp ], 'orga_address_number_comp' ); ?>
						
							<b><?php _e( "Adresse :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_address; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_address() : '' ), $investments_drafts_item_data->orga_address, 'orga_address' ); ?>
						
							<b><?php _e( "Code postal :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_postal_code; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_postal_code() : '' ), $investments_drafts_item_data->orga_postal_code, 'orga_postal_code' ); ?>
						
							<b><?php _e( "Ville :", 'yproject' ) ?></b> <?php echo $investments_drafts_item_data->orga_city; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $WDGOrganization_existing->get_city() : '' ), $investments_drafts_item_data->orga_city, 'orga_city' ); ?>
						
							<b><?php _e( "Pays :", 'yproject' ) ?></b> <?php echo $country_list[ $investments_drafts_item_data->orga_nationality ]; ?>
							<?php view_investment_draft_helper_apply_draft( $is_existing_orga, ( !empty( $WDGOrganization_existing ) ? $country_list[ $WDGOrganization_existing->get_nationality() ] : '' ), $country_list[ $investments_drafts_item_data->orga_nationality ], 'orga_nationality' ); ?>
						
							<br>
						<?php endif; ?>
							
						<?php if ( $is_existing_user ): ?>
						<button type="button" class="apply-draft-data button admin-theme" data-type="all"><?php _e( "Appliquer toutes les donn&eacute;es", 'yproject' ) ?></button>
						<img id="img-loading-data-all" class="hidden" src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading">
						<br><br>
						<?php endif; ?>
							
						<b><?php _e( "Photo du ch&egrave;que :", 'yproject' ) ?></b> <a href="<?php echo $investments_drafts_item->check; ?>" target="_blank"><?php _e( "T&eacute;l&eacute;charger", 'yproject' ); ?></a><br>
						<b><?php _e( "Photos du contrat :", 'yproject' ) ?></b> <a href="<?php echo $investments_drafts_item->contract; ?>" target="_blank"><?php _e( "T&eacute;l&eacute;charger", 'yproject' ); ?></a><br>
						<br>
						
						<?php if ( $is_existing_user ): ?>
							<?php if ( $investments_drafts_item_data->user_type != 'orga' || ( $investments_drafts_item_data->user_type == 'orga' && $is_existing_orga ) ): ?>
								<button type="button" class="create-investment-from-draft button admin-theme"><?php _e( "Valider l'investissement", 'yproject' ) ?></button>
							<?php else: ?>
								<button type="button" class="create-investment-from-draft button admin-theme"><?php _e( "Valider l'investissement (en cr&eacute;ant l'organisation)", 'yproject' ) ?></button>
							<?php endif; ?>
						<?php else: ?>
							<button type="button" class="create-investment-from-draft button admin-theme"><?php _e( "Valider l'investissement (en cr&eacute;ant les donn&eacute;es de l'investisseur)", 'yproject' ) ?></button>
						<?php endif; ?>
						<img id="img-loading-create-investment" class="hidden" src="<?php echo $stylesheet_directory_uri; ?>/images/loading.gif" width="30" alt="loading">
						
					</div>
				</form>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>