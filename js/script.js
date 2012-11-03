var loadcounter = 0;
var loadfull = 200;
var mobile = false;
var slider;
var menu;
var newanimation = '<div class="newanimation animation">Loading...</div>';

$(document).ready(function(){
	sections = $('#sections');
	getTopics();
	menu = new Menu($('#menu'));
	
	if (mobile == true){
		var footer = $('#footercontent');
		$('#closelink').click(function(){
			$('#textwrapper').fadeOut(function(){
				$('#text .content').html("");
			});
			scroll(0,0);
		});

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
	});
	if (mobile == true){
    	slider = new Swipe(document.getElementById('menu'),document.getElementById('menuhandle'), {
    		callback: function(e) {
/*     			console.log('hey'); */
    		}
    	});
	}
	

});

function getArticle(url){
	$('#textwrapper').fadeIn();
	$('#text .content').html(newanimation);
	$.post("ajax/getarticle/",{"url": url}, function(data, status) {
/* 		console.log(data); */
		if (data.status == "success"){
			var title = '<h2>'+data.response.title+'</h2>';
			var origlink = '<div class="origlink"><a href="'+url+'" target="_blank">'+url+'</a></div>'
			var text = data.response.content;
			text = '<p>'+text.replace(/\n/g, '<p>');
			$('#text .content').html(title+origlink+text);
		}
		else{
			var text = "<h2 class='error'>Ooops!</h2>Det gick inte att hämta innehållet i artikeln. <a href='"+url+"' target='_blank'>Klicka här för att gå till orginalsidan.</a>";
			$('#text .content').html();
		}
		if(mobile == true){
			scroll(0,0);
		}
		
	}, 'json');
}

function getTopics(){
	topicsSection = $('<div class="topics"></div>');
	var topicscount = 6;
	loadcounter = topicscount * 3;
	$.get("ajax/topics/"+topicscount, function(data, textStatus) {
		getSections(data, topicsSection);
		menu.addTopics(data);
		loadfull = data.length;
	}, 'json');
	sections.append(topicsSection);

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
	if (next!=true){
		section.append($('<div class="next"><a href="#'+next.replace(/\s/g, "")+'" class="internal">»</a></div>'));
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
/* 				console.log(data); */
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
	}
}