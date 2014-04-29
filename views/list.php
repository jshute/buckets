<div id="<?=$id ?>">
	<div id="load-list">
		<? if (count($entries)): ?>
			<table class="data" id="list-table">
				<thead>
					<tr>
						<th>Name</th>
						<th>Bucket</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($entries as $item): ?>
						<tr class="entry" data-id="<?=$item->id ?>">
							<td>
								<? if ($id == 'edit'): ?>
<?

$checked = '';

$relations = sq::model('relations')
	->where(array('related_to' => $item->id, 'related_from' => url::request('id')), 'AND')
	->read();

if (count($relations)) {
	$checked = 'checked';
} else {
	$relations = sq::model('relations')
		->where(array('related_from' => $item->id, 'related_to' => url::request('id')), 'AND')
		->read();

	if (count($relations)) {
		$checked = 'checked';
	}
}

?>
									<input type="checkbox" <?=$checked ?> data-current-id="<?=url::request('id') ?>" class="row-toggle" name="<?=$item->id ?>"/>
								<? endif ?>
								<?=$item->name ?>
							</td>
							<td class="secondary">
								<? if (isset($item->categories->name)): ?>
									<?=$item->categories->name ?>
								<? else: ?>
									Not in a Bucket
								<? endif ?>
							</td>
						</tr>
					<? endforeach ?>
				</tbody>
			</table>
		<? else: ?>
			<span class="empty-view">Nothing Found :(</span>
		<? endif ?>
	</div>
</div>