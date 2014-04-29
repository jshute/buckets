<? $this->layout = 'layouts/main' ?>
<section class="content" id="content">
	<? if (isset($message) && $message): ?>
		<div class="alert"><?=$message ?></div>
	<? endif ?>
	<?=$content ?>
</section>
<section class="sidebar">
	<? if (isset($actions) && is_array($actions)): ?>
		<ul class="listing">
			<? foreach($actions as $name => $link): ?>
				<? if (is_numeric($name)): ?>
					<li class="title"><h4><?=$link ?></h4></li>
				<? else: ?>
					<li><a href="<?=$base.$link ?>"><?=$name ?></a></li>
				<? endif ?>
			<? endforeach ?>
		</ul>
	<? endif ?>
	<? if (isset($sidebar)): ?>
		<?=$sidebar ?>
	<? endif ?>
</section>