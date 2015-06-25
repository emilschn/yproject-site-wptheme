<h1>Liste des votants</h1>
<?php if (isset($_GET['campaign_id'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . "ypcf_project_votes";
    $campaign_id = $_GET['campaign_id'];
    
    $list_user_voters = $wpdb->get_results( "SELECT user_id, invest_sum FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1" );
    
    $colonnes = array('Utilisateur', 'Nom', 'Prénom', 'Ville', 'Mail', 'Montant promis');
?>
<em>Seuls les personnes ayant voté "Oui" sont affich&eacute;es.</em><br/><br/>
<em>Si vous envoyez un mail group&eacute; aux votants, pensez &agrave; les mettre dans le champ CCI, pour qu&apos;ils n&apos;aient pas acc&egrave;s aux adresses des autres.</em><br /><br />


<div class="tablescroll" >
<table class="wp-list-table" cellspacing="0">
    
    <thead style="background-color: #CCC;">
    <tr>
        <?php foreach($colonnes as $titre){
            //Ecriture des nom des colonnes en haut
            echo '<td>'.$titre.'</td>';}?>
    </tr>
    </thead>

    <tbody>
	<?php
	$i = -1;
	foreach ( $list_user_voters as $item ) {
            $i++;
            $bgcolor = ($i % 2 == 0) ? "#FFF" : "#EEE";
            $user_data = get_userdata($item->user_id);?>

            <tr style="background-color: <?php echo $bgcolor; ?>">
            <?php
                $colonnesres = array(
                    bp_core_get_userlink($item->user_id),
                    $user_data->last_name,
                    $user_data->first_name,
                    $user_data->user_city,
                    $user_data->user_email,
                    $item->invest_sum
                );

                //Ecriture de la ligne
                foreach($colonnesres as $item){
                    echo '<td>'.$item.'</td>';
                }
            ?>
            </tr>
            <?php
	}
	?>
    </tbody>
    
    <tfoot style="background-color: #CCC;">
    <tr>
        <?php foreach($colonnes as $titre){
            //Ecriture des nom des colonnes en haut
            echo '<td>'.$titre.'</td>';}?>
    </tr>
    </tfoot>
</table>
</div>
<?php } ?>