<?php global $page_controler; ?>

<div id="content" style="margin-top: 90px; background-color: #f2f2f2;">
	<div class="padder_more">
		<div class="center_small margin-height">
			
			<div class="errors align-center margin-height">
				<?php echo $page_controler->get_login_error_reason(); ?>
			</div>
			
			<?php locate_template( 'common/connexion-lightbox.php', TRUE, FALSE ); ?>
		</div>
	</div>
</div>