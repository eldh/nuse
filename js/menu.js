function Menu(parent){
	var self = this;
	this.parent = parent;
	this.parent.append(this.ul);
	this.newtopicform.append(this.newtopicinput);
	this.ul.append(this.newtopicform);
	this.newtopicform.focusout(function(){
		$(this).val('');
	});
	this.parent.append(this.menuhandle);
	this.newtopicform.submit(function(event){
		self.newtopic(event, self);
	});
/*
	this.menuhandle.tappable(function(){
		self.toggle();
	});
*/
}
Menu.prototype = {
	toggle: function(callback){
		callback();
	},
	menuhandle: $('<div id="menuhandle"><a href="#">Topics</a></div>'),
	newtopicform: $('<form id="addtopic"></form>'),
	newtopicinput: $('<input type="text" id="newtopic" placeholder="+" />'),
	ul: $('<ul class="nav" id="menuul"></ul>'),
	addTopics: function(topics){
		for (var i=0; i < topics.length; i++){
/* 			console.log(topics[i].replace(/\s/g, "")); */
			this.ul.append('<li><a href="#'+topics[i].replace(/\s/g, "")+'" class="internal">'+topics[i]+'</a></li>');
		}
		
		//Now we can do stuff with the elements that have been loaded
		$('a.internal').live('click',function(event){
			event.preventDefault();
			var offset = $($(this).attr('href')).offset().top;
			
			if (mobile == true){
				Menu.prototype.toggle(function(){
					$('html, body').animate({scrollTop:offset-50}, 500);
					alert($('#menuhandle').position().top);
				});
			} else{
				$('html, body').animate({scrollTop:offset-50}, 500);
			}
		});
		$('.up a').live('click',function(event){
			event.preventDefault();
			$('html, body').animate({scrollTop:0}, 500);
		});
		$('.topic').css('min-height', window.innerHeight);
	
	},
	getMenuitem: function(){
		return this.ul;
	},
	newtopic: function(event, _this){
		event.preventDefault();
		loadfull = loadcounter + 3;
	
		//Get the topic
		var topic = _this.newtopicinput.val();
		_this.newtopicinput.val("");
		_this.newtopicinput.blur();
		var nextlink = false;
		//Add the section
		nextlink = $('#menu a').first().attr('href').substr(1);
		var newsection = createSection(topic, nextlink);
		newsection.hide();
		newsection.addClass("newsection");
		topicsSection.prepend(newsection);
		
		topicsSection.prepend(newanimation);
	    var link = '<li><a href="#'+topic.replace(/\s/g, "")+'" class="internal">'+topic+'</a></li>';
	    _this.ul.children().eq(0).after(link); //Place it after the search form
		
		//Fix some other stuff with the new topic section
		$('.topic').css('min-height', window.innerHeight);
		if (mobile == true){
			Menu.prototype.toggle(function(){$('html, body').animate({scrollTop:0}, 500)});
		}
		else{
			$('html, body').animate({scrollTop:0}, 500);
		}
		newsection.focus();
	}


}