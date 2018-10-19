<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author, $declaration;
?>
			
<?php
$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$nb_fields = $campaign->get_turnover_per_declaration();
$declaration_turnover = $declaration->get_turnover();

$replaces_original = array( '<br />', '\'', '
');
$replaces_final = array( '', '\\\'', '\n' );
$declaration_message = str_replace( $replaces_original, $replaces_final, $declaration->get_message() );

$date_due = new DateTime( $declaration->date_due );
$declaration_month_num = $date_due->format( 'n' );
$declaration_year = $date_due->format( 'Y' );
$date_due->sub( new DateInterval( 'P'.$nb_fields.'M' ) );
// Si l'année de déclaration est différente de la première date de déclaration,
	// on recule d'une année
if ( $declaration_year > $date_due->format( 'Y' ) ) {
	$declaration_year--;
}
$list_months = '';
$list_months_with_turnover = '';
for ($i = 0; $i < $nb_fields; $i++) {
	if ( !empty( $list_months ) ) {
		$list_months .= ', ';
		$list_months_with_turnover .= '\n';
	}
	// Si on est en janvier, et que ce n'est pas la première déclaration,
		// on avance d'une année
	if ( $i > 0 && $date_due->format( 'n' ) == 1 ) {
		$declaration_year++;
	}
	$list_months .= ucfirst( __( $months[ $date_due->format('m') - 1 ] ) ) . ' ' . $declaration_year;
	$list_months_with_turnover .= ucfirst( __( $months[ $date_due->format('m') - 1 ] ) ) . ' ' . $declaration_year;
	$list_months_with_turnover .= ' : ' .UIHelpers::format_number( $declaration_turnover[$i] ). ' &euro; HT';
	$date_due->add( new DateInterval( 'P1M' ) );
}
?>

<tr>
	<?php // Date d'échéance ?>
	<td><?php echo $declaration->date_due; ?></td>
	
	<?php // Mois concernés pour la déclaration de CA ?>
	<td>
		<?php echo $list_months; ?>
	</td>
	
	<?php // Total du CA déclaré ?>
	<td>
		<?php if ( $declaration->get_status() != WDGROIDeclaration::$status_declaration ): ?>
			<?php $turnover_total = $declaration->get_turnover_total(); ?>
			<?php echo UIHelpers::format_number( $turnover_total ); ?> &euro;
			<a href="#wallet" onclick="alert('<?php echo $list_months_with_turnover; ?>');">+</a>
		<?php endif; ?>
	</td>
	
	<?php // Total des royalties ?>
	<td>
		<?php if ( $declaration->get_status() != WDGROIDeclaration::$status_declaration ): ?>
			<?php
			$amount = $declaration->get_amount_with_commission();
			$commission = $declaration->get_commission_to_pay();
			?>
			<?php echo UIHelpers::format_number( $amount ); ?> &euro;
			<?php if ( $commission > 0 ): ?>
				<br />
				(dont commission : <?php echo UIHelpers::format_number( $commission ); ?> &euro;)
			<?php endif; ?>
		<?php endif; ?>
	</td>
	
	<?php // Message de royalties ?>
	<td>
		<?php if ( $declaration->get_status() != WDGROIDeclaration::$status_declaration ): ?>
			<?php if ( empty( $declaration_message ) ): ?>
				<?php _e( "Aucun message", 'yproject' ); ?>
			<?php else: ?>
				<a href="#wallet" onclick="alert('<?php echo $declaration_message; ?>');"><?php _e( "Voir le message", 'yproject' ); ?></a>
			<?php endif; ?>
		<?php endif; ?>
	</td>
	
	<?php // Etat ?>
	<td>
		<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_declaration ): ?>
			<?php _e( "En cours", 'yproject' ); ?>
		
		<?php elseif ( $declaration->get_status() == WDGROIDeclaration::$status_payment ): ?>
			<?php _e( "En attente de paiement", 'yproject' ); ?>
		
		<?php elseif ( $declaration->get_status() == WDGROIDeclaration::$status_waiting_transfer ): ?>
			<?php _e( "En attente de virement", 'yproject' ); ?>
		
		<?php elseif ( $declaration->get_status() == WDGROIDeclaration::$status_transfer ): ?>
			<?php _e( "En cours de versement", 'yproject' ); ?>
		
		<?php elseif ( $declaration->get_status() == WDGROIDeclaration::$status_finished ): ?>
			<?php _e( "Valid&eacute;e", 'yproject' ); ?>
		
		<?php endif; ?>
	</td>
	
	<?php // Info ajustement ?>
	<td></td>
	
	<?php // Montant ajustement ?>
	<td></td>
	
	<?php // Justificatif ?>
	<td>
		<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_finished ): ?>
			<?php $declaration->make_payment_certificate(); ?>
			<a href="<?php echo $declaration->get_payment_certificate_url(); ?>" target="_blank" class="button blue"><?php _e( "T&eacute;l&eacute;charger", 'yproject' ); ?></a>
		<?php endif; ?>
	</td>
	
	<?php // Facture ?>
	<td>
		<?php if ( $declaration->get_status() == WDGROIDeclaration::$status_finished ): ?>
		<?php if ( $is_admin ): ?>
		<form action="<?php echo admin_url( 'admin-post.php?action=generate_royalties_bill'); ?>" method="POST" class="align-center admin-theme-block">
			/!\ Attention : assurez-vous que la facture n'a pas encore été générée sur l'outil pour ne pas créer de doublon. /!\<br>
			<input type="hidden" name="roi_declaration_id" value="<?php echo $declaration->id; ?>">
			<input type="hidden" name="campaign_id" value="<?php echo $campaign_id; ?>">
			<button class="button"><?php _e( "G&eacute;n&eacute;rer la facture", 'yproject' ); ?></button>
		</form>
		<?php else: ?>
		A venir
		<?php endif; ?>
		<?php endif; ?>
	</td>
			
</tr>
