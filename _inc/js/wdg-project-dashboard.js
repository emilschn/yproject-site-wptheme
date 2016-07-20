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
                    e.preventDefault;
                    return false; //Empêche le défilement automatique lorsqu'on clique sur un lien avec un #
                });
                $("#ndashboard-navbar li a:not(.disabled)").first().trigger("click");
            }

            //Onglets information
            if ($(".bloc-grid").length > 0) {
                $(".bloc-grid .display-bloc").click(function () {
                    $(".bloc-grid .display-bloc").removeClass("active");
                    $(this).addClass("active");

                    $("#tab-container .tab-content").hide();
                    $("#tab-container #" + $(this).data("tab-target")).show();
                });

                $("#tab-container .tab-content").hide();
            }


            //Informations

            //Infos personnelles
            if ($("#userinfo_form").length > 0) {
                $("#userinfo_form").submit(function (e) {
                    e.preventDefault();
                    $("#userinfo_form_button").hide();
                    $("#userinfo_form_loading").show();
                    $("#userinfo_form input, #userinfo_form option").prop('disabled', true);
                    $.ajax({
                        'type': "POST",
                        'url': ajax_object.ajax_url,
                        'data': {
                            'action': 'save_user_infos',
                            'campaign_id': $("#userinfo_form").data("campaignid"),
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
                    }).done(function (result) {
                        if (result != "") {
                            var jsonResult = JSON.parse(result);
                            response = jsonResult.response;

                            if(jsonResult.response=="edit_user"){
                                $("#userinfo_form_button").show();
                                $("#userinfo_form_loading").hide();
                                $("#userinfo_form input, #userinfo_form option").prop('disabled', false);
                                $("#userinfo_form_errors").empty();
                                for (var i = 0; i < jsonResult.errors.length; i++) {
                                    $("#userinfo_form_errors").append("<li>" + jsonResult.errors[i] + "</li>");
                                }
                            }
                        }
                    });
                });
            }

            //Infos projet
            if ($("#projectinfo_form").length > 0) {
                $("#projectinfo_form").submit(function (e) {
                    e.preventDefault();
                    $("#projectinfo_form_button").hide();
                    $("#projectinfo_form_loading").show();
                    $("#projectinfo_form input, #projectinfo_form option").prop('disabled', true);
                    $.ajax({
                        'type': "POST",
                        'url': ajax_object.ajax_url,
                        'data': {
                            'action': 'save_project_infos',
                            'campaign_id': $("#userinfo_form").data("campaignid"),
                            'project_name': $("#update_project_name").val(),
                            'project_category': $("#update_project_category").val(),
                            'project_activity': $("#update_project_activity").val(),
                            'project_location': $("#update_project_location").val()
                        }
                    }).done(function (result) {
                        $("#projectinfo_form_button").show();
                        $("#projectinfo_form_loading").hide();
                        $("#projectinfo_form input, #projectinfo_form option").prop('disabled', false);

                        $("#projectinfo_form_errors").empty();
                        var jsonResult = JSON.parse(result)
                        for (var i = 0; i < jsonResult.errors.length; i++) {
                            $("#projectinfo_form_errors").append("<li>" + jsonResult.errors[i] + "</li>");
                        }
                    });
                });
            }

            //Infos financement
            if ($("#projectfunding_form").length > 0) {
                var nb_years_li_existing = ($("#estimated-turnover li").length);
                var start_year;

                //Etiquettes de numéros d'années pour le CA prévisionnel
                $("#first-payment-y").change(function(){
                    start_year = $("#first-payment-y").val();
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
                    $("#projectfunding_form_button").hide();
                    $("#projectfunding_form_loading").show();
                    $("#projectfunding_form input, #projectfunding_form option").prop('disabled', true);

                    var list_turnover = {};
                    $("#estimated-turnover li:visible").each(function(){
                        list_turnover[$(this).find('.year').html().toString()] = $(this).find('input').val();
                    });

                    $.ajax({
                        'type': "POST",
                        'url': ajax_object.ajax_url,
                        'data': {
                            'action': 'save_project_funding',
                            'campaign_id': $("#userinfo_form").data("campaignid"),
                            'minimum_goal': $("#update_minimum_goal").val(),
                            'maximum_goal': $("#update_maximum_goal").val(),
                            'funding_duration': $("#update_funding_duration").val(),
                            'roi_percent_estimated': $("#update_roi_percent_estimated").val(),
                            'first_payment_date': $("#first-payment-y").val()+"-"+($("#first-payment-m").prop("value"))+"-"+$("#first-payment-d").val(),
                            'list_turnover': JSON.stringify(list_turnover)
                        }
                    }).done(function (result) {
                        $("#projectfunding_form_button").show();
                        $("#projectfunding_form_loading").hide();
                        $("#projectfunding_form input, #projectfunding_form option").prop('disabled', false);

                        $("#projectfunding_form_errors").empty();
                        var jsonResult = JSON.parse(result)
                        for (var i = 0; i < jsonResult.errors.length; i++) {
                            $("#projectfunding_form_errors").append("<li>" + jsonResult.errors[i] + "</li>");
                        }
                    });
                });
            }

        }
    };

})(jQuery);