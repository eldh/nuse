$(document).ready(function(){
	workspace = $('#workspace');
	menu = $('#menu');
	addTopicForm = $('#addtopic');
	addTopicForm.click(addTopic);
	getTopics();
	
});

function addTopic() {
	var topic = $('#newtopic');
	console.log(topic);
	topicsSection.prepend(createSection(topic.val()));
	topic.val("");
}


function getTopics(){
	topicsSection = $('<section class="topics"></section>');
	menuTopics = $('<section class="topics"></section>');
	menuTopics.append('<h1>Aktuella ämnen</h1>')
	$.get("ajax/topics", function(data, textStatus) {
		getSections(data, topicsSection);
		fillMenu(data, menuTopics);
	}, 'json');
	workspace.append(topicsSection);
	menu.append(menuTopics);
}

function fillMenu(topics, parent){
	for (var i in topics){
		var link = '<a href="#'+topics[i]+'">'+topics[i]+'</a><br />';
		parent.append(link);
	}
}

function getSections(topics, parent){
	for (var i in topics){
		parent.append(createSection(topics[i]));
	}
}

function createSection(topic) {
	var section = $('<section class="topic clearfix"></section>');
	section.append('<h2>'+topic+'</h2>')
	var news = $('<div class="news section"></div>');
	var blogs = $('<div class="blogs section"></div>');
	var tweets = $('<div class="tweets section"></div>');
	section.append(news);
	section.append(blogs);
	section.append(tweets);
	
	$.get("ajax/news/"+escape(topic), function(data, textStatus) {
		if (data[0] == null){
			news.append("<div>Inga nyhetsartiklar. Dags att tipsa gammelmedia kanske?</div>");
		}
		else{
			for(var i in data){
				news.append(newsDiv(data[i]));
			}
		}
		
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
			var refreshLink = '<a href="#" onClick=nextPage("'+data['next_page']+'")>Nästa sida</a>';
			tweets.append(refreshLink);
		}
	}, 'json');
	return section;
}

function nextPage(url){
	$.post("ajax/nextpage/",{"url": url}, function(data, textStatus) {
		console.log(data);
			for(var i in data['results']){
				console.log(data);
				tweets.append(tweetDiv(data['results'][i]));
			}
		
    }, 'json');

}

function tweetDiv(data){
    return $('<div class="tweet item">'+data.text+'<br /><a href="http://www.twitter.com/#!/'+data.from_user+'/status/'+data.id_str+'" class="small">'+data.from_user_name+'</a></div>');
}
function newsDiv(data){
    return $('<div class="item"><a href="'+data.url+'">'+data.title[0]+'</a><br /><a href="'+data.url+'" class="small">'+data.title[1]+'</a></div>');;
}
function blogDiv(data){
    return $('<div class="item"><a href="'+data.url+'">'+data.title+'</a><br /><a href="'+data.url+'" class="small">'+data.name+'</a></div>');;
}

