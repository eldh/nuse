/*
 * Swipe 1.0
 *
 * Brad Birdsall, Prime
 * Copyright 2011, Licensed GPL & MIT
 *
*/

window.Swipe = function(container, element, options) {

  // return immediately if element doesn't exist
  if (!element) return null;

  var _this = this;

  // retreive options
  this.options = options || {};
  this.speed = this.options.speed || 300;
  this.callback = this.options.callback || function() {};
  this.delay = this.options.auto || 0;

  // reference dom elements
  this.element = element;
  this.container = container;


  // trigger slider initialization
  this.setup();

  // add event listeners
  if (this.element.addEventListener) {
    this.element.addEventListener('touchstart', this, false);
    this.element.addEventListener('touchmove', this, false);
    this.element.addEventListener('touchend', this, false);
    this.element.addEventListener('webkitTransitionEnd', this, false);
    this.element.addEventListener('msTransitionEnd', this, false);
    this.element.addEventListener('oTransitionEnd', this, false);
    this.element.addEventListener('transitionend', this, false);
    window.addEventListener('onscroll',this, false);
  }

};

Swipe.prototype = {

  setup: function() {


    this.height = this.container.getBoundingClientRect().height;
    this.handleheight = this.element.getBoundingClientRect().height;
    
    this.startpos = 0;


    // return immediately if measurement fails
    if (!this.height) return null;
    


  },

  slide: function(duration) {
    this.slideUp();
  },  
  slideUp: function(duration) {

    this.height = this.container.getBoundingClientRect().height;
    var style = this.container.style;

    // fallback to default speed
    if (duration == undefined) {
        duration = this.speed;
    }

    // set duration speed (0 represents 1-to-1 scrolling)
    style.webkitTransitionDuration = style.MozTransitionDuration = style.msTransitionDuration = style.OTransitionDuration = style.transitionDuration = duration + 'ms';

    // translate to given index position
    if (this.start.pageY >= this.handleheight) { //Started from up
	    style.MozTransform = style.webkitTransform = 'translate3d(0,' + -(this.height - this.handleheight) + 'px,0)';
	    style.msTransform = style.OTransform = 'translateY(' + -(this.height - this.handleheight) + 'px)';
    
    }
    else{
	    style.MozTransform = style.webkitTransform = 'translate3d(0,0px,0)';
	    style.msTransform = style.OTransform = 'translateY(0px)';    
    }
  },
 slideDown: function(duration) {

    this.height = this.container.getBoundingClientRect().height;
    var style = this.container.style;

    // fallback to default speed
    if (duration == undefined) {
        duration = this.speed;
    }

    // set duration speed (0 represents 1-to-1 scrolling)
    style.webkitTransitionDuration = style.MozTransitionDuration = style.msTransitionDuration = style.OTransitionDuration = style.transitionDuration = duration + 'ms';

    if (this.start.pageY <= this.handleheight) { //Started from bottom
	    style.MozTransform = style.webkitTransform = 'translate3d(0,' + (this.height - this.handleheight) + 'px,0)';
	    style.msTransform = style.OTransform = 'translateY(' + (this.height - this.handleheight) + 'px)';
    
    }
    else{
	    style.MozTransform = style.webkitTransform = 'translate3d(0,0px,0)';
	    style.msTransform = style.OTransform = 'translateY(0px)';    
    }
  },

  begin: function() {

    var _this = this;

    this.interval = (this.delay)
      ? setTimeout(function() { 
        _this.next(_this.delay);
      }, this.delay)
      : 0;
  
  },
  
  stop: function() {
    this.delay = 0;
    clearTimeout(this.interval);
  },
  
  resume: function() {
    this.delay = this.options.auto || 0;
  },

  handleEvent: function(e) {
    switch (e.type) {
      case 'touchstart': this.onTouchStart(e); break;
      case 'touchmove': this.onTouchMove(e); break;
      case 'touchend': this.onTouchEnd(e); break;
      case 'click': this.onClick(e); break;
      case 'webkitTransitionEnd':
      case 'msTransitionEnd':
      case 'oTransitionEnd':
      case 'transitionend': this.transitionEnd(e); break;
      case 'onscroll': this.onScroll(); break;
    }
  },

  transitionEnd: function(e) {
    
    this.callback(e);

  },
  onScroll: function(e) {
    console.log(e);
    alert(this.element.offsetTop);
  },
  onClick: function(e) {
    
    console.log(e);

  },

  onTouchStart: function(e) {
/* 	e.preventDefault(); */
    this.start = {

      // get touch coordinates for delta calculations in onTouchMove
      pageX: e.touches[0].pageX,
      pageY: e.touches[0].pageY,

      // set initial timestamp of touch sequence
      time: Number( new Date() )

    };

    // used for testing first onTouchMove event
    this.isScrolling = 0;
    
    // reset deltaY
    this.deltaY = 0;

    // set transition time to 0 for 1-to-1 touch movement
    this.container.style.MozTransitionDuration = this.container.style.webkitTransitionDuration = 0;

  },

  onTouchMove: function(e) {
	
    // ensure swiping with one touch and not pinching
    if(e.touches.length > 1 || e.scale && e.scale !== 1) return;

    this.deltaY = e.touches[0].pageY - this.start.pageY;

    // determine if scrolling test has run - one time test
    if ( typeof this.isScrolling == 'undefined') {
      this.isScrolling = !!( this.isScrolling || Math.abs(this.deltaY) < Math.abs(e.touches[0].pageX - this.start.pageX) );
    }

    // if user is not trying to scroll horisontally
    if (!this.isScrolling) {

      // prevent native scrolling 
      e.preventDefault();

      // cancel slideshow
      clearTimeout(this.interval);

      // increase resistance if first or last slide
      this.deltaY = this.deltaY / 
      	(
      		(
      			(this.deltaY > 0) 
      			|| ((this.start.pageY >= this.handleheight) && (Math.abs(this.deltaY) < this.height))
      		) 
      		? (this.deltaY/this.height/1.5 + 1) : 1
      	);
      
      // translate immediately 1-to-1
      this.container.style.MozTransform = this.container.style.webkitTransform = 'translate3d(0,' + (this.deltaY) + 'px,0)';

    }
  },

  onTouchEnd: function(e) {
	e.preventDefault();

    // determine if slide attempt triggers next/prev slide
    var isValidSlide = 
          Number(new Date()) - this.start.time < 250      // if slide duration is less than 250ms
          && Math.abs(this.deltaY) > 20                   // and if slide amt is greater than 20px
          || Math.abs(this.deltaY) > this.height/2;        // or if slide amt is greater than half the height

    if (!this.isScrolling) {
    	if(
    		((this.deltaY < 0) && isValidSlide) //Sliding up and valid slide
    		|| ((this.start.pageY < this.handleheight) && !isValidSlide) //Started from up and not valid slide 
    	){
    		this.slideUp(this.speed);
    	}
    	else{
			this.slideDown(this.speed);
    	}

    }

  }

};