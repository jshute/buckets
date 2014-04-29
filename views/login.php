<? if (isset($_SESSION['sq-login-attempts']) && $_SESSION['sq-login-attempts']):
	$this->message = 'Login not recognized. Try again or <a href="'.$base.'get-account">reset your account.</a>';
endif ?>
<h2>Login to Buckets</h2>
<? $this->test = 'test' ?>
<form method="post" action="<?=$base ?>auth/login">
	<?=form::label('username', 'Email') ?>
	<?=form::text('username', null, array('autofocus')) ?>
	<?=form::label('password', 'Password') ?>
	<?=form::password('password') ?>
	<input type="submit" value="Login"/>
</form>