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
	$checkResultarray = selectID($_SESSION['listUser']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<title>ユーザー削除確認</title>
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
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$pass = "";
	$passLength = 0;
	$passLength = mb_strlen( $_SESSION['result_array']['LUSERPASS'] ,"UTF-8");
	for ($i = 0; $i < $passLength ; $i++)
	{
		$pass .="●";
	}
	require_once("f_Button.php");
	$filename = $_SESSION['filename'];
	echo "<left>";
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton();
	echo "</div>";
	echo "<div style='clear:both;'></div>";
	echo "</form>";
	echo "</left>";
	
	if($isexist)
	{
		echo "<center>";
		echo "<a class = 'title'>ユーザー削除確認</a>";
		echo "<br><br>";
		$_SESSION['pre_post'] = $_SESSION['post'] ;
		$_SESSION['post']['true'] = true;
		echo '<form action="listUserJump.php" method="post">';
		echo "<table>";
        echo "<tr><td id = 'item'>社員番号</td>";
        echo "<td>".$_SESSION['result_array']['STAFFID']."</td></tr>";
        echo "<tr><td id = 'item'>社員名</td>";
        echo "<td>".$_SESSION['result_array']['STAFFNAME']."</td></tr>";        
        echo "<tr><td id = 'item'>ユーザーID</td>";
		echo "<td>".$_SESSION['result_array']['LUSERNAME']."</td>";
		echo "</tr><tr><td id = 'item'>パスワード</td>";
		echo "<td>".$pass."</td>";
		echo "</tr></table>";
		echo "<br>";
		echo '<input type="submit" name = "delete" value = "削除" 
				class="free">';
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" 
				class = "free">';
		echo "</form>";
		echo "</center>";
	}
	else
	{
		echo "<div = class='center'>";
		echo "<a class = 'title'>ユーザー削除不可</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>他の端末ですでにデータが削除されているため、更新できません。</a>
				</div>";
		echo "<br>";
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
		var judge_go = '<?php echo $isexist ; ?>';
		if(judge_go)
		{
			if(confirm("入力内容正常確認。\n情報削除しますがよろしいですか？" +
				"\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./deleteUserComp.php";
			}
		}
	}
--></script>

</html>
