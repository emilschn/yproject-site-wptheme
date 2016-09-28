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


Slideshow.prototype.playSlider = function(){  
    intervalId = setInterval(this.next.bind(this), this.timeInterval);
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


Slideshow.prototype.gotoSlide = function(num){
    
    if((parseInt(num)-1) === this.currentIndex){ return false; }//évite de déclencher animation si clic sur repère slide courante
    
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