<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center align-justify wdg-lightbox-ref">
<br><br>

<?php _e("Afin de proc&eacute;der au virement, voici les informations bancaires dont vous aurez besoin :", 'yproject'); ?><br>
<ul>
	<li><strong><?php _e( "Titulaire du compte :", 'yproject' ); ?></strong> LEMON WAY</li>
	<li><strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268</li>
	<li><strong>BIC :</strong> BNPAFRPPIFE</li>
</ul>

<?php _e( "Code &agrave; indiquer (pour identifier votre paiement) :", 'yproject' ); ?><br>
<strong>wedogood-<?php echo $page_controler->get_investor_lemonway_id(); ?></strong>
<br><br>
<?php _e( "Indiquez imp&eacute;rativement ce code comme 'libell&eacute; b&eacute;n&eacute;ficiaire' ou 'code destinataire' au moment du virement !", 'yproject' ); ?>

<br><br>

<div class="db-form full v3">
	<p class="align-justify">
		<?php _e("Une fois le virement effectu&eacute;, cliquez sur", 'yproject'); ?>
	</p>
	<a class="button red" href="<?php echo $page_controler->get_wire_next_link(); ?>"><?php _e("Suivant", 'yproject'); ?></a>
</div>
<br><br>

</div>


<hr />
<?php _e("Exemple de saisie du code destinataire sur diff&eacute;rentes banques :", 'yproject'); ?><br><br>
<div class="align-center"><img src="<?php echo home_url( '/wp-content/plugins/appthemer-crowdfunding/includes/ui/shortcodes/capture-lbp.png' ); ?>" /></div><br><br>