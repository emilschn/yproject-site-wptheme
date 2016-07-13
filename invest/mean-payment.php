<?php
global $campaign, $payment_url, $error_session;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}
ypcf_session_start();

if (isset($campaign)):
	//Lien
	$page_mean_payment = get_page_by_path('moyen-de-paiement');
	$page_mean_payment_link = get_permalink($page_mean_payment->ID) . '?campaign_id=' . $campaign->ID . '&meanofpayment=';
	
	//Gestion wallet
	$WDGUser_current = WDGUser::current();
	$amount = $_SESSION['redirect_current_amount_part'] * $campaign->part_value();
	$can_use_wallet = FALSE;
	$can_use_card_and_wallet = FALSE;
	if ($_SESSION['redirect_current_invest_type'] == 'user') {
		$can_use_wallet = $WDGUser_current->can_pay_with_wallet($amount, $campaign);
		$can_use_card_and_wallet = $WDGUser_current->can_pay_with_card_and_wallet( $campaign );
	} else {
		$invest_type = $_SESSION['redirect_current_invest_type'];
		$organisation = new YPOrganisation($invest_type);
		$can_use_wallet = $organisation->can_pay_with_wallet($amount, $campaign);
		$can_use_card_and_wallet = $organisation->can_pay_with_card_and_wallet( $campaign );
	}
	
	//Possible de régler par virement ?
	$can_use_wire = ($campaign->can_use_wire($_SESSION['redirect_current_amount_part']));
	//Possible de régler par chèque ?
	$can_use_check = ($campaign->can_use_check($_SESSION['redirect_current_amount_part']));
	?>
						
	<?php
	global $current_breadcrumb_step; $current_breadcrumb_step = 3;
	locate_template( 'invest/breadcrumb.php', true );
	?>

	<?php if (isset($error_session) && $error_session == '1'): ?>
		<?php
			$page_invest_link = get_permalink($page_invest->ID);
			$page_invest_link .= '?campaign_id=' . $campaign->ID . '&invest_start=1';
		?>
		<?php _e("La session de paiement n'existe plus. Peut-&ecirc;tre avez-vous choisi un autre moyen de paiement ?", 'yproject'); ?><br />
		<a href="<?php echo $page_invest_link; ?>"><?php _e("Merci de recommencer le processus de paiement.", 'yproject'); ?></a><br /><br />

	<?php elseif (!empty($payment_url)): ?>
		<?php _e("La redirection automatique ayant &eacute;chou&eacute;, veuillez cliquer sur", 'yproject'); ?> <a href="<?php echo $payment_url; ?>"><?php _e("ce lien", 'yproject'); ?></a>.<br /><br />

	<?php else: ?>
		<?php _e("Merci de choisir votre moyen de paiement :", 'yproject'); ?><br />

		<ul class="invest-mean-payment">
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>card">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-carte.jpg" alt="<?php _e("Carte bancaire", 'yproject'); ?>" />
					<?php _e("Carte bancaire", 'yproject'); ?>
				</a>
			</li>
			
			<?php if ($can_use_wallet): ?>
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>wallet" class="alert-confirm" data-alertconfirm="<?php _e("Vous allez valider le transfert d'argent de votre porte-monnaie vers celui du projet.", 'yproject'); ?>">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-portemonnaie.jpg" alt="<?php _e("Porte-monnaie WEDOGOOD", 'yproject'); ?>" />
					<?php echo sprintf( __( 'Porte-monnaie WEDOGOOD (Vous disposez actuellement de %s &euro;)', 'yproject' ), $lemonway_amount ); ?>
				</a>
			</li>
			
			<?php elseif ($can_use_card_and_wallet): ?>
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>cardwallet">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-carte-portemonnaie.jpg" alt="<?php _e("Carte bancaire et porte-monnaie WEDOGOOD", 'yproject'); ?>" />
					<?php echo sprintf( __( 'Porte-monnaie WEDOGOOD (Vous disposez actuellement de %s &euro;) compl&eacute;t&eacute; par carte', 'yproject' ), $lemonway_amount ); ?>
				</a>
			</li>
			<?php endif; ?>
			
			<?php if ($can_use_wire): ?>
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>wire">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-virement.jpg" alt="<?php _e("Virement bancaire", 'yproject'); ?>" />
					<?php _e("Virement bancaire", 'yproject'); ?>
				</a>
			</li>
			<?php endif; ?>
			
			<?php if ($can_use_check): ?>
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>check">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-cheque.jpg" alt="<?php _e("Ch&egrave;que", 'yproject'); ?>" />
					<?php _e("Ch&egrave;que", 'yproject'); ?>
				</a>
			</li>
			<?php endif; ?>
			
			<div class="clear"></div>
		</ul>
	<?php endif; ?>

	<?php if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay): ?>
	<div class="align-center mangopay-image"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" alt="Bandeau Mangopay" /></div>
	<?php endif; ?>

<?php endif;