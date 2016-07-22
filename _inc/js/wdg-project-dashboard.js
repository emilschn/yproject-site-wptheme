jQuery(document).ready(function ($) {
    WDGProjectDashboard.init();
});


var WDGProjectDashboard = (function ($) {
    return {
        forceInvestSubmit: false,
        currentOpenedROI: 0,

        init: function () {
            //Gestion de l'AJAX pour la lightbox de ROI
            if ($(".transfert-roi-open").length > 0) {
                $(".transfert-roi-open").click(function () {
                    if ($(this).data('roideclaration-id') !== WDGProjectDashboard.currentOpenedROI) {
                        //Affichage
                        WDGProjectDashboard.currentOpenedROI = $(this).data('roideclaration-id');
                        $("#wdg-lightbox-transfer-roi #lightbox-content .loading-content").html("");
                        $("#wdg-lightbox-transfer-roi #lightbox-content .loading-image").show();
                        $("#wdg-lightbox-transfer-roi #lightbox-content .loading-form").hide();

                        //Lancement de la requête pour récupérer les utilisateurs et les sommes associées
                        $.ajax({
                            'type': "POST",
                            'url': ajax_object.ajax_url,
                            'data': {
                                'action': 'display_roi_user_list',
                                'roideclaration_id': $(this).data('roideclaration-id')
                            }
                        }).done(function (result) {
                            var content = '<table>';
                            content += '<tr><td>Utilisateur</td><td>Investissement</td><td>Versement</td><td>Commission</td></tr>';
                            content += result;
                            content += '</table>';
                            $("#wdg-lightbox-transfer-roi #lightbox-content .loading-content").html(content);
                            $("#wdg-lightbox-transfer-roi #lightbox-content .loading-image").hide();
                            $("#wdg-lightbox-transfer-roi #lightbox-content .loading-form input#hidden-roi-id").val(WDGProjectDashboard.currentOpenedROI);
                            $("#wdg-lightbox-transfer-roi #lightbox-content .loading-form").show();
                        });
                    }
                });
            }

            //Onglets dashboard
            if ($("#ndashboard-navbar li a").length > 0) {
                $("#ndashboard-navbar li a:not(.disabled)").click(function (e) {

                    $("#ndashboard-navbar li a").removeClass("active");
                    $(this).addClass("active");

                    $("#ndashboard-content .page-dashboard").hide();
                    $("#ndashboard-content " + $(this).attr("href")).show();
                    history.pushState(null, null, $(this).attr("href"));
                    return false; //Empêche le défilement automatique lorsqu'on clique sur un lien avec un #
                });
                var hash = window.location.hash
                var tabsaved = $('#ndashboard-navbar li a[href="'+window.location.hash+'"]')
                if(hash != '' && !tabsaved.hasClass("disabled")){
                    tabsaved.trigger("click");
                } else {
                    $("#ndashboard-navbar li a:not(.disabled)").first().trigger("click");
                }
            }

            //Infobulles
            $('#ndashboard .infobutton[title!=""]').qtip({
                position: {
                    my: 'bottom center',
                    at: 'top center',
                },
                style: {
                    classes: 'qtip-tipsy qtip-shadow'
                }
            });

            //Onglets information
            if ($(".bloc-grid").length > 0) {
                $(".bloc-grid .display-bloc").click(function () {
                    if($(this).hasClass("active")){
                        $(".bloc-grid .display-bloc").removeClass("active");
                        $("#tab-container .tab-content").hide();

                    } else {
                        $(".bloc-grid .display-bloc").removeClass("active");
                        $("#tab-container .tab-content").hide();
                        $(this).addClass("active");
                        $("#tab-container #" + $(this).data("tab-target")).show();
                    }
                });

                $("#tab-container .tab-content").hide();
            }


            //Informations
            var update_tab = function(data_to_update, form_id, form_button_id, form_loading_id, form_errors_id){
                $("#"+form_button_id).hide();
                $("#"+form_loading_id).show();
                $("#"+form_id+" input, #"+form_id+" select").prop('disabled', true);

                data_to_update.campaign_id= $("#userinfo_form").data("campaignid");
                $.ajax({
                    'type': "POST",
                    'url': ajax_object.ajax_url,
                    'data': data_to_update
                }).done(function (result) {
                    if (result != "") {
                        var jsonResult = JSON.parse(result);
                        response = jsonResult.response;
                        $("#"+form_button_id).show();
                        $("#"+form_loading_id).hide();
                        $("#"+form_id+" input, #"+form_id+" select").prop('disabled', false);
                        $("#"+form_errors_id).empty();
                        for (var i = 0; i < jsonResult.errors.length; i++) {
                            $("#"+form_errors_id).append("<li>" + jsonResult.errors[i] + "</li>");
                        }
                    }
                });
            };

            //Infos personnelles
            if ($("#tab-user-infos").length > 0) {
                $("#userinfo_form").submit(function (e) {
                    e.preventDefault();
                    var data_to_update = {
                        'action': 'save_user_infos',
                        'invest_type': $("#invest_type").val(),
                        'gender': $("#update_gender").val(),
                        'firstname': $("#update_firstname").val(),
                        'lastname': $("#update_lastname").val(),
                        'birthday_day': $("#update_birthday_day").val(),
                        'birthday_month': $("#update_birthday_month").val(),
                        'birthday_year': $("#update_birthday_year").val(),
                        'birthplace': $("#update_birthplace").val(),
                        'nationality': $("#update_nationality").val(),
                        'address': $("#update_address").val(),
                        'postal_code': $("#update_postal_code").val(),
                        'city': $("#update_city").val(),
                        'country': $("#update_country").val(),
                        'telephone': $("#update_mobile_phone").val(),
                        'is_project_holder': $("#input_is_project_holder").val(),
                    }
                    update_tab(data_to_update, "userinfo_form", "userinfo_form_button", "userinfo_form_loading", "userinfo_form_errors")
                });
            }

            //Infos organisation
            if ($("#tab-organization").length > 0) {
                $("#orgainfo_form").submit(function (e) {
                    e.preventDefault();
                    var data_to_update = {
                        'action': 'save_project_organisation',
                        'project-organisation': $("#update_project_organisation").val()
                    }
                    update_tab(data_to_update, "orgainfo_form", "orgainfo_form_button", "orgainfo_form_loading", "orgainfo_form_errors");

                });

                $("#update_project_organisation").change(function(e){
                    var newval = $("#update_project_organisation").val();

                    if(newval!=''){
                        $("#edit-orga-button").show();
                        var newname = $("#update_project_organisation").find('option:selected').text();
                        $("#edit-orga-button").attr("href",$("#edit-orga-button").data("url-edit")+newval);

                        $("#edit-orga-button").text("Editer "+newname);
                    } else {
                        $("#edit-orga-button").hide();
                    }

                });
            }

            //Infos projet
            if ($("#tab-project").length > 0) {
                $("#projectinfo_form").submit(function (e) {
                    e.preventDefault();
                    var data_to_update = {
                        'action': 'save_project_infos',
                        'project_name': $("#update_project_name").val(),
                        'backoffice_summary' : tinyMCE.get('update_backoffice_summary').getContent(),
                        'project_category': $("#update_project_category").val(),
                        'project_activity': $("#update_project_activity").val(),
                        'project_location': $("#update_project_location").val()
                    }
                    update_tab(data_to_update, "projectinfo_form", "projectinfo_form_button", "projectinfo_form_loading", "projectinfo_form_errors");
                });
            }

            //Infos financement
            if ($("#tab-funding").length > 0) {
                var nb_years_li_existing = ($("#estimated-turnover li").length);

                //Etiquettes de numéros d'années pour le CA prévisionnel
                $("#first-payment-y").change(function(){
                    var start_year = $("#first-payment-y").val();
                    $("#estimated-turnover li .year").each(function(index){
                        $(this).html((parseInt(start_year)+index));
                    });
                });

                //Cases pour le CA prévisionnel
                $("#update_funding_duration").change(function() {
                    var new_nb_years = parseInt($("#update_funding_duration").val());

                    //Ajoute des boîtes au besoin
                    if(new_nb_years > nb_years_li_existing){
                        var newlines = $("#estimated-turnover").html();

                        for(i=0; i<new_nb_years-nb_years_li_existing;i++){
                            newlines = newlines+
                                '<li><label>Année <span class="year"></span></label>'+
                                '<input type="text" value="0"/></li>'
                        }
                        $("#estimated-turnover").html(newlines);

                        //MAJ des étiquettes "Année XXXX"
                        $("#first-payment-y").trigger("change");
                        nb_years_li_existing = new_nb_years;
                    } else {
                        //N'affiche que les boites nécessaires
                        $("#estimated-turnover li").hide();
                        $("#estimated-turnover li").slice(0,new_nb_years).show();
                    }
                    nb_years_li_existing = Math.max(new_nb_years,nb_years_li_existing);
                });
                $("#update_funding_duration").trigger('change');

                $("#projectfunding_form").submit(function (e) {
                    e.preventDefault();

                    var list_turnover = {};
                    $("#estimated-turnover li:visible").each(function(){
                        list_turnover[$(this).find('.year').html().toString()] = $(this).find('input').val();
                    });
                    var data_to_update = {
                        'action': 'save_project_funding',
                        'minimum_goal': $("#update_minimum_goal").val(),
                        'maximum_goal': $("#update_maximum_goal").val(),
                        'funding_duration': $("#update_funding_duration").val(),
                        'roi_percent_estimated': $("#update_roi_percent_estimated").val(),
                        'first_payment_date': $("#first-payment-y").val()+"-"+($("#first-payment-m").prop("value"))+"-"+$("#first-payment-d").val(),
                        'list_turnover': JSON.stringify(list_turnover)
                    }
                    update_tab(data_to_update, "projectfunding_form", "projectfunding_form_button", "projectfunding_form_loading", "projectfunding_form_errors");

                });
            }

            //Infos communication
            if ($("#tab-communication").length > 0) {
                $("#communication_form").submit(function (e) {
                    e.preventDefault();
                    var data_to_update = {
                        'action': 'save_project_communication',
                        'website': $("#update_website").val(),
                        'facebook': $("#update_facebook").val(),
                        'twitter': $("#update_twitter").val()
                    }
                    update_tab(data_to_update, "communication_form", "communication_form_button", "communication_form_loading", "communication_form_errors");
                });
            }

        }
    };

})(jQuery);