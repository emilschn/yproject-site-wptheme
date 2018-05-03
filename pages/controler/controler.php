<?php
class WDG_Page_Controler {
	
	private $db_cache_manager;
	private $page_title;
	private $page_description;
	private $page_meta_keywords;
	private $show_user_details_confirmation;
	private $show_user_pending_preinvestment;
	
	public function __construct() {
		ypcf_session_start();
		date_default_timezone_set("Europe/Paris");
		include_once( __DIR__ . '/../../functions/assets-version.php' );
		global $stylesheet_directory_uri;
		$stylesheet_directory_uri = get_stylesheet_directory_uri();
		$this->db_cache_manager = new WDG_Cache_Plugin();
		$this->init_page_title();
		$this->init_page_description();
		
		if ( is_user_logged_in() && ATCF_CrowdFunding::get_platform_context() == 'wedogood' ) {
			$this->init_show_user_pending_preinvestment();
			$this->init_show_user_details_confirmation();
		}
	}
	
	public function get_db_cached_elements( $key, $version ) {
		return $this->db_cache_manager->get_cache( $key, $version );
	}
	
	public function set_db_cached_elements( $key, $value, $duration, $version ) {
		$this->db_cache_manager->set_cache( $key, $value, $duration, $version );
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
			
		} else if ( is_category() ) {
			global $cat;
			$this_category = get_category($cat);
			$this_category_name = $this_category->name;
			$name_exploded = explode('cat', $this_category_name);
			$campaign_post = get_post($name_exploded[1]);
			$this->page_title = 'Actualit&eacute;s du projet ' . (is_object($campaign_post) ? $campaign_post->post_title : '') . ' | ' . get_bloginfo( 'name' );
			
		} else {
			$this->page_title = wp_title( '|', false, 'right' ) . get_bloginfo( 'name' );
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
		if ( have_posts() ){
			while ( have_posts() ) {
				the_post();
				$posttags = get_the_tags();
				if ( $posttags ) {
					foreach( (array) $posttags as $tag) {
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
		$this->page_description = "Première plateforme française de royalty crowdfunding. Levez des fonds sans dilution de capital ni endettement.";
		if ( have_posts() ){
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
	 * Détermine si la navigation est visible ou non
	 * @return boolean
	 */
	public function get_header_nav_visible() {
		return ( ATCF_CrowdFunding::get_platform_context() == 'wedogood' );
	}
	
	/**
	 * 
	 */
	public function get_display_link_account() {
		global $post;
		return ( is_home() || is_front_page() || $post->post_name == 'les-projets' || $post->post_name == 'investissement' || $post->post_name == 'financement' );
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
				$user_details_confirmation = $WDG_user_current->get_show_details_confirmation();
				if ( $user_details_confirmation ) {
					$this->show_user_details_confirmation = new WDG_Form_User_Details( $WDG_user_current->get_wpref(), $user_details_confirmation );
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
			global $post;
			if ( is_user_logged_in() && ATCF_CrowdFunding::get_platform_context() == 'wedogood' && $post->post_name != 'terminer-preinvestissement' ) {
				$WDG_user_current = WDGUser::current();
				if ( $WDG_user_current->has_pending_preinvestments() ) {
					$this->show_user_pending_preinvestment = $WDG_user_current->get_first_pending_preinvestment();
				}
				if ( !$this->show_user_pending_preinvestment ) {
					$user_organizations_list = $WDG_user_current->get_organizations_list();
					foreach ( $user_organizations_list as $organization_item ) {
						$WDGUserOrga = new WDGUser( $organization_item->wpref );
						if ( $WDGUserOrga->has_pending_preinvestments() ) {
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
	
}