<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	require_once("f_DB.php");
	require_once("f_Button.php");
	start();
	$judge = false;
	if(isset($_SESSION['post']['true']))
	{
		if($_SESSION['post']['true'])
		{
			$judge = true;
			$_SESSION['post'] = $_SESSION['pre_post'];
			$_SESSION['pre_post'] = null;
		}
	}
	if($judge)
	{
		$filename = $_SESSION['filename'];
		echo "<left>";
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton();
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		echo "</left>";
		if(countLoginUser())
		{
			deleteUser();
			$userName = $_SESSION['result_array']['LUSERNAME'];
			$password = $_SESSION['result_array']['LUSERPASS'];
			$_SESSION['result_array'] = null;
			$pass = "";
			$passLength = 0;
			$passLength = mb_strlen( $password ,"UTF-8");
			for ($i = 0; $i < $passLength ; $i++)
			{
				$pass .="●";
			}
			$password = null;
			echo "<center>";
			echo "<a class = 'title'>ユーザー削除完了</a>";
			echo "<br><br>";
			echo "<table><tr><td id = 'item'>ユーザーID</td>";
			echo '<td>';
			echo $userName;
			echo '</td>';
			echo "</tr><tr><td id = 'item'>パスワード</td>";
			echo '<td>';
			echo $pass;
			echo '</td>';
			echo "</tr></table>";
			echo "<br>";
			echo '<form action="listUserJump.php" method="post">';
			echo "<input type='submit' name='cancel' id='cancel' value = '一覧に戻る' class='free'>";
			echo "</form>";
			echo "</center>";
            
            //自動的に一覧画面に遷移させる
            echo '<script type = "text/javascript">';
            echo "document.getElementById('cancel').click();";
            echo '</script>';

		}
		else
		{
			echo "<center>";
			echo "<a class = 'title'>ユーザー削除不可</a>";
			echo "<br><br>";
			echo "<a class ='error'>ユーザーが残り１つのため削除できません。</a>";
			echo "<br>";
			echo '<form action="listUserJump.php" method="post">';
			echo "<input type='submit' name='cancel' value = '一覧に戻る' class='free'>";
			echo "</form>";
			echo "</center>";
		}
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
	}
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
<title>ユーザー削除完了</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		set_button_size();
	});
    
    //ブラウザバック防止
    window.addEventListener('pageshow', function() {
        if (event.persisted) {
            window.location.href = 'retry.php';
        } else {
            
        }    
      });
    
--></script>
</head>
<body>


</body>

</html>
