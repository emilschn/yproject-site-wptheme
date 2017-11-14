<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<div class="center">
	
	<h2>Je choisis mon mode de paiement :</h2>
	
	<div class="mean-payment-list">

		<?php if ( $page_controler->can_use_wallet() ): ?>
			<a href="<?php echo $page_controler->get_payment_url( 'wallet' ); ?>" class="alert-confirm" data-alertconfirm="<?php _e( "Vous allez valider le transfert d'argent de votre porte-monnaie vers celui du projet.", 'yproject' ); ?>">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-porte-monnaie.png" alt="<?php _e( "Porte-monnaie WEDOGOOD", 'yproject' ); ?>" width="120">
				<span class="mean-payment-name"><?php _e( "Porte-monnaie WE DO GOOD", 'yproject' ); ?></span><br>
				<span><?php echo sprintf( __( "Je dispose actuellement de %s &euro; sur mon porte-monnaie.", 'yproject' ), $page_controler->get_lemonway_amount() ); ?></span>
			</a>

		<?php elseif ( $page_controler->can_use_card_and_wallet() ): ?>
			<a href="<?php echo $page_controler->get_payment_url( 'cardwallet' ); ?>">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb-pm.png" alt="<?php _e( "Carte bancaire et porte-monnaie WEDOGOOD", 'yproject' ); ?>" width="120">
				<span class="mean-payment-name"><?php _e( "Porte-monnaie WE DO GOOD et carte bancaire", 'yproject' ); ?></span><br>
				<span><?php echo sprintf( __( "Je dispose actuellement de %s &euro; sur mon porte-monnaie.", 'yproject' ), $page_controler->get_lemonway_amount() ); ?></span><br>
				<span><?php echo sprintf( __( "Je paierai %s &euro; via carte bancaire.", 'yproject' ), $page_controler->get_remaining_amount() ); ?></span>
				<span><?php _e( "CB, Visa, Mastercard ; e-carte bleue provisoire non accept&eacute;e", 'yproject' ); ?></span>
			</a>
		<?php endif; ?>
		
		
		<a href="<?php echo $page_controler->get_payment_url( 'card' ); ?>">
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cb.png" alt="<?php _e( "Carte bancaire", 'yproject' ); ?>" width="120">
			<span class="mean-payment-name"><?php _e( "Carte bancaire", 'yproject' ); ?></span><br>
			<span><?php _e( "CB, Visa, Mastercard ; e-carte bleue provisoire non accept&eacute;e", 'yproject' ); ?></span>
		</a>

		
		<?php if ( $page_controler->can_use_wire() ): ?>
			<a href="<?php echo $page_controler->get_payment_url( 'wire' ); ?>" <?php if ( !$page_controler->is_user_lemonway_registered() ): ?>class="alert-confirm" data-alertconfirm="<?php _e( "Attention : pour investir via un virement bancaire, vous devrez nous fournir une copie de votre pi&egrave;ce d'identit&eacute; et un justificatif de domicile.", 'yproject' ); ?>"<?php endif; ?>>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( "Virement bancaire", 'yproject' ); ?>" width="120">
				<span class="mean-payment-name"><?php _e( "Virement bancaire", 'yproject' ); ?></span><br>
				<span><?php _e( "Une copie de votre pi&egrave;ce d'identit&eacute; et un justificatif de domicile seront n&eacute;cessaires", 'yproject' ); ?></span>
			</a>
		
		<?php elseif ( $page_controler->display_inactive_wire() ): ?>
			<p class="disabled">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-virement.png" alt="<?php _e( "Virement bancaire", 'yproject' ); ?>" width="120">
				<span class="mean-payment-name"><?php _e( "Virement bancaire", 'yproject' ); ?></span><br>
				<span><?php echo sprintf( __( "Les virements bancaires sont autoris&eacute;s &agrave; partir de %s &euro; d'investissement", 'yproject' ), ATCF_Campaign::$invest_amount_min_wire ); ?></span>
			</p>
		<?php endif; ?>

			
		<?php if ( $page_controler->can_use_check() ): ?>
			<a href="<?php echo $page_controler->get_payment_url( 'check' ); ?>">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cheque.png" alt="<?php _e( "Ch&egrave;que", 'yproject' ); ?>" width="120">
				<span class="mean-payment-name"><?php _e( "Ch&egrave;que", 'yproject' ); ?></span><br>
				<span><?php _e( "Pour une comptabilisation plus rapide, munissez-vous d'une photo de ce ch&egrave;que.", 'yproject' ); ?></span>
			</a>
			
		<?php elseif ( $page_controler->display_inactive_check() ): ?>
			<p class="disabled">
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/template-invest/picto-cheque.png" alt="<?php _e("Ch&egrave;que", 'yproject'); ?>" width="120">
				<span class="mean-payment-name"><?php _e( "Ch&egrave;que", 'yproject' ); ?></span><br>
				<span><?php echo sprintf( __( "Les paiements par ch&egrave;ques sont autoris&eacute;s &agrave; partir de %s &euro; d'investissement", 'yproject' ), ATCF_Campaign::$invest_amount_min_check ); ?></span>
			</p>
		<?php endif; ?>
	</div>
	
</div>
