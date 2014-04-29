<? self::style('//fonts.googleapis.com/css?family=Dosis') ?>
<? self::style('//fonts.googleapis.com/css?family=Open+Sans') ?>
<? self::style($base.'css/stylesheet.css') ?>
<? self::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js') ?>
<? self::script($base.'js/jquery.tablesorter.min.js') ?>
<? self::script($base.'js/scripts.js') ?>
<? self::$foot = '<script>var base = "'.$base.'";</script>' ?>
<? self::$title = 'Buckets | The Deckers eCommerce dependency manager.' ?>
<? if (isset($entry) && url::get('action') == 'details'): ?>
	<? self::$title = $entry->name.' Dependencies | Buckets' ?>
<? endif ?>
<header>
	<a href="<?=$base?>">
		<h1>Buckets</h1>
		<p>The Deckers eCommerce dependency manager.</p>
	</a>
	<img class="logo" height="42" width="150" src="<?=$base ?>images/deckers_logo.png" alt="Deckers Outdoor Corporation"/>
</header>
<?=$content ?>