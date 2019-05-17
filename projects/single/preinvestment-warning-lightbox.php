<?php global $stylesheet_directory_uri, $post; ?>

<?php
// *****************************************************************************
// Lightbox d'avertissement de pré-investissement
// *****************************************************************************
$edd_settings = get_option( 'edd_settings' );
?>

<?php ob_start(); ?>
<div id="preinvestment-warning-form" class="wdg-lightbox-ref">
	
	<form method="post" class="sidebar-login-form db-form v3 full ajax-form">
		
		<div class="align-left">
			<?php echo apply_filters( 'the_content', ATCF_CrowdFunding::get_translated_setting( 'preinvest_warning' ) ); ?>
		</div>
		
		<div id="user-details-form-buttons">
			
			<button type="button" class="button redirect half right red" data-redirecturl="<?php echo home_url( '/investir/' ) . '?campaign_id=' .$post->ID. '&invest_start=1'; ?>"><?php _e( "Continuer", 'yproject' ); ?></button>
			
		</div>
		
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="preinvest-warning" title="'.__( "Avant de pr&eacute;-investir", 'yproject' ).'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
// *****************************************************************************