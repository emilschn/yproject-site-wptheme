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
		<ul id="project-list-menu" class="only_on_mobile">
			<li><a href="#" class="selected" data-status="collecte">En cours</a></li>
			<li><a href="#" data-status="vote">En vote</a></li>
			<li><a href="#" data-status="preview">Avant-premi&egrave;re</a></li>
			<li><a href="#" data-status="funded">Termin&eacute;s</a></li>
		</ul>
<?php
//*******************
//CACHE PROJECTS CURRENT
$cache_projects_current = $WDG_cache_plugin->get_cache('projects-current', 2);
if ($cache_projects_current !== FALSE) { echo $cache_projects_current; }
else {
	ob_start();
?>
		<?php 
		//PROJETS EN COURS
		$nb_collecte_projects = count(query_projects_collecte()); 
		if ($nb_collecte_projects > 0) { ?>
			<div class="part-title-separator mobile_hidden">
				<span class="part-title">En cours de financement</span>
			</div>

			<?php require('projects/home-large.php'); ?>
		<?php } ?>
<?php
	$cache_projects_current = ob_get_contents();
	$WDG_cache_plugin->set_cache('projects-current', $cache_projects_current, 60*10, 2);
	ob_end_clean();
	echo $cache_projects_current;
}
//FIN CACHE PROJECTS CURRENT
//*******************
?>
	    
<?php
//*******************
//CACHE PROJECTS OTHERS
$cache_projects_others = $WDG_cache_plugin->get_cache('projects-others', 2);
if ($cache_projects_others !== FALSE) { echo $cache_projects_others; }
else {
	ob_start();
?>
		<?php 
		//PROJETS A VENIR
		require('projects/home-small.php');
		$is_right_project = TRUE;
		$preview_projects = query_projects_preview();
		$vote_projects = query_projects_vote();
		$nb_vote_projects = count($vote_projects);
		$nb_preview_projects = count($preview_projects);
		$nb_total_projects = $nb_vote_projects + $nb_preview_projects;
		if ($nb_total_projects > 0) { ?>
			<div class="part-title-separator mobile_hidden">
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

		//PROJETS REUSSIS
		?>
		<div class="part-title-separator mobile_hidden">
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
		//PROJETS ECHOUES
		?>
		<div class="part-title-separator mobile_hidden">
			<?php
			$nb_archived_projects = count(query_projects_archive()); 
			if ($nb_archived_projects > 0) { ?>
				<span class="part-title">Projets termin&eacute;s</span>	
			<?php } ?>
		</div>
		<?php if ($nb_archived_projects > 0) { ?>
			<?php require('projects/home-large.php'); ?>
		<?php } ?>
	    
<?php
	$cache_projects_others = ob_get_contents();
	$WDG_cache_plugin->set_cache('projects-others', $cache_projects_others, 60*10, 2);
	ob_end_clean();
	echo $cache_projects_others;
}
//FIN CACHE PROJECTS CURRENT
//*******************
?>
	    
	</div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>