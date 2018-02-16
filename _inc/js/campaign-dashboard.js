function WDGCampaignDashboard() {
    this.initWithHash();
	this.initMenu();
}

/**
 * Initialise l'affichage avec le # de l'url
 */
WDGCampaignDashboard.prototype.initWithHash = function() {

	var sCurrentTab = window.location.hash.substring(1);
	if (sCurrentTab !== '') {
		this.switchTab( sCurrentTab );
	} else {
		this.switchTab( 'home' );
	}
	
};

/**
 * Initialise le menu
 */
WDGCampaignDashboard.prototype.initMenu = function() {
	
	var self = this;
	$( 'ul.nav-menu li a' ).each( function() {
		$( this ).click( function() {
			self.switchTab( $( this ).data( 'tab' ) );
		} );
	} );
	
};

/**
 * Change d'onglet
 */
WDGCampaignDashboard.prototype.switchTab = function(sType) {
	
	$( 'ul.nav-menu li' ).removeClass( 'selected' );
	$( 'div#item-body > div.item-body-tab' ).hide();
	
	$( 'ul.nav-menu li#menu-item-' + sType ).addClass( 'selected' );
	$( 'div#item-body > div#item-body-' + sType ).show();
	
};

WDGCampaignDashboard.prototype.getContactsTable = function(inv_data, campaign_id) {
	var self = this;
	
	$.ajax({
		'type' : "POST",
		'url' : ajax_object.ajax_url,
		'data': {
			'action':'create_contacts_table',
			'id_campaign':campaign_id,
			'data' : inv_data
		}
	}).done(function(result){
		//Affiche resultat requete Ajax une fois reçue
		$('#ajax-contacts-load').after(result);
		$('#ajax-loader-img').hide();//On cache la roue de chargement.

		YPUIFunctions.initQtip();

		//Création du tableau dynamique dataTable
		self.table = $('#contacts-table').DataTable({
			scrollX: '100%',
			scrollY: '70vh', //Taille max du tableau : 70% de l'écran
			scrollCollapse: true, //Diminue taille du tableau si peu d'éléments*/

			paging: false, //Pas de pagination, affiche tous les éléments yolo
			order: [[result_contacts_table['default_sort'],"desc"]],

			colReorder: { //On peut réorganiser les colonnes
				fixedColumnsLeft: result_contacts_table['id_column_index']+1 //Les 5 colonnes à gauche sont fixes
			},
			fixedColumns : {
				leftColumns: result_contacts_table['id_column_index']+1
			},


			columnDefs: [
				{
					targets: result_contacts_table['array_hidden'], //Cache colonnes par défaut
					visible: false
				},{
					targets: [result_contacts_table['id_column_index']], //Cache colonnes par défaut
					visible: false
				},{
					className: 'select-checkbox',
					targets : 0,
					orderable: false,
				},{
					width: "30px",
					className: "dt-body-center nopadding",
					targets: [2,3,4]
				}
			],

			//Permet la sélection de lignes
			select: {
				style: 'multi', //Sélection multiple
				selector: 'td:first-child'
			},

			dom: 'Bfrtip',
			buttons: [
				{
					text: '<i class="fa fa-square-o" aria-hidden="true"></i> Sélectionner les éléments affichés',
					action: function () {
						self.table.rows( { search: 'applied' } ).select();
					}
				},{
					//Bouton envoi de mail
					extend: 'selected',
					text: '<i class="fa fa-envelope" aria-hidden="true"></i> Envoyer un mail',
					action: function ( e, dt, button, config ) {
						$("#send-mail-tab").slideDown();
						var target = $(this).data("target");
						self.scrollTo($("#send-mail-tab"));
					}
					//TODO : Scroller jusqu'au panneau
				},


				{
					extend: 'collection',
					text: '<i class="fa fa-eye" aria-hidden="true"></i> Informations à afficher',
					buttons: [{
						//Bouton d'affichage de colonnes
						extend: 'colvis',
						text: '<i class="fa fa-columns" aria-hidden="true"></i> Colonnes à afficher',
						columns: ':gt('+result_contacts_table['id_column_index']+')', //On ne peut pas cacher les 5 premières colonnes
						collectionLayout: 'two-column'
					},{
						extend: 'colvisGroup',
						text: 'Tout afficher',
						show: ':gt('+result_contacts_table['id_column_index']+'):hidden'
					},{
						extend: 'colvisGroup',
						text: 'Tout masquer',
						hide: ':gt('+result_contacts_table['id_column_index']+')'
					},{
						extend: 'colvisRestore',
						text: '<i class="fa fa-refresh" aria-hidden="true"></i> Rétablir colonnes par défaut'
					}]
				},

				//Menu d'export
				{
					extend: 'collection',
					text: '<i class="fa fa-download" aria-hidden="true"></i> Exporter',
					buttons: [ {
						//Bouton d'export excel
						extend: 'excel',
						text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Fichier Excel',
						exportOptions: {
							modifier: {
								columns: ':visible'
							}
						}
					},{
						//Bouton d'export impression
						extend: 'print',
						text: '<i class="fa fa-print" aria-hidden="true"></i> Imprimer',
						exportOptions: {
							modifier: {
								columns: ':visible'
							}
						}
					} ]
				}
			],

			language : {
				"sProcessing":     "Traitement en cours...",
				"sSearch":         "Rechercher&nbsp;:",
				"sLengthMenu":     "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo":           "Affichage de _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty":      "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sInfoFiltered":   "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix":    "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords":    "Aucun &eacute;l&eacute;ment",
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
				},
				select: {
					rows: {
						_: "<br /><b>%d</b> contacts sélectionnés",
						0: '<br />Cliquez sur un contact pour le sélectionner',
						1: "<br /><b>1</b> contact sélectionné"
					}
				}
			}
		});
		self.table.columns.adjust();

		var mailButtonDefault = self.table.button(1).text()
		self.table.on("select.dt deselect.dt", function ( e, dt, type, indexes ) {
			//Maj Bouton de Mail
			var selectedCount = self.table.rows({ selected: true }).count();
			if(selectedCount==0){
				self.table.button(1).text(mailButtonDefault);
				$("#send-mail-tab").slideUp();
			} else {
				self.table.button(1).text(mailButtonDefault+" ("+selectedCount+")");
			}


			//Maj Bouton de sélection
			var allContained = true;
			self.table.rows( { search:'applied' } ).every( function ( rowIdx, tableLoop, rowLoop ) {
				if($.inArray(rowIdx, self.table.rows({ selected: true }).indexes())==-1){
					allContained= false;
				}
			} );

			if(allContained){
				self.table.button(0).text('<i class="fa fa-check-square-o" aria-hidden="true"></i> Déselectionner les éléments affichés');
				self.table.button(0).action(function () {
					self.table.rows( { search: 'applied' } ).deselect();
				});
			} else {
				self.table.button(0).text('<i class="fa fa-square-o" aria-hidden="true"></i> Sélectionner les éléments affichés');
				self.table.button(0).action(function () {
					self.table.rows( { search: 'applied' } ).select();
				});
			}

			//Maj Champs de Mail
			$("#nb-mailed-contacts").text(selectedCount);

			//Maj liste des identifiants à mailer
			var recipients_array = [];
			$.each(self.table.rows({ selected: true }).data(), function(index, element){
				recipients_array.push(element[result_contacts_table['id_column_index']]);
			});
			$("#mail_recipients").val(recipients_array);
		} );

		// Champs de filtrage
		$( self.table.table().container() ).on( 'keyup', 'tfoot .text input', function () {
			self.table
				.column( $(this).data('index') )
				.search( this.value )
				.draw();
		} );
		$( self.table.table().container() ).on( 'change', 'tfoot .check input', function () {
			if($(this).is(":checked")){
				self.table
					.column( $(this).data('index') )
					.search("1")
					.draw();
			}
			else {
				self.table
					.column( $(this).data('index') )
					.search("")
					.draw();
			}
		} );


	}).fail(function(){
		$('#ajax-contacts-load').after("<em>Le chargement du tableau a échoué</em>");
		$('#ajax-loader-img').hide();//On cache la roue de chargement.
	});
	
};

WDGCampaignDashboard.prototype.scrollTo = function( target ) {
	$( 'html, body, .wdg-lightbox-padder' ).animate(
		{ scrollTop: target.offset().top - 75 },
		'slow'
	);
};

var wdgCampaignDashboard;
$( function(){
    wdgCampaignDashboard = new WDGCampaignDashboard();
} );