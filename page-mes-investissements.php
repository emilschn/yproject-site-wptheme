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
	<div class="padder">
	    <?php printUserProfileAdminBar(true);  ?>
	    <div class="center">
		<?php 
		if (is_user_logged_in()) :
		?>

		    <h2 class="underlined"><?php _e( 'Mes porte-monnaie', 'yproject' ); ?></h2>
		    <?php
			$real_amount_invest = ypcf_mangopay_get_user_personalamount_by_wpid(get_current_user_id()) / 100;
			$real_amount_wallet = ypcf_mangopay_get_userwallet_personalamount_by_wpid(get_current_user_id()) / 100;
			echo 'Vous disposez de ' . $real_amount_invest . '&euro; dans votre porte-monnaie d&apos;investissement.<br />';
			echo 'Vous disposez de ' . $real_amount_wallet . '&euro; dans votre porte-monnaie d&apos;int&eacuter&ecirc;ts.';
		    ?>

		    <h2 class="underlined"><?php _e( 'Mes investissements', 'yproject' ); ?></h2>
		    <?php
		    $purchases = edd_get_users_purchases(bp_current_user_id(), -1, false, array('completed', 'pending', 'publish', 'failed', 'refunded'));
		    if ( $purchases ) : ?>
		    <ul class="user_history">
			<?php 
			    foreach ( $purchases as $post ) : setup_postdata( $post );
				$downloads = edd_get_payment_meta_downloads($post->ID); 
				$download_id = '';
				if (is_array($downloads[0])) $download_id = $downloads[0]["id"]; 
				else $download_id = $downloads[0];
				$post_camp = get_post($download_id);
				printUserInvest($post, $post_camp);
			    endforeach;
			?>
		    </ul>
		    <?php endif; ?>
		    
		    
		<?php
		endif;
		?>
	    </div>
	</div>
    </div>

<?php get_footer(); ?>