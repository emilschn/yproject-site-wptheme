<?php
global $WDGOrganization;
?>

<h2 class="underlined"><?php _e( 'Authentification de ', 'yproject' ); echo $WDGOrganization->get_name(); ?></h2>

<?php locate_template( array( 'pages/view/mon-compte/partial-authentication.php' ), true, false ); ?>