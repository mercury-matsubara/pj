<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
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
	$judge = true;
	$error = pjCheck($_SESSION['list']);
	if(!empty($error[0]['STAFFNAME']))
	{
		$judge = false;
		$list = makeList_error($error);
	}
	if(!empty($_SESSION['message']))
	{
		$judge = false;
		$message = $_SESSION['message'];
        $pjcode = $_SESSION['pjcode'];
        $edabancode = $_SESSION['edabancode'];
        $pjname = $_SESSION['pjname'];
        
		unset($_SESSION['message']);
        unset($_SESSION['pjcode']);
        unset($_SESSION['edabancode']);
        unset($_SESSION['pjname']);
	}
	if($judge)
	{
		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left' id = 'space_button'>　</div>";
		echo "<div><table id = 'button'><tr><td>";
		echo makebutton();
		echo "</td></tr></table></div>";
		echo "</form>";
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title."</a>";
        echo "</div>";
		echo "<br><br>";
        echo '<div><center>';
        echo "<table border='1' id = 'select_pj' class ='list' name ='formInsert'><thead><tr><th><a class ='head'>No</a></th><th><a class ='head'>プロジェクトコード</a></th><th><a class ='head'>枝番コード</a><th><a class ='head'>製番・案件名</a></th></tr></thead>";
        
        $pjcode = explode(",",$_SESSION['list']['pjcode']);
        $edabancode = explode(",",$_SESSION['list']['edabancode']);
        $pjname = explode(",",$_SESSION['list']['pjname']);
        $_SESSION['post'] = $_SESSION['pre_post'];
        $_SESSION['list'] = $_SESSION['kensaku'];
        unset($_SESSION['kensaku']);
        
        for($i=0; $i < count($pjcode);$i++){
            if(($i % 2) == 1){
                echo '<td id = "stripe">'.($i + 1).'</td>';
                echo "<td id = 'stripe'>".$pjcode[$i]."</td>";
                echo "<td id = 'stripe'>".$edabancode[$i]."</td>";
                echo "<td id = 'stripe'>".$pjname[$i]."</td></tr>";
            }
            else
            {
                echo '<td>'.($i + 1).'</td>';
                echo "<td>".$pjcode[$i]."</td>";
                echo "<td>".$edabancode[$i]."</td>";
                echo "<td>".$pjname[$i]."</td></tr>";
            }
        }
		echo "</table>";
                echo '</center></div>';
//		echo '<form action="pjendJump.php" method="post" >';
//		echo "<div class = 'center'>";
//		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
//		echo "</div>";
//		echo "</form>";
	
	}
	else
	{
		$_SESSION['post'] = $_SESSION['pre_post'];
		echo "<form action='pageJump.php' method='post'>";
		echo "<div class = 'left' id = 'space_button'>　</div>";
		echo "<div><table id = 'button'><tr><td>";
		echo makebutton();
		echo "</td></tr></table></div>";
		echo "</form>";
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title."エラー"."</a>";
		echo "</div>";;
		echo "<br><br>";
		echo "<div><center>";
   
		if(!empty($message))
		{
            echo ''.(count($message)).'件のPJが進捗登録がされていません。PJ終了処理がキャンセルされました。';
            echo "<div class='listScroll'>";
            echo "<table border='1' id = 'select_pj' class ='list' name ='formInsert'><thead><tr><th><a class ='head'>No</a></th><th><a class ='head'>プロジェクトコード</a></th><th><a class ='head'>枝番コード</a><th><a class ='head'>製番・案件名</a></th></th><th><a class ='head'>エラー内容</a></th></th><tr/></thead>";

            for($i = 0; $i < count($message); $i++){
                if(($i % 2) == 1){
                    echo '<tr>';
                    echo '<td id = "stripe">'.($i + 1).'</td>';
                    echo '<td id = "stripe">'.$pjcode[$i].'</td>';
                    echo '<td id = "stripe">'.$edabancode[$i].'</td>';
                    echo '<td id = "stripe">'.$pjname[$i].'</td>';
                    echo '<td id = "stripe">'.$message[$i].'</td>';
                    echo '</tr>';
                }
                else{
                    echo '<tr>';
                    echo '<td>'.($i + 1).'</td>';
                    echo '<td>'.$pjcode[$i].'</td>';
                    echo '<td>'.$edabancode[$i].'</td>';
                    echo '<td>'.$pjname[$i].'</td>';
                    echo '<td>'.$message[$i].'</td>';
                    echo '</tr>';
                }
            }
		}
		else
		{
            echo "<div class='listScroll'>";
			echo $list;
            echo "</div>";
            $list = "";
		}
                echo "</table>";
                echo "</div>";
        if(isset($list))
        {
            if($list != "")
            {
                echo "<br><br>";
                echo "<div class='listScroll'>";
                echo $list;
                echo "</div>";
            }
        }
		echo "</center></div><br><br>";
		echo '<form action="pjendJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
		echo "</div>";
		echo "</form>";
        
	}
?>
<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("処理内容正常確認。\nプロジェクトを終了しますがよろしいですか？"))
			{
				location.href = "./pjendsyori.php";
			}
		}
	}
--></script>
</body>
</html>


