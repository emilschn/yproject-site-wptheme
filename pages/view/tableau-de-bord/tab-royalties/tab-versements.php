<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div id="stat-subtab-versements" class="stat-subtab">

	<div id="tab-wallet-timetable" class="tab-content-large">
		<?php if ($page_controler->get_campaign()->funding_type() == 'fundingdonation'): ?>
			Ce projet n'est pas concerné.

		<?php else: ?>

			<?php if ( $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_funded || $page_controler->get_campaign()->campaign_status() == ATCF_Campaign::$campaign_status_closed ): ?>

				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/form-send-document.php' ), true ); ?>

				<?php locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/list-declarations.php' ), true ); ?>

				<?php if ( $page_controler->can_access_admin() ): ?>

					<form method="POST" action="<?php echo admin_url( 'admin-post.php?action=declaration_auto_generate'); ?>" class="align-center admin-theme-block">

						<br />
						<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>">
						<input type="hidden" name="month_count" value="3">
						Nombre de déclarations (ne rien préciser si procédure normale) : <input type="text" name="declarations_count"><br>
						<button type="submit" class="button admin-theme"><?php _e( "G&eacute;n&eacute;rer les &eacute;ch&eacute;ances manquantes", 'yproject' ); ?></button>
						<br /><br />

					</form>
					<br /><br />

				<?php endif; ?>

			<?php else: ?>
				<p class="align-center">
					<?php _e( "Retrouvez prochainement ici le suivi de vos paiements de royalties.", 'yproject' ); ?>
				</p>

			<?php endif; ?>

		<?php endif; ?>
	</div>
	
</div>