<?php
global $campaign, $payment_url;
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
	?>
						
	<?php
	global $current_breadcrumb_step; $current_breadcrumb_step = 3;
	locate_template( 'invest/breadcrumb.php', true );
	?>

	<?php if (!empty($payment_url)): ?>
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
			<?php if ($can_use_wire) { ?>
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>wire">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-virement.jpg" alt="<?php _e("Virement bancaire", 'yproject'); ?>" />
					<?php _e("Virement bancaire", 'yproject'); ?>
				</a>
			</li>
			<?php } ?>
			<?php if ($can_use_check) { ?>
			<li>
				<a href="<?php echo $page_mean_payment_link; ?>check">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/paiement-cheque.jpg" alt="<?php _e("Ch&egrave;que", 'yproject'); ?>" />
					<?php _e("Ch&egrave;que", 'yproject'); ?>
				</a>
			</li>
			<?php } ?>
			<div class="clear"></div>
		</ul>
	<?php endif; ?>

	<div class="align-center mangopay-image"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" alt="Bandeau Mangopay" /></div>

<?php endif;