<?php get_header(); ?>

	<div id="content">
		<div class="padder">
		    <br />
		    <div class="center">
			<?php 
			    date_default_timezone_set("Europe/Paris");
			    require_once("common.php");
			?>
			<?php printHomePreviewProjectsTemp(-1); ?>

			<div style="clear: both"></div>
		    </div>
		</div><!-- .padder -->
	</div><!-- #content -->
	
<?php get_footer(); ?>