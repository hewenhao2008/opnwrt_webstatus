<?php
require_once 'functions.php';

if( session_id() == null ) { session_start(); }

if (isset($_GET['logout'])) {
	logger('logout');
	setcookie("authentication", null, time()-1 , "/" , ".".preg_replace('/^www\./','', $host));
	session_destroy();
	$loggedin = false;
	header("Location: ". $_SERVER['HTTP_REFERER']);
	return;
}

if (!empty($_POST)){
	$username = $_POST["username"];
	$password = sha1($_POST["password"]);
	$user = checkuser($username, $password);
	if ($user[0] > 0 && $user[0] != null && $user[0] != 0 ) {
		$_SESSION['authenticated'] = true;
		$_SESSION['userid'] = $user[0];
		$logininfo = array($user[0],$username,$password);
		$loggedin = true;
		logger('login');
		if (isset($_POST['remember'])) { setcookie("authentication", serialize($logininfo), time()+60*60*24*30 , "/" , ".".preg_replace('/^www\./','', $host)); }
		//header("Location: ". $_SERVER['HTTP_REFERER']);
	}
	else {
		$error = 'Incorrect username or password';
		logger('wrong password');
		$loggedin = false ;
	}
}

$loggedin = checklogin();

?>