<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
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
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title1 = $form_ini[$filename]['title'];
	$title2 = '';
	$isMaster = false;
	$isReadOnly = false;
	if(isset($_SESSION['data']) == false)
	{
		$_SESSION['data'] = array();
	}
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '編集';
		$isReadOnly = true;
		break;
	case 1:
		$title2 = '編集';
		$isMaster = true;
		$isReadOnly = true;
		break;
	default:
		$title2 = '';
	}
	$maxover = -1;
	if(isset($_SESSION['max_over']))
	{
		$maxover = $_SESSION['max_over'];
	}
	$isexist = true;
	$checkResultarray = existID($_SESSION['list']['id']);
	if(count($checkResultarray) == 0)
	{
		$isexist = false;
	}
	$endmonth = endMonth();
	$endMonth = explode(',', $endmonth);
	$edalist = edaget();
	$koulist = kouget();
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
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
	
	var totalcount  = "<?php echo $maxover; ?>";
	var ischeckpass = true;
	
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
	
	function inputcheck(name,size,type,isnotnull){
		var judge =true;
		var str = document.getElementById(name).value;
		var len = 0;
		var str2 = escape(str);
		var filename = "<?php echo $filename; ?>";
		
		if(type==1)
		{
			for(i = 0; i < str2.length; i++, len++){
				if(str2.charAt(i) == "%"){
					if(str2.charAt(++i) == "u"){
						i += 3;
						len++;
					}
					else
					{
						judge=false;
					}
					i++;
				}
				else
				{
					judge=false;
				}
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('全角で入力してください');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==2)
		{
			for(i = 0; i < str2.length; i++, len++){
				if(str2.charAt(i) == "%"){
					if(str2.charAt(++i) == "u"){
						i += 3;
						len++;
						judge=false;
					}
				}
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('半角で入力してください');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==3)
		{
			if(str.match(/[^0-9A-Za-z]+/)) 
			{
				judge=false;
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('半角英数で入力してください');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		else if(type==4)
		{
			if(str.match(/[^0-9]+/)) 
			{
				judge=false;
			}
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
			else
			{
				window.alert('半角数字で入力してください');
				document.getElementById(name).style.backgroundColor = '#ff0000';
			}
		}
		if (size < strlen(str))
		{
				window.alert(size+'文字以内で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
			judge = false;
		}
		else
		{
			if(judge)
			{
				document.getElementById(name).style.backgroundColor = '';
			}
		}
		
		if(isnotnull == 1)
		{
//			if()
//			{
//				
//			}
//			else
//			{
				if(document.getElementById(name).value == '')
				{
					document.getElementById(name).style.backgroundColor = '#ff0000';
					judge = false;
					window.alert('値を入力してください');
				}
				else if(judge)
				{
					document.getElementById(name).style.backgroundColor = '';
				}
//			}
		}
		//重複チェック
		if(filename == 'EDABANINFO_2' && name == 'form_203_0')
		{
			var edaitem = "<?php echo $edalist; ?>".split(",");
			var str = document.getElementById(name).value;
			var numcnt = 0;
			while(numcnt < edaitem.length - 1)
			{
				if (str == edaitem[numcnt + 1])
				{
					judge = false;
					alert('この製番・案件名は登録済みです');
					document.getElementById(name).style.backgroundColor = '#ff0000';
					break;
				}
				numcnt = numcnt + 2;
			}
		}
		if(filename == 'KOUTEIINFO_2' && name == 'form_303_0')
		{
			var kouitem = "<?php echo $koulist; ?>".split(",");
			var str = document.getElementById(name).value;
			var numcnt = 0;
			while(numcnt < kouitem.length - 1)
			{
				if (str == kouitem[numcnt + 1])
				{
					judge = false;
					alert('この工程は登録済みです');
					document.getElementById(name).style.backgroundColor = '#ff0000';
					break;
				}
				numcnt = numcnt + 2;
			}
		}
		return judge;
	}
	function check(checkList,notnullcolumns,notnulltype)
	{
		var judge = true;
		var filename = "<?php echo $filename; ?>";
		
		if(ischeckpass == true)
		{
			if(filename == 'PROGRESSINFO_2')
			{
				//月次チェック
				var endmonth = "<?php echo $endmonth; ?>";
				var endArray = endmonth.split(",");
				var yr = document.getElementById("form_704_0").value;
				var mn = document.getElementById("form_704_1").value;
				var cnt = 0;
				
				while(cnt < endArray.length)
				{
					if(yr == endArray[cnt + 1] && mn == endArray[cnt + 2])
					{
						judge = false;
						break;
					}
					else
					{
						cnt = cnt + 3;
					}
				}
				if(document.getElementById('form_102_0').value == "")
				{
					document.getElementById('form_102_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_202_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_203_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_402_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_403_0').style.backgroundColor = '#ff0000';
					window.alert('プロジェクトを選択してください');
					judge = false;
				}
				else
				{
					document.getElementById('form_102_0').style.backgroundColor = '';
					document.getElementById('form_202_0').style.backgroundColor = '';
					document.getElementById('form_203_0').style.backgroundColor = '';
					document.getElementById('form_402_0').style.backgroundColor = '';
					document.getElementById('form_403_0').style.backgroundColor = '';
				}
				if(document.getElementById('form_302_0').value == "")
				{
					document.getElementById('form_302_0').style.backgroundColor = '#ff0000';
					document.getElementById('form_303_0').style.backgroundColor = '#ff0000';
					window.alert('工程を選択してください');
					judge = false;
				}
				else
				{
					document.getElementById('form_302_0').style.backgroundColor = '';
					document.getElementById('form_303_0').style.backgroundColor = '';
				}
				var checkListArray = checkList.split(",");
				var notNullArray = notnullcolumns.split(",");
				var notNullTypeArray = notnulltype.split(",");
				for (var i = 0 ; i < checkListArray.length ; i++ )
				{
					var param = checkListArray[i].split("~");
					if((param[0] == 'form_102_0') || (param[0] == 'form_202_0') || (param[0] == 'form_203_0') || (param[0] == 'form_302_0')|| (param[0] == 'form_303_0') || (param[0] == 'form_402_0') || (param[0] == 'form_403_0'))
					{
						continue;
					}
					if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
					{
						judge = false;
					}
				}
				for(var i = 0 ; i < notnullcolumns.length ; i++ )
				{
					var formelements = document.forms["edit"];
					for(var j = 0 ; j < formelements.length ; j++ )
					{
						if(formelements.elements[j].name.indexOf(notNullArray[i]) != -1)
						{
							var tagname = formelements.elements[j].tagName;
							if(tagname == 'SELECT')
							{
								var selectnum = formelements.elements[j].selectedIndex;
								if(formelements.elements[j].options[selectnum].value == "")
								{
									formelements.elements[j].style.backgroundColor = '#ff0000';
									judge = false;
									alert('値を選択して下さい');
								}
								else
								{
									formelements.elements[j].style.backgroundColor = '';
								}
							}
						}
					}
				}
			}
			else
			{
				var checkListArray = checkList.split(",");
				var notNullArray = notnullcolumns.split(",");
				var notNullTypeArray = notnulltype.split(",");
				for (var i = 0 ; i < checkListArray.length ; i++ )
				{
					var param = checkListArray[i].split("~");
					if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
					{
						judge = false;
					}
				}
				for(var i = 0 ; i < notnullcolumns.length ; i++ )
				{
					var formelements = document.forms["edit"];
					for(var j = 0 ; j < formelements.length ; j++ )
					{
						if(formelements.elements[j].name.indexOf(notNullArray[i]) != -1)
						{
							var tagname = formelements.elements[j].tagName;
							if(tagname == 'SELECT')
							{
								var selectnum = formelements.elements[j].selectedIndex;
								if(formelements.elements[j].options[selectnum].value == "")
								{
									formelements.elements[j].style.backgroundColor = '#ff0000';
									judge = false;
									alert('値を選択して下さい');
								}
								else
								{
									formelements.elements[j].style.backgroundColor = '';
								}
							}
						}
					}
				}
			}
		}
		ischeckpass = true;
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
	function AddTableRows(id){
		var table01 = document.getElementById('edit');
		var tr = table01.getElementsByTagName("TR");
		var tr_count = tr.length;
		var start = true;
		var start_count = 0;
		var end =true;
		var end_count = 0;
		totalcount++;
		for(count=0 ; count < tr_count ; count++)
		{
			if(tr[count].id==id){
				if(start)
				{
					start_count = count;
					start =false;
				}
			}
			else
			{
				if(start == false)
				{
					if(end)
					{
						end_count = count;
						end = false;
					}
				}
			}
		}
		if(end_count==0)
		{
			end_count=tr_count;
		}
		rows = new Array();
		cells = new Array();
		for(counter=0; counter<(end_count-start_count) ; counter++)
		{
			var row = table01.insertRow((end_count+counter));
			var cell1 = row.insertCell(0);
			var cell2 = row.insertCell(1);
			var cell3 = row.insertCell(2);
			var row2 = table01.rows[start_count+counter];
			var cell4 = row2.cells[2];
			var cell5 = row2.cells[1];
			cell3.innerHTML = cell4.innerHTML;
			cell2.innerHTML = cell5.innerHTML;
			
			var inp = cell3.getElementsByTagName("INPUT");
			for( var count = 0, len = inp.length; count < len; count++ ){
				var id = inp[count].id;
				var re = new RegExp(id,'g');
				cell3.innerHTML =cell3.innerHTML.replace(re,id+"_"+totalcount);
			}
			var inp2 = cell3.getElementsByTagName("SELECT");
			for( var count = 0, len = inp2.length; count < len; count++ ){
				var id = inp2[count].id;
				var re = new RegExp(id,'g');
				cell3.innerHTML =cell3.innerHTML.replace(re,id+"_"+totalcount);
			}
		}
		totalcount++;
	}

--></script>
</head>
<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	if($isexist)
	{
		$out_column ='';
		make_post($_SESSION['list']['id']);
		if(isset($_SESSION['data']))
		{
			$data = $_SESSION['data'];
		}
		else
		{
			$data = "";
		}
		//佐竹
		if ($filename == 'PROGRESSINFO_2' ){
			$judge = false;
			$id = $_SESSION['list']['id'];
			$con = dbconect();																									// db接続関数実行
			$sql[1] = "SELECT * FROM projectditealinfo,syaininfo,projectinfo, projectnuminfo, edabaninfo,progressinfo where projectinfo.1CODE = projectnuminfo.1CODE AND projectinfo.2CODE = edabaninfo.2CODE AND projectinfo.5code = projectditealinfo.5CODE AND syaininfo.4CODE = projectditealinfo.4CODE AND projectditealinfo.6CODE = progressinfo.6CODE AND progressinfo.7code = ".$id." ;";
			$result = $con->query($sql[1]) or ($judge = true);																	// クエリ発行
			if($judge)
			{
				error_log($con->error,0);
				$judge = false;
			}
			while($result_row = $result->fetch_array(MYSQLI_ASSOC))
			{
				$_SESSION['edit']['form_102_0'] = $result_row['PROJECTNUM'];
				$_SESSION['edit']['form_202_0'] = $result_row['EDABAN'];
				$_SESSION['edit']['form_203_0'] = $result_row['PJNAME'];
				$_SESSION['edit']['form_402_0'] = $result_row['STAFFID'];
				$_SESSION['edit']['form_403_0'] = $result_row['STAFFNAME'];
				$workday = explode('-',$result_row['SAGYOUDATE']);
				$_SESSION['edit']['form_704_0'] = $workday[0];
				$_SESSION['edit']['form_704_1'] = $workday[1];
				$_SESSION['edit']['form_704_2'] = $workday[2];
			}
			//麻野間 2017/11/29
			//月次済チェック
			$endjudge = true;
			for($i = 0; $i < count($endMonth); $i = $i + 3)
			{
				if(isset($endMonth[$i + 1],$endMonth[$i + 2]) && ($_SESSION['edit']['form_704_0'] == $endMonth[$i + 1]) && ($_SESSION['edit']['form_704_1'] == $endMonth[$i + 2]))
				{
					$endjudge = false;
				}
			}
		}
		
		//麻野間 2017/11/29
		$form = makeformEdit_set($_SESSION['edit'],$out_column,$isReadOnly,"edit",$data );
		$checkList = $_SESSION['check_column'];
		$notnullcolumns = $_SESSION['notnullcolumns'];
		$notnulltype = $_SESSION['notnulltype'];
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton($filename,'top');
		echo "</div>";
		echo "</form>";
		echo "<form action='listJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
		echo "<input type ='submit' value = '戻る' name = 'cancel' class = 'free'>";
		echo "</div></form>";
		echo "<div style='clear:both;'></div>";
		echo '<form name ="edit" action="listJump.php" method="post" enctype="multipart/form-data" 
					onsubmit = "return check(\''.$checkList.
					'\',\''.$notnullcolumns.
					'\',\''.$notnulltype.'\');">';
		echo "<div class = 'center'><br><br>";
		echo "<a class = 'title'>".$title1.$title2."</a>";
		echo "</div><br><br>";
		echo $form;
		echo "</tr></table>";
		echo "<div class = 'center'>";

		if($filename == "SAIINFO_2")
		{
			echo '<input type="submit" name = "kousinn" value = "登録" 
					class="free">';
			echo '<input type="submit" name = "clear" value = "クリア" 
					class = "free" onClick = "ischeckpass = false;">';
		}
		else
		{
			if($filename == "PROGRESSINFO_2"){
				if($endjudge)
				{
					echo '<input type="submit" name = "kousinn" value = "更新" 
							class="free">';
					echo '<input type="submit" name = "clear" value = "クリア" 
							class = "free" onClick = "ischeckpass = false;">';
					echo '<input type="submit" name = "delete" value = "削除" 
							class = "free" onClick = "ischeckpass = false;">';
					echo '<input type="submit" name = "insert" value = "別件登録" 
							class = "free"">';
				}
				else
				{
					echo '<input type="submit" name = "clear" value = "クリア" 
							class = "free" onClick = "ischeckpass = false;">';
					echo '<input type="submit" name = "insert" value = "別件登録" 
							class = "free"">';
				}
			}else{
					echo '<input type="submit" name = "kousinn" value = "更新" 
							class="free">';
					echo '<input type="submit" name = "clear" value = "クリア" 
							class = "free" onClick = "ischeckpass = false;">';
					echo '<input type="submit" name = "delete" value = "削除" 
							class = "free" onClick = "ischeckpass = false;">';
			
			}
		}

		echo "</form>";
		echo "</div>";
	}
	else
	{
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton($filename,'top');
		echo "</div>";
		echo "<div style='clear:both;'></div>";
		echo "</form>";
		echo "<br><br><div = class='center'>";
		echo "<a class = 'title'>".$title1.$title2."不可</a>";
		echo "</div><br><br>";
		echo "<div class ='center'>
				<a class ='error'>他の端末ですでにデータが削除されているため、".$title2."できません。</a>
				</div>";
		echo '<form action="listJump.php" method="post" >';
		echo "<div class = 'center'>";
		echo '<input type="submit" name = "cancel" value = "一覧に戻る" class = "free">';
		echo "</div>";
		echo "</form>";
	}
?>
</body>
</html>


