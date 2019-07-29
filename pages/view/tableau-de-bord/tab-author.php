<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
global $country_list;
?>

<h2><?php _e( "Repr&eacute;sentant l&eacute;gal", 'yproject' ); ?></h2>
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
			'value'			=> $page_controler->get_campaign_author()->get_gender(),
			'editable'		=> $page_controler->can_access_author(),
			'options_id'	=> array( 'female', 'male' ),
			'options_names'	=> array( "une femme", "un homme" )
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_firstname',
			'type'			=> 'text',
			'label'			=> "Pr&eacute;nom",
			'value'			=> $page_controler->get_campaign_author()->get_firstname(),
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_lastname',
			'type'			=> 'text',
			'label'			=> "Nom",
			'value'			=> $page_controler->get_campaign_author()->get_lastname(),
			'editable'		=> $page_controler->can_access_author()
		));
		
		$birthday_datetime = new DateTime( $page_controler->get_campaign_author()->get_birthday_date() );
		$birthday_description = ( !ypcf_check_user_is_complete( $page_controler->get_campaign()->post_author() ) ? "<span class='errors'>Le porteur de projet doit &ecirc;tre majeur</span>" : '' );
		DashboardUtility::create_field( array(
			'id'			=> 'new_birthday',
			'type'			=> 'date',
			'label'			=> "Date de naissance",
			'value'			=> $birthday_datetime,
			'editable'		=> $page_controler->can_access_author(),
			'description'	=> $birthday_description
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_birthplace',
			'type'			=> 'text',
			'label'			=> "Ville de naissance",
			'value'			=> $page_controler->get_campaign_author()->get_birthplace(),
			'editable'		=> $page_controler->can_access_author()
		));

		DashboardUtility::create_field( array(
			'id'			=> 'new_nationality',
			'type'			=> 'select',
			'label'			=> "Nationalit&eacute;",
			'value'			=> $page_controler->get_campaign_author()->get_nationality(),
			'editable'		=> $page_controler->can_access_author(),
			'options_id'	=> array_keys( $country_list ),
			'options_names'	=> array_values( $country_list )
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_mobile_phone',
			'type'			=> 'text',
			'label'			=> "T&eacute;l&eacute;phone mobile",
			'value'			=> $page_controler->get_campaign_author()->get_phone_number(),
			'infobubble'	=> "Ce num&eacute;ro sera celui utilis&eacute; pour vous contacter &agrave; propos de votre projet",
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_mail',
			'type'			=> 'text',
			'label'			=> "Adresse &eacute;lectronique",
			'value'			=> $page_controler->get_campaign_author()->get_email(),
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_address',
			'type'			=> 'text',
			'label'			=> "Adresse",
			'value'			=> $page_controler->get_campaign_author()->get_address(),
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_postal_code',
			'type'			=> 'text',
			'label'			=> "Code postal",
			'value'			=> $page_controler->get_campaign_author()->get_postal_code(),
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field( array(
			'id'			=> 'new_city',
			'type'			=> 'text',
			'label'			=> "Ville",
			'value'			=> $page_controler->get_campaign_author()->get_city(),
			'editable'		=> $page_controler->can_access_author()
		) );

		DashboardUtility::create_field(array(
			'id'			=> 'new_country',
			'type'			=> 'text',
			'label'			=> "Pays",
			'value'			=> $page_controler->get_campaign_author()->get_country(),
			'editable'		=> $page_controler->can_access_author()
		) );?>
		<br/>

		<?php DashboardUtility::create_save_button( 'userinfo_form', $page_controler->can_access_author() ); ?>
	</form>
	
</div>