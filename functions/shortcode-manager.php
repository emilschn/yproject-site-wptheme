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
		'yproject_crowdfunding_invest_share',
		'yproject_lightbox_button',
		'yproject_lightbox_cornered',
		'yproject_lightbox',
		'yproject_widelightbox',
		'yproject_connexion_lightbox',
		'yproject_register_lightbox',
		'yproject_statsadvanced_lightbox',
		'yproject_newproject_lightbox',
		'wdg_project_vote_count',
		'wdg_project_amount_count',
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
	
	function yproject_lightbox_button($atts, $content = '') {
		$atts = shortcode_atts( array(
			'label'	=> 'Afficher',
			'id'	=> 'lightbox',
			'class' => 'button',
			'style' => ''
		), $atts );
		return '<a href="#'.$atts['id'].'" class="wdg-button-lightbox-open '.$atts['class'].'" style="'.$atts['style'].'" data-lightbox="'.$atts['id'].'">'.$atts['label'].'</a>';
	}
	
	/**
	 * Lightbox avec coin transparent
	 */
	function yproject_lightbox_cornered( $atts, $content = '' ) {
		$atts = shortcode_atts( array(
			'id'		=> 'lightbox',
			'title'		=> '',
			'scrolltop' => '0',
			'style'		=> '',
			'class'		=> '',
			'msgtype'	=> '', // valid / error
			'autoopen'	=> '0'
		), $atts );
		
		$msgtype_lightbox = '';
		$classes = ( $atts['autoopen'] == '0' ) ? $atts['class']. ' hidden' : $atts['class'];
		if ( !empty( $atts['msgtype'] ) ) {
			$classes .= ' msg-'.$atts['msgtype'];
		}
		
		ob_start();
		?>
		<div id="wdg-lightbox-<?php echo $atts[ 'id' ]; ?>" <?php echo $atts[ 'style' ]; ?> class="wdg-lightbox cornered <?php echo $classes; ?>" data-scrolltop=<?php echo $atts[ 'scrolltop' ]; ?>>
			<div class="wdg-lightbox-click-catcher"></div>
			<div class="wdg-lightbox-corner">
				<div class="wdg-lightbox-button-close">
					<a href="#" class="button">X</a>
				</div>
				<h2><?php echo $atts[ 'title' ]; ?></h2>
			</div>
			<div class="wdg-lightbox-padder">
				<?php echo do_shortcode( $content ); ?>
			</div>
		</div>
		<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	function yproject_lightbox($atts, $content = '') {
		$atts = shortcode_atts( array(
			'id'		=> 'lightbox',
			'scrolltop' => '0',
			'style'		=> '',
			'class'		=> '',
			'msgtype'	=> '',
			'autoopen'	=> '0'
		), $atts );

		$msgtype_lightbox = '';
		$classes = ( $atts['autoopen'] == '0' ) ? $atts['class']. ' hidden' : $atts['class'];
		if ( !empty( $atts['msgtype'] ) ) {
			$msgtype_lightbox = $atts['msgtype'].'-msg';
			$classes .= ' msg-lightbox';
		}
		return '<div id="wdg-lightbox-'.$atts['id'].'" '.$atts['style'].' class="wdg-lightbox '.$classes.'" data-scrolltop='.$atts['scrolltop'].'>
			<div class="wdg-lightbox-click-catcher"></div>
			<div class="wdg-lightbox-padder '.$msgtype_lightbox.'">
				<div class="wdg-lightbox-button-close">
				<a href="#" class="button">X</a>
				</div>'.do_shortcode($content).'
			</div>
			</div>';
	}
	
	function yproject_widelightbox($atts, $content = '') {
		$atts = shortcode_atts( array(
			'id'		=> 'lightbox',
			'scrolltop'	=> '0',
		), $atts );
		return '<div id="wdg-lightbox-'.$atts['id'].'" class="wdg-lightbox hidden" data-scrolltop='.$atts['scrolltop'].'>
			<div class="wdg-lightbox-click-catcher"></div>
			<div class="wdg-lightbox-padder wdg-widelightbox-padder">
				<div class="wdg-lightbox-button-close">
				<a href="#" class="button">X</a>
				</div>'.do_shortcode($content).'
			</div>
			</div>';
	}
	
	//Shortcodes lightbox Connexion
	function yproject_connexion_lightbox($atts, $content = '') {
		ob_start();
		locate_template('common/connexion-lightbox.php',true);
		$lightbox_content = ob_get_contents();
		ob_end_clean();
		echo do_shortcode('[yproject_lightbox_cornered id="connexion" title="'.__('Inscription et connexion', 'yproject').'"]' . $content . $lightbox_content . '[/yproject_lightbox_cornered]');
	}
	
	//Shortcodes lightbox d'inscription 
	function yproject_register_lightbox($atts, $content = '') {
		ob_start();
		locate_template('common/register-lightbox.php',true);
		$lightbox_content = ob_get_contents();
		ob_end_clean();
		echo do_shortcode('[yproject_lightbox_cornered id="register" title="'.__('Inscription', 'yproject').'"]' . $content . $lightbox_content . '[/yproject_lightbox_cornered]');
	}
	
	//Shortcode lightbox Tableau de bord
	// ->TB Stats
	function yproject_statsadvanced_lightbox($atts, $content = '') {
		ob_start();
		locate_template('projects/dashboard/dashboard-statsadvanced-lightbox.php',true);
		$content = ob_get_contents();
		ob_end_clean();
		echo do_shortcode('[yproject_lightbox id="statsadvanced"]' .$content . '[/yproject_lightbox]');
	}
	
	//Shortcode pour Lightbox de création de projet
	function yproject_newproject_lightbox($atts, $content = '') {
		ob_start();
		locate_template('common/newproject-lightbox.php',true);
		$content = ob_get_contents();
		ob_end_clean();
		echo do_shortcode('[yproject_lightbox_cornered id="newproject" class="wdg-lightbox-ref"]' .$content . '[/yproject_lightbox_cornered]');
		echo do_shortcode('[yproject_register_lightbox]');
	}
	
	function wdg_project_vote_count($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => '',
		), $atts );

		if (isset($atts['project']) && is_numeric($atts['project'])) {
			$post_campaign = get_post($atts['project']);
			$campaign = atcf_get_campaign($post_campaign);
			return $campaign->nb_voters();
		}
	}

	function wdg_project_amount_count($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => '',
		), $atts );

		if (isset($atts['project']) && is_numeric($atts['project'])) {
			$post_campaign = get_post($atts['project']);
			$campaign = atcf_get_campaign($post_campaign);
			return $campaign->current_amount();
		}
	}
}






