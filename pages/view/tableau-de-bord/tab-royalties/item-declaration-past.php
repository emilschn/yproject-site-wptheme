<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $declaration;
$adjustments = $declaration->get_adjustments();
$class_status = ( $declaration->get_status == WDGROIDeclaration::$status_failed ) ? 'error' : 'confirm';

$form_bill = $page_controler->get_form_declaration_bill( $declaration->id );
$fields_hidden = $form_bill->getFields( WDG_Form_Declaration_Bill::$field_group_hidden );
$fields_file = $form_bill->getFields( WDG_Form_Declaration_Bill::$field_group_file );

$nb_fields = $page_controler->get_campaign()->get_turnover_per_declaration();
$months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
?>

<div id="declaration-item-<?php echo $declaration->id; ?>" class="declaration-item">
	<div class="single-line">
		<?php echo $declaration->get_formatted_date( 'due' ); ?>
	</div>
	<div class="single-line <?php echo $class_status; ?>">
		<?php if ( $declaration->get_status == WDGROIDeclaration::$status_failed ): ?>
			<?php _e( "Annul&eacute;", 'yproject' ); ?>
		<?php else: ?>
			<?php _e( "Effectu&eacute;", 'yproject' ); ?>
		<?php endif; ?>
	</div>
	<div class="align-center">
		<?php _e( "Chiffre d'affaires d&eacute;clar&eacute; :", 'yproject' ); ?><br>
		<span class="amount"><?php echo UIHelpers::format_number( $declaration->get_turnover_total() ); ?> &euro;</span>
	</div>
	<div class="align-center">
		<?php _e( "Montant pay&eacute; :", 'yproject' ); ?><br>
		<span class="amount"><?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro;</span>
	</div>
	<div class="align-right single-line">
		<?php if ( $declaration->is_checked_by_adjustments() ): ?>
			<?php _e( "V&eacute;rifi&eacute;", 'yproject' ); ?>
		<?php else: ?>
			<?php _e( "&Agrave; v&eacute;rifier", 'yproject' ); ?>
		<?php endif; ?>
	</div>
</div>



<div id="declaration-item-more-<?php echo $declaration->id; ?>" class="declaration-item-more hidden">
	<hr>
	
	<div class="db-form v3 center align-left">
		
		<?php $declared_by_info = $declaration->get_declared_by(); ?>
		<?php if ( !empty( $declared_by_info ) && isset( $declared_by_info[ 'name' ] ) ): ?>
			<?php _e( "D&eacute;claration r&eacute;alis&eacute;e par :", 'yproject' ); ?> <?php echo $declaration->get_declared_by()[ 'name' ]. ' (' .$declaration->get_declared_by()[ 'status' ]. ')'; ?>
		<?php endif; ?>
		<br><br>
		
		<strong><?php echo sprintf( __( "Chiffre d'affaires d&eacute;clar&eacute; (pr&eacute;visionnel : %s &euro;) :", 'yproject' ), $declaration->get_estimated_turnover() ); ?></strong><br>
		
		<table>
			<?php $declaration_turnover = $declaration->get_turnover(); ?>
			<?php if ( $nb_fields > 1 ): ?>
				<?php
				$date_due = new DateTime( $declaration->date_due );
				$date_due->sub( new DateInterval( 'P' .$nb_fields. 'M' ) );
				?>
				<?php for ( $i = 0; $i < $nb_fields; $i++ ): ?>
					<tr>
						<td><?php echo ucfirst( __( $months[ $date_due->format( 'm' ) - 1 ] ) ); ?> :</td>
						<td><?php echo UIHelpers::format_number( $declaration_turnover[ $i ] ); ?> &euro;</td>
					</tr>
					<?php $date_due->add( new DateInterval( 'P1M' ) ); ?>
				<?php endfor; ?>
			<?php endif; ?>
				
			<tr class="strong">
				<td><?php _e( "Total du chiffre d'affaires d&eacute;clar&eacute; :", 'yproject' ); ?></td>
				<td><?php echo UIHelpers::format_number( $declaration->get_turnover_total() ); ?> &euro;</td>
			</tr>
			<?php if ( !empty( $adjustments ) ): ?>
				<tr>
					<td><?php echo sprintf( __( "Royalties sur le chiffre d'affaires d&eacute;clar&eacute; (%s) :", 'yproject' ), UIHelpers::format_number( $page_controler->get_campaign()->roi_percent_remaining() ) . ' %' ); ?></td>
					<td><?php echo UIHelpers::format_number( $declaration->get_amount_royalties() ); ?> &euro;</td>
				</tr>
			<?php endif; ?>
		</table>
		<br><br>
		
		<?php if ( !empty( $adjustments ) ): ?>
			<strong><?php _e( "Ajustement", 'yproject' ); ?></strong><br>
			<?php foreach ( $adjustments as $adjustment_obj ): ?>
				<?php echo $adjustment_obj->message_organization; ?><br>
				<table>
					<tr>
						<td><?php _e( "Diff&eacute;rentiel de CA constat&eacute; lors de l'ajustement :", 'yproject' ); ?></td>
						<td><?php echo UIHelpers::format_number( $adjustment_obj->turnover_difference ); ?> &euro;</td>
					</tr>
					<tr>
						<td><?php _e( "Montant de l'ajustement :", 'yproject' ); ?></td>
						<td><?php echo UIHelpers::format_number( $adjustment_obj->amount ); ?> &euro;</td>
					</tr>
				</table>
				<br>
			<?php endforeach; ?>
			<br>
		<?php endif; ?>
			
		<table>
			<tr class="strong">
				<td><?php _e( "Total de royalties vers&eacute;es :", 'yproject' ); ?></td>
				<td><?php echo UIHelpers::format_number( $declaration->get_amount_with_adjustment() ); ?> &euro;</td>
			</tr>
			<?php if ( $declaration->get_commission_to_pay() > 0 ): ?>
				<tr>
					<td>
						<?php _e( "Frais de gestion (", 'yproject' ); ?>
						<?php echo $declaration->get_percent_commission_without_tax() . ' % HT'; ?>
						<?php if ( $page_controler->get_campaign()->get_minimum_costs_to_organization() > 0 ): ?>
							<?php echo ', min. ' .( $page_controler->get_campaign()->get_minimum_costs_to_organization() / 1.2 ). ' &euro;'; ?>
						<?php endif; ?>
						<?php _e( ") :", 'yproject' ); ?>
					</td>
					<td><?php echo UIHelpers::format_number( $declaration->get_commission_to_pay_without_tax() ); ?> &euro;</td>
				</tr>
				<tr>
					<td><?php _e( "TVA sur frais de gestion (20 %) :", 'yproject' ); ?></td>
					<td><?php echo UIHelpers::format_number( $declaration->get_commission_tax() ); ?> &euro;</td>
				</tr>
			<?php endif; ?>
			<tr class="strong">
				<td><?php _e( "Montant total pay&eacute; :", 'yproject' ); ?></td>
				<td><?php echo UIHelpers::format_number( $declaration->get_amount_with_commission() ); ?> &euro;</td>
			</tr>
			<tr>
				<td><?php _e( "Reliquat non vers&eacute; aux investisseurs :", 'yproject' ); ?></td>
				<td><?php echo UIHelpers::format_number( $declaration->remaining_amount ); ?> &euro;</td>
			</tr>
		</table>
		<br><br>
		
		<strong><?php _e( "Message transmis aux investisseurs :", 'yproject' ); ?></strong><br>
		<?php if ( empty( $declaration->message ) ): ?>
			Aucun message n'a été envoyé aux investisseurs.
		<?php else: ?>
			<?php echo $declaration->get_message(); ?>
		<?php endif; ?>
		<br><br>
		
		<strong><?php _e( "Nombre de salari&eacute;s :", 'yproject' ); ?></strong><br>
		<?php echo $declaration->employees_number; ?>
		<br><br>
		
		<strong><?php _e( "Autres financements :", 'yproject' ); ?></strong><br>
		<?php echo $declaration->get_other_fundings(); ?>
		<br><br>
		
		<strong><?php _e( "Justificatifs :", 'yproject' ); ?></strong><br>
		<?php _e( "Vous retrouverez le d&eacute;tail des versements par personne dans le justificatif." ); ?>
		<br><br>
		
		<?php if ( $declaration->get_amount_with_commission() > 0 ): ?>
			<?php if ( $declaration->get_amount_with_adjustment() > 0 ): ?>
				<?php $declaration->make_payment_certificate(); ?>
				<a href="<?php echo $declaration->get_payment_certificate_url(); ?>" target="_blank" class="button blue-pale" download="justificatif-<?php echo $declaration->date_due; ?>"><?php _e( "T&eacute;l&eacute;charger le justificatif" ); ?></a>
				<br><br>
			<?php endif; ?>
			
			<?php if ( $declaration->get_commission_to_pay() > 0 ): ?>
				<?php if ( $page_controler->can_access_admin() ): ?>
					<form action="<?php echo admin_url( 'admin-post.php?action=generate_royalties_bill'); ?>" method="POST" class="db-form v3 admin-theme-block">
						<input type="hidden" name="campaign_id" value="<?php echo $page_controler->get_campaign_id(); ?>">
						<input type="hidden" name="roi_declaration_id" value="<?php echo $declaration->id; ?>">
						<button type="submit" class="button admin-theme"><?php _e( "G&eacute;n&eacute;rer la facture", 'yproject' ); ?></button>
					</form>
					<br><br>
				<?php endif; ?>

				<form action="<?php echo $page_controler->get_form_declaration_bill_action(); ?>" method="post" enctype="multipart/form-data" class="db-form v3 full">

					<?php foreach ( $fields_hidden as $field ): ?>
						<?php global $wdg_current_field; $wdg_current_field = $field; ?>
						<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
					<?php endforeach; ?>

					<?php foreach ( $fields_file as $field ): ?>
						<?php global $wdg_current_field; $wdg_current_field = $field; ?>
						<?php locate_template( array( 'common/forms/field.php' ), true, false );  ?>
					<?php endforeach; ?>

					<?php if ( $page_controler->can_access_admin() ): ?>
						<button type="submit" class="button admin-theme clear"><?php _e( "Enregistrer", 'yproject' ); ?></button>
					<?php endif; ?>

					<div class="clear"></div>

				</form>
				<br><br>
			<?php endif; ?>
			
		<?php else: ?>
			Aucun paiement effectué.
		<?php endif; ?>
		<br><br>
		
	</div>
</div>

<div id="declaration-item-more-btn-<?php echo $declaration->id; ?>" class="declaration-item-more-btn align-center">
	<button class="button transparent" data-declaration="<?php echo $declaration->id; ?>">+</button>
</div>