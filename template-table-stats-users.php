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
						<td>Nb projets investis</td>
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
						<td>Nb projets investis</td>
						<td>Montants investis</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php $user_list = get_users(); ?>
					<?php foreach ($user_list as $user): ?>
						<?php 
						$count_follow = $wpdb->get_var( "SELECT count(campaign_id) FROM ".$table_jcrois." WHERE user_id = ".$user->ID );
						$count_votes = $wpdb->get_var( "SELECT count(post_id) FROM ".$table_vote." WHERE user_id = ".$user->ID );
						$count_invest = $wpdb->get_var( "SELECT count(p.ID) FROM ".$wpdb->posts." p LEFT JOIN ".$wpdb->postmeta." pm ON p.ID = pm.post_id WHERE p.post_type='edd_payment' AND p.post_status='publish' AND pm.meta_key = '_edd_payment_user_id' AND pm.meta_value = ".$user->ID );
						$request = "SELECT sum(pm2.meta_value) "
									. "FROM ".$wpdb->postmeta." pm2 "
									. "LEFT JOIN ".$wpdb->posts." p ON p.ID = pm2.post_id "
									. "LEFT JOIN ".$wpdb->postmeta." pm ON pm2.post_id = pm.post_id "
									. "WHERE p.post_type='edd_payment' AND p.post_status='publish' AND pm2.meta_key = '_edd_payment_total' "
									. "AND pm.meta_key = '_edd_payment_user_id' AND pm.meta_value = " . $user->ID;
						$amount_invest = $wpdb->get_var( $request );
						?>
						<tr>
							<td><?php echo $user->user_firstname . ' ' . $user->user_lastname; ?></td>
							<td><?php echo $user->user_email; ?></td>
							<td><?php if ($user->get('user_gender') == "female") { echo 'F'; } elseif ($user->get('user_gender') == "male") { echo 'M'; } ?></td>
							<td><?php echo $user->get('user_birthday_year') . '-' . $user->get('user_birthday_month') . '-' . $user->get('user_birthday_day'); ?></td>
							<td><?php echo $user->get('user_postal_code'); ?></td>
							<td><?php echo $user->get('user_city'); ?></td>
							<td><?php echo $count_follow; ?></td>
							<td><?php echo $count_votes; ?></td>
							<td><?php echo $count_invest; ?></td>
							<td><?php echo $amount_invest; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				
			</table>
		</div>
	
    </div>
</div>

<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/jquery.dataTables.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/dataTables.colVis.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $stylesheet_directory_uri; ?>/_inc/css/dataTables/dataTables.colReorder.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/dataTables.colVis.js"></script>
<script type="text/javascript" src="<?php echo $stylesheet_directory_uri; ?>/_inc/js/dataTables/dataTables.colReorder.js"></script>
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