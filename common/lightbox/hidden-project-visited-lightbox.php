<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php
$id_campaign_hidden_visited = $page_controler->get_show_user_hidden_project_visited();
$campaign_hidden_visited = new ATCF_Campaign( $id_campaign_hidden_visited );
$url_campaign_hidden_visited = $campaign_hidden_visited->get_public_url();
?>

<?php ob_start(); ?>
<div class="wdg-lightbox-ref">
	
	<p class="align-justify">
		<?php echo sprintf( __( 'account.lightbox.hidden-project-visited.DESCRIPTION', 'yproject' ), $campaign_hidden_visited->data->post_title ); ?>
	</p>
	
	<form class="db-form v3 button-list">
		<a href="#" class="button transparent half"><?php _e( 'Non', 'yproject' ); ?></a>
		<a href="<?php echo $url_campaign_hidden_visited; ?>" class="button red half"><?php _e( 'Oui', 'yproject' ); ?></a>
		<br><br>
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="hidden-project-visited" title="' .__( 'account.lightbox.hidden-project-visited.TITLE', 'yproject' ). '" autoopen="1" catchclick="1" save-close="1"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
