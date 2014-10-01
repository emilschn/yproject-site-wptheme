<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-default
 */

$current_user = get_user_by('id', bp_displayed_user_id());
?>

<?php do_action( 'bp_before_member_header' ); ?>

<span id="user-id" data-value="<?php echo bp_displayed_user_id(); ?>"></span>

<div id="item-header-avatar" class="left">
	<a href="<?php bp_displayed_user_link(); ?>">
		<?php print_user_avatar(bp_displayed_user_id()); ?>
	</a>
</div><!-- #item-header-avatar -->

<div id="item-header-content" class="left">

	<h1>
		<a href="<?php bp_displayed_user_link(); ?>"><?php echo $current_user->display_name; ?></a>
	</h1>

	 <?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
		<span class="user-nicename">@<?php bp_displayed_user_username(); ?></span>
	<?php endif; ?>

	<?php
	$user_meta = get_userdata(bp_displayed_user_id());
	echo($user_meta->description);
	?>

	<?php do_action( 'bp_before_member_header_meta' ); ?>

	<div id="item-meta">

		<div id="item-buttons">

			<?php do_action( 'bp_member_header_actions' ); ?>

		</div><!-- #item-buttons -->

		<?php
		// If you'd like to show specific profile fields here use:  bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
		do_action( 'bp_profile_header_meta' );
		?>

	</div><!-- #item-meta -->

</div><!-- #item-header-content -->


<div style="clear: both"></div>

<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>

<?php
$display_loggedin_user = (bp_loggedin_user_id() == bp_displayed_user_id());

//Gestion de renvoi du code
if (is_user_logged_in() && $display_loggedin_user) :
	//Si on a demandé de renvoyer le code
	if (isset($_GET['invest_id_resend']) && $_GET['invest_id_resend'] != '') {
		$contractid = ypcf_get_signsquidcontractid_from_invest($_GET['invest_id_resend']);
		// $signsquid_infos = signsquid_get_contract_infos($contractid);
		$signsquid_signatory = signsquid_get_contract_signatory($contractid);
		$current_user = wp_get_current_user();
		if ($signsquid_signatory != '' && $signsquid_signatory->{'email'} == $current_user->user_email) {
		    if (ypcf_send_mail_purchase($_GET['invest_id_resend'], "send_code", $signsquid_signatory->{'code'}, $current_user->user_email)) { ?>
			    Votre code de signature de contrat a &eacute;t&eacute; renvoy&eacute; &agrave; l&apos;adresse <?php echo $current_user->user_email; ?>.<br />
			    
		    <?php } else { ?>
			    <span class="errors">Il y a eu une erreur lors de l&apos;envoi du code. N&apos;h&eacute;sitez pas &agrave; nous contacter.</span><br />
			
		    <?php }
		    
		} else { ?>
			<span class="errors">Nous ne trouvons pas le contrat correspondant.</span><br />
		    
		<?php }
	}
endif; ?>