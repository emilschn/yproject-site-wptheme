<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<?php _e("Transaction en cours.", 'yproject'); ?><br>

<?php if ( !$page_controler->get_current_investment()->has_token() ): ?>
	<?php _e( "Merci de vous rendre sur la page", 'yproject' ); ?> <a href="<?php echo get_permalink($invest_page->ID); ?>"><?php _e( "Mes investissements", 'yproject' ); ?></a> <?php _e( "pour suivre l&apos;&eacute;volution de votre paiement.", 'yproject' ); ?><br><br>
<?php endif; ?>

<div class="align-center">
	<a class="button" href="<?php echo $page_controler->get_pending_next_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>
</div>
<br><br>
</div>