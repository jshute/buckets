<form method="post" action="">
	<? if (url::get('id')): ?>
		<h3>Edit Bucket<span class="entry-name"> <?=$category->name ?></span></h3>
		<?=form::hidden('id', $category->id) ?>
	<? else: ?>
		<h3>Create New Bucket</h3>
	<? endif ?>
	<?=form::label('save[name]', 'Bucket Name') ?>
	<?=form::text('save[name]', $category->name) ?>
	<?=form::label('save[description]', 'Description') ?>
	<?=form::textarea('save[description]', $category->description) ?>
	<input type="submit" value="Save"/>
	<a class="cancel" href="<?=$base ?>buckets">Cancel</a>
</form>