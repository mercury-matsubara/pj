<?php
	require_once ("f_mail.php");
	$mail = json_decode ( $_POST['mail'], true );
	$add = $mail['add'];
	$title = $mail['title'];
	$sentence = $mail['sentence'];
	$error = "";
	for($i = 0 ; $i < count($add) ; $i++)
	{
		if($add[$i] != "")
		{
			
			$title[$i] = mb_convert_encoding($title[$i], "SJIS", "UTF-8");
			$sentence[$i] = mb_convert_encoding($sentence[$i], "SJIS", "UTF-8");
			$error = sendmail($add[$i],$title[$i],$sentence[$i]);
			if($error != "")
			{
				$error = "";
			}
		}
	}
?>
