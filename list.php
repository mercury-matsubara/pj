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
*                                          ver 1.1.0  2014/07/03                                             *
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
	//print_r($_SESSION['filename']);
	
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$isCSV = $form_ini[$filename]['isCSV'];
	$filename_array = explode('_',$filename);
	$filename_insert = $filename_array[0]."_1";
	if(isset($_SESSION['list']['limit']) == false)
	{
		$_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
	}
	$main_table = $form_ini[$filename]['use_maintable_num'];
	//print_r($main_table);
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '一覧';
		break;
	case 1:
		$title2 = '一覧';
		$isMaster = true;
		break;
	default:
		$title2 = '';
	}
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">	
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./Modal.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./syukkacheck.js'></script>
<script language="JavaScript"><!--
	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		set_button_size();
	});
	function check(checkList)
	{
		var judge = true;
		var checkListArray = checkList.split(",");
		for (var i = 0 ; i < checkListArray.length ; i++ )
		{
			var param = checkListArray[i].split("~");
			if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
			{
				judge = false;
			}
		}
		return judge;
	}
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
--></script>
</head>
<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
	$form = makeformSerch_set($_SESSION['list'],"form");
	
	if($filename == 'MONTHLIST_2')
	{
		$sql = itemListSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	else if($filename == 'PJLIST_2')
	{
//		$_SESSION['list']['form_405_0'] = '0';
		$sql = itemListSQL($_SESSION['list']);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList_item($sql,$_SESSION['list']);
	}
	elseif($filename != "KOUTEIINFO_2")
	{
		$sql = joinSelectSQL($_SESSION['list'],$main_table);
		$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
		$list = makeList($sql,$_SESSION['list']);
	}
	if($filename == 'KOUTEIINFO_2' && $main_table ='3' )
	{
		$where = "";
		if(!empty($_SESSION['list']['form_302_0']))
		{
			$where .= "WHERE KOUTEIID LIKE '%".$_SESSION['list']['form_302_0']."%'";
		}
		if(!empty($_SESSION['list']['form_303_0']))
		{
			if(!empty($where))
			{
				$where .= "AND KOUTEINAME LIKE '%".$_SESSION['list']['form_303_0']."%'";
			}
			else
			{
				$where .= "WHERE KOUTEINAME LIKE '%".$_SESSION['list']['form_303_0']."%'";
			}
		}
		$sql[0] = "SELECT * FROM kouteiinfo ".$where." ORDER BY KOUTEIID;";
		$sql[1] = "SELECT COUNT(*) FROM kouteiinfo ".$where.";";
		$list = makeList($sql,$_SESSION['list']);
	}
	if($filename == 'ENDPJLIST_2' && $main_table =='8' )
	{
		$sql[0] =  str_replace("*", " a.PROJECTNUM as PROJECTNUM,a.EDABAN as EDABAN,a.PJNAME as PJNAME,STAFFNAME,TEIJITIME,ZANGYOTIME,DETALECHARGE,TOTALTIME,PERFORMANCE,5ENDDATE", $sql[0]);
		$sql[0] =  str_replace("endpjinfo", " endpjinfo as a ", $sql[0]);
		$sql[0] =  str_replace("projectnuminfo.PROJECTNUM", " a.PROJECTNUM ", $sql[0]);
		$sql[0] =  str_replace("edabaninfo.EDABAN", " a.EDABAN ", $sql[0]);
		$sql[0] =  str_replace("edabaninfo.PJNAME", " a.PJNAME ", $sql[0]);
		$sql[1] =  str_replace("endpjinfo", " endpjinfo as a ", $sql[1]);
		$sql[1] =  str_replace("projectnuminfo.PROJECTNUM", " a.PROJECTNUM ", $sql[1]);
		$sql[1] =  str_replace("edabaninfo.EDABAN", " a.EDABAN ", $sql[1]);
		$sql[1] =  str_replace("edabaninfo.PJNAME", " a.PJNAME ", $sql[1]);
		
		$list = makeList($sql,$_SESSION['list']);
	}
	$checkList = $_SESSION['check_column'];
	$isLavel = $form_ini[$filename]['isLabel'];
	$isMail = $form_ini[$filename]['isMail'];
	
	if($isLavel == 1)
	{
		echo "<div class = 'left'>";
		echo '<input type="submit" name="label" class="free" 
				value = "ラベル発行" >';
		echo "</div>";
	}
	if($isMail == 1)
	{
		echo "<div class = 'left'>";
		echo '<input type="button" name="mail" class="free" value = "メール発行" 
				onClick = "click_mail();">';
		echo "</div>";
	}
        echo "<form action='pageJump.php' method='post'>";
	echo makebutton();
	echo "</form>";
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="listJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo '<input type="submit" name="serch" value = "表示" class="free" >';
	echo "</td></tr></table><br><br>";
	
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
		$year = date_create('NOW');
		$year = date_format($year, "Y");
		$year--;
		echo "<table><tr><td>作業日 : </td><td>";
		echo pulldownDate_set(2,$year,0,"form_start","",$_SESSION['list'],"","form",1);
		echo "</td></tr></table>";
	}
	
	echo $list;
	echo "</form>";
	if($isCSV == 1)
	{
		echo "<form action='download_csv.php' method='post'>";
		echo "<div class = 'left'>";
		echo "<input type ='submit' name = 'csv' class='button' value = 'csvファイル生成' style ='height:30px;' >";
		echo "</div>";
		echo "</form>";
	}
	if(isset($form_ini[$filename_insert]))
	{
		echo "<form action='pageJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
		echo "<input type ='submit' value = '新規作成' class = 'free' name = '".$filename_insert."_button'>";
		if($filename == 'PROGRESSINFO_2')
		{
			echo "<input type ='submit' value = 'ファイル取込' class = 'free' name = 'PROGRESSINFO_6_button'>";
		}
		echo "</div>";
		echo "</form>";
	}
	if($filename == 'HENKYAKUINFO_2' || $filename == 'SYUKKAINFO_2')
	{
//		echo "<form action='insertrireki.php' method='post' onsubmit = 'return syukkacheck();' >";
		echo "<div class = 'left'>";
//		echo "<input type ='submit' name = 'syukka' class='free' value = '設定'>";
		echo "<input type ='submit' name = 'syukka' class='free' value = '設定' onclick = 'syukkacheck();'>";
		echo "</div>";
//		echo "</form>";
	}
	echo "</div>";
	echo "</div>";
?>
</body>
<script language="JavaScript">
    window.onload = function() {
        var filename = '<?php if($filename == "ENDPJLIST_2"){ echo $filename; }else{ echo ""; } ?>';
        
        if(filename == 'ENDPJLIST_2'){
            //日付入力欄値
            document.getElementById("startdate").value = '<?php if(isset($_SESSION["list"]["startdate"])){ echo $_SESSION["list"]["startdate"]; }else{ echo ""; } ?>';
            document.getElementById("enddate").value = '<?php if(isset($_SESSION["list"]["enddate"])){ echo $_SESSION["list"]["enddate"]; }else{ echo ""; } ?>';
        }
    }
</script>
</html>
