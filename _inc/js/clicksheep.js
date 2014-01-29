var ClickSheepAPI = (function($) {
    return {
		newones: [],
		sheepPath: "",
		
		create6Sheeps: function() {
			ClickSheepAPI.newSheep($("header"));
			ClickSheepAPI.newSheep($("header"));
			ClickSheepAPI.newSheep($("header"));
			ClickSheepAPI.newSheep($("header"));
			ClickSheepAPI.newSheep($("header"));
			ClickSheepAPI.newSheep($("header"));
		},
		
		init:function () {
			$(".sheep").click( function() {
				ClickSheepAPI.hideSheep(this);
			});
			$(".sheep").each(function() {
				if (!$(this).is(':animated')) ClickSheepAPI.restartSheep(this);
			});
		},

		restartSheep: function(sheep) {
			var startLeft = 0;
			var endLeft = $(window).width() - $(sheep).width();
			if ($(sheep).hasClass("reverse")) {
				startLeft = endLeft;
				endLeft = 0;
			}
			var duration = 45000;
			if ($(sheep).hasClass("fast")) {
				duration = 35000;
			}
			
			$(sheep).css("left", startLeft);
			$(sheep).animate(
				{left: endLeft}, 
				duration, 
				"linear",
				function() {
					if ($(sheep).hasClass("reverse")) {
						$(sheep).removeClass("reverse");
					} else {
						$(sheep).addClass("reverse");
					}
					ClickSheepAPI.restartSheep(this);
				}
			);
		},

		hideSheep: function(sheep) {
			$(sheep).stop();
			$(sheep).css("top", $(sheep).position().top);
			$(sheep).animate(
				{left: $(sheep).position().left - 10, top: $(sheep).position().top - 10, width: "+=20", height: "+=20", opacity: 0.25},
				500,
				"linear",
				function () {
					$(this).hide();
					ClickSheepAPI.newSheep(this);
					ClickSheepAPI.newSheep(this);
					ClickSheepAPI.newSheep(this);
					ClickSheepAPI.newSheep(this);
					setTimeout("ClickSheepAPI.init();", 500);
				}
			);
		},
		
		newSheep: function(element) {
			var sheep_classes = "sheep";
			if (Math.random()<.5) sheep_classes += " reverse";
			if (Math.random()<.5) sheep_classes += " fast";
			var testRandom = Math.random();
			while (testRandom > 0.9 && testRandom < 0.05) testRandom = Math.random();
			var randomTop = testRandom * parseInt($(document).height());
			var randomColor = (Math.random()<.5) ? "_gris" : "";
			var newone = $(element).after('<img class="'+sheep_classes+'" src="'+ClickSheepAPI.sheepPath+'/images/mouton_petit'+randomColor+'.gif" style="top: '+randomTop+'px" />');
			ClickSheepAPI.newones.push(newone);
		}
    }
})(jQuery);