var loadcounter = 0;
var loadfull = 200;
var mobile = false;
var slider;
var newanimation = '<div class="newanimation animation">Laddar ämne...</div>';
var dot = '<div class="dot"></div>';

$(document).ready(function(){
	sections = $('#sections');
	menu = $('#menu');
	var menuTopics;
	addTopicForm = $('#addtopic');
	addTopicForm.submit(addTopic);
	getTopics();
	
	$('#newtopic').focusout(function(){
		$(this).val('');
	});
	
	var footer = $('#footercontent');
	menu.append($("<div id='dots'></div>"));
	$('#closelink').click(function(){
		$('#textwrapper').fadeOut(function(){
			$('#text .content').html("");
		});
		scroll(0,0);
	})
	$('a.internal').live('click',function(event){
		event.preventDefault();
		var offset = $($(this).attr('href')).offset().top;
		$.scroll(offset-80, 1000);
	});
	$('.textlinks a').live('click', function(event){
		event.preventDefault();
		var url = $(this).attr('href');
		getArticle(url);
		return false;
	});
	$('#textwrapper .bg').click(function(){
		$('#textwrapper').fadeOut(function(){
			$('#text .content').html("");
		});
	})
});

function getArticle(url){
	$('#textwrapper').show();
	$('#text .content').html(newanimation);
	$.post("ajax/getarticle/",{"url": url}, function(data, status) {
		if (data.status == "success"){
			var title = '<h2>'+data.response.title+'</h2>';
			var origlink = '<div class="origlink"><a href="'+url+'" target="_blank">'+url+'</a></div>'
			var text = data.response.content;
			text = '<p>'+text.replace(/\n/g, '<p>');
			$('#text .content').html(title+origlink+text);
		}
		else{
			var text = "<h2 class='error'>Ooops!</h2>Det gick inte att hämta innehållet i artikeln. <a href='"+url+"'>Klicka här för att gå till orginalsidan.</a>";
			$('#text .content').html();
		}
		if(mobile == true){
			scroll(0,0);
		}
		
	}, 'json');
}

function addTopic(event) {
	event.preventDefault();
	loadfull = loadcounter + 3;

	//Get the topic
	var topic = $('#newtopic').val();
	$('#newtopic').val("");
	$('#newtopic').blur();
	var nextlink = false;
	//Add the section
	nextlink = $('#menu a').first().attr('href').substr(1);
	var newsection = createSection(topic, nextlink);
	topicsSection.prepend(newsection.hide());
	newsection.show().addClass("show");
	var link = '<li><a href="#'+topic.replace(/\s/g, "")+'" class="internal">'+topic+'</a></li>';
	menuTopics.prepend(link);
	//Fix the other stuff
	$('html, body').animate({scrollTop:0}, 500);
	newsection.focus();
}


function getTopics(){
	topicsSection = $('<div class="topics"></div>');
	menuTopics = $('<ul class="nav"></ul>');
	var topicscount = 8;
	loadcounter = topicscount * 3;
	$.get("ajax/topics/"+topicscount, function(data, textStatus) {
		data = JSON.parse(data);
		getSections(data, topicsSection);
		fillMenu(data, menuTopics);
		loadfull = data.length;
	}, 'json');
	sections.append(topicsSection);
	menu.append(menuTopics);
}

function fillMenu(topics, parent){
	for (var i=0; i < topics.length; i++){
		console.log(topics[i].replace(/\s/g, ""));
		var link = '<li><a href="#'+topics[i].replace(/\s/g, "")+'" class="internal">'+topics[i]+'</a></li>';
		parent.append(link);
	}
}

function getSections(topics, parent){
	for (var i in topics){
		var nextid = parseInt(i)+1;
		var next = topics[nextid];
		if (typeof next == 'undefined'){
			next = true;
		}
		parent.append(createSection(topics[i], next).addClass("show"));
	}
	parent.addClass("show");
	$(".dot:first-child").addClass('active');
}

function createSection(topic, next) {
	var section = $('<div class="topic clearfix row"></div>');
	section.append('<a name="'+topic.replace(/\s/g, "")+'"class="anchor">');
	section.append('<h2 id="'+topic.replace(/\s/g, "")+'">'+topic+'</h2>');
	var news = $('<div class="news textlinks section span-one-third"></div>');
	var blogs = $('<div class="blogs textlinks section span-one-third"></div>');
	var tweets = $('<div class="tweets section span-one-third"></div>');
	news.append($('<span class="header news">Tidningar</span>'));
	blogs.append($('<span class="header blogs">Bloggar</span>'));
	tweets.append($('<span class="header tweets">Twitter</span>'));
	section.append(news);
	section.append(blogs);
	section.append(tweets);
	$('#dots').append(dot);
	$.get("ajax/news/"+escape(topic), function(data, textStatus) {
		data = JSON.parse(data);
		if (data[0] == null){
			news.append("<div>Inga nyhetsartiklar. Dags att tipsa gammelmedia kanske?</div>").addClass("show");
		}
		else{
			for(var i in data){
				news.append(newsDiv(data[i])).addClass("show");
			}
		}
		checkDone();
	}, 'json');
	
	$.get("ajax/blogs/"+escape(topic), function(data, textStatus) {
		data = JSON.parse(data);
		if (data[0] == null){
			blogs.append("<div>Inga bloggträffar. Ring Ajour!</div>").addClass("show");
		}
		else{
			for(var i in data){
				blogs.append(blogDiv(data[i])).addClass("show");
			}
		}
		checkDone();
	}, 'json');

	$.get("ajax/mixedtweets/"+escape(topic), function(data, textStatus) {
		data = JSON.parse(data);
		if (data['results'][0] == null){
			tweets.append("<div>Inga tweets. Uppenbarligen inget som intresserar tyckareliten?</div>").addClass("show");
		}
		else{
			for(var i in data['results']){
				console.log(data);
				tweets.append(tweetDiv(data['results'][i])).addClass("show");
			}
		}
		checkDone();
	}, 'json');
	return section;
}


function tweetDiv(data){
	return $('<div class="tweet item"><span class="item-main-content">'+data.text+'</span><br /><a href="http://www.twitter.com/#!/'+data.from_user+'/status/'+data.id_str+'" class="small" target="_blank">'+data.from_user_name+'</a></div>');
}
function newsDiv(data){
	return $('<div class="item"><span class="item-main-content"><a href="'+data.url+'" class="black">'+data.title[0]+'</a></span><br /><a href="'+data.url+'" class="small">'+data.title[1]+'</a></div>');;
}
function blogDiv(data){
	return $('<div class="item"><span class="item-main-content"><a href="'+data.url+'" class="black">'+data.title+'</a></span><br /><a href="'+data.url+'" class="small">'+data.name+'</a></div>');;
}

function checkDone(){
	loadcounter++;
	if (loadcounter >= loadfull){
		$('.newanimation').hide();
		$('.newsection').addClass("show");
		bullets = $("#dots .dot");
		slider = new Swipe(document.getElementById('sections'), {
			callback: function(e, pos) {
				var i = bullets.length;
				while (i--) {
					bullets[i].className = 'dot ';
				}
				bullets[pos].className = 'dot active';
			}
		});

	}
}






;(function($) {
	var interpolate = function (source, target, shift) { 
		return (source + (target - source) * shift); 
	};

	var easing = function (t) { 
	    return t<.5 ? 16*t*t*t*t*t : 1+16*(--t)*t*t*t*t; 
	};

	$.scroll = function(endY, duration, easingF) {
		endY = endY || ($.os.android ? 1 : 0);
		duration = duration || 200;
		(typeof easingF === 'function') && (easing = easingF);

		var startY = document.body.scrollTop,
			startT  = Date.now(),
			finishT = startT + duration;

		var animate = function() {
			var now = +(new Date()),
				shift = (now > finishT) ? 1 : (now - startT) / duration;

			window.scrollTo(0, interpolate(startY, endY, easing(shift)));

			(now > finishT) || setTimeout(animate, 15);
		};
	
		animate();
	};
}(Zepto));