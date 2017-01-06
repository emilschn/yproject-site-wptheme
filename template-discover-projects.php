<?php
/**
 * Template Name: Découvrir des projets
 *
 */
?>

<?php get_header(); ?>

<?php global $WDG_cache_plugin, $stylesheet_directory_uri; ?>

<div id="content">
		
	<header>
            <div class="header-container">
		<div class="padder">
			<div class="wdg-component-slider project-list-slider left">
				<div class="slider-container">
					<div class="slider-choice">
						<span class="num-slide active-slide" id="span-1">1</span>
						<span class="num-slide inactive-slide" id="span-2">2</span>  
						<span class="num-slide inactive-slide" id="span-3">3</span>
					</div>
					
					<div id="slider">
						<?php
						$i = 0;
						$project_list_slider = ATCF_Campaign::get_list_most_recent( 3 );
						?>
						<?php foreach ($project_list_slider as $project_id): ?>
							<?php
							$i++;
							$campaign = atcf_get_campaign( $project_id );
							$img = $campaign->get_home_picture_src();
							?>
							<div class="slider-item slide-1button" id="slide-<?php echo $i?>" style="<?php if ($i > 1){ ?>display: none;<?php } else { ?>left: 0px;<?php } ?>">
								<img class="slide" id="img-slide-<?php echo $i?>" src="<?php echo $img; ?>"/> 

								<div class="message-banner">
									<p class="screen-message"><?php echo $campaign->data->post_title; ?></p>
								</div>
								<div id="button-container">
									<a class="button-slide" href="<?php echo get_permalink($project_id); ?>"><?php _e("D&eacute;couvrir le projet", "yproject") ?></a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div> <!-- .wdg-component-slider -->

			<?php
//*******************
//CACHE STATS PROJETS
$cache_project_stats = $WDG_cache_plugin->get_cache('list-projects-stats', 1);
if ($cache_project_stats !== FALSE) { echo $cache_project_stats; }
else {
	ob_start();
			$project_list_funded = ATCF_Campaign::get_list_funded( 28 );
			$count_amount = 0;
			$count_people = 0;
			$count_projects = 0;
			foreach ( $project_list_funded as $project_post ) {
				$count_projects++;
				$campaign = atcf_get_campaign( $project_post->ID );
				$count_people += $campaign->backers_count();
				$count_amount += $campaign->current_amount( false );
			}
			?>
			<div id="wdg-project-stats" class="right">
				<p><?php _e( "WE DO GOOD c'est :" ); ?></p>
				<p>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="money" />
					<span>
						<span><?php echo number_format( $count_amount, 0, '', ' ' ); ?> &euro;</span>
						<?php _e( "lev&eacute;s" ); ?>
					</span>
				</p>
				<p>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-people.png" alt="people" />
					<span>
						<span><?php echo number_format( $count_people, 0, '', ' ' ); ?></span>
						<?php _e( "investisseurs" ); ?>
					</span>
				</p>
				<p>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-arrows.png" alt="arrows" />
					<span>
						<span><?php echo count($project_list_funded); ?></span>
						<?php _e( "projets propuls&eacute;s" ); ?>
					</span>
				</p>
			</div>
<?php
	$cache_project_stats = ob_get_contents();
	$WDG_cache_plugin->set_cache('list-projects-stats', $cache_project_stats, 60*60*24, 1);  //MAJ 24h
	ob_end_clean();
	echo $cache_project_stats;
}
//FIN CACHE STATS PROJETS
//*******************
?>
			
			<div class="clear"></div>
		</div>
            </div>
	</header>
	
	
	<h2 class="standard only-inf997">/ <?php _e("les projets", "yproject") ?> /</h2>
	
		
	<div class="padder projects-current">
<?php
//*******************
//CACHE PROJECT FILTERS
$cache_project_filters = $WDG_cache_plugin->get_cache('list-project-filters', 1);
if ($cache_project_filters !== FALSE) { echo $cache_project_filters; }
else {
	ob_start();
?>
		<nav id="project-filter">
			<span><?php _e( "Filtres", 'yproject' ); ?> <span class="only-inf997 inline"><?php _e( "projets", 'yproject' ); ?></span></span>
            <div class="project-filter-container">
                <select id="project-filter-impact" class="project-filter-select">
                    <option value="all" selected="selected"><?php _e( "Tous les impacts", 'yproject' ); ?></option>
                    <?php
                    $terms_category = get_terms('download_category', array('slug' => 'categories', 'hide_empty' => false));
                    $term_category_id = $terms_category[0]->term_id;
                    $impacts_list = get_terms( 'download_category', array(
                        'child_of' => $term_category_id,
                        'hierarchical' => false,
                        'hide_empty' => false
                    ) );
                    foreach ( $impacts_list as $impact ): ?>
                                    <option value="<?php echo $impact->slug; ?>"><?php echo $impact->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="project-filter-container">
                <select id="project-filter-location" class="project-filter-select">
                    <option value="all" selected="selected"><?php _e( "Toutes les r&eacute;gions", 'yproject' ); ?></option>
                    <?php $region_list = atcf_get_regions(); ?>
                    <?php foreach ( $region_list as $region => $dpt_list ): ?>
                    <option value="<?php echo implode($dpt_list, ','); ?>"><?php echo $region ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="project-filter-container">
                <select id="project-filter-step" class="project-filter-select">
                    <option value="all" selected="selected"><?php _e( "Toutes les &eacute;tapes de campagne", 'yproject' ); ?></option>
                    <option value="vote"><?php _e( "En vote", 'yproject' ); ?></option>
                    <option value="collecte"><?php _e( "En financement", 'yproject' ); ?></option>
					<option value="funded"><?php _e( "Financ&eacute;", 'yproject' ); ?></option>
                </select>
            </div>
            <div class="project-filter-container">
                <select id="project-filter-activity" class="project-filter-select">
                    <option value="all" selected="selected"><?php _e( "Tous les secteurs d'activit&eacute;", 'yproject' ); ?></option>
                    <?php
                    $terms_activity = get_terms('download_category', array('slug' => 'activities', 'hide_empty' => false));
                    $term_activity_id = $terms_activity[0]->term_id;
                    $activities_list = get_terms( 'download_category', array(
                        'child_of' => $term_activity_id,
                        'hierarchical' => false,
                        'hide_empty' => false
                    ) );
                    foreach ( $activities_list as $activity ): ?>
                    <option value="<?php echo $activity->slug; ?>"><?php echo $activity->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
		</nav>
<?php
	$cache_project_filters = ob_get_contents();
	$WDG_cache_plugin->set_cache('list-project-filters', $cache_project_filters, 60*60*24, 1);  //MAJ 24h
	ob_end_clean();
	echo $cache_project_filters;
}
//FIN CACHE PROJECT FILTERS
//*******************
?>
		
		<section class="wdg-component-projects-preview">
			<div class="project-slider">
				<div class="block-projects">
<?php
//*******************
//CACHE PROJECT CURRENT
$cache_project_current = $WDG_cache_plugin->get_cache('list-project-current', 1);
if ($cache_project_current !== FALSE) { echo $cache_project_current; }
else {
	ob_start();
?>
				<?php
				$project_list_funding = ATCF_Campaign::get_list_funding( );
				$project_list_vote = ATCF_Campaign::get_list_vote( );
				foreach ( $project_list_funding as $project_post ) {
					$project_id = $project_post->ID;
					require('projects/preview.php');
				}
				foreach ( $project_list_vote as $project_post ) {
					$project_id = $project_post->ID;
					require('projects/preview.php');
				}
				?>
<?php
	$cache_project_current = ob_get_contents();
	$WDG_cache_plugin->set_cache('list-project-current', $cache_project_current, 60*2, 1);  //MAJ 2min
	ob_end_clean();
	echo $cache_project_current;
}
//FIN CACHE PROJECT FILTERS
//*******************
?>
				</div>
			</div>
		</section>
	    
	</div><!-- .padder -->
		
	<div>
		<div class="padder projects-funded">

			<section class="wdg-component-projects-preview">
				<h2 class="standard">/ <?php _e("projets financ&eacute;s", "yproject") ?> /</h2>

				<div class="project-slider">
					<div class="block-projects">
<?php		
//*******************
//CACHE STATS PROJETS
$cache_project_funded = $WDG_cache_plugin->get_cache('list-projects-funded', 1);
if ($cache_project_funded !== FALSE) { echo $cache_project_funded; }
else {
	ob_start();
?>
					<?php
					$count = 0;
					$project_list_funded = ATCF_Campaign::get_list_funded( 28 );
					foreach ( $project_list_funded as $project_post ) {
						$count++;
						$project_id = $project_post->ID;
						require('projects/preview.php');
					}
					?>
<?php
	$cache_project_funded = ob_get_contents();
	$WDG_cache_plugin->set_cache('list-projects-funded', $cache_project_funded, 60*60*2, 1);  //MAJ 2h
	ob_end_clean();
	echo $cache_project_funded;
}
//FIN CACHE STATS PROJETS
//*******************
?>
					</div>
				</div>
			</section>
			
			<div class="align-center">
				<button class="button big more-content"><?php _e( "Voir plus de projets", 'yproject' ); ?></button>
			</div>

		</div><!-- .padder -->
	</div>
	
</div><!-- #content -->
	
<?php get_footer(); ?>