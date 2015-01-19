<?=form::open($category) ?>
	<? if (url::get('id')): ?>
		<h3>Edit Bucket<span class="entry-name"> <?=$category->name ?></span></h3>
	<? else: ?>
		<h3>Create New Bucket</h3>
	<? endif ?>
	<?=form::label('name', 'Bucket Name') ?>
	<?=form::text('name') ?>
	<?=form::label('description', 'Description') ?>
	<?=form::textarea('description') ?>
	<input type="submit" value="Save"/>
	<a class="cancel" href="<?=$base ?>buckets">Cancel</a>
<?=form::close() ?>