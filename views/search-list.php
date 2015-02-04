<form method="get" action="<?=$base ?>search" class="search" id="search-live">
	<?=form::text('q', url::get('q'), array(
		'id' => 'query-live', 'placeholder' => 'Search&hellip;', 'autocomplete' => 'off', 'autofocus')) ?>
	<?=form::single('cat', 'categories', url::get('cat'), array(
		'id' => 'category-live', 'empty-label' => 'Choose Bucket&hellip;')) ?>
	<? if (url::get('id')): ?>
		<?=form::hidden('id', url::get('id')) ?>
	<? endif ?>
	<?=form::hidden('listId', $id) ?>
</form>
<?=$this->render('list', array('entries' => $entries, 'id' => $id)) ?>