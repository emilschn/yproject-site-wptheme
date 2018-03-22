<?php
$page_controler = WDG_Templates_Engine::instance()->get_controler();
?>

<?php $declaration_info = $page_controler->get_campaign()->declaration_info(); ?>
<?php if ( $page_controler->can_access_admin() ): ?>
	<form action="" id="forcemandate_form" class="ajax-db-form" data-action="save_project_declaration_info">
		<?php DashboardUtility::create_field(array(
			"id"			=> "new_declaration_info",
			"type"			=> "editor",
			"label"			=> __( "Informations de reversement", 'yproject' ),
			"value"			=> $declaration_info,
			"editable"		=> $page_controler->can_access_admin(),
			"admin_theme"	=> $page_controler->can_access_admin(),
			"visible"		=> $page_controler->can_access_admin(),
		)); ?>

		<?php DashboardUtility::create_save_button( "forcemandate-form", $page_controler->can_access_admin() ); ?>
	</form>
<?php elseif ( !empty( $declaration_info ) ) : ?>

	<strong><?php _e( "Informations relatives &agrave; votre d&eacute;claration", 'yproject' ) ?></strong><br />
	<?php echo $declaration_info; ?><br /><br />

<?php endif; ?>