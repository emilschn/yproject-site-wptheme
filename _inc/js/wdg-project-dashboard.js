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
            if ($("#ndashboard-navbar li").length > 0) {
                $("#ndashboard-navbar li:not(.disabled)").click(function () {
                    $("#ndashboard-navbar li").removeClass("active");
                    $(this).addClass("active");

                    $("#ndashboard-content .page-dashboard").hide();
                    $("#ndashboard-content ." + $(this).data("page-target")).show();
                });
                $("#ndashboard-navbar li:not(.disabled)").first().trigger("click");
            }

            //Informations
            if ($("#userinfo_form").length > 0) {
                $("#userinfo_form").submit(function (e) {
                    e.preventDefault();
                    $("#userinfo_form_button").hide();
                    $("#userinfo_form_loading").show();
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
                        WDGProjectDashboard.formInvestReturnEvent(result);
                    });
                });
            }
        },

        formInvestReturnEvent: function (result) {
            var response = "";
            if (result != "") {
                var jsonResult = JSON.parse(result);
                response = jsonResult.response;
                console.log(jsonResult);
            }
            switch (response) {
                case "edit_user":
                    $("#userinfo_form_button").show();
                    $("#userinfo_form_loading").hide();
                    $("#userinfo_form_errors").empty();
                    for (var i = 0; i < jsonResult.errors.length; i++) {
                        $("#userinfo_form_errors").append("<li>" + jsonResult.errors[i] + "</li>");
                    }
                    break;
            }
        }


    };

})(jQuery);