<?php 
/**
 * Affichage de la liste des paiements : seulement aux utilisateurs admin
 */ 
if ( !current_user_can('manage_options') ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 ); exit();
}

require_once EDD_PLUGIN_DIR . 'includes/admin/payments/class-payments-table.php';

get_header(); 
?>

    <div id="content">
	<div class="padder">
	    <div class="center">
		<?php 
		if ( current_user_can('manage_options') ) :
		?>
		    <h2 class="underlined">Stats des paiements</h2>
		    <?php
		    global $wp_query;
		    $args = array();
		    $payment_count = edd_count_payments( $args );
		    ?>
		    <ul>
			<li>Paiements totaux : <?php echo ($payment_count->publish + $payment_count->pending + $payment_count->refunded + $payment_count->failed + $payment_count->abandoned + $payment_count->trash); ?></li>
			<li>Paiements acceptés : <?php echo $payment_count->publish; ?></li>
			<li>Paiements en attente : <?php echo $payment_count->pending; ?></li>
			<li>Paiements remboursés : <?php echo $payment_count->refunded; ?></li>
			<li>Paiements échoués : <?php echo $payment_count->failed; ?></li>
			<li>Paiements annulés : <?php echo $payment_count->revoked; ?></li>
			<li>Paiements abandonnés : <?php echo $payment_count->abandoned; ?></li>
			<li>Paiements supprimés : <?php echo $payment_count->trash; ?></li>
		    </ul>
		    
		    <h2 class="underlined">Liste des paiements</h2>
		    <table class="wp-list-table" cellspacing="0">
			<thead style="background-color: #CCC;">
			<tr>
			    <td style="max-width: 80px; overflow: hidden;">Utilisateur</td>
			    <td style="max-width: 100px; overflow: hidden;">Projet</td>
			    <td>Date</td>
			    <td>Montant</td>
			    <td>ID WP</td>
			    <td>Etat WP</td>
			    <td>Détail WP</td>
			    <td style="max-width: 80px; overflow: hidden;">ID MP</td>
			    <td>Terminé sur MP</td>
			    <td>Succes sur MP</td>
			</tr>
			</thead>

			<tfoot style="background-color: #CCC;">
			<tr>
			    <td style="max-width: 80px; overflow: hidden;">Utilisateur</td>
			    <td style="max-width: 100px; overflow: hidden;">Projet</td>
			    <td>Date</td>
			    <td>Montant</td>
			    <td>ID WP</td>
			    <td>Etat WP</td>
			    <td>Détail WP</td>
			    <td style="max-width: 80px; overflow: hidden;">ID MP</td>
			    <td>Terminé sur MP</td>
			    <td>Succes sur MP</td>
			</tr>
			</tfoot>

			<tbody id="the-list">
			    <?php
			    $payments_data = get_payments_data();
			    $i = -1;
			    foreach ( $payments_data as $item ) {
				$i++;
				if ($i % 2 == 0) $bgcolor = "#FFF";
				else $bgcolor = "#EEE";
				
				$user = get_user_by('id', $item['user']);
				$user_link = bp_core_get_userlink($item['user']);
				
				$downloads = edd_get_payment_meta_downloads($item['ID']); 
				$download_id = '';
				if (is_array($downloads[0])) $download_id = $downloads[0]["id"]; 
				else $download_id = $downloads[0];
				$post_campaign = get_post($download_id);
				$post_campaign_link = get_permalink($download_id);
				
				$post_invest = get_post($item['ID']);
				ypcf_get_updated_payment_status($item['ID']);
				
				$mangopay_id = edd_get_payment_key($item['ID']);
				$mangopay_contribution = ypcf_mangopay_get_contribution_by_id($mangopay_id);
				$mangopay_is_completed = (isset($mangopay_contribution->IsCompleted) && $mangopay_contribution->IsCompleted) ? 'Oui' : 'Non';
				$mangopay_is_succeeded = (isset($mangopay_contribution->IsSucceeded) && $mangopay_contribution->IsSucceeded) ? 'Oui' : 'Non';
				
				?>
				<tr style="background-color: <?php echo $bgcolor; ?>">
				    <td style="max-width: 80px; overflow: hidden;"><?php echo $user_link; ?></td>
				    <td style="max-width: 100px; overflow: hidden;"><a href="<?php echo $post_campaign_link; ?>"><?php echo $post_campaign->post_title; ?></a></td>
				    <td><?php echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $item['ID'] ) ) ); ?></td>
				    <td><?php echo $item['amount']; ?></td>
				    <td><?php echo $item['ID']; ?></td>
				    <td><?php echo edd_get_payment_status( $post_invest, true ); ?></td>
				    <td><a href="<?php echo add_query_arg( 'id', $item['ID'], admin_url( 'edit.php?post_type=download&page=edd-payment-history&view=view-order-details' ) ); ?>">Détails</a></td>
				    <td style="max-width: 80px; overflow: hidden;"><?php echo $mangopay_id; ?></td>
				    <td><?php echo $mangopay_is_completed; ?></td>
				    <td><?php echo $mangopay_is_succeeded; ?></td>
				</tr>
				<?php
			    }
			    ?>
			</tbody>
		    </table>
		    
		    <?php
		endif;
		?>
	    </div>
	</div>
    </div>

<?php get_footer(); ?>