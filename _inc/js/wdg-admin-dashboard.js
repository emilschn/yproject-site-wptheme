jQuery(document).ready( function($) {
    WDGAdminDashboard.init();
});


var WDGAdminDashboard = (function($) {
	return {
		init: function() {
			$(".db-money-flow-item h2").click(function() {
				var targetId = $(this).data("target");
				if ($("#extendable-" + targetId).is(":visible")) {
					$.ajax({
						'type' : "POST",
						'url' : ajax_object.ajax_url,
						'data': { 
						      'action': 'show_project_money_flow',
						      'campaign_id' : targetId
						}
					}).done(function(result){
						$("#extendable-" + targetId).html(result);
						setTimeout(function() {WDGAdminDashboard.setDataTable(targetId); }, 100);
					});
				}
			});
		},
		
		setDataTable: function(targetId) {
			$("#extendable-" + targetId + " table").DataTable({
				order: [[ 0, "desc" ]]
			});
		}
	};
    
})(jQuery);