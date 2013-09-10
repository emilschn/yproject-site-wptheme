<?php get_header(); ?>

	<div id="content">
		<div class="padder">
		    <div class="center">
			<?php 
			    date_default_timezone_set("Europe/Paris");
			    require_once("common.php");
			?>
			<?php /*printHomePreviewProjectsTemp(-1);*/ ?>
			
			<div class="projects_founding_zone">
			    <div class="projects_title">Les projets en cours de financement</div>
			    <div>
				<?php printPreviewProjectsNew(-1); ?>
				<div style="clear: both"></div>
			    </div>
			</div>
			<div class="projects_vote_zone">
			    <div class="projects_title">Les projets en cours de vote</div>
			    <div>
				<?php printPreviewProjectsVote(-1); ?>
				<div style="clear: both"></div>
			    </div>
			</div>

		    </div>
		</div><!-- .padder -->
	</div><!-- #content -->
	
<?php get_footer(); ?>