$(document).ready(function(){

  // données récupérés depuis les cookies et var (envoyés par index.php)
  var voteObject = JSON.parse(unescape(voteData)); // objet contenant toutes les données de la partie vote du jeu de données
  var fundingObject = JSON.parse(unescape(fundingData)); // objet contenant toutes les données de la partie funding du jeu de données
  var campaignUrl = JSON.parse(unescape(cookies['campaign_url']));


    //---- Graphiques de l'onglet évaluations ----//
      var listDates = []; // dates en abscisse

      var listVoteCurrent = []; // points montants des intentions d'investissements
      var listPreinvestmentCurrent = []; // points montants des pré-investissements
      var listVoteCurrentTotal = 0; // cumul des montants des intentions d'investissements
      var listPreinvestmentCurrentTotal = 0; // cumul des monants des pré-investissements

      var listVoteTarget = []; // points objectifs d'intentions d'investissements
      var listPreinvestmentTarget = []; // points objectifs de pré-investissements

      var evalChartObj = {}; // objet contenant les dates (index) + les montants des intentions et pré investissements

      // on ajoute les montants d'intentions d'investissements dans l'objet
      $.each(voteObject.list_vote.current, function (index, value) {
        if(typeof(evalChartObj[value.date]) == 'undefined'){
          evalChartObj[value.date + 'I' + index] = {};
        }
        evalChartObj[value.date + 'I' + index].list_vote = value.sum;
      });

      // on ajoute les montants de pré-investissements dans l'objet
      $.each(voteObject.list_preinvestment.current, function (index, value) {
        if(typeof(evalChartObj[value.date]) == 'undefined'){
          evalChartObj[value.date + 'I' + index] = {};
        }
        evalChartObj[value.date + 'I' + index].list_preinvestment = value.sum;
      });

      // on ajoute les objectifs d'intentions d'investissements dans l'objet
      $.each(voteObject.list_vote.target, function (index, value) {
        if(typeof(evalChartObj[index]) == 'undefined'){
          evalChartObj[index] = {};
        }
        evalChartObj[index].list_vote_target = value;
      });

      // on ajoute les objectifs de pré-investissements dans l'objet
      $.each(voteObject.list_preinvestment.target, function (index, value) {
        if(typeof(evalChartObj[index]) == 'undefined'){
          evalChartObj[index] = {};
        }
        evalChartObj[index].list_preinvestment_target = value;
      });

      // tri de l'objet par date
      evalChartObj = sortObjectByKey(evalChartObj);

      // on parcourt l'objet pour pousser les données dans les graphiques
      $.each(evalChartObj, function (index, value) {

        var foundDate = listDates.find(function(date) {
          return date == formatDate(index);
        });

        if(typeof(value.list_preinvestment) == 'undefined') {
          value.list_preinvestment = 0;
        }

        if(typeof(value.list_vote) == 'undefined') {
          value.list_vote = 0;
        }

        if(typeof(foundDate) == 'undefined') { // si la date n'existe pas encore dans l'objet

          listDates.push(formatDate(index));

          listPreinvestmentCurrentTotal += Number(value.list_preinvestment); // cumul des pré-investissements
          listPreinvestmentCurrent.push(listPreinvestmentCurrentTotal);

          listVoteCurrentTotal += Number(value.list_vote); // cumul des intentions
          listVoteCurrent.push(listVoteCurrentTotal);

          listVoteTarget.push(value.list_vote_target);
          listPreinvestmentTarget.push(value.list_preinvestment_target);

        }

        else{

          listPreinvestmentCurrent[listPreinvestmentCurrent.length-1] += Number(value.list_preinvestment);
          listPreinvestmentCurrentTotal = listPreinvestmentCurrent[listPreinvestmentCurrent.length-1];

          listVoteCurrent[listVoteCurrent.length-1] += Number(value.list_vote);
          listVoteCurrentTotal = listVoteCurrent[listVoteCurrent.length-1];
        }

      });

      // paramètres du graphique "Courbe des objectifs"
      var targetChartData = {
        labels: listDates,
        datasets: [
          {
            label: "Objectifs de pré-investissements (€)",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "#D3EAE9",
            borderColor: "#D3EAE9",
            data: listPreinvestmentTarget
          },
          {
            label: "Objectifs d'intentions d'investissements (€)",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "#B4B4B4",
            borderColor: "#B4B4B4",
            data: listVoteTarget
          },
          {
            label: "Pré-investissements (€)",
            fill: true,
            lineTension: 0.1,
            backgroundColor: "rgba(0, 135, 155, 0.6)",
            borderColor: "#00879b",
            data: listPreinvestmentCurrent
          },
          {
            label: "Intentions d'investissements (€)",
            fill: true,
            lineTension: 0.1,
            backgroundColor: "rgba(180, 180, 180, 0.6)",
            borderColor: "#B4B4B4",
            data: listVoteCurrent
          }
        ]
      };

		// création du graphique "Courbe des objectifs"
		var ctx = $("#goal-chart");
		var LineGraph = new Chart(ctx, {
			type: 'line',
			data: targetChartData,
			options: {
				tooltips: {
					callbacks: {
						title: function(tooltipItems, data) {
							return moment(data.labels[tooltipItems[0].index]).format('DD/MM/YYYY');;
						}
					}
				},
				options:{
					tooltips:{
						callbacks:{title:function(P,Q){return moment(Q.labels[P[0].index]).format('DD/MM/YYYY')}}
					}
				},
				scales: {
					xAxes: [{
						type: "time",
						time: {
							unit: 'day',
							min: listDates[0],
							displayFormats: {
								day: 'DD/MM/YYYY'
							}
						}
					}]
				}
			}
		});

      // création du graphique "Risque"
      new Chart(document.getElementById("risk-chart"),{
        "type":"bar",
        "data":{
          "labels":[
            "Très faible",
            "Faible",
            "Moyen",
            "Risqué",
            "Très risqué"
          ],
          "datasets":[{
            "label":"",
            "data":[
              voteObject.risk['1'],
              voteObject.risk['2'],
              voteObject.risk['3'],
              voteObject.risk['4'],
              voteObject.risk['5']
            ],
            "fill":false,
            "backgroundColor":[
              "#93626d",
              "#93626d",
              "#93626d",
              "#93626d",
              "#93626d"
            ]
          }]
        },
        "options":{
          legend:{
            display: false
          },
          "scales":{
            "yAxes":[{
              "ticks":{"beginAtZero":true}
            }]
          }
        }
      });

      // Création du graphique "Les internautes aimeraient avoir plus d’informations sur"
      var moreInfoObject = {
        'children': [
          {'name': 'Impact<br>sociétal', 'value': voteObject.more_info['impact']},
          {'name': 'Produit<br>Service', 'value': voteObject.more_info['product']},
          {'name': 'Structure<br>de l\'équipe', 'value': voteObject.more_info['team']},
          {'name': 'Prévisionnel<br>financier', 'value': voteObject.more_info['finance']},
          {'name': 'Autre', 'value': voteObject.more_info['other']},
        ]
      }
      var diameter = 600,
          color = d3.scaleOrdinal().range(["#93626d","#a37983","#b39199","#c4a9af","#d4c0c5"]);
      var bubble = d3.pack()
        .size([diameter, diameter])
        .padding(5);
      var svg = d3.select('#chart-infos').append('svg')
        .attr('width', 600)
        .attr('height', 600)
        .attr('class', 'chart-svg');
      var root = d3.hierarchy(moreInfoObject)
        .sum(function(d) { return d.value; })
        .sort(function(a, b) { return b.value - a.value; });
      bubble(root);
      var node = svg.selectAll('.node')
        .data(root.children)
        .enter()
        .append('g').attr('class', 'node')
        .attr('transform', function(d) { return 'translate(' + d.x + ' ' + d.y + ')'; })
        .append('g').attr('class', 'graph');
      node.append("circle")
        .attr("r", function(d) { return d.r; })
        .style("fill", function(d) {
          return color(d.data.name);
        });
      node.append("svg:title")
        .text(function(d) { return d.value + ' internautes'; });
      node.append("foreignObject")
        .attr("width", 100)
        .attr("height", 100)
        .attr('transform','translate(-50,-30)')
      .append("xhtml:p")
        .style("color", "#FFF")
        .style("text-align", "center")
        .attr("title", function(d) { return d.value + " internautes"; })
        .html(function(d) { return d.data.name; });


    //---- Graphiques de l'onglet levée de fonds ----//
      var listDates = []; // dates en abscisse

      var listInvestmentCurrent = []; // points montants des investissements
      var listInvestmentTarget = []; // points objectifs idéaux
      var listInvestmentCurrentTotal = 0; // cumul des montants d'investissements

      var investmentChartObj = {}; // objet contenant les dates (index) + les valeurs des investissements + objectifs

      // on ajoute les montants d'investissements dans l'objet
      $.each(fundingObject.list_investment.current, function (index, value) {
        if(typeof(investmentChartObj[value.date]) == 'undefined') {
          investmentChartObj[value.date + 'I' + index] = {};
        }
        investmentChartObj[value.date + 'I' + index].list_investment_current = value.sum;
      });

      // on ajoute les objectifs idéaux dans l'objet
      $.each(fundingObject.list_investment.target, function (index, value) {
        if(typeof(investmentChartObj[index]) == 'undefined') {
          investmentChartObj[index] = {};
        }
        investmentChartObj[index].list_investment_target = value;
      });

      // tri de l'objet par date
      investmentChartObj = sortObjectByKey(investmentChartObj);

      // on parcourt l'objet pour pousser les données dans les graphiques
      $.each(investmentChartObj, function (index, value) {

        var foundDate = listDates.find(function(date) {
          return date == formatDate(index);
        });

        if(typeof(value.list_investment_current) == 'undefined') {
          value.list_investment_current = 0;
        }

        if(typeof(foundDate) == 'undefined') { // si la date n'existe pas encore dans l'objet

          listDates.push(formatDate(index));

          listInvestmentCurrentTotal += Number(value.list_investment_current); // cumul des investissements
          listInvestmentCurrent.push(listInvestmentCurrentTotal);

          listInvestmentTarget.push(value.list_investment_target);

        }

        else{
          listInvestmentCurrent[listInvestmentCurrent.length-1] += Number(value.list_investment_current);
          listInvestmentCurrentTotal = listInvestmentCurrent[listInvestmentCurrent.length-1];
        }

      });

      // données du graphique "Valeurs des investissements"
      var investmentChartData = {
        labels: listDates,
        datasets: [
          {
            label: "Valeur des investissements (€)",
            fill: true,
            lineTension: 0.1,
            backgroundColor: "rgba(0, 135, 155, 0.6)",
            borderColor: "#00879b",
            data: listInvestmentCurrent
          },
          {
            label: "Objectifs idéaux",
            fill: true,
            lineTension: 0.1,
            backgroundColor: "rgba(180, 180, 180, 0.6)",
            borderColor: "#B4B4B4",
            data: listInvestmentTarget
          }
        ]
      };

      // création du graphique "Valeurs des investissements"
      var ctx = $("#investment-chart");
      var LineGraph = new Chart(ctx, {
        type: 'line',
        data: investmentChartData,
        options: {
          tooltips: {
            callbacks: {
                title: function(tooltipItems, data) {
                  return moment(data.labels[tooltipItems[0].index]).format('DD/MM/YYYY');;
                }
            }
          },
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }],
            xAxes: [{
                type: "time",
                time: {
                    unit: 'day',
                    min: listDates[0],

                    displayFormats: {
                        day: 'DD/MM/YYYY'
                    }
                }
            }]
          }
        }
      });

      // Données du graphique "Statistiques supplémentaires"
      var supStatsChartData = {
        datasets: [{
            data: [fundingObject.stats.percent_men, fundingObject.stats.percent_women],
            backgroundColor: ['#93626d','#b39199']
        }],
        labels: [
            'Hommes',
            'Femmes',
        ]
      };
      var ctx = $("#sup-stats-chart");

      // création du graphique "Statistiques supplémentaires"
      new Chart(ctx, {
        type: 'doughnut',
        data: supStatsChartData,
        options: {
          cutoutPercentage: 80,
          tooltips: {
            enabled: true,
            mode: 'single',
            callbacks: {
                label: function(tooltipItems, data) {
                    return data.labels[tooltipItems.index] + ' : ' + data.datasets[tooltipItems.datasetIndex].data[tooltipItems.index] + ' %';
                }
            }
          },
        }
      });

    //---- Graphiques de l'onglet visites ----//

      var listDates = []; // dates en abscisse

      var listNbVotePerDay = []; // points nb de votes/j
      var listNbInvestmentPerDay = []; // points nb investissements/j
      var listNbPreinvestmentPerDay = []; // points nb pré-investissements/j
      var listVisitPerDay = []; // points visites/j

      var visitVoteInvestChartObj = {}; // objet contenant les dates (en index) + visites/j + nb votes/j + nb pre-investissements/j + nb investissements/j

      // on ajoute le nombre d'évaluations / j dans l'objet
      $.each(voteObject.list_vote.current, function (index, value) {
        if(typeof(visitVoteInvestChartObj[value.date]) == 'undefined'){
          visitVoteInvestChartObj[value.date] = {};
        }
        if(typeof(visitVoteInvestChartObj[value.date.split('T')[0]].nb_vote) == 'undefined'){
          visitVoteInvestChartObj[value.date].nb_vote = 1;
        }
        else{
          visitVoteInvestChartObj[value.date].nb_vote += 1;
        }
      });

      // on ajoute le nombre de pré-invesissements / j dans l'objet
      $.each(voteObject.list_preinvestment.current, function (index, value) {
        if(typeof(visitVoteInvestChartObj[value.date.split('T')[0]]) == 'undefined'){
          visitVoteInvestChartObj[value.date.split('T')[0]] = {};
        }
        if(typeof(visitVoteInvestChartObj[value.date.split('T')[0]].nb_preinvestment) == 'undefined'){
          visitVoteInvestChartObj[value.date.split('T')[0]].nb_preinvestment = 1;
        }
        else{
          visitVoteInvestChartObj[value.date.split('T')[0]].nb_preinvestment += 1;
        }
      });

      // on ajoute le nombre d'invesissements / j dans l'objet
      $.each(fundingObject.list_investment.current, function (index, value) {
        if(typeof(visitVoteInvestChartObj[value.date.split('T')[0]]) == 'undefined'){
          visitVoteInvestChartObj[value.date.split('T')[0]] = {};
        }
        if(typeof(visitVoteInvestChartObj[value.date.split('T')[0]].nb_investment) == 'undefined'){
          visitVoteInvestChartObj[value.date.split('T')[0]].nb_investment = 1;
        }
        else{
          visitVoteInvestChartObj[value.date.split('T')[0]].nb_investment += 1;
        }
      });

      // paramètres pour récupérer les visites via l'API Google Analytics
      var visitsPostData = {
        'stat_type':'visits',
        'start_date':voteObject.start.split('T')[0],
        'end_date':fundingObject.end.split('T')[0],
        'path':campaignUrl
      };

      // paramètres pour récupérer les sources via l'API Google Analytics
      var sourcesPostData = {
        'stat_type':'sources',
        'start_date':voteObject.start.split('T')[0],
        'end_date':fundingObject.end.split('T')[0],
        'path':campaignUrl
      };

      // paramètres pour récupérer les villes via l'API Google Analytics
      var citiesPostData = {
        'stat_type':'cities',
        'start_date':voteObject.start.split('T')[0],
        'end_date':fundingObject.end.split('T')[0],
        'path':campaignUrl
      };

      // Graphique visites + votes + investissements + pré-investissements
      $.ajax({
        url : "../wp-content/plugins/appthemer-crowdfunding/analytics-api/visits.php", // appel de la "mini-api" pour faire le lien avec Google Analytics
        type : "POST",
        data : visitsPostData,
        dataType  : 'json',
        timeout : 30000,
        success : function(data){
          var date = [];
          var visits = [];
          for(var i in data) {
            var date = formatGoogleDate(data[i].date);
            if(typeof(visitVoteInvestChartObj[date]) == 'undefined'){
              visitVoteInvestChartObj[date] = {};
            }
            visitVoteInvestChartObj[date].visits = data[i].visits;
          }

          // tri de l'objet par date
          visitVoteInvestChartObj = sortObjectByKey(visitVoteInvestChartObj);

          // on parcourt l'objet pour pousser les données dans le graphique
          $.each(visitVoteInvestChartObj, function (index, value) {
            listDates.push(formatDate(index));
            listVisitPerDay.push(value.visits);
            if (value.nb_vote === undefined) {
              listNbVotePerDay.push(0);
            }
            else{
              listNbVotePerDay.push(value.nb_vote);
            }
            if (value.nb_investment === undefined) {
              listNbInvestmentPerDay.push(0);
            }
            else{
              listNbInvestmentPerDay.push(value.nb_investment);
            }
            if (value.nb_preinvestment === undefined) {
              listNbPreinvestmentPerDay.push(0);
            }
            else{
              listNbPreinvestmentPerDay.push(value.nb_preinvestment);
            }
          });

          // Paramètres du graphique
          var chartdata = {
            labels: listDates,
            datasets: [
              {
                label: "Visites",
                fill: true,
                lineTension: 0.1,
                backgroundColor: "rgba(167,167,167,0.6)",
                borderColor: "#A7A7A7",
                data: listVisitPerDay,
                type:"line",
                yAxisID: 'A'
              },
              {
                label: "Évaluations",
                yAxisID: 'B',
                data: listNbVotePerDay,
                backgroundColor: "#8A5658"
              },
              {
                label: "Investissements",
                yAxisID: 'B',
                data: listNbInvestmentPerDay,
                backgroundColor: "#1691A3"
              },
              {
                label: "Pré-investissements",
                yAxisID: 'B',
                data: listNbPreinvestmentPerDay,
                backgroundColor: "#66B7C3"
              }
            ]
          };

          // Création du graphique
          var ctx = $("#visit-chart");
          var LineGraph = new Chart(ctx, {
            type: 'bar',
            data: chartdata,
            options: {
              tooltips: {
                callbacks: {
                    title: function(tooltipItems, data) {
                      return moment(data.labels[tooltipItems[0].index]).format('DD/MM/YYYY');;
                    }
                }
              },
    					scales: {
    						yAxes: [
                  {
                    id: 'A',
                    type: 'linear',
                    position: 'left',
                    ticks: {
                      beginAtZero: true
                    }
    						  },
                  {
                    id: 'B',
                    type: 'linear',
                    position: 'right',
                    ticks: {
                      beginAtZero: true
                    }
    						  }
                ],
                xAxes: [{
                    type: "time",
                    time: {
                        unit: 'day',
                        min: listDates[0],

                        displayFormats: {
                            day: 'DD/MM/YYYY'
                        }
                    }
                }]
    					}
    				}
          });

        },
        error : function(data) {
        }
      });

      // Graphique des sources (principaux canaux)
      $.ajax({
        url : "../wp-content/plugins/appthemer-crowdfunding/analytics-api/visits.php",
        type : "POST",
        data : sourcesPostData,
        dataType  : 'json',
        timeout : 30000,
        success : function(data){
          var source = [];
          var visits = [];
          for(var i in data) {
            source.push(data[i].source);
            visits.push(data[i].visits);
          }
          var chartdata = {
            labels: source,
            datasets: [
              {
                label: "Visites",
                data: visits,
                backgroundColor: [
                  "#8F4D5B",
                  "#93626D",
                  "#A37982",
                  "#B39098",
                  "#BEA0A7"
    					]
              }
            ]
          };

          var ctx = $("#chanel-chart");
          var LineGraph = new Chart(ctx, {
            type: 'pie',
            data: chartdata
          });
        },
        error : function(data) {
        }
      });

      // Graphique des villes (tableau "Provenances")
      $.ajax({
        url : "../wp-content/plugins/appthemer-crowdfunding/analytics-api/visits.php",
        type : "POST",
        data : citiesPostData,
        dataType  : 'json',

        timeout : 30000,
        success : function(data){
          var cities = [];
          var visits = [];
          var totalVisits = 0;
          for(var i in data) {
            totalVisits = totalVisits + data[i].visits;
          }
          for(var i in data) {
            if(i < 5){
              $('#cities-tab tr:last').after('<tr class="txt-center"><td>' + data[i].cities + '</td><td>' + data[i].visits + '</td><td>' + (data[i].visits * 100 / totalVisits).toFixed(1) + ' %</td></tr>');
            }
            else {
              $('#cities-tab tr:last').after('<tr style="display:none;" class="txt-center"><td>' + data[i].cities + '</td><td>' + data[i].visits + '</td><td>' + (data[i].visits * 100 / totalVisits).toFixed(1) + ' %</td></tr>');
            }
          }
        },
        error : function(data) {
        }
      });

});


// Fonction permettant de trier un objet via son index
var sortObjectByKey = function(obj){
  var keys = [];
  var sorted_obj = {};
  for(var key in obj){
      if(obj.hasOwnProperty(key)){
          keys.push(key);
      }
  }
  keys.sort();
  $.each(keys, function(i, key){
      sorted_obj[key] = obj[key];
  });
  return sorted_obj;
};

// Fonction permettant de récupérer chaque cookie
var cookies = document.cookie.split("; ").
  map(function(el){ return el.split("="); }).
  reduce(function(prev,cur){ prev[cur[0]] = cur[1];return prev },{});

// Fonction de formatage de date pour affichage correct dans les graphiques
function formatDate(date){
  if(date.indexOf('T') != -1) {
    var date = date.split('T')[0];
  }
  else {
    var date = date.split('I')[0];
  }
  // date = new Date(date),
  //   dd = date.getDate(),
  //   mm = date.getMonth()+1,
  //   yyyy = date.getFullYear();
  // if(dd<10){
  //     dd='0'+dd;
  // }
  // if(mm<10){
  //     mm='0'+mm;
  // }
  // return mm+'/'+dd+'/'+yyyy;
  return date;
}

// Fonction de formatage des dates Google Analytics (yyyymmdd -> yyyy-mm-dd)
function formatGoogleDate(date) {
    var strDate = String(date),
      y = strDate.substring(0,4),
      m = strDate.substring(4,6),
      d = strDate.substring(6,8);
    return y+'-'+m+'-'+d;
}

// Pour relier correctement les points "sans data" dans Chart.js
Chart.pluginService.register({
  beforeDatasetsDraw: function(chart) {
    for (dataset of chart.config.data.datasets) {
      for (data of dataset._meta[chart.id].data) {
        data._model.skip = false;
        data._model.tension = 0;
      }
    }
  }
});