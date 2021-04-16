<?php
    global $stylesheet_directory_uri;
    $page_controler = WDG_Templates_Engine::instance()->get_controler();
    $WDGUserDetailsForm = $page_controler->get_user_details_form();
    $fields_hidden = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_hidden );
    $fields_basics = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_basics );
    $fields_complete = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_complete );
    $fields_extended = $WDGUserDetailsForm->getFields( WDG_Form_User_Details::$field_group_extended );
    $form_feedback = $page_controler->get_user_form_feedback();
?>

<form method="POST" enctype="multipart/form-data" class="<?php echo $page_controler->get_form_css_classes();?> account-form">
		
    <?php foreach ( $fields_hidden as $field ): ?>
        <?php global $wdg_current_field; $wdg_current_field = $field; ?>
        <?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
    <?php endforeach; ?>

    <?php if ( !empty( $form_feedback[ 'errors' ] ) ): ?>
        <div class="wdg-message error">
            <?php _e( 'account.parameters.SOME_ERRORS', 'yproject' ); ?><br>
            <?php foreach ( $form_feedback[ 'errors' ] as $error ): ?>
                - <?php echo $error[ 'text' ]; ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if ( !empty( $form_feedback[ 'success' ] ) ): ?>
        <div class="wdg-message confirm">
            <?php foreach ( $form_feedback[ 'success' ] as $message ): ?>
                <?php echo $message; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php foreach ( $fields_basics as $field ): ?>
        <?php global $wdg_current_field; $wdg_current_field = $field; ?>
        <?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
    <?php endforeach; ?>

    <?php if ( !empty( $fields_complete ) ): ?>
        <?php foreach ( $fields_complete as $field ): ?>
            <?php global $wdg_current_field; $wdg_current_field = $field; ?>
            <?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ( !empty( $fields_extended ) ): ?>
        <?php foreach ( $fields_extended as $field ): ?>
            <?php global $wdg_current_field; $wdg_current_field = $field; ?>
            <?php locate_template( array( "common/forms/field.php" ), true, false );  ?>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <p class="align-left">
        * <?php _e( 'common.REQUIRED_FIELDS', 'yproject' ); ?><br>
    </p>

    <div id="user-details-form-buttons">
        <button type="submit" class="button save red <?php if ($page_controler->get_controler_name() == 'tableau-de-bord' && !$page_controler->get_campaign()->is_preparing()){ ?>confirm<?php } ?>">
            <span class="button-text">
                <?php _e( 'common.SAVE_MODIFICATION', 'yproject' ); ?>
            </span>
            <span class="button-loading loading align-center hidden">
			    <img class="alignverticalmiddle marginright" src="<?php echo $stylesheet_directory_uri; ?>/images/loading-grey.gif" width="30" alt="chargement" /><?php _e( 'common.REGISTERING', 'yproject' ); ?>			
		    </span>
        </button>
    </div>
    
</form>
    