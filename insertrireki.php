<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	start();
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
<?php
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$_SESSION['post'] = $_SESSION['pre_post'];
        $_SESSION['pre_post'] = null;
	
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
	$title .= "個別金額設定完了";
?>
<head>
<title><?php echo $title ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./inputcheck.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
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
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
		set_button_size();
	});
	$(window).resize(function()
	{
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
	});
--></script>
</head>
<body>
<?php
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</form></div>";
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>PJ個別金額設定完了</a>";
	echo "<br><br>";
	echo "個別金額の設定が完了しました。";
	echo "<br><br>";
	echo "</div>";
	echo "<div class = 'left' id = 'space_button'>　</div>";
	echo "<div><table id = 'button'><tr><td>";
        echo "<form action='listJump.php' method='post'>";
        echo '<input type="submit" name = "cancel" value = "一覧に戻る" class="free">';
        echo "</form>";
	echo "</td></tr></table></div>";
	$con = dbconect();																									// db接続関数実行
	$CODE5 = $_SESSION['kobetu']['id'];
	$CODE4 = "";
	$name_arrsy = array();
	$keyarray = array_keys($_SESSION['list']);
	foreach($keyarray as $key)
	{
		if (strstr($key, 'kobetu'))
		{
			$name_arrsy = explode('_',$key);
			$CODE4 = $name_arrsy[1];
			$row_num = 0 ;
			$judge = false;
			$SQL = "SELECT COUNT(*) FROM projectditealinfo WHERE 4CODE = ".$CODE4." AND 5CODE = ".$CODE5." ;";
			$result = $con->query($SQL) or ($judge = true);																	// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$row_num = $result_row['COUNT(*)'];
			}
			if($row_num == 0 && $_SESSION['list'][$key] != '')
			{
				$judge = false;
				$SQL = "INSERT INTO projectditealinfo (4CODE,5CODE,DETALECHARGE) VALUES(".$CODE4.",".$CODE5.",".$_SESSION['list'][$key].");";
				$con->query($SQL) or ($judge = true);																	// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
			}
			else if($row_num == 1)
			{
				$judge = false;
				if($_SESSION['list'][$key] == '')
				{
					$_SESSION['list'][$key] = 0;
				}
				$SQL = "UPDATE projectditealinfo SET DETALECHARGE = ".$_SESSION['list'][$key]." WHERE 4CODE = ".$CODE4." AND 5CODE  = ".$CODE5." ;";
				$con->query($SQL) or ($judge = true);																	// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
			}
		}
		else if($key == 'chage')
		{
			$judge = false;
			$SQL = "UPDATE projectinfo SET CHARGE = ".$_SESSION['list'][$key]." WHERE  5CODE  = ".$CODE5." ;";
			$con->query($SQL) or ($judge = true);																	// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
		}
	}
    insert_sousarireki($_SESSION["filename"],"1",$_SESSION["pjdata"]);
    $_SESSION['list'] = $_SESSION['kensaku'];
    unset($_SESSION['kensaku']);
?>
</body>
</html>
