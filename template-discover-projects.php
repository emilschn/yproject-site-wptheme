<?php
/**
 * Template Name: Découvrir des projets
 *
 */
?>

<?php get_header(); ?>

<?php global $WDG_cache_plugin; ?>

<div id="content">
	<div class="padder center">
		<?php 
		//PROJETS EN COURS
		$cache_result_collected = $WDG_cache_plugin->get_cache('home-collecte-projects');
		if ($cache_result_collected === FALSE) {
			ob_start();
			$nb_collecte_projects = count(query_projects_collecte()); 
			if ($nb_collecte_projects > 0) { ?>
				<div class="part-title-separator" >
					<span class="part-title">En cours de financement</span>
				</div>

				<?php require('projects/home-large.php'); ?>
			<?php } ?>
	    
			<?php 
			$cache_result_collected = ob_get_contents();
			$WDG_cache_plugin->set_cache('home-collecte-projects', $cache_result_collected, 2*60*60);
			ob_end_clean();
		}
		echo $cache_result_collected; 

		//PROJETS A VENIR
		$cache_result_to_come = $WDG_cache_plugin->get_cache('home-small-projects');
		if ($cache_result_to_come === FALSE) {
			ob_start();
			require('projects/home-small.php');
			$is_right_project = TRUE;
			$preview_projects = query_projects_preview();
			$vote_projects = query_projects_vote();
			$nb_vote_projects = count($vote_projects);
			$nb_preview_projects = count($preview_projects);
			$nb_total_projects = $nb_vote_projects + $nb_preview_projects;
			if ($nb_total_projects > 0) { ?>
				<div class="part-title-separator">
					<span class="part-title">Prochainement</span>
				</div>
	    
				<?php
				$nb_printed_post = 0;
				$is_last_post = FALSE;
				if ($nb_vote_projects > 0) {
					foreach ($vote_projects as $vote_post) {
						$nb_printed_post++;
						if (($nb_printed_post == $nb_total_projects) && ($nb_total_projects % 2 != 0)) {
							$is_right_project = FALSE;
							$is_last_post = TRUE;
						}
						$is_right_project = print_vote_post($vote_post, $is_right_project);
					}
					if ($is_last_post) print_empty_post();
				}
				if ($nb_preview_projects > 0) {
					foreach ($preview_projects as $preview_post) {
						$nb_printed_post++;
						if (($nb_printed_post == $nb_total_projects) && ($nb_total_projects % 2 != 0)) {
						    $is_right_project = FALSE;
						    $is_last_post = TRUE;
						}
						$is_right_project = print_preview_post($preview_post, $is_right_project);
					}
					if ($is_last_post) print_empty_post();
				}
			}
			$cache_result_to_come = ob_get_contents();
			$WDG_cache_plugin->set_cache('home-small-projects', $cache_result_to_come, 2*60*60);
			ob_end_clean();
		}
		echo $cache_result_to_come;

		//PROJETS REUSSIS
		$cache_result_success = $WDG_cache_plugin->get_cache('home-funded-projects');
		if ($cache_result_success === FALSE) {
			ob_start(); ?>
				<div class="part-title-separator">
					<?php
					$nb_funded_projects = count(query_projects_funded()); 
					if ($nb_funded_projects > 0) { ?>
						<span class="part-title">D&eacute;j&agrave; financ&eacute;</span>	
					<?php } ?>
				</div>
				<?php if ($nb_funded_projects > 0) { ?>
					<?php require('projects/home-large.php'); ?>
				<?php } ?>
	    
			<?php 
			$cache_result = ob_get_contents();
			$WDG_cache_plugin->set_cache('home-funded-projects', $cache_result, 2*60*60);
			ob_end_clean();
		}
		echo $cache_result;
	?>
	</div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>