<?php 
/**
 * Template Name: Projet Gestion financière
 *
 */
?>

<?php get_header(); ?>
<div id="content">
	<div class="padder">
		<?php require_once('projects/single-admin-bar.php'); ?>

		<div id="project-wallet" class="center margin-height">
		    
			<?php global $can_modify; ?>

			<?php if ($can_modify): ?>
		    
				<?php
				if (have_posts()) {
				    the_post();
				    the_content();
				}
				$keep_going = TRUE;
				?>
		    
				<h2><?php _e('Porte-monnaie', 'yproject'); ?></h2>
				<h3>1 - <?php _e('Associer une organisation &agrave; votre projet', 'yproject'); ?></h3>
				<?php if ($keep_going) {
					//Init variables utiles
					global $campaign_id, $current_user; 
					$post_campaign = get_post($campaign_id);
					$campaign = atcf_get_campaign($post_campaign);
					$api_project_id = BoppLibHelpers::get_api_project_id($post_campaign->ID);

					//Vérification si une organisation a bien été définie
					$current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
					if (isset($current_organisations) && count($current_organisations) > 0) {
						$current_organisation = $current_organisations[0];
					}
					if (isset($current_organisation)) {
						$page_edit_orga = get_page_by_path('editer-une-organisation');
						echo __('Organisation d&eacute;finie :', 'yproject') . ' ' . $current_organisation->organisation_name . ' <a class="button" href="'.  get_permalink($page_edit_orga->ID) .'?orga_id='.$current_organisation->organisation_wpref.'">' . __('Editer', 'yproject') . '</a>';
					} else {
						$keep_going = FALSE;
						_e('Pas encore d&eacute;fini', 'yproject');
						$page_parameters = get_page_by_path('parametres-projet');
						echo ' - <a href="' .get_permalink($page_parameters->ID) . $campaign_id_param . $params_partial . '">' . __('Param&egrave;tres', 'yproject') . '</a>';
					}
				} ?>
				
				<h3 <?php if (!$keep_going) { ?>class="grey"<?php } ?>>2 - <?php _e('Documents d&apos;authentification', 'yproject'); ?></h3>
				<?php if ($keep_going) {
					locate_template( array("requests/organisations.php"), true );
					$organisation_obj = new YPOrganisation($current_organisation->organisation_wpref);
					$organisation_obj->check_strong_authentication();
					$strongauth_status = ypcf_mangopay_get_user_strong_authentication_status($organisation_obj->get_wpref());
					if ($strongauth_status['message'] != '') { echo $strongauth_status['message'] . '<br />'; }

					switch ($organisation_obj->get_strong_authentication()) {
						case 0:
							$keep_going = FALSE;
							?>
							Afin de lutter contre le blanchiment d&apos;argent, pour tout investissement de plus de <strong><?php echo YP_STRONGAUTH_AMOUNT_LIMIT; ?>&euro;</strong> sur l&apos;ann&eacute;e,
							ou pour retirer plus de <strong><?php echo YP_STRONGAUTH_REFUND_LIMIT; ?>&euro;</strong>,
							nous devons transmettre les pi&egrave;ces d&apos;identit&eacute; suivantes &agrave; notre partenaire Mangopay
							(Les fichiers doivent &ecirc;tre de type jpeg, gif, png ou pdf et leur poids inf&eacute;rieur &agrave; 2 Mo) :<br /><br />

							<form>
							<label for="org_file_cni" class="large">Pi&egrave;ce d&apos;identit&eacute; recto-verso de la personne repr&eacute;sentant l'organisation *</label>
							<input type="file"name="org_file_cni" /> <br />

							<label for="org_file_status" class="large">Statuts sign&eacute;s *</label>
							<input type="file"name="org_file_status" /> <br />

							<label for="org_file_extract" class="large">Extrait du registre de commerce datant de moins de 3 mois *</label>
							<input type="file"name="org_file_extract" /> <br />

							<label for="org_file_declaration" class="large">D&eacute;claration de b&eacute;n&eacute;ficiaire &eacute;conomique (si aucun actionnaire personne physique n'est identifi&eacute; dans les statuts)</label>
							<input type="file"name="org_file_declaration" /><br />
							
							<input type="submit" value="<?php _e('Envoyer', 'yproject'); ?>" class="button" />
							</form>
							<?php
							break;
						case 1:
							_e('Cette organisation est identifi&eacute;e et valid&eacute;e par notre partenaire Mangopay.', 'yproject');
							?><br /><br /><?php
							break;
						case 5:
							$keep_going = FALSE;
							//Le message d'attente est affiché dans le statut de strong authentication.
							break;
					}
				} ?>
						
				<h3 <?php if (!$keep_going) { ?>class="grey"<?php } ?>>3 - <?php _e('RIB', 'yproject'); ?></h3>
				<?php if ($keep_going) { ?>
					<form>
						<label for="org_bankownername"><?php _e('Nom du propri&eacute;taire du compte', 'yproject'); ?></label>
						<input type="text" name="org_bankownername" value="<?php echo $organisation_obj->get_bank_owner(); ?>" /> <br />

						<label for="org_bankowneraddress"><?php _e('Adresse du compte', 'yproject'); ?></label>
						<input type="text" name="org_bankowneraddress" value="<?php echo $organisation_obj->get_bank_address(); ?>" /> <br />

						<label for="org_bankowneriban"><?php _e('IBAN', 'yproject'); ?></label>
						<input type="text" name="org_bankowneriban" value="<?php echo $organisation_obj->get_bank_iban(); ?>" /> <br />

						<label for="org_bankownerbic"><?php _e('BIC', 'yproject'); ?></label>
						<input type="text" name="org_bankownerbic" value="<?php echo $organisation_obj->get_bank_bic(); ?>" /> <br />
							
						<input type="submit" value="<?php _e('Enregistrer', 'yproject'); ?>" class="button" />
					</form>
				<?php } ?>
				
					
				<h2 <?php if (!$keep_going) { ?>class="grey"<?php } ?>><?php _e('Reverser aux investisseurs', 'yproject'); ?></h2>
				<?php if ($keep_going) { ?>
				<?php } ?>
				
				
				<h2 <?php if (!$keep_going) { ?>class="grey"<?php } ?>><?php _e('Liste des op&eacute;rations bancaires', 'yproject'); ?></h2>
				<?php if ($keep_going) { ?>
				<?php } ?>

			<?php else: ?>

				<?php _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject'); ?>

			<?php endif; ?>

		</div>
	</div><!-- .padder -->
</div><!-- #content -->

	
<?php get_footer(); ?>