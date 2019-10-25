<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();

$override_current_user = filter_input( INPUT_GET, 'override_current_user' );
$suffix = '';
if ( !empty( $override_current_user ) ) {
	$suffix = '?override_current_user=' .$override_current_user;
}
$lw_wallet_amount = $WDGUser_displayed->get_lemonway_wallet_amount();
$pending_amount = $WDGUser_displayed->get_pending_rois_amount();
?>

<h2>Mon porte-monnaie électronique</h2>

<div class="db-form v3 align-left">

	<div class="wallet-preview">
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-invest/picto-porte-monnaie.png" alt="porte-monnaie" width="100" height="69">
		<div>
			<span><?php echo UIHelpers::format_number( $lw_wallet_amount ); ?> &euro;</span><br>
			<span><?php _e( "disponibles", 'yproject' ); ?></span>
		</div>
		<a href="<?php echo home_url( '/les-projets/' ); ?>?source=account" class="button red half"><?php _e( "Investir", 'yproject' ); ?></a>
	</div>

	<?php if ( !$WDGUser_displayed->is_lemonway_registered() ): ?>
		<div class="wdg-message error msg-authentication-alert">
			<?php if ( $pending_amount > 0 ): ?>
				<?php echo sprintf( __( "Nous attendons votre authentification pour verser %s &euro; sur votre porte-monnaie.", 'yproject' ), UIHelpers::format_number( $pending_amount ) ); ?><br><br>
			<?php endif; ?>

			<?php _e( "Depuis Janvier 2019, l'authentification de votre compte est n&eacute;cessaire aupr&egrave;s de notre prestataire de paiement pour lib&eacute;rer l'acc&egrave;s &agrave; votre porte-monnaie et pouvoir retirer vos royalties." ); ?>
		</div>

		<a href="#authentication" class="button red go-to-tab" data-tab="authentication"><?php _e( "Voir le statut de mon authentification", 'yproject' ); ?></a>

	<?php else: ?>
		<?php if ( !$page_controler->is_iban_validated() ): ?>
			<h3><?php _e( "Retirer sur mon compte bancaire", 'yproject' ); ?></h3>

			<?php if ( $page_controler->is_iban_waiting() ): ?>
				<?php _e( "Votre RIB est en cours de validation par notre prestataire de paiement. Merci de revenir d'ici 48h pour vous assurer de sa validation.", 'yproject' ); ?>
				<br><br>

			<?php else: ?>
				<?php if ( $WDGUser_displayed->get_lemonway_iban_status() == WDGUser::$iban_status_rejected ): ?>
					<?php _e( "Votre RIB a &eacute;t&eacute; refus&eacute; par notre prestataire de paiement.", 'yproject' ); ?><br>
				<?php endif; ?>
				<?php _e( "Afin de retirer vos royalties, merci de renseigner vos coordonn&eacute;es bancaires.", 'yproject' ); ?><br><br>
				<a href="#bank" class="button blue go-to-tab" data-tab="bank"><?php _e( "Mes coordonn&eacute;es bancaires", 'yproject' ); ?></a>
				<br><br>

			<?php endif; ?>

		<?php elseif ( $lw_wallet_amount > 0 ): ?>
			<h3><?php _e( "Retirer sur mon compte bancaire", 'yproject' ); ?></h3>

			<form action="" method="POST" enctype="multipart/form-data" class="db-form v3">
				<p class="align-center">
					<input type="submit" class="button blue" value="Reverser sur mon compte bancaire" />
				</p>
				<input type="hidden" name="action" value="user_wallet_to_bankaccount" />
				<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>" />
			</form>

		<?php endif; ?>


		<h3><?php _e( "Historique de mes transactions", 'yproject' ); ?></h3>
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
		
	<?php endif; ?>

	<br><br>
</div>