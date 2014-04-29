<a href="<?=$base ?>" class="back">Back</a>
<h3>Manage Buckets</h3>
<table class="data" id="buckets">
	<thead>
		<tr>
			<th>Name</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($categories as $item): ?>
			<tr class="entry" data-id="<?=$item->id ?>">
				<td><?=$item->name ?>
			</tr>
		<? endforeach ?>
	</tbody>
</table>