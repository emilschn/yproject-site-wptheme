<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
?>

<h2>Mon porte-monnaie électronique</h2>

<?php
$amount = $WDGUser_displayed->get_lemonway_wallet_amount();
$override_current_user = filter_input( INPUT_GET, 'override_current_user' );
$suffix = '';
if ( !empty( $override_current_user ) ) {
	$suffix = '?override_current_user=' .$override_current_user;
}
?>
Montant des royalties vers&eacute;es : <?php echo $WDGUser_displayed->get_rois_amount(); ?> &euro;<br>
<?php if ( !$WDGUser_displayed->is_lemonway_registered() ): ?>
	<?php $pending_amount = $WDGUser_displayed->get_pending_rois_amount(); ?>
	<?php if ( $pending_amount > 0 ): ?>
	<?php echo $pending_amount; ?> &euro; sont en attente d'authentification.<br>
	<?php endif; ?>
	
<?php else: ?>
	Vous disposez de <?php echo $amount; ?> &euro; dans votre porte-monnaie.<br>
	
<?php endif; ?>
<br><br>

<?php if ( !$WDGUser_displayed->is_lemonway_registered() ): ?>
	<div class="db-form v3">
		<div class="wdg-message error msg-authentication-alert">
			<?php _e( "Depuis Janvier 2019, l'authentification de votre compte est n&eacute;cessaire aupr&egrave;s de notre prestataire de paiement pour lib&eacute;rer l'acc&egrave;s &agrave; votre porte-monnaie et pouvoir retirer vos royalties." ); ?>
		</div>

		<div class="align-center">
			<a href="#authentication" class="button red go-to-tab" data-tab="authentication"><?php _e( "Voir le statut de mon authentification", 'yproject' ); ?></a>
		</div>
	</div>

<?php else: ?>
	<?php if ( !$page_controler->is_iban_validated() ): ?>
		<?php if ( $page_controler->is_iban_waiting() ): ?>
			<?php _e( "Votre RIB est en cours de validation par notre prestataire de paiement. Merci de revenir d'ici 48h pour vous assurer de sa validation.", 'yproject' ); ?>
			<br><br>

		<?php else: ?>
			<?php if ( $WDGUser_displayed->get_lemonway_iban_status() != WDGUser::$iban_status_rejected ): ?>
			<?php _e( "Votre RIB a &eacute;t&eacute; refus&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
			<?php endif; ?>
			<?php _e( "Afin de retirer vos royalties, merci de renseigner vos coordonn&eacute;es bancaires.", 'yproject' ); ?><br><br>
			<a href="#bank" class="button blue go-to-tab" data-tab="bank"><?php _e( "Mes coordonn&eacute;es bancaires", 'yproject' ); ?></a>
			<br><br>

		<?php endif; ?>

	<?php elseif ( $amount > 0 ): ?>
		<form action="" method="POST" enctype="multipart/form-data">
			<p class="align-center">
				<input type="submit" class="button blue" value="Reverser sur mon compte bancaire" />
			</p>
			<input type="hidden" name="action" value="user_wallet_to_bankaccount" />
			<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
		</form>
		<br><br>

	<?php endif; ?>


	<h3><?php _e( 'Mes transferts d&apos;argent', 'yproject' ); ?></h3>
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
	
<?php endif; ?>