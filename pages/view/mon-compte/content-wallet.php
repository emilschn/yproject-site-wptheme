<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
?>

<?php
//Si on a demandé de renvoyer le code
if (isset($_GET['invest_id_resend']) && $_GET['invest_id_resend'] != '') {
	$contractid = ypcf_get_signsquidcontractid_from_invest($_GET['invest_id_resend']);
	// $signsquid_infos = signsquid_get_contract_infos($contractid);
	$signsquid_signatory = signsquid_get_contract_signatory($contractid);
	$current_user = wp_get_current_user();
	if ($signsquid_signatory != '' && $signsquid_signatory->{'email'} == $current_user->user_email) {
		if (ypcf_send_mail_purchase($_GET['invest_id_resend'], "send_code", $signsquid_signatory->{'code'}, $current_user->user_email)) {
			?>
			Votre code de signature de contrat a &eacute;t&eacute; renvoy&eacute; &agrave; l&apos;adresse <?php echo $current_user->user_email; ?>.<br />
			<?php
		} else {
			?>
			<span class="errors">Il y a eu une erreur lors de l&apos;envoi du code. N&apos;h&eacute;sitez pas &agrave; nous contacter.</span><br />
			<?php
		}
	} else {
	?>
	<span class="errors">Nous ne trouvons pas le contrat correspondant.</span><br />
	<?php
	}
}
?>
<h2 class="underlined">Mon porte-monnaie électronique</h2>

<?php
$amount = $WDGUser_displayed->get_lemonway_wallet_amount();
$override_current_user = filter_input( INPUT_GET, 'override_current_user' );
$suffix = '';
if ( !empty( $override_current_user ) ) {
	$suffix = '?override_current_user=' .$override_current_user;
}
?>
Vous disposez de <?php echo $amount; ?> &euro; dans votre porte-monnaie.
<a href="<?php echo home_url( '/details-des-investissements/' ) . $suffix; ?>">Voir le d&eacute;tail de mes royalties</a>
<br><br>

<?php if ( !$WDGUser_displayed->is_document_lemonway_registered( LemonwayDocument::$document_type_bank ) ): ?>
	<?php if ( $WDGUser_displayed->get_document_lemonway_status( LemonwayDocument::$document_type_bank ) == LemonwayDocument::$document_status_waiting ): ?>
		<?php _e( "Votre RIB est en cours de validation par notre prestataire de paiement. Merci de revenir d'ici 48h pour vous assurer de sa validation.", 'yproject' ); ?>
		<br><br>

	<?php else: ?>
		<?php _e( "Afin de retirer vos royalties, merci de renseigner vos coordonn&eacute;es bancaires.", 'yproject' ); ?><br><br>
		<a href="#bank" class="button blue go-to-tab" data-tab="bank"><?php _e( "Mes coordonn&eacute;es bancaires", 'yproject' ); ?></a>
		<br><br>

	<?php endif; ?>

<?php elseif ($amount > 0): ?>
	<form action="" method="POST" enctype="multipart/form-data">
		<p class="align-center">
			<input type="submit" class="button blue" value="Reverser sur mon compte bancaire" />
		</p>
		<input type="hidden" name="action" value="user_wallet_to_bankaccount" />
		<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
	</form>
	<br><br>
	
<?php endif; ?>
	
	
<h2 class="underlined"><?php _e( 'Mes transferts d&apos;argent', 'yproject' ); ?></h2>
<?php
$args = array(
	'author'    => get_current_user_id(),
	'post_type' => 'withdrawal_order_lw',
	'post_status' => 'any',
	'orderby'   => 'post_date',
	'order'     =>  'ASC'
);
$transfers = get_posts($args);
if ($transfers) :
?>
<ul class="user_history">
	<?php 
	foreach ( $transfers as $post ) :
		$post = get_post($post);
		$post_amount = $post->post_title;
		?>
		<?php if ($post->post_status == 'publish'): ?>
		<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Termin&eacute;</li>
		<?php elseif ($post->post_status == 'draft'): ?>
		<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- Annul&eacute;</li>
		<?php else: ?>
		<li id="<?php echo $post->post_content; ?>"><?php echo $post->post_date; ?> : <?php echo $post_amount; ?>&euro; -- En cours</li>
		<?php endif; ?>
	<?php
	endforeach;
	?>
</ul>
<?php else: ?>
	Aucun transfert d&apos;argent.
<?php endif; ?>
<br><br>

