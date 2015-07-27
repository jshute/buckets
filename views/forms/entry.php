<? if (sq::request()->any('id')): ?>
	<h3>Edit<span class="entry-name"> <?=$entry->name ?></span></h3>
<? else: ?>
	<h3>Create New Entry</h3>
<? endif ?>
<form method="post" action="">
	<? $cancelLink = $base ?>
	<? if (sq::request()->any('id')):
		echo form::hidden('id', sq::request()->any('id'));
		$cancelLink = $base.'details?id='.sq::request()->any('id');
	endif ?>
	<?=form::label('save[name]', 'Name') ?>
	<?=form::text('save[name]', $entry->name) ?>
	<?=form::label('save[categories_id]', 'Bucket') ?>
	<?=form::single('save[categories_id]', 'categories', $entry->categories_id) ?>
	<?=form::label('save[description]', 'Description') ?>
	<?=form::blurb('save[description]', $entry->description) ?>

	<input type="submit" value="Save"/>
	<a class="cancel" href="<?=$cancelLink ?>">Cancel</a>
</form>
<div class="affected">
<?

$title = '&hellip;';
if ($entry->name):
	$title = ' '.$entry->name;
endif;

?>
	<h2>Dependencies of<span class="entry-name"><?=$title ?></span></h2>
	<?=$this->render('search-list', array('id' => 'edit', 'entries' => $entries)) ?>
	<? if (!sq::request()->any('id')): ?>
		<div class="disabled"></div>
	<? endif ?>
</div>