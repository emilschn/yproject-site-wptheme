<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


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
							$project_list_slider = $page_controler->get_slider();
							$i = 0;
							?>
							<?php foreach ( $project_list_slider as $project_item ): ?>
								<?php $i++; ?>
								<div class="slider-item slide-1button" id="slide-<?php echo $i?>" style="<?php if ($i > 1){ ?>display: none;<?php } else { ?>left: 0px;<?php } ?>">
									<img class="slide" id="img-slide-<?php echo $i?>" src="<?php echo $project_item[ 'img' ]; ?>"/> 

									<div class="message-banner">
										<p class="screen-message"><?php echo $project_item[ 'title' ]; ?></p>
									</div>
									<div id="button-container">
										<a class="button-slide" href="<?php echo $project_item[ 'link' ]; ?>"><?php _e("D&eacute;couvrir le projet", "yproject") ?></a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div> <!-- .wdg-component-slider -->
				
				

<?php
/******************************************************************************/
// STATS PROJECTS
/******************************************************************************/
?>
		
<?php $stats_list = $page_controler->get_stats_list(); ?>
				
				
				<div id="wdg-project-stats" class="right">
					<p><?php _e( "WE DO GOOD c'est :" ); ?></p>
					<p>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="air balloon" />
						<span>
							<span><?php echo number_format( $stats_list[ 'count_amount' ], 0, '', ' ' ); ?> &euro;</span><br />
							<?php _e( "lev&eacute;s", 'yproject' ); ?>
						</span>
					</p>
					<p>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-people.png" alt="people" />
						<span>
							<span><?php echo number_format( $stats_list[ 'count_people' ], 0, '', ' ' ); ?></span><br />
							<?php _e( "investisseurs", 'yproject' ); ?>
						</span>
					</p>
					<p>
						<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="money" />
						<span>
							<span><?php echo number_format( $stats_list[ 'count_roi' ], 0, '', ' ' ); ?> &euro;</span><br />
							<?php _e( "de royalties vers&eacute;s", 'yproject' ); ?>
						</span>
					</p>
				</div>
				
<?php
/******************************************************************************/
// FIN STATS PROJECTS
/******************************************************************************/
?>
			
				<div class="clear"></div>
				
			</div>
			
		</div>
		
	</header>
	
	<h2 class="standard only-inf997">/ <?php _e("les projets", "yproject") ?> /</h2>
	
		
<?php
/******************************************************************************/
// FILTERS
/******************************************************************************/
?>
	<div class="padder projects-current">
		
<?php $filters_html = $page_controler->get_filters_html(); ?>

<?php if ( !$filters_html ): ?>
				
	<?php
	$filters_list = $page_controler->get_filters_list();
	$impacts_list = $filters_list[ 'impacts' ];
	$region_list = $filters_list[ 'regions' ];
	$status_list = $filters_list[ 'status' ];
	$activities_list = $filters_list[ 'activities' ];
	?>

	<?php ob_start(); ?>
		
		<nav id="project-filter">
			<span><?php _e( "Filtres", 'yproject' ); ?> <span class="only-inf997 inline"><?php _e( "projets", 'yproject' ); ?></span></span>
            <div class="project-filter-container">
                <select id="project-filter-impact" class="project-filter-select">
                    <option id="all-impacts" value="all" selected="selected"><?php _e( "Tous les impacts", 'yproject' ); ?></option>
                    <?php foreach ( $impacts_list as $impact ): ?>							
						<option id="<?php echo $impact->slug; ?>" value="<?php echo $impact->slug; ?>" ><?php echo $impact->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="project-filter-container">
                <select id="project-filter-location" class="project-filter-select">
                    <option value="all" selected="selected"><?php _e( "Toutes les r&eacute;gions", 'yproject' ); ?></option>
                    <?php foreach ( $region_list as $region => $dpt_list ): ?>
						<option value="<?php echo implode($dpt_list, ','); ?>"><?php echo $region; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="project-filter-container">
                <select id="project-filter-step" class="project-filter-select">
                    <option value="all" selected="selected"><?php _e( "Toutes les &eacute;tapes de campagne", 'yproject' ); ?></option>
                    <?php foreach ( $status_list as $status_key => $status_label ): ?>
						<option value="<?php echo $status_key; ?>"><?php echo $status_label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="project-filter-container">
                <select id="project-filter-activity" class="project-filter-select">
                    <option value="all"><?php _e( "Tous les types de projet", 'yproject' ); ?></option>
                    <?php foreach ( $activities_list as $activity ): ?>
						<option value="<?php echo $activity->slug; ?>" <?php selected( $activity->slug, 'entreprises' ); ?>><?php echo $activity->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
		</nav>
				
	<?php
	$cache_filters = ob_get_contents();
	$page_controler->set_filters_html( $cache_filters );
	ob_end_clean();
	?>
				
<?php endif; ?>

<?php echo $page_controler->get_filters_html(); ?>
		
<?php
/******************************************************************************/
// FIN FILTERS
/******************************************************************************/
?>



<?php
/******************************************************************************/
// CURRENT PROJECTS
/******************************************************************************/
?>
		<section class="wdg-component-projects-preview">
			<div class="project-slider">
				<div class="block-projects">
					
<?php $currentprojects_html = $page_controler->get_currentprojects_html(); ?>

<?php if ( !$currentprojects_html ): ?>

				<?php ob_start(); ?>
					
				<?php
				global $project_id;
				$currentprojects_list = $page_controler->get_currentprojects_list();
				$project_list_funding = $currentprojects_list[ 'funding' ];
				$project_list_vote = $currentprojects_list[ 'vote' ];
				foreach ( $project_list_funding as $project_post ) {
					$project_id = $project_post->ID;
					locate_template( array( "projects/preview.php" ), true, false );
				}
				foreach ( $project_list_vote as $project_post ) {
					$project_id = $project_post->ID;
					locate_template( array( "projects/preview.php" ), true, false );
				}
				?>
				
				<?php
				$cache_currentprojects = ob_get_contents();
				$page_controler->set_currentprojects_html( $cache_currentprojects );
				ob_end_clean();
				?>
					
<?php endif; ?>

<?php echo $page_controler->get_currentprojects_html(); ?>
				
				</div>
			</div>
		</section>
		
<?php
/******************************************************************************/
// FIN CURRENT PROJECTS
/******************************************************************************/
?>
	    
	</div><!-- .padder -->
		
	<div>
		<div class="padder projects-funded">

			<section class="wdg-component-projects-preview">
				<h2 class="standard">/ <?php _e("projets financ&eacute;s", "yproject") ?> /</h2>

				<div class="project-slider">
					<div class="block-projects">
					
<?php $fundedprojects_html = $page_controler->get_fundedprojects_html(); ?>

<?php if ( !$fundedprojects_html ): ?>

					<?php ob_start(); ?>
						
					<?php
					$index_project = 1;
					$index_cache = 1;
					global $project_id;
					$project_list_funded = $page_controler->get_fundedprojects_list();
					foreach ( $project_list_funded as $project_post ) {
						$project_id = $project_post->ID;
						locate_template( array( "projects/preview.php" ), true, false );
						if ( $index_project == 5 ) {
							$cache_fundedprojects = ob_get_contents();
							$page_controler->set_fundedprojects_html( $cache_fundedprojects, $index_cache );
							ob_end_clean();
							ob_start();
							$index_cache++;
							$index_project = 1;
						} else {
							$index_project++;
						}
					}
					if ( $index_project > 1 ) {
						$cache_fundedprojects = ob_get_contents();
						$page_controler->set_fundedprojects_html( $cache_fundedprojects, $index_cache );
						ob_end_clean();
					}
					?>
					
<?php endif; ?>

<?php echo $page_controler->get_fundedprojects_html(); ?>
						
					</div>
				</div>
			</section>
			
			<div class="align-center">
				<button class="button big more-content"><?php _e( "Voir plus de projets", 'yproject' ); ?></button>
			</div>

		</div><!-- .padder -->
	</div>
	
</div><!-- #content -->