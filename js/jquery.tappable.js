;(function($){var touchSupported=('ontouchstart'in window)
$.fn.tappable=function(options){var cancelOnMove=true,onlyIf=function(){return true},touchDelay=0,callback
switch(typeof options){case'function':callback=options
break;case'object':callback=options.callback
if(typeof options.cancelOnMove!='undefined'){cancelOnMove=options.cancelOnMove}
if(typeof options.onlyIf!='undefined'){onlyIf=options.onlyIf}
if(typeof options.touchDelay!='undefined'){touchDelay=options.touchDelay}
break;}
var fireCallback=function(el,event){if(typeof callback=='function'&&onlyIf(el)){callback.call(el,event)}}
if(touchSupported){this.bind('touchstart',function(event){var el=this
if(onlyIf(this)){$(el).addClass('touch-started')
window.setTimeout(function(){if($(el).hasClass('touch-started')){$(el).addClass('touched')}},touchDelay)}
return true})
this.bind('touchend',function(event){var el=this
if($(el).hasClass('touch-started')){$(el).removeClass('touched').removeClass('touch-started')
fireCallback(el,event)}
return true})
this.bind('click',function(event){event.preventDefault()})
if(cancelOnMove){this.bind('touchmove',function(){$(this).removeClass('touched').removeClass('touch-started')})}}else if(typeof callback=='function'){this.bind('click',function(event){if(onlyIf(this)){callback.call(this,event)}})}
return this}})(jQuery);