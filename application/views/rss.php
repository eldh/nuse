<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<rss version="2.0">

	<channel>
		
		<title><?php echo $title ?></title>
		
		<link>http://www.nuse.se</link>
		<description>Svenska nyheter.</description>
		
		
		<?php foreach($items as $entry): ?>
			<item>
				<title><?php echo $entry['title'][0].' – '.$entry['title'][1] ?></title>
				<link><?php echo $entry['url'] ?></link>
				<description><?php echo $entry['teaser'] ?></description>
			</item>
	
	
		<?php endforeach; ?>
		
	</channel>
</rss> 