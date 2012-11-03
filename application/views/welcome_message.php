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
	<?php if(!$mobile){ ?>
	<div id="overlay">
		<img src='<?=$base?>img/logo.png' id="logo" />
		<div class="animation clearfix">
			Loading...
    	</div>
	</div>
	<?php } ?>
	<nav id="menu">
		
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
    <a href="<?=$base?>about" id="logowide"></a>

	<script src=<?=$base?>js/html5.js type="text/javascript"></script>
	<script src=<?=$base?>js/jquery.js type="text/javascript"></script>
	<script src=<?=$base?>js/jquery.tappable.js type="text/javascript"></script>
	<script src=<?=$base?>js/menu.js type="text/javascript"></script>
	<script src=<?=$base?>js/script.js type="text/javascript"></script>
	<?php if($mobile){ ?>
		<script type="text/javascript">
			mobile = true;
		</script>
	<?php } ?>
	<div id="textwrapper">
		<div class="bg"></div>
		<div id="text">
			<?php if($mobile){ ?>
				<div id="closelinkwrapper"><span id="closelink">&#x2715;</span></div>
			<?php } ?>
			<div class="content"></div>
		</div>
	</div>
	<div id="debuginfo"></div>
</body>
</html>