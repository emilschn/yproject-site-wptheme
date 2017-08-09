<?php
YPShortcodeManager::register_shortcodes();
class YPShortcodeManager {
	public static $shortcode_list = array(
		'yproject_crowdfunding_invest_form',
		'yproject_crowdfunding_invest_confirm',
		'yproject_crowdfunding_invest_mean_payment',
		'yproject_crowdfunding_invest_payment_check',
		'yproject_crowdfunding_invest_payment_wire',
		'yproject_crowdfunding_invest_return',
		'yproject_crowdfunding_invest_share'
	);
	
	public static function register_shortcodes() {
		foreach (YPShortcodeManager::$shortcode_list as $shortcode) {
			add_shortcode( $shortcode, array( 'YPShortcodeManager', $shortcode ) );
		}
	}
	
	public static function include_template($path) {
		ob_start();
		locate_template( $path, true );
		$form = ob_get_contents();
		ob_end_clean();
		return $form;
	}
	
	
	function yproject_crowdfunding_invest_form($atts, $content = '') {
		$form = '';
		if (ypcf_get_current_step() == 1) {
			$form = YPShortcodeManager::include_template('invest/input.php');
		}
		return $form;
	}
	
	function yproject_crowdfunding_invest_confirm($atts, $content = '') {
		$form = '';
		if (ypcf_get_current_step() == 2) {
			$form = YPShortcodeManager::include_template('invest/confirm.php');
		}
		return $form;
	}
	
	function yproject_crowdfunding_invest_mean_payment($atts, $content = '') {
		return YPShortcodeManager::include_template('invest/mean-payment.php');
	}
	
	function yproject_crowdfunding_invest_payment_check($atts, $content = '') {
		return YPShortcodeManager::include_template('invest/payment-check.php');
	}
	
	function yproject_crowdfunding_invest_payment_wire($atts, $content = '') {
		return YPShortcodeManager::include_template('invest/payment-wire.php');
	}
	
	function yproject_crowdfunding_invest_return($atts, $content = '') {
		return YPShortcodeManager::include_template('invest/return.php');
	}
	
	function yproject_crowdfunding_invest_share($atts, $content = '') {
		return YPShortcodeManager::include_template('invest/share.php');
	}
}
