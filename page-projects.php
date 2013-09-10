<?php get_header(); ?>

	<div id="content">
		<div class="padder">
		    <div class="center">
			<?php 
			    date_default_timezone_set("Europe/Paris");
			    require_once("common.php");
			?>
			
			<div class="projects_founding_zone">
			    <div class="projects_title"><?php _e("Les projets en cours de financement", "yproject"); ?></div>
			    <div>
				<?php printPreviewProjectsNew(-1); ?>
				<div style="clear: both"></div>
			    </div>
			</div>
			<div class="projects_vote_zone">
			    <div class="projects_title"><?php _e("Les projets en cours de vote", "yproject"); ?></div>
			    <div>
				<?php printPreviewProjectsVote(-1); ?>
				<div style="clear: both"></div>
			    </div>
			</div>

		    </div>
		</div><!-- .padder -->
	</div><!-- #content -->
	
<?php get_footer(); ?>