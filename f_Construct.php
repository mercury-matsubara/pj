<?php
function start(){
	if ((isset($_SESSION['userName']) == false) || (isset($_SESSION['pre_post'] ) == false))
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").
							$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
		exit();
	}
}
function startJump($post){
	$judge = false;
	if (isset($_SESSION['userName']) == false || count($post) == 0)
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").
							$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
		exit();
	}
}

?>
