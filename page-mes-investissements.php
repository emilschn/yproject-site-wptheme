<?php 
/**
 * Enregistrement fait dans le plugin pour gérer la redirection éventuelle au bon moment
 * Le reste de la page devrait être fait dans un shortcode. On verra ça plus tard.
 */ 
if (!is_user_logged_in()) wp_redirect(site_url());

require_once("wp-content/themes/yproject/common.php");
?>
<?php get_header(); ?>

    <div id="content">
	<div class="padder_more">
	    <?php printUserProfileAdminBar(true);  ?>
	    <div class="center">
		<?php 
		if (is_user_logged_in()) :
		?>
		    <h2><?php _e( 'Mes investissements', 'yproject' ); ?></h2>

		    <?php
		    if (is_user_logged_in()) {
			echo 'Vous disposez de ' . ypcf_mangopay_get_user_personalamount_by_wpid(get_current_user_id()) . edd_get_currency() . ' dans votre porte-monnaie.';
		    }


		    $purchases = edd_get_users_purchases(bp_current_user_id());
		    if ( $purchases ) : ?>
			    <table id="edd_user_history">
				    <thead>
					    <tr class="edd_purchase_row">
						    <?php do_action('edd_purchase_history_header_before'); ?>
						    <th class="edd_purchase_date"><?php _e('Date', 'edd'); ?></th>
						    <th class="edd_purchase_project"><?php _e('Project', 'edd'); ?></th>
						    <th class="edd_purchase_status"><?php _e('Status', 'edd'); ?></th>
						    <?php do_action('edd_purchase_history_header_after'); ?>
					    </tr>
				    </thead>
				    <?php foreach ( $purchases as $post ) : setup_postdata( $post ); ?>
					    <?php $purchase_data = edd_get_payment_meta( $post->ID ); ?>

					    <tr class="edd_purchase_row">
						    <?php do_action( 'edd_purchase_history_row_start', $post->ID, $purchase_data ); ?>
						    <td class="edd_purchase_date"><?php echo date_i18n( get_option('date_format'), strtotime( get_post_field( 'post_date', $post->ID ) ) ); ?></td>
						    <td class="edd_purchase_project">
							    <?php 
							    $downloads = edd_get_payment_meta_downloads($post->ID); 
							    $download_id = '';
							    if (is_array($downloads[0])) $download_id = $downloads[0]["id"]; 
							    else $download_id = $downloads[0];

							    $post_camp = get_post($download_id);
							    $campaign = atcf_get_campaign( $post_camp );
							    echo '<a href="' . get_permalink($campaign->ID) . '">' . $post_camp->post_title . '</a>';
							    ?>
						    </td>
						    <td class="edd_purchase_status">
							<?php echo edd_get_payment_status( $post, true ); ?>
						    </td>
						    <?php do_action( 'edd_purchase_history_row_end', $post->ID, $purchase_data ); ?>
					    </tr>
				    <?php endforeach; ?>
			    </table>
		    <?php endif; ?>
		<?php
		endif;
		?>
	    </div>
	</div>
    </div>

<?php get_footer(); ?>