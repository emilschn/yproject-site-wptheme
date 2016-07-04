<?php
/**
 * Affichage des investissements de l'utilisateur
 */
$WDGUser_current = WDGUser::current();
$user_investments = $WDGUser_current->get_validated_investments();
?>

<?php if ( empty( $user_investments ) ): ?>
	<?php _e( "Vous n'avez encore aucun investissement valide sur le site.", 'yproject' ); ?>

<?php else: ?>

	<?php foreach ( $user_investments as $campaign_id => $campaign_investments ): ?>

		<?php if ( !empty($campaign_id) ): ?>
		
			<?php $campaign = atcf_get_campaign( $campaign_id ); ?>

			<?php if ( !empty ($campaign) && !empty ( $campaign->data ) ): ?>
			<div class="user-roi-item">

				<a href="<?php echo get_permalink( $campaign_id ); ?>"><h3><?php echo $campaign->data->post_title; ?></h3></a>
				
				
				<?php
				/**
				 * Liste des investissements
				 */
				?>
				<?php if ( !empty ($campaign_investments ) ): ?>
				<h4><?php _e("Liste de vos investissements", 'yproject'); ?></h4>
				
				<ul>
					<?php foreach ($campaign_investments as $investment_id): ?>
						<?php $payment_date = date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $investment_id ) ) ); ?>
						<?php $payment_amount = edd_get_payment_amount( $investment_id ); ?>
						<li><?php echo $payment_date; ?> : <?php echo $payment_amount; ?> &euro;</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
				
				
				<?php
				/**
				 * Liste des ROIs reçus
				 */
				?>
				<?php $roi_list = WDGROI::get_roi_list_by_campaign_user( $campaign_id, $WDGUser_current->wp_user->ID ); ?>
				
				<h4><?php _e("Royalties re&ccedil;ues", 'yproject'); ?></h4>
				<?php if ( !empty($roi_list) ): ?>
				
					<ul>
					<?php foreach ($roi_list as $roi): ?>
						<?php $roi_date = date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $roi->date_transfer ) ) ); ?>
						<li><?php echo $roi_date; ?> : <?php echo $roi->amount; ?> &euro;</li>
					<?php endforeach; ?>
					</ul>
				
				<?php else: ?>
					<span class="indent"><?php _e("Vous n'avez pas encore re&ccedil;u de royalties sur ce projet.", 'yproject'); ?></span>
				
				<?php endif; ?>
				
				
				<?php
				/**
				 * Liste des ROIs à venir
				 */
				?>
				<?php $future_roi_list = WDGROIDeclaration::get_list_by_campaign_id( $campaign_id ); ?>
				
				<h4><?php _e("Royalties &agrave; venir", 'yproject'); ?></h4>
				<?php if ( !empty($future_roi_list) ): ?>
				
					<ul>
					<?php foreach ($future_roi_list as $roi_declaration): ?>
						<?php if ($roi_declaration->status != WDGROIDeclaration::$status_finished): ?>
							<?php $roi_declaration_date = date_i18n( get_option('date_format'), strtotime( $roi_declaration->date_due ) ); ?>
							<li><?php echo $roi_declaration_date; ?></li>
						<?php endif; ?>
					<?php endforeach; ?>
					</ul>
				
				<?php else: ?>
					<span class="indent"><?php _e("Aucun versement n'a encore &eacute;t&eacute; programm&eacute;.", 'yproject'); ?></span>
				
				<?php endif; ?>
				
			</div>
			<?php endif; ?>

		<?php endif; ?>

	<?php endforeach; ?>

<?php endif;