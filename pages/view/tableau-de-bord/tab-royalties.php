<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$saved_mandates_list = $page_controler->get_campaign_organization()->get_lemonway_mandates();
$last_mandate_status = '';
$last_mandate_id = FALSE;
if ( !empty( $saved_mandates_list ) ) {
	$last_mandate = end( $saved_mandates_list );
	$last_mandate_status = $last_mandate[ "S" ];
	$last_mandate_id = $last_mandate[ "ID" ];
}
?>

<div>
<?php if ( $page_controler->get_return_lemonway_card() == TRUE ): ?>
	<?php
	$msg_validation_payment = __("Paiement effectu&eacute;", "yproject");
	echo do_shortcode('[yproject_lightbox id="msg-validation-payment" scrolltop="1" msgtype="valid" autoopen="1"]'.$msg_validation_payment.'[/yproject_lightbox]');
	?>
<?php elseif ( $page_controler->get_return_lemonway_card() !== FALSE ): ?>
	<?php
	$msg_error_payment = __("Il y a eu une erreur au cours de votre paiement.", "yproject");
	echo do_shortcode('[yproject_lightbox id="msg-validation-payment" scrolltop="1" msgtype="error" autoopen="1"]'.$msg_error_payment.'[/yproject_lightbox]');
	?>
<?php endif; ?>
</div>

<h2><?php _e( "Royalties", 'yproject' ); ?></h2>

<ul class="menu-onglet">
  <li><a href="#royalties" class="focus" data-subtab="versements"><?php _e( "Versements", 'yproject' ); ?><span></span></a></li>
  <li><a href="#royalties" data-subtab="ajustements"><?php _e( "Ajustements", 'yproject' ); ?><span></span></a></li>
</ul>

<?php
// Inclusion des fichiers externes dans /tab-royalties/
locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/tab-versements.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/tab-ajustements.php'  ), true );