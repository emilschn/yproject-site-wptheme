var ClickSheepAPI = (function($) {
    return {
		newones: [],
		sheepPath: "",
		
		create6Sheeps: function() {
			ClickSheepAPI.newSheep($("header"), 1);
			ClickSheepAPI.newSheep($("header"), 1);
			ClickSheepAPI.newSheep($("header"), 2);
			ClickSheepAPI.newSheep($("header"), 2);
			ClickSheepAPI.newSheep($("header"), 3);
			ClickSheepAPI.newSheep($("header"), 4);
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
			if ($(sheep).hasClass("realfast")) {
				duration = 10000;
			
			} else if ($(sheep).hasClass("fast")) {
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
					ClickSheepAPI.newSheep(this, 1);
					ClickSheepAPI.newSheep(this, 2);
					ClickSheepAPI.newSheep(this, 3);
					ClickSheepAPI.newSheep(this, 4);
					setTimeout("ClickSheepAPI.init();", 500);
				}
			);
		},
		
		newSheep: function(element, quarter) {
			var sheep_classes = "sheep";
			if (Math.random()<.5) sheep_classes += " reverse";
			var testSpeed = Math.random();
			if (testSpeed < 0.04) sheep_classes += " realfast";
			else if (testSpeed < .5) sheep_classes += " fast";
			var testRandom = Math.random();
			var max = 0.25 * quarter;
			while (testRandom > max || testRandom < 0.1) testRandom = Math.random();
			var randomTop = testRandom * parseInt($(document).height());
			var randomColor = (Math.random()<.5) ? "_gris" : "";
			var newone = $(element).after('<img class="'+sheep_classes+'" src="'+ClickSheepAPI.sheepPath+'/images/mouton_petit'+randomColor+'.gif" style="top: '+randomTop+'px" />');
			ClickSheepAPI.newones.push(newone);
		}
    }
})(jQuery);