<div class="right">
	<a href="<?php echo home_url('/creer-une-organisation/'); ?>" class="button right">Cr&eacute;er une organisation</a>
</div>
<br /><br />

<?php
$WDGUser_current = WDGUser::current();
$organizations_list = $WDGUser_current->get_organizations_list();
?>

<?php if ( !empty( $organizations_list ) ) : ?>

	<?php foreach ($organizations_list as $organization_item): ?>

		<?php $organization_obj = new WDGOrganization( $organization_item->wpref ); ?>

		<h2 class="underlined"><?php echo $organization_item->name; ?></h2>
		
		<strong><?php _e( "Montant dans votre porte-monnaie &eacute;lectronique :", 'yproject' ); ?></strong>
			<?php echo UIHelpers::format_number( $organization_obj->get_lemonway_balance() ); ?> &euro;
			(<?php _e( "Pour retirer l'argent sur votre porte-monnaie, contactez-nous sur investir@wedogood.co", 'yproject' ); ?>)
			<br />
		
		<ul>
		<?php
		$has_declaration = false;
		$date_now = new DateTime();
		?>
		<?php for( $year = 2016; $year < $date_now->format('Y'); $year++ ): ?>
			<?php if ( $organization_obj->has_royalties_for_year( $year ) ): ?>
				<?php
				$has_declaration = true;
				$declaration_url = $organization_obj->get_royalties_certificate_per_year( $year );
				?>
				<li style="list-style: none;"><a href="<?php echo $declaration_url; ?>" download="attestation-royalties-<?php echo $year; ?>.pdf" class="button red">Télécharger l'attestation <?php echo $year; ?></a><br /><br /></li>
			<?php endif; ?>
		<?php endfor; ?>
		</ul>

	<?php endforeach; ?>

<?php else: ?>
	<?php _e( "Vous n'avez pas encore cr&eacute;&eacute; d'organisation.", 'yproject'); ?>

<?php endif; ?>

<br /><br />