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
	switch ($form_ini[$main_table]['table_type'])
	{
	case 0:
		$title2 = '再登録';
		$isReadOnly = true;
		break;
	case 1:
		$title2 = '再登録';
		$isReadOnly = true;
		$isMaster = true;
		break;
	default:
		$title2 = '';
	}
	$maxover = -1;
	if(isset($_SESSION['max_over']))
	{
		$maxover = $_SESSION['max_over'];
	}
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./inputcheck.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src='./saiban.js'></script>
<script language="JavaScript"><!--
	history.forward();
	
	var totalcount  = "<?php echo $maxover; ?>";
	var isCancel = false;
	
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


//	function check(checkList,notnullcolumns,notnulltype)
//	{
//		var judge = true;
//		var checkListArray = checkList.split(",");
//		var notNullArray = notnullcolumns.split(",");
//		var notNullTypeArray = notnulltype.split(",");
//		for (var i = 0 ; i < checkListArray.length ; i++ )
//		{
//			var param = checkListArray[i].split("~");
//			if(!inputcheck(param[0],param[1],param[2],param[3],param[4]))
//			{
//				judge = false;
//			}
//		}
//		for(var i = 0 ; i < notnullcolumns.length ; i++ )
//		{
//			var formelements = document.forms["insert"];
//			for(var j = 0 ; j < formelements.length ; j++ )
//			{
//				if(formelements.elements[j].name.indexOf(notNullArray[i]) != -1)
//				{
//					var tagname = formelements.elements[j].tagName;
//					if(tagname == 'SELECT')
//					{
//						var selectnum = formelements.elements[j].selectedIndex;
//						if(formelements.elements[j].options[selectnum].value == "")
//						{
//							formelements.elements[j].style.backgroundColor = '#ff0000';
//							judge = false;
//							alert('値を選択して下さい');
//						}
//						else
//						{
//							formelements.elements[j].style.backgroundColor = '';
//						}
//					}
//				}
//			}
//		}
//		return judge;
//	}



	function check(checkList,notnullcolumns,notnulltype)
	{
		var judge = true;
		if(isCancel == false)
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
				var formelements = document.forms["insert"];
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
		return judge;
	}


	function popup_modal(GET)
	{
		var w = screen.availWidth;
		var h = screen.availHeight;
		w = (w * 0.8);
		h = (h * 0.8);
		url = 'Modal.php?tablenum='+GET+'&form=insert';
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
		var table01 = document.getElementById('insert');
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
	}

--></script>
</head>
<body>
<?php
	$html = "";
	$out_column ='';
	$judge = false;
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$errorinfo = existCheck($_SESSION['insert'],$main_table,1);
	if(count($errorinfo) == 1 && $errorinfo[0] == "")
	{
		if($filename == 'PROGRESSINFO_1')
		{
			
			$errorinfo = endCheck($_SESSION['insert']['form_704_0'],$_SESSION['insert']['form_704_1']);
		}
		if(count($errorinfo) == 1 && $errorinfo[0] == '')
		{
			$judge = true;
			$_SESSION['insert']['true'] = true;
			$_SESSION['pre_post'] = $_SESSION['post'];
		}
	}
	if($filename == 'PROGRESSINFO_2')
	{
		$errorinfo = endCheck($_SESSION['insert']['form_704_0'],$_SESSION['insert']['form_704_1']);
		if(count($errorinfo) == 1 && $errorinfo[0] == "" )
		{
			$judge = true;
			$_SESSION['insert']['true'] = true;
			$_SESSION['pre_post'] = $_SESSION['post'];
		}
		else
		{
			$judge = false;
		}
	}
	$form = makeformInsert_set($_SESSION['insert'],$errorinfo[0],$isReadOnly,"insert");
	$checkList = $_SESSION['check_column'];
	$notnullcolumns = $_SESSION['notnullcolumns'];
	$notnulltype = $_SESSION['notnulltype'];
	echo "<form action='pageJump.php' method='post'><div class = 'left'>";
	echo makebutton($filename,'top');
	echo "</div>";
	echo "</form>";
	echo "<form action='insertJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
	echo "<input type ='submit' value = '戻る' name = 'back' class = 'free'>";
	echo "</div></form>";
	echo "<div style='clear:both;'></div>";
	echo '<form name ="insert" action="insertJump.php" method="post" enctype="multipart/form-data" 
				onsubmit = "return check(\''.$checkList.
				'\',\''.$notnullcolumns.
				'\',\''.$notnulltype.'\');">';
	echo "<div class = 'center'><br><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "</div>";
	echo "<br><br>";
	if(isset($errorinfo[1]) && $errorinfo[1] != "")
	{
		echo "<a class = 'error'>".$errorinfo[1]."</a><br>";
	}
	for($i = 2 ; $i < count($errorinfo) ; $i++)
	{
		echo "<a class = 'error'>".$errorinfo[$i]."</a><br>";
	}
	echo $form;
	echo "</tr></table>";
	echo "<div class = 'center'>";
	echo '<input type="submit" name = "insert" value = "登録" class="free" ';
	if(isset($errorinfo[1]) && $errorinfo[1] != "")
	{
		echo 'disabled>';
	}
	else
	{
		echo '>';
	}
	echo '<input type="submit" name = "cancel" value = "クリア" class="free" onClick ="isCancel = true;">';
	echo "</form>";
	echo "</div>";
?>
<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("入力内容正常確認。\n情報登録しますがよろしいですか？" +
						"\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./insertComp.php";
			}
		}
	}
--></script>
</body>
</html>


