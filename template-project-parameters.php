<?php 
/**
 * Template Name: Projet Paramètres
 *
 */
BoppLibHelpers::check_create_role(BoppLibHelpers::$project_organisation_manager_role['slug'], BoppLibHelpers::$project_organisation_manager_role['title']);
if (isset($_POST['action'])) $feedback = WDGFormProjects::form_validate_edit_parameters();
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
					
                                        <a id="picture-head"></a><a id="video-zone"></a><a id="project-owner"></a><?php /* ancres déplacées pour cause de menu... */ ?>
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
						<?php echo '<span>';
						switch ($campaign->funding_type()) {
							case 'fundingproject':
								echo 'Avance sur chiffre d&apos;affaires (royalties)<br />';
								break;
							case 'fundingdevelopment':
								echo 'Capital pour les coop&eacute;ratives<br />';
								break;
							case 'fundingdonation':
								echo 'Don avec contrepartie<br />';
								break;
						}
						echo '</span>';
						?>
                                                
						<?php if ($campaign->funding_type() != 'fundingdonation'): ?>
						<label>Dur&eacute;e du financement :</label>
						<span><?php echo $campaign->funding_duration(); ?> ann&eacute;es.</span><br />
						<?php endif; ?>
                                                
						<label>Montant demand&eacute; :</label>
						<?php $goal = (int)$campaign->goal(false); ?>
						<span>Entre <?php echo $campaign->minimum_goal(); ?> &euro; et <?php echo $goal; ?> &euro;</span><br />
					
					<?php endif; 
                                        
					//Gestion des contreparties
					if ($campaign->funding_type() == 'fundingdonation'):
						$rewards = atcf_get_rewards($campaign_id);
						$status = $campaign->campaign_status();
						$can_edit = ( $status == "preparing" || $status == "preview" || $status == "vote");
					?>
					
						<label for="project-rewards">Contreparties :</label><br/>
						<div class="reward-table-param"><table>
							<thead>
								<tr>
									<th>Nom de la contrepartie</th>
									<th>Montant</th>
									<th>Limite</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=0;
								foreach ($rewards->rewards_list as $value) {
									if ($can_edit){
										$line = '<tr>'
											.'<td><input name="reward-name-'.$i.'" type="text" name="" value="'.$value['name'].'" placeholder="Nommez et d&eacute;crivez bri&egrave;vement la contrepartie" class="reward-text" />'
											.'<input name="reward-id-'.$i.'" type="hidden" value="'.$value['id'].'"/></td>'
											.'<td><input name="reward-amount-'.$i.'" type="number" min="0" name="" value="'.$value['amount'].'" placeholder="0"/>€</td>'
											.'<td><input name="reward-limit-'.$i.'" type="number" min="0" value="'.$value['limit'].'" placeholder="0"/></td>'
											.'</tr>';
									} else {
										$line = '<tr>'
											.'<td>'.$value['name'].'</td>'
											.'<td>'.$value['amount'].'</td>'
											.'<td>'.$value['limit'].'</td>'
											.'</tr>';
									}
									echo $line;
									$i++;
								}
								if ($can_edit) { for ($loop=0; $loop<=2; $loop++){ ?>
								<tr>
									<td><input name="reward-name-<?php echo $i+$loop;?>" type="text" name="" value="" placeholder="Nommez et d&eacute;crivez bri&egrave;vement la contrepartie" class="reward-text"/></td>
									<td><input name="reward-amount-<?php echo $i+$loop;?>" type="number" min="0" name="" value="" placeholder=""/></td>
									<td><input name="reward-limit-<?php echo $i+$loop;?>" type="number" min="0" name="" value="" placeholder=""/></td>
								</tr>
								<?php } } ?>
							</tbody>
						</table></div>
						<br/>
					<?php
					endif;
                                        
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
						<?php if ($current_organisation!=null){
							$page_edit_orga = get_page_by_path('editer-une-organisation');
							$edit_org .= '<a class="button" href="'.  get_permalink($page_edit_orga->ID) .'?orga_id='.$current_organisation->organisation_wpref.'">';
							$edit_org .= 'Editer '.$current_organisation->organisation_name.'</a>';
							echo $edit_org;
						} ?>

					<?php else: ?>
						<?php _e('Le porteur de projet n&apos;est li&eacute; &agrave; aucune organisation.', 'yproject'); ?>
						<input type="hidden" name="project-organisation" value="" />

					<?php endif; ?>
					
					<input type="submit" name="new_orga" value="Cr&eacute;er une organisation" class="small-margin button" />
					<br />
					<br />
					
					<label for="phone">Num&eacute;ro de t&eacute;l&eacute;phone de contact : </label>
					<input type="text" name="phone" value="<?php echo $campaign->contact_phone(); ?>" /><br />
					
					<label for="video">Vid&eacute;o de pr&eacute;sentation :</label>
					<input type="text" name="video" placeholder="URL de la vidéo" value="<?php echo $campaign->video(); ?>" /><br />
					<?php if($campaign->video()!=''){ ?><div class="video-zone">
							<?php echo wp_oembed_get($campaign->video(), array('width' => 580, 'height' => 325)); ?>
					</div><?php } ?><br />
						
					<?php $image_src_header = $campaign->get_header_picture_src(false); ?>
					<label for="image_header">Image du bandeau :</label>
					<input type="file" name="image_header" /><br />
					<span class="extra-field">(Max. 2Mo ; id&eacute;alement 370px de hauteur et au minimum 960px de largeur)</span><br />
					<input type="checkbox" name="image_header_blur" <?php if ($campaign->is_header_blur()) { echo 'checked="checked"'; } ?> /> Appliquer un flou artistique<br />
					<?php if ($image_src_header != '') { ?><img style="max-width: 100%;" src="<?php echo $image_src_header; ?>" /><br /><?php } ?>
					
					<?php $image_src_home = $campaign->get_home_picture_src(false); ?>
					<label for="image_home">Image d&apos;aper&ccedil;u :</label>
					<input type="file" name="image_home" /><br />
					<span class="extra-field">(Max. 2Mo ; id&eacute;alement 615px de largeur * 330px de hauteur)</span><br />
					<?php if ($image_src_home != '') { ?><img style="max-width: 100%;" src="<?php echo $image_src_home; ?>" /><br /><?php } ?>
                                        
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