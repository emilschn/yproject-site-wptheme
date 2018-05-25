<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$current_user_authentication = $page_controler->get_current_user_authentication();
$current_user_authentication_info = $page_controler->get_current_user_authentication_info();
?>

<h2 class="underlined"><?php _e( 'Mon authentification', 'yproject' ); ?></h2>

<p>
	<h3><?php _e( "Niveau 0", 'yproject' ); ?> <img src="" alt="check"></h3>
	<?php _e( "Vous pouvez suivre et évaluer des projets.", 'yproject' ); ?>
</p>

<p>
	<h3><?php _e( "Niveau 1", 'yproject' ); ?> <img src="" alt="check" class="<?php if ( $current_user_authentication < 1 ) { echo "hidden"; } ?>"></h3>
	<?php _e( "Vous pouvez pr&eacute;-investir et investir par carte et virement jusqu'&agrave; 250 €.", 'yproject' ); ?><br>
	<?php _e( "Investissement par ch&egrave;que illimit&eacute;.", 'yproject' ); ?><br>
	<?php _e( "Vous pouvez investir vos royalties sur d'autres projets jusqu'&agrave; 250 €.", 'yproject' ); ?><br>
	<?php if ( $current_user_authentication == 0 ): ?>
		<a href="#parameters"><?php _e( "Remplissez vos param&egrave;tres", 'yproject' ); ?></a>
		<?php if ( !empty( $current_user_authentication_info ) ): ?>
			<?php echo $current_user_authentication_info; ?>
		<?php endif; ?>
	<?php endif; ?>
</p>

<p>
	<h3><?php _e( "Niveau 2", 'yproject' ); ?> <img src="" alt="check" class="<?php if ( $current_user_authentication < 2 ) { echo "hidden"; } ?>"></h3>
	<?php _e( "Vous pouvez pr&eacute;-investir et investir par carte, virement et royalties de mani&egrave;re illimit&eacute;e.", 'yproject' ); ?><br>
	<?php if ( $current_user_authentication == 1 ): ?>
		<?php _e( "Envoyez vos documents pour que notre prestataire de paiement les analyse et les valide.", 'yproject' ); ?>
		<?php if ( !empty( $current_user_authentication_info ) ): ?>
			<?php echo $current_user_authentication_info; ?>
		<?php endif; ?>
	<?php endif; ?>
</p>

<p>
	<h3><?php _e( "Niveau 3", 'yproject' ); ?> <img src="" alt="check" class="<?php if ( $current_user_authentication < 3 ) { echo "hidden"; } ?>"></h3>
	<?php _e( "Vous pouvez retirer vos royalties sur votre compte bancaire.", 'yproject' ); ?><br>
	<?php if ( $current_user_authentication == 2 ): ?>
		<?php _e( "Renseignez votre RIB avec un fichier correspondant pour que notre prestataire de paiement l'analyse et le valide.", 'yproject' ); ?>
		<?php if ( !empty( $current_user_authentication_info ) ): ?>
			<?php echo $current_user_authentication_info; ?>
		<?php endif; ?>
	<?php endif; ?>
</p>

<p>
	<h3><?php _e( "Niveau 3 - Banques en ligne", 'yproject' ); ?> <img src="" alt="check" class="<?php if ( $current_user_authentication < 4 ) { echo "hidden"; } ?>"></h3>
	<?php _e( "Vous pouvez retirer vos royalties sur votre compte bancaire en ligne (compte Nickel, ...).", 'yproject' ); ?><br>
	<?php if ( $current_user_authentication == 3 ): ?>
		<?php _e( "Si vous avez un compte chez une banque en ligne (compte Nickel), fournissez une deuxi&egrave;me pi&egrave;ce d'identit&eacute; pour son authentification.", 'yproject' ); ?>
		<?php if ( !empty( $current_user_authentication_info ) ): ?>
			<?php echo $current_user_authentication_info; ?>
		<?php endif; ?>
	<?php endif; ?>
</p>