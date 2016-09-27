/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

   
function Slideshow(){
    this.currentIndex = 0;      
    this.timeInterval = 3500;
    this.init();  
}

Slideshow.prototype.init = function(){  
    $('.slider-choice span').click(function(e){
        this.gotoSlide($(e.currentTarget).text());
    }.bind(this));
    
    //initialisation
    this.elem = $('#slider');
    this.elem.find('.slider-item').hide();
    this.elem.find('.slider-item:first').show();
    this.elemCurrent = this.elem.find('.slider-item:first');
   
    //Play slider
    this.items = $('.slider-item');//tous les slides
    this.itemsNb = this.items.length;//nb de slides
    this.playSlider();
   
    //Passage souris sur le slider
    $('.slider-container').mouseover(this.stopSlider.bind(this));
    $('.slider-container').mouseout(this.playSlider.bind(this));
       
};

/**
 * Affiche l'image en modifiant son display
 */
//Slideshow.prototype.cycleItems = function(){
//    this.item = $('.slider-item').eq(this.currentIndex);
//    this.items.hide();
//    this.item.css('display','inline-block');
//};


Slideshow.prototype.playSlider = function(){  
    intervalId = setInterval(this.next.bind(this), this.timeInterval);

    
    
//    
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
    clearInterval(intervalId);  
    intervalId = null;
};

Slideshow.prototype.next = function(){ 
    var num = (parseInt(this.currentIndex)) +1;
    if(num > this.itemsNb -1){
        num = 0;
    }
    this.gotoSlide(parseInt(num)+1);
};

//Slideshow.prototype.prev = function(){
//    var num = this.currentIndex -1;
//    if(num < 1){
//        num = this.itemsNb -1;
//    }
//    this.gotoSlide(parseInt(num)-1);
//};


Slideshow.prototype.gotoSlide = function(num){
    
    if((parseInt(num)-1) === this.currentIndex){ return false; }//évite de déclencher animation si clic sur repère slide courante
    
    //animation fadeIn/fadeOut
//    this.elemCurrent.fadeOut();//enlève la slide courante
//    this.elem.find('#slide-'+num).fadeIn();//affiche la slide sélectionnée  

    //animation en slide
    var sens = 1; //sens droite vers gauche
    if((parseInt(num)-1) < this.currentIndex && (parseInt(num)-1) - this.currentIndex === -1){ sens = -1; }

    if((parseInt(num)-1) === 0 && this.currentIndex === 2) {sens = 1; }
    else if((parseInt(num)-1) === 2 && this.currentIndex === 0) {sens = -1; }
    
    
    var cssDeb = {"left" : sens*this.elem.width()};
    var cssFin = {"left" : -sens*this.elem.width()};
    this.elem.find('#slide-'+num).show().css(cssDeb);//élément qui va arriver
    this.elem.find('#slide-'+num).animate({"top": 0, "left": 0}, 1000);
    this.elemCurrent.animate(cssFin, 1000);
    
    this.currentIndex = (parseInt(num))-1;
    this.pointSlide();
    this.elemCurrent = $('#slider').find('#slide-'+num);
    
};

/**
 * Modifie l'apparence des points selon la slide affichée dans le défilement
 */
Slideshow.prototype.pointSlide = function(){
    if(this.currentIndex === 0){
        $('#span-1').removeClass('inactive-slide').addClass('active-slide'); 
        $('#span-3').removeClass('active-slide').addClass('inactive-slide');
        $('#span-2').removeClass('active-slide').addClass('inactive-slide'); 
    }
    else if(this.currentIndex === 1){
        $('#span-2').removeClass('inactive-slide').addClass('active-slide'); 
        $('#span-1').removeClass('active-slide').addClass('inactive-slide'); 
        $('#span-3').removeClass('active-slide').addClass('inactive-slide'); 
    }
    else if(this.currentIndex === 2){
        $('#span-3').removeClass('inactive-slide').addClass('active-slide');
        $('#span-2').removeClass('active-slide').addClass('inactive-slide');
        $('#span-1').removeClass('active-slide').addClass('inactive-slide'); 
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


