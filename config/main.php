<?php

// Config settings
return array(
	'debug' => true,
	
	'sql' => array(
		'dbname'   => 'app',
		'username' => 'root',
		'password' => 'root',
		'host'     => 'localhost'
	),
	
	'auth' => array(
		'login-failed-message' => 'Login not recognized. Try again or <a href="'.sq::base().'get-account">reset your account.</a>'
	),
	
	'route' => array(
		'definitions' => array(
			'buckets/{action?}' => array('controller' => 'buckets'),
			'auth/{action}' => array('controller' => 'auth'),
			'{action}/{id?}' => array('controller' => 'site'),
			'{controller?}/{action?}/{id?}'
		)
	)
);

?>