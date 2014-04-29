<? sq::load('phpMarkdown') ?>
<a class="back" href="<?=$base ?>">Back</a>
<h3><?=$entry->name ?></h3>
<span class="secondary">Bucket: 
	<? if (isset($entry->categories->name)): ?>
		<?=$entry->categories->name ?>
	<? else: ?>
		Not in a Bucket
	<? endif ?>
</span>
<p><?=markdown($entry->description) ?></p>

<h2>Dependencies of <?=$entry->name ?></h2>
<?=$this->render('search-list', array('entries' => $related, 'id' => 'list')) ?>
<? self::$title = $entry->name.' Dependencies | Buckets' ?>