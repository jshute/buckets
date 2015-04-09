<? $this->message = form::flash() ?>
<h2>Login to Buckets</h2>
<form method="post" action="<?=$base ?>auth/login">
	<?=form::label('username', 'Email') ?>
	<?=form::text('username', null, array('autofocus')) ?>
	<?=form::label('password', 'Password') ?>
	<?=form::password('password') ?>
	<input type="submit" value="Login"/>
</form>