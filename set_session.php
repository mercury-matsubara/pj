<?php
	session_start();
	if(isset($_POST['data']))
	{
		$_SESSION['list']['checkdata'] = $_POST['data'];
		$_SESSION['pre_post'] = $_SESSION['post'];
		unset($_SESSION['post']);
	}
?>