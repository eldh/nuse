var loadcounter = 0;
var loadfull = 200;
var mobile = false;
var slider;
var newanimation = '<div class="newanimation animation">Loading...</div>';
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
	
	if (mobile == true){
		var footer = $('#footercontent');
		menu.append('<h1>nuse</h1>');
		menu.append($("<div id='dots'></div>"));
		$('#closelink').click(function(){
			$('#textwrapper').fadeOut(function(){
				$('#text .content').html("");
			});
			scroll(0,0);
		})
	}
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
	$('#textwrapper').fadeIn();
	$('#text .content').html(newanimation);
	$.post("ajax/getarticle/",{"url": url}, function(data, status) {
		console.log(data);
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
	if (mobile != true){//Add link to the menu
		nextlink = $('#menu a').first().attr('href').substr(1);
	}
	var newsection = createSection(topic, nextlink);
	newsection.hide();
	newsection.addClass("newsection");
	topicsSection.prepend(newsection);
	
	if (mobile != true){//Add link to the menu
		topicsSection.prepend(newanimation);
		var link = '<li><a href="#'+topic.replace(/\s/g, "")+'" class="internal">'+topic+'</a></li>';
		menuTopics.prepend(link);
	}
	//Fix the other stuff
	$('.topic').css('min-height', window.innerHeight);
	$('html, body').animate({scrollTop:0}, 500);
	newsection.focus();
}


function getTopics(){
	topicsSection = $('<div class="topics"></div>');
	menuTopics = $('<ul class="nav"></ul>');
	var topicscount = 6;
	loadcounter = topicscount * 3;
	$.get("ajax/topics/"+topicscount, function(data, textStatus) {
		getSections(data, topicsSection);
		fillMenu(data, menuTopics);
		loadfull = data.length;
	}, 'json');
	sections.append(topicsSection);
	if (mobile != true){
		menu.append(menuTopics);
	}

}

function fillMenu(topics, parent){
	for (var i=0; i < topics.length; i++){
	console.log(topics[i].replace(/\s/g, ""));
		var link = '<li><a href="#'+topics[i].replace(/\s/g, "")+'" class="internal">'+topics[i]+'</a></li>';
		parent.append(link);
	}
	
	//Now we can do stuff with the elements that have been loaded
	$('a.internal').live('click',function(event){
		event.preventDefault();
		var offset = $($(this).attr('href')).offset().top;
		$('html, body').animate({scrollTop:offset-50}, 500);
	});
	$('.up a').live('click',function(event){
		event.preventDefault();
		$('html, body').animate({scrollTop:0}, 500);
	});
	$('.topic').css('min-height', window.innerHeight);

}

function getSections(topics, parent){
	for (var i in topics){
		var nextid = parseInt(i)+1;
		var next = topics[nextid];
		if (typeof next == 'undefined'){
			next = true;
		}
		parent.append(createSection(topics[i], next));
	}
	if (mobile == true){
		$(".dot:first-child").addClass('active');
	}
}

function createSection(topic, next) {
	var section = $('<div class="topic clearfix row"></div>');
	section.append('<a name="'+topic.replace(/\s/g, "")+'"class="anchor">');
	section.append('<h2 id="'+topic.replace(/\s/g, "")+'"><span>'+topic+'</span></h2>')
	var news = $('<div class="news textlinks section span-one-third"></div>');
	var blogs = $('<div class="blogs textlinks section span-one-third"></div>');
	var tweets = $('<div class="tweets section span-one-third"></div>');
	news.append($('<span class="headericon news"></span>'));
	blogs.append($('<span class="headericon blogs"></span>'));
	tweets.append($('<span class="headericon tweets"></span>'));
	section.append(news);
	section.append(blogs);
	section.append(tweets);
	if ((next!=true) && (mobile != true)){
		section.append($('<div class="next"><a href="#'+next.replace(/\s/g, "")+'" class="internal">»</a></div>'));
	}
	else if (mobile != true) {
		section.append($('<div class="next up"><a href="#1" >«</a></div>'));
	}
	else{ //if mobile
		$('#dots').append(dot);
	}
	$.get("ajax/news/"+escape(topic), function(data, textStatus) {
		if (data[0] == null){
			news.append("<div>Inga nyhetsartiklar. Dags att tipsa gammelmedia kanske?</div>");
		}
		else{
			for(var i in data){
				news.append(newsDiv(data[i]));
			}
		}
		checkDone();
	}, 'json');
	
	$.get("ajax/blogs/"+escape(topic), function(data, textStatus) {
		if (data[0] == null){
			blogs.append("<div>Inga bloggträffar. Ring Ajour!</div>");
		}
		else{
			for(var i in data){
				blogs.append(blogDiv(data[i]));
			}
		}
		checkDone();
	}, 'json');

	$.get("ajax/mixedtweets/"+escape(topic), function(data, textStatus) {
		if (data['results'][0] == null){
			tweets.append("<div>Inga tweets. Uppenbarligen inget som intresserar tyckareliten?</div>");
		}
		else{
			for(var i in data['results']){
				console.log(data);
				tweets.append(tweetDiv(data['results'][i]));
			}
		}
		checkDone();
	}, 'json');
	return section;
}


function tweetDiv(data){
    return $('<div class="tweet item"><span>'+data.text+'</span><br /><a href="http://www.twitter.com/#!/'+data.from_user+'/status/'+data.id_str+'" class="small" target="_blank">'+data.from_user_name+'</a></div>');
}
function newsDiv(data){
    return $('<div class="item"><span><a href="'+data.url+'" class="black">'+data.title[0]+'</a></span><br /><a href="'+data.url+'" class="small">'+data.title[1]+'</a></div>');;
}
function blogDiv(data){
    return $('<div class="item"><span><a href="'+data.url+'" class="black">'+data.title+'</a></span><br /><a href="'+data.url+'" class="small">'+data.name+'</a></div>');;
}



function checkDone(){
	loadcounter++;
	if (loadcounter >= loadfull){
		$('#overlay').fadeOut('slow');
		$('.newanimation').hide();
		$('.newsection').fadeIn('slow');
		if (mobile == true){
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
}