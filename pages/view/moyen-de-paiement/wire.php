<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$viban = $page_controler->get_investor_iban();
?>

<div class="center align-justify wdg-lightbox-ref">
<br><br>

<?php _e( 'invest.mean-payment.wire.INFORMATION', 'yproject' ); ?><br>
<ul>
	<li><strong><?php _e( 'invest.mean-payment.wire.ACCOUNT_OWNER', 'yproject' ); ?></strong> <?php echo $viban[ 'holder' ]; ?></li>
	<li><strong>IBAN :</strong> <?php echo $viban[ 'iban' ]; ?></li>
	<li><strong>BIC :</strong> <?php echo $viban[ 'bic' ]; ?></li>
	<?php if ( !empty( $viban[ 'backup' ] ) && !empty( $viban[ 'backup' ][ 'lemonway_id' ] ) ): ?>
		<li><strong><?php _e( 'account.bank.CODE', 'yproject' ); ?></strong> <?php echo $viban[ 'backup' ][ 'lemonway_id' ]; ?></li>
	<?php endif; ?>
</ul>
<br><br>

<div class="db-form full v3 investment-form">
	<p class="align-justify">
		<?php _e( 'invest.mean-payment.wire.ONCE_WIRE_DONE', 'yproject' ); ?>
	</p>
	<a class="button red investment-button" href="<?php echo $page_controler->get_wire_next_link(); ?>">
		<span class="button-text">
			<?php _e( 'common.NEXT', 'yproject' ); ?>
		</span>
		<span class="button-loading loading align-center hidden">
			<img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30" alt="chargement" /><?php _e( 'common.NEXT', 'yproject' ); ?>...
		</span>
	</a>		
</div>
<br><br>

</div>
