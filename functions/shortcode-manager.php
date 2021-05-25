<?php
YPShortcodeManager::register_shortcodes();
class YPShortcodeManager {
	public static $shortcode_list = array(
		'yproject_lightbox_button',
		'yproject_lightbox_cornered',
		'yproject_lightbox',
		'yproject_widelightbox',
		'yproject_connexion_lightbox',
		'yproject_register_lightbox',
		'yproject_statsadvanced_lightbox',
		'yproject_newproject_lightbox',
		'wdg_home_stats',
		'wdg_config_text',
		'wdg_page_auto_refresh',
		'wdg_project_vote_count',
		'wdg_project_vote_intention_sum',
		'wdg_project_investors_count',
		'wdg_project_amount_count',
		'wdg_project_investment_link',
		'wdg_project_progress_bar',
		'wdg_project_royalties_simulator',
		'wdg_project_preview',
		'wdg_project_warning_lightbox',
		'wdg_royalties_simulator',
		'wdg_page_breadcrumb',
		'wdg_footer_banner_link'
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

	public static function yproject_lightbox_button($atts, $content = '') {
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
	public static function yproject_lightbox_cornered($atts, $content = '') {
		$atts = shortcode_atts( array(
			'id'		=> 'lightbox',
			'title'		=> '',
			'scrolltop' => '0',
			'style'		=> '',
			'class'		=> '',
			'msgtype'	=> '', // valid / error
			'autoopen'	=> '0',
			'catchclick'=> '1',
			'save-close'=> '0'
		), $atts );

		$msgtype_lightbox = '';
		$data_autoopen = ( $atts['autoopen'] == '1' ) ? '1' : '0';
		$classes = 'hidden';
		if ( !empty( $atts['msgtype'] ) ) {
			$classes .= ' msg-'.$atts['msgtype'];
		}
		$catcher_classes = ( $atts['catchclick'] == '1' ) ? '' : 'disable';

		ob_start(); ?>
		<div id="wdg-lightbox-<?php echo $atts[ 'id' ]; ?>" <?php echo $atts[ 'style' ]; ?> class="wdg-lightbox cornered <?php echo $classes; ?>" data-autoopen="<?php echo $data_autoopen; ?>" data-scrolltop=<?php echo $atts[ 'scrolltop' ]; ?>>
			<div class="wdg-lightbox-click-catcher <?php echo $catcher_classes; ?>"></div>
			<div class="wdg-lightbox-corner">
				<div class="wdg-lightbox-button-close">
					<?php if ( $atts['catchclick'] == '1'): ?> 
					<a data-save-close="<?php echo $atts['save-close'] ?>" href="#" class="button">X</a>
					<?php endif; ?>
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

	public static function yproject_lightbox($atts, $content = '') {
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

	public static function yproject_widelightbox($atts, $content = '') {
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
	public static function yproject_connexion_lightbox($atts, $content = '') {
		ob_start();
		locate_template('common/connexion-lightbox.php', true);
		$lightbox_content = ob_get_contents();
		ob_end_clean();
		echo do_shortcode('[yproject_lightbox_cornered id="connexion" title="'.__('Inscription et connexion', 'yproject').'"]' . $content . $lightbox_content . '[/yproject_lightbox_cornered]');
	}

	//Shortcodes lightbox d'inscription
	public static function yproject_register_lightbox($atts, $content = '') {
		ob_start();
		locate_template('common/register-lightbox.php', true);
		$lightbox_content = ob_get_contents();
		ob_end_clean();
		echo do_shortcode('[yproject_lightbox_cornered id="register" title="'.__('Inscription', 'yproject').'"]' . $content . $lightbox_content . '[/yproject_lightbox_cornered]');
	}

	//Shortcode lightbox Tableau de bord
	// ->TB Stats
	public static function yproject_statsadvanced_lightbox($atts, $content = '') {
		ob_start();
		locate_template('projects/dashboard/dashboard-statsadvanced-lightbox.php', true);
		$content = ob_get_contents();
		ob_end_clean();
		echo do_shortcode('[yproject_lightbox id="statsadvanced"]' .$content . '[/yproject_lightbox]');
	}

	//Shortcode pour Lightbox de création de projet
	public static function yproject_newproject_lightbox($atts, $content = '') {
		ob_start();
		locate_template('common/newproject-lightbox.php', true);
		$content = ob_get_contents();
		ob_end_clean();
		echo do_shortcode('[yproject_lightbox_cornered id="newproject" class="wdg-lightbox-ref" title="'.__( "Lancement de levée de fonds", 'yproject' ).'"]' .$content . '[/yproject_lightbox_cornered]');
		echo do_shortcode('[yproject_register_lightbox]');
	}

	public static function wdg_config_text($atts, $content = '') {
		$atts = shortcode_atts( array(
			'url'		=> ''
		), $atts );

		if ( empty( $atts[ 'url' ] ) ) {
			return '';
		}

		$config_text_content = WDGConfigTexts::get_config_text_by_name( $atts[ 'url' ] );

		return $config_text_content;
	}

	public static function wdg_home_stats($atts, $content = '') {
		$atts = shortcode_atts( array(
			'stat'		=> ''
		), $atts );

		global $stylesheet_directory_uri;
		$db_cacher = WDG_Cache_Plugin::current();
		$stats = $db_cacher->get_cache( WDG_Cache_Plugin::$stats_key, WDG_Cache_Plugin::$stats_version );

		if (!$stats) {
			$stats_list = WDG_Cache_Plugin::initialize_home_stats();
		} else {
			$stats_list = json_decode( $stats, true );
		}

		if ( !empty( $atts[ 'stat' ] ) ) {
			return number_format( $stats_list[ $atts[ 'stat' ] ], 0, '', ' ' );
		}

		ob_start(); ?>

			<section class="project-stats">
				<div>
					<div class="left">
						<div>
							<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-home/picto-montgolfiere-noir-h100.png" alt="montgolfiere" width="115" height="100">
						</div>
						<div>
							<span><?php echo number_format( $stats_list[ 'count_amount' ], 0, '', ' ' ); ?> &euro;</span><br>
							<?php _e( 'common.RAISED.P', 'yproject' ); ?>
						</div>
					</div>
					<div class="left">
						<div>
							<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-home/picto-ensemble-noir-h100.png" alt="ensemble" width="139" height="100">
						</div>
						<div>
							<span><?php echo number_format( $stats_list[ 'count_people' ], 0, '', ' ' ); ?></span><br>
							<?php _e( 'common.INVESTORS', 'yproject' ); ?>
						</div>
					</div>
					<div class="left">
						<div>
							<img src="<?php echo $stylesheet_directory_uri; ?>/images/template-home/picto-monnaie-noir-h100.png" alt="monnaie" width="102" height="100">
						</div>
						<div>
							<span><?php echo number_format( $stats_list[ 'royaltying_projects' ], 0, '', ' ' ); ?></span><br>
							<?php _e( 'common.COMPANIES_ROYALTIZE', 'yproject' ); ?>
						</div>
					</div>
					
					<div class="clear"></div>
					<p><?php _e( 'common.ROYALTIZE_DEFINITION', 'yproject' ); ?></p>

				</div>
			</section>

		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	public static function wdg_page_auto_refresh($atts, $content = '') {
		$atts = shortcode_atts( array(
			'nb_minutes' => '2',
		), $atts );

		$nb_milliseconds = $atts[ 'nb_minutes' ] * 60 * 1000;
		$code = '<script type="text/javascript">setTimeout("location.reload(true);", ' .$nb_milliseconds. ');</script>';

		return $code;
	}

	public static function wdg_project_vote_count($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => '',
			'project_list' => ''
		), $atts );

		$project_ids_list = array();
		if ( isset( $atts[ 'project' ] ) && is_numeric( $atts[ 'project' ] ) ) {
			array_push( $project_ids_list, $atts[ 'project' ] );
		} elseif ( isset( $atts[ 'project_list' ] ) ) {
			$project_ids_list = explode( ',', $atts[ 'project_list' ] );
		}

		$buffer_nb_voters = 0;
		foreach ( $project_ids_list as $project_id ) {
			$campaign = atcf_get_campaign( $project_id );
			$buffer_nb_voters += $campaign->nb_voters();
		}

		return UIHelpers::format_number( $buffer_nb_voters, 0 );
	}

	public static function wdg_project_vote_intention_sum($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => '',
			'project_list' => ''
		), $atts );

		$project_ids_list = array();
		if ( isset( $atts[ 'project' ] ) && is_numeric( $atts[ 'project' ] ) ) {
			array_push( $project_ids_list, $atts[ 'project' ] );
		} elseif ( isset( $atts[ 'project_list' ] ) ) {
			$project_ids_list = explode( ',', $atts[ 'project_list' ] );
		}

		global $wpdb;
		$table_name = $wpdb->prefix . WDGCampaignVotes::$table_name_votes;
		$buffer_sum_vote_intention = 0;
		foreach ( $project_ids_list as $project_id ) {
			$sum_vote_intention = $wpdb->get_var( "SELECT sum(invest_sum) FROM ".$table_name." WHERE post_id = ". $project_id );
			$buffer_sum_vote_intention += $sum_vote_intention;
		}

		return UIHelpers::format_number( $buffer_sum_vote_intention, 0 );
	}

	public static function wdg_project_investors_count($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => '',
		), $atts );

		if (isset($atts['project']) && is_numeric($atts['project'])) {
			$post_campaign = get_post($atts['project']);
			$campaign = atcf_get_campaign($post_campaign);

			return $campaign->backers_count();
		}
	}

	public static function wdg_project_amount_count($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => '',
		), $atts );

		if (isset($atts['project']) && is_numeric($atts['project'])) {
			$post_campaign = get_post($atts['project']);
			$campaign = atcf_get_campaign($post_campaign);

			return $campaign->current_amount();
		}
	}

	public static function wdg_project_investment_link($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => '',
			'label' => '',
			'class' => '',
			'style' => ''
		), $atts );

		$buffer = '';
		if ( isset( $atts[ 'project' ] ) && is_numeric( $atts[ 'project' ] ) ) {
			if ( is_user_logged_in() ) {
				$buffer = '<a href="' .WDG_Redirect_Engine::override_get_page_url( 'investir' ). '?campaign_id=' .$atts[ 'project' ]. '&amp;invest_start=1" class="' .$atts[ 'class' ]. '" style="' .$atts[ 'style' ]. '">' .$atts[ 'label' ]. '</a>';
			} else {
				$buffer = '<a href="' .WDG_Redirect_Engine::override_get_page_url( 'connexion' ). '?redirect-invest=' .$atts[ 'project' ] . '" class="' .$atts[ 'class' ]. '" style="' .$atts[ 'style' ]. '">' .$atts[ 'label' ]. '</a>';
			}
		}

		return $buffer;
	}

	public static function wdg_project_progress_bar($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => ''
		), $atts );

		global $campaign, $stylesheet_directory_uri, $is_progressbar_shortcode;
		$campaign = new ATCF_Campaign( $atts[ 'project' ] );
		$stylesheet_directory_uri = get_stylesheet_directory_uri();
		$is_progressbar_shortcode = TRUE;

		ob_start();
		locate_template( array( 'projects/common/progressbar.php' ), true );
		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}

	public static function wdg_project_royalties_simulator($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => ''
		), $atts );

		global $campaign, $stylesheet_directory_uri, $is_simulator_shortcode;
		$campaign = new ATCF_Campaign( $atts[ 'project' ] );
		$stylesheet_directory_uri = get_stylesheet_directory_uri();
		$is_simulator_shortcode = TRUE;

		ob_start(); ?>
		<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/wdg-campaign.js?d=<?php echo ASSETS_VERSION; ?>"></script>
		<?php
		locate_template( array( 'projects/single/rewards.php' ), true );
		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}

	public static function wdg_project_preview($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project' => '',
			'home' => ''
		), $atts );

		global $stylesheet_directory_uri, $project_id;
		$stylesheet_directory_uri = get_stylesheet_directory_uri();

		ob_start(); ?>
		<section class="wdg-component-projects-preview">
			<div class="project-slider">
				<div class="block-projects">
					<?php

					// Projets qui s'affichent sur l'accueil
					if ( $atts[ 'home' ] == 1 ) {
						$db_cacher = WDG_Cache_Plugin::current();
						$projects = $db_cacher->get_cache( WDG_Cache_Plugin::$projects_key, WDG_Cache_Plugin::$projects_version );

						if ( !$projects ) {
							$projects_list = WDG_Cache_Plugin::initialize_home_projects();
						} else {
							$projects_list = json_decode( $projects, TRUE );
						}

						foreach ( $projects_list as $project_id ) {
							locate_template( array("projects/preview.php"), true, false );
						}

						// Un projet spécifique
					} else {
						if ( isset( $atts[ 'project' ] ) ) {
							$project_id = $atts[ 'project' ];
							locate_template( array( 'projects/preview.php' ), true );
						}
					} ?>
				</div>
			</div>
		</section>
		<?php

		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}

	public static function wdg_project_warning_lightbox($atts, $content = '') {
		$atts = shortcode_atts( array(
			'project_id' => ''
		), $atts );

		if ( empty( $atts[ 'project_id' ] ) ) {
			return '';
		}

		global $campaign, $stylesheet_directory_uri;
		$campaign = new ATCF_Campaign( $atts[ 'project_id' ] );
		$campaign_status = $campaign->campaign_status();

		ob_start();

		if ( !is_user_logged_in() && ( $campaign_status == ATCF_Campaign::$campaign_status_preview || $campaign_status == ATCF_Campaign::$campaign_status_vote || $campaign_status == ATCF_Campaign::$campaign_status_collecte ) ) {
			locate_template( array( 'projects/single/warning-lightbox.php' ), true, false );
		}

		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}

	public static function wdg_royalties_simulator($atts, $content = '') {
		$atts = shortcode_atts( array(
			'title_color'				=> '#00879b',
			'title_1'					=> __( "MON PR&Eacute;VISIONNEL :", 'yproject' ),
			'description_1'				=> __( "Indiquez votre chiffre d&apos;affaires pr&eacute;visionnel sur le nombre d&apos;ann&eacute;es souhait&eacute;es.", 'yproject' ),
			'title_2'					=> __( "MON BESOIN DE FINANCEMENT :", 'yproject' ),
			'description_2'				=> __( "Indiquez le montant maximum que vous souhaitez lever et les royalties &agrave; reverser correspondantes.", 'yproject' ),
			'goal_label'				=> __( "Montant maximum &agrave; lever", 'yproject' ),
			'button_label'				=> __( "Calculer", 'yproject' ),
			'percent_royalties_advice_1'	=> __( "Nous vous conseillons de verser", 'yproject' ),
			'percent_royalties_advice_2'	=> __( "% de votre CA &agrave; vos investisseurs.", 'yproject' )
		), $atts );

		global $stylesheet_directory_uri;

		ob_start(); ?>
		
		<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/wdg-royalties-simulator.js?d=<?php echo ASSETS_VERSION; ?>"></script>
		
		<form id="royalties-simulator" class="db-form form-register v3 full center bg-white">
			<h3 style="color: <?php echo $atts[ 'title_color' ]; ?>"><?php echo $atts[ 'title_1' ]; ?></h3>
			<span><?php echo $atts[ 'description_1' ]; ?></span>
			<br><br>
			
			<?php for ( $i = 1; $i <= 5; $i++ ): ?>
			<div id="field-year-<?php echo $i; ?>" class="field field-text">
				<label for="year-<?php echo $i; ?>"><?php _e( "Ann&eacute;e ", 'yproject' ); ?><?php echo $i; ?></label>
				<span class="field-error"></span><br>
				<div class="field-container">
					<span class="field-value">
						<input type="text" name="year-<?php echo $i; ?>" id="year-<?php echo $i; ?>">
					</span>
				</div>
			</div>
			<?php endfor; ?>
			
			<h3 style="color: <?php echo $atts[ 'title_color' ]; ?>"><?php echo $atts[ 'title_2' ]; ?></h3>
			<span><?php echo $atts[ 'description_2' ]; ?></span>
			<br><br>
			
			<div id="field-goal" class="field field-text">
				<label for="goal"><?php echo $atts[ 'goal_label' ]; ?></label>
				<span class="field-error"></span><br>
				<div class="field-container">
					<span class="field-value">
						<input type="text" name="goal" id="goal">
					</span>
				</div>
			</div>
			
			<button class="button blue"><?php echo $atts[ 'button_label' ]; ?></button>
			<br><br>
			
			<span class="form-error-general hidden"><?php _e( "Il y a des erreurs dans les donn&eacute;es saisies.", 'yproject' ); ?></span>
			
			<span id="royalties_advice" class="hidden">
				<?php echo $atts[ 'percent_royalties_advice_1' ]; ?>
				<span class="royalties_advice_value"></span>
				<?php echo $atts[ 'percent_royalties_advice_2' ]; ?>
			</span>
		</form>
		
		<?php
		$buffer = ob_get_contents();
		ob_end_clean();

		return $buffer;
	}

	public static function wdg_page_breadcrumb($atts, $content = '') {
		$atts = shortcode_atts( array(
			'separator'				=> '&gt;',
			'separator_unpublished'	=> '-'
		), $atts );
		global $post;

		// On commence toujours par WE DO GOOD
		$buffer = "<a href=\"" .home_url(). "\">WE DO GOOD</a>";
		$buffer .= " " .$atts[ 'separator' ]. " ";

		if ( $post->post_parent ) {
			// Récupère les parents et les replace dans l'ordre du plus haut au plus bas
			$post_ancestors_list = get_post_ancestors( $post->ID );
			$post_ancestors_list = array_reverse( $post_ancestors_list );

			// On ajoute le post en cours pour le gérer correctement dans la liste
			array_push( $post_ancestors_list, get_the_ID() );
			$count_ancestors = count( $post_ancestors_list );

			// Parcours des parents dans le bon ordre
			for ( $i = 0; $i < $count_ancestors; $i++ ) {
				$post_ancestor = get_post( $post_ancestors_list[ $i ] );

				// Le parent est publié normalement, on lui met un lien normal
				if ( $post_ancestor->post_status == 'publish' ) {
					$post_meta_title = get_post_meta( $post_ancestor->ID, 'breadcrumb_title', TRUE );
					$post_title = ( !empty( $post_meta_title ) ) ? $post_meta_title : get_the_title( $post_ancestor );
					$buffer .= "<a href=\"" . get_permalink( $post_ancestor ) . "\" title=\"" .$post_title. "\">" .$post_title. "</a>";
					if ( isset( $post_ancestors_list[ $i + 1 ] ) ) {
						$buffer .= " " .$atts[ 'separator' ]. " ";
					}
				} else {
					// Le parent n'est pas publié : on parcourt ses descendants pour faire une liste de type Catégorie 1 - Catégorie 2 (plutôt que >) au sein du même lien
					$post_meta_title = get_post_meta( $post_ancestor->ID, 'breadcrumb_title', TRUE );
					$current_title = ( !empty( $post_meta_title ) ) ? $post_meta_title : get_the_title( $post_ancestor );
					while ( isset( $post_ancestors_list[ $i + 1 ] ) && $post_ancestors_list[ $i ]->post_status != 'publish' ) {
						$i++;
						$current_title .= " " .$atts[ 'separator_unpublished' ]. " ";
						$post_meta_title = get_post_meta( $post_ancestors_list[ $i ], 'breadcrumb_title', TRUE );
						$post_title = ( !empty( $post_meta_title ) ) ? $post_meta_title : get_the_title( $post_ancestors_list[ $i ] );
						$current_title .= $post_title;
					}
					$buffer .= "<a href=\"" . get_permalink( $post_ancestors_list[ $i ] ) . "\" title=\"" .$current_title. "\">" .$current_title. "</a>";
				}
			}

			// Pas de parent, mais pas la page d'accueil = on affiche juste la page
		} elseif ( !is_home() && !is_front_page() ) {
			$post_meta_title = get_post_meta( get_the_ID(), 'breadcrumb_title', TRUE );
			$post_title = ( !empty( $post_meta_title ) ) ? $post_meta_title : get_the_title();
			$buffer .= "<a href=\"" .get_the_permalink(). "\" title=\"" .$post_title. "\">" .$post_title. "</a>";
		}

		$buffer = "<nav itemtype=\"http://data-vocabulary.org/Breadcrumb\" class=\"wdg-breadcrumb\">" .$buffer. "</nav>";

		return $buffer;
	}

	public static function wdg_footer_banner_link($atts, $content = '') {
		$atts = shortcode_atts( array(
			'link' => ''
		), $atts );

		$footer_style = 'position: fixed; z-index: 30000; bottom: 0px; left: 0px; width: 100%; padding: 16px 0px; font-size: 18px; background: #333; color: #FFF; text-align: center;';
		$link_style = 'color: #FFF; text-transform: uppercase;';
		$img_arrow_src = get_stylesheet_directory_uri(). "/images/footer-banner-shortcode-arrow.png";
		$img_arrow_style = 'margin-top: -4px; vertical-align: middle;';
		$img_wdg_src = get_stylesheet_directory_uri(). "/images/footer-banner-shortcode-logo.png";
		$img_wdg_style = 'height: 31px; margin-top: -3px; vertical-align: middle;';
		$text = "D&eacute;couvrez les royalties sur";
		$buffer = '<div style="' .$footer_style. '"><a href="' .$atts[ 'link' ]. '" style="' .$link_style. '"><img style="' .$img_arrow_style. '" src="' .$img_arrow_src. '"> ' .$text. ' <img style="' .$img_wdg_style. '" src="' .$img_wdg_src. '"></a></div>';

		return $buffer;
	}
}
