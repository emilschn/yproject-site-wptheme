<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php _e( "Afin d'investir par ch&egrave;que, prenez une photo du ch&egrave;que que vous souhaitez faire &agrave; l'ordre de", 'yproject' ); ?>
<?php echo $page_controler->get_campaign_organization_name(); ?>
<?php _e( "et envoyez cette photo gr&acirc;ce au formulaire ci-dessous.", 'yproject' ); ?><br><br>

<?php _e( "Ensuite, envoyez-nous ce ch&egrave;que &agrave; l'adresse suivante :", 'yproject' ); ?><br>
WE DO GOOD<br>
8 rue Kervégan<br>
44000 Nantes<br><br>

<?php _e( "Le ch&egrave;que ne sera encaiss&eacute; que si la campagne r&eacute;ussit.", 'yproject' ); ?><br><br>

<?php if ( $page_controler->get_current_investment()->get_session_amount() > 1500 ): ?>
	<?php _e( "Lorsque nous l'aurons valid&eacute;, vous recevrez un contrat d'investissement &agrave; signer en ligne, via notre partenaire Signsquid.", 'yproject' ); ?><br><br>
<?php endif; ?>

<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST" enctype="multipart/form-data">
	<input type="file" name="check_picture" />
	<button type="submit" class="button"><?php _e( "Envoyer", 'yproject' ); ?></button>
	<input type="hidden" name="action" value="post_invest_check" />
	<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_current_campaign()->ID; ?>" />
</form>

<br><br>

<?php _e( "Si vous ne pouvez pas prendre le ch&egrave;que en photo pour l'instant,", 'yproject' ); ?>
<?php _e( "vous pouvez confirmer votre investissement", 'yproject' ); ?>
<?php _e( "et nous envoyer ce chèque &agrave; l'adresse investir@wedogood.co quand ce sera possible.", 'yproject' ); ?>

<br><br>

<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="POST">
	<button type="submit" class="button"><?php _e( "Confirmer et envoyer plus tard", 'yproject' ); ?></button>
	<input type="hidden" name="action" value="post_confirm_check" />
	<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_current_campaign()->ID; ?>" />
</form>

<br><br>
