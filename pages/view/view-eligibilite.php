<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>
<div
  id="app"
<?php if ( $page_controler->has_init_guid() ): ?>
  data-guid='<?php echo $page_controler->get_init_guid(); ?>'
<?php endif; ?>
  data-locale='<?php echo $page_controler->get_init_locale(); ?>'
  data-ajaxurl='<?php echo admin_url( 'admin-ajax.php' ); ?>'>
</div>