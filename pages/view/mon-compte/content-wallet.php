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

<div class="db-form v3 align-left"  id="item-body-wallet">

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
		<h3><?php _e( "Recharger mon porte-monnaie par virement", 'yproject' ); ?></h3>
		<p class="align-justify">
			<?php _e( "Afin d'emp&ecirc;cher les utilisations de cartes frauduleuses et le blanchiment d'argent, il n'est pas possible, pour l'instant, de recharger son porte-monnaie avec un autre moyen de paiement.", 'yproject' ); ?><br><br>
		</p>

		<strong><?php _e( "Compte bancaire de destination", 'yproject' ); ?></strong><br>
		<img src="<?php echo $stylesheet_directory_uri; ?>/images/footer/lemonway-gris.png" class="wire-lw right" alt="logo Lemonway" width="250">
		<strong><?php _e( "Titulaire du compte :", 'yproject' ); ?></strong> LEMON WAY<br>
		<strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268<br>
		<strong>BIC :</strong> BNPAFRPPIFE
		<br><br>
		
		<p class="align-justify">
			<strong><?php _e( "Code &agrave; indiquer (pour identifier votre paiement) :", 'yproject' ); ?></strong> <span id="clipboard-user-lw-code">wedogood-<?php echo $WDGUser_displayed->get_lemonway_id(); ?></span><br>
			<div class="align-center">
				<button type="button" class="button blue copy-clipboard" data-clipboard="clipboard-user-lw-code"><?php _e( "Copier le code", 'yproject' ); ?></button>
				<span class="hidden"><?php _e( "Code copi&eacute;", 'yproject' ); ?></span>
			</div>
			<br><br>
			<i><?php _e( "Indiquez imp&eacute;rativement ce code comme 'libell&eacute; b&eacute;n&eacute;ficiaire' ou 'code destinataire' au moment du virement !", 'yproject' ); ?></i>
			<br><br>
		</p>

		<?php if ( !$page_controler->is_iban_validated() ): ?>
			<h3><?php _e( "Virer vers mon compte bancaire", 'yproject' ); ?></h3>

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
			<h3><?php _e( "Virer vers mon compte bancaire", 'yproject' ); ?></h3>

			<form action="" method="POST" enctype="multipart/form-data" class="db-form v3 full align-left">
				<input type="hidden" name="action" value="user_wallet_to_bankaccount">
				<input type="hidden" name="user_id" value="<?php echo $WDGUser_displayed->get_wpref(); ?>">

				<div id="field-amount_to_bank" class="field field-text-money">
					<label for="amount_to_bank"><?php echo sprintf( __( "Montant &agrave; retirer (maximum %s &euro;) :", 'yproject' ), UIHelpers::format_number( $lw_wallet_amount ) ); ?></label>
					<div class="field-container">
						<span class="field-value">
							<input type="text" name="amount_to_bank" id="amount_to_bank" value="<?php echo $lw_wallet_amount; ?>" class="format-number">
							<span class="field-money">&euro;</span>
						</span>
					</div>
				</div>

				<?php $WDGUser_lw_bank_info = $page_controler->get_current_user_iban(); ?>
				<strong><?php _e( "Compte bancaire associ&eacute; :", 'yproject' ); ?></strong><br>
				<?php echo $WDGUser_lw_bank_info->HOLDER; ?><br>
				<?php echo $WDGUser_lw_bank_info->DATA; ?><br>
				<?php echo $WDGUser_lw_bank_info->SWIFT; ?><br>
				<br><br>

				<a href="#bank" class="button transparent go-to-tab" data-tab="bank"><?php _e( "Modifier mon RIB", 'yproject' ); ?></a>
				<br><br>
				<button type="submit" class="button blue"><?php _e( "Virer vers mon compte bancaire", 'yproject' ); ?></button>
			</form>
			<br><br>

		<?php endif; ?>


		<h3><?php _e( "Historique de mes transactions", 'yproject' ); ?></h3>
		<?php
		$transfers = get_posts( array(
			'author'		=> $WDGUser_displayed->get_wpref(),
			'numberposts'	=> -1,
			'post_type'		=> 'withdrawal_order_lw',
			'post_status'	=> 'any',
			'orderby'		=> 'post_date',
			'order'			=> 'DESC'
		) );
		?>

		<?php if ( $transfers ): ?>
		<ul class="user-history">
			<?php foreach ( $transfers as $transfer_post ): ?>

				<?php
				$post_amount = $transfer_post->post_title;
				?>
				<?php if ( $transfer_post->post_status == 'publish' ): ?>
					<li id="withdrawal-<?php echo $transfer_post->ID; ?>">
						<span><?php echo get_the_date( 'd/m/Y', $transfer_post ); ?></span>
						<span><?php echo UIHelpers::format_number( $post_amount ); ?> &euro;</span>
						<span><?php _e( "vers&eacute;s sur votre compte bancaire", 'yproject' ); ?></span>
					</li>
				<?php endif; ?>

			<?php endforeach; ?>
		</ul>

		<?php else: ?>
			Aucun transfert d&apos;argent.
		<?php endif; ?>
		
	<?php endif; ?>

	<br><br>
</div>