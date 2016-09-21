/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


//function Slideshow(){
//    
//}
//
//Slideshow.prototype.startSlider = function(){
//    
//};
//
//Slideshow.prototype.stopSlider = function(){
//    
//};

$( document ).ready(function() {

var currentIndex = 0,
    items = $('.slider-item'),
    itemsNb = items.length,
    timeInterval = 3000;

startSlider();

/**
 * Pour afficher l'image courante selon son index
 * 
 */
function cycleItems(){
    var item = $('.slider-container div').eq(currentIndex);
    items.hide();
    item.css('display','inline-block');
}

//Fonction autoSlide pour slider en mode automatique


//Fonction arrêt slider automatique
function stopSlider(){
    clearInterval(interval);
    interval = null;
}
//Fonction démarrage slider automatique
function startSlider(){
    interval = setInterval(function() { //interval id
        currentIndex += 1;
        if (currentIndex > itemsNb - 1) {
          currentIndex = 0;
        }
        cycleItems();
    }, timeInterval);
}


items.on('click', function(){
    interval !== null ? stopSlider() : startSlider();
    console.log(currentIndex);
});

});


