<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>
<div
  id="app"
  data-locale='<?php echo $page_controler->get_init_locale(); ?>'
  data-ajaxurl='<?php echo admin_url( 'admin-ajax.php' ); ?>'
  data-hasvalidationcode='<?php echo $page_controler->get_param_validation_code(); ?>'
  data-customajaxurl='<?php echo site_url( '/wp-content/plugins/appthemer-crowdfunding/includes/control/requests/ajax-entry-point.php' ); ?>'
  data-redirecturlfr='<?php echo wp_unslash( $page_controler->get_redirect_url_by_language( 'fr' ) ); ?>'
  data-redirecturlen='<?php echo wp_unslash( $page_controler->get_redirect_url_by_language( 'en' ) ); ?>'>
</div>