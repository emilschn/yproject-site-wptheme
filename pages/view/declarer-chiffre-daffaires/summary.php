<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
$summary_data = $page_controler->get_summary_data();
?>

<div class="center declaration-summary">
	<table border="0">
		<tr class="bold">
			<td colspan="2"><?php echo sprintf( __( "Chiffre d'affaires d&eacute;clar&eacute; (pr&eacute;visionnel : %s &euro;)", 'yproject' ), $summary_data[ 'amount_estimated' ] ); ?></td>
		</tr>

		<?php foreach ( $summary_data[ 'turnover_by_month' ] as $month_name => $month_turnover_amount ): ?>
			<tr>
				<td><?php echo $month_name. ' : '; ?></td>
				<td class="align-right"><?php echo YPUIHelpers::display_number( $month_turnover_amount ); ?> &euro;</td>
			</tr>
		<?php endforeach; ?>

		<tr class="bold">
			<td><?php _e( "Total du chiffre d'affaires d&eacute;clar&eacute; :", 'yproject' ); ?></td>
			<td class="align-right"><?php echo YPUIHelpers::display_number( $summary_data[ 'turnover_total' ] ); ?> &euro;</td>
		</tr>

		<?php if ( !empty( $summary_data[ 'amount_royalties' ] ) ): ?>
			<tr>
				<td><?php echo sprintf( __( "Royalties sur le chiffre d'affaires d&eacute;clar&eacute; (%s %s) :", 'yproject' ), YPUIHelpers::display_number( $summary_data[ 'percent_royalties' ] ), '%' ); ?></td>
				<td class="align-right"><?php echo YPUIHelpers::display_number( $summary_data[ 'amount_royalties' ] ); ?> &euro;</td>
			</tr>
		<?php endif; ?>

		<tr><td colspan="2"><br></td></tr>

		<?php if ( !empty( $summary_data[ 'amount_adjustment' ] ) ): ?>
			<tr class="bold">
				<td colspan="2"><?php _e( "Ajustement", 'yproject' ); ?></td>
			</tr>

			<tr>
				<td><?php _e( "Montant de l'ajustement :", 'yproject' ); ?></td>
				<td class="align-right"><?php echo YPUIHelpers::display_number( $summary_data[ 'amount_adjustment' ] ); ?> &euro;</td>
			</tr>

			<tr><td colspan="2"><br></td></tr>
		<?php endif; ?>


		<?php if ( !empty( $summary_data[ 'amount_royalties_with_adjustment' ] ) ): ?>
			<tr class="bold">
				<td><?php _e( "Total de royalties &agrave; verser :", 'yproject' ); ?></td>
				<td class="align-right"><?php echo YPUIHelpers::display_number( $summary_data[ 'amount_royalties_with_adjustment' ] ); ?> &euro;</td>
			</tr>
		<?php endif; ?>

		<?php if ( !empty( $summary_data[ 'commission_without_tax' ] ) ): ?>
			<tr>
				<td>
					<?php
					echo sprintf( __( "Frais de gestion (%s %s HT", 'yproject' ), YPUIHelpers::display_number( $summary_data[ 'commission_percent_without_tax' ] ), '%' );
					if ( !empty( $summary_data[ 'minimum_commission_without_tax' ] ) ):
						echo sprintf( __( ", min. %s € HT", 'yproject' ), YPUIHelpers::display_number( $summary_data[ 'minimum_commission_without_tax' ] ) );
					endif;
					_e( ") :", 'yproject' );
					?>
				</td>
				<td class="align-right"><?php echo YPUIHelpers::display_number( $summary_data[ 'commission_without_tax' ] ); ?> &euro;</td>
			</tr>
		<?php endif; ?>

		<?php if ( !empty( $summary_data[ 'commission_tax' ] ) ): ?>
			<tr>
				<td><?php _e( "TVA sur frais de gestion (20 %) :", 'yproject' ); ?></td>
				<td class="align-right"><?php echo YPUIHelpers::display_number( $summary_data[ 'commission_tax' ] ); ?> &euro;</td>
			</tr>
		<?php endif; ?>

		<tr class="bold">
			<td><?php _e( "Montant total &agrave; r&eacute;gler :", 'yproject' ); ?></td>
			<td class="align-right"><?php echo YPUIHelpers::display_number( $summary_data[ 'amount_to_pay' ] ); ?> &euro;</td>
		</tr>
	</table>
	<br>

	<h2><?php _e( "Message &agrave; transmettre aux investisseurs :", 'yproject' ); ?></h2>
	<?php $message = $summary_data[ 'message' ]; ?>
	<?php if ( empty( $message ) ): ?>
		<?php _e( "Aucun message", 'yproject' ); ?>
	<?php else: ?>
		<?php echo $message; ?>
	<?php endif; ?>
</div>

<form action="<?php echo $page_controler->get_form_action(); ?>" method="post" class="db-form v3 full bg-white">
	
	<button type="submit" name="action" value="gotopayment" class="button red">
	<?php if ( $page_controler->is_card_shortcut_displayed() ): ?>
		<?php _e( "Valider et payer par carte", 'yproject' ); ?>
	<?php else: ?>
		<?php _e( "Valider et payer par pr&eacute;l&eacute;vement", 'yproject' ); ?>
	<?php endif; ?>
	</button>
	<div class="clear"><br></div>
	
	<button type="submit" name="action" value="gobacktodeclaration" class="button half left transparent"><?php _e( "Pr&eacute;c&eacute;dent", 'yproject' ); ?></button>
	<button type="submit" name="action" value="changepayment" class="button half right transparent"><?php _e( "Autre mode de paiement", 'yproject' ); ?></button>
	<div class="clear"></div>

</form>