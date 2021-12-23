<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.1.0  2014/07/03                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
<?php
	require_once("f_Construct.php");
	start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	$filename = $_SESSION['filename'];
	$title = $form_ini[$filename]['title'];
//	$_SESSION['post'] = $_SESSION['pre_post'];
//	$_SESSION['pre_post'] = null;
	
?>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
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
		var t1 =  $('td.one').width();
		var t2 =  $('td.two').width();
		var w = $(window).width();
		var width_div = 0;
		if (w > 600)
		{
			width_div = w/2 - (t1 + t2)/2;
		}
		$('td.space').css({
			width : width_div
		});
		set_button_size();
	});
--></script>
</head>
<body>
<?php
	$flag = true;
	$org5code = $_SESSION['kimatagi']['5CODE'];
	$orgpjcode = $_SESSION['kimatagi']['nextcode'];
	$orgedaban = $_SESSION['kimatagi']['nextedaban'];
	$orgpjname = $_SESSION['kimatagi']['nextname'];
	$orgcharge = $_SESSION['kimatagi']['charge'];
	$nowpjcode = $_SESSION['kimatagi']['nowcode'];
	$nowedaban = $_SESSION['kimatagi']['nowedaban'];
	$nowpjname = $_SESSION['kimatagi']['nowname'];
	$nowcharge = $_SESSION['kimatagi']['nowcharge'];
	$message = "";
	$error = pjCheck($org5code);
	if(!empty($error[0]['STAFFNAME']))
	{
		$flag = false;
		$list = makeList_error($error);
	}
	if(!empty($_SESSION['message']))
	{
		$flag = false;
		$message .= $_SESSION['message'];
		unset($_SESSION['message']);
	}
	if($nowcharge < $orgcharge)
	{
		$flag = false;
		$message .= "<font color='red'>期またぎ金額が前期PJ受注金額を上回っています。</font><br><br>";
	}
	if($flag)
	{
		$judge = true;
		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left' id = 'space_button'>　</div>";
		echo "<div><table id = 'button'><tr><td>";
		echo makebutton();
		echo "</td></tr></table></div>";
		echo "</form>";
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>期またぎ処理</a>";
		echo "</div>";
		echo "<br><br>";
		echo "<table>";
		echo "<tr><td class = 'space'><td class='center' colspan='2'>前期PJ</td></tr>";
		echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>プロジェクトコード</a></td><td class = 'two'><a class = 'comp' >".$nowpjcode."</a></td></tr>";
		echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>枝番コード</a></td><td class = 'two'><a class = 'comp' >".$nowedaban."</a></td></tr>";
		echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>製番・案件名</a></td><td class = 'two'><a class = 'comp' >".$nowpjname."</a></td></tr>";
		echo "<tr><td class = 'space'><td class='center' colspan='2'></td></tr>";
		echo "<tr><td class = 'space'><td class='center' colspan='2'>次期PJ</td></tr>";
		echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>プロジェクトコード</a></td><td class = 'two'><a class = 'comp' >".$orgpjcode."</a></td></tr>";
		echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>枝番コード</a></td><td class = 'two'><a class = 'comp' >".$orgedaban."</a></td></tr>";
		echo "<tr><td class = 'space'></td><td class = 'one'><a class = 'itemname'>製番・案件名</a></td><td class = 'two'><a class = 'comp' >".$orgpjname."</a></td></tr>";
		echo "</table>";
//		echo '<form action="nenziJump.php" method="post" >';
//		echo "<div class = 'center'>";
//		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
//		echo "</div>";
//		echo "</form>";
	}
	else
	{
		$_SESSION['post'] = $_SESSION['pre_post'];
		$_SESSION['pre_post'] = null;
		$judge = false;
		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left' id = 'space_button'>　</div>";
		echo "<div><table id = 'button'><tr><td>";
		echo makebutton();
		echo "</td></tr></table></div>";
		echo "</form>";
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>期またぎ処理エラー</a>";
		echo "</div>";
		echo "<br><br>";
		echo "<div><center>";
		if(!empty($message))
		{
			echo $message;
		}
		else
		{
			echo "<FONT color='red'>前期PJの進捗データに誤りがあるため、期またぎ処理が行えません。</FONT><br><br>";
			echo $list;
		}
		echo "</center></div><br><br>";
		echo '<form action="nenziJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>
<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $flag ?>';
		if(judge)
		{
			if(confirm("処理内容正常確認。\nプロジェクトの期またぎ処理を行いますがよろしいですか？"))
			{
				location.href = "./kimatagiComp.php";
			}
		}
	}
--></script>
</body>
</html>


