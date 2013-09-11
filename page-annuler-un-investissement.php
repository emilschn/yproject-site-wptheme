<?php get_header(); ?>

<div id="content">
    <div class="padder">
	<div class="center">
	    
	<?php 
	    date_default_timezone_set("Europe/Paris");
	    require_once("common.php");

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
		    //Faire un transfer -> PayerID : project id ; PayerWalletID : projectwallet ; BeneficiaryID : userid ; Amount : edd_get_payment_amount($payment_id) ; BeneficiaryWalletID : 0
		    //Passer le statut du paiement en refund
		    _e( 'La somme est maintenant disponible dans votre porte-monnaie.', 'yproject' );
		    
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
		
	</div>
    </div><!-- .padder -->
</div><!-- #content -->
	
<?php get_footer(); ?>