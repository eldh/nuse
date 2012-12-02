<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8" />
	<title>
		<?=$title?>
	</title>
	<meta name="description" content="Följ det svenska nyhetsflödet." />
	<meta name="keywords" content="nyheter, mashup, twitter, svenska, sverige" />
	
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?=$base?>css/style.css" type="text/css" media="screen" />
	<link rel="apple-touch-icon-precomposed" href="<?=$base?>img/icon.png" />
	<link rel="shortcut icon" href="<?=$base?>img/favicon.ico">
	<?php if($mobile){ ?>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="<?=$base?>css/iphone.css" type="text/css" />
	<?php } ?>
	
</head>
<body>
	<nav id="menu">
		<form id="addtopic"><input type="text" id="newtopic" placeholder="+" /></form>
	</nav>
	<div id="wrapper" class="container-fluid">
		<div id="sections" class="content center">
		</div>
	</div> <!-- #content, #wrapper -->
	<footer id="footer">
		<div class="center">
			<div class="content full" id="footercontent">
				<div class="logo"></div>
				<p>&nbsp;</p>
				<p><a href="http://www.digitalmagi.se/nuse">Vad är nuse?</a></p>
			</div>
		</div>
	</footer>

	<script src=<?=$base?>js/html5.js type="text/javascript"></script>
	<script src=<?=$base?>js/zepto.min.js type="text/javascript"></script>
	<script src=<?=$base?>js/script.js type="text/javascript"></script>
	<?php if($mobile){ ?>
		<script src=<?=$base?>js/swipe.js type="text/javascript"></script>
		<script type="text/javascript">
		mobile = true;
		</script>
	<?php } ?>
	<div id="textwrapper">
		<div class="bg"></div>
		<div id="text">
			<div id="closelink"></div>
			<div class="content"></div>
		</div>
	</div>
</body>
</html>