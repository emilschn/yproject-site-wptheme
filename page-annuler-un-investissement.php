<?php get_header(); ?>

<div id="content">
	<div class="padder">
		
		<?php locate_template( array("common/basic-header.php"), true ); ?>
	    
		<div class="center padding-top">

			<?php 
			date_default_timezone_set("Europe/Paris");
			$payment_id = $_GET["invest_id"];
			$payment_post = get_post($payment_id);
			$valid_payment_access = (isset($payment_post) && $payment_post->post_author == get_current_user_id() && $payment_post->post_type == 'edd_payment');
			if ($valid_payment_access) {
				$payment_status = ypcf_get_updated_payment_status($payment_id);
				$downloads = edd_get_payment_meta_downloads($payment_id); 
				$download_id = '';
				if (is_array($downloads[0])) $download_id = $downloads[0]["id"]; 
				else $download_id = $downloads[0];
				$post_campaign = get_post($download_id);
				$campaign = atcf_get_campaign( $post_campaign );
				$valid_payment_access = ($campaign->is_active() && !$campaign->is_collected() && !$campaign->is_funded() && $campaign->campaign_status() == ATCF_Campaign::$campaign_status_collecte && $payment_status == "publish");
			}

			if ($valid_payment_access) {
				if (isset($_POST["confirm"]) && $_POST["confirm"] == "confirmed") {
					//On transfère la somme sur mangopay
					//On passe le statut du paiement en refund
					edd_undo_purchase( $download_id, $payment_id );
					wp_update_post( array( 'ID' => $payment_id, 'post_status' => 'refunded' ) );

					//On passe le log à refunded pour que ce soit bien pris en compte au niveau du décompte en cours du projet
					$log_payment_id = 0;
					query_posts( array(
					    'post_type'  => 'edd_log',
					    'meta_query' => array (array(
						'key'   => '_edd_log_payment_id',
						'value' => $payment_id
					    ))
					)); 
					if (have_posts()) : while (have_posts()) : the_post(); $log_payment_id = get_the_ID(); endwhile; endif;
					wp_reset_query();
					wp_update_post( array( 'ID' => $log_payment_id, 'post_status' => 'refunded' ) );

					//Affichage
					_e( 'Votre remboursement est validé. Vous recevrez cet argent sur votre compte sous peu.', 'yproject' );
					?>
		    
				<?php } else { ?>
						<?php the_content(); ?>

						<?php $future_amount = $campaign->current_amount(false) - edd_get_payment_amount($payment_id); ?>
						<a href="<?php echo get_permalink($campaign->ID); ?>"><?php echo $post_campaign->post_title; ?></a><br />
						<?php echo __("Sans vous, la somme atteinte sera de ", "yproject") . $future_amount . edd_get_currency(); ?><br /><br />
						<form action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="confirm" value="confirmed" />
							<input type="submit" value="<?php _e("Confirmer", "yproject"); ?>" class="button" />
						</form>
				<?php } ?>

			<?php } else { ?>
				<?php _e( 'Acc&egrave;s impossible', 'yproject' ); ?>
			<?php } ?>
			
			<br /><br />
			
			&lt;&lt; <a href="<?php home_url('/mon-compte/'); ?>#projects">Mon compte</a><br /><br />
			
		</div>
	</div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>