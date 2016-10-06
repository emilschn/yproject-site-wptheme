<?php 
/**
 * Template Name: Tableau des statistiques utilisateurs
 * 
 */ 
if ( !current_user_can('manage_options') ) {
    global $wp_query;
    $wp_query->set_404();
    status_header( 404 );
    get_template_part( 404 ); exit();
}
global $disable_logs;
$disable_logs = TRUE;
$number = 4000;
$offset = (isset($_GET['offset'])) ? $_GET['offset'] * $number : 0;

get_header();
global $stylesheet_directory_uri, $wpdb;
$table_jcrois = $wpdb->prefix . "jycrois";
$table_vote = $wpdb->prefix . WDGCampaignVotes::$table_name_votes;
?>

<div id="content">
    <div class="padder">
		<h1>Tableau complet de la liste des utilisateurs</h1>
		
		<div class="wdg-datatable">
			<table width="100%">
				<thead>
					<tr>
						<td>Prénom Nom</td>
						<td>e-mail</td>
						<td>Sexe</td>
						<td>Date de naissance</td>
						<td>CP</td>
						<td>Ville</td>
						<td>Nb projets suivis</td>
						<td>Nb projets votés</td>
						<td>Nb investissements</td>
						<td>Montants investis</td>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td>Prénom Nom</td>
						<td>e-mail</td>
						<td>Sexe</td>
						<td>Date de naissance</td>
						<td>CP</td>
						<td>Ville</td>
						<td>Nb projets suivis</td>
						<td>Nb projets votés</td>
						<td>Nb investissements</td>
						<td>Montants investis</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php $user_list = get_users( array('number' => $number, 'offset' => $offset) ); ?>
					<?php foreach ($user_list as $user): ?>
						<?php
						$sql = "SELECT COUNT(post_meta.meta_value) AS nb_invest, SUM(post_meta.meta_value) AS sum_invest, ";
							$sql .= "(SELECT COUNT(jycrois.campaign_id) FROM ".$table_jcrois." jycrois WHERE jycrois.user_id = " .$user->ID. ") AS nb_follow, ";
							$sql .= "(SELECT COUNT(vote.post_id) FROM ".$table_vote." vote WHERE vote.user_id = " .$user->ID. ") AS nb_votes ";
						$sql .= "FROM ".$wpdb->postmeta." post_meta ";
						$sql .= "LEFT JOIN ".$wpdb->posts." post ON post.ID = post_meta.post_id ";
						$sql .= "LEFT JOIN ".$wpdb->postmeta." post_meta2 ON post_meta.post_id = post_meta2.post_id ";
						$sql .= "WHERE post.post_type='edd_payment' AND post.post_status='publish' AND post_meta.meta_key = '_edd_payment_total' ";
						$sql .= "AND post_meta2.meta_key = '_edd_payment_user_id' AND post_meta2.meta_value = " . $user->ID;
						
						$user_results = $wpdb->get_results( $sql );
						$user_result = $user_results[0];
						?>
						<tr>
							<td><?php echo $user->user_firstname . ' ' . $user->user_lastname; ?></td>
							<td><?php echo $user->user_email; ?></td>
							<td><?php if ($user->get('user_gender') == "female") { echo 'F'; } elseif ($user->get('user_gender') == "male") { echo 'M'; } ?></td>
							<td><?php echo $user->get('user_birthday_year') . '-' . $user->get('user_birthday_month') . '-' . $user->get('user_birthday_day'); ?></td>
							<td><?php echo $user->get('user_postal_code'); ?></td>
							<td><?php echo $user->get('user_city'); ?></td>
							<td><?php echo $user_result->nb_follow; ?></td>
							<td><?php echo $user_result->nb_votes; ?></td>
							<td><?php echo $user_result->nb_invest; ?></td>
							<td><?php echo $user_result->sum_invest; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				
			</table>
		</div>
	
		<a href="https://www.wedogood.co/statistiques-utilisateurs">Voir la première partie</a><br />
		<a href="https://www.wedogood.co/statistiques-utilisateurs?offset=1">Voir la deuxième partie</a>
		
    </div>
</div>

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/jquery.dataTables.min.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/dataTables.colReorder.min.js"></script>
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/colReorder.dataTables.min.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/dataTables.select.min.js"></script>
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/select.dataTables.min.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/dataTables.buttons.min.js"></script>
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/buttons.dataTables.min.css" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/buttons.colVis.min.js"></script>
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/jszip.min.js"></script>
		
<script type="text/javascript">
	jQuery(document).ready( function($) {
		// Ajoute mise en page et interactions du tableau
		// Ajoute un champ de filtre à chaque colonne dans le footer
		$('.wdg-datatable table tfoot td').each( function () {
			$(this).prepend( '<input type="text" placeholder="Filtrer par :" class="col-filter"/><br/>' );
		} );
		
		// Ajoute les actions de filtrage
		$(".wdg-datatable table tfoot input").on( 'keyup change', function () {
			table
				.column( $(this).parent().index()+':visible' )
				.search( this.value )
				.draw();
		} );

		//Récupère le tri par défaut 
		sortColumn = 0;
		$('.wdg-datatable table thead td').each(function(index){
			if ($(this).text() === "Nb projets investis") { sortColumn = index; };
		});

		var table = $('.wdg-datatable table').DataTable({
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

<?php get_footer();