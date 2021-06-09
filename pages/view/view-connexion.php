<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>
<div
  id="app"
  data-locale='<?php echo $page_controler->get_init_locale(); ?>'
  data-ajaxurl='<?php echo admin_url( 'admin-ajax.php' ); ?>'>
</div>