<?php
	session_start();
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	start();
?>
<!DOCTYPE html>
<?php
	require_once("f_DB.php");
	$isexist = true;
	$pass = $_SESSION['result_array']['LUSERPASS'];
	$checkResultarray = selectID($_SESSION['listUser']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
?>
<style type="text/css">
	a.title {
		font-size: xx-large;
	}
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>ユーザー更新再入力</title>
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	history.forward();
	var button = '';
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
		if (button == 'change')
		{
			var judge = true;
			var checkListArray = checkList.split(",");
			var pass = '<?php echo $pass; ?>';
			var isenpty = false;
			for (var i = 0 ; i < checkListArray.length ; i++ )
			{
				var param = checkListArray[i].split("_");
				if(!inputcheck(param[0],param[1],param[2]))
				{
					judge = false;
				}
				if(document.getElementById(param[0]).value =="")
				{
					judge = false;
					document.getElementById(param[0]).style.backgroundColor 
						= '#ff0000';
					isenpty = true;
				}
			}
			if(isenpty)
			{
				judge = false;
				window.alert('項目を入力してください。');
			}
			if(document.getElementById('pass').value != pass)
			{
				judge = false;
				document.getElementById('pass').style.backgroundColor 
					= '#ff0000';
				window.alert('現在のパスワードと内容が異なっております。');
			}
			if (document.getElementById('newpass').value !=
				 document.getElementById('passCheck').value)
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
		
        if(button == 'clear')
        {
            result = true;
        }
		return result;
	}
	
	function set_button(buttonName)
	{
		button = buttonName;
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
	$_SESSION['post'] = null;
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$columnName = "";
	$judge = false;
	if($_SESSION['editUser']['uid'] != $_SESSION['result_array']['LUSERNAME'])
	{
		$columnName 
			= UserCheck($_SESSION['editUser']['uid'],$_SESSION['editUser']['pass']);
	}
	if ($columnName == "")
	{
		$judge = true;
	}
	else
	{
		$judge = false;
	}
	$checkList = "uid_20_3,pass_20_3,newpass_20_3,passCheck_20_3";
	require_once("f_Button.php");
	$filename = $_SESSION['filename'];
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	if($isexist)
	{
		echo "<div class = 'center'>";
		echo "<a class = 'title'>ユーザー更新再入力</a>";
		echo "</div><br><br>";
		echo '<form action="listUserJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
        echo "<input type ='hidden' name ='4CODE'  value ='".$_SESSION['editUser']['4CODE']."' >";
		echo "<table>";
        echo "<tr><td class = 'space'></td><td class='one'>社員番号</td>";
        echo "<td><input type ='text' name='form_402_0' id='form_402_0'  value ='".$_SESSION['editUser']['form_402_0']."' readonly class='readOnly' size='20px' onchange='return inputcheck('form_402_0',6,4,false,2);'></td></tr>";
		echo "<tr><td class = 'space'></td><td class='one'>社員名</td>";
        echo "<td><input type ='text' name ='form_403_0' id='form_403_0' value ='".$_SESSION['editUser']['form_403_0']."' readonly class='readOnly' size='60px' onchange='return inputcheck('form_403_0',6,4,false,2);'></td></tr>";        
        echo "<tr><td class = 'space'></td><td class = 'one'>ユーザーID</td>";
		echo '<td class = "two"><input type = "text" size = "30"  name = "uid"  id="uid" value = "'
				.$_SESSION['editUser']['uid'].'" 
				onchange ="return inputcheck(\'uid\',20,3);"></td>';
		if(!$judge)
		{
			echo "<td><a class ='error'>既に登録されています。</a></td>";
		}
		if(!$isexist || !$judge)
		{
			$judge = false;
		}
		else
		{
			$_SESSION['pre_post'] = $_SESSION['post'] ;
			$_SESSION['post']['true'] = true;
		}
		echo "</tr><tr><td class = 'space'></td><td class = 'one'>現在パスワード</td>";
		echo '<td class = "two"><input type = "password" size = "31" name = "pass"  id="pass" 
				value = "'.$_SESSION['editUser']['pass'].'" 
				onchange ="return inputcheck(\'pass\',20,3);"></td>';
		echo "</tr><tr><td class = 'space'></td><td class = 'one'>パスワード</td>";
		echo '<td class = "two"><input type = "password" size = "31" name = "newpass" 
				value = "'.$_SESSION['editUser']['newpass'].'" id="newpass" 
				onchange ="return inputcheck(\'newpass\',20,3);"></td>';
		echo "</tr><tr><td class = 'space'></td><td class = 'one'>確認用パスワード</td>";
		echo '<td class = "two"><input type = "password" size = "31" name = "passCheck"
				 value = "'.$_SESSION['editUser']['passCheck'].'" id="passCheck"
				 onchange ="return inputcheck(\'passCheck\',20,3);"></td>';
		echo "</tr></table>";
		echo "<br>";
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "change" value = "更新" 
				class = "free" onClick="set_button(\'change\');">';
        echo '<input type="submit" name="clear" value = "クリア" class="free"
                onClick="set_button(\'clear\');">';
        echo '<input type="submit" name = "cancel" value = "一覧に戻る" 
				class = "free" onClick="set_button(\'cancel\');">';

		echo "</div>";
	}
	else
	{
		$judge == false;
		echo "<div = class='center'>";
		echo "<a class = 'title'>ユーザー更新不可</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>他の端末ですでにデータが削除されているため、更新できません。</a>
				</div>";
		echo '<form action="listUserJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>
</body>

<script language="JavaScript"><!--

	window.onload = function(){
		var judge_go = '<?php echo $judge ?>';
		if(judge_go)
		{
			if(confirm("入力内容正常確認。\n情報更新しますがよろしいですか？" +
						"\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./editUserComp.php";
			}
		}
	}
--></script>

</html>
