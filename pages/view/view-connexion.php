<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="content" style="margin-top: 90px; background-color: #f2f2f2;">
	<div class="padder_more">
		<div class="center_small margin-height">
			
			<?php if ( $page_controler->is_login_displayed() ): ?>
				<div class="errors align-center" style="padding: 20px 0px;">
					<?php echo $page_controler->get_login_error_reason(); ?>
				</div>

				<?php locate_template( 'common/connexion-lightbox.php', TRUE, FALSE ); ?>
			
			<?php else: ?>
				<?php locate_template( 'common/register-lightbox.php', TRUE, FALSE ); ?>
			
			<?php endif; ?>
			
		</div>
	</div>
</div>