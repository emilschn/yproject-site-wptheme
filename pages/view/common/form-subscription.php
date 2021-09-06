<?php
    global $WDGOrganization;        
    global $stylesheet_directory_uri;

    $page_controler = WDG_Templates_Engine::instance()->get_controler();
    $WDGSubscriptionForm = $page_controler->get_subscription_form();
    $fields_basics = $WDGSubscriptionForm->getFields( WDG_Form_Subscription::$field_group_basics );
    $fields_hidden = $WDGSubscriptionForm->getFields( WDG_Form_Subscription::$field_group_hidden );
    $form_feedback = $page_controler->get_user_form_feedback(); 
?>

<form method="POST" enctype="multipart/form-data" class="<?php echo $page_controler->get_form_css_classes();?>" 
action="<?php echo admin_url( 'admin-post.php?action=user_account_organization_subscription' ); ?>">

    <?php foreach ( $fields_hidden as $field ): ?>
        <?php global $wdg_current_field; $wdg_current_field = $field; ?>
        <?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
    <?php endforeach; ?>

    <?php foreach ( $fields_basics as $field ): ?>
        <?php global $wdg_current_field; $wdg_current_field = $field; ?>
        <?php locate_template( array( "common/forms/field.php" ), true, false );  ?>  
    <?php endforeach; ?>

    <div id="user-details-form-buttons">
        <button type="submit" class="button save red">
            <span class="button-text">
                <?php _e( 'common.SAVE', 'yproject' ); ?>
            </span>
            <span class="button-loading loading align-center hidden">
                <img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30" alt="chargement" /><?php _e( 'common.REGISTERING', 'yproject' ); ?>			
            </span>
        </button>
    </div>

</form>

