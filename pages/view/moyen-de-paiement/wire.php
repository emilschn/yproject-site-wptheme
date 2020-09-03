<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify wdg-lightbox-ref">
<br><br>

<?php _e( 'invest.mean-payment.wire.INFORMATION', 'yproject' ); ?><br>
<ul>
	<li><strong><?php _e( 'invest.mean-payment.wire.ACCOUNT_OWNER', 'yproject' ); ?></strong> LEMON WAY</li>
	<li><strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268</li>
	<li><strong>BIC :</strong> BNPAFRPPIFE</li>
</ul>

<?php _e( 'account.wallet.TRANSFER_ID_CODE', 'yproject' ); ?><br>
<strong><span id="clipboard-user-lw-code">wedogood-<?php echo $page_controler->get_investor_lemonway_id(); ?></span></strong>
<div class="align-center">
	<button type="button" class="button blue copy-clipboard" data-clipboard="clipboard-user-lw-code"><?php _e( 'account.wallet.COPY_CODE', 'yproject' ); ?></button>
	<span class="hidden"><?php _e( 'account.wallet.CODE_COPIED', 'yproject' ); ?></span>
</div>
<br><br>
<?php _e( 'account.wallet.WHERE_TO_COPY_THE_CODE', 'yproject' ); ?>

<br><br>

<div class="db-form full v3">
	<p class="align-justify">
		<?php _e( 'invest.mean-payment.wire.ONCE_WIRE_DONE', 'yproject' ); ?>
	</p>
	<a class="button red" href="<?php echo $page_controler->get_wire_next_link(); ?>"><?php _e( 'common.NEXT', 'yproject' ); ?></a>
</div>
<br><br>

</div>


<hr />
<?php _e( 'invest.mean-payment.wire.CODE_EXAMPLES', 'yproject' ); ?><br><br>
<div class="align-center"><img src="<?php echo home_url( '/wp-content/plugins/appthemer-crowdfunding/includes/ui/shortcodes/capture-lbp.png' ); ?>" /></div><br><br>