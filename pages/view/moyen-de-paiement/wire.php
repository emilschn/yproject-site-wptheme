<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$viban = $page_controler->get_investor_iban();
?>

<div class="center align-justify wdg-lightbox-ref">
<br><br>

<?php _e( 'invest.mean-payment.wire.INFORMATION', 'yproject' ); ?><br>
<ul>
	<li><strong><?php _e( 'invest.mean-payment.wire.ACCOUNT_OWNER', 'yproject' ); ?></strong> <?php echo $viban->HOLDER; ?></li>
	<li><strong>IBAN :</strong> <?php echo $viban->DATA; ?></li>
	<li><strong>BIC :</strong> <?php echo $viban->SWIFT; ?></li>
</ul>
<br><br>

<div class="db-form full v3">
	<p class="align-justify">
		<?php _e( 'invest.mean-payment.wire.ONCE_WIRE_DONE', 'yproject' ); ?>
	</p>
	<a class="button red" href="<?php echo $page_controler->get_wire_next_link(); ?>"><?php _e( 'common.NEXT', 'yproject' ); ?></a>
</div>
<br><br>

</div>
