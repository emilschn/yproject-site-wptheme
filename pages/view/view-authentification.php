<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>
<div
  id="app"
  data-locale='<?php echo $page_controler->get_init_locale(); ?>'
  data-ajaxurl='<?php echo admin_url( 'admin-ajax.php' ); ?>'
  data-customajaxurl='<?php echo site_url( '/wp-content/plugins/appthemer-crowdfunding/includes/control/requests/ajax-entry-point.php' ); ?>'
  data-redirecturl='<?php echo wp_unslash( WDGUser::get_login_redirect_page() ); ?>'>
</div>