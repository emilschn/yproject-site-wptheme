<?php
global $can_modify, $disable_logs, $campaign_id, $campaign, $post_campaign, $WDGAuthor, $WDGUser_current, $organization_obj, $is_admin, $is_author;
$disable_logs = FALSE;

WDGFormProjects::form_submit_turnover();
WDGFormProjects::form_submit_account_files();
$return_roi_payment = WDGFormProjects::form_submit_roi_payment();
$return_lemonway_card = WDGFormProjects::return_lemonway_card();
WDGFormProjects::form_proceed_roi_transfers();
?>

<div class="head"><?php _e('Gestion financi&egrave;re', 'yproject'); ?></div>
<div id="tab-wallet-subtabs" class="tab-subtabs bloc-grid">
	<div class="display-bloc" data-tab-target="tab-wallet-synthesis">
		<i class="fa fa-bar-chart fa-4x aria-hidden="true"></i>
		<div class="infobloc-title">
			<?php _e("Synth&egrave;se","yproject");?>
		</div>
	</div>
	<div class="display-bloc" data-tab-target="tab-wallet-declarations">
		<i class="fa fa-tasks fa-4x aria-hidden="true"></i>
		<div class="infobloc-title">
			<?php _e("D&eacute;clarations et paiements","yproject");?>
		</div>
	</div>
	<div class="display-bloc" data-tab-target="tab-wallet-timetable">
		<i class="fa fa-calendar fa-4x aria-hidden="true"></i>
		<div class="infobloc-title">
			<?php _e("Ech&eacute;ancier","yproject");?>
		</div>
	</div>
</div>

<div>
<?php if ( $return_roi_payment == 'error_lw_payment' ): ?>
	<span class="errors">Erreur LWROI001 : Erreur de paiement vers votre porte-monnaie.</span>
<?php endif; ?>

<?php if ( $return_lemonway_card == TRUE ): ?>
	<span class="success">Paiement effectué</span>
<?php elseif ( $return_lemonway_card !== FALSE ): ?>
	<span class="errors">Il y a eu une erreur au cours de votre paiement.</span>
<?php endif; ?>
</div>

<div id="tab-wallet-subtabs-container" class="tab-container">
<?php
locate_template( array("projects/dashboard/wallet/tab-synthesis.php"), true );
locate_template( array("projects/dashboard/wallet/tab-declarations.php"), true );
locate_template( array("projects/dashboard/wallet/tab-timetable.php"), true );
?>
</div>