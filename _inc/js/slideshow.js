/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

   
function Slideshow(){
    this.currentIndex = 0;      
    this.timeInterval = 3000;
    this.init($('.slider-container'));
    this.startSlider();  
    this.imgListenner();
    
}

/**
 * Affiche l'image en modifiant son display
 */
Slideshow.prototype.cycleItems = function(){
    this.item = $('.slider-item').eq(this.currentIndex);
    this.items.hide();
    this.item.css('display','inline-block');
};


Slideshow.prototype.startSlider = function(){   
//    this.items = $('.slider-item');
//    this.itemsNb = this.items.length;
//    if(!this.interval){
//        this.interval = setInterval(function() { //interval id             
//            this.currentIndex += 1;       
//            if (this.currentIndex > this.itemsNb - 1) {
//                this.currentIndex = 0;
//            }
//            this.cycleItems();//affiche les images une à une
//            this.pointSlide();//modifie apparence des points        
//        }.bind(this), this.timeInterval);
//    }
};

/**
 * Fonction d'arrêt du slider
 */
Slideshow.prototype.stopSlider = function(){
    clearInterval(this.interval);
    this.interval = null;
};

Slideshow.prototype.init = function(){
    
    $('.slider-choice span').click(function(e){
        this.gotoSlide($(e.currentTarget).text());
    }.bind(this));
    //initialisation
    elem = $('#slider');
    elem.find('.slider-item').hide();
    elem.find('.slider-item:first').show();
    this.elemCurrent = elem.find('.slider-item:first');
};

Slideshow.prototype.gotoSlide = function(num){
    this.currentIndex = num-1;
    this.elemCurrent.fadeOut();//enlève la slide courante
    elem.find('#slide-'+num).fadeIn();//affiche la slide sélectionnée
    
    this.pointSlide();
    this.elemCurrent = $('#slider').find('#slide-'+num);
};

/**
 * Fonction start/stop sur click slide
 */
Slideshow.prototype.imgListenner = function(){
    this.items.on('click', function(){
        this.interval !== null ? this.stopSlider() : this.startSlider();
    }.bind(this));    
};

/**
 * Modifie l'apparence des points selon la slide affichée dans le défilement
 */
Slideshow.prototype.pointSlide = function(){
    if(this.currentIndex === 0){
        $('#slide-1').removeClass('inactive-slide').addClass('active-slide'); 
        $('#slide-3').removeClass('active-slide').addClass('inactive-slide');
        $('#slide-2').removeClass('active-slide').addClass('inactive-slide'); 
    }
    else if(this.currentIndex === 1){
        $('#slide-2').removeClass('inactive-slide').addClass('active-slide'); 
        $('#slide-1').removeClass('active-slide').addClass('inactive-slide'); 
        $('#slide-3').removeClass('active-slide').addClass('inactive-slide'); 
    }
    else if(this.currentIndex === 2){
        $('#slide-3').removeClass('inactive-slide').addClass('active-slide');
        $('#slide-2').removeClass('active-slide').addClass('inactive-slide');
        $('#slide-1').removeClass('active-slide').addClass('inactive-slide'); 
    }
};
    




//////////////

//$( document ).ready(function() {
//
//var currentIndex = 0,
//    items = $('.slider-item'),
//    itemsNb = items.length,
//    timeInterval = 3000;
//
//// Slider en mode automatique dès l'arrivée sur la page
//startSlider();
//
///**
// * Pour afficher l'image courante selon son index
// */
//function cycleItems(){
//    var item = $('.slider-container div').eq(currentIndex);
//    items.hide();
//    item.css('display','inline-block');
//}
//
////Fonction autoSlide pour slider en mode automatique
//
//
////Fonction arrêt slider automatique
//function stopSlider(){
//    clearInterval(interval);
//    interval = null;
//}
////Fonction démarrage slider automatique
//function startSlider(){
//    interval = setInterval(function() { //interval id
//        currentIndex += 1;
//        if (currentIndex > itemsNb - 1) {
//          currentIndex = 0;
//        }
//        cycleItems();
//    }, timeInterval);
//}
//
//
//items.on('click', function(){
//    interval !== null ? stopSlider() : startSlider();
//    console.log(currentIndex);
//});
//
//});


