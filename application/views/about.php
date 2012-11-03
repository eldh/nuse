<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8" />
	<title>
		<?=$title?>
	</title>
	<meta name="description" content="Följ det svenska nyhetsflödet." />
	<meta name="keywords" content="nyheter, mashup, twitter, svenska" />
	
	<link href='http://fonts.googleapis.com/css?family=Kameron:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?=$base?>css/style.css" type="text/css" media="screen" />
	<link rel="apple-touch-icon-precomposed" href="<?=$base?>img/icon.png" />
	<LINK REL="shortcut icon" href="<?=$base?>img/favicon.ico"><?php
	if($mobile){ ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="<?=$base?>css/iphone.css" type="text/css" />
	<?php } ?>
	
</head>
<body>
	<div id="wrapper" class="container-fluid">
		<div id="about" class="content center">
			<h1>Nuse</h1>
			<p>Nuse är ett sätt att hålla sig uppdaterad om aktuella nyhetsämnen.</p>
			<p>Jag skapade Nuse för att jag tycker de webbtidningar som finns idag är föråldrade och jag ville experimentera med nya sätt att läsa nyheter på. </p>
			<p>Jag som ligger bakom detta experiment heter <a href="http://digitalmagi.se">Andreas Eldh</a>.</p>
			<p class="twitterlink"><a href="http://www.twitter.com/eldh">&nbsp;</a></p>
		</div>
	</div> <!-- #content, #wrapper -->
	<a href="<?=$base?>" id="logowide"></a>
</body>
</html>