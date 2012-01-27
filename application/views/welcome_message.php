<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8" />
	<title>
		<?=$title?>
	</title>
	<meta name="description" content="Följ det svenska nyhetsflödet." />
	<meta name="keywords" content="nyheter, mashup, twitter, svenska" />

	<link rel="stylesheet" href="<?=$base?>css/style.css" type="text/css" media="screen" />
	
	<script src=<?=$base?>js/jquery.js type="text/javascript"></script>
	<script src=<?=$base?>js/script.js type="text/javascript"></script>
	<script src=<?=$base?>js/html5.js type="text/javascript"></script>
</head>
<body>
	<div id="wrapper"><div id="content">
		<header id="top">
			<h2>nuse</h2>
				<input type="text" id="newtopic" />
				<input type="submit" value="Lägg till ämne" id="addtopic" />
		</header>
		<nav id="menu"></nav>
		<div id="workspace">
		
		</div>

	</div></div> <!-- #content, #wrapper -->
	<footer id="footer">
		<div class="center">
			<div class="content full">
			</div>
		</div>
	</footer>
</body>
</html>