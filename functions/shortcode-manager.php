<?php
class YPShortcodeManager {
	public static $shortcode_list = array(
		'yproject_crowdfunding_invest_form'
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
			locate_template( 'invest/invest-input.php', true );
			$form = ob_get_contents();
			ob_end_clean();
		}
		return $form;
	}
}
