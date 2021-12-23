<?php
	session_start();
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
	$param_ini = parse_ini_file('./ini/param.ini', true);
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;


	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$formnum = $form_ini[$filename]['sech_form_num'];
	$now_year = date_create('NOW');
	$now_year = date_format($now_year, "Y");
	$now_month = date_create('NOW');
	$now_month = date_format($now_month, "n");

	$sql = array();
	$wareki = "";
	$wareki1 = "";
	$wareki2 = "";
	$check_csv = "";

	if(isset($_SESSION['list']['limit']) == false)
	{
		$_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
		$_SESSION['list']['isAll'] = false;
	}
	if(isset($_SESSION['list']['form_'.$formnum.'_0']) == false &&
			 isset($_SESSION['list']['form_'.$formnum.'_1']) == false)
	{
		$_SESSION['list']['form_'.$formnum.'_0'] = $now_year;
		$_SESSION['list']['form_'.$formnum.'_1'] = $now_month;
	}
	$wareki1 = wareki_year($_SESSION['list']['form_'.$formnum.'_0']);
	$wareki2 = wareki_year_befor($_SESSION['list']['form_'.$formnum.'_0']);
	if($wareki1 != $wareki2)
	{
		$wareki = $wareki1."年 - ".$wareki2."年度 "
					.$_SESSION['list']['form_'.$formnum.'_1']."月";
	}
	else
	{
		$wareki = $wareki1."年度 ".$_SESSION['list']['form_'.$formnum.'_1']."月";
	}
	$check_csv = make_scv($_SESSION['list'],'check_');
?>
<head>
<title><?php echo $title1 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./Modal.js'></script>
<script src='./button_size.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script language="JavaScript"><!--
	var check_csv = '<?php echo $check_csv; ?>';
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true,
			colornum: 1
		});
		make_check_csv();
		set_button_size();
	});
	function check(checkList)
	{
		var judge = true;
		var checkListArray = checkList.split(",");
		for (var i = 0 ; i < checkListArray.length ; i++ )
		{
			var param = checkListArray[i].split("~");
			if(!inputcheck(param[0],param[1],param[2]))
			{
				judge = false;
			}
		}
		return judge;
	}
--></script>
</head>
<body>
<?php
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
	$sql = joinSelectSQL($_SESSION['list'],$main_table);
	$list = makeList($sql,$_SESSION['list'],"form");
	$isLavel = $form_ini[$filename]['isLabel'];
	$isMail = $form_ini[$filename]['isMail'];
	$isCheckBox = $form_ini[$filename]['isCheckBox'];
	echo "<form action='pageJump.php' method='post'>";
	echo makebutton();
	echo '</form>';
	if($isLavel == 1)
	{
		echo "<div class = 'left'>";
		echo '<input type="button" name="label" value = "ラベル発行" class="free" onClick="click_label();">';
		echo "</div>";
	}
	if($isMail == 1)
	{
		echo "<div class = 'left'>";
		echo '<input type="button" name="mail" value = "メール発行" class="free" onClick="click_mail();">';
		echo "</div>";
	}
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="kensakuJump.php" method="post" >';
	echo make_kensaku($_SESSION['list'],$main_table);
	echo "<br><table><tr><td><a class='kensaku'>";
	echo $wareki;
	echo "</a></td>";
	if($isCheckBox == 1)
	{
		echo '<td><input type="submit" name="all_check" class="free" value="全件選択"></td>';
		echo '<td><input type="submit" name="all_clear" class="free" value="全件解除"></td>';
	}
	echo "</tr></table><br><br>";
	echo "<input type ='hidden' name='out' id = 'checkout' value=''>";
	echo $list;
	echo "</form>";
	echo "<form action='download_csv.php' method='post'>";
	echo "<div class = left>";
	echo "<input type ='submit' name = 'csv' class='button' value = 'csvファイル生成' style ='height:30px;' >";
	echo "</div>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>
