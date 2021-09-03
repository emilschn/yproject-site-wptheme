<?php
class WDG_Page_Controler {
	private $db_cache_manager;
	private $page_title;
	private $page_description;
	private $page_meta_keywords;
	protected $page_analytics_data;
	private $show_user_details_confirmation;
	private $show_user_pending_preinvestment;
	private $show_user_pending_investment;
	protected $show_user_hidden_project_visited = FALSE;
	private $show_user_needs_authentication;
	protected $controler_name;

	public function __construct() {
		ypcf_session_start();
		date_default_timezone_set("Europe/Paris");
		include_once __DIR__ . '/../../functions/assets-version.php';
		global $stylesheet_directory_uri;
		$stylesheet_directory_uri = get_stylesheet_directory_uri();
		$this->db_cache_manager = new WDG_Cache_Plugin();
		$this->init_page_title();
		$this->init_page_description();

		// Si c'est une page projet
		if ( is_single() && get_post_type() == 'download' ) {
			$this->init_override_languages();
		}
		$this->init_analytics_data();

		if ( is_user_logged_in() && ATCF_CrowdFunding::get_platform_context() == 'wedogood' ) {
			$this->init_show_user_pending_preinvestment();
			$this->init_show_user_pending_investment();
			$this->init_show_user_details_confirmation();
			$this->init_show_user_needs_authentication();
		}
	}

	public function get_db_cached_elements($key, $version) {
		return $this->db_cache_manager->get_cache( $key, $version );
	}

	public function set_db_cached_elements($key, $value, $duration, $version) {
		$this->db_cache_manager->set_cache( $key, $value, $duration, $version );
	}

	public function get_controler_name() {
		return $this->controler_name;
	}

	/**
	 * Retourne le titre de la page
	 * @return string
	 */
	public function get_page_title() {
		return $this->page_title;
	}

	private function init_page_title() {
		if ( is_home() || is_front_page() ) {
			global $post;
			$this->page_title = $post->post_title;
		} else {
			global $post;
			if ( is_single() && $post->post_type == 'download' ) {
				$this->page_title = $post->post_title . __('meta.title.PROJECT', 'yproject');
			
			} else if ( is_category() ) {
				global $cat;
				$this_category = get_category($cat);
				$this_category_name = $this_category->name;
				$name_exploded = explode('cat', $this_category_name);
				$campaign_post = get_post($name_exploded[1]);
				$this->page_title = 'Actualit&eacute;s du projet ' . (is_object($campaign_post) ? $campaign_post->post_title : '') . ' | ' . get_bloginfo( 'name' );
			
			} else {
				$this->page_title = wp_title( '|', false, 'right' );
			}
		}
	}

	/**
	 * Récupère les keywords à partir des tags
	 */
	public function get_page_meta_keywords() {
		return $this->page_meta_keywords;
	}

	private function init_page_meta_keywords() {
		$this->page_meta_keywords = '';
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				$posttags = get_the_tags();
				if ( $posttags ) {
					foreach ( (array) $posttags as $tag) {
						$this->page_meta_keywords .= $tag->name . ',';
					}
				}
			}
		}
	}

	/**
	 * Récupère la description de la page (champs personnalisé)
	 */
	public function get_page_description() {
		return $this->page_description;
	}

	private function init_page_description() {
		$this->page_description = "WE DO GOOD est le leader français des levées de fonds en royalties et du crowdinvesting. Investissez en ligne à partir de 10 € dans les projets qui vous parlent.";
		global $post;
		if ( is_single() && $post->post_type == 'download' ) {
			$campaign = new ATCF_Campaign( $post->ID );
			$project_activity = $campaign->get_categories_by_type( 'activities', TRUE );
			$this->page_description = __('meta.description.PROJECT', 'yproject') . ' ' . $project_activity;

		} else if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				global $post;
				$meta_description = get_post_meta( $post->ID, 'metadescription', TRUE );
				if ( !empty( $meta_description ) ) {
					$this->page_description = $meta_description;
				}
			}
		}
	}

	/**
	 * Récupère les infos à pusher sur Analytics
	 */
	public function get_analytics_data() {
		return $this->page_analytics_data;
	}

	private function init_analytics_data() {
		$this->page_analytics_data = array();
		if ( is_user_logged_in() ) {
			$WDG_user_current = WDGUser::current();
			$this->page_analytics_data[ 'user_id' ] = $WDG_user_current->get_wpref();

			ypcf_session_start();
			if ( !empty( $_SESSION['send_creation_event'] ) && $_SESSION['send_creation_event'] === 1 ) {
				$this->page_analytics_data[ 'event' ] = 'creation-compte-ok';
			}
		}
	}

	/**
	 * Détermine si la navigation est visible ou non
	 * @return boolean
	 */
	public function get_header_nav_visible() {
		return ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' );
	}

	//******************************************************************************
	/**
	 * Gestion multilingue : nécessaire pour mettre à jour les liens vers les pages particulières (projets)
	 */
	protected function init_override_languages() {
		add_filter( 'wpml_active_languages', 'WDG_Page_Controler::override_languages', 10 );
	}

	public static function override_languages($languages) {
		$buffer = array();

		global $post, $locale, $wpml_request_handler;
		$language_cookie_lang = $wpml_request_handler->get_cookie_lang();
		$campaign = new ATCF_Campaign( $post );

		foreach ( $languages as $language_key => $language_item ) {
			$buffer_item = $language_item;
			$buffer_item[ 'active' ] = ( $language_cookie_lang == $language_item[ 'code' ] );
			if ( $language_key == 'fr' ) {
				$buffer_item[ 'url' ] = site_url( '/' . $campaign->get_url() . '/' );
			} else {
				$buffer_item[ 'url' ] = site_url( '/' . $language_key . '/' . $campaign->get_url() . '/' );
			}
			array_push( $buffer, $buffer_item );
		}

		return $buffer;
	}

	//******************************************************************************
	/**
	 * Chargement script et style de datatable
	 */
	protected function enqueue_datatable($cdn = false) {
		if ( $cdn ) {
			wp_enqueue_style( 'datatable-css', 'https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css', null, false, 'all' );

			wp_enqueue_script( 'datatable-script', 'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js', array( 'jquery', 'wdg-script' ), true, true );
			wp_enqueue_script( 'datatable-buttons', 'https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js', array( 'jquery', 'wdg-script' ), true, true );
			wp_enqueue_script( 'datatable-jszip', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js', array( 'jquery', 'wdg-script' ), true, true );
			wp_enqueue_script( 'datatable-fonts', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js', array( 'jquery', 'wdg-script' ), true, true );
			wp_enqueue_script( 'datatable-buttons-html5', 'https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js', array( 'jquery', 'wdg-script' ), true, true );
		} else {
			wp_enqueue_script( 'datatable-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/jquery.dataTables.min.js', array( 'jquery', 'wdg-script' ), true, true );
			wp_enqueue_style( 'datatable-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/jquery.dataTables.min.css', null, false, 'all' );

			wp_enqueue_script( 'datatable-colreorder-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/dataTables.colReorder.min.js', array( 'datatable-script' ), true, true );
			wp_enqueue_style( 'datatable-colreorder-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/colReorder.dataTables.min.css', null, false, 'all' );

			wp_enqueue_script( 'datatable-responsive-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/dataTables.responsive.min.js', array( 'datatable-script' ), true, true );
			wp_enqueue_style( 'datatable-responsive-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/responsive.dataTables.min.css', null, false, 'all' );

			wp_enqueue_script( 'datatable-select-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/dataTables.select.min.js', array( 'datatable-script' ), true, true );
			wp_enqueue_style('datatable-select-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/select.dataTables.min.css', null, false, 'all' );

			wp_enqueue_script( 'datatable-buttons-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/dataTables.buttons.min.js', array( 'datatable-script' ), true, true );
			wp_enqueue_script( 'datatable-buttons-colvis-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/buttons.colVis.min.js', array( 'datatable-script' ), true, true );
			wp_enqueue_script( 'datatable-buttons-html5-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/buttons.html5.min.js', array( 'datatable-script' ), true, true );
			wp_enqueue_script( 'datatable-buttons-print-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/buttons.print.min.js', array( 'datatable-script' ), true, true );
			wp_enqueue_script( 'datatable-jszip-script', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/js/dataTables/jszip.min.js', array( 'datatable-script' ), true, true );
			wp_enqueue_style( 'datatable-buttons-css', dirname( get_bloginfo( 'stylesheet_url' ) ). '/_inc/css/dataTables/buttons.dataTables.min.css', null, false, 'all' );
		}
	}

	//******************************************************************************
	/**
	 * Détermine si il est nécessaire d'afficher la lightbox de confirmation d'information à l'utilisateur
	 */
	public function init_show_user_details_confirmation() {
		if ( !isset( $this->show_user_details_confirmation ) ) {
			$this->show_user_details_confirmation = false;
			if ( !$this->get_show_user_pending_preinvestment() && is_user_logged_in() && ATCF_CrowdFunding::get_platform_context() == 'wedogood' ) {
				$WDG_user_current = WDGUser::current();
				$user_details_confirmation_type = $WDG_user_current->get_show_details_confirmation();
				if ( $user_details_confirmation_type ) {
					$this->show_user_details_confirmation = new WDG_Form_User_Details( $WDG_user_current->get_wpref(), $user_details_confirmation_type );
					$WDG_user_current->update_last_details_confirmation();
				}
			}
		}
	}

	public function get_show_user_details_confirmation() {
		return $this->show_user_details_confirmation;
	}

	//******************************************************************************
	public function init_show_user_pending_preinvestment() {
		if ( !isset( $this->show_user_pending_preinvestment ) ) {
			$this->show_user_pending_preinvestment = false;
			if ( is_user_logged_in() ) {
				$WDG_user_current = WDGUser::current();
				if ( $WDG_user_current->is_admin() ) {
					$input_user_id = filter_input( INPUT_GET, 'override_current_user' );
					if ( !empty( $input_user_id ) ) {
						$WDG_user_current = new WDGUser( $input_user_id );
					}
				}

				if ( $WDG_user_current->has_pending_preinvestments() ) {
					ypcf_debug_log( 'WDG_Page_Controler::init_show_user_pending_preinvestment has_pending_preinvestments' );
					$this->show_user_pending_preinvestment = $WDG_user_current->get_first_pending_preinvestment();
				}
				if ( !$this->show_user_pending_preinvestment ) {
					$user_organizations_list = $WDG_user_current->get_organizations_list();
					foreach ( $user_organizations_list as $organization_item ) {
						$WDGUserOrga = new WDGUser( $organization_item->wpref );
						if ( $WDGUserOrga->has_pending_preinvestments() ) {
							ypcf_debug_log( 'WDG_Page_Controler::init_show_user_pending_preinvestment ORGA has_pending_preinvestments' );
							$this->show_user_pending_preinvestment = $WDGUserOrga->get_first_pending_preinvestment();
							break;
						}
					}
				}
			}
		}
	}

	public function get_show_user_pending_preinvestment() {
		return $this->show_user_pending_preinvestment;
	}

	//******************************************************************************
	public function init_show_user_pending_investment() {
		if ( !isset( $this->show_user_pending_investment ) ) {
			$this->show_user_pending_investment = false;
			if ( is_user_logged_in() ) {
				$WDG_user_current = WDGUser::current();
				if ( $WDG_user_current->is_admin() ) {
					$input_user_id = filter_input( INPUT_GET, 'override_current_user' );
					if ( !empty( $input_user_id ) ) {
						$WDG_user_current = new WDGUser( $input_user_id );
					}
				}

				if ( $WDG_user_current->is_lemonway_registered() ) {
					if ( $WDG_user_current->has_pending_not_validated_investments() ) {
						$this->show_user_pending_investment = $WDG_user_current->get_first_pending_not_validated_investment();
					}
				}
				if ( !$this->show_user_pending_investment ) {
					$user_organizations_list = $WDG_user_current->get_organizations_list();
					if ( $user_organizations_list ) {
						foreach ( $user_organizations_list as $organization_item ) {
							$WDGOrga = new WDGOrganization( $organization_item->wpref );
							if ( $WDGOrga->is_registered_lemonway_wallet() && $WDGOrga->has_pending_not_validated_investments() ) {
								$this->show_user_pending_investment = $WDGOrga->get_first_pending_not_validated_investment();
								break;
							}
						}
					}
				}
			}
		}
	}

	public function get_show_user_pending_investment() {
		return $this->show_user_pending_investment;
	}

	//******************************************************************************
	public function get_show_user_hidden_project_visited() {
		return $this->show_user_hidden_project_visited;
	}

	//******************************************************************************
	public function init_show_user_needs_authentication() {
		if ( !isset( $this->show_user_needs_authentication ) ) {
			$this->show_user_needs_authentication = false;
			if ( is_user_logged_in() ) {
				$WDG_user_current = WDGUser::current();
				$this->show_user_needs_authentication = !$WDG_user_current->is_lemonway_registered();
			}
		}
	}

	public function get_show_user_needs_authentication() {
		return $this->show_user_needs_authentication;
	}
}