<?php
class YPShortcodeManager {
	public static $shortcode_list = array(
		'yproject_crowdfunding_invest_form',
		'yproject_crowdfunding_invest_confirm',
		'yproject_crowdfunding_invest_mean_payment'
	);
	
	public static function register_shortcodes() {
		foreach (YPShortcodeManager::$shortcode_list as $shortcode) {
			add_shortcode( $shortcode, array( 'YPShortcodeManager', $shortcode ) );
		}
	}
	
	
	function yproject_crowdfunding_invest_form($atts, $content = '') {
		$form = '';
		if (ypcf_get_current_step() == 1) {
			ob_start();
			locate_template( 'invest/input.php', true );
			$form = ob_get_contents();
			ob_end_clean();
		}
		return $form;
	}
	
	function yproject_crowdfunding_invest_confirm($atts, $content = '') {
		$form = '';
		if (ypcf_get_current_step() == 2) {
			ob_start();
			locate_template( 'invest/confirm.php', true );
			$form = ob_get_contents();
			ob_end_clean();
		}
		return $form;
	}
	
	function yproject_crowdfunding_invest_mean_payment($atts, $content = '') {
		ob_start();
		locate_template( 'invest/mean-payment.php', true );
		$form = ob_get_contents();
		ob_end_clean();
		return $form;
	}
}
