<?php
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.0.0  2014/05/09                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<script language="JavaScript"><!--
	history.forward();
	function countdown(){
	location.href = "./login.php";
	}
--></script>
</head>
<body>
	<CENTER>
	<?php
		require_once("f_Button.php");
		echo "その様なページ移動は禁止されています。<br>5秒以内にページが移動しない場合は下記のボタンをクリックしてください。";
		echo "<form action='./login.php' method='post'>";
		echo "<input type='submit' class = 'button' name ='logout__button' value = 'ログイン画面に戻る' style = 'WIDTH : 140px; HEIGHT : 30px;' >";
		echo "</form>";
	?>
	</CENTER>
	<script type="text/javascript"><!--
		setInterval( "countdown()", 5000 );
	// --></script>
</body>
</html>
