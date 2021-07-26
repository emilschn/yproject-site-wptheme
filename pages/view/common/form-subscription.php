<?php
    global $stylesheet_directory_uri;
    $page_controler = WDG_Templates_Engine::instance()->get_controler();
    $WDGSubscriptionForm = $page_controler->get_subscription_form();
    $fields_basics = $WDGSubscriptionForm->getFields( WDG_Form_Subscription::$field_group_basics );

?>
<form method="POST" enctype="multipart/form-data" class="<?php echo $page_controler->get_form_css_classes();?>">

<?php foreach ( $fields_basics as $field ): ?>
		<?php global $wdg_current_field; $wdg_current_field = $field; ?>
		<?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
	<?php endforeach; ?>

</form>

