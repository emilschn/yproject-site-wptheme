<?php 
global $disable_logs; $disable_logs = TRUE;
$campaign = atcf_get_current_campaign();

if ($campaign->current_user_can_edit()) {
	locate_template( array("requests/investments.php"), true );
	$investments_list = wdg_get_project_investments($_GET['campaign_id'], TRUE);
	$campaign = $investments_list['campaign'];
	$is_campaign_over = ($campaign->campaign_status() == 'funded' || $campaign->campaign_status() == 'archive');
?>
		
<h3>Liste des investisseurs</h3>
<i>Si vous envoyez un mail group&eacute; &agrave; vos investisseurs, pensez &agrave; les mettre dans le champ CCI, pour qu&apos;ils n&apos;aient pas acc&egrave;s aux adresses des autres.</i><br /><br />

<?php
        $classcolonnes = array('coluname',
                            'collname',
                            'colfname',
                            'colbrthday',
                            'colbrthplace',
                            'colnat',
                            'colcity',
                            'coladdr',
                            'colpcode',
                            'colcountry',
                            'colemail',
                            'colphone',
                            'colinvesmontant',
                            'colinvdate',
                            'colinvpaytype',
                            'colinvpaystate',
                            'colinvsign');
        if ($is_campaign_over) { $classcolonnes[]='colinvstate'; }
        
        $titrecolonnes = array('Utilisateur',
                            'Nom',
                            'Prénom',
                            'Date de naissance',
                            'Ville de naissance',
                            'Nationalité',
                            'Ville',
                            'Adresse',
                            'Code postal',
                            'Pays',
                            'Mail',
                            'Téléphone',
                            'Montant investi',
                            'Date',
                            'Type de paiement',
                            'Etat du paiement',
                            'Signature');
        if ($is_campaign_over) { $titrecolonnes[]='Investissement'; }
        
        $selectiondefaut = array(true,
                            true,
                            true,
                            false,
                            false,
                            false,
                            true,
                            false,
                            false,
                            false,
                            true,
                            true,
                            true,
                            false,
                            false,
                            false,
                            false);
        if ($is_campaign_over) { $selectiondefaut[]=true; }
        
        $colonnes = array_combine($classcolonnes, $titrecolonnes);
        $displaycolonnes = array_combine($classcolonnes,$selectiondefaut);
    ?>

<div id="display-options-col-div">
    <button>Colonnes &agrave; afficher &#9662;</button>
    <form>
    <fieldset id="display-options-col-menu">
        <ul id="display-options-col-list">
            <li id="cb-li-collall">
                <input id="cbcolall" class="check-users-columns" type="checkbox" value="all">
                </input><label for="cbcolall">Tout sélectionner</label>
            </li>
        <?php foreach($colonnes as $class=>$titre){
            //Checkbox d'affichage des colonnes : voir dans common.js
                echo '<li><input type="checkbox" ';
                if (($displaycolonnes[$class]) == true){echo 'checked ';}
                echo 'class="check-users-columns" '
                    .'value="'.$class.'" '
                    .'id="cb'.$class.'">'
                    .' <label for="cb'.$class.'">'.$titre.'</label></li>';}
        ?>
        </ul>
    </fieldset>
    </form>
</div>

<br/>

<div id="tablescroll" >
<table class="wp-list-table" cellspacing="0" id="investors-table">
    
    <thead style="background-color: #CCC;">
    <tr>
        <?php foreach($colonnes as $class=>$titre){
            //Ecriture des nom des colonnes en haut
            echo '<td class="'.$class.'" ';
            if (($displaycolonnes[$class]) == false){echo 'hidden=""';}
            echo '>'.$titre.'</td>';}?>
    </tr>
    </thead>

    <tbody id="the-list">
	<?php
	$i = -1;
	foreach ( $investments_list['payments_data'] as $item ) {
//	    if ($item['status'] == 'publish' || $item['status'] == 'refunded') {
		$i++;
		$bgcolor = ($i % 2 == 0) ? "#FFF" : "#EEE";

		$post_invest = get_post($item['ID']);
		$mangopay_id = edd_get_payment_key($item['ID']);
		$payment_type = 'Carte';
		$payment_state = edd_get_payment_status( $post_invest, true );;
		if (strpos($mangopay_id, 'wire_') !== FALSE) {
			$payment_type = 'Virement';
			$contribution_id = substr($mangopay_id, 5);
			$mangopay_contribution = ypcf_mangopay_get_withdrawalcontribution_by_id($contribution_id);
			$mangopay_is_completed = (isset($mangopay_contribution));
			$mangopay_is_succeeded = (isset($mangopay_contribution) && $mangopay_contribution->Status == 'ACCEPTED');
//			if ($mangopay_is_succeeded) $payment_state = 'Validé';
//			else if ($mangopay_is_completed) $payment_state = 'Echoué';
		} else {
			$mangopay_contribution = ypcf_mangopay_get_contribution_by_id($mangopay_id);
			$mangopay_is_completed = (isset($mangopay_contribution->IsCompleted) && $mangopay_contribution->IsCompleted);
			$mangopay_is_succeeded = (isset($mangopay_contribution->IsSucceeded) && $mangopay_contribution->IsSucceeded);
//			if ($mangopay_is_succeeded) $payment_state = 'Validé';
//			else if ($mangopay_is_completed) $payment_state = 'Echoué';
		}
		$investment_state = 'Validé';
		if ($campaign->campaign_status() == 'archive') {
		    $investment_state = 'Annulé';
			
		    $refund_id = get_post_meta($item['ID'], 'refund_id', TRUE);
		    if (isset($refund_id) && !empty($refund_id)) {
			$refund_obj = ypcf_mangopay_get_refund_by_id($refund_id);
			$investment_state = 'Remboursement en cours';
			if ($refund_obj->IsCompleted) {
			    if ($refund_obj->IsSucceeded) {
				$investment_state = 'Remboursé';
			    } else {
				$investment_state = 'Remboursement échoué';
			    }
			}
			
		    } else {
			$refund_id = get_post_meta($item['ID'], 'refund_wire_id', TRUE);
			if (isset($refund_id) && !empty($refund_id)) {
			    $investment_state = 'Remboursé';
			}
		    }
		}
		
		$user_data = get_userdata($item['user']);
                
                //Liste des données à afficher pour la ligne traitée
                $datacolonnes= array(bp_core_get_userlink($item['user']),
                    $user_data->last_name,
                    $user_data->first_name,
                    $user_data->user_birthday_day.'/'.$user_data->user_birthday_month.'/'.$user_data->user_birthday_year,
                    $user_data->user_birthplace,
                    $user_data->user_nationality,
                    $user_data->user_city,
                    $user_data->user_address,
                    $user_data->user_postal_code,
                    $user_data->user_country,
                    $user_data->user_email,
                    $user_data->user_mobile_phone,
                    $item['amount'].'€',
                    date_i18n( /*get_option('date_format')*/ 'd/m/Y', strtotime( get_post_field( 'post_date', $item['ID'] ) ) ),
                    $payment_type,
                    $payment_state,
                    $item['signsquid_status_text']
                );
                if ($is_campaign_over) { $datacolonnes[]=$investment_state; }
                $affichedonnees = array_combine($classcolonnes, $datacolonnes);
                ?>
                
                <tr style="background-color: <?php echo $bgcolor; ?>">
                <?php
                    //Ecriture de la ligne
                    foreach($affichedonnees as $class=>$data){
                        /*echo '<td class="'.$class.'">'.$data.'</td>';*/
                        echo '<td class="'.$class.'" ';
                        if (($displaycolonnes[$class]) == false){echo 'hidden=""';}
                        echo '>'.$data.'</td>';
                    }
		?>
                </tr>
                <?php
//	    }
	}
	?>
    </tbody>
    
    <tfoot style="background-color: #CCC;">
    <tr>
        <?php foreach($colonnes as $class=>$titre){
            //Ecriture des nom des colonnes en bas
            echo '<td class="'.$class.'" ';
            if (($displaycolonnes[$class]) == false){echo 'hidden=""';}
            echo '>'.$titre.'</td>';}?>        
    </tr>
    </tfoot>
</table>
</div>


<?php
}
?>