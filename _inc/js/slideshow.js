/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

   
function Slideshow(){
    this.currentIndex = 0;      
    this.timeInterval = 5500;
	this.intervalId = null;
	this.sliderContainer = $('.slider-container');
	this.numSlide = $('.num-slide');
    this.init();  
}

Slideshow.prototype.init = function(){  
    $('.slider-choice span').click(function(e){
        this.gotoSlide($(e.currentTarget).text());
    }.bind(this));
      
    //initialisation
    this.elem = $('#slider');
    this.elem.find('.slider-item').hide();
	this.sliderFirstItem = this.elem.find('.slider-item:first');
    this.sliderFirstItem.show();
    this.elemCurrent = this.sliderFirstItem;
     
    //Play slider
    this.items = $('.slider-item');//tous les slides
    this.itemsNb = this.items.length;//nb de slides
    

    if ($(window).width() > 997){
        //Passage souris sur le slider
        this.sliderContainer.mouseover(this.stopSlider.bind(this));
        this.sliderContainer.mouseout(this.playSlider.bind(this));

        this.playSlider();
    }
};


Slideshow.prototype.playSlider = function(){  
    this.intervalId = setInterval(this.next.bind(this), this.timeInterval);
};

/**
 * Fonction d'arrêt du slider
 */
Slideshow.prototype.stopSlider = function(){
    clearInterval(this.intervalId);  
    this.intervalId = null;
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
    var direction = 1; //direction droite vers gauche
    if((parseInt(num)-1) < this.currentIndex && (parseInt(num)-1) - this.currentIndex === -1){ direction = -1; }

    if((parseInt(num)-1) === 0 && this.currentIndex === 2) {direction = 1; }
    else if((parseInt(num)-1) === 2 && this.currentIndex === 0) {direction = -1; }
    
    
    var cssStart = {"left" : direction*this.elem.width()};
    var cssEnd = {"left" : -direction*this.elem.width()};
    this.elem.find('#slide-'+num).show().css(cssStart);//élément qui va arriver
    this.elem.find('#slide-'+num).animate(
		{"top": 0, "left": 0}, 1500, function() {
			$( '.wdg-component-slider .slider-container #slider .slider-item .message-banner' ).css( 'width', '100%' ).css( 'width', '-=282px' );
		}
	);
    this.elemCurrent.animate(cssEnd, 1500);
    
    this.currentIndex = (parseInt(num))-1;
    this.pointSlide();
    this.elemCurrent = this.elem.find('#slide-'+num);
    
};

/**
 * Modifie l'apparence des points selon la slide affichée dans le défilement
 */
Slideshow.prototype.pointSlide = function(){
    this.numSlide.removeClass('active-slide').addClass('inactive-slide');
    $('#span-'+(this.currentIndex+1)).removeClass('inactive-slide').addClass('active-slide');
};

// création du slider
$(function(){
    new Slideshow();
    
});