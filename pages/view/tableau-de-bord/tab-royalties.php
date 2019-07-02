<?php $page_controler = WDG_Templates_Engine::instance()->get_controler(); ?>

<h2><?php _e( "Royalties", 'yproject' ); ?></h2>

<ul class="menu-onglet">
  <li><a href="#royalties" class="focus" data-subtab="versements"><?php _e( "Versements", 'yproject' ); ?><span></span></a></li>
  <li><a href="#royalties" data-subtab="ajustements"><?php _e( "Ajustements", 'yproject' ); ?><span></span></a></li>
</ul>

<?php
// Inclusion des fichiers externes dans /tab-royalties/
locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/tab-versements.php'  ), true );
locate_template( array( 'pages/view/tableau-de-bord/tab-royalties/tab-ajustements.php'  ), true );