<form method="get" action="<?=$base ?>search" class="search" id="search-live">
	<?=form::text('q', sq::request()->get('q'), array(
		'id' => 'query-live', 'placeholder' => 'Search&hellip;', 'autocomplete' => 'off', 'autofocus')) ?>
	<?=form::single('cat', 'categories', sq::request()->get('cat'), array(
		'id' => 'category-live', 'empty-label' => 'Choose Bucket&hellip;')) ?>
	<? if (sq::request()->get('id')): ?>
		<?=form::hidden('id', sq::request()->get('id')) ?>
	<? endif ?>
	<?=form::hidden('listId', $id) ?>
</form>
<?=$this->render('list', array('entries' => $entries, 'id' => $id)) ?>