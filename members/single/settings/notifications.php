<?php
get_header(); 
locate_template( array( 'members/single/admin-bar.php' ), true ); ?>

	<div id="content">
		<div class="padder center">

			<h2 class="underlined"><?php _e( 'E-mails de notifications', 'yproject' ); ?></h2>

			<form action="<?php echo home_url('/mes-notifications'); ?>" method="post" class="standard-form " id="settings-form">
				<p><?php _e( 'Envoyer une notification par e-mail quand :', 'yproject' ); ?></p>

				<div class="submit">
					<input type="submit" name="submit" value="<?php _e( 'Enregistrer', 'yproject' ); ?>" id="submit" class="auto"  />
				</div>

			</form>

		</div><!-- .padder -->
	</div><!-- #content -->


<?php get_footer();