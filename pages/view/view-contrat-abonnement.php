<?php
global $stylesheet_directory_uri;
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>


<form class="db-form v3 full bg-white enlarge">
	
	<div id="contract-preview">
		<?php echo $page_controler->get_current_investment_contract_preview(); ?>
	</div>
	
	<div id="contract-buttons">

		<br><br><br>

		<button type="submit" class="button half right red"><?php _e( 'invest.contract.VALIDATE_CONTRACT', 'yproject' ); ?></button>

		<a href="<?php echo WDG_Redirect_Engine::override_get_page_url( 'mon-compte' ). '#subscription'; ?> "  class="button half left transparent"> 
			<?php _e( 'common.PREVIOUS', 'yproject' ); ?>
		</a>
		<div class="clear"></div>
	</div>
</form>