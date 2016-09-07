<?php
global $campaign, $payment_url, $error_session;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}

if (isset($campaign)):
	//Lien
	$page_mean_payment = get_page_by_path('moyen-de-paiement');
	$page_mean_payment_link = get_permalink($page_mean_payment->ID) . '?campaign_id=' . $campaign->ID . '&meanofpayment=';
	//Possible de régler par virement ?
	$can_use_wire = ($campaign->can_use_wire($_SESSION['redirect_current_amount_part']));
	//Possible de régler par chèque ?
	$can_use_check = ($campaign->can_use_check($_SESSION['redirect_current_amount_part']));
	$user_is_lemonway_registered = FALSE;
	if ($can_use_wire) {
		$wdg_current_user = WDGUser::current();
		$user_is_lemonway_registered = $wdg_current_user->is_lemonway_registered();
	}
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
			
			<?php if ($can_use_wire): ?>
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>wire" <?php if (!$user_is_lemonway_registered): ?>class="alert-confirm" data-alertconfirm="<?php _e("Attention : pour investir via un virement bancaire, vous devrez nous fournir une copie de votre pi&egrave;ce d'identit&eacute; et un justificatif de domicile.", 'yproject'); ?>"<?php endif; ?>>
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-virement.jpg" alt="<?php _e("Virement bancaire", 'yproject'); ?>" />
					<?php _e("Virement bancaire", 'yproject'); ?> <?php _e("(une copie de votre pi&egrave;ce d'identit&eacute; et un justificatif de domicile seront n&eacute;cessaires)", 'yproject'); ?>
				</a>
			</li>
			<?php elseif ($campaign->can_use_wire_remaining_time() && !$campaign->can_use_wire_amount($_SESSION['redirect_current_amount_part'])): ?>
			<li>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-virement.jpg" alt="<?php _e("Virement bancaire", 'yproject'); ?>" />
				<?php echo sprintf( __("Les virements bancaires sont autoris&eacute;s &agrave; partir de %s &euro; d'investissement", 'yproject'), ATCF_Campaign::$invest_amount_min_wire); ?>
			</li>
			<?php endif; ?>
			
			<?php if ($can_use_check): ?>
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>check">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-cheque.jpg" alt="<?php _e("Ch&egrave;que", 'yproject'); ?>" />
					<?php _e("Ch&egrave;que", 'yproject'); ?>
				</a>
			</li>
			<?php else: ?>
			<li>
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-cheque.jpg" alt="<?php _e("Ch&egrave;que", 'yproject'); ?>" />
				<?php echo sprintf( __("Les paiements par ch&egrave;ques sont autoris&eacute;s &agrave; partir de %s &euro; d'investissement", 'yproject'), ATCF_Campaign::$invest_amount_min_check); ?>
			</li>
			<?php endif; ?>
			<div class="clear"></div>
		</ul>
	<?php endif; ?>

<?php endif;