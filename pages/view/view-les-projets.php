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
										<a class="button-slide" href="<?php echo $project_item[ 'link' ]; ?>"><?php _e( 'project.DISCOVER', 'yproject'); ?></a>
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
	<p><?php _e( 'common.WEDOGOOD_IS', 'yproject' ); ?></p>
	<p>
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-balloon.png" alt="air balloon" />
		<span>
			<span><?php echo number_format( $stats_list[ 'count_amount' ], 0, '', ' ' ); ?> &euro;</span><br>
			<?php _e( 'common.RAISED.P', 'yproject' ); ?>
		</span>
	</p>
	<p>
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-people.png" alt="people" />
		<span>
			<span><?php echo number_format( $stats_list[ 'count_people' ], 0, '', ' ' ); ?></span><br>
			<?php _e( 'common.INVESTORS', 'yproject' ); ?>
		</span>
	</p>
	<p>
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="money" />
		<span>
			<span><?php echo number_format( $stats_list[ 'royaltying_projects' ], 0, '', ' ' ); ?></span><br>
			<?php _e( 'common.COMPANIES_ROYALTIZE', 'yproject' ); ?>
		</span>
	</p>
</div>
<div id="wdg-project-definition"><?php _e( 'common.ROYALTIZE_DEFINITION', 'yproject' ); ?></div>
				
<?php
/******************************************************************************/
// FIN STATS PROJECTS
/******************************************************************************/
?>
			
				<div class="clear"></div>
				
			</div>
			
		</div>
		
	</header>
	
	<h2 class="standard only-inf997">/ <?php _e( 'projects.THE_PROJECTS', 'yproject' ) ?> /</h2>
	
		
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
			<span><?php _e( 'projects.FILTERS', 'yproject' ); ?> <span class="only-inf997 inline"><?php _e( 'projects.PROJECTS', 'yproject' ); ?></span></span>
            <div class="project-filter-container">
                <select id="project-filter-impact" class="project-filter-select">
                    <option id="all-impacts" value="all" selected="selected"><?php _e( 'projects.ALL_IMPACTS', 'yproject' ); ?></option>
                    <?php foreach ( $impacts_list as $impact ): ?>							
						<option id="<?php echo $impact->slug; ?>" value="<?php echo $impact->slug; ?>" ><?php echo $impact->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="project-filter-container">
                <select id="project-filter-location" class="project-filter-select">
                    <option value="all" selected="selected"><?php _e( 'projects.ALL_LOCALIZATIONS', 'yproject' ); ?></option>
                    <?php foreach ( $region_list as $region => $dpt_list ): ?>
						<option value="<?php echo implode(',', $dpt_list); ?>"><?php echo $region; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="project-filter-container">
                <select id="project-filter-step" class="project-filter-select">
                    <option value="all" selected="selected"><?php _e( 'projects.ALL_STEPS', 'yproject' ); ?></option>
                    <?php foreach ( $status_list as $status_key => $status_label ): ?>
						<option value="<?php echo $status_key; ?>"><?php echo $status_label; ?></option>
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
	    
	</div><!-- .padder -->
		
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

<?php $currentprojects_html = $page_controler->get_currentprojects_html(); ?>

<?php if ( !$currentprojects_html ): ?>

	<?php ob_start(); ?>
		<div class="padder projects-current">
			<div class="align-center">
				<img id="loader-project-list" class="hidden" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" />
			</div>

			<section class="wdg-component-projects-preview">
				<div class="project-slider">
					<div class="block-projects">

					<?php
					global $project_id;
					$currentprojects_list = $page_controler->get_currentprojects_list();
					$project_list_funding = $currentprojects_list[ 'funding' ];
					if ( count( $project_list_funding ) > 0 ) {
						foreach ( $project_list_funding as $project_post ) {
							$project_id = $project_post->ID;
							locate_template( array( "projects/preview.php" ), true, false );
						}
					}
					$project_list_vote = $currentprojects_list[ 'vote' ];
					if ( count( $project_list_vote ) > 0 ) {
						foreach ( $project_list_vote as $project_post ) {
							$project_id = $project_post->ID;
							locate_template( array( "projects/preview.php" ), true, false );
						}
					}
					?>

					</div>
				</div>
			</section>
		</div>
				
	<?php
	$cache_currentprojects = ob_get_contents();
	$page_controler->set_currentprojects_html( $cache_currentprojects );
	ob_end_clean();
	?>
					
<?php endif; ?>

<?php echo $page_controler->get_currentprojects_html(); ?>
		
<?php
/******************************************************************************/
// FIN CURRENT PROJECTS
/******************************************************************************/
?>


<?php
/******************************************************************************/
// POSITIVE SAVINGS PROJECTS
/******************************************************************************/
?>
					
<?php $positive_savings_projects_html = $page_controler->get_positive_savings_projects_html(); ?>

<?php if ( !$positive_savings_projects_html ): ?>

<?php ob_start(); ?>

	<?php $project_list_positive_savings = $page_controler->get_positive_savings_projects_list(); ?>
	<?php if ( count( $project_list_positive_savings ) > 0 ): ?>
	<div class="projects-positive-savings">
		<div class="padder">
			<section class="wdg-component-projects-preview">
				<h2 class="standard">/ <?php _e( 'projects.POSITIVE_SAVINGS', 'yproject' ); ?> /</h2>
				<div class="projects-title-precisions">
					<?php _e( 'projects.POSITIVE_SAVINGS_DESCRIPTION', 'yproject' ); ?><br>
					<a href="<?php echo home_url( '/epargne-positive/' ); ?>"><?php _e( 'projects.POSITIVE_SAVINGS_KNOW_MORE', 'yproject' ); ?></a>
				</div>


				<div class="project-slider">
					<div class="block-projects">

						<?php
						global $project_id;
						foreach ( $project_list_positive_savings as $project_post ) {
							$project_id = $project_post->ID;
							locate_template( array( "projects/preview.php" ), true, false );
						}
						?>
						
					</div>
				</div>
			</section>
		</div>
	</div>
	<?php endif; ?>
	
	<?php
	$currentprojects_list = $page_controler->get_currentprojects_list();
	$project_list_funding_after = $currentprojects_list[ 'funding_after' ];
	?>
	<?php if ( count( $project_list_funding_after ) > 0 ): ?>
	<div class="projects-after-end-date">
		<div class="padder">
			<section class="wdg-component-projects-preview">
				<h2 class="standard">/ <?php _e( 'projects.CLOSING_CAMPAIGNS', 'yproject' ); ?> /</h2>
				<div class="projects-title-precisions"><?php _e( 'projects.CLOSING_CAMPAIGNS_DESCRIPTION', 'yproject' ); ?></div>

				<div class="project-slider">
					<div class="block-projects">

					<?php
					global $project_id;
					foreach ( $project_list_funding_after as $project_post ) {
						$project_id = $project_post->ID;
						locate_template( array( "projects/preview.php" ), true, false );
					}
					?>
					</div>
				</div>
			</section>
		</div>
	</div>
	<?php endif; ?>
	
<?php
$cache_positive_savings_projects = ob_get_contents();
$page_controler->set_positive_savings_projects_html( $cache_positive_savings_projects );
ob_end_clean();
?>

<?php endif; ?>

<?php echo $page_controler->get_positive_savings_projects_html(); ?>

<?php
/******************************************************************************/
// FIN POSITIVE SAVINGS PROJECTS
/******************************************************************************/
?>


<?php
/******************************************************************************/
// FUNDED PROJECTS
/******************************************************************************/
?>
	<div>
		<div class="padder projects-funded">

			<section class="wdg-component-projects-preview">
				<h2 class="standard">/ <?php _e( 'projects.FUNDED_CAMPAIGNS', 'yproject' ) ?> /</h2>

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
				<button class="button big more-content"><?php _e( 'projects.FUNDED_CAMPAIGNS_SEE_MORE', 'yproject' ); ?></button>
			</div>

		</div><!-- .padder -->
	</div>

<?php
/******************************************************************************/
// FIN FUNDED PROJECTS
/******************************************************************************/
?>
	
</div><!-- #content -->