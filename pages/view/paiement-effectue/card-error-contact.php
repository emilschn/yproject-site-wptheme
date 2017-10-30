<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<br><br>
<?php _e( "Il y a eu une erreur pendant votre tentative d'investissement, ou celui-ci &eacute;tait d&eacute;j&agrave; comptabilis&eacute.", 'yproject' ); ?><br>
<?php _e( "Merci de nous contacter afin d'&eacute;tudier votre tentative sur investir@wedogood.co.", 'yproject' ); ?><br>

<div class="align-center">
	<?php if ( $page_controler->get_error_link() != '' ): ?>
	<a class="button" href="<?php echo $page_controler->get_error_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>

	<?php elseif ( $page_controler->get_error_restart_link() != '' ): ?>
	<a href="<?php echo $page_controler->get_error_restart_link(); ?>"><?php _e( "Red&eacute;marrer l'investissement", 'yproject' ); ?></a>

	<?php endif; ?>
</div><br><br>