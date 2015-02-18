jQuery(document).ready( function($) {
    ProjectEditor.init();
});


var ProjectEditor = (function($) {
	return {
		elements: [],
		isEditing: false,
		
		initElements: function() {
			ProjectEditor.elements["title"] = {elementId: "#projects-banner #head-content #title", contentId: "#projects-banner #head-content #title span"};
			ProjectEditor.elements["subtitle"] = {elementId: "#projects-banner #head-content #subtitle", contentId: "#projects-banner #head-content #subtitle"};
			ProjectEditor.elements["summary"] = {elementId: "#post_bottom_content #projects-summary", contentId: "#post_bottom_content #projects-summary"};
			ProjectEditor.elements["rewards"] = {elementId: "#projects-right-desc #project-rewards-custom", contentId: "#projects-right-desc #project-rewards-custom"};
			ProjectEditor.elements["description"] = {elementId: "#post_bottom_content #project-content-description .zone-content", contentId: "#post_bottom_content #project-content-description .zone-edit"};
			ProjectEditor.elements["societal_challenge"] = {elementId: "#post_bottom_content #project-content-societal_challenge .zone-content", contentId: "#post_bottom_content #project-content-societal_challenge .zone-edit"};
			ProjectEditor.elements["added_value"] = {elementId: "#post_bottom_content #project-content-added_value .zone-content", contentId: "#post_bottom_content #project-content-added_value .zone-edit"};
			ProjectEditor.elements["economic_model"] = {elementId: "#post_bottom_content #project-content-economic_model .zone-content", contentId: "#post_bottom_content #project-content-economic_model .zone-edit"};
			ProjectEditor.elements["implementation"] = {elementId: "#post_bottom_content #project-content-implementation .zone-content", contentId: "#post_bottom_content #project-content-implementation .zone-edit"};
			ProjectEditor.elements["picture-head"] = {elementId: "#projects-banner #head-image #reposition-cover", contentId: "#single_project_admin_bar"};
			ProjectEditor.elements["video-zone"] = {elementId: "#post_bottom_content #projects-left-desc .video-zone", contentId: "#single_project_admin_bar"};
			
		},
		
		addEditButton: function(property) {
			var buttonEdit = '<div id="wdg-edit-'+property+'" class="edit-button" data-property="'+property+'"></div>';
			switch (property) {
				case "societal_challenge":
				case "added_value":
				case "economic_model":
				case "implementation":
					$(ProjectEditor.elements[elementKey].elementId).after(buttonEdit);
					$("#wdg-edit-"+property).hide();
					break;
					
				default:
					$(ProjectEditor.elements[elementKey].elementId).after(buttonEdit);
					ProjectEditor.showEditButton(property);
					break;
			}
		},
		
		showEditButton: function(property) {
			if (property !== "picture-head") {
				var elementId = ProjectEditor.elements[property].elementId;
				$("#wdg-edit-"+property).css("left", $(elementId).position().left + $(elementId).outerWidth());
				var marginTop = Number($(elementId).css("marginTop").replace("px", ""));
				$("#wdg-edit-"+property).css("top", $(elementId).position().top + marginTop);
				$("#wdg-edit-"+property).show();
			}
			
		},
		
		hideEditButton: function(property) {
			$("#wdg-edit-"+property).hide();
		},
		
		initClick: function() {
			$(".edit-button").click(function() {
				ProjectEditor.isEditing = true;
				var sProperty = $(this).data("property");
				switch (sProperty) {
					case "title":
					case "subtitle":
						ProjectEditor.createInput(sProperty);
						break;
					case "summary":
					case "rewards":
						ProjectEditor.createTextArea(sProperty);
						break;
					case "description":
					case "societal_challenge":
					case "added_value":
					case "economic_model":
					case "implementation":
						ProjectEditor.showEditableZone(sProperty);
						break;
					case "picture-head":
					case "video-zone":
						ProjectEditor.redirectParams(sProperty);
						break;
				}
			});
		},
	    
		init: function() {
			ProjectEditor.initElements();
			for (elementKey in ProjectEditor.elements) {
				if (elementKey !== "picture-head") {
					$(ProjectEditor.elements[elementKey].elementId).addClass("editable");
				}
				ProjectEditor.addEditButton(elementKey);
			}
			ProjectEditor.initClick();
		},
		
		getInitValue: function(property) {
			var buffer = '';
			
			switch (property) {
				case "title":
				case "subtitle":
				case "summary":
				case "rewards":
					buffer = $(ProjectEditor.elements[property].contentId).text();
					break;
			}
			
			return buffer;
		},
		
		createInput: function(property) {
			var initValue = ProjectEditor.getInitValue(property);
			var newElement = '<input type="text" id="wdg-input-'+property+'" class="edit-input" value="'+initValue+'" />';
			$(ProjectEditor.elements[property].elementId).after(newElement);
			$("#wdg-input-"+property).css("left", $(ProjectEditor.elements[property].elementId).position().left);
			$("#wdg-input-"+property).css("top", $(ProjectEditor.elements[property].elementId).position().top);
			
			var buttonValidate = '<div id="wdg-validate-'+property+'" class="edit-button-validate" data-property="'+property+'"></div>';
			$("#wdg-input-"+property).after(buttonValidate);
			$("#wdg-validate-"+property).css("left", $("#wdg-input-"+property).position().left + $("#wdg-input-"+property).outerWidth());
			$("#wdg-validate-"+property).css("top", $("#wdg-input-"+property).position().top);
			$("#wdg-validate-"+property).click(function() {
				ProjectEditor.validateInput($(this).data("property"));
			});
			
			$(ProjectEditor.elements[property].elementId).hide();
			$("#wdg-edit-"+property).hide();
		},
		
		createTextArea: function(property) {
			var initValue = ProjectEditor.getInitValue(property);
			var newElement = '<textarea id="wdg-input-'+property+'" class="edit-input">'+initValue+'</textarea>';
			$(ProjectEditor.elements[property].elementId).after(newElement);
			$("#wdg-input-"+property).css("left", $(ProjectEditor.elements[property].elementId).position().left);
			var marginTop = Number($(ProjectEditor.elements[property].elementId).css("marginTop").replace("px", ""));
			$("#wdg-input-"+property).css("top", $(ProjectEditor.elements[property].elementId).position().top + marginTop);
			var width = $(ProjectEditor.elements[property].elementId).width() - 4;
			$("#wdg-input-"+property).width(width);
			$("#wdg-input-"+property).height($(ProjectEditor.elements[property].elementId).height());
			
			var buttonValidate = '<div id="wdg-validate-'+property+'" class="edit-button-validate" data-property="'+property+'"></div>';
			$("#wdg-input-"+property).after(buttonValidate);
			$("#wdg-validate-"+property).css("left", $("#wdg-input-"+property).position().left + $("#wdg-input-"+property).outerWidth());
			$("#wdg-validate-"+property).css("top", $("#wdg-input-"+property).position().top);
			$("#wdg-validate-"+property).click(function() {
				ProjectEditor.validateInput($(this).data("property"));
			});
			
			$(ProjectEditor.elements[property].elementId).hide();
			$("#wdg-edit-"+property).hide();
		},
		
		showEditableZone: function(property) {
			$(ProjectEditor.elements[property].elementId).hide();
			$("#wdg-edit-"+property).hide();
			
			$(ProjectEditor.elements[property].contentId).show();
			$(ProjectEditor.elements[property].contentId + "> div").show();
			var buttonValidate = '<div id="wdg-validate-'+property+'" class="edit-button-validate" data-property="'+property+'"></div>';
			$(ProjectEditor.elements[property].contentId).after(buttonValidate);
			$("#wdg-validate-"+property).css("left", $(ProjectEditor.elements[property].contentId).position().left + $(ProjectEditor.elements[property].contentId).outerWidth());
			$("#wdg-validate-"+property).css("top", $(ProjectEditor.elements[property].contentId).position().top);
			$("#wdg-validate-"+property).click(function() {
				ProjectEditor.validateInput($(this).data("property"));
			});
		},
		
		redirectParams: function(property) {
			$(ProjectEditor.elements[property].contentId).children().children().each(function() {
				if ($(this).text() === "Paramètres") {
					window.location.href = $(this).attr("href") + "#" + property;
				}
			});
		},
		
		validateInput: function(property) {
			var value = $("#wdg-input-"+property).val();
			switch (property) {
				case "summary":
				case "rewards":
					value = (value + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br />' + '$2');
					break;
				case "description":
				case "societal_challenge":
				case "added_value":
				case "economic_model":
				case "implementation":
					value = tinyMCE.get("wdg-input-"+property).getContent();
					break;
			}
		    
			$("#wdg-validate-"+property).text("...");
			$("#wdg-validate-"+property).unbind("click");
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
					'action':	'save_edit_project',
					'property':	property,
					'value':	value,
					'id_campaign':  $("#content").data("campaignid")
				}
			}).done(function(result) {
				ProjectEditor.validateInputDone(result);
			});
		},
		
		validateInputDone: function(property) { 
			switch (property) {
				case "title":
				case "subtitle":
				case "summary":
				case "rewards":
					ProjectEditor.updateText(property);
					$(ProjectEditor.elements[property].elementId).show();
					$("#wdg-input-"+property).remove();
					ProjectEditor.showEditButton(property);
					$("#wdg-validate-"+property).remove();
					break;
				case "description":
				case "societal_challenge":
				case "added_value":
				case "economic_model":
				case "implementation":
					ProjectEditor.updateText(property);
					$(ProjectEditor.elements[property].elementId).show();
					$(ProjectEditor.elements[property].contentId).hide();
					ProjectEditor.showEditButton(property);
					$("#wdg-validate-"+property).remove();
					break;
			}
			ProjectEditor.isEditing = false;
		},
		
		updateText: function(property) { 
			switch (property) {
				case "title":
				case "subtitle":
					$(ProjectEditor.elements[property].contentId).text($("#wdg-input-"+property).val());
					break;
				case "summary":
				case "rewards":
					var value = $("#wdg-input-"+property).val();
					value = (value + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + '<br />' + '$2');
					$(ProjectEditor.elements[property].contentId).html(value);
					break;
				case "description":
				case "societal_challenge":
				case "added_value":
				case "economic_model":
				case "implementation":
					$(ProjectEditor.elements[property].elementId).html(tinyMCE.get("wdg-input-"+property).getContent());
					break;
			}
		}
	};
    
})(jQuery);