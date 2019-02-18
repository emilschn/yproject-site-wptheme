<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $WDGOrganization;
?>
<h2><?php _e( "Documents de", 'yproject' ); ?>  <?php echo $WDGOrganization->get_name(); ?></h2>

<h3><?php _e( "Attestations de transactions annuelles", 'yproject' ); ?></h3>
<?php
$has_declaration = false;
$date_now = new DateTime();
?>
<?php for( $year = 2016; $year < $date_now->format('Y'); $year++ ): ?>
	<?php if ( $WDGOrganization->has_royalties_for_year( $year ) ): ?>
		<?php
		$has_declaration = true;
		$declaration_url = $WDGOrganization->get_royalties_certificate_per_year( $year );
		?>
		<a href="<?php echo $declaration_url; ?>" download="attestation-royalties-<?php echo $year; ?>.pdf" class="button red">Télécharger l'attestation <?php echo $year; ?></a><br /><br />
	<?php endif; ?>
<?php endfor; ?>
<?php if ( !$has_declaration ): ?>
	<?php _e( "Aucune", 'yproject' ); ?>
<?php endif; ?>


<?php if ( $page_controler->has_tax_documents( $WDGOrganization->get_wpref() ) ): ?>
<h3><?php _e( "Attestations IFU (Imprim&eacute; Fiscal Unique - 2561)", 'yproject' ); ?></h3>
<?php $tax_documents = $page_controler->get_tax_documents( $WDGOrganization->get_wpref() ); ?>
<?php foreach( $tax_documents as $year => $document_path ): ?>
	<a href="<?php echo $document_path; ?>" download="ifu-<?php echo $year; ?>.pdf" class="button blue-pale download-certificate">Télécharger l'attestation <?php echo $year; ?></a>
	<br><br>
<?php endforeach; ?>
<?php endif; ?>