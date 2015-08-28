<h1>Liste des votants</h1>
<?php if (isset($_GET['campaign_id'])) {
    global $wpdb;
    $table_name = $wpdb->prefix . "ypcf_project_votes";
    $campaign_id = $_GET['campaign_id'];
    
    $list_user_voters = $wpdb->get_results( "SELECT user_id, invest_sum, date FROM ".$table_name." WHERE post_id = ".$campaign_id." AND validate_project = 1" );
    
    $colonnes = array('Utilisateur', 
        'Nom', 
        'Prénom', 
        'Ville',
        'Montant promis',
        'Date du vote');
?>
<em>Seules les personnes ayant voté "Oui" sont affich&eacute;es.</em><br/><br/>
<em>Si vous envoyez un mail group&eacute; aux votants, pensez &agrave; les mettre dans le champ CCI, pour qu&apos;ils n&apos;aient pas acc&egrave;s aux adresses des autres.</em><br /><br />


<div class="wdg-datatable" >
<table class="display" id="voters-table" cellspacing="0" width="100%">
    
    <thead>
    <tr>
        <?php foreach($colonnes as $titre){
            //Ecriture des nom des colonnes en haut
            echo '<td>'.$titre.'</td>';}?>
    </tr>
    </thead>

    <tbody>
	<?php
	foreach ( $list_user_voters as $item ) {
            $user_data = get_userdata($item->user_id);?>
            <tr>
            <?php
                $colonnesres = array(
                    bp_core_get_userlink($item->user_id),
                    $user_data->last_name,
                    $user_data->first_name,
                    $user_data->user_city,
                    $item->invest_sum,
                    $item->date
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
    
    <tfoot>
    <tr>
        <?php foreach($colonnes as $titre){
            //Ecriture des nom des colonnes en haut
            echo '<td>'.$titre.'</td>';}?>
    </tr>
    </tfoot>
</table>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
        //Ajoute mise en page et interactions du tableau
        // Ajoute un champ de filtre à chaque colonne dans le footer
        $('#voters-table tfoot td').each( function () {
            $(this).prepend( '<input type="text" placeholder="Filtrer par :" class="col-filter"/><br/>' );
        } );
            // Ajoute les actions de filtrage
                            $("#voters-table tfoot input").on( 'keyup change', function () {
                                table
                                    .column( $(this).parent().index()+':visible' )
                                    .search( this.value )
                                    .draw();
                            } );
                            
            //Récupère le tri par défaut 
            sortColumn = 0;
            $('#voters-table thead td').each(function(index){
                if($(this).text() === "Date du vote"){sortColumn = index;};
            });
            
            var table = $('#voters-table').DataTable({
                order: [[ sortColumn, "desc" ]], //Colonne à trier (date)

                dom: 'RC<"clear">lfrtip',
                lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tous"]], //nombre d'élements possibles
                iDisplayLength: 25,//nombre d'éléments par défaut

                //Boutons de sélection de colonnes
                colVis: {
                    buttonText: "Afficher/cacher colonnes",
                    restore: "Restaurer",
                    showAll: "Tout afficher",
                    showNone: "Tout cacher",
                    overlayFade: 100
                },
                language: {
                        "sProcessing":     "Traitement en cours...",
                        "sSearch":         "Rechercher&nbsp;:",
                    "sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
                        "sInfo":           "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
                        "sInfoEmpty":      "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
                        "sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
                        "sInfoPostFix":    "",
                        "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords":    "Aucun &eacute;l&eacute;ment &agrave; afficher",
                        "sEmptyTable":     "Aucune donn&eacute;e disponible dans le tableau",
                        "oPaginate": {
                                "sFirst":      "Premier",
                                "sPrevious":   "Pr&eacute;c&eacute;dent",
                                "sNext":       "Suivant",
                                "sLast":       "Dernier"
                        },
                        "oAria": {
                                "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                                "sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
                        }
                }
            });
        });
    </script>

</div>
<?php } ?>