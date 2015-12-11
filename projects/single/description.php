<?php
global $campaign, $stylesheet_directory_uri;
$campaign_status = $campaign->campaign_status();
$file_complement = '';
if (!empty($client_context)) { $file_complement .= '-' . $client_context; }
?>

<div class="project-description center">
	<div class="project-description-title separator-title">
		<span> 
			<?php _e('Pr&eacute;sentation', 'yproject'); ?>
		</span>
	</div>
    
	<div class="project-description-item" data-content="description">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/projet<?php echo $file_complement; ?>.png" alt="project" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-description" class="projects-desc-content">
			<h2><?php _e('Pitch', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>

	<div class="project-description-item" data-content="societal_challenge">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/sociale<?php echo $file_complement; ?>.png" alt="impacts" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle" />
		<div id="project-content-societal_challenge" class="projects-desc-content">
			<h2><?php _e('Impacts positifs', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$societal_challenge = html_entity_decode($campaign->societal_challenge()); 
				echo apply_filters('the_content', $societal_challenge);
				?>
			</div>
		</div>
	</div>
	
	<?php if ($campaign_status != 'preview'): ?>
	<div class="project-description-item" data-content="added_value">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/economie<?php echo $file_complement; ?>.png" alt="strategy" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-added_value" class="projects-desc-content">
			<h2><?php _e('Strat&eacute;gie', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$added_value = html_entity_decode($campaign->added_value()); 
				echo apply_filters('the_content', $added_value);
				?>
			</div>
		</div>
	</div>
	
	<div class="project-description-item" data-content="economic_model">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/model<?php echo $file_complement; ?>.png" alt="model" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-economic_model" class="projects-desc-content">
			<h2><?php _e('Donn&eacute;es financi&egrave;res', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$economic_model = html_entity_decode($campaign->economic_model()); 
				echo apply_filters('the_content', $economic_model);
				?>
			</div>
		</div>
	</div>
	<?php endif; ?>
    
	<div class="project-description-item" data-content="implementation">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/porteur<?php echo $file_complement; ?>.png" alt="team" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-implementation" class="projects-desc-content">
			<h2><?php _e('&Eacute;quipe', 'yproject'); ?></h2>
			<div class="zone-content">
				<?php 
				$implementation = html_entity_decode($campaign->implementation()); 
				echo apply_filters('the_content', $implementation);
				?>
			</div>
		</div>
	</div>
    
	<?php if ($campaign_status != 'preview'): ?>
	<div class="project-description-item" data-content="statistics">
		<img class="project-content-icon vertical-align-middle" src="<?php echo $stylesheet_directory_uri; ?>/images/porteur<?php echo $file_complement; ?>.png" alt="stats" />
		<img class="vertical-align-middle grey-triangle" src="<?php echo $stylesheet_directory_uri; ?>/images/triangle_gris_projet.png" alt="grey triangle"/>
		<div id="project-content-implementation" class="projects-desc-content">
			<h2><?php _e('Statistiques', 'yproject'); ?></h2>
			<div class="zone-content">
				TODO
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>