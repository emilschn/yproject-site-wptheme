<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $country_list;
?>

<div class="db-form v3 full center bg-white">
	
	<form id="userinfo_form" class="ajax-db-form" data-action="save_user_infos_dashboard">
		<?php if ( $page_controler->can_access_author() ) {
			?><input type="hidden" id="input_is_project_holder" name="is_project_holder" value="1"/><?php
		} else {
			?><p><?php _e("Seul le créateur du projet peut compléter ses informations personnelles","yproject");?></p><?php
		}?>

		<ul class="errors">

		</ul>

		<?php
		DashboardUtility::create_field( array(
			'id'			=> 'new_gender',
			'type'			=> 'select',
			'label'			=> "Vous &ecirc;tes",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get('user_gender'),
			'editable'		=> $page_controler->can_access_author(),
			'options_id'	=> array( 'female', 'male' ),
			'options_names'	=> array( "une femme", "un homme" )
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_firstname',
			'type'			=> 'text',
			'label'			=> "Pr&eacute;nom",
			'value'			=> $page_controler->get_campaign_author()->wp_user->user_firstname,
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_lastname',
			'type'			=> 'text',
			'label'			=> "Nom",
			'value'			=> $page_controler->get_campaign_author()->wp_user->user_lastname,
			'editable'		=> $page_controler->can_access_author()
		));

		$bd = new DateTime();
		$user_birthday_year = $page_controler->get_campaign_author()->wp_user->get( 'user_birthday_year' );
		if ( !empty( $user_birthday_year ) ) {
			$bd->setDate( 
				intval( $page_controler->get_campaign_author()->wp_user->get( 'user_birthday_year' ) ),
				intval( $page_controler->get_campaign_author()->wp_user->get( 'user_birthday_month' ) ),
				intval( $page_controler->get_campaign_author()->wp_user->get( 'user_birthday_day' ) )
			);
		}

		DashboardUtility::create_field( array(
			'id'			=> 'new_birthday',
			'type'			=> 'date',
			'label'			=> "Date de naissance",
			'value'			=> $bd,
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_birthplace',
			'type'			=> 'text',
			'label'			=> "Ville de naissance",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get( 'user_birthplace' ),
			'editable'		=> $page_controler->can_access_author()
		));

		DashboardUtility::create_field( array(
			'id'			=> 'new_nationality',
			'type'			=> 'select',
			'label'			=> "Nationalit&eacute;",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get( 'user_nationality' ),
			'editable'		=> $page_controler->can_access_author(),
			'options_id'	=> array_keys( $country_list ),
			'options_names'	=> array_values( $country_list )
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_mobile_phone',
			'type'			=> 'text',
			'label'			=> "T&eacute;l&eacute;phone mobile",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get( 'user_mobile_phone' ),
			'infobubble'	=> "Ce num&eacute;ro sera celui utilis&eacute; pour vous contacter &agrave; propos de votre projet",
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_mail',
			'type'			=> 'text',
			'label'			=> "Adresse &eacute;lectronique",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get( 'user_email' ),
			'infobubble'	=> "Pour modifier votre adresse e-mail de contact, rendez-vous dans vos param&egrave;tres de compte"
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_address',
			'type'			=> 'text',
			'label'			=> "Adresse",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get( 'user_address' ),
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_postal_code',
			'type'			=> 'text',
			'label'			=> "Code postal",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get('user_postal_code'),
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_city',
			'type'			=> 'text',
			'label'			=> "Ville",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get('user_city'),
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field(array(
			'id'			=> 'new_country',
			'type'			=> 'text',
			'label'			=> "Pays",
			'value'			=> $page_controler->get_campaign_author()->wp_user->get('user_country'),
			'editable'		=> $page_controler->can_access_author()
		) );?>
		<br/>

		<?php DashboardUtility::create_save_button( 'userinfo_form', $page_controler->can_access_author() ); ?>
	</form>
	
</div>