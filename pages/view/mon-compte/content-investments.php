<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$WDGUser_displayed = $page_controler->get_current_user();
$list_current_organizations = $page_controler->get_current_user_organizations();
?>

<h2><?php _e( "Investissements de", 'yproject' ); ?> <?php echo $page_controler->get_user_name(); ?></h2>

<p>
	<?php _e( "Les informations ci-dessous sont celles de votre compte personnel.", 'yproject' ); ?><br>
	<?php if ( count( $list_current_organizations ) > 0 ): ?>
	<?php _e( "Retrouvez celles de vos organisations en utilisant le menu.", 'yproject' ); ?>
	<?php endif; ?>
</p>


<h3><?php _e( "Mes attestations de transactions annuelles", 'yproject' ); ?></h3>
<?php
$has_declaration = false;
$date_now = new DateTime();
?>
<?php for( $year = 2016; $year < $date_now->format('Y'); $year++ ): ?>
	<?php if ( $WDGUser_displayed->has_royalties_for_year( $year ) ): ?>
		<?php
		$has_declaration = true;
		$declaration_url = $WDGUser_displayed->get_royalties_certificate_per_year( $year );
		?>
		<a href="<?php echo $declaration_url; ?>" download="attestation-royalties-<?php echo $year; ?>.pdf" class="button blue-pale download-certificate">Télécharger l'attestation <?php echo $year; ?></a><br /><br />
	<?php endif; ?>
<?php endfor; ?>
<?php if ( !$has_declaration ): ?>
	<?php _e( "Aucune", 'yproject' ); ?>
<?php endif; ?>
<br><br>

<h3 class="to-hide-after-loading"><?php _e( "Mes investissements", 'yproject' ); ?></h3>
	
<div id="ajax-loader" class="center" style="text-align: center;"><img id="ajax-loader-img" src="<?php echo get_stylesheet_directory_uri() ?>/images/loading.gif" alt="chargement" /></div>

