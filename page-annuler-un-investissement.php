<?php get_header(); ?>

<div id="content">
    <div class="padder">
	<div class="center">
	    
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
		$valid_payment_access = ($campaign->is_active() && !$campaign->is_collected() && !$campaign->is_funded() && $campaign->vote() == "collecte" && $payment_status == "publish");
	    }

	    if ($valid_payment_access) {
		if (isset($_POST["confirm"]) && $_POST["confirm"] == "confirmed") {
		    //On transfère la somme sur mangopay
		    $current_user = wp_get_current_user();
		    $amount = edd_get_payment_amount($payment_id);
		    $new_transfer = ypcf_mangopay_transfer_project_to_user($current_user, $download_id, $amount);
		    update_post_meta($payment_id, 'refund_transfer_id', $new_transfer->ID);
		    
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
		    _e( 'La somme est maintenant disponible dans votre porte-monnaie.', 'yproject' );
		    echo '<br />';
		    $page_investments = get_page_by_path('mes-investissements');
		    ?>
		    &lt;&lt; <a href="<?php echo get_permalink($page_investments->ID); ?>"><?php echo __('Mes investissements', 'yproject'); ?></a>
		    <?php
		    
		} else {
		
		    if (have_posts()) : while (have_posts()) : the_post(); 
	?>
			<?php the_content(); ?>

			<?php
			    $future_amount = $campaign->current_amount(false) - edd_get_payment_amount($payment_id);
			?>
			<a href="<?php echo get_permalink($campaign->ID); ?>"><?php echo $post_campaign->post_title; ?></a><br />
			<?php echo __("Sans vous, la somme atteinte sera de ", "yproject") . $future_amount . edd_get_currency(); ?>
			<form action="" method="post" enctype="multipart/form-data">
			    <input type="hidden" name="confirm" value="confirmed" />
			    <input type="submit" value="<?php _e("Confirmer", "yproject"); ?>" />
			</form>

		<?php endwhile; else: ?>
			<?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ); ?>
		<?php endif;
		}
	?>

	<?php } else { ?>
		<?php _e( 'Acc&egrave;s impossible', 'yproject' ); ?>
	<?php } ?>
			
	    <center><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/powered_by_mangopay.png" /></center>
		
	</div>
    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>