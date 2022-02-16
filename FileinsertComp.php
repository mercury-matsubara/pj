<?php
	set_time_limit(180);
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	start();
	
	require_once("f_DB.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	$isMaster = false;
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$judge = true;
	if(isset($_SESSION['insert']['true']))
	{
		if($_SESSION['insert']['true'])
		$judge = true;
	}
	
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
/*
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '“o˜^';
		break;
	case 1:
		$title2 = '“o˜^';
		$isMaster = true;
		break;
	default:
		$title2 = '';
	}
*/
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
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	$(window).resize(function()
	{
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
	});
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
        if($judge)
	{
		require_once("f_Button.php");
		require_once("f_File.php");
		$filename = $_SESSION['filename'];
		require_once("f_DB.php");
		unset($_SESSION['upload']);
		$form = FileReadInsert();
                if(isset($_SESSION['history']))
                {
                    if($_SESSION['fileinsert']['judge'])
                    {
                        header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://")
                                    .$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/TOP.php");
                        unset($_SESSION['history']);
                    }
                }
		echo "<table border='0' WIDTH=100%><tr>";
		echo "<form action='pageJump.php' method='post'><div>";
		echo makebutton();
		echo "</div></form>";
//		echo "<form action='FileinsertJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
//		echo "<input type ='submit' value = '–ß‚é' name = 'back' class = 'free'>";
//		echo "</div></form>";
		echo "<div style='clear:both;'></div>";
		echo "</tr></table>";
		/*
		if($_SESSION['fileinsert']['judge'])
		{
			echo "<div class = 'center'><br><br>";
			echo "<a class = 'title'>".$title1."“o˜^Š®—¹</a>";
			echo "</div>";
		}
		else
		{
			echo "<div class = 'center'><br><br>";
			echo "<a class = 'title'>".$title1."“o˜^ƒGƒ‰[</a>";
			echo "</div>";
		}
		*/
		//----2018/01/18 €”Ô32 asanoma æ‚İŠ®—¹‚Ì‰æ–Ê‘JˆÚ‘Î‰ start ----->>
		echo "<div class = 'center'><br><br>";
		if($_SESSION['fileinsert']['judge'])
		{
			echo "<a class = 'title'>".$title1."“o˜^Š®—¹</a>";
		}
		else
		{
			echo "<a class = 'title'>".$title1."“o˜^ƒGƒ‰[</a>";
		}
		
                //----2018/01/18 €”Ô32 asanoma æ‚İŠ®—¹‚Ì‰æ–Ê‘JˆÚ‘Î‰ end -----<<
                echo "<center>";
                echo "<br><br>";
                echo $form;
                
                if(isset($_SESSION['history']))
                {
                    echo "<br><br>";
                    echo "<div style='display:inline-flex'>";
                    echo "<form action='FileinsertJump.php' method='post'>";
                    echo "<input type ='submit' value = 'TOP‚É–ß‚é' name = 'back' class = 'free'>";
                    echo "</form></div></div>";
                }
                else
                {
                    //----2018/01/18 €”Ô32 asanoma æ‚İŠ®—¹‚Ì‰æ–Ê‘JˆÚ‘Î‰ start ----->>
                    echo "<br><br>";
                    echo "<div style='display:inline-flex'>";
                    echo "<form action='FileinsertJump.php' method='post'>";
                    echo "<input type ='submit' value = 'ˆê——‚É–ß‚é' name = 'back' class = 'free'>";
                    echo "</form>";
                    echo "<form action='pageJump.php' method='post'>";
                    echo "<input type ='submit' value = 'æ‰æ–Ê‚É–ß‚é' class = 'free' name = 'PROGRESSINFO_6_button'>";
                    echo "</form></div></div>";
                    //----2018/01/18 €”Ô32 asanoma æ‚İŠ®—¹‚Ì‰æ–Ê‘JˆÚ‘Î‰ end -----<<
                }
                echo "</center>";
		$_SESSION['insert'] = null;
	}
	else
	{
		header("location:".(empty($_SERVER['HTTPS'])? "http://" : "https://").$_SERVER['HTTP_HOST'].dirname($_SERVER["REQUEST_URI"])."/retry.php");
	}
	
?>

</body>

</html>
