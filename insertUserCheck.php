<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
?>
<!DOCTYPE html>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>管理者登録再入力</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	var button = "";
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
	function check(checkList)
	{
		var result = false;
		if(button == 'insert')
		{
			var judge = true;
			var isenpty = false;
			var checkListArray = checkList.split(",");
			for (var i = 0 ; i < checkListArray.length ; i++ )
			{
				var param = checkListArray[i].split("_");
				if(inputcheck(param[0],param[1],param[2]) == false)
				{
					judge = false;
				}
				if(document.getElementById(param[0]).value =="")
				{
					judge = false;
					document.getElementById(param[0]).style.backgroundColor = '#ff0000';
					isenpty = true;
				}
			}
			if(isenpty)
			{
				window.alert('項目を入力してください。');
			}
			if (document.getElementById('pass').value != document.getElementById('passCheck').value)
			{
				judge = false;
				window.alert('パスワードと確認用パスワードの内容が一致していません。');
			}
			result = judge;
		}
		if(button == 'cancel')
		{
			result = true;
		}
		return result;
	}
	
	function set_button(buttonName)
	{
		button = buttonName;
	}
--></script>
</head>

<body>

<?php
	require_once("f_DB.php");
	$judge = false;
	$columnName = UserCheck($_SESSION['insertUser']['uid'],$_SESSION['insertUser']['pass']);
	if ($columnName == "")
	{
		$judge = true;
		$_SESSION['pre_post'] = $_SESSION['post'];
		$_SESSION['post']['true'] = true;
	}
	else
	{
		$judge = false;
	}
	require_once("f_Button.php");
	$filename = $_SESSION['filename'];
	$checkList = "uid_20_3,pass_20_3,passCheck_20_3";
	
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "<div class='center'>";
	echo "<a class = 'title'>管理者登録再入力</a>";
	echo "</div><br><br>";
	echo '<form action="insertUserJump.php" method="post"
			 onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td class = 'space'></td><td class = 'one'>管理者ID</td>";
	echo '<td class = "two"><input type = "text" size = "30" name = "uid" id="uid" 
			value ="'.$_SESSION['insertUser']['uid'].'" onchange ="return inputcheck(\'uid\',20,3);"></td>';
	if(!$judge)
	{
		echo "<td><a class ='error'>既に登録されています。</a></td>";
	}
	echo "</tr><tr><td class = 'space'></td><td class = 'one'>パスワード</td>";
	echo '<td class = "two"><input type = "password" size = "31" name ="pass" id="pass" 
			value = "'.$_SESSION['insertUser']['pass'].'" onchange ="return inputcheck(\'pass\',20,3);"></td>';
	echo "</tr><tr><td class = 'space'></td><td class = 'one'>確認用パスワード</td>";
	echo '<td class = "two"><input type = "password" size = "31" name = "passCheck" id="passCheck" 
			value = "'.$_SESSION['insertUser']['passCheck'].'"onchange ="return inputcheck(\'passCheck\',20,3);"></td>';
	echo "</tr></table>";
	echo "<br>";
	echo "<div class = 'center'>";
	echo '<input type="submit" name="insert" value = "登録" class="free"
			 onClick="set_button(\'insert\');">';
	echo '<input type="submit" name="cancel" value = "キャンセル" class="free"
			 onClick="set_button(\'cancel\');">';
	echo "</div>";
	echo "</form>";
?>

<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("入力内容正常確認。\n情報登録しますがよろしいですか？" +
						"\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./insertUserComp.php";
			}
		}
	}
--></script>

</html>
