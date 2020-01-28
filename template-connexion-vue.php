<?php
/**
 * Template Name: Connexion Vue
 *
 */
?>

<?php
$WDG_Vue_Components = WDG_Vue_Components::instance();
$WDG_Vue_Components->enqueue_component( WDG_Vue_Components::$component_signin_signup );
?>

<?php get_header( ATCF_CrowdFunding::get_platform_context() ); ?>

<?php date_default_timezone_set("Europe/Paris"); ?>

<div id="content" class="login-page-container">
	
	<div class="padder">
		
		<div id="app" data-ajaxurl="<?php echo admin_url( 'admin-ajax.php' ); ?>"></div>
		
	</div><!-- .padder -->
	
</div><!-- #content -->

<?php get_footer( ATCF_CrowdFunding::get_platform_context() );
