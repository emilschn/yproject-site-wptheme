function WDGCampaignTurnoverSimulator() {
	this.initFinanceFormEvents();
	this.initAdjustments();
	this.initDisplayedInfos();
	this.refreshYears();
}

WDGCampaignTurnoverSimulator.prototype.initFinanceFormEvents = function () {
	var self = this;

	$('#select-new_funding_duration').change(function () {
		self.refreshYears();
	});
	$('#new_funding_duration_infinite_estimation').change(function () {
		self.refreshYears();
	});

	$('#new_maximum_goal, #new_roi_percent_estimated').bind('keyup click', function () {
		self.refreshTurnover();
	});

}


WDGCampaignTurnoverSimulator.prototype.initAdjustments = function () {
	var self = this;

	if ($('#field-declarations_checked').length > 0) {
		$('#field-declarations_checked .select-multiple-items label.radio-label').click(function () {
			// Dans un setTimeout, sinon l'événement est déclenché avant que la checkbox soit considérée comme cochée
			setTimeout(function () { self.refreshAdjustmentDeclarationsTurnover(); }, 800);
		});
	}

	if ($('#field-documents').length > 0) {
		$('#field-documents .select-multiple-items label.radio-label').click(function () {
			// Dans un setTimeout, sinon l'événement est déclenché avant que la checkbox soit considérée comme cochée
			setTimeout(function () { self.refreshAdjustmentDocumentsTurnover(); }, 800);
		});
	}
}

WDGCampaignTurnoverSimulator.prototype.refreshAdjustmentDeclarationsTurnover = function () {
	var self = this;

	var nAmount = 0;
	var sString = '';
	$('#field-declarations_checked .select-multiple-items label.radio-label input').each(function () {
		if ($(this).is(':checked')) {
			var nSizeOfDeclaration = 12; // Taille de la chaine 'declaration-'
			var nDeclarationId = $(this).val().substring(nSizeOfDeclaration);
			if ($('#list-declarations #declaration-item-more-' + nDeclarationId).length > 0) {
				var nDeclarationTurnoverTotal = $('#list-declarations #declaration-item-more-' + nDeclarationId).data('turnover-total');
				if (nDeclarationTurnoverTotal != undefined) {
					if (sString != '') {
						sString += '+ ';
					}
					sString += nDeclarationTurnoverTotal + ' € ';
					nAmount += nDeclarationTurnoverTotal;
				}
			}
		}
	});

	sString += '=> Total déclaré : ' + nAmount.toString() + ' €.';
	if ($('#field-declarations_checked .turnover-total-description').length > 0) {
		$('#field-declarations_checked .turnover-total-description').html(sString);
	} else {
		$('#field-declarations_checked').append('<div class="turnover-total-description">' + sString + '</div>');
	}
}

WDGCampaignTurnoverSimulator.prototype.refreshAdjustmentDocumentsTurnover = function () {
	var self = this;

	var nAmount = 0;
	$('#field-documents .select-multiple-items label.radio-label input').each(function () {
		if ($(this).is(':checked')) {
			var nSizeOfDeclaration = 5; // Taille de la chaine 'file-'
			var nDeclarationId = $(this).val().substring(nSizeOfDeclaration);
			if ($('.adjustments-documents-list .adjustment-document-amount-' + nDeclarationId).length > 0) {
				var nDeclarationTurnoverTotal = $('.adjustments-documents-list .adjustment-document-amount-' + nDeclarationId).data('value');
				if (nDeclarationTurnoverTotal != undefined) {
					nAmount += nDeclarationTurnoverTotal;
				}
			}
		}
	});

	$('#form-add-adjustment #field-turnover_checked #turnover_checked').val(nAmount);
	$('#form-add-adjustment #field-turnover_checked #turnover_checked').change();
}


WDGCampaignTurnoverSimulator.prototype.initDisplayedInfos = function () {
	var self = this;

	var sSymbol = $('#estimated-turnover').data('symbol');
	$('#total-roi').html('0 ' + sSymbol);
	$('#total-funding').html('---');
	$('#medium-rend').html('--- %').css('color', '#2B2C2C');
	$('#gain').html('');
	var nFundingDuration = self.getFundingDuration();
	for (var i = 0; i < nFundingDuration; i++) {
		$('#roi-amount-' + i).html('0 ' + sSymbol);
	}

}

WDGCampaignTurnoverSimulator.prototype.refreshYears = function () {
	var self = this;

	// Récupération de l'ancien et du nouveau nombre d'années de financement
	var nYearsOld = $('#estimated-turnover tr').length;
	var nYearsNew = self.getFundingDuration();

	// Si il y a plus d'années à présent, il faut ajouter des items
	if (nYearsNew > nYearsOld) {
		if (nYearsNew <= 30) {
			for (var i = 0; i < nYearsNew - nYearsOld; i++) {
				$('#estimated-turnover').append(
					'<tr>' +
					'<td>Année&nbsp;<span class="year">' + (i + 1 + nYearsOld) + '</span></td>' +

					'<td class="field field-value" data-type="number" data-id="new_estimated_turnover_' + (i + nYearsOld) + '">' +
					'<i class="right fa" aria-hidden="true"></i>' +
					'<input type="text" pattern="\d*" value="0" id="new_estimated_turnover_' + (i + nYearsOld) + '" class="right-icon">&nbsp;' + $('#estimated-turnover').data('symbol') +
					'</td>' +

					'<td class="field field-value" data-type="number" data-id="new_estimated_sales_' + (i + nYearsOld) + '">' +
					'<input type="text" pattern="\d*" value="0" id="new_estimated_sales_' + (i + nYearsOld) + '">' +
					'</td>' +

					'<td id="roi-amount-' + (i + nYearsOld) + '">0 ' + $('#estimated-turnover').data('symbol') +
					'</td>' +
					'</tr>'
				);
			}
			$('#estimated-turnover tr').slice(0, nYearsNew).show();
		}

	} else {
		//N'affiche que les boites nécessaires
		$('#estimated-turnover tr').hide();
		$('#estimated-turnover tr').slice(0, nYearsNew).show();
	}
	this.refreshTurnover();
	this.reinitTurnoverEvents();
}

WDGCampaignTurnoverSimulator.prototype.refreshTurnover = function () {
	var self = this;

	var nFundingDuration = self.getFundingDuration();
	var nCampaignGoal = self.getInputValue('new_maximum_goal', 2);
	var nROIPercent = self.getInputValue('new_roi_percent_estimated', 10);

	if (nFundingDuration > 0 && nCampaignGoal > 0) {
		var sSymbol = $('#estimated-turnover').data('symbol');

		$('#total-funding').html(self.getNumberValueToString(nCampaignGoal) + ' &euro;');

		// Calcul des royalties
		var nTotalRoyalties = 0;
		for (var i = 0; i < nFundingDuration; i++) {
			var nYearRoyalties = (sSymbol == '%') ? nCampaignGoal * self.getInputValue('new_estimated_turnover_' + i, 2) / 100 : nROIPercent * self.getInputValue('new_estimated_turnover_' + i, 2) / 100;
			nYearRoyalties = nYearRoyalties.toFixed(2);
			$('#roi-amount-' + i).html(self.getNumberValueToString(nYearRoyalties) + ' &euro;');
			nTotalRoyalties += parseFloat(nYearRoyalties);
		}
		$('#total-roi').html(self.getNumberValueToString(nTotalRoyalties) + ' &euro;');

		// Calcul du rendement
		var nYield = Math.round(((nTotalRoyalties / nCampaignGoal) - 1) * 100 * 100) / 100;
		var nYieldFormatted = self.getNumberValueToString(nYield);
		if (nYield > 0) {
			nYieldFormatted = "+" + nYieldFormatted;
		}

		$('#medium-rend').html(nYieldFormatted + ' %');
		$('#medium-rend').css('color', '#2B2C2C');
		if (nYield < 0) {
			$('#medium-rend').css('color', '#EA4F51').css('display', 'inline-block').css('margin', 0);
			$('#medium-rend').append('<br>(insuffisant)');
		}

		// Calcul du gain
		var nProfit = nTotalRoyalties / nCampaignGoal;
		var sProfit = self.getNumberValueToString(nProfit);
		$('#gain').html('x' + sProfit + ' en ' + nFundingDuration + ' ans');


	} else {
		self.initDisplayedInfos();
	}

}

WDGCampaignTurnoverSimulator.prototype.reinitTurnoverEvents = function () {
	var self = this;

	var nFundingDuration = self.getFundingDuration();
	for (var i = 0; i < nFundingDuration; i++) {
		if ($('#new_estimated_turnover_' + i).length > 0) {
			$('#new_estimated_turnover_' + i).unbind();
			$('#new_estimated_turnover_' + i).bind('click keyup', function () {
				self.refreshTurnover();
			});

			self.formatTurnover('new_estimated_turnover_' + i);
			$('#new_estimated_turnover_' + i).change(function () {
				self.formatTurnover($(this).attr('id'));
			});
		}
	}
}

WDGCampaignTurnoverSimulator.prototype.getFundingDuration = function () {
	var buffer = parseInt($('#select-new_funding_duration').val());
	if (buffer == 0 || isNaN(buffer)) {
		buffer = 5;
		var estimation = parseInt($('#new_funding_duration_infinite_estimation').val());
		if (estimation > 0 && !isNaN(estimation)) {
			buffer = estimation;
		}
	}
	return buffer;
}

WDGCampaignTurnoverSimulator.prototype.getInputValue = function (sIdInput, nPrecision) {
	var buffer = 0;
	var sTurnoverInputValue = ($('#' + sIdInput).length > 0) ? $('#' + sIdInput).val() : $('span[data-id=' + sIdInput + '] span').text();
	sTurnoverInputValue = sTurnoverInputValue.split(' ').join('').replace(',', '.');
	buffer = parseFloat(sTurnoverInputValue);
	buffer = buffer.toFixed(nPrecision);

	return buffer;
}

WDGCampaignTurnoverSimulator.prototype.getNumberValueToString = function (nValue) {
	nValue = parseFloat(nValue).toFixed(2);
	var buffer = JSHelpers.formatNumber(nValue, '');
	return buffer;
}

WDGCampaignTurnoverSimulator.prototype.formatTurnover = function (sIdInput) {
	var sInput = $('#' + sIdInput).val();
	var sInputFormatted = JSHelpers.formatNumber(JSHelpers.formatTextToNumber(sInput), '');
	$('#' + sIdInput).val(sInputFormatted);
}


//******************************************************************************





function WDGCampaignDashboard() {
	this.walletTimetableDatatable;
	this.createTableRequest;
	this.initWithHash();
	this.initLinks();
	this.initMenu();
	this.initHelp();
	this.initStatsSubTabs();
	this.drawTimetable();
	this.initAjaxForms();
	this.initHome();
	this.initContacts();
	this.initQtip();
	this.initTeam();
	this.initRoyalties();
	this.initFinance();
	this.initCampaign();
}

/**
 * Initialise l'affichage avec le # de l'url
 */
WDGCampaignDashboard.prototype.initWithHash = function () {

	var sCurrentTab = window.location.hash.substring(1);
	if (sCurrentTab !== '') {
		this.switchTab(sCurrentTab);
	} else {
		this.switchTab('home');
	}

};

/**
 * Initialise les liens pour couper d'éventuelles requêtes si nécessaire
 */
WDGCampaignDashboard.prototype.initLinks = function () {

	var self = this;
	$('a').click(function () {
		// On ne couple la requete que si il n'y a pas de # dans le lien
		if ($(this).attr('href') !== undefined && $(this).attr('href') !== '' && $(this).attr('href').indexOf('#') === -1) {
			if (self.createTableRequest !== undefined) {
				self.createTableRequest.abort();
			}
			if (YPUIFunctions.currentRequest !== '') {
				YPUIFunctions.currentRequest.abort();
			}
		}
	});

};

/**
 * Initialise le menu
 */
WDGCampaignDashboard.prototype.initMenu = function () {

	var self = this;
	$('ul.nav-menu li a').each(function () {
		$(this).click(function () {
			self.switchTab($(this).data('tab'));
		});
	});
	$('a.switch-tab').click(function () {
		self.switchTab($(this).attr('href').substr(1));
	});

};

/**
 * Initialise les éléments d'aide
 */
WDGCampaignDashboard.prototype.initHelp = function () {
	$('.help-item-remove').hover(
		function () {
			$(this).parent().addClass('hover');
		},
		function () {
			$(this).parent().removeClass('hover');
		}
	);

	$('.help-item-remove').click(function () {
		$(this).parent().fadeOut(100);
		$.ajax({
			'type': "POST",
			'url': ajax_object.ajax_url,
			'data': {
				'action': 'remove_help_item',
				'name': $(this).data('item-name'),
				'version': $(this).data('item-version')
			}
		});
	});
};

/**
 * Initialise le sous-menu de l'onglet Statistiques
 */
WDGCampaignDashboard.prototype.initStatsSubTabs = function () {

	var self = this;
	$('ul.menu-onglet li a').each(function () {
		$(this).click(function () {
			if ($(this).data('subtab') !== '') {
				$('.stat-subtab').hide();
				$('#stat-subtab-' + $(this).data('subtab')).show();
				$('ul.menu-onglet li a').removeClass('focus');
				$(this).addClass('focus');
				if ($(this).data('subtab') == 'leveedefonds') {
					$('#sup-stats-chart').width(600);
				}
			}
		});
	});

};

/**
 * Change d'onglet
 */
WDGCampaignDashboard.prototype.switchTab = function (sType) {

	$('ul.nav-menu li').removeClass('selected');
	$('div#item-body > div.item-body-tab').hide();

	$('ul.nav-menu li#menu-item-' + sType).addClass('selected');
	$('div#item-body > div#item-body-' + sType).show();

	// Mise à jour des datatables pour éviter les décalages de header
	if (sType == 'royalties' && this.walletTimetableDatatable != undefined) {
		this.walletTimetableDatatable.draw();
	}
	if (sType == 'contacts' && this.table != undefined) {
		this.table.draw();
	}

	this.scrollTo($('#container'));

};

/**
 * Gère les formulaires ajax
 */
WDGCampaignDashboard.prototype.initAjaxForms = function () {

	var self = this;
	$('form.ajax-db-form').submit(function (e) {

		if ($(this).attr('action') != '' && $(this).attr('action') != undefined) {
			return;
		}
		e.preventDefault();
		if ($(this).data('action') == undefined) {
			return false;
		}
		var thisForm = $(this);
		var thisFormAction = $(this).data('action');

		//Recueillir informations du formulaire
		var data_to_update = {
			'action': thisFormAction,
			'campaign_id': campaign_id
		};
		$(this).find('.field').each(function (index) {
			var id = $(this).data('id');
			if (id != undefined) {
				switch ($(this).data('type')) {
					case 'datetime':
						var sDate = $(this).find('input:eq(0)').val();
						var aDate = sDate.split('/');
						data_to_update[id] = aDate[1] + '/' + aDate[0] + '/' + aDate[2] + "\ "
							+ $(this).find('select:eq(0)').val() + ':'
							+ $(this).find('select:eq(1)').val();
						break;
					case 'editor':
						data_to_update[id] = tinyMCE.get(id).getContent();
						break;
					case 'check':
						data_to_update[id] = $('#' + id).is(':checked');
						break;
					case 'multicheck':
						var data_temp = new Array();
						$('input', this).each(function () {
							if ($(this).is(':visible') && $(this).is(':checked')) {
								data_temp.push($(this).val());
							}
						});
						data_to_update[id] = data_temp;
						break;
					case 'checkboxes':
						$('input', this).each(function () {
							if ($(this).is(':checked')) {
								data_to_update[id] = $(this).is(':checked');
							}
						});
						break;
					case 'text':
					case 'number':
					case 'date':
					case 'link':
					case 'textarea':
					case 'select':
					default:
						data_to_update[id] = $(':input', this).val();
						break;
				}
				if (data_to_update[id] == undefined) {
					delete data_to_update[id];
				}
			}
		});

		//Désactive les champs
		var save_button = $("#" + $(this).attr("id") + "_button");
		save_button.find(".button-text").hide();
		save_button.find(".button-waiting").show();
		$(":input", this).prop('disabled', true);

		thisForm.find('.feedback_save span').fadeOut();

		if ($(this).data('confirm') == "true" || $(this).data('confirm') == true) {
			var confirmSave = window.confirm("Etes-vous sûrs de vouloir enregistrer ces modifications ?");
		} else {
			var confirmSave = true;
		}
		if (confirmSave) {
			//Envoi de requête Ajax
			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': data_to_update
			}).done(function (result) {
				if (result != "") {
					var jsonResult = JSON.parse(result);
					feedback = jsonResult;

					//Affiche les erreurs
					var firstErrorInput = false;
					for (var input in feedback.errors) {
						firstErrorInput = $('#field-' + input + ' .field-error');
						$('#field-' + input + ' .field-error').html(feedback.errors[input]);
						$('#field-' + input + ' .field-error').show();
						$('#field-' + input).find('i.fa.validation').remove();
					}

					for (var input in feedback.success) {
						$('#field-' + input + ' .field-error').hide();
						thisinput = thisForm.find('input[name=' + input + '],select[name=select-' + input + ']');
						self.removeFieldError(thisinput);
						thisinput.parent().parent().find('i.fa.validation').remove();
						thisinput.addClass("validation");
						thisinput.parent().after('<i class="fa fa-check validation" aria-hidden="true"></i>');
					}

					//Scrolle jusqu'à la 1ère erreur et la sélectionne
					if (firstErrorInput !== false) {
						self.scrollTo(firstErrorInput);
						thisForm.find('.save_errors').fadeIn();
					} else {
						thisForm.find('.save_ok').fadeIn();
					}


				}
			}).fail(function () {
				thisForm.find('.save_fail').fadeIn();

			}).always(function () {
				if (thisFormAction == 'save_project_status') {
					window.location.reload();
				} else {
					//Réactive les champs
					save_button.find(".button-waiting").hide();
					save_button.find(".button-text").show();
					thisForm.find(":input").prop('disabled', false);
				}
			});
		} else {
			//Réactive les champs
			save_button.find(".button-waiting").hide();
			save_button.find(".button-text").show();
			thisForm.find(":input").prop('disabled', false);
		}
	});
};

WDGCampaignDashboard.prototype.initHome = function () {

	//Preview date fin collecte sur LB étape suivante
	if (($("#innbdayvote").length > 0) || ($("#innbdaycollecte").length > 0)) {

		updateDate = function (idfieldinput, iddisplay) {
			$("#" + iddisplay).empty();
			if ($("#" + idfieldinput).val() <= $("#" + idfieldinput).prop("max") && $("#" + idfieldinput).val() >= $("#" + idfieldinput).prop("min")) {
				var d = new Date();
				var jsupp = $("#" + idfieldinput).val();
				d.setDate(d.getDate() + parseInt(jsupp));
				$("#" + iddisplay).prepend(' ' + d.getDate() + '/' + (d.getMonth() + 1) + '/' + d.getFullYear());
			} else {
				$("#" + iddisplay).prepend("La durée doit être comprise entre " + ($("#" + idfieldinput).prop("min")) + " et " + ($("#" + idfieldinput).prop("max")) + " jours");
			}
		};

		updateDate("innbdaycollecte", "previewenddatecollecte");
		updateDate("innbdayvote", "previewenddatevote");

		$("#innbdaycollecte").on('keyup change', function () {
			updateDate("innbdaycollecte", "previewenddatecollecte");
		});

		$("#innbdayvote").on('keyup change', function () {
			updateDate("innbdayvote", "previewenddatevote");
		});
	}

};

WDGCampaignDashboard.prototype.initContacts = function () {

	var self = this;
	var mail_content, mail_title, originalText;
	$("#direct-mail #mail-preview-button").click(function (e) {
		mail_content = tinyMCE.get('mail_content').getContent();
		mail_title = $("#direct-mail #mail-title").val();

		if (mail_title == "") {
			self.fieldError($("#direct-mail #mail-title"), "L'objet du mail ne peut être vide");
		} else {
			self.removeFieldError($("#direct-mail #mail-title"));
			originalText = $(this).html();
			$(this).find(".button-text").hide();
			$(this).find(".button-loading").show();
			if ($(this).hasClass("disabled")) {
				e.preventDefault();
			}
			$(this).addClass("disabled");

			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': {
					'action': 'preview_mail_message',
					'id_campaign': campaign_id,
					'mail_content': mail_content,
					'mail_title': mail_title
				}
			}).done(function (result) {
				var res = JSON.parse(result);

				$("#direct-mail .preview-title").html('<i class="fa fa-envelope" aria-hidden="true"></i>&nbsp;' + res.content.title);
				$("#direct-mail .preview").html(res.content.body);
				$("#direct-mail .step-write").slideUp();
				$("#direct-mail .step-confirm").slideDown();
				$("#direct-mail #mail-preview-button").html(originalText);
			})
		}
	});

	$("#direct-mail #mail-send-button").click(function (e) {
		$(this).find(".button-text").hide();
		$(this).find(".button-loading").show();
		if ($(this).hasClass("disabled")) {
			e.preventDefault();
		}
		$(this).addClass("disabled");
	});

	$("#direct-mail #mail-back-button").click(function () {
		$("#direct-mail .step-confirm").slideUp();
		$("#direct-mail .step-write").slideDown();
	});


	$('.show-notifications').click(function (e) {
		e.preventDefault();
		$('#form-notifications #mail_type').val($(this).data('mailtype'));
		$('#form-notifications').hide();
		$('#form-notifications').slideDown(100);
	});

	$('.show-notifications-end-vote').click(function (e) {
		e.preventDefault();
		$('#form-notifications-end-vote #mail_type').val($(this).data('mailtype'));
		$('#form-notifications-end-vote').hide();
		$('#form-notifications-end-vote').slideDown(100);
	});

	$('.show-notifications-end').click(function (e) {
		e.preventDefault();
		$('#form-notifications-end #notifications_content').html($(this).html());
		$('#form-notifications-end #mail_type').val($(this).data('mailtype'));
		$('#form-notifications-end').hide();
		$('#form-notifications-end').slideDown(100);
	});

	$('.button-test-notification').click(function (e) {
		e.preventDefault();
		var self = this;
		$(this).addClass('disabled');

		var sTypeNotif = $($(this).siblings("input[name=action]")[0]).val();
		var sCampaignID = $($(this).siblings("input[name=campaign_id]")[0]).val();
		var sMailType = $($(this).siblings("input[name=mail_type]")[0]).val();
		var sTestimony = '';
		var sImageURL = '';
		var sImageDescription = '';
		if ($(this).siblings("input[name=image_description]").length > 0) {
			sTestimony = tinyMCE.get('testimony').getContent();
			sImageURL = $($(this).siblings("input[name=image_url]")[0]).val();
			sImageDescription = $($(this).siblings("input[name=image_description]")[0]).val();
		}

		$.ajax({
			'type': "POST",
			'url': ajax_object.ajax_url,
			'data': {
				'action': 'send_test_notifications',
				'campaign_id': sCampaignID,
				'notif_type': sTypeNotif,
				'mail_type': sMailType,
				'send_option': 'test',
				'testimony': sTestimony,
				'image_url': sImageURL,
				'image_description': sImageDescription
			}
		}).done(function (result) {
			$(self).removeClass('disabled');
			if (result == '1') {
				alert('Test envoyé');
			} else {
				alert('Erreur envoi du test');
			}
		});
	});

	$(".button-send-notification").click(function (e) {
		$(this).find(".button-text").hide();
		$(this).find(".button-loading").show();
		if ($(this).hasClass("disabled")) {
			e.preventDefault();
		}
		$(this).addClass("disabled");
	});

	if ($('.button-contacts-add-check').length > 0) {
		$('.button-contacts-add-check').click(function () {
			$('#form-contacts-add-check').slideDown(30);
			self.scrollTo($('#form-contacts-add-check'));
		});

		var aAddCheckCurrentUserOrgas = new Array();
		$('#button-contacts-add-check-search').click(function (e) {
			e.preventDefault();
			$('#button-contacts-add-check-search').addClass('disabled');
			$('.add-check-feedback').hide();
			$('#fields-user-info').hide();
			$('#fields-orga-info').hide();
			$('#fields-orga-select').hide();
			$('#fields-save-info').hide();
			$('#add-check-search-loading').show();


			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': {
					'action': 'search_user_by_email',
					'email': $('#form-contacts-add-check #user-email').val()
				}
			}).done(function (result) {
				$('#button-contacts-add-check-search').removeClass('disabled');
				$('#add-check-search-loading').hide();

				var jsonResult = JSON.parse(result);
				switch (jsonResult.user_type) {
					case 'user':
						$('#add-check-feedback-found-orga').hide();
						$('#add-check-feedback-found-user').show();
						$('#fields-user-info').show();
						$('#fields-user-info #select-gender').val(jsonResult.user_data.user.gender);
						$('#fields-user-info #firstname').val(jsonResult.user_data.user.firstname);
						$('#fields-user-info #lastname').val(jsonResult.user_data.user.lastname);
						$('#fields-user-info #field-birthday .adddatepicker').datepicker('setDate', jsonResult.user_data.user.birthday_day + '/' + jsonResult.user_data.user.birthday_month + '/' + jsonResult.user_data.user.birthday_year);
						$('#fields-user-info #birthplace').val(jsonResult.user_data.user.birthplace);
						$('#fields-user-info #select-birthplace_department').val(jsonResult.user_data.user.birthplace_department);
						$('#fields-user-info #select-birthplace_district').val(jsonResult.user_data.user.birthplace_district);
						$('#fields-user-info #select-birthplace_country').val(jsonResult.user_data.user.birthplace_country);
						$('#fields-user-info #select-nationality').val(jsonResult.user_data.user.nationality);
						$('#fields-user-info #address_number').val(jsonResult.user_data.user.address_number);
						$('#fields-user-info #select-address_number_complement').val(jsonResult.user_data.user.address_number_complement);
						$('#fields-user-info #address').val(jsonResult.user_data.user.address);
						$('#fields-user-info #postal_code').val(jsonResult.user_data.user.postal_code);
						$('#fields-user-info #city').val(jsonResult.user_data.user.city);
						$('#fields-user-info #select-country').val(jsonResult.user_data.user.country);

						// Vider et remplir la liste des organisations existantes
						$('form#form-contacts-add-check select#select-orga_id option').each(function () {
							if ($(this).val() !== '' && $(this).val() !== 'new-orga') {
								$(this).remove();
							}
						});
						aAddCheckCurrentUserOrgas = new Array();
						var aOrga = jsonResult.user_data.orga_list;
						var nOrga = jsonResult.user_data.orga_list.length;
						for (var iOrga = 0; iOrga < nOrga; iOrga++) {
							$('form#form-contacts-add-check select#select-orga_id').append('<option value="' + aOrga[iOrga].wpref + '">' + aOrga[iOrga].name + '</option>');
							aAddCheckCurrentUserOrgas[aOrga[iOrga].wpref] = aOrga[iOrga];
						}
						break;

					case 'orga':
						$('#add-check-feedback-found-user').hide();
						$('#fields-user-info').hide();
						$('#add-check-feedback-found-orga').show();
						break;

					default:
						$('#add-check-feedback-found-orga').hide();
						$('#add-check-feedback-not-found').show();
						$('#fields-user-info').show();
						$('#fields-user-info #select-gender').val('');
						$('#fields-user-info #firstname').val('');
						$('#fields-user-info #lastname').val('');
						$('#fields-user-info #field-birthday .adddatepicker').datepicker('setDate', '01/01/1970');
						$('#fields-user-info #birthplace').val('');
						$('#fields-user-info #select-nationality').val('');
						$('#fields-user-info #address_number').val('');
						$('#fields-user-info #select-address_number_complement').val('');
						$('#fields-user-info #address').val('');
						$('#fields-user-info #postal_code').val('');
						$('#fields-user-info #city').val('');
						$('#fields-user-info #select-country').val('');
						break;
				}
			});
		});

		$('form#form-contacts-add-check select#select-user_type').change(function () {
			if ($('form#form-contacts-add-check select#select-user_type').val() != '') {
				if ($('form#form-contacts-add-check select#select-user_type').val() != 'user') {
					$('#fields-save-info').hide();
					$('#fields-orga-select').show();
				} else {
					$('#fields-orga-select').hide();
					$('#fields-save-info').show();
				}
			} else {
				$('#fields-orga-select').hide();
				$('#fields-save-info').hide();
			}
			$('#fields-orga-info').hide();
		});

		$('form#form-contacts-add-check select#select-orga_id').change(function () {
			if ($('form#form-contacts-add-check select#select-orga_id').val() != '') {
				if ($('form#form-contacts-add-check select#select-orga_id').val() == 'new-orga') {
					// Vider les champs d'infos d'orga
					$('#fields-orga-info #org_name').val('');
					$('#fields-orga-info #org_email').val('');
					$('#fields-orga-info #org_website').val('');
					$('#fields-orga-info #org_legalform').val('');
					$('#fields-orga-info #org_idnumber').val('');
					$('#fields-orga-info #org_rcs').val('');
					$('#fields-orga-info #org_capital').val('');
					$('#fields-orga-info #org_address_number').val('');
					$('#fields-orga-info #select-org_address_number_comp').val('');
					$('#fields-orga-info #org_address').val('');
					$('#fields-orga-info #org_postal_code').val('');
					$('#fields-orga-info #org_city').val('');
					$('#fields-orga-info #select-org_nationality').val('');
				} else {
					var oOrgaItem = aAddCheckCurrentUserOrgas[$('form#form-contacts-add-check select#select-orga_id').val()];
					$('#fields-orga-info #org_name').val(oOrgaItem.name);
					$('#fields-orga-info #org_email').val(oOrgaItem.email);
					$('#fields-orga-info #org_website').val(oOrgaItem.website);
					$('#fields-orga-info #org_legalform').val(oOrgaItem.legalform);
					$('#fields-orga-info #org_idnumber').val(oOrgaItem.idnumber);
					$('#fields-orga-info #org_rcs').val(oOrgaItem.rcs);
					$('#fields-orga-info #org_capital').val(oOrgaItem.capital);
					$('#fields-orga-info #org_address_number').val(oOrgaItem.address_number);
					$('#fields-orga-info #select-org_address_number_comp').val(oOrgaItem.address_number_comp);
					$('#fields-orga-info #org_address').val(oOrgaItem.address);
					$('#fields-orga-info #org_postal_code').val(oOrgaItem.postal_code);
					$('#fields-orga-info #org_city').val(oOrgaItem.city);
					$('#fields-orga-info #select-org_nationality').val(oOrgaItem.nationality);
				}
				$('#fields-orga-info').show();
				$('#fields-save-info').show();
			} else {
				$('#fields-orga-info').hide();
				$('#fields-save-info').hide();
			}
		});
	}

	if ($('div#investment-drafts-list').length > 0) {
		$('button.btn-view-investment-draft').click(function () {
			var draftid = $(this).data('draftid');
			$('form#preview-investment-draft-' + draftid).toggle();
		});

		$('button.apply-draft-data').click(function () {
			var self = this;
			var userId = $(this).parent().data('userid');
			var orgaId = $(this).parent().data('orgaid');
			var draftId = $(this).parent().data('draftid');
			var dataType = $(this).data('type');
			var dataValue = $(this).data('value');
			$(self).hide();
			if (dataType === 'all') {
				$('#preview-investment-draft-' + draftId + ' button.apply-draft-data').hide();
			}
			$('#preview-investment-draft-' + draftId + ' #img-loading-data-' + dataType).show();
			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': {
					'action': 'apply_draft_data',
					'user_id': userId,
					'orga_id': orgaId,
					'draft_id': draftId,
					'data_type': dataType,
					'data_value': dataValue
				}
			}).done(function (result) {
				$('<i class="text-green">' + result + '</i>').insertAfter($('#preview-investment-draft-' + draftId + ' #img-loading-data-' + dataType));
				$('#preview-investment-draft-' + draftId + ' #img-loading-data-' + dataType).hide();
			});
		});

		$('button.create-investment-from-draft').click(function () {
			var self = this;
			var draftId = $(this).parent().data('draftid');
			var campaignId = $(this).parent().data('campaignid');
			$(self).hide();
			$('#preview-investment-draft-' + draftId + ' #img-loading-create-investment').show();
			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': {
					'action': 'create_investment_from_draft',
					'draft_id': draftId,
					'campaign_id': campaignId
				}
			}).done(function (result) {
				if (result == '1') {
					window.location.reload();
				} else {
					$('#preview-investment-draft-' + draftId + ' #img-loading-create-investment').after("<em>" + result + "</em>");
					$('#preview-investment-draft-' + draftId + ' #img-loading-create-investment').hide();//On cache la roue de chargement.
				}
			});
		});
	}
};

function addCheckByPMCallback(result) {
	$('form#form-contacts-add-check p.errors').remove();
	if (result != '') {
		try {
			var resultParsed = JSON.parse(result);
			var fdErrorsData = resultParsed.errors;
			var count_data_errors = 0;
			for (var error in fdErrorsData) {
				if (error !== "") {
					count_data_errors++;
					var err = $("<p class='errors'>" + fdErrorsData[error][1] + "</p>");
					err.insertBefore($("form#form-contacts-add-check div#field-" + fdErrorsData[error][0] + " .field-container"));
				}
			}
			if (count_data_errors > 0) {
				var firsterror = $('form#form-contacts-add-check').find('.errors').first().parent();
				if (firsterror.length === 1) {
					wdgCampaignDashboard.scrollTo(firsterror);
				}
			} else {
				if (resultParsed.success === '1') {
					$('form#form-contacts-add-check .loading').show();
					$('form#form-contacts-add-check button').hide();
					window.location.reload();
				}
			}

		} catch (e) { }
	}
}

/* Fonction de mise à jour du bouton d'édition d'une organisation
* une fois l'organisation sélectionnée et enregistrée
* @param {type} form : formulaire de saisie
*/
WDGCampaignDashboard.prototype.updateEditOrgaBtn = function (form) {
	var newval = $("#select-new_project_organization").val();
	if (newval !== '') {
		var edit_btn = form.find($("#edit-orga-button")).show();

		var newname = $("#select-new_project_organization").find('option:selected').text();
		edit_btn.attr("href", "#");
		edit_btn.text("Editer " + newname);

	} else {
		edit_btn.hide();
	}
};

WDGCampaignDashboard.prototype.getContactsTable = function (inv_data, campaign_id) {
	var self = this;

	self.createTableRequest = $.ajax({
		'type': "POST",
		'url': ajax_object.ajax_url,
		'data': {
			'action': 'create_contacts_table',
			'id_campaign': campaign_id,
			'data': inv_data
		},
		'timeout': 30000 // sets timeout to 30 seconds
	}).done(function (result) {
		self.createTableRequest = undefined;
		//Affiche resultat requete Ajax une fois reçue
		$('#ajax-contacts-load').after(result);
		$('#ajax-loader-img').hide();//On cache la roue de chargement.


		var nb_visible_colums_filters = (result_contacts_table['id_column_user_id'] - result_contacts_table['id_column_index'] - 2);

		//Création du tableau dynamique dataTable
		self.table = $('#contacts-table').DataTable({
			responsive: {
				details: {
					type: 'column',
					target: 'td:not(:first-child)' // un clic sur la ligne excepté la checkbox permet de déplier la ligne
				}
			},
			scrollY: '70vh', //Taille max du tableau : 70% de l'écran
			scrollCollapse: true, //Diminue taille du tableau si peu d'éléments*/

			paging: false, //Pas de pagination, affiche tous les éléments yolo
			order: [[result_contacts_table['default_sort'], "desc"]],

			columnDefs: [
				{
					targets: result_contacts_table['array_hidden'], //Cache colonnes par défaut
					visible: false
				},
				{
					className: 'select-checkbox min-tablet',
					targets: 0,
					orderable: false,
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
						self.table.rows({ search: 'applied' }).select();
					}
				},
				{
					//Bouton envoi de mail
					extend: 'selected',
					text: '<i class="fa fa-envelope" aria-hidden="true"></i> Envoyer un mail',
					action: function (e, dt, button, config) {
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
						columns: ':gt(' + result_contacts_table['id_column_index'] + '):lt(' + nb_visible_colums_filters + ')', //On ne peut pas cacher les 5 premières colonnes, ni la dernière
						collectionLayout: 'two-column'
					}, {
						extend: 'colvisGroup',
						text: 'Tout afficher',
						show: ':gt(' + result_contacts_table['id_column_index'] + '):lt(' + nb_visible_colums_filters + '):hidden'
					}, {
						extend: 'colvisGroup',
						text: 'Tout masquer',
						hide: ':gt(' + result_contacts_table['id_column_index'] + ')'
					}, {
						extend: 'colvisRestore',
						text: '<i class="fa fa-refresh" aria-hidden="true"></i> Rétablir colonnes par défaut'
					}]
				},

				//Menu d'export
				{
					extend: 'collection',
					text: '<i class="fa fa-download" aria-hidden="true"></i> Exporter',
					buttons: [{
						//Bouton d'export excel
						extend: 'excel',
						text: '<i class="fa fa-file-excel-o" aria-hidden="true"></i> Fichier Excel',
						exportOptions: {
							modifier: {
								columns: ':visible'
							}
						}
					}, {
						//Bouton d'export impression
						extend: 'print',
						text: '<i class="fa fa-print" aria-hidden="true"></i> Imprimer',
						exportOptions: {
							modifier: {
								columns: ':visible'
							}
						}
					}]
				}
			],

			language: {
				"sProcessing": "Traitement en cours...",
				"sSearch": "Rechercher&nbsp;:",
				"sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
				"sInfo": "Affichage de _TOTAL_ &eacute;l&eacute;ments",
				"sInfoEmpty": "Aucun &eacute;l&eacute;ment &agrave; afficher",
				"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
				"sInfoPostFix": "",
				"sLoadingRecords": "Chargement en cours...",
				"sZeroRecords": "Aucun &eacute;l&eacute;ment",
				"sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
				"oPaginate": {
					"sFirst": "Premier",
					"sPrevious": "Pr&eacute;c&eacute;dent",
					"sNext": "Suivant",
					"sLast": "Dernier"
				},
				"oAria": {
					"sSortAscending": ": activer pour trier la colonne par ordre croissant",
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
		self.table.responsive.recalc();

		// on réinitialise les tooltip quand on change les colonnes affichées
		self.table.on('column-visibility.dt', function (e, settings, column, state) {
			self.initQtip();
		});

		self.table.on('responsive-display', function (e, datatable, row, showHide, update) {
			self.initQtip();
		});


		var mailButtonDefault = self.table.button(1).text()
		self.table.on("select.dt deselect.dt", function (e, dt, type, indexes) {
			//Maj Bouton de Mail
			var selectedCount = self.table.rows({ selected: true }).count();
			if (selectedCount == 0) {
				self.table.button(1).text(mailButtonDefault);
				$("#send-mail-tab").slideUp();
			} else {
				self.table.button(1).text(mailButtonDefault + " (" + selectedCount + ")");
			}


			//Maj Bouton de sélection
			var allContained = true;
			self.table.rows({ search: 'applied' }).every(function (rowIdx, tableLoop, rowLoop) {
				if ($.inArray(rowIdx, self.table.rows({ selected: true }).indexes()) == -1) {
					allContained = false;
				}
			});

			if (allContained) {
				self.table.button(0).text('<i class="fa fa-check-square-o" aria-hidden="true"></i> Déselectionner les éléments affichés');
				self.table.button(0).action(function () {
					self.table.rows({ search: 'applied' }).deselect();
				});
			} else {
				self.table.button(0).text('<i class="fa fa-square-o" aria-hidden="true"></i> Sélectionner les éléments affichés');
				self.table.button(0).action(function () {
					self.table.rows({ search: 'applied' }).select();
				});
			}

			//Maj Champs de Mail
			$("#nb-mailed-contacts").text(selectedCount);

			//Maj liste des identifiants à mailer
			var recipients_array = [];
			$.each(self.table.rows({ selected: true }).data(), function (index, element) {
				recipients_array.push(element[result_contacts_table['id_column_user_id'] - 1]);
			});
			$("#mail_recipients").val(recipients_array);
		});

		// Champs de filtrage
		$(self.table.table().container()).on('keyup', 'tfoot .text input', function () {
			self.table
				.column($(this).data('index'))
				.search(this.value)
				.draw();
		});
		$(self.table.table().container()).on('change', 'tfoot .check input', function () {
			if ($(this).is(":checked")) {
				self.table
					.column($(this).data('index'))
					.search("1")
					.draw();
			}
			else {
				self.table
					.column($(this).data('index'))
					.search("")
					.draw();
			}
		});
		self.initQtip();

		$('span.authentication-more-info a').click(function (e) {
			e.preventDefault();
			$(this).siblings("span").toggle();
			if ($(this).text() == '+') {
				$(this).text('-');
			} else {
				$(this).text('+');
			}
		});

	}).fail(function () {
		$('#ajax-contacts-load').after("<em>Le chargement du tableau a échoué</em>");
		$('#ajax-loader-img').hide();//On cache la roue de chargement.
	});

};

WDGCampaignDashboard.prototype.drawTimetable = function () {
	// Ajoute mise en page et interactions du tableau
	// Ajoute un champ de filtre à chaque colonne dans le footer
	$('#wdg-timetable tfoot td').each(function () {
		$(this).prepend('<input type="text" placeholder="Filtrer par :" class="col-filter"/><br/>');
	});

	// Ajoute les actions de filtrage
	$("#wdg-timetable tfoot input").on('keyup change', function () {
		walletTimetable
			.column($(this).parent().index() + ':visible')
			.search(this.value)
			.draw();
	});

	//Récupère le tri par défaut 
	sortColumn = 0;

	this.walletTimetableDatatable = $('#wdg-timetable').DataTable({
		scrollX: true,

		order: [[sortColumn, "asc"]], //Colonne à trier (date)

		dom: 'RC<"clear">lfrtip',
		lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Tous"]], //nombre d'élements possibles
		iDisplayLength: 50,//nombre d'éléments par défaut

		//Boutons de sélection de colonnes
		colVis: {
			buttonText: "Afficher/cacher colonnes",
			restore: "Restaurer",
			showAll: "Tout afficher",
			showNone: "Tout cacher",
			overlayFade: 100
		},
		language: {
			"sProcessing": "Traitement en cours...",
			"sSearch": "Rechercher&nbsp;:",
			"sLengthMenu": "Afficher _MENU_ &eacute;l&eacute;ments",
			"sInfo": "Affichage de l'&eacute;l&eacute;ment _START_ &agrave; _END_ sur _TOTAL_ &eacute;l&eacute;ments",
			"sInfoEmpty": "Affichage de l'&eacute;l&eacute;ment 0 &agrave; 0 sur 0 &eacute;l&eacute;ments",
			"sInfoFiltered": "(filtr&eacute; de _MAX_ &eacute;l&eacute;ments au total)",
			"sInfoPostFix": "",
			"sLoadingRecords": "Chargement en cours...",
			"sZeroRecords": "Aucun &eacute;l&eacute;ment &agrave; afficher",
			"sEmptyTable": "Aucune donn&eacute;e disponible dans le tableau",
			"oPaginate": {
				"sFirst": "Premier",
				"sPrevious": "Pr&eacute;c&eacute;dent",
				"sNext": "Suivant",
				"sLast": "Dernier"
			},
			"oAria": {
				"sSortAscending": ": activer pour trier la colonne par ordre croissant",
				"sSortDescending": ": activer pour trier la colonne par ordre d&eacute;croissant"
			}
		}
	});
};

WDGCampaignDashboard.prototype.scrollTo = function (target) {
	$('html, body, .wdg-lightbox-padder').animate(
		{ scrollTop: target.offset().top - 75 },
		'slow'
	);
};

WDGCampaignDashboard.prototype.fieldError = function ($param, errorText) {
	$param.addClass("error");
	$param.removeClass("validation");
	$param.qtip({
		content: errorText,
		position: {
			my: 'bottom center',
			at: 'top center',
		},
		style: {
			classes: 'wdgQtip qtip-red qtip-rounded qtip-shadow'
		},
		show: 'focus',
		hide: 'blur'
	});
	$param.parent().parent().find('i.fa.validation').remove();
};

WDGCampaignDashboard.prototype.removeFieldError = function ($param) {
	if ($param.hasClass("error")) {
		$param.removeClass("error");
		$param.qtip().destroy();
	}
};

WDGCampaignDashboard.prototype.initTeam = function ($param) {
	var self = this;
	$(".project-manage-team").click(function () {
		var action, data
		action = $(this).attr('data-action');
		switch (action) {
			case "yproject-add-member":
				data = ($("#new_team_member_string")[0].value);
				break;
			case "yproject-remove-member":
				data = $(this).attr('data-user');
				break;
		}
		self.manageTeam(action, data, campaign_id);
	});
	$(".project-manage-notifications").click(function () {
		var action, data
		action = $(this).attr('data-action');
		data = $(this).attr('data-user');
		self.manageTeam(action, data, campaign_id);
	});
};

WDGCampaignDashboard.prototype.initRoyalties = function () {
	var self = this;
	self.currentOpenedROI = 0;
	self.isRefund = 0;
	if ($(".transfert-roi-open").length > 0) {
		$(".transfert-roi-open").click(function () {
			if ($(this).data('roideclaration-id') !== self.currentOpenedROI) {
				//Affichage
				self.currentOpenedROI = $(this).data('roideclaration-id');
				self.isRefund = $(this).data('refund');
				$("#wdg-lightbox-transfer-roi #lightbox-content .loading-content").html("");
				$("#wdg-lightbox-transfer-roi #lightbox-content .loading-image").show();
				$("#wdg-lightbox-transfer-roi #lightbox-content .loading-form").hide();

				//Lancement de la requête pour récupérer les utilisateurs et les sommes associées
				$.ajax({
					'type': "POST",
					'url': ajax_object.ajax_url,
					'data': {
						'action': 'display_roi_user_list',
						'roideclaration_id': $(this).data('roideclaration-id'),
						'is_refund': $(this).data('refund')
					}
				}).done(function (result) {
					var content = 'Versement impossible';
					if (result != '0') {
						content = '<table>';
						content += '<tr><td>Utilisateur</td><td>Investissement</td><td>Versement</td><td>Commission</td></tr>';
						content += result;
						content += '</table>';
						$("#wdg-lightbox-transfer-roi #lightbox-content .loading-form input#hidden-roi-id").val(self.currentOpenedROI);
						$("#wdg-lightbox-transfer-roi #lightbox-content .loading-form input#hidden-isrefund").val(self.isRefund);
						$("#wdg-lightbox-transfer-roi #lightbox-content .loading-form").show();
					}
					$("#wdg-lightbox-transfer-roi #lightbox-content .loading-content").html(content);
					$("#wdg-lightbox-transfer-roi #lightbox-content .loading-image").hide();
				});
			}
		});

		$('#proceed_roi_transfers_form').submit(function (e) {
			e.preventDefault();
			self.proceedRoyalties();
		});
	}

	if ($("#turnover-declaration").length > 0) {
		if ($("#turnover-total").length > 0) {
			$("#turnover-total").change(function () {
				self.refreshTurnoverAmountToPay();
			});
		}
		var i = 0;
		while ($("#turnover-" + i).length > 0) {
			$("#turnover-" + i).change(function () {
				self.refreshTurnoverAmountToPay();
			});
			i++;
		}
	}

	$('#display-form-send-document').click(function () {
		$(this).slideUp(50);
		$('#form-send-document').slideDown(100);
	});

	$('#display-list-declarations').click(function () {
		$(this).slideUp(50);
		$('#list-declarations').slideDown(100);
	});

	$('.declaration-item-more-btn button').click(function () {
		var declarationId = $(this).data('declaration');
		if ($('#declaration-item-more-' + declarationId).is(':visible')) {
			$('#declaration-item-more-btn-' + declarationId + ' button').text('+');
			$('#declaration-item-' + declarationId).removeClass('expanded');
			$('#declaration-item-more-' + declarationId).slideUp(50);
		} else {
			$('#declaration-item-more-btn-' + declarationId + ' button').text('-');
			$('#declaration-item-' + declarationId).addClass('expanded');
			$('#declaration-item-more-' + declarationId).slideDown(100);
		}
	});

	$('#display-form-add-adjustment').click(function () {
		$(this).slideUp(50);
		$('#form-add-adjustment').slideDown(100);
	});

	$('#form-add-adjustment #field-turnover_checked #turnover_checked').change(function () {
		self.refreshAjustmentTurnoverDifference(false);
	});

	$('#form-add-adjustment #field-turnover_difference #turnover_difference').change(function () {
		self.refreshAjustmentAmountToPay(false);
	});

	$('.adjustment-edit-form #field-turnover_difference #turnover_difference').change(function () {
		self.refreshAjustmentAmountToPay($(this).parent().parent().parent().parent());
	});

	$('.adjustment-item-more-btn button').click(function () {
		var adjustmentId = $(this).data('adjustment');
		if ($('#adjustment-item-more-' + adjustmentId).is(':visible')) {
			$('#adjustment-item-more-btn-' + adjustmentId + ' button').text('+');
			$('#adjustment-item-' + adjustmentId).removeClass('expanded');
			$('#declaration-item-more-' + adjustmentId).slideUp(50);
		} else {
			$('#adjustment-item-more-btn-' + adjustmentId + ' button').text('-');
			$('#adjustment-item-' + adjustmentId).addClass('expanded');
			$('#adjustment-item-more-' + adjustmentId).slideDown(100);
		}
	});

	$('div.adjustment-item-more div.adjustment-summary button.edit-adjustment').click(function () {
		var adjustmentId = $(this).data('adjustment');
		$('div#adjustment-item-more-' + adjustmentId + ' .adjustment-summary').slideUp(50);
		$('div#adjustment-item-more-' + adjustmentId + ' .adjustment-edit-form').slideDown(100);
		$('div#adjustment-item-more-' + adjustmentId + ' .adjustment-edit-form select').each(function () {
			var selectElement = this;
			$(this).children().each(function () {
				if ($(this).attr('selected') == 'selected') {
					$(selectElement).val($(this).attr('value'));
				}
			});
		});
	});
};

WDGCampaignDashboard.prototype.refreshTurnoverAmountToPay = function () {
	var roiPercent = $('#turnover-declaration').data('roi-percent');
	var minCostsOrga = $('#turnover-declaration').data('minimum-costs');
	var costsOrga = $('#turnover-declaration').data('costs-orga');
	var total = 0;
	if ($('#turnover-total').length > 0) {
		total = Number($('#turnover-total').val().split(',').join('.'));
	} else {
		var i = 0;
		while ($('#turnover-' + i).length > 0) {
			total += Number($('#turnover-' + i).val().split(',').join('.'));
			i++;
		}
	}
	var amount = total * roiPercent / 100;
	var fees = Math.max(minCostsOrga, amount * costsOrga / 100);
	var amount_with_fees = amount + fees;
	amount_with_fees += $('#turnover-declaration').data('adjustment');
	amount_with_fees = Math.round(amount_with_fees * 100) / 100;

	$('.amount-to-pay').text(amount_with_fees);
	$('.commission-to-pay').text(fees);
};

WDGCampaignDashboard.prototype.refreshAjustmentTurnoverDifference = function (formTarget) {
	var idTarget = 'form-add-adjustment';
	if (formTarget !== false) {
		idTarget = formTarget.attr('id');
	}

	var totalDeclaration = 0;
	$('#field-declarations_checked .select-multiple-items label.radio-label input').each(function () {
		if ($(this).is(':checked')) {
			var nSizeOfDeclaration = 12; // Taille de la chaine 'declaration-'
			var nDeclarationId = $(this).val().substring(nSizeOfDeclaration);
			if ($('#list-declarations #declaration-item-more-' + nDeclarationId).length > 0) {
				var nDeclarationTurnoverTotal = $('#list-declarations #declaration-item-more-' + nDeclarationId).data('turnover-total');
				if (nDeclarationTurnoverTotal != undefined) {
					totalDeclaration += nDeclarationTurnoverTotal;
				}
			}
		}
	});

	var totalChecked = Number($('#' + idTarget + ' #field-turnover_checked #turnover_checked').val().split(',').join('.'));
	var diff = totalChecked - totalDeclaration;
	$('#' + idTarget + ' #field-turnover_difference #turnover_difference').val(diff);
	$('#' + idTarget + ' #field-turnover_difference #turnover_difference').change();
}

WDGCampaignDashboard.prototype.refreshAjustmentAmountToPay = function (formTarget) {
	var idTarget = 'form-add-adjustment';
	if (formTarget !== false) {
		idTarget = formTarget.attr('id');
	}
	var roiPercent = $('#' + idTarget + ' #field-roi_percent #roi_percent').val();
	var total = Number($('#' + idTarget + ' #field-turnover_difference #turnover_difference').val().split(',').join('.'));
	var amount = total * roiPercent / 100;
	amount = Math.round(amount * 100) / 100;

	$('#' + idTarget + ' #field-amount #amount').val(amount);
};

WDGCampaignDashboard.prototype.proceedRoyalties = function () {
	var self = this;
	var data_to_update = {
		'action': 'proceed_roi_transfers',
		'campaign_id': $('#hidden-campaign-id').val(),
		'roi_id': $('#hidden-roi-id').val(),
		'isrefund': $('#hidden-isrefund').val(),
		'send_notifications': $('#check_send_notifications').is(':checked'),
		'transfer_remaining_amount': $('#check_transfer_remaining_amount').is(':checked')
	};

	var save_button = $('#proceed_roi_transfers_button');
	save_button.find('.button-text').hide();
	save_button.find('.button-waiting').show();

	//Envoi de requête Ajax
	$.ajax({
		'type': "POST",
		'url': ajax_object.ajax_url,
		'data': data_to_update

	}).done(function (result) {
		if (result == 100) {
			$('#proceed_roi_transfers_percent').html('Versement effectu&eacute; !');
			$('#proceed_roi_transfers_button').hide();

		} else {
			var roundResult = parseFloat(result);
			roundResult = roundResult.toFixed(2);
			$('#proceed_roi_transfers_percent').html(roundResult + ' %');
			self.proceedRoyalties();
		}

	}).fail(function () {
		$('#proceed_roi_transfers_percent').html('<span class="error">Erreur serveur (ROI1611)</p>');
	});

};

WDGCampaignDashboard.prototype.manageTeam = function (action, data, campaign_id) {
	var self = this;
	//Clic pour ajouter un membre
	if (action === "yproject-add-member") {
		//Test si le champ de texte est vide
		if (data === "") {
			//Champ vide, ne rien faire
		} else {
			//Bloque le champ de texte d'ajout
			$("#new_team_member_string").prop('disabled', true);
			$("#new_team_member_string").val('');
			tmpPlaceHolder = $("#new_team_member_string").prop('placeholder');
			$("#new_team_member_string").prop('placeholder', "Ajout de " + data + "...");
			$("#new_team_member_string").next().hide();

			//Lance la requête Ajax
			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': {
					'action': 'add_team_member',
					'id_campaign': campaign_id,
					'new_team_member': data
				}
			}).done(function (result) {
				if (result == 'FALSE') {
					alert("Cet utilisateur n'a pas de compte sur la plateforme.");
				}
				//Nettoie le champ de texte d'ajout
				$("#new_team_member_string").prop('disabled', false);
				$("#new_team_member_string").prop('placeholder', tmpPlaceHolder);
				$("#new_team_member_string").next().show();

				if (result === "FALSE") {
					$("#new_team_member_string").next().next().after("<div id=\"fail_add_team_indicator\"><br/><em>L'utilisateur " + data + " n'a pas été trouvé</em><div>");
					$("#fail_add_team_indicator").delay(4000).fadeOut(400);
				} else {
					res = JSON.parse(result);

					//Teste si l'user existait déjà
					doublon = false;
					$(".project-manage-team").each(function () {
						doublon = doublon || (res.id == $(this).attr('data-user'));
					});

					if (!doublon) {
						if ($("#team-list li").length == 0) {
							$("#team-list").html("");
						}
						newline = '<li style="display: none;">';
						newline += res.firstName + " " + res.lastName + " (" + res.userLink + ") ";
						newline += '<a class="project-manage-team button red" data-action="yproject-remove-member" data-user="' + res.id + '"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a>';
						newline += "</li>";
						$("#team-list").append(newline);
						$("a[data-user=" + res.id + "]").closest("li").slideDown();
						$('#team-list-empty').hide();

						//Recharge l'UI pour ajouter listener au nouveau button
						$(".project-manage-team").click(function () {
							action = $(this).attr('data-action');
							switch (action) {
								case "yproject-add-member":
									data = ($("#new_team_member_string")[0].value);
									break;
								case "yproject-remove-member":
									data = $(this).attr('data-user');
									break;
							}
							self.manageTeam(action, data, campaign_id);
						});
					}
				}
			});
		}
	}

	//Clic pour supprimer un membre
	else if (action === "yproject-remove-member") {
		if (confirm("Êtes-vous sûr(e) de vouloir supprimer cet utilisateur de l'équipe du projet (son compte personnel ne sera pas supprimé) ?")) {
			//Affichage en attente de suppression
			$("a[data-user=" + data + "]").closest("li").css("opacity", 0.5);
			$("a[data-user=" + data + "]").html('<i class="fa fa-spinner fa-spin fa-fw"></i>');

			$.ajax({
				'type': "POST",
				'url': ajax_object.ajax_url,
				'data': {
					'action': 'remove_team_member',
					'id_campaign': campaign_id,
					'user_to_remove': data
				}
			}).done(function (result) {
				$("a[data-user=" + data + "]").closest("li").slideUp("slow", function () { $(this).remove(); });
			});
		}
	}

	//Clic pour activer les notifications
	else if (action === "yproject-add-notification" || action === "yproject-remove-notification") {
		$("a.project-manage-notifications[data-user=" + data + "]").parent().css("opacity", 0.5);
		$.ajax({
			'type': "POST",
			'url': ajax_object.ajax_url,
			'data': {
				'action': 'update_user_project_notifications',
				'id_campaign': campaign_id,
				'id_user': data,
				'notifications': action === "yproject-add-notification" ? '1' : '0'
			}
		}).done(function (result) {
			if ($("a.project-manage-notifications[data-user=" + data + "]").hasClass('red')) {
				$("a.project-manage-notifications[data-user=" + data + "]").removeClass('red');
				$("a.project-manage-notifications[data-user=" + data + "]").addClass('disabled');
				$("a.project-manage-notifications[data-user=" + data + "]").attr('title', 'Activer les notifications');
				$("a.project-manage-notifications[data-user=" + data + "]").attr('data-action', 'yproject-add-notification');
			} else {
				$("a.project-manage-notifications[data-user=" + data + "]").removeClass('disabled');
				$("a.project-manage-notifications[data-user=" + data + "]").addClass('red');
				$("a.project-manage-notifications[data-user=" + data + "]").attr('title', 'Désactiver les notifications');
				$("a.project-manage-notifications[data-user=" + data + "]").attr('data-action', 'yproject-remove-notification');
			}
			$("a.project-manage-notifications[data-user=" + data + "]").parent().css("opacity", 1);
		});
	}
};

WDGCampaignDashboard.prototype.initFinance = function () {
	WDGCampaignTurnoverSimulator = new WDGCampaignTurnoverSimulator();
};

WDGCampaignDashboard.prototype.initCampaign = function () {
	$("#item-body-campaign ul input[type=checkbox]").prop('disabled', false);

	// Validation du passage à l'étape suivante
	$('#form-changing-from-vote').submit(function (e) {
		if (!confirm('Attention, le choix de la date de fin est définitif. Êtes-vous sûr(e) de valider cette date de fin ?')) {
			e.preventDefault();
		}
	});
};

WDGCampaignDashboard.prototype.initQtip = function () {
	$('.infobutton, .qtip-element').each(function () {
		//Check if doesn't exist yet
		if ($(this).data("hasqtip") == undefined) {
			var contentTip;
			if ($(this).attr("title") != undefined) {
				contentTip = $(this).attr("title");
			} else {
				contentTip = $(this).next('.tooltiptext').text();
			}

			var settings = {
				content: contentTip,
				position: {
					my: 'bottom center',
					at: 'top center',
				},
				style: {
					classes: 'wdgQtip qtip-dark qtip-rounded qtip-shadow'
				},
				hide: {
					fixed: true,
					delay: 300
				}
			};

			if ($(this).is("input[type=text], input[type=number], textarea")) {
				settings['show'] = 'focus'
				settings['hide'] = 'blur'
			}

			var personnalised_settings = $(this).data("tooltip");
			if (personnalised_settings != undefined) {
				var data_settings = JSON.parse(personnalised_settings);
				for (var attrname in data_settings) { settings[attrname] = data_settings[attrname]; }
			}

			if (contentTip != "") {
				$(this).qtip(settings);
			}
		}
	});
};

var wdgCampaignDashboard;
jQuery(document).ready(function ($) {
	// Initialisation uniquement si construit
	if ($('ul.nav-menu').length > 0) {
		wdgCampaignDashboard = new WDGCampaignDashboard();
	}
});