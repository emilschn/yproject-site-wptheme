<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify">
<?php _e( 'invest.pending.PENDING', 'yproject' ); ?><br>

<?php if ( !$page_controler->get_current_investment()->has_token() ): ?>
	<?php _e( 'invest.pending.PLEASE_GO_1', 'yproject' ); ?> <a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mon-compte' ); ?>"><?php _e( 'invest.pending.PLEASE_GO_2', 'yproject' ); ?></a> <?php _e( 'invest.pending.PLEASE_GO_3', 'yproject' ); ?><br><br>
<?php endif; ?>

<div class="align-center">
	<a class="button" href="<?php echo $page_controler->get_pending_next_link(); ?>"><?php _e( 'common.NEXT', 'yproject' ); ?></a>
</div>
<br><br>
</div>