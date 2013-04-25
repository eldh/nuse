var nuse = nuse || {};
nuse.loadcounter = 0;
nuse.loadfull = 200;
nuse.mobile = false;
nuse.slider = "";
nuse.newanimation = '<div class="newanimation animation">Laddar ämne...</div>';

$(document).ready(function(){
	nuse.sections = $('#sections');
	nuse.menu = $('#nav');
	nuse.topicsSection = $('<div class="topics"></div>');
	nuse.menuTopics = $('<ul class="nav"></ul>');
	var menuTopics;
	$('#addtopic').submit(nuse.addTopic);
	nuse.getTopics();

	$('#newtopic').focusout(function(){
		$(this).val('');
	});

	var footer = $('#footercontent');
	$('#closelink').click(function(){
		$('#textwrapper').fadeOut(function(){
			$('#text .content').html("");
		});
		scroll(0,0);
	});
	$(document).on('click', 'a.internal', function(event){
		event.preventDefault();
		var offset = $($(this).attr('href')).offset().top;
		$.scroll(offset-80, 1000);
	});
	// $(document).on('click', '.textlinks a', function(event){
	// 	event.preventDefault();
	// 	var url = $(this).attr('href');
	// 	nuse.getArticle(url);
	// 	return false;
	// });
	$('#textwrapper .bg').click(function(){
		$('#textwrapper').fadeOut(function(){
			$('#text .content').html("");
		});
	});
});

nuse.getArticle = function(url){
	$('#textwrapper').show();
	$('#text .content').html(nuse.newanimation);
	$.post("ajax/getarticle/",{"url": url}, function(data, status) {
		var text = "";
		if (data.status == "success"){
			var title = '<h2>'+data.response.title+'</h2>';
			var origlink = '<div class="origlink"><a href="'+url+'" target="_blank">'+url+'</a></div>';
			text = data.response.content;
			text = '<p>'+text.replace(/\n/g, '<p>');
			$('#text .content').html(title+origlink+text);
		}
		else{
			text = "<h2 class='error'>Ooops!</h2>Det gick inte att hämta innehållet i artikeln. <a href='"+url+"'>Klicka här för att gå till orginalsidan.</a>";
			$('#text .content').html();
		}
		if(nuse.mobile === true){
			$.scroll(0,100);
		}
	}, 'json');
};

nuse.addTopic = function(event) {
	event.preventDefault();
	nuse.loadfull = nuse.loadcounter + 3;
	//Get the topic
	var topic = $('#newtopic').val();
	$('#newtopic').val("");
	$('#newtopic').blur();
	//Add the section
	var nextlink = false;
	nextlink = nuse.menu.find('a').first().attr('href').substr(1);
	var newsection = nuse.createSection(topic, nextlink);
	nuse.topicsSection.prepend(newsection.hide());
	newsection.show().addClass("show");
	var link = '<li><a href="#'+topic.replace(/\s/g, "")+'" class="internal">'+topic+'</a></li>';
	nuse.menuTopics.prepend(link);
	//Fix the other stuff
	$.scroll(0, 1000);
	newsection.focus();
};


nuse.getTopics = function(){
	var topicscount = 5;
	nuse.loadcounter = topicscount * 3;
	$.get("ajax/topics/"+topicscount, function(data, textStatus) {
		nuse.getSections(data, nuse.topicsSection);
		nuse.fillMenu(data, nuse.menuTopics);
		nuse.loadfull = data.length;
	}, 'json');
	nuse.sections.append(nuse.topicsSection);
	nuse.menu.prepend(nuse.menuTopics);
};

nuse.fillMenu = function(topics, parent){
	for (var i=0; i < topics.length; i++){
		console.log(topics[i].replace(/\s/g, ""));
		var link = '<li><a href="#'+topics[i].replace(/\s/g, "")+'" class="internal">'+topics[i]+'</a></li>';
		parent.append(link);
	}
};

nuse.getSections = function(topics, parent){
	for (var i in topics){
		var nextid = parseInt(i, 10)+1;
		var next = topics[nextid];
		if (typeof next == 'undefined'){
			next = true;
		}
		parent.append(nuse.createSection(topics[i], next).addClass("show"));
	}
	parent.addClass("show");
};

nuse.createSection = function(topic, next) {
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
	$.get("ajax/news/"+escape(topic), function(data, textStatus) {
		if (data[0] === null){
			news.append("<div>Inga nyhetsartiklar. Dags att tipsa gammelmedia kanske?</div>").addClass("show");
		}
		else{
			for(var i in data){
				news.append(nuse.newsDiv(data[i])).addClass("show");
			}
		}
		nuse.checkDone();
	}, 'json');
	
	$.get("ajax/blogs/"+escape(topic), function(data, textStatus) {
		if (data[0] === null){
			blogs.append("<div>Inga bloggträffar. Ring Ajour!</div>").addClass("show");
		}
		else{
			for(var i in data){
				blogs.append(nuse.blogDiv(data[i])).addClass("show");
			}
		}
		nuse.checkDone();
	}, 'json');

	$.get("ajax/mixedtweets/"+escape(topic), function(data, textStatus) {
		console.log(data);
		if (data.length < 1){
			tweets.append("<div>Inga tweets. Uppenbarligen inget som intresserar tyckareliten?</div>").addClass("show");
		}
		else{
			for(var i in data){
				tweets.append(nuse.tweetDiv(data[i])).addClass("show");
			}
		}
		nuse.checkDone();
	}, 'json');
	return section;
};


nuse.tweetDiv = function(data){
	return $('<div class="tweet item"><span class="item-main-content">'+data.text+'</span><br /><a href="http://www.twitter.com/#!/'+data.user.screen_name+'/status/'+data.id_str+'" class="small" target="_blank">'+data.user.name+'</a></div>');
};
nuse.newsDiv = function(data){
	return $('<div class="item"><span class="item-main-content"><a href="'+data.url+'" class="black">'+data.title[0]+'</a></span><br /><a href="'+data.url+'" class="small">'+data.title[1]+'</a></div>');
};
nuse.blogDiv = function(data){
	return $('<div class="item"><span class="item-main-content"><a href="'+data.url+'" class="black">'+data.title+'</a></span><br /><a href="'+data.url+'" class="small">'+data.name+'</a></div>');
};

nuse.checkDone = function(){
	nuse.loadcounter++;
	if (nuse.loadcounter >= nuse.loadfull){
		$('.newanimation').hide();
		$('.newsection').addClass("show");
		if(nuse.mobile){
			nuse.initializeSlider();
		}
	}
};

nuse.initializeSlider = function(){
	nuse.slider = new Swipe(document.getElementById('sections'), {
		callback: function(e, pos) {
			var i = bullets.length;
			while (i--) {
				bullets[i].className = 'dot ';
			}
			bullets[pos].className = 'dot active';
		}
	});
};
nuse.isTouch = function(){
	return 'ontouchstart' in document.documentElement;
};

(function($) {
	var interpolate = function (source, target, shift) {
		return (source + (target - source) * shift);
	};

	var easing = function (t) {
		return t<0.5 ? 16*t*t*t*t*t : 1+16*(--t)*t*t*t*t;
	};



	$.scroll = function(endY, duration, easingF) {
		duration = duration || 200;
		if (typeof easingF === 'function') easing = easingF;

		var startY = document.body.scrollTop,
		startT  = Date.now(),
		finishT = startT + duration;

		var animate = function() {
			var now = +(new Date()),
			shift = (now > finishT) ? 1 : (now - startT) / duration;

			window.scrollTo(0, interpolate(startY, endY, easing(shift)));

			if (now <= finishT) setTimeout(animate, 15);
		};

		animate();
	};
}($));