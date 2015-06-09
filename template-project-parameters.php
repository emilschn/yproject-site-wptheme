<?php 
/**
 * Template Name: Projet Paramètres
 *
 */
BoppLibHelpers::check_create_role(BoppLibHelpers::$project_organisation_manager_role['slug'], BoppLibHelpers::$project_organisation_manager_role['title']);
if (isset($_POST['action'])) $feedback = YPProjectLib::form_validate_edit_parameters();
?>

<?php get_header(); ?>
<div id="content">
	<div class="padder">
		<?php require_once('projects/single-admin-bar.php'); ?>

		<div class="center margin-height">
		    
			<?php if ($feedback === TRUE): ?>
		    
				<p class="success">Enregistrement effectu&eacute;.</p>
				
			<?php endif; ?>
		    
			<?php if ($feedback === FALSE): ?>
		    
				<p class="error">Il y a eu des erreurs pendant l&apos;&eacute;dition du formulaire.</p>
				
			<?php endif; ?>
		    
			<?php global $can_modify; ?>

			<?php if ($can_modify): ?>
				<?php
				global $campaign_id; 
				$post_campaign = get_post($campaign_id);
				$campaign = atcf_get_campaign($post_campaign);

				//Gestion des catégories
				$campaign_categories = get_the_terms($campaign_id, 'download_category');
				$selected_category = 0;
				$selected_activity = 0;
				$terms_category = get_terms( 'download_category', array('slug' => 'categories', 'hide_empty' => false));
				$term_category_id = $terms_category[0]->term_id;
				$terms_activity = get_terms( 'download_category', array('slug' => 'activities', 'hide_empty' => false));
				$term_activity_id = $terms_activity[0]->term_id;
				if ($campaign_categories) {
					foreach ($campaign_categories as $campaign_category) {
						if ($campaign_category->parent == $term_category_id) {
							$selected_category = $campaign_category->term_id;
						}
						if ($campaign_category->parent == $term_activity_id) {
							$selected_activity = $campaign_category->term_id;
						}
					}
				}
				?>
				
			
				<form action="" method="POST" enctype="multipart/form-data" class="wdg-forms">

					<label for="project-name">Nom du projet :</label>
					<input type="text" name="project-name" value="<?php echo $post_campaign->post_title; ?>" /><br />
					
					<label for="categories">Cat&eacute;gorie :</label>
					<?php wp_dropdown_categories( array( 
					    'hide_empty'  => 0,
					    'taxonomy'    => 'download_category',
					    'selected'    => $selected_category,
					    'echo'        => 1,
					    'child_of'    => $term_category_id, 
					    'name'        => 'categories'
					) ); ?><br />
					
					<a id="picture-head"></a><a id="video-zone"></a><?php /* ancre déplacée pour cause de menu... */ ?>
					<label for="activities">Secteur d&apos;activit&eacute; :</label>
					<?php wp_dropdown_categories( array( 
					    'hide_empty'  => 0,
					    'taxonomy'    => 'download_category',
					    'selected'    => $selected_activity,
					    'echo'        => 1,
					    'child_of'    => $term_activity_id, 
					    'name'        => 'activities'
					) ); ?><br />

					<label for="project-location">Localisation :</label>
					<select name="project-location">
						<?php 
						$locations = atcf_get_locations();
						$location_str = '';
						foreach ($locations as $location) {
							$selected_str = ($location == $campaign->location()) ? 'selected="selected"' : '';
							$location_str .= '<option ' . $selected_str . '>' . $location . '</option>';
						}
						echo $location_str;
						?>
					</select><br />
					
					<?php if ($campaign->campaign_status() == "preparing") : ?>
						<label for="fundingtype">Type de financement :</label>
						<?php
						$funding_project_selected = ($campaign->funding_type() == 'fundingproject') ? 'checked="checked"' : '';
						$funding_dev_selected = ($campaign->funding_type() == 'fundingdevelopment') ? 'checked="checked"' : '';
						$funding_donation_selected = ($campaign->funding_type() == 'fundingdonation') ? 'checked="checked"' : '';
						?>
						<input type="radio" name="fundingtype" class="radiofundingtype first" id="fundingproject" value="fundingproject" <?php echo $funding_project_selected; ?>>Financement d'un projet<br />
						<input type="radio" name="fundingtype" class="radiofundingtype" id="fundingdevelopment" value="fundingdevelopment" <?php echo $funding_dev_selected; ?>>Capital (coop&eacute;ratives SA uniquement)<br />
						<input type="radio" name="fundingtype" class="radiofundingtype" id="fundingdonation" value="fundingdonation" <?php echo $funding_donation_selected; ?>>Don avec contrepartie<br />

						<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
						<label for="fundingduration">Dur&eacute;e du financement :</label>
						<input type="text" name="fundingduration" value="<?php echo $campaign->funding_duration(); ?>"> ann&eacute;es.<br />
						<?php endif; ?>
						
						<label>Montant demand&eacute; (seulement des chiffres) :</label>
						<?php $goal = (int)$campaign->goal(false); ?>
						Minimum : <input type="text" name="minimum_goal" size="10" value="<?php echo $campaign->minimum_goal(); ?>"> &euro; (Min. 500&euro;) - 
						Maximum : <input type="text" name="maximum_goal" size="10" value="<?php echo $goal; ?>"> &euro;
					
					<?php else: ?>
						<label>Type de financement :</label>
						<?php 
						switch ($campaign->funding_type()) {
							case 'fundingproject':
								echo 'Financement d&apos;un projet<br />';
								break;
							case 'fundingdevelopment':
								echo 'Capital (coop&eacute;ratives SA uniquement)<br />';
								break;
						}
						?>

						<label>Dur&eacute;e du financement :</label>
						<?php echo $campaign->funding_duration(); ?> ann&eacute;es.<br />

						<label>Montant demand&eacute; :</label>
						<?php $goal = (int)$campaign->goal(false); ?>
						Entre <?php echo $campaign->minimum_goal(); ?> &euro; et <?php echo $goal; ?> &euro;<br />
					
					<?php endif; ?>
					
						
					<?php
					// Gestion des organisations
					$str_organisations = '';
					global $current_user;
					$api_project_id = BoppLibHelpers::get_api_project_id($post_campaign->ID);
					$current_organisations = BoppLib::get_project_organisations_by_role($api_project_id, BoppLibHelpers::$project_organisation_manager_role['slug']);
					if (isset($current_organisations) && count($current_organisations) > 0) {
						$current_organisation = $current_organisations[0];
					}
					$api_user_id = BoppLibHelpers::get_api_user_id($post_campaign->post_author);
					$organisations_list = BoppUsers::get_organisations_by_role($api_user_id, BoppLibHelpers::$organisation_creator_role['slug']);
					if ($organisations_list) {
						foreach ($organisations_list as $organisation_item) {
							$selected_str = ($organisation_item->id == $current_organisation->id) ? 'selected="selected"' : '';
							$str_organisations .= '<option ' . $selected_str . ' value="'.$organisation_item->organisation_wpref.'">' .$organisation_item->organisation_name. '</option>';
						}
					}
					?>
					<label for="project-organisation">Organisation :</label>
					    
					<?php if ($str_organisations != ''): ?>
						<select name="project-organisation">
							<option value=""></option>
							<?php echo $str_organisations; ?>
						</select>

					<?php else: ?>
						<?php _e('Le porteur de projet n&apos;est li&eacute; &agrave; aucune organisation.', 'yproject'); ?>
						<input type="hidden" name="project-organisation" value="" />

					<?php endif; ?>
					
					<input type="submit" name="new_orga" value="Cr&eacute;er une organisation" class="small-margin button" />
					<br />
					<br />
					
                                        <label for="phone">Num&eacute;ro de t&eacute;l&eacute;phone de contact : </label>
                                        <input type="text" name="phone" value="<?php echo $campaign->contact_phone(); ?>" /><br />
						
					<?php $image_src_header = $campaign->get_header_picture_src(false); ?>
					<label for="image_header">Image du bandeau :</label>
					<input type="file" name="image_header" /><br />
					<span class="extra-field">(Max. 2Mo ; id&eacute;alement 1366px de largeur * 370px de hauteur)</span><br />
					<input type="checkbox" name="image_header_blur" <?php if ($campaign->is_header_blur()) { echo 'checked="checked"'; } ?> /> Appliquer un flou artistique<br />
					<?php if ($image_src_header != '') { ?><img src="<?php echo $image_src_header; ?>" /><br /><?php } ?>
					
					<?php $image_src_home = $campaign->get_home_picture_src(false); ?>
					<label for="image_home">Image d&apos;aper&ccedil;u :</label>
					<input type="file" name="image_home" /><br />
					<span class="extra-field">(Max. 2Mo ; id&eacute;alement 610px de largeur * 330px de hauteur)</span><br />
					<?php if ($image_src_home != '') { ?><img src="<?php echo $image_src_home; ?>" /><br /><?php } ?>
					
					<label for="video">Vid&eacute;o de pr&eacute;sentation :</label>
					<input type="text" name="video" placeholder="URL de la vidéo" value="<?php echo $campaign->video(); ?>" /><br />
						
					<input type="hidden" name="action" value="edit-project-parameters" />
						
					<input type="submit" value="Enregistrer" />
				    
				</form>

			<?php else: ?>

				<?php _e('Vous n&apos;avez pas la permission pour voir cette page.', 'yproject'); ?>

			<?php endif; ?>

		</div>
	</div><!-- .padder -->
</div><!-- #content -->

	
<?php get_footer(); ?>