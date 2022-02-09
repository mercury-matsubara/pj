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
*                                          ver 1.1.0  2014/07/03                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
<head>
<meta http-equiv="Content-Type" content="text/css; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<title>ユーザー管理</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
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
    
    function popup_modal(GET)
	{
		var w = screen.availWidth;
		var h = screen.availHeight;
		w = (w * 0.8);
		h = (h * 0.8);
		url = 'Modal.php?tablenum='+GET+'&form=edit';
//		n = showModalDialog(
//			url,
//			this,
////			"dialogWidth=800px; dialogHeight=480px; resizable=yes; maximize=yes"
//			"dialogWidth=" + w + "px; dialogHeight=" + h + "px; resizable=yes; maximize=yes"
//		);
                n = window.open(
                        url,
                        this,
                        "width =" + w + ",height=" + h + ",resizable=yes,maximize=yes"
                );	
	}

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
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	require_once("f_Button.php");
	require_once("f_DB.php");
	$filename = $_SESSION['filename'];
	echo "<left>";
	echo "<div style='clear:both;'></div>";
	echo "</left><br>";
	echo "<center>";
	echo "<a class = 'title'>ユーザー管理</a>";
	echo "<br><br>";
	echo "</center>";
	echo "<left>";
	echo "<div class = 'pad' >";
	echo "<form action='listUserJump.php' method='post'>";
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo "<table><tr><td></td><td>";
    echo '<input type="button" value="社員選択" onclick="popup_modal(\'4\')">';
    if(isset($_SESSION['post']['4CODE']))
    {
        $code4 = $_SESSION['post']['4CODE'];
    }
    else
    {
        $code4 = "";
    }
    echo "<input type ='hidden' name ='4CODE'  value ='".$code4."' >";
    echo "</td></tr>";
    echo "<tr><td><a class='itemname'>社員番号</a></td>";
    if(isset($_SESSION['post']['form_402_0']))
    {
        $syainnum = $_SESSION['post']['form_402_0'];
    }
    else
    {
        $syainnum = "";
    }
    echo "<td><input type ='text' name='form_402_0' id='form_402_0'  value ='".$syainnum."' readonly class='readOnly' size='20px' onchange='return inputcheck('form_402_0',6,4,false,2);'></td>";
    echo "</tr>";
    echo "<tr><td><a class='itemname'>社員名</a></td>";
    if(isset($_SESSION['post']['form_403_0']))
    {
        $syainname = $_SESSION['post']['form_403_0'];
    }
    else
    {
        $syainname = "";
    }
    echo "<td><input type ='text' name ='form_403_0' id='form_403_0' value ='".$syainname."' readonly class='readOnly' size='60px' onchange='return inputcheck('form_403_0',6,4,false,2);'></td>";
    echo "</tr>";
	echo "<tr></td>";
	echo "<td><a class='itemname'>ソート条件</a></td>";
	echo "<td>";
	echo "<select name='sort'>";
	echo "<option value='1'";
	if((isset ($_SESSION['post']['sort'])))
	{
		if($_SESSION['post']['sort'] == 1)
		{
			echo "selected";
		}
	}
	echo ">指定なし</option>";
	echo "<option value='2'";
	if((isset ($_SESSION['post']['sort'])))
	{
		if($_SESSION['post']['sort'] == 2)
		{
			echo "selected";
		}
	}
	echo ">社員番号</option>";
	echo "<option value='3'";
	if((isset ($_SESSION['post']['sort'])))
	{
		if($_SESSION['post']['sort'] == 3)
		{
			echo "selected";
		}
	}
	echo ">社員名</option>";    
	echo "<input name='radiobutton' type='radio' value='asc'";
	if((isset ($_SESSION['post']['radiobutton'])))
	{
		if($_SESSION['post']['radiobutton'] == 'asc')
		{
			echo "checked";
		}
	}
	else
	{
		echo "checked";
	}
	echo ">昇順";
	echo "<input name='radiobutton' type='radio' value='desc'";
	if((isset ($_SESSION['post']['radiobutton'])))
	{
		if($_SESSION['post']['radiobutton'] == 'desc')
		{
			echo "checked";
		}
	}
	echo ">降順";
	echo "</td>";
	echo "</tr></table>";
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo "<input type='submit' name='serchUser_button' class = 'free'
			value = '表示'>";
	echo "</td></tr></table><br>";
	echo selectUser();
	echo "</form>";
    //一覧に戻るボタンが使用できるようにするための変更
	//echo "<form action='pageJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
	//echo "<input type ='submit' value = '新規作成' class = 'free' name = 'insertUser_5_button'>";
    echo "<form action='listUserJump.php' method='post'><div class = 'left' style = 'HEIGHT: 30px'>";
    echo "<input type = 'submit' value = '新規作成' class = 'free' name = 'insert'>";
    
	echo "</div>";
	echo "</form>";
	echo "</div>";
	echo "</left>";
	echo "<form action='pageJump.php' method='post'>";
	echo makebutton();
	echo "</form>";
?>
</body>

</html>
