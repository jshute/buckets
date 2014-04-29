<h3>Confirm Delete</h3>
<form method="post" action="">
	<p>Are you sure you want to delete <?=$entry->name ?>?</p>
	<input type="submit" value="Yes, Delete It"/>
	<input type="hidden" name="id" value="<?=$entry->id ?>"/>
	<a class="cancel" href="<?=$base ?>">Cancel</a>
</form>