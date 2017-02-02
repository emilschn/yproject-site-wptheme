<?php locate_template( array( 'members/single/admin-bar.php' ), true ); ?>

<h2 class="underlined"><?php _e( 'E-mails de notifications', 'yproject' ); ?></h2>

<form action="<?php echo home_url('/mes-notifications'); ?>" method="post" class="standard-form " id="settings-form">
	<p><?php _e( 'Envoyer une notification par e-mail quand :', 'yproject' ); ?></p>

	<div class="submit">
		<input type="submit" name="submit" value="<?php _e( 'Enregistrer', 'yproject' ); ?>" id="submit" class="auto"  />
	</div>

</form>