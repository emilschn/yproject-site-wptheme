<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<br><br>
<?php echo $page_controler->get_current_investment()->error_item->get_error_message(); ?><br>

<?php
_e( "Code erreur :", 'yproject' );
echo $page_controler->get_current_investment()->error_item->get_error_code();
?><br>

<div class="align-center">
	<?php if ( $page_controler->get_error_link() != '' ): ?>
	<a class="button" href="<?php echo $page_controler->get_error_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>

	<?php elseif ( $page_controler->get_error_restart_link() != '' ): ?>
	<a href="<?php echo $page_controler->get_error_restart_link(); ?>"><?php _e( "Red&eacute;marrer l'investissement", 'yproject' ); ?></a>

	<?php endif; ?>
</div><br><br>
</div>