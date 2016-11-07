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
		<div class="padder">
			<div class="wdg-component-slider project-list-slider">
				<div class="slider-container">
					<div class="slider-choice">
						<span class="num-slide active-slide" id="span-1">1</span>
						<span class="num-slide inactive-slide" id="span-2">2</span>  
						<span class="num-slide inactive-slide" id="span-3">3</span>
					</div>
					<?php $project_list_slider = ATCF_Campaign::get_list_most_recent( 3 ); ?>
					<div id="slider">
					</div>
				</div>
			</div> <!-- .wdg-component-slider -->

			<div>
				<p><?php _e( "WE DO GOOD c'est :" ); ?></p>
				<p>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-money.png" alt="money" />
					<span>
						<span>300 000 &euro;</span>
						<?php _e( "lev&eacute;s" ); ?>
					</span>
				</p>
				<p>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-people.png" alt="people" />
					<span>
						<span>6 000</span>
						<?php _e( "investisseurs" ); ?>
					</span>
				</p>
				<p>
					<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-project-list/picto-arrows.png" alt="arrows" />
					<span>
						<span>59</span>
						<?php _e( "projets propuls&eacute;s" ); ?>
					</span>
				</p>
			</div>
		</div>
	</header>
		
		
	<div class="padder">
		<nav id="project-filter">
			Filtres
			<select>
				<option>Tous les impacts</option>
			</select>
			<select>
				<option>Tous les régions</option>
			</select>
			<select>
				<option>Toutes les étapes de campagne</option>
			</select>
			<select>
				<option>Tous les secteurs d'activité</option>
			</select>
		</nav>
		
		<section class="wdg-component-projects-preview">
			<div class="block-projects">
			<?php
			$project_list_funding = ATCF_Campaign::get_list_funding( );
			$project_list_vote = ATCF_Campaign::get_list_vote( );
			$count = 0;
			foreach ( $project_list_funding as $project_post ) {
				$count++;
				$project_id = $project_post->ID;
				require('projects/preview.php');
			}
			foreach ( $project_list_vote as $project_post ) {
				$count++;
				$project_id = $project_post->ID;
				require('projects/preview.php');
			}
			?>
			</div>
		</section>
	    
	</div><!-- .padder -->
	
</div><!-- #content -->
	
<?php get_footer(); ?>