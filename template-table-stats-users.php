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
$number = 1000;
$offset = (isset($_GET['offset'])) ? $_GET['offset'] * $number : 0;

get_header();
global $stylesheet_directory_uri, $wpdb;
$table_jcrois = $wpdb->prefix . "jycrois";
$table_vote = $wpdb->prefix . WDGCampaignVotes::$table_name_votes;
$input_poll = filter_input( INPUT_GET, 'poll' );
$input_official_data = filter_input( INPUT_GET, 'official_data' );
?>

<div id="content">
    <div class="padder">
		<br><br><br><br><br>
		
		<?php if ( $input_official_data == '1' ): ?>
			<?php
				$count = 0;
				$count_1_50 = 0;
				$count_51_100 = 0;
				$count_101_250 = 0;
				$count_251_1000 = 0;
				$count_1000 = 0;
				$count_invest_by_user_in_france = 0;
				
				$amount_total = 0;
				$amount_out_of_euro = 0;
			
				$today = new DateTime();
				$payments = edd_get_payments( array(
					'number'	=> -1,
					'status'	=> 'publish',
					'year'		=> $today->format( 'Y' ) - 1
				) );
				if ( $payments ) {
					foreach ( $payments as $payment ) {
						$count++;
						$amount = edd_get_payment_amount( $payment->ID );
						$amount_total += $amount;
						if ( $amount < 51 ) {
							$count_1_50++;
						} elseif ( $amount < 101 ) {
							$count_51_100++;
						} elseif ( $amount < 251 ) {
							$count_101_250++;
						} elseif ( $amount < 1001 ) {
							$count_251_1000++;
						} else {
							$count_1000++;
						}
						
						$user_info = edd_get_payment_meta_user_info( $payment->ID );
						$user_id = (isset( $user_info['id'] ) && $user_info['id'] != -1) ? $user_info['id'] : $user_info['email'];
						if ( !WDGOrganization::is_user_organization( $user_id ) ) {
							$WDGUser = new WDGUser( $user_id );
							$country_iso_code = $WDGUser->get_country( 'iso2' );
							if ( $country_iso_code == 'FR' ) {
								$count_invest_by_user_in_france++;
							}
							$euro_list = array( 'DE', 'AT', 'BE', 'BG', 'CY', 'HR', 'DK', 'ES', 'EE', 'FI', 'FR', 'GR', 'HU', 'GR', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'CZ', 'RO', 'GB', 'SK', 'SI', 'SE' );
							if ( !in_array( $country_iso_code, $euro_list ) ) {
								$amount_out_of_euro += $amount;
							}
						}
					}
				}
			?>
			Investissements totaux : <?php echo $count; ?><br>
			Investissements inf 51 € : <?php echo $count_1_50; ?> (<?php echo round( $count_1_50 / $count * 100, 2 ); ?> %)<br>
			Investissements entre 51 et 100 € : <?php echo $count_51_100; ?> (<?php echo round( $count_51_100 / $count * 100, 2 ); ?> %)<br>
			Investissements entre 101 et 250 € : <?php echo $count_101_250; ?> (<?php echo round( $count_101_250 / $count * 100, 2 ); ?> %)<br>
			Investissements entre 251 et 1000 € : <?php echo $count_251_1000; ?> (<?php echo round( $count_251_1000 / $count * 100, 2 ); ?> %)<br>
			Investissements plus de 1000 € : <?php echo $count_1000; ?> (<?php echo round( $count_1000 / $count * 100, 2 ); ?> %)<br>
			Investissements par pers. phys. en France : <?php echo $count_invest_by_user_in_france; ?> (<?php echo round( $count_invest_by_user_in_france / $count * 100, 2 ); ?> %)<br>
			Montants totaux : <?php echo $amount_total; ?> €<br>
			Montants dont provenance hors UE : <?php echo $amount_out_of_euro; ?> € (<?php echo round( $amount_out_of_euro / $amount_total * 100, 2 ); ?> %)<br>
		
		<?php elseif ( $input_poll == 'warranty' ): ?>
		<?php $poll_answers = WDGWPREST_Entity_PollAnswer::get_list( FALSE, FALSE, $input_poll ); ?>
		<h1>Résultats sondage garantie</h1>
		<div class="wdg-datatable">
			<table width="100%">
				<thead>
					<tr>
						<td>Date</td>
						<td>Contexte</td>
						<td>Montant (contexte)</td>
						<td>Investirait montant différent</td>
						<td>Investirait montant</td>
						<td>Investirait sur d'autres projets</td>
						<td>Investirait nombre</td>
						<td>E-mail</td>
						<td>Age</td>
						<td>Code postal</td>
						<td>Sexe</td>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td>Date</td>
						<td>Contexte</td>
						<td>Montant (contexte)</td>
						<td>Investirait montant différent</td>
						<td>Investirait montant</td>
						<td>Investirait sur d'autres projets</td>
						<td>Investirait nombre</td>
						<td>E-mail</td>
						<td>Age</td>
						<td>Code postal</td>
						<td>Sexe</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php foreach ( $poll_answers as $answer ): ?>
					<?php $answers_decoded = json_decode( $answer->answers ); ?>
					<tr>
						<td><?php echo $answer->date; ?></td>
						<td><?php echo $answer->context; ?></td>
						<td><?php echo $answer->context_amount; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-more-amount' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-amount-with-warranty' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-more-number' }; ?></td>
						<td><?php echo $answers_decoded->{ 'would-invest-number-per-year-with-warranty' }; ?></td>
						<td><?php echo $answer->user_email; ?></td>
						<td><?php echo $answer->user_age; ?></td>
						<td><?php echo $answer->user_postal_code; ?></td>
						<td><?php echo $answer->user_gender; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				
			</table>
		</div>
		
		
		<?php elseif ( $input_poll == 'source' ): ?>
		<?php $poll_answers = WDGWPREST_Entity_PollAnswer::get_list( FALSE, FALSE, $input_poll ); ?>
		<h1>Résultats sondage source</h1>
		<div class="wdg-datatable">
			<table width="100%">
				<thead>
					<tr>
						<td>Date</td>
						<td>Contexte</td>
						<td>Montant (contexte)</td>
						<td>Connait le PP</td>
						<td>Intéret secteur</td>
						<td>Diversifier</td>
						<td>Impact</td>
						<td>Autre</td>
						<td>Autre (txt)</td>
						<td>Connu par</td>
						<td>Autre (txt)</td>
						<td>Venu via</td>
						<td>Autre (txt)</td>
						<td>E-mail</td>
						<td>Age</td>
						<td>Code postal</td>
						<td>Sexe</td>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td>Date</td>
						<td>Contexte</td>
						<td>Montant (contexte)</td>
						<td>Connait le PP</td>
						<td>Intéret secteur</td>
						<td>Diversifier</td>
						<td>Impact</td>
						<td>Autre</td>
						<td>Autre (txt)</td>
						<td>Connu par</td>
						<td>Autre (txt)</td>
						<td>Venu via</td>
						<td>Autre (txt)</td>
						<td>E-mail</td>
						<td>Age</td>
						<td>Code postal</td>
						<td>Sexe</td>
					</tr>
				</tfoot>
				
				<tbody>
					<?php foreach ( $poll_answers as $answer ): ?>
					<?php $answers_decoded = json_decode( $answer->answers ); ?>
					<tr>
						<td><?php echo $answer->date; ?></td>
						<td><?php echo $answer->context; ?></td>
						<td><?php echo $answer->context_amount; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'know-project-manager' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'interrested-by-domain' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'diversify-savings' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'looking-for-positive-impact' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo ( $answers_decoded->motivations->{ 'other-motivations' } == '1' ) ? 'Oui' : 'Non'; ?></td>
						<td><?php echo $answers_decoded->{ 'other-motivations-to-invest' }; ?></td>
						<td><?php echo $answers_decoded->{ 'how-the-fundraising-was-known' }; ?></td>
						<td><?php echo $answers_decoded->{ 'other-source-to-know-the-fundraising' }; ?></td>
						<td><?php echo $answers_decoded->{ 'where-user-come-from' }; ?></td>
						<td><?php echo $answers_decoded->{ 'other-source-where-the-user-come-from' }; ?></td>
						<td><?php echo $answer->user_email; ?></td>
						<td><?php echo $answer->user_age; ?></td>
						<td><?php echo $answer->user_postal_code; ?></td>
						<td><?php echo $answer->user_gender; ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				
			</table>
		</div>
		
		
		<?php else: ?>
		<h1>Tableau complet de la liste des utilisateurs</h1>
		
		<div class="wdg-datatable">
			<table width="100%">
				<thead>
					<tr>
						<td>Prénom Nom</td>
						<td>e-mail</td>
						<td>Entité morale</td>
						<td>Sexe</td>
						<td>Date de naissance</td>
						<td>Adresse</td>
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
						<td>Entité morale</td>
						<td>Sexe</td>
						<td>Date de naissance</td>
						<td>Adresse</td>
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
							<td><?php echo ( WDGOrganization::is_user_organization( $user->ID ) ? "OUI" : "NON" ); ?></td>
							<td><?php if ($user->get('user_gender') == "female") { echo 'F'; } elseif ($user->get('user_gender') == "male") { echo 'M'; } ?></td>
							<td><?php echo $user->get('user_birthday_year') . '-' . $user->get('user_birthday_month') . '-' . $user->get('user_birthday_day'); ?></td>
							<td><?php echo $user->get('user_address'); ?></td>
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
	
		<?php
		$result = count_users();
		$user_count = $result['total_users'];
		$nb_page = ceil( $user_count / 1000 );
		?>
		Pages :
		<a href="<?php echo home_url('/statistiques-utilisateurs/'); ?>">1</a>
		<?php for ($i = 2; $i <= $nb_page; $i++): ?>
		| <a href="<?php echo home_url('/statistiques-utilisateurs/'); ?>?offset=<?php echo ($i-1); ?>"><?php echo $i; ?></a>
		<?php endfor; ?>
		<?php endif; ?>
		
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