<?php 
$cache_result=$WDG_cache_plugin->get_cache('project-'.$campaign_id.'-content-first');
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
		?>

		<div id="projects-top-desc">
			<div id="projects-left-desc" class="left">
				<div id="projects-summary"><?php echo html_entity_decode($campaign->summary()); ?></div>
				<?php 
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
					$video_element = wp_oembed_get($campaign->video(), array('width' => 580));
				}
				?>
				<div class="video-zone" <?php if ($img_src != '') { ?>style="background-image: url('<?php echo $img_src; ?>');"<?php } ?>>
					<?php echo $video_element; ?>
				</div>
			</div>
			<div id="projects-right-desc" class="right" >
				<div id="project-owner">
					<?php 
					$author_id=get_the_author_meta('ID');
					print_user_avatar($author_id);
					$author=get_user_meta($author_id);
					?>
					<div id="project-owner-desc">
						<?php

						echo '<p>'.$author['last_name'][0].' '.$author['first_name'][0].'</p>';
						echo '<p>@'.$author['nickname'][0].'</p>';
				//echo '<p>'.$author['user_postal_code'][0].' '.$author['user_city'][0].'</p>';
				//echo '<p>'.$author['user_mobile_phone'][0].'</p>';
						?>
					</div>
				</div>

				<div id="project-about">
					<p> A propos de<p>
						<p>  <?php echo get_the_title(); ?> </p>
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
		<h2 >En quoi consiste le projet ?</h2>
		<label class="control-label" for="projectCategory">Catégorie :</label>
		<div class="controls">
			<select id="projectCategory" name="projectCategory">
				<option value="" disabled selected>Selectionner</option>
				<option value="collaborative">Collaboratif</option>
				<option value="social">Social</option>
				<option value="environmental">Environnemental</option>
				<option value="economic">Economique</option>
			</select>
		</div>

		<label class="control-label" for="projectBusinessSector">Secteur d'activité</label>
		<div class="controls">
			<select id="projectBusinessSector" name="projectBusinessSector">
				<option value="" disabled selected>Selectionner</option>
				<option value="administration">Administration publique</option>
				<option value="agriculture">Agriculture et agroalimentaire</option>
				<option value="culture">Art / design / audiovisuel /culture</option>
				<option value="building">Bâtiment et travaux publics</option>
				<option value="">Commerce / artisanat / distribution</option>
				<option value="energy">Energie</option>
				<option value="learning">Enseignement et formation</option>
				<option value="">Fabrication et transformation de biens</option>
				<option value="">Finance et assurance,Gestion de l'eau des déchets et dépollution</option>
				<option value="">Hébergement et restauration</option>
				<option value="media">Médias</option>
				<option value="">Recherche et cabinets d'étude</option>
				<option value="">Services à la personnes / santé / action sociale</option>
				<option value="">Services et activités de conseil</option>
				<option value="">Sport / loisirs / tourisme / activités récréatives</option>
				<option value="telecom">Télécoms / informatique et Internet</option>
				<option value="transport">Transports et logistique</option>
				<option value="other_category">Autre</option>
			</select>
		</div>

		<a href="#" class="tooltip" title="Test Tooltip">?</a>
		<label class="control-label" for="projectFundingType">Mode de financement :</label>
		<div class="controls">
			<select id="projectFundingType" name="projectFundingType">
				<option value="" disabled selected>Selectionner</option>
				<option value="good_project">Good project</option>
				<option value="good_company">Good company</option>
			</select>
		</div>

		<label class="control-label" for="projectReturnOnInvestment">Retour sur investissement prévu</label>
		<div class="controls">
			<input type="text" class="edit-input" data-placeholder="" id="projectReturnOnInvestment" name="projectReturnOnInvestment">
		</div>

		<label class="control-label" for="projectInvestorBenefit">Avantage investisseur</label>
		<div class="controls">
			<input type="text" class="edit-input" data-placeholder="Avantage" id="projectInvestorBenefit" name="projectInvestorBenefit">
		</div>

		<div class="controls">
			<?php 
			$content = '';
			$editor_id = 'projectMarket';
			wp_editor( $content, $editor_id, $settings_editor );
			?>
		</div>
	</div>
</div>


<!-- -------------------------------------------------------
--------- Quelle est l'impact sociétal du projet ? ---------
-------------------------------------------------------- -->


<a id="utilite-societale"></a>
<div class="projects-desc-item">
	<img class="project-content-icon vertical-align-middle" src="<?php echo $images_folder;?>sociale.png" data-content="social"/>
	<img class="vertical-align-middle grey-triangle" src="<?php echo $images_folder;?>triangle_gris_projet.png"/>
	<div id="project-content-social" class="projects-desc-content">
		<h2 >Quelle est l'utilité sociétale du projet ?</h2>
		<div class="control-group">
			<label class="control-label" for="projectEconomyExcerpt">Economie</label>
			<div class="controls">
				<input type="text" class="edit-input" data-placeholder="x emplois, commerce équitable, ..." id="projectEconomyExcerpt" name="projectEconomyExcerpt">

			</div>
		</div>

		<!-- Text input-->
		<div class="control-group">
			<label class="control-label" for="projectSocialExcerpt">Social</label>
			<div class="controls">
				<input type="text" class="edit-input" placeholder="éducation, insertion, culture, ..." id="projectSocialExcerpt" name="projectSocialExcerpt">

			</div>
		</div>

		<!-- Text input-->
		<div class="control-group">
			<label class="control-label" for="projectEnvironmentExcerpt">Environnement</label>
			<div class="controls">
				<input type="text" class="edit-input" placeholder="recyclage, agriculture biologique, circuits courts, ..." id="projectEnvironmentExcerpt" name="projectEnvironmentExcerpt">
			</div>
		</div>

		<!-- div -->
		<div class="control-group">
			<label class="control-label" for="projectMission"></label>
			<div class="controls">                     
				<?php 
				$content = '';
				$editor_id = 'projectMission';
				wp_editor( $content, $editor_id, $settings_editor );
				?>
			</div>
		</div>

		<!-- div -->
		<div class="control-group">
			<label class="control-label" for="projectEconomy"></label>
			<div class="controls">                     
				<?php 
				$content = '';
				$editor_id = 'projectEconomy';
				wp_editor( $content, $editor_id, $settings_editor );
				?>
			</div>
		</div>

		<!-- div -->
		<div class="control-group">
			<label class="control-label" for="projectSocial"></label>
			<div class="controls">                     
				<?php 
				$content = '';
				$editor_id = 'projectSocial';
				wp_editor( $content, $editor_id, $settings_editor );
				?>
			</div>
		</div>

		<!-- div -->
		<div class="control-group">
			<label class="control-label" for="projectEnvironment"></label>
			<div class="controls">                     
				<?php 
				$content = '';
				$editor_id = 'projectEnvironment';
				wp_editor( $content, $editor_id, $settings_editor );
				?>
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
			<h2 >Quelle est l'opportunité économique du projet ?</h2>

				<div class="control-group">
					<label class="control-label" for="projectContextExcerpt">Contexte</label>
					<div class="controls">
						<input type="text" class="edit-input" placeholder="éducation, insertion, culture, ..." id="projectContextExcerpt" name="projectContextExcerpt">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="projectMarketExcerpt">Marchés</label>
					<div class="controls">
						<input type="text" class="edit-input" placeholder="éducation, insertion, culture, ..." id="projectMarketExcerpt" name="projectMarketExcerpt">
					</div>
				</div>

				<!-- div -->
				<div class="control-group">
					<label class="control-label" for="projectContext"></label>
					<div class="controls">    
						<?php 
						$content = '';
						$editor_id = 'projectContext';
						wp_editor( $content, $editor_id, $settings_editor );
						?>
					</div>
				</div>

				<!-- div -->
				<div class="control-group">
					<label class="control-label" for="projectMarket"></label>
					<div class="controls">                     
						<?php 
						$content = '';
						$editor_id = 'projectMarket';
						wp_editor( $content, $editor_id, $settings_editor );
						?>
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
			<h2 >Quel est le modèle économique du projet ?</h2>
			<div>
				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="projectWorthOffer">Proposition de valeur </label>
					<div class="controls">
						<input type="text" class="edit-input" placeholder="éducation, insertion, culture, ..." id="projectContextExcerpt" name="projectContextExcerpt">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="projectClientCollaborator">Clients et partenaires </label>
					<div class="controls">
						<input type="text" class="edit-input" placeholder="éducation, insertion, culture, ..." id="projectContextExcerpt" name="projectContextExcerpt">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="projectBusinessCore">Coeur de métier :</label>
					<div class="controls">
						<input type="text" class="edit-input" placeholder="éducation, insertion, culture, ..." id="projectContextExcerpt" name="projectContextExcerpt">
					</div>
				</div>

				<!-- Text input-->
				<div class="control-group">
					<label class="control-label" for="projectIncome">Revenus</label>
					<div class="controls">
						<input type="text" class="edit-input" placeholder="éducation, insertion, culture, ..." id="projectContextExcerpt" name="projectContextExcerpt">					</div>
					</div>

					<!-- Text input-->
					<div class="control-group">
						<label class="control-label" for="projectCost">Coûts</label>
						<div class="controls">
							<input type="text" class="edit-input" placeholder="éducation, insertion, culture, ..." id="projectContextExcerpt" name="projectContextExcerpt">					</div>
						</div>

						<div id="canvas">
							<div>
								<div>
									<label class="control-label" for="projectCollaboratorsCanvas">Partenaires</label>
									<textarea class="edit-input" placeholder=""  id="projectCollaboratorsCanvas" name="projectCollaboratorsCanvas"></textarea>
								</div>
								<div>
									<div>
										<label class="control-label" for="projectActivitiesCanvas">Activités</label>
										<textarea class="edit-input" placeholder=""  id="projectActivitiesCanvas" name="projectActivitiesCanvas"></textarea>
									</div>
									<div>
										<label class="control-label" for="projectRessourcesCanvas">Ressources</label>
										<textarea class="edit-input" placeholder=""  id="projectRessourcesCanvas" name="projectRessourcesCanvas"></textarea>
									</div>
								</div>
								<div>
									<label class="control-label" for="projectWorthOfferCanvas">Proposition de valeur</label>
									<textarea class="edit-input" placeholder=""  id="projectWorthOfferCanvas" name="projectWorthOfferCanvas"></textarea>
								</div>
								<div>
									<div>
										<label class="control-label" for="projectCustomersRelationsCanvas">Relation Clients</label>
										<textarea class="edit-input" placeholder=""  id="projectCustomersRelationsCanvas" name="projectCustomersRelationsCanvas"></textarea>
									</div>
									<div>
										<label class="control-label" for="projectChainDistributionsCanvas">Canaux de distribution</label>
										<textarea class="edit-input" placeholder=""  id="projectChainDistributionsCanvas" name="projectChainDistributionsCanvas"></textarea>
									</div>
								</div>
								<div>
									<label class="control-label" for="projectClientsCanvas">Clients</label>
									<textarea class="edit-input" placeholder=""  id="projectClientsCanvas" name="projectClientsCanvas"></textarea>
								</div>
								<div>
									<div>
										<label class="control-label" for="projectCostStructureCanvas">Structure des coûts</label>
										<textarea class="edit-input" placeholder=""  id="projectCostStructureCanvas" name="projectCostStructureCanvas"></textarea>
									</div>
									<div>
										<label class="control-label" for="projectSourceOfIncomeCanvas">Sources de revenu</label>
										<textarea class="edit-input" placeholder=""  id="projectSourceOfIncomeCanvas" name="projectSourceOfIncomeCanvas"></textarea>
									</div>
								</div>
							</div>

							<!-- div -->
							<div class="control-group">
								<label class="control-label" for="projectFinancialBoard"></label>
								<div class="controls">                     
									<?php 
									$content = '';
									$editor_id = 'projectFinancialBoard';
									wp_editor( $content, $editor_id, $settings_editor );
									?>
								</div>
							</div>

							<!-- div -->
							<div class="control-group">
								<label class="control-label" for="projectPerspectives"></label>
								<div class="controls">                     
									<?php 
									$content = '';
									$editor_id = 'projectPerspectives';
									wp_editor( $content, $editor_id, $settings_editor );
									?>
								</div>
							</div>
						</div>
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
		<h2>Qui porte le projet ?</h2>
		<div>
			<!-- div -->
			<div class="control-group">
				<label class="control-label" for="projectOtherInformation"></label>
				<div class="controls">                     
					<?php 
					$content = '';
					$editor_id = 'projectOtherInformation';
					wp_editor( $content, $editor_id, $settings_editor );
					?>
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
