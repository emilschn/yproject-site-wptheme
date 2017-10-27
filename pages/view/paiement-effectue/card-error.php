<?php global $page_controler, $stylesheet_directory_uri; ?>

<?php echo $page_controler->get_current_investment()->get_error_message(); ?><br>

<?php
_e( "Code erreur :", 'yproject' );
echo $page_controler->get_current_investment()->get_error_code();
?><br>

<div class="align-center">
	<?php if ( $page_controler->get_error_link() != '' ): ?>
	<a class="button" href="<?php echo $page_controler->get_error_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>

	<?php elseif ( $page_controler->get_error_restart_link() != '' ): ?>
	<a href="<?php echo $page_controler->get_error_restart_link(); ?>"><?php _e( "Red&eacute;marrer l'investissement", 'yproject' ); ?></a>

	<?php endif; ?>
</div><br><br>