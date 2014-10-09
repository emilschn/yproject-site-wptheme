<?php 
$cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-content-first');
$bopp_campaign_id = BoppLibHelpers::get_api_project_id($campaign_id);
$bopp= BoppLib::get_project($bopp_campaign_id);


if(false===$cache_result){
	ob_start();
	$images_folder=get_stylesheet_directory_uri().'/images/';
	global $campaign; 
	$campaign_id_param = '?campaign_id=';
	if (isset($_GET['campaign_id'])) {
		$campaign_id_param .= $_GET['campaign_id'];
		$post = get_post($_GET['campaign_id']);
		$campaign = atcf_get_campaign( $post );
	} else  {
		$campaign_id_param .= $post->ID;
	}
	$vote_status = html_entity_decode($campaign->vote());
	$settings_editor = array( 
		'media_buttons' => true,
		'teeny'         => true,
		'quicktags'     => false,
		'editor_css'    => '<style>body { background: white; }</style>',
		'tinymce'       => array(
			'theme_advanced_path'     => false,
			'theme_advanced_buttons1' => 'bold,italic,bullist,numlist,blockquote,justifyleft,justifycenter,justifyright,justifyfull,link,unlink',
			'plugins'                 => 'paste',
			'paste_remove_styles'     => true
			),
		);
		
	
	$settings_textarea = array( 
		'media_buttons' => false,
		'teeny'         => false,
		'quicktags'     => false,
		'editor_css'    => '<style>body { background: white; }</style>',
		'tinymce'       => false
		);
		?>


	<div id="projects-top-desc">
	<div id="projects-left-desc" class="left">
		<div id="projects-summary">
		<?php if($can_modify){ ?>
			<div class="edit_description">
				<a href="#" data-action="edit_description">Editer</a>
				<span class="cancel_save">
				    <a href="#" data-action="cancel_description">Annuler</a> |
				    <a href="#" data-campaign="<?= $campaign_id ?>" data-action="save_description">Enregistrer</a> 
				</span>
			</div>
			<?php } ?>
			<div class="controls">
				<?php if($can_modify){ ?>
				<textarea type="text" class="edit-input edit-description-field" data-placeholder="" id="projectDescription" name="projectDescription"><?php if ($bopp->project_description) { echo $bopp->project_description;}?></textarea>
				<?php } ?>
				<span class="project_description view-description-content"><?php if ($bopp->project_description) { echo $bopp->project_description;} else { echo "&nbsp"; } ?></span>
				
			</div>
		</div>
		<?php if($can_modify){ ?>
		<div id="tabs">
		    <ul>
		        <li>
		            <a href="#video_project">vid</a>
		        </li>
		        <li>
		            <a href="#image_project">img</a>
		        </li>
		    </ul>
		    <div id="video_project">
		       	<div class="video-zone">
		       		
					<div class="edit_video">
						<a href="#" data-action="edit_video">Editer</a>
						<span class="cancel_save">
						    <a href="#" data-action="cancel_video">Annuler</a> |
						    <a href="#" data-campaign="<?= $campaign_id ?>" data-action="save_video">Enregistrer</a> 
						</span>
					</div>

					<label class="control-label" for="projectVideo"><strong>► VIDEO YOUTUBE </strong></label>
					<div class="controls">
						<input type="text" value="<?php if ($bopp->project_video) { echo $bopp->project_video;}?>" class="edit-input edit-video-field" data-placeholder="" id="projectVideo" name="projectVideo">
					</div>
					<div id="video-project">
					<?php 
					if ($bopp->project_video != "") {
						$video_element = wp_oembed_get($bopp->project_video, array('width' => 550));
						echo $video_element; 
					} else { 
						echo "Il n'y a pas de vidéo pour le moment";
					}
					?>
					</div>	
				</div>
		    </div>
		    <div id="image_project">
		    	<?php 
					$attachments = get_posts( array(
								'post_type' => 'attachment',
								'post_parent' => $post->ID,
								'post_mime_type' => 'image'
					    ));
				    $image_obj_home = '';
				    $image_obj_header = '';
				    $image_src_home = '';
				    $image_src_header = '';

				    //Si on en trouve bien une avec le titre "image_home" on prend celle-là
				    foreach ($attachments as $attachment) {
					if ($attachment->post_title == 'image_home') $image_obj_home = wp_get_attachment_image_src($attachment->ID, "full");
					if ($attachment->post_title == 'image_header') $image_obj_header = wp_get_attachment_image_src($attachment->ID, "full");
				    }
				    echo $image_src_header;
				    //Sinon on prend la première image rattachée à l'article
				    if ($image_obj_home != '') $image_src_home = $image_obj_home[0];
				    if ($image_obj_header != '') $image_src_header = $image_obj_header[0];
				?>
		         <div class="update_field atcf-update-campaign-image-home">
					<label class="update_field_label" for="image_home">Image d&apos;aper&ccedil;u (Max. 2Mo ; id&eacute;alement 610px de largeur * 330px de hauteur)</label><br />
					<?php if ($image_src_home != '') {  ?><div class="update-field-img-home"><img src="<?php echo $image_src_home; ?>" /></div><br /><?php } ?>
					<form id="image_home_upload" method="post" action="#" enctype="multipart/form-data" >
					  <input type="file" name="image_home" id="image_home">
					  <input type='hidden' value='<?php wp_create_nonce( 'upload_home' ); ?>' name='_nonce' />
					  <input type="hidden" name="post_id" id="post_id" value="<?= $campaign_id ?>">
					  <input type="hidden" name="action" id="action" value="save_image_home">
					  <input id="submit-ajax" name="submit-ajax" type="submit" value="upload">
					</form>
					<div id="output1"></div>
				</div>
		    </div>
		</div>
		<?php } else { 

		$video_element = '';
		$img_src = '';
		//Si aucune vidéo n'est définie, ou si on est encore en mode preview, on affiche l'image
		if ($campaign->video() == '' || $vote_status == 'preview') {
			$attachments = get_posts( array(
				'post_type' => 'attachment',
				'post_parent' => $post->ID,
				'post_mime_type' => 'image'
				));
			$image_obj = '';
			//Si on en trouve bien une avec le titre "image_home" on prend celle-là
			foreach ($attachments as $attachment) {
				if ($attachment->post_title == 'image_home') $image_obj = wp_get_attachment_image_src($attachment->ID, "full");
			}
			//Sinon on prend la première image rattachée à l'article
			if ($image_obj == '' && count($attachments) > 0) $image_obj = wp_get_attachment_image_src($attachments[0]->ID, "full");
			if ($image_obj != '') $img_src = $image_obj[0];

		//Sinon on utilise l'objet vidéo fourni par wordpress
		} else {
			$video_element = wp_oembed_get($bopp->project_video, array('width' => 580));
		}
		?>
		<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>');"<?php } ?>>
			<?php echo $video_element; ?>
		</div>
		<?php } ?>
	</div>
	<div id="projects-right-desc" class="right" >
		<div id="project-owner">
			<?php 
			$author_id=get_the_author_meta('ID');
//			print_user_avatar($author_id);
			$author=get_user_meta($author_id);
			?>
			<div id="project-owner-desc" style="width: 100%; text-align: center;">
				<?php echo $author['last_name'][0] . ' ' . $author['first_name'][0]; ?><br />
				<?php echo '@'.$author['nickname'][0]; ?>
				<?php
				
//				echo '<p>'.$author['last_name'][0].' '.$author['first_name'][0].'</p>';
//				echo '<p>@'.$author['nickname'][0].'</p>';
				?>
			</div>
		</div>

		<div id="project-about">
			<p>À propos de<p>
			<p><?php echo get_the_title(); ?></p>
		</div>
		<div id="project-map">
			<?php $cursor_top_position=get_post_meta($post->ID,'campaign_cursor_top_position',TRUE); ?>
			<?php $cursor_left_position=get_post_meta($post->ID,'campaign_cursor_left_position',TRUE); ?>
			<div id="map-cursor" style="<?php if($cursor_top_position!='') echo 'top:'.$cursor_top_position.';'; if($cursor_left_position!='') echo 'left:'.$cursor_left_position; ?> ">
				<p><?php echo $campaign->location(); ?></p>
			</div>
			
		</div>
		<?php
		$cache_result=ob_get_contents();
		$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-content-first',$cache_result);
		ob_end_clean();
		}
		echo $cache_result;
			
			if($can_modify){ ?>
				<a id="move-cursor" href="JavaScript:void(0);" onclick='javascript:WDGProjectPageFunctions.move_cursor(<?php if(isset($_GET['campaign_id'])){echo $_GET['campaign_id'];}else{global $post;echo($post->ID); } ?>)'>Modifier la position du curseur</a>
			<?php } 

			$cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-content-second');
			if(false===$cache_result){
				ob_start();
				?>
	</div>
</div>
<div id="project-description-title-padding"></div>
<div class="part-title-separator">
	<span class="part-title"> 
		Description du projet
	</span>
</div>
<div id="projects-bottom-desc">
	<?php 
	if ($vote_status == 'preview') : 
		//$forum = get_page_by_path('forum');
	?>
	<!--<center><a href="<?php echo get_permalink($forum->ID) . $campaign_id_param; ?>">Participez sur son forum !</a></center>-->
<?php endif; ?>

	


	<div class="indent">
		<!-- -----------------------------------------------
		------------ En quoi consiste le projet ? ----------
		------------------------------------------------- -->

		<div class="projects-desc-item">
			<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>projet.png" data-content="project"/>
			<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>


			<div id="project-content-project" class="projects-desc-content">
			<div class="excerpt">
					<?php if($can_modify){ ?>
					<div class="edit_projects">
						<a href="#" data-action="edit_project">Editer</a>
						<span class="cancel_save">
						    <a href="#" data-action="cancel_project">Annuler</a> |
						    <a href="#" data-campaign="<?= $campaign_id ?>" data-action="save_project">Enregistrer</a> 
						</span>
					</div>
					<?php } ?>
					<h2 >En quoi consiste le projet ?</h2>
					<label class="control-label" for="projectCategory"><strong>► Catégorie :</strong></label>
					
					<div class="controls">
						<?php if($can_modify){ ?>
						<select id="projectCategory" class="edit-projects-field" name="projectCategory">
							<option value="" disabled <?php if ($bopp->project_category!="Collaboratif" || $bopp->project_category!="Social" || $bopp->project_category!="Environnemental" || $bopp->project_category!="Economique") { echo "selected"; }?> >Selectionner</option>
							<option <?php if ($bopp->project_category=="Collaboratif") { echo "selected"; }?> value="Collaboratif">Collaboratif</option>
							<option <?php if ($bopp->project_category=="Social") { echo "selected"; }?> value="Social">Social</option>
							<option <?php if ($bopp->project_category=="Environnemental") { echo "selected"; }?> value="Environnemental">Environnemental</option>
							<option <?php if ($bopp->project_category=="Economique") { echo "selected"; }?> value="Economique">Economique</option>
						</select>
						<?php } ?>
						<span class="project_category view-projects-content"><?php if ($bopp->project_category) { echo $bopp->project_category;} else { echo "&nbsp"; } ?></span>
					</div>
				

					<label class="control-label" for="projectBusinessSector"><strong>► Secteur d'activité :</strong></label>
					<div class="controls">
						<?php if($can_modify){ ?>
						<select id="projectBusinessSector" class="edit-projects-field" name="projectBusinessSector">
							<option value="" disabled <?php if ($bopp->project_business_sector!="Administration publique" || 
																$bopp->project_business_sector!="Agriculture et agroalimentaire" || 
																$bopp->project_business_sector!="Art / design / audiovisuel /culture" || 
																$bopp->project_business_sector!="Bâtiment et travaux publics" || 
																$bopp->project_business_sector!="Commerce / artisanat / distribution" || 
																$bopp->project_business_sector!="Energie" || 
																$bopp->project_business_sector!="Enseignement et formation" || 
																$bopp->project_business_sector!="Fabrication et transformation de biens" || 
																$bopp->project_business_sector!="Finance et assurance,Gestion de l'eau des déchets et dépollution" || 
																$bopp->project_business_sector!="Hébergement et restauration" || 
																$bopp->project_business_sector!="Médias" || 
																$bopp->project_business_sector!="Recherche et cabinets d'étude" || 
																$bopp->project_business_sector!="Services à la personnes / santé / action sociale" || 
																$bopp->project_business_sector!="Sport / loisirs / tourisme / activités récréatives" ||
																$bopp->project_business_sector!="Télécoms / informatique et Internet" ||
																$bopp->project_business_sector!="Transports et logistique" ||
																$bopp->project_business_sector!="Autre")
															{ echo "selected"; }?> >Selectionner</option>
							<option <?php if ($bopp->project_business_sector=="Administration publique") { echo "selected"; }?> value="Administration publique">Administration publique</option>
							<option <?php if ($bopp->project_business_sector=="Agriculture et agroalimentaire") { echo "selected"; }?> value="Agriculture et agroalimentaire">Agriculture et agroalimentaire</option>
							<option <?php if ($bopp->project_business_sector=="Art / design / audiovisuel /culture") { echo "selected"; }?> value="Art / design / audiovisuel /culture">Art / design / audiovisuel /culture</option>
							<option <?php if ($bopp->project_business_sector=="Bâtiment et travaux publics") { echo "selected"; }?> value="Bâtiment et travaux publics">Bâtiment et travaux publics</option>
							<option <?php if ($bopp->project_business_sector=="Commerce / artisanat / distribution") { echo "selected"; }?> value="Commerce / artisanat / distribution">Commerce / artisanat / distribution</option>
							<option <?php if ($bopp->project_business_sector=="Energie") { echo "selected"; }?> value="Energie">Energie</option>
							<option <?php if ($bopp->project_business_sector=="Enseignement et formation") { echo "selected"; }?> value="Enseignement et formation">Enseignement et formation</option>
							<option <?php if ($bopp->project_business_sector=="Fabrication et transformation de biens") { echo "selected"; }?> value="Fabrication et transformation de biens">Fabrication et transformation de biens</option>
							<option <?php if ($bopp->project_business_sector=="Finance et assurance,Gestion de l'eau des déchets et dépollution") { echo "selected"; }?> value="Finance et assurance,Gestion de l'eau des déchets et dépollution">Finance et assurance,Gestion de l'eau des déchets et dépollution</option>
							<option <?php if ($bopp->project_business_sector=="Hébergement et restauration") { echo "selected"; }?> value="Hébergement et restauration">Hébergement et restauration</option>
							<option <?php if ($bopp->project_business_sector=="Médias") { echo "selected"; }?> value="Médias">Médias</option>
							<option <?php if ($bopp->project_business_sector=="Recherche et cabinets d'étude") { echo "selected"; }?> value="Recherche et cabinets d'étude">Recherche et cabinets d'étude</option>
							<option <?php if ($bopp->project_business_sector=="Services à la personnes / santé / action sociale") { echo "selected"; }?> value="Services à la personnes / santé / action sociale">Services à la personnes / santé / action sociale</option>
							<option <?php if ($bopp->project_business_sector=="Services et activités de conseil") { echo "selected"; }?> value="Services et activités de conseil">Services et activités de conseil</option>
							<option <?php if ($bopp->project_business_sector=="Sport / loisirs / tourisme / activités récréatives") { echo "selected"; }?> value="Sport / loisirs / tourisme / activités récréatives">Sport / loisirs / tourisme / activités récréatives</option>
							<option <?php if ($bopp->project_business_sector=="Télécoms / informatique et Internet") { echo "selected"; }?> value="Télécoms / informatique et Internet">Télécoms / informatique et Internet</option>
							<option <?php if ($bopp->project_business_sector=="Transports et logistique") { echo "selected"; }?> value="Transports et logistique">Transports et logistique</option>
							<option <?php if ($bopp->project_business_sector=="Autre") { echo "selected"; }?> value="Autre">Autre</option>
						</select>
						<?php } ?>
						<span class="project_business_sector view-projects-content"><?php if ($bopp->project_business_sector) { echo $bopp->project_business_sector;} else { echo "&nbsp"; } ?></span>
					</div>
					<label class="control-label" for="projectFundingType"><strong>► Mode de financement :</strong></label>
					<div class="controls">
						<?php if($can_modify){ ?>
						<select id="projectFundingType" class="edit-projects-field" name="projectFundingType">
							<option value="" disabled <?php if ($bopp->project_funding_type!="Good project" || $bopp->project_funding_type!="Good company") { echo "selected"; }?> selected>Selectionner</option>
							<option <?php if ($bopp->project_funding_type=="Good project") { echo "selected"; }?> value="Good project">Good project</option>
							<option <?php if ($bopp->project_funding_type=="Good company") { echo "selected"; }?> value="Good company">Good company</option>
						</select>
						<?php } ?>
						<span class="project_funding_type view-projects-content"><?php if ($bopp->project_funding_type) { echo $bopp->project_funding_type;} else { echo "&nbsp"; } ?></span>
					</div>

					<label class="control-label" for="projectFundingDuration"><strong>► Durée de l'investissement</strong></label>
					<div class="controls">
						<?php if($can_modify){ ?>
						<input type="text" value="<?php if ($bopp->project_funding_duration) { echo $bopp->project_funding_duration;}?>" class="edit-input edit-projects-field" data-placeholder="" id="projectFundingDuration" name="projectFundingDuration">
						<?php } ?>
						<span class="project_funding_duration view-projects-content"><?php if ($bopp->project_funding_duration) { echo $bopp->project_funding_duration;} else { echo "&nbsp"; } ?></span>
					</div>

					<?php if($can_modify){ ?> <span class="tooltips project_return_on_investment_tooltips" title="Renseignez le pourcentage estimant le retour sur investissement prévu par le prévisionnel">?</span> <?php } ?>	
					<label class="control-label" for="projectReturnOnInvestment"><strong>► Retour sur investissement prévu :</strong></label>
					<div class="controls">
						<?php if($can_modify){ ?>
						<input type="text" value="<?php if ($bopp->project_return_on_investment) { echo $bopp->project_return_on_investment;}?>" class="edit-input edit-projects-field" data-placeholder="" id="projectReturnOnInvestment" name="projectReturnOnInvestment">
						<?php } ?>	
						<span class="project_return_on_investment view-projects-content"><?php if ($bopp->project_return_on_investment) { echo $bopp->project_return_on_investment;} else { echo "&nbsp"; } ?></span>
					</div>

					<?php if($can_modify){ ?> <span class="tooltips project_investor_benefit_tooltips" title="Renseignez l'avantage que vous pouvez proposer à vos investisseurs (une réduction sur un achat, l'invitation à une visite privée du lieu...)">?</span> <?php } ?>	
					<label class="control-label" for="projectInvestorBenefit"><strong>► Avantage investisseur :</strong></label>
					<div class="controls">
						<?php if($can_modify){ ?>
						<input type="text" value="<?php if ($bopp->project_investor_benefit) { echo $bopp->project_investor_benefit;}?>" class="edit-input edit-projects-field" data-placeholder="Avantage" id="projectInvestorBenefit" name="projectInvestorBenefit">
						<?php } ?>
						<span class="project_investor_benefit view-projects-content"><?php if ($bopp->project_investor_benefit) { echo $bopp->project_investor_benefit;} else { echo "&nbsp"; } ?></span>					
					</div>
					<a href="#" class="readmore">En savoir plus</a>
				</div>

				<div class="more">
					<?php if($can_modify){ ?> <span class="tooltips project_summary_tooltips" title="Résumez le projet concerné par le financement en dix lignes.">?</span> <?php } ?>
					<label class="control-label-wp edit-projects-field" for="projectSummary"><strong>Résumé</strong></label>
					<div class="controls-wp">
						<?php if($can_modify){ ?>
						<span class="edit-input edit-projects-field">
							<?php 
							$content = $bopp->project_summary;
							$editor_id = 'project_summary';
							wp_editor( $content, $editor_id, $settings_editor );
							?>
						</span>
						<?php } ?>
						<span class="project_summary view-projects-content"><?php if ($bopp->project_summary) { echo $bopp->project_summary;} else { echo "&nbsp"; } ?></span>					
					</div>
				</div>
			</div>


			
		</div>


		<!-- -------------------------------------------------------
		--------- Quelle est l'impact sociétal du projet ? ---------
		-------------------------------------------------------- -->

		
		<div class="projects-desc-item">
			<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>sociale.png" data-content="social"/>
			<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
			<div id="project-content-social" class="projects-desc-content">
			
			<div class="excerpt">
				<?php if($can_modify){ ?>
				<div class="edit_societal">
					<a href="#" data-action="edit_societal">Editer</a>
					<span class="cancel_save">
					    <a href="#" data-action="cancel_societal">Annuler</a> |
					    <a href="#" data-campaign="<?= $campaign_id ?>" data-action="save_societal">Enregistrer</a> 
					</span>
				</div>
				<?php } ?>

			
				<h2 >Quelle est l'utilité sociétale du projet ?</h2>
				<div class="control-group">
					<?php if($can_modify){ ?> <span class="tooltips project_economy_excerpt_tooltips" title="En mots clés (résumé de vos impacts économiques)">?</span> <?php } ?>
					<label class="control-label" for="projectEconomyExcerpt"><strong>► Économie:</strong></label>
					<div class="controls">
						<?php if($can_modify){ ?>
						<input type="text" class="edit-societal-field" value="<?php if ($bopp->project_economy_excerpt) { echo $bopp->project_economy_excerpt;}?>" data-placeholder="x emplois, commerce équitable, ..." id="projectEconomyExcerpt" name="projectEconomyExcerpt">
						<?php } ?>
						<span class="project_economy_excerpt view-societal-content"><?php if ($bopp->project_economy_excerpt) { echo $bopp->project_economy_excerpt;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>

				<!-- Text input-->
				<?php if($can_modify){ ?> <span class="tooltips project_social_excerpt_tooltips" title="En mots clés (résumé de vos impacts sociaux)">?</span> <?php } ?>
				<div class="control-group">
					<label class="control-label" for="projectSocialExcerpt"><strong>► Social:</strong></label>
					<div class="controls">
						<?php if($can_modify){ ?>
						<input type="text" class="edit-societal-field" value="<?php if ($bopp->project_social_excerpt) { echo $bopp->project_social_excerpt;}?>" placeholder="éducation, insertion, culture, ..." id="projectSocialExcerpt" name="projectSocialExcerpt">
						<?php } ?>
						<span class="project_social_excerpt view-societal-content"><?php if ($bopp->project_social_excerpt) { echo $bopp->project_social_excerpt;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>

				<!-- Text input-->
				<?php if($can_modify){ ?> <span class="tooltips project_environment_excerpt_tooltips" title="En mots clés (résumé de vos impacts environnementaux)">?</span> <?php } ?>
				<div class="control-group">
					<label class="control-label" for="projectEnvironmentExcerpt"><strong>► Environnement :</strong></label>
					<div class="controls">
						<?php if($can_modify){ ?>
						<input type="text" class="edit-societal-field" value="<?php if ($bopp->project_environment_excerpt) { echo $bopp->project_environment_excerpt;}?>" placeholder="recyclage, agriculture biologique, circuits courts, ..." id="projectEnvironmentExcerpt" name="projectEnvironmentExcerpt">
						<?php } ?>
						<span class="project_environment_excerpt view-societal-content"><?php if ($bopp->project_environment_excerpt) { echo $bopp->project_environment_excerpt;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>
				<a href="#" class="readmore">En savoir plus</a>
			</div>

			<div class="more">
				<!-- div -->
				<?php if($can_modify){ ?> <span class="tooltips project_mission_tooltips" title="A quel problème social ou environnemental votre projet s'attaque-t-il ?
Quelle est la mission de votre projet sa raison d'être, la vision qui le porte, ses valeurs et principes d'action ?">?</span> <?php } ?>
				<div class="control-group">
					<label class="control-label-wp edit-societal-field" for="projectMission"><strong>Mission</strong></label>
					<div class="controls-wp"> 
						<?php if($can_modify){ ?>
						<span class="edit-input edit-societal-field">                    
							<?php 
							$content = $bopp->project_mission;
							$editor_id = 'project_mission';
							wp_editor( $content, $editor_id, $settings_editor );
							?>
						</span>
						<?php } ?>
						<span class="project_mission view-societal-content"><?php if ($bopp->project_mission) { echo $bopp->project_mission;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>

				<!-- div -->
				<?php if($can_modify){ ?> <span class="tooltips project_economy_tooltips" title="Impacts économiques
Qu'apporte votre projet en termes d'emploi et d'activité économique (choix des partenaires, relations commerciales, locaux, etc.) ?">?</span> <?php } ?>
				<div class="control-group">
					<label class="control-label-wp edit-societal-field" for="projectEconomy"><strong>Économie</strong></label>
					<div class="controls-wp">
						<?php if($can_modify){ ?> 
						<span class="edit-input edit-societal-field">                   
							<?php 
							$content = $bopp->project_economy;
							$editor_id = 'project_economy';
							wp_editor( $content, $editor_id, $settings_editor );
							?>
						</span>
						<?php } ?>
						<span class="project_economy view-societal-content"><?php if ($bopp->project_economy) { echo $bopp->project_economy;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>

				<!-- div -->
				<?php if($can_modify){ ?> <span class="tooltips project_social_tooltips" title="Impacts sociaux
Quelles personnes bénéficient directement et indirectement de votre activité ? Comment ?">?</span> <?php } ?>
				<div class="control-group">
					<label class="control-label-wp edit-societal-field" for="projectSocial"><strong>Social</strong></label>
					<div class="controls-wp">
						<?php if($can_modify){ ?> 
						<span class="edit-input edit-societal-field">                  
							<?php 
							$content = $bopp->project_social;
							$editor_id = 'project_social';
							wp_editor( $content, $editor_id, $settings_editor );
							?>
						</span>
						<?php } ?>
						<span class="project_social view-societal-content"><?php if ($bopp->project_social) { echo $bopp->project_social;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>

				<!-- div -->
				<?php if($can_modify){ ?> <span class="tooltips project_environment_tooltips" title="Impacts environnementaux / Quels impacts positifs votre activité a-t-elle sur l'environnement ?
Quelle est l'empreinte environnementale de votre activité et comment la limitez-vous ?">?</span> <?php } ?>
				<div class="control-group">
					<label class="control-label-wp edit-societal-field" for="projectEnvironment"><strong>Environnement</strong></label>
					<div class="controls-wp"> 
						<?php if($can_modify){ ?>
						<span class="edit-input edit-societal-field">                
							<?php 
							$content = $bopp->project_environment;
							$editor_id = 'project_environment';
							wp_editor( $content, $editor_id, $settings_editor );
							?>
						</span>
						<?php } ?>
						<span class="project_environment view-societal-content"><?php if ($bopp->project_environment) { echo $bopp->project_environment;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>

				<!-- div -->
				<?php if($can_modify){ ?> <span class="tooltips project_measure_performance_tooltips" title="Gouvernance
Qu'allez vous mettre en place pour assurer le suivi de vos impacts et impliquer vos parties prenantes dans votre activité ?">?</span> <?php } ?>
				<div class="control-group">
					<label class="control-label-wp edit-societal-field" for="projectMeasurePerformance"><strong>Gouvernance et mesure d'impact</strong></label>
					<div class="controls-wp"> 
						<?php if($can_modify){ ?>
						<span class="edit-input edit-societal-field">                
							<?php 
							$content = $bopp->project_measure_performance;
							$editor_id = 'project_measure_performance';
							wp_editor( $content, $editor_id, $settings_editor );
							?>
						</span>
						<?php } ?>
						<span class="project_measure_performance view-societal-content"><?php if ($bopp->project_measure_performance) { echo $bopp->project_measure_performance;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>

				<!-- div -->
				<?php if($can_modify){ ?> <span class="tooltips project_good_point_tooltips" title="Un mot à ajouter ? Vous êtes drôle ? Votre projet a un aspect ludique ?  un point bonus non encore signalé ? C'est le moment pour en parler !
">?</span> <?php } ?>
				<div class="control-group">
					<label class="control-label-wp edit-societal-field" for="projectGoodPoint"><strong>Good Point</strong></label>
					<div class="controls-wp">
						<?php if($can_modify){ ?>
						<span class="edit-input edit-societal-field">                
							<?php 
							$content = $bopp->project_good_point;
							$editor_id = 'project_good_point';
							wp_editor( $content, $editor_id, $settings_editor );
							?>
						</span>
						<?php } ?>
						<span class="project_good_point view-societal-content"><?php if ($bopp->project_good_point) { echo $bopp->project_good_point;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>
				
			</div>
		</div>

	</div>

		<!-- ------------------------------------------------------------
		-------- Quelle est l'opportunité économique du projet ? --------
		------------------------------------------------------------- -->

		<?php if ($vote_status != 'preview'): ?>
			<div class="projects-desc-item">
				<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>economie.png" data-content="economic" />
				<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
				<div id="project-content-economic" class="projects-desc-content">
					<div class="excerpt">
						<?php if($can_modify){ ?>
						<div class="edit_economy">
							<a href="#" data-action="edit_economy">Editer</a>
							<span class="cancel_save">
							    <a href="#" data-action="cancel_economy">Annuler</a> 
							    <a href="#" data-campaign="<?= $campaign_id ?>" data-action="save_economy">Enregistrer</a> 
							</span>
						</div>
						<?php } ?>

						<h2 >Quelle est l'opportunité économique du projet ?</h2>
						<?php if($can_modify){ ?> <span class="tooltips project_context_excerpt_tooltips" title="En mots clés (résumé du contexte général ci-dessous)">?</span> <?php } ?>
						<div class="control-group">
							<label class="control-label" for="projectContextExcerpt">Contexte</label>
							<div class="controls">
								<?php if($can_modify){ ?>
								<input type="text" class="edit-economy-field" value="<?php if ($bopp->project_context_excerpt) { echo $bopp->project_context_excerpt;}?>" placeholder="" id="projectContextExcerpt" name="projectContextExcerpt">
								<?php } ?>
								<span class="project_context_excerpt view-economy-content"><?php if ($bopp->project_context_excerpt) { echo $bopp->project_context_excerpt;} else { echo "&nbsp"; } ?></span>	
							</div>
						</div>

						<!-- Text input-->
						<?php if($can_modify){ ?> <span class="tooltips project_market_excerpt_tooltips" title="En mots clés (résumé du contexte de marché ci-dessous)">?</span> <?php } ?>
						<div class="control-group">
							<label class="control-label" for="projectMarketExcerpt">Marchés</label>
							<div class="controls">
								<?php if($can_modify){ ?>
								<input type="text" class="edit-economy-field" value="<?php if ($bopp->project_market_excerpt) { echo $bopp->project_market_excerpt;}?>" placeholder="éducation, insertion, culture, ..." id="projectMarketExcerpt" name="projectMarketExcerpt">
								<?php } ?>
								<span class="project_market_excerpt view-economy-content"><?php if ($bopp->project_market_excerpt) { echo $bopp->project_market_excerpt;} else { echo "&nbsp"; } ?></span>	
							</div>
						</div>
						<a href="#" class="readmore">En savoir plus</a>
					</div>

					<div class="more">
						<!-- div -->
						<?php if($can_modify){ ?> 
						<span class="tooltips project_context_tooltips" title="	Résumez en une phrase chaque type de contexte. Politique : expliquez en quoi votre activité est sujette ou non à une agitation politique. Economique : quel est votre marché et comment évolue-t-il, à combien l'estimez vous (€) ? Socio-démographique : quels sont les tendances et styles de vie qui influent sur votre marché et votre activité (incluez des chiffres de votre étude de marché). Technologique : votre activité est-elle dépendante de ou liée à des évolutions technologiques ? Si oui lesquelles ? Environnemental : à quels aspects environnementaux sont liés votre secteur et votre activité (consommation d'eau ou d'énergie, émissions de gaz à effet de serre, etc.) ? En quoi-cela impacte-t-il votre activité ? Légal : quelles sont les contraintes légales susceptibles de peser sur votre activité ?">
						?</span> <?php } ?>
						<div class="control-group">
							<label class="control-label-wp edit-economy-field" for="projectContext"></label>
							<div class="controls-wp">  
								<?php if($can_modify){ ?>  
								<span class="edit-input edit-economy-field">                
								<?php 
								$content = $bopp->project_context;
								$editor_id = 'project_context';
								wp_editor( $content, $editor_id, $settings_editor );
								?>
								</span>
								<?php } ?>

								<span class="project_context view-economy-content"><?php if ($bopp->project_context) { echo $bopp->project_context;} else { echo "&nbsp"; } ?></span>	
							</div>
						</div>
	
						<!-- div -->
						<?php if($can_modify){ ?> <span class="tooltips project_market_tooltips" title="Marché : qui sont vos concurrents ? Comment vous différenciez-vous d'eux  ?">?</span> <?php } ?>
						<div class="control-group">
							<label class="control-label-wp edit-economy-field" for="projectMarket"></label>
							<div class="controls-wp"> 
								<?php if($can_modify){ ?>
								<span class="edit-input edit-economy-field">                
								<?php 
								$content = $bopp->project_market;
								$editor_id = 'project_market';
								wp_editor( $content, $editor_id, $settings_editor );
								?>
								</span>
								<?php } ?>
								<span class="project_market view-economy-content"><?php if ($bopp->project_market) { echo $bopp->project_market;} else { echo "&nbsp"; } ?></span>	
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<!-- --------------------------------------------------------
		-------- Quelle est le modèle économique du projet ? --------
		--------------------------------------------------------- -->

		<?php if ($vote_status != 'preview'): ?>
			<div class="projects-desc-item">
				<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>model.png" data-content="model" />
				<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
				<div id="project-content-model" class="projects-desc-content">
				<div class="excerpt">
					<?php if($can_modify){ ?>
					<div class="edit_model">
						<a href="#" data-action="edit_model">Editer</a>
						<span class="cancel_save">
						    <a href="#" data-action="cancel_model">Annuler</a> 
						    <a href="#" data-campaign="<?= $campaign_id ?>" data-action="save_model">Enregistrer</a> 
						</span>
					</div>
					<?php } ?>

					<h2 >Quel est le modèle économique du projet ?</h2>
						<!-- Text input-->
						<?php if($can_modify){ ?> <span class="tooltips project_worth_offer_tooltips" title="En mots clés (résumé de votre proposition de valeur ci-dessous)">?</span> <?php } ?>
						<div class="control-group">
							<label class="control-label" for="projectWorthOffer"><strong>► Proposition de valeur</strong></label>
							<div class="controls">
								<?php if($can_modify){ ?>
								<input type="text" class="edit-input edit-model-field"  value="<?php if ($bopp->project_worth_offer) { echo $bopp->project_worth_offer;}?>" placeholder="éducation, insertion, culture, ..." id="projectWorthOffer" name="projectWorthOffer">
								<span class="project_worth_offer view-model-content"><?php if ($bopp->project_worth_offer) { echo $bopp->project_worth_offer;} else { echo "&nbsp"; } ?></span>	
								<?php } ?>
							</div>
						</div>

						<!-- Text input-->
						<?php if($can_modify){ ?> <span class="tooltips project_client_collaborator_tooltips" title="En mots clés (résumé de vos clients et partenaires ci-dessous)">?</span> <?php } ?>
						<div class="control-group">
							<label class="control-label" for="projectClientCollaborator"><strong>► Clients et partenaires</strong></label>
							<div class="controls">
								<?php if($can_modify){ ?>
								<input type="text" class="edit-input edit-input edit-model-field"  value="<?php if ($bopp->project_client_collaborator) { echo $bopp->project_client_collaborator;}?>" placeholder="éducation, insertion, culture, ..." id="projectClientCollaborator" name="projectClientCollaborator">
								<?php } ?>
								<span class="project_client_collaborator view-model-content"><?php if ($bopp->project_client_collaborator) { echo $bopp->project_client_collaborator;} else { echo "&nbsp"; } ?></span>	
							</div>
						</div>

						<!-- Text input-->
						<?php if($can_modify){ ?> <span class="tooltips project_business_core_tooltips" title="En mots clés (résumé de vos activités ci-dessous)">?</span> <?php } ?>
						<div class="control-group">
							<label class="control-label" for="projectBusinessCore"><strong>► Coeur de métier :</strong></label>
							<div class="controls">
								<?php if($can_modify){ ?>
								<input type="text" class="edit-input edit-input edit-model-field"  value="<?php if ($bopp->project_business_core) { echo $bopp->project_business_core;}?>" placeholder="éducation, insertion, culture, ..." id="projectBusinessCore" name="projectBusinessCore">
								<?php } ?>
								<span class="project_business_core view-model-content"><?php if ($bopp->project_business_core) { echo $bopp->project_business_core;} else { echo "&nbsp"; } ?></span>	

							</div>
						</div>

						<!-- Text input-->
						<?php if($can_modify){ ?> <span class="tooltips project_income_tooltips" title="En mots clés (résumé de votre structure de revenus ci-dessous)">?</span> <?php } ?>
						<div class="control-group">
							<label class="control-label" for="projectIncome"><strong>► Revenus</strong></label>
							<div class="controls">
								<?php if($can_modify){ ?>
								<input type="text" class="edit-input edit-input edit-model-field" value="<?php if ($bopp->project_income) { echo $bopp->project_income;}?>"  placeholder="éducation, insertion, culture, ..." id="projectIncome" name="projectIncome">	
								<?php } ?>
								<span class="project_income view-model-content"><?php if ($bopp->project_income) { echo $bopp->project_income;} else { echo "&nbsp"; } ?></span>	
							</div>
						</div>

							<!-- Text input-->
							<?php if($can_modify){ ?> <span class="tooltips project_cost_tooltips" title="En mots clés (résumé de votre structure de coûts ci-dessous)">?</span> <?php } ?>
							<div class="control-group">
								<label class="control-label" for="projectCost"><strong>► Coûts</strong></label>
								<div class="controls">
									<?php if($can_modify){ ?>
									<input type="text" class="edit-input edit-input edit-model-field" value="<?php if ($bopp->project_cost) { echo $bopp->project_cost;}?>" placeholder="éducation, insertion, culture, ..." id="projectCost" name="projectCost">				
									<?php } ?>
									<span class="project_cost view-model-content"><?php if ($bopp->project_cost) { echo $bopp->project_cost;} else { echo "&nbsp"; } ?></span>	
								</div>
							</div>

							<a href="#" class="readmore">En savoir plus</a>
							</div>
							<div class="more">

								<div id="canvas">
									<?php if($can_modify){ ?> <span class="tooltips project_collaborators_canvas_tooltips" title="Qui sont vos fournisseurs et partenaires clés ? Quelles sont leurs activités ? Quelles ressources acquérez-vous auprès d'eux ?">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectCollaboratorsCanvas">Partenaires?</label>
										<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">         
											<?php 
											$content = $bopp->project_collaborators_canvas;
											$editor_id = 'project_collaborators_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_collaborators_canvas view-model-content"><?php if ($bopp->project_collaborators_canvas) { echo $bopp->project_collaborators_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>

									<?php if($can_modify){ ?> <span class="tooltips project_activities_canvas_tooltips" title="Quelles activités sont nécessaires pour réaliser et distribuer votre offre (production, réflexion/conseil, animation, plateforme, etc.) ?
Quel est votre coeur de métier ?">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectActivitiesCanvas">Activités?</label>
										<?php if($can_modify){ ?>
										<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_activities_canvas;
											$editor_id = 'project_activities_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_activities_canvas view-model-content"><?php if ($bopp->project_activities_canvas) { echo $bopp->project_activities_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>

									<?php if($can_modify){ ?> <span class="tooltips project_ressources_canvas_tooltips" title="De quelles ressources avez-vous besoin / disposez-vous pour réaliser et vendre votre offre (physiques, intellectuelles, humaines, financières) ?
De quelle manière pourriez-vous utiliser ces ressources différemment ?">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectRessourcesCanvas">Ressources?</label>
										<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_ressources_canvas;
											$editor_id = 'project_ressources_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_ressources_canvas view-model-content"><?php if ($bopp->project_ressources_canvas) { echo $bopp->project_ressources_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>

									<?php if($can_modify){ ?> <span class="tooltips project_worth_offer_canvas_tooltips" title="A quel problèmes/besoins répondez vous chez vos clients (nouveauté, performance, réduction des coûts, accessibilité, marque...) ?
Quels produits / services proposez-vous ?">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectWorthOfferCanvas">Proposition de valeur?</label>
										<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_worth_offer_canvas;
											$editor_id = 'project_worth_offer_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_worth_offer_canvas view-model-content"><?php if ($bopp->project_worth_offer_canvas) { echo $bopp->project_worth_offer_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>

									<?php if($can_modify){ ?> <span class="tooltips project_customers_relations_canvas_tooltips" title="Quelle relation entretenez-vous avec vos clients (assistance personnalisé, self service, communauté d'entraide, etc.) ?">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectCustomersRelationsCanvas">Relation Clients?</label>
										<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_customers_relations_canvas;
											$editor_id = 'project_customers_relations_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_customers_relations_canvas view-model-content"><?php if ($bopp->project_customers_relations_canvas) { echo $bopp->project_customers_relations_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>


									<?php if($can_modify){ ?> <span class="tooltips project_chain_distributions_canvas_tooltips" title="Comment vous faites-vous connaître de vos clients ?
Comment vos clients peuvent-ils acheter votre offre ?
Comment délivrez vous votre offre à vos clients ?
Comment fournissez-vous un service après vente ?">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectChainDistributionsCanvas">Canaux de distribution?</label>
										<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_customers_relations_canvas;
											$editor_id = 'project_chain_distributions_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_chain_distributions_canvas view-model-content"><?php if ($bopp->project_chain_distributions_canvas) { echo $bopp->project_chain_distributions_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>

									<?php if($can_modify){ ?> <span class="tooltips project_clients_canvas_tooltips" title="Qui sont vos clients (par catégorie et type de profil) ? Incluez des chiffres de votre étude de marché.">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectClientsCanvas">Clients?</label>
										<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_clients_canvas;
											$editor_id = 'project_clients_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_clients_canvas view-model-content"><?php if ($bopp->project_clients_canvas) { echo $bopp->project_clients_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>

									<?php if($can_modify){ ?> <span class="tooltips project_cost_structure_canvas_tooltips" title="Quels sont les coûts de votre business model (activités, ressources, distribution, etc.) ?
Comment sont-ils structurés (fixes, variables) ?
Comment pouvez-vous les réduire (économies d'échelle, de gamme) ?">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectCostStructureCanvas">Structure des coûts?</label>
										<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_cost_structure_canvas;
											$editor_id = 'project_cost_structure_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_cost_structure_canvas view-model-content"><?php if ($bopp->project_cost_structure_canvas) { echo $bopp->project_cost_structure_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>

									<?php if($can_modify){ ?> <span class="tooltips project_source_of_income_canvas_tooltips" title="Quelles sont vos sources de revenus possibles ?
Pourquoi vos clients sont-ils prêts à payer ?
Comment payent-ils votre offre (ventes uniques, location, abonnement, forfait, à l'usage, etc.) ?">?</span> <?php } ?>
									<div class="controls-wp">
										<label class="control-label-wp" for="projectSourceOfIncomeCanvas">Sources de revenu?</label>
										<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_source_of_income_canvas;
											$editor_id = 'project_source_of_income_canvas';
											wp_editor( $content, $editor_id, $settings_textarea );
											?>
											</span>
										<?php } ?>
										<span class="project_source_of_income_canvas view-model-content"><?php if ($bopp->project_source_of_income_canvas) { echo $bopp->project_source_of_income_canvas;} else { echo "&nbsp"; } ?></span>	
									</div>
								</div>
							
									<!-- div -->
									<?php if($can_modify){ ?> <span class="tooltips project_financial_board_tooltips" title="Pourquoi investir dans votre projet ? Cela donne-t-il droit à une réduction IR / ISF (investissement en capital seulement) ?

A quoi va servir le financement, exactement ?
Intégrez sous forme d'image des tableaux financiers : liste détaillée des dépenses permises par la collecte et compte de résultat prévisionnel. Si besoin, explicitez en quelques mots ces tableaux financiers. Ajouter quelques informations sur votre prévisionnel global, votre capital, vos apports personnels...">?</span> <?php } ?>
									<div class="control-group">
										<label class="control-label-wp edit-model-field" for="projectFinancialBoard">Tableau financier</label>
										<div class="controls-wp">
											<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                             
											<?php 
											$content = $bopp->project_financial_board;
											$editor_id = 'project_financial_board';
											wp_editor( $content, $editor_id, $settings_editor );
											?>
											</span>
											<?php } ?>
											<span class="project_financial_board view-model-content"><?php if ($bopp->project_financial_board) { echo $bopp->project_financial_board;} else { echo "&nbsp"; } ?></span>	
										</div>
									</div>

									<!-- div -->
									<?php if($can_modify){ ?> <span class="tooltips project_perspectives_tooltips" title="Quelles perspectives de développement offre votre projet ?">?</span> <?php } ?>
									<div class="control-group">
										<label class="control-label-wp edit-model-field" for="projectPerspectives">Perspectives</label>
										<div class="controls-wp">
											<?php if($can_modify){ ?>
											<span class="edit-input edit-model-field">                     
											<?php 
											$content = $bopp->project_perspectives;
											$editor_id = 'project_perspectives';
											wp_editor( $content, $editor_id, $settings_editor );
											?>
											</span>
											<?php } ?>
											<span class="project_perspectives view-model-content"><?php if ($bopp->project_perspectives) { echo $bopp->project_perspectives;} else { echo "&nbsp"; } ?></span>	
										</div>
									</div>
									</div>
								</div>
							</div>
		

			
		<?php endif; ?>

		<!-- ------------------------------------------
		-----------  Qui porte le projet ?  -----------
		------------------------------------------- -->

		<div class="projects-desc-item">
			<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>porteur.png" data-content="porteur"/>
			<img class="vertical-align-middle grey-triangle"src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
			<div id="project-content-porteur" class="projects-desc-content">
				<?php if($can_modify){ ?>
				<div class="edit_members">
					<a href="#" data-action="edit_members">Editer</a>
					<span class="cancel_save">
					    <a href="#" data-action="cancel_members">Annuler</a> 
					    <a href="#" data-campaign="<?= $campaign_id ?>" data-action="save_members">Enregistrer</a> 
					</span>
				</div>
				<?php } ?>

				<h2>Qui porte le projet ?</h2>

				<div class="control-group">
					<?php if($can_modify){ ?> <span class="tooltips project_other_information_tooltips" title="Expliquez les origines de l'idée et du projet.
Êtes-vous accompagné(e)(s) dans votre projet par des structures spécialisées ? Si oui lesquelles, et comment ?
Votre projet a-t-il reçu des prix ou distinctions ? Si oui lesquels ?">?</span> <?php } ?>
					<label class="control-label-wp edit-members-field" for="projectOtherInformation"></label>
					<div class="controls-wp">
					<?php if($can_modify){ ?>
						<span class="edit-input edit-members-field">                     
						<?php 
						$content = $bopp->project_other_information;
						$editor_id = 'project_other_information';
						wp_editor( $content, $editor_id, $settings_editor );
						?>
						</span>
						<?php } ?>
						<span class="project_other_information view-members-content"><?php if ($bopp->project_other_information) { echo $bopp->project_other_information;} else { echo "&nbsp"; } ?></span>	
					</div>
				</div>
			</div>
		</div>	
	</div>
</div>




<?php
$cache_result=ob_get_contents();
$WDG_cache_plugin->set_cache('project-'.$campaign_id.'-content-second',$cache_result);
ob_end_clean();
}
echo $cache_result;
?>
