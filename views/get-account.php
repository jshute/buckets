<form method="post" action="<?=$base ?>get-account">
	<h2>Request Account</h2>
	<p>Email must be a deckers.com email address. An activation email will be sent to the address.</p>
	<p>Note: If you already have an account this form will update it with new information.</p>
	<?=form::label('email', 'Email') ?>
	<?=form::text('email', null, array('autofocus')) ?>
	<?=form::label('password', 'Password') ?>
	<?=form::password('password') ?>
	<?=form::label('first', 'First Name') ?>
	<?=form::text('first') ?>
	<?=form::label('last', 'Last Name') ?>
	<?=form::text('last') ?>
	<input type="submit" value="Send Email"/>
</form>