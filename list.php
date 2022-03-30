<?php
	session_start();
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
        $syain = "";
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
    
        if($filename == "PROGRESSINFO_2" || $filename == "PJTOUROKU_2")
        {
            $title2 = "登録/編集";
        }

        if($filename == "PROJECTNUMINFO_2" || $filename == "EDABANINFO_2" || $filename == "KOUTEIINFO_2" || 
                $filename == "SYAINNINFO_2" || $filename == "GENKAINFO_2")
        {
            $title2 = "メンテナンス";
        }
        //在籍社員取得
        if($filename == "GENKAINFO_2")
        {
            $syain = syaget();
            $syain = rtrim($syain, ",");
        }
        if(isset($_SESSION['path']))
        {
                header('Content-Type: application/octet-stream'); 
                header('Content-Disposition: attachment; filename="'.$_SESSION['file_name'].'"'); 
                header('Content-Length: '.filesize($_SESSION['path']));
                readfile($_SESSION['path']);
                unlink($_SESSION['path']);
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
    
    function deleterireki()
    {
        <?php 
        $rireki_ini_array = parse_ini_file("./ini/form.ini",true);            //操作履歴情報ファイル
        $delete_month = $rireki_ini_array["rireki_2"]["delete_month"];			
        ?>
        var checkmsg = '<?php echo $delete_month; ?>' + 'ヶ月以上前の操作履歴を削除します。よろしいでしょうか？';
        if(window.confirm(checkmsg))
        {
            return true;
        }
        else
        {
            return false;
        }    
    }
    
    //原価を修正した社員をリストに追加
    function editgenka(id)
    {
        if(inputcheck(id,7,4,0,2))
        {
            var judge = true;
            var datas = sessionStorage.getItem('datas');
            if(datas != null)
            {
                var dataArray = datas.split(",");
                for (var i = 0 ; i < dataArray.length ; i++ )
                {
                    if(dataArray[i] == $('#'+id).attr('name'))
                    {
                        judge = false;
                    }
                }
            }
            else
            {
                datas = $('#'+id).attr('name');
                judge = false;
            }
            
            if(judge)
            {
                datas += ','+$('#'+id).attr('name');
            }
            sessionStorage.setItem('datas',datas);
        }
    }
    
    //チェックリスト作成
    function madeChecklist()
    {
            var checkList = '';
            //テーブルの行数を取得
            var row = genkaList.rows.length;
            for (var i = 1 ; i < row ; i++ )
            {
                    checkList += 'genka_'+i+'~7~4~1~2,';
            }
            checkList = checkList.slice(0,-1);
            if(check(checkList))
            {
                    var msg = "以下の社員の原価を変更しますが、よろしいですか？\n"
                    var syain = "<?php echo $syain; ?>";
                    var syainArray = syain.split(",");
                    var datas = sessionStorage.getItem('datas');
                    var dataArray = datas.split(",");
                    //原価を修正された社員の名前をメッセージに追加
                    for (var i = 0 ; i < dataArray.length ; i++ )
                    {
                            for(var j = 0; j < syainArray.length; j++)
                            {
                                    if(dataArray[i] == syainArray[j])
                                    {
                                            msg += syainArray[j + 1]+'\n';
                                    }
                            }
                    }
                    sessionStorage.removeItem('datas');
                    if(window.confirm(msg))
                    {
                            sessionStorage.clear();
                            return true;
                    }
                    else
                    {
                            //入力値のリセット
                            for (var i = 1 ; i < row ; i++ )
                            {
                                    document.getElementById("genka_"+i).value = sessionStorage.getItem("genka_"+i);
                            }
                            return false;
                    }
            }
            else
            {
                    return false;
            }
    }
--></script>
</head>
<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$sql = array();
        $list = "";
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
        elseif($filename == "rireki_2")
        {
                    $sql = itemListSQL($_SESSION['list']);
                    $sql = SQLsetOrderby($_SESSION['list'],$filename,$sql);
                    $list = makeList_item($sql,$_SESSION['list']);        
        }
	elseif($filename != "KOUTEIINFO_2" && $filename != "SYUEKIHYO_2")
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
        
                //ソート条件
                if(!isset($_SESSION['list']['sort1']) && !isset($_SESSION['list']['sort2']))
                {
                    $orderbysql = "ORDER BY KOUTEIID ASC";
                }
                elseif($_SESSION['list']['sort1'] == 1 && $_SESSION['list']['sort2'] == 1)
                {
                    $orderbysql = "ORDER BY KOUTEIID ASC";
                }
                else
                {
                    $orderby = "ORDER BY ";
                    $orderbysql = "";
                    for($i = 1; $i <= 2; $i++)
                    {
                        if($_SESSION['list']['sort'.$i] != 1)
                        {
                            $orderby_column_name = $form_ini[$_SESSION['list']['sort'.$i]]['column'];
                            $orderbysql .= "".$orderby." ".$orderby_column_name." ".$_SESSION['list']['radiobutton'.$i]."";
                            $orderby = " , ";
                        }
                    }
                }
		$sql[0] = "SELECT * FROM kouteiinfo ".$where." ".$orderbysql.";";
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
	echo "<div style='clear:both;' id='container'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
        if($filename == 'GENKAINFO_2')
        {
                echo "<form name ='form' action='listJump.php' method='post' onsubmit = 'return madeChecklist();'>";
        }
        else if($filename != 'SYUEKIHYO_2')
        {
                echo '<form name ="form" action="listJump.php" method="post" 
				onsubmit = "return check(\''.$checkList.'\');">';
                echo "<table><tr><td>";
                echo "<fieldset><legend>検索条件</legend>";
                echo $form;
                echo "</fieldset>";
                echo "</td><td valign='bottom'>";
                echo '<input type="submit" name="serch" value = "表示" class="free" >';
                echo "</td></tr></table><br><br>";
        }
        
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
        if($filename != 'GENKAINFO_2')
        {
                echo "</form>";
        }
	if($isCSV == 1)
	{
		echo "<form action='download_csv.php' method='post'>";
                if($filename == 'SYUEKIHYO_2')
                {
                        echo "<div class = 'center'>";
                        echo "<input type ='submit' name = 'csv' class='button' value = 'csvファイル生成' style ='height:30px;' >";
                        echo "</div>";
                        echo "</form>";
                }
                else
                {
                        echo "<div class = 'left'>";
                        echo "<input type ='submit' name = 'csv' class='button' value = 'csvファイル生成' style ='height:30px;' >";
                        echo "</div>";
                        echo "</form>";
                }
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
        if($filename == 'GENKAINFO_2')
        {
		echo "<input type ='submit' name='setGenka' class='free' value = '設定' >";
                echo "</form>";
        }
    if($filename == "rireki_2")
    {
        echo "<form action='deleterirekiJump.php' method='post' onsubmit='return deleterireki();'>";
		echo "<div class = 'left'>";
		echo "<input type ='submit' name = 'deletesousarireki' class='free' value = 'データ削除'>";
		echo "</div>";
        echo "</form>";
    }
	echo "</div>";
	echo "</div>";
        echo "<form action='pageJump.php' method='post'>";
	echo makebutton();
	echo "</form>";
?>
</body>
<script language="JavaScript">
    window.onload = function() {
        var filename = '<?php if($filename == "ENDPJLIST_2" || $filename == "GENKAINFO_2"){ echo $filename; }else{ echo ""; } ?>';
        
        if(filename == 'ENDPJLIST_2'){
            //日付入力欄値
            document.getElementById("startdate").value = '<?php if(isset($_SESSION["list"]["startdate"])){ echo $_SESSION["list"]["startdate"]; }else{ echo ""; } ?>';
            document.getElementById("enddate").value = '<?php if(isset($_SESSION["list"]["enddate"])){ echo $_SESSION["list"]["enddate"]; }else{ echo ""; } ?>';
        }        
        
        if(filename == 'GENKAINFO_2')
        {
            //DBの原価の値を記憶
            var row = genkaList.rows.length;
            for (var i = 1 ; i < row ; i++ )
            {
                    sessionStorage.setItem('genka_'+i,document.getElementById('genka_'+i).value);
            }
        }
    }
</script>
</html>
