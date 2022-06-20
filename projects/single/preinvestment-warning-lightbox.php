<?php global $stylesheet_directory_uri, $post; ?>

<?php
// *****************************************************************************
// Lightbox d'avertissement de pré-investissement
// *****************************************************************************
?>

<?php ob_start(); ?>
<div id="preinvestment-warning-form" class="wdg-lightbox-ref">
	
	<form method="post" class="sidebar-login-form db-form v3 full ajax-form">
		
		<div class="align-left">
			<?php WDG_Languages_Helpers::set_current_locale_id( WDG_Languages_Helpers::get_current_locale_id() ); ?>
			<?php echo apply_filters( 'the_content', WDGConfigTexts::get_config_text_by_name( WDGConfigTexts::$type_investment_generalities_preinvestment, 'preinvest_warning' ) ); ?>
		</div>
		
		<div id="user-details-form-buttons">
			
			<button type="button" class="button redirect half right red" data-redirecturl="<?php echo WDG_Redirect_Engine::override_get_page_url( 'investir' ) . '?campaign_id=' .$post->ID. '&invest_start=1'; ?>"><?php _e( "Continuer", 'yproject' ); ?></button>
			
		</div>
		
	</form>
	
</div>

<?php
$lightbox_content = ob_get_contents();
ob_end_clean();
echo do_shortcode('[yproject_lightbox_cornered id="preinvest-warning" title="'.__( "Avant de pr&eacute;-investir", 'yproject' ).'"]' . $lightbox_content . '[/yproject_lightbox_cornered]');
// *****************************************************************************