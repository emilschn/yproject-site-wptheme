jQuery(document).ready( function($) {
    ProjectEditor.init();
});


var ProjectEditor = (function($) {
	return {
		elements: [],
	    
		init: function() {
			ProjectEditor.elements["subtitle"] = '#projects-banner #head-content #subtitle';
			
			for (elementKey in ProjectEditor.elements) {
				ProjectEditor.addEditButton(elementKey);
			}
			
			$(".edit-button").click(function() {
				ProjectEditor.startEditElement($(this).data("property"));
			});
		},
		
		addEditButton: function(property) {
			var buttonEdit = '<div id="wdg-edit-'+property+'" class="edit-button" data-property="'+property+'">E</div>';
			$(ProjectEditor.elements[elementKey]).after(buttonEdit);
			ProjectEditor.showEditButton(property);
		},
		
		showEditButton: function(property) {
			var elementId = ProjectEditor.elements[elementKey];
			$('#wdg-edit-'+property).css("left", $(elementId).position().left + $(elementId).width());
			$('#wdg-edit-'+property).css("top", $(elementId).position().top);
			$('#wdg-edit-'+property).show();
		},
		
		startEditElement: function(property) {
			switch (property) {
				case "subtitle":
					ProjectEditor.createInput(property);
					break;
			}
		},
		
		createInput: function(property) {
			var initValue = $(ProjectEditor.elements[property]).text();
			var newElement = '<input type="text" id="wdg-input-'+property+'" class="edit-input" value="'+initValue+'" />';
			$(ProjectEditor.elements[property]).after(newElement);
			$("#wdg-input-"+property).css("left", $(ProjectEditor.elements[property]).position().left);
			$("#wdg-input-"+property).css("top", $(ProjectEditor.elements[property]).position().top);
			
			var buttonValidate = '<div id="wdg-validate-'+property+'" class="edit-button-validate" data-property="'+property+'">V</div>';
			$("#wdg-input-"+property).after(buttonValidate);
			$('#wdg-validate-'+property).css("left", $("#wdg-input-"+property).position().left + $("#wdg-input-"+property).outerWidth());
			$('#wdg-validate-'+property).css("top", $("#wdg-input-"+property).position().top);
			$('#wdg-validate-'+property).click(function() {
				ProjectEditor.validateInput($(this).data("property"));
			});
			
			$(ProjectEditor.elements[property]).hide();
			$("#wdg-edit-"+property).hide();
		},
		
		validateInput: function(property) {
			$('#wdg-validate-'+property).text("...");
			$('#wdg-validate-'+property).unbind("click");
			$.ajax({
				'type' : "POST",
				'url' : ajax_object.ajax_url,
				'data': { 
					'action':	'save_edit_project',
					'property':	property,
					'value':	$("#wdg-input-"+property).val(),
					'id_campaign':  $("#content").data("campaignid")
				}
			}).done(function(result) {
				ProjectEditor.validateInputDone(result);
			});
		},
		
		validateInputDone: function(property) { 
			switch (property) {
				case "subtitle":
					$(ProjectEditor.elements[property]).text($("#wdg-input-"+property).val());
					$(ProjectEditor.elements[property]).show();
					$("#wdg-input-"+property).remove();
					ProjectEditor.showEditButton(property);
					$('#wdg-validate-'+property).remove();
					break;
			}
		}
	};
    
})(jQuery);