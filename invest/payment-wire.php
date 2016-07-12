<?php
global $campaign;
if (!isset($campaign)) {
	$campaign = atcf_get_current_campaign();
}

if (isset($campaign)): ?>

	<?php if (isset($_GET['meanofpayment']) && $_GET['meanofpayment'] == 'wire'): ?>
		<?php
		$page_payment_done = get_page_by_path('paiement-effectue');
		global $current_breadcrumb_step; $current_breadcrumb_step = 3;
		locate_template( 'invest/breadcrumb.php', true );
		?>

		<?php _e("Afin de proc&eacute;der au virement, voici les informations bancaires dont vous aurez besoin :", 'yproject'); ?><br />
		<ul>
			<?php if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay): ?>
				<?php $mangopay_contribution = ypcf_mangopay_get_withdrawalcontribution_by_id($_REQUEST["ContributionID"]); ?>
				<li><strong><?php _e("Titulaire du compte :", 'yproject'); ?></strong> <?php echo $mangopay_contribution->BankAccountOwner; ?></li>
				<li><strong>IBAN :</strong> <?php echo $mangopay_contribution->BankAccountIBAN; ?></li>
				<li><strong>BIC :</strong> <?php echo $mangopay_contribution->BankAccountBIC; ?></li>
				<li>
					<strong><?php _e("Code unique (pour identifier votre paiement) :", 'yproject'); ?></strong> <?php echo $mangopay_contribution->GeneratedReference; ?><br />
					<ul>
						<li><?php _e("Indiquez imp&eacute;rativement ce code comme 'libell&eacute; b&eacute;n&eacute;ficiaire' ou 'code destinataire' au moment du virement !", 'yproject'); ?></li>
					</ul>
				</li>
				
			<?php elseif ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_lemonway): ?>
				<?php
				$WDGUser_current = WDGUser::current();
				$WDGUser_current->register_lemonway();
				?>
				<li><strong><?php _e("Titulaire du compte :", 'yproject'); ?></strong> LEMON WAY</li>
				<li><strong>IBAN :</strong> FR76 3000 4025 1100 0111 8625 268</li>
				<li><strong>BIC :</strong> BNPAFRPPIFE</li>
				<li>
					<strong><?php _e("Code &agrave; indiquer (pour identifier votre paiement) :", 'yproject'); ?></strong> wedogood-<?php echo $WDGUser_current->get_lemonway_id(); ?><br />
					<ul>
						<li><?php _e("Indiquez imp&eacute;rativement ce code comme 'libell&eacute; b&eacute;n&eacute;ficiaire' ou 'code destinataire' au moment du virement !", 'yproject'); ?></li>
					</ul>
				</li>
				
			<?php endif; ?>
		</ul>
		<br /><br />
	
		<?php _e("Une fois le virement effectu&eacute;, cliquez sur", 'yproject'); ?><br /><br />
		<?php if ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_mangopay): ?>
			<a href="<?php echo get_permalink($page_payment_done->ID) . '?ContributionID=' . $_REQUEST["ContributionID"] . '&campaign_id=' . $campaign->ID . '&meanofpayment=wire'; ?>" class="button"><?php _e("SUIVANT", 'yproject'); ?></a><br /><br />
			<div class="align-center mangopay-image"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" alt="Bandeau Mangopay" /></div>
		
		<?php elseif ($campaign->get_payment_provider() == ATCF_Campaign::$payment_provider_lemonway): ?>
			<a href="<?php echo get_permalink($page_payment_done->ID) . '?campaign_id=' . $campaign->ID . '&meanofpayment=wire'; ?>" class="button"><?php _e("SUIVANT", 'yproject'); ?></a><br /><br />

		<?php endif; ?>
		
		<hr />
		<?php _e("Exemple de saisie du code destinataire sur diff&eacute;rentes banques :", 'yproject'); ?><br /><br />
		<div class="align-center"><img src="<?php echo home_url(); ?>/wp-content/plugins/appthemer-crowdfunding/includes/ui/shortcodes/capture-lbp.png" /></div><br /><br />

	<?php else: ?>
		Error YPSIPW001 : <?php _e("Probl&egrave;me de page.", 'yproject'); ?>
		
	<?php endif; ?>
	
<?php endif;