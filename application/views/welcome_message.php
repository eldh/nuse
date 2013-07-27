<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8" />
	<title>
		<?=$title?>
	</title>
	<meta name="description" content="Följ det svenska nyhetsflödet." />
	<meta name="keywords" content="nyheter, mashup, twitter, svenska, sverige" />
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link href='http://fonts.googleapis.com/css?family=Lato:100,300,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?=$base?>css/nuse.css" type="text/css" media="screen" />
	<link rel="apple-touch-icon-precomposed" href="<?=$base?>img/icon.png" />
	<link rel="shortcut icon" href="<?=$base?>img/favicon.ico">
	<?php if($mobile){ ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<?php } ?>
	
</head>
<body class="js">
	<header>
		<div class="gw content content-center">
			<div class="logo header__logo">nuse</div>
			<nav id="nav" class="row">
				<form id="add-topic-form" class="addtopic"><span class="addtopic__button" id="add-topic-button">+</span><input type="text" id="add-topic-input" placeholder="Lägg till ämne" class="addtopic__input" /></form>
			</nav>
		</div>
	</header>
	<div id="wrapper">
		<div id="sections" class="content-center"></div>
	</div>
	<footer id="footer">
		<div class="gw content-center">
			<div class="g one-whole" id="footercontent">
				<div class="logo"></div>
				<p>&nbsp;</p>
				<p>Nuse hittar vad som skrivits om intressanta nyhetsämnen. Tjänsten har skapats av <a href="http://www.eldh.co/">Andreas Eldh</a>.</p>
			</div>
		</div>
	</footer>

	<script src="<?=$base?>js/html5.js" type="text/javascript"></script>
	<script src="<?=$base?>js/jquery.min.js" type="text/javascript"></script>
	<script src="<?=$base?>js/script.js" type="text/javascript"></script>
	<script src="<?=$base?>js/responsive-nav.js" type="text/javascript"></script>
	    <script>
      var navigation = responsiveNav("#nav", {
        animate: true,        // Boolean: Use CSS3 transitions, true or false
        transition: 400,      // Integer: Speed of the transition, in milliseconds
        label: "",            // String: Label for the navigation toggle
        insert: "after",      // String: Insert the toggle before or after the navigation
        customToggle: "",     // Selector: Specify the ID of a custom toggle
        openPos: "relative",  // String: Position of the opened nav, relative or static
        jsClass: "js",        // String: 'JS enabled' class which is added to <html> el
        init: function(){},   // Function: Init callback
        open: function(){},   // Function: Open callback
        close: function(){}   // Function: Close callback
      });
    </script>
<div id="textwrapper">
	<div class="bg"></div>
	<div id="text">
		<div id="closelink"></div>
		<div class="content"></div>
	</div>
</div>
</body>
</html>