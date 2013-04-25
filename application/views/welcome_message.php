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
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="<?=$base?>css/style.css" type="text/css" media="screen" />
	<link rel="apple-touch-icon-precomposed" href="<?=$base?>img/icon.png" />
	<link rel="shortcut icon" href="<?=$base?>img/favicon.ico">
	<?php if($mobile){ ?>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<?php } ?>
	
</head>
<body class="js">
	<header>
		<div class="content content-center">
			<nav id="nav" class="row">

				<form id="addtopic"><input type="text" id="newtopic" placeholder="Lägg till ämne" /></form>
			</nav>	
		</div>
	</header>
	<div id="wrapper" class="container-fluid">
		<div id="sections" class="content content-center">
		</div>
	</div> <!-- #content, #wrapper -->
	<footer id="footer">
		<div class="content content-center">
			<div class="row" id="footercontent">
				<div class="logo"></div>
				<p>&nbsp;</p>
				<p>Nuse har skapats av <a href="http://www.digitalmagi.se/">Andreas Eldh</a></p>
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