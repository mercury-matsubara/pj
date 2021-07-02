<?php
<?php
	session_start();
	require_once("f_Construct.php");
	start();

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////

function pjend($post){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	require_once("f_DB.php");																							// DB関数呼び出し準備
	require_once("f_File.php");																							// DB関数呼び出し準備
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$item_ini = parse_ini_file('./ini/item.ini', true);
	
	//------------------------//
	//          定数          //
	//------------------------//
	$filename = $_SESSION['filename'];
	$pjid = $post['5CODE'];
	$nowdate = date_create("NOW");
	$nowdate = date_format($nowdate, 'Y-n-j');
	$teijitime = (float)$item_ini['settime']['teijitime'];
	
	//------------------------//
	//          変数          //
	//------------------------//
	$judge = false;
	$time = array();
	$teizi = array();
	$zangyou = array();
	$charge = 0;
	$period = 0;
	
	//------------------------//
	//        検索処理        //
	//------------------------//
	$sql = "SELECT * FROM progressinfo LEFT JOIN projectditealinfo USING(6CODE) LEFT JOIN projectinfo USING(5CODE) "
			."LEFT JOIN projectnuminfo USING(1CODE) LEFT JOIN syaininfo USING(4CODE) LEFT JOIN edabaninfo USING(2CODE) LEFT JOIN kouteiinfo USING(3CODE) WHERE projectditealinfo.5CODE = ".$pjid." order by SAGYOUDATE ;";
	$con = dbconect();																									// db接続関数実行
	$result = $con->query($sql) or ($judge = true);																		// クエリ発行
	if($judge)
	{
		error_log($con->error,0);
		$judge = false;
	}
	while($result_row = $result->fetch_array(MYSQLI_ASSOC))
	{
		if(isset($time[$result_row['6CODE']]))
		{
			$time[$result_row['6CODE']][count($time[$result_row['6CODE']])] = $result_row;
		}
		else
		{
			$time[$result_row['6CODE']][0] = $result_row;
		}
	}
	
	$keyarray = array_keys($time);
	$checkflg = false;
	$checkflgmessage = '';
	$delcheckflg = false;
	foreach($keyarray as $key)
	{
		
		$teizi[$key] = 0;
		$zangyou[$key] = 0;
		$teizicheck[$key] = 0;
		unset($before);
		$checkteiziarray = array();
		$checkkouteiarray = array();
		for($i = 0 ; $i < count($time[$key]) ; $i++)
		{
			$after = $time[$key][$i]['SAGYOUDATE'];
			if(isset($before))
			{
				if($before == $after)
				{
					$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
					if($teizicheck[$key] > $teijitime)
					{
						$checkflg = true;
						//定時エラー//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'の'.$errrecdate.'の進捗データが規定の定時時間を越えています。<br />';
					}
					if(array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray) !== FALSE)
					{
						$checkflg = true;
						//同一レコードエラー//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'の'.$time[$key][$i]['KOUTEINAME'].'の進捗データに同一工程のレコードが存在します。<br />';
						$checkstack = array_search($time[$key][$i]['KOUTEINAME'],$checkkouteiarray);
						$checkkouteiarray[$checkstack] = '';
					}
				}
				else
				{
					$teizicheck[$key] = 0;
					$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
					if($teizicheck[$key] > $teijitime)
					{
						$checkflg = true;
						//定時エラー//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'の'.$errrecdate.'の進捗データが規定の定時時間を越えています。<br />';
					}
					$checkteiziarray = array();
					$checkkouteiarray = array();
				}
			}
			else
			{
				$teizicheck[$key]  += $time[$key][$i]['TEIZITIME'];
				if($teizicheck[$key] > $teijitime)
					{
						$checkflg = true;
						//定時エラー//
						$errrecname = $time[$key][$i]['STAFFNAME'];
						$errrecdate = $time[$key][$i]['SAGYOUDATE'];
						$checkflgmessage .= $errrecname.'の'.$errrecdate.'の進捗データが規定の定時時間を越えています。<br />';
					}
			}
			$teizi[$key]  += $time[$key][$i]['TEIZITIME'];
			$zangyou[$key]  += $time[$key][$i]['ZANGYOUTIME'];
			$charge = $time[$key][$i]['DETALECHARGE'];
			$before = $time[$key][$i]['SAGYOUDATE'];
			$checkteiziarray[] = $time[$key][$i]['TEIZITIME'];
			$checkkouteiarray[] = $time[$key][$i]['KOUTEINAME'];
			$pjnum = $time[$key][$i]['PROJECTNUM'];
			$pjeda = $time[$key][$i]['EDABAN'];
			$pjname = $time[$key][$i]['PJNAME'];
		}
		if(!$checkflg)
		{	
			$total = $teizi[$key] + $zangyou[$key];
		    $performance = round($charge/$total,3);
		    $sql_end = "INSERT INTO endpjinfo (6CODE,TEIJITIME,ZANGYOTIME,TOTALTIME,PERFORMANCE,PROJECTNUM,EDABAN,PJNAME) VALUES "
		    			."(".$key.",".$teizi[$key].",".$zangyou[$key].",".$total.",".$performance.","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".") ;";
		    $result = $con->query($sql_end) or ($judge = true);																		// クエリ発行
		    if($judge)
		    {
		    	error_log($con->error,0);
		    	$judge = false;
		    }
		    $month_array = array();
		    for($i = 0 ; $i < count($time[$key]); $i++ )
		    {
		    	$date = date_create($time[$key][$i]['SAGYOUDATE']);
		    	$month = date_format($date, 'n');
		    	$day = date_format($date, 'j');
		    	$year = date_format($date, 'Y');
		    	$period = getperiod($month,$year);
		    	if(isset($month_array[$month]))
		    	{
		    		$month_array[$month]['teizi'] += $time[$key][$i]['TEIZITIME'];
		    		$month_array[$month]['zangyou'] += $time[$key][$i]['ZANGYOUTIME'];
		    	}
		    	else
		    	{
		    		$month_array[$month]['teizi'] = $time[$key][$i]['TEIZITIME'];
		    		$month_array[$month]['zangyou'] = $time[$key][$i]['ZANGYOUTIME'];
		    		$month_array[$month]['4CODE'] = $time[$key][$i]['4CODE'];
		    	}
		    }
		    if(!$delcheckflg)
			{
				$sql_delete = "DELETE FROM monthdatainfo WHERE 5CODE = ".$pjid." ;";
				$result = $con->query($sql_delete) or ($judge = true);																		// クエリ発行
				if($judge)
				{
					error_log($con->error,0);
					$judge = false;
				}
				$delcheckflg = true;
			}
		    for($i = 1 ;$i <= 12 ;$i++)
		    {
		    	if(isset($month_array[$i]))
		    	{
		    		$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,9ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES"
		    					." (".$month_array[$i]['4CODE'].",".$pjid.",'".$period."','".$i."','�莞����','"
		    					.$month_array[$month]['teizi']."','".$nowdate."'".","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".");";
		    		$checkSQLtest .= $sql_month;
		    		$result = $con->query($sql_month) or ($judge = true);																		// クエリ発行
		    		if($judge)
		    		{
		    			error_log($con->error,0);
		    			$judge = false;
		    		}
		    		$sql_month = "INSERT INTO monthdatainfo (4CODE,5CODE,PERIOD,MONTH,ITEM,VALUE,9ENDDATE,PROJECTNUM,EDABAN,PJNAME) VALUES"
		    					." (".$month_array[$i]['4CODE'].",".$pjid.",'".$period."','".$i."','�c�Ǝ���','"
		    					.$month_array[$month]['zangyou']."','".$nowdate."'".","."'".$pjnum."'".","."'".$pjeda."'".","."'".$pjname."'".");";
		    		$checkSQLtest .= $sql_month;
		    		$result = $con->query($sql_month) or ($judge = true);																		// クエリ発行
		    		if($judge)
		    		{
		    			error_log($con->error,0);
		    			$judge = false;
		    		}
		    	}
		    }
		    $sql_update = "UPDATE progressinfo SET 7ENDDATE = '".$nowdate."' , 7PJSTAT = '2' WHERE 6CODE = ".$key." ;";
		    $result = $con->query($sql_update) or ($judge = true);																		// クエリ発行
		    if($judge)
		    {
		    	error_log($con->error,0);
		    	$judge = false;
		    }
		    $sql_update = "UPDATE projectditealinfo SET 6ENDDATE = '".$nowdate."' , 6PJSTAT = '2' WHERE 6CODE = ".$key." ;";
		    $result = $con->query($sql_update) or ($judge = true);																		// クエリ発行
		    if($judge)
		    {
		    	error_log($con->error,0);
		    	$judge = false;
		    }
		}
	}
	if(!$checkflg)
	{
		$sql_update = "UPDATE projectinfo SET  5ENDDATE = '".$nowdate."' , 5PJSTAT = '2' WHERE 5CODE = ".$pjid." ;";
	    $result = $con->query($sql_update) or ($judge = true);																		// クエリ発行
	    if($judge)
	    {
	    	error_log($con->error,0);
	    	$judge = false;
	    }
	    return("終了処理が完了しました。");
	}
	else
	{
		return($checkflgmessage);
	}
}


	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
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
	echo $filename.'<br>';
	echo $main_table.'<br>';
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS" />
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
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
	function select_value(value,name,type)
	{
		value = value.split("#$");
		name = name.split(",");
		type = type.split(",");
		for(var i = 0 ; i < value.length ; i++)
		{
			var obj = document.getElementsByName(name[i])[(document.getElementsByName(name[i]).length-1)];
			if(type[i] == 9)
			{
				obj.value = value[i];
			}
			else
			{
				var select = obj;
				var selectnum = obj.selectedIndex;
				select.options[selectnum].selected = false;
				select.options[selectnum].disabled = true;
				for(var j = 0; j < select.options.length ; j++)
				{
					if(select.options[j].value == value[i])
					{
						select.options[j].selected = false;
						select.options[j].disabled = true;
					}
				}
			}
		}
	}
	
	function checkonradio()
	{
		var id ='<?php echo $main_table; ?>';
		var judge = false;
		id += 'CODE';
		document.getElementById(id).value;
		if(document.getElementById(id).value != "")
		{
			judge = true;
		}
		else
		{
			alert("終了するプロジェクトを選択してください。");
			judge = false;
		}
		return judge;
	}
	
// --></script>
</head>
<body>


<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	if(!isset($_SESSION['list']))
	{
		$_SESSION['list'] = array();
	}
//	$_SESSION['list']['form_506_0'] = '2';
	
	$sql = array();
	$sql = joinSelectSQL($_SESSION['list'],$main_table);
	$sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
	$damy_array = array();
	$list ="";
	$list = makeList_radio($sql,$_SESSION['list'],$main_table);
	$columns = $form_ini[$filename]['sech_form_num'];
	$form = makeformModal_set($_SESSION['list'],'',"form",$columns);
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	$form_drop = makeformModal_set($damy_array,'readOnly','drop',$columns);
	
	
	$checkList = $_SESSION['check_column'];
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="pjendJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
	echo "<table><tr><td>";
	echo "<fieldset><legend>検索条件</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo '<input type="submit" name="serch" value = "表示" class="free" >';
	echo "</td></tr></table>";
	echo $list;
	echo "</form>";
	echo '<form name ="drop" id = "drop" action="pjendJump.php" method="post" onsubmit ="return checkonradio();">';
	echo "<br><table><tr><td>";
	echo "<input type = 'hidden' name = '".$main_table."CODE' id = '".$main_table."CODE' value =''>";
	echo $form_drop ;
	echo "</td><td valign='bottom' >";
	echo '<input type="submit" name="end" class="button" value="ＰＪ終了">';
	echo "</td>";
	echo "</tr></table>";
	echo "</form>";
	echo "</div>";
?>
	
</body>
</html>


