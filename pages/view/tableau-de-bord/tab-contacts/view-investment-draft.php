<?php
global $stylesheet_directory_uri, $country_list, $address_number_complements;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$investments_drafts = $page_controler->get_campaign()->investment_drafts();
?>

<?php if ( !empty( $investments_drafts ) ): ?>
<div id="investment-drafts-list">
	<h3><?php _e( "Ch&egrave;ques en attente de validation (ajout&eacute;s via le Tableau de bord)", 'yproject' ); ?></h3>
	<ul>
		<?php foreach ( $investments_drafts as $investments_drafts_item ): $investments_drafts_item_data = json_decode( $investments_drafts_item->data ); ?>
		<li>
			<?php echo $investments_drafts_item_data->email .' : '. $investments_drafts_item_data->invest_amount .' €'; ?>
			<?php if ( $page_controler->can_access_admin() ): ?>
				<button class="button admin-theme btn-view-investment-draft" data-draftid="<?php echo $investments_drafts_item->id ; ?>"><?php _e( "Voir", 'yproject' ); ?></button>
				<form id="preview-investment-draft-<?php echo $investments_drafts_item->id ; ?>" class="db-form hidden">
					<div class="field admin-theme">
						<?php _e( "Montant de l'investissement :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->invest_amount .' €'; ?><br>
						<?php _e( "En tant que personne", 'yproject' ) ?> <?php echo ( $investments_drafts_item_data->user_type == 'user' ) ? "physique" : "morale"; ?><br>
						<br>
						
						<?php _e( "E-mail de l'investisseur :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->email; ?><br>
						<?php _e( "Sexe :", 'yproject' ) ?> <?php echo ( $investments_drafts_item_data->gender == 'male' ) ? "Homme" : "Femme"; ?><br>
						<?php _e( "Pr&eacute;nom :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->firstname; ?><br>
						<?php _e( "Nom :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->lastname; ?><br>
						<?php _e( "Date de naissance :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->birthday; ?><br>
						<?php _e( "Ville de naissance :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->birthplace; ?><br>
						<?php _e( "Arrondissement dans la ville de naissance :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->birthplace_district; ?><br>
						<?php _e( "D&eacute;partement de naissance :", 'yproject :' ) ?> <?php echo $investments_drafts_item_data->birthplace_department; ?><br>
						<?php _e( "Pays de naissance :", 'yproject' ) ?> <?php echo $country_list[ $investments_drafts_item_data->birthplace_country ]; ?><br>
						<?php _e( "Nationalit&eacute; :", 'yproject' ) ?> <?php echo $country_list[ $investments_drafts_item_data->nationality ]; ?><br>
						<?php _e( "Num&eacute;ro (adresse) :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->address_number; ?><br>
						<?php _e( "Compl&eacute;ment de num&eacute;ro :", 'yproject' ) ?> <?php echo $address_number_complements[ $investments_drafts_item_data->address_number_complement ]; ?><br>
						<?php _e( "Adresse :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->address; ?><br>
						<?php _e( "Code postal :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->postal_code; ?><br>
						<?php _e( "Ville :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->city; ?><br>
						<?php _e( "Pays :", 'yproject' ) ?> <?php echo $country_list[ $investments_drafts_item_data->country ]; ?><br>
						<br>
						
						<?php if ( $investments_drafts_item_data->user_type == 'orga' ): ?>
							<?php _e( "Donn&eacute;es de l'organisation :", 'yproject' ); ?><br>
							<?php if ( $investments_drafts_item_data->orga_id == 'new-orga' ): ?>
								<?php _e( "Nouvelle organisation", 'yproject' ); ?><br>
							<?php else: ?>
								<?php _e( "Organisation existante :", 'yproject' ); ?> TODO<br>
							<?php endif; ?>
							<?php _e( "D&eacute;nomination sociale :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_name; ?><br>
							<?php _e( "E-mail de contact :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_email; ?><br>
							<?php _e( "Site internet :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_website; ?><br>
							<?php _e( "Forme juridique :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_legalform; ?><br>
							<?php _e( "Num&eacute;ro SIRET :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_idnumber; ?><br>
							<?php _e( "RCS (Ville) :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_rcs; ?><br>
							<?php _e( "Capital social (en euros) :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_capital; ?><br>
							<?php _e( "Num&eacute;ro (adresse) :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_address_number; ?><br>
							<?php _e( "Compl&eacute;ment de num&eacute;ro :", 'yproject' ) ?> <?php echo $address_number_complements[ $investments_drafts_item_data->org_address_number_comp ]; ?><br>
							<?php _e( "Adresse :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_address; ?><br>
							<?php _e( "Code postal :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_postal_code; ?><br>
							<?php _e( "Ville :", 'yproject' ) ?> <?php echo $investments_drafts_item_data->org_city; ?><br>
							<?php _e( "Pays :", 'yproject' ) ?> <?php echo $country_list[ $investments_drafts_item_data->org_nationality ]; ?><br>
							<br>
						<?php endif; ?>
							
						<?php _e( "Photo du ch&egrave;que :", 'yproject' ) ?> TODO DWNL<br>
						<?php _e( "Photos du contrat :", 'yproject' ) ?> TODO DWNL<br>
					</div>
				</form>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>