<?php global $stylesheet_directory_uri, $post; ?>

<?php
$WDGUser_current = WDGUser::current();
$amount_voted = 0;
if ( $WDGUser_current->has_voted_on_campaign( $post->ID ) && !$WDGUser_current->has_invested_on_campaign( $post->ID ) ) {
	$amount_voted = $WDGUser_current->get_amount_voted_on_campaign( $post->ID );
}
?>

<?php ob_start(); ?>
<i><?php _e( "Vos informations ont bien &eacute;t&eacute; enregistr&eacute;es.", 'yproject' ); ?></i>
<br><br><br>

<?php if ( $amount_voted > 250 && !$WDGUser_current->is_lemonway_registered() ): ?>
	<?php _e( "Votre compte n'est pas encore authentifi&eacute; aupr&egrave;s de notre prestataire de paiement, Lemon Way. Vous ne pouvez donc pas investir plus de 250 €.", 'yproject' ); ?>
	<br><br>
	<?php _e( "Pour authentifier votre compte, il suffit de compl&eacute;ter les informations de votre compte personnel et d'y d&eacute;poser une copie de votre carte d'identit&eacute; ou passeport, ainsi qu'un justificatif de domicile.", 'yproject' ); ?>
	<br>
	<p class="align-center">
		<a href="<?php echo home_url( '/mon-compte/' ); ?>" class="button red"><?php _e( "Je compl&egrave;te mon compte", 'yproject' ); ?></a>
	</p>
	<br><br><br>
<?php endif; ?>

<?php _e( "Merci d'avoir &eacute;valu&eacute; sur ce projet, pensez &agrave; en parler autour de vous !", 'yproject' ); ?>
<br><br>
<p class="align-center">
	<?php locate_template( array( "projects/common/share-buttons.php" ), true, false );  ?>
</p>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
$campaign_title = $post->post_title;
echo do_shortcode('[yproject_lightbox_cornered id="vote-share" title="'.__( "&Eacute;valuation de ", 'yproject' ).$campaign_title.'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');