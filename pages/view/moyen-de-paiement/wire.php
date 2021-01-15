<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$viban = $page_controler->get_investor_iban();
?>

<div class="center align-justify wdg-lightbox-ref">
<br><br>

<?php _e("Afin de proc&eacute;der au virement, voici les informations bancaires dont vous aurez besoin :", 'yproject'); ?><br>
<ul>
	<li><strong><?php _e( "Titulaire du compte :", 'yproject' ); ?></strong> <?php echo $viban->HOLDER; ?></li>
	<li><strong>IBAN :</strong> <?php echo $viban->IBAN; ?></li>
	<li><strong>BIC :</strong> <?php echo $viban->BIC; ?></li>
</ul>
<br><br>

<div class="db-form full v3">
	<p class="align-justify">
		<?php _e("Une fois le virement effectu&eacute;, cliquez sur", 'yproject'); ?>
	</p>
	<a class="button red" href="<?php echo $page_controler->get_wire_next_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>
</div>
<br><br>

</div>