var tapArea, moved, startX, startY;

tapArea = document.querySelector('#menuhandle'); //element to delegate
moved = false; //flags if the finger has moved
startX = 0; //starting x coordinate
startY = 0; //starting y coordinate

//touchstart			
tapArea.ontouchstart = function(e) {

	moved = false;
	startX = e.touches[0].clientX;
  	startY = e.touches[0].clientY;
};

//touchmove	
tapArea.ontouchmove = function(e) {

        //if finger moves more than 10px flag to cancel
        //code.google.com/mobile/articles/fast_buttons.html
	if (Math.abs(e.touches[0].clientX - startX) > 10 ||
      	Math.abs(e.touches[0].clientY - startY) > 10) {
			moved = true;
			style.webkitTransitionDuration = style.MozTransitionDuration = style.msTransitionDuration = style.OTransitionDuration = style.transitionDuration = duration + 'ms';

  	}
};

//touchend
tapArea.ontouchend = function(e) {

	e.preventDefault();

        //get element from touch point
	var element = e.changedTouches[0].target;

        //if the element is a text node, get its parent.
	if (element.nodeType === 3) {	
		element = element.parentNode;
	}

	if (!moved) {
                //check for the element type you want to capture
		if (element.tagName.toLowerCase() === 'label') {
                        alert('tap');
                }
	}
};

//don't forget about touchcancel!
tapArea.ontouchcancel = function(e) {

        //reset variables
	moved = false;
	startX = 0;
  	startY = 0;
};