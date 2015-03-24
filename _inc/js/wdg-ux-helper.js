jQuery(document).ready( function($) {
    UXHelper.init();
});


var UXHelper = (function($) {
	return {
		controllersPath: [],
		projectsList: '',
	    
		init: function() {
			$("#navigation").after('<div class="header-ux-helper-button"><a href="#">?</a></div>');
			UXHelper.initScroll();
			UXHelper.initClick();
		},
		
		initScroll: function() {
			$(document).scroll(function() {
				if ($(document).scrollTop() > 110) {
					$(".header-ux-helper-button").css("top", "40px");
					$("#ux-help-container").css("marginTop", "10px");
				} else {
					$(".header-ux-helper-button").css("top", "95px");
					$("#ux-help-container").css("marginTop", "12px");
				}
			});
		},
		
		initClick: function() {
			$(".header-ux-helper-button a").click(function() {
				if ($(".header-ux-helper-button>a").text() === '?') {
					UXHelper.getAjaxProjects();
					UXHelper.controllersPath = new Array();
					$(".header-ux-helper-button div#ux-help-container").remove();
					$(".header-ux-helper-button a").text("X");
					$(".header-ux-helper-button").prepend('<div id="ux-help-container" class="center" style="display: none;"></div>');
					UXHelper.fillUxContainer(UXPathHeader);
					$(".header-ux-helper-button div#ux-help-container").slideDown(400);
					
				} else {
					$(".header-ux-helper-button>a").text("?");
					$(".header-ux-helper-button div#ux-help-container").slideUp(400);
				}
			});
		},
		
		switchUxContainer: function(contentObject) {
			$(".header-ux-helper-button div#ux-help-container").empty();
			UXHelper.fillUxContainer(contentObject);
		},
		
		fillUxContainer: function(contentObject) {
			UXHelper.controllersPath.push(contentObject);
			$(".header-ux-helper-button div#ux-help-container").append('<div class="ux-help-container-top">'+contentObject.description_top+'</div>');
			UXHelper.fillLinkList(contentObject);
			if (UXHelper.controllersPath.length > 1) {
				$(".header-ux-helper-button div#ux-help-container").append('<div class="ux-help-container-to-parent"><a href="#">&lt;&lt; Précédent</a></div>');
				$(".ux-help-container-to-parent>a").click(function() {
					if (UXHelper.controllersPath.length > 1) {
						//Deletes 2 last items to reinit the first of them
						UXHelper.controllersPath.pop();
						UXHelper.switchUxContainer(UXHelper.controllersPath.pop());
					}
				});
			}
		},
		
		fillLinkList: function(contentObject) {
			switch (jQuery.type(contentObject.links)) {
				case 'array':
					for (var i = 0; i<contentObject.links.length; i++) {
						var link = contentObject.links[i];
						var href = (link.url !== undefined) ? link.url : '#';
						var margin_left = (contentObject.links.length % 2 !== 0 && i === contentObject.links.length - 1) ? 200 : 0;
						var content = '<div class="ux-help-container-link" style="margin-left: '+margin_left+'px"><a id="ux-help-link-'+i+'" class="button" href="'+href+'" data-index="'+i+'">'+link.label+'</a></div>';
						$(".header-ux-helper-button div#ux-help-container").append(content);
						$("#ux-help-link-"+i).click(function() {
							var link = UXHelper.controllersPath[UXHelper.controllersPath.length - 1].links[$(this).data("index")];
							if (link.path !== undefined) {
								UXHelper.switchUxContainer(link.path);
							}
						});
					}
					break;
					
				case 'string':
					if (contentObject.links === 'projectlist') {
						$(".header-ux-helper-button div#ux-help-container").append(UXHelper.projectsList);
					}
					break;
			}
			$(".header-ux-helper-button div#ux-help-container").append('<div style="clear: both;"></div>');
		},
		
		getAjaxProjects: function() {
			if (UXHelper.projectsList === '') {
				$.ajax({
					'type' : "POST",
					'url' : ajax_object.ajax_url,
					'data': { 
						'action': 'get_current_projects',
						'nb': 4
					}
				}).done(function(result){
					UXHelper.projectsList = result;
					if (UXHelper.controllersPath[UXHelper.controllersPath.length - 1] === UXPathHeaderFastProjects) {
						$(".ux-help-container-top").after(UXHelper.projectsList);
					}
				});
			}
		}
	};
    
})(jQuery);

var UXPathHeaderHelp = {
	description_top: 'Quel est le problème ?',
	links: [
		{
			label: 'Que fait ce site ?',
			url: '/descriptif'
		},
		{
			label: 'Qui est derrière ce site ?',
			url: '/lequipe'
		},
		{
			label: 'Je ne comprends vraiment rien...',
			url: '/contact'
		}
	]
};
var UXPathHeaderFastProjects = {
	description_top: 'Projets en cours',
	links: 'projectlist'
};
var UXPathHeaderFast = {
	description_top: 'Je veux aller vite pour',
	links: [
		{
			label: 'Créer ma page projet',
			url: '/creer-un-projet'
		},
		{
			label: 'Voir les projets en cours',
			path: UXPathHeaderFastProjects
		}
	]
};
var UXPathHeader = {
	description_top: 'Pourquoi avoir cliqué ?',
	links: [
		{
			label: 'Je suis pressé',
			path: UXPathHeaderFast
		},
		{
			label: 'Je ne comprends rien',
			path: UXPathHeaderHelp
		}
	]
};
