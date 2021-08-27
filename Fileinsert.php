<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	require_once("f_Construct.php");
	start();
	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	
	if(isset($_POST))
	{
		$_SESSION['insert'] = $_POST;
	}
	else
	{
		$_SESSION['insert'] = array();
	}
	
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
		$isReadOnly = true;
		break;
	case 1:
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
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.0.0  2016/11/18                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
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

	function check()
	{
		var judge = true;
		if(document.getElementsByName('inpath')[(document.getElementsByName('inpath').length-1)].value == "")
		{
			document.getElementsByName('inpath')[(document.getElementsByName('inpath').length-1)].style.backgroundColor = '#ff0000';
			judge = false;
			alert('ファイルを選択して下さい');
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
		totalcount++;
	}

--></script>
</head>
<body>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$out_column ='';
	$checkList = "";
	$notnullcolumns = "";
	$notnulltype = "";
	$columns = $form_ini[$filename]['result_num'];
	$columns_array = explode(',',$columns);
	echo "<table border='0' WIDTH=100%><tr>";
	echo "<form action='pageJump.php' method='post'><div>";
	echo makebutton($filename,'top');
	echo "</div></form>";
	echo "<form action='FileinsertJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
	echo "<input type ='submit' value = '戻る' name = 'back' class = 'free'>";
	echo "</div></form>";
	echo "<div style='clear:both;'></div>";
	echo "</tr></table>";
	echo '<form name ="fileinsert" action="FileinsertJump.php" method="post" enctype="multipart/form-data" 
				onsubmit = "return check();">';
	echo "<div class = 'center'><br><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "</div><br><br>";
	echo "<div class = 'center'>";
	echo '<input type="file" name="inpath" size="300" class="free"><br><br>';
	echo '<input type="submit" name = "fileinsert" value = "取込" class="free">';
	echo "</form>";
	
	echo "<br><br><br>";
	echo "<FONT color='red'>ユーザ単位の進捗情報が取り込みできます。取り込み情報は</FONT><br><br>";
        //----2018/01/25 項番32 asanoma 取込形式修正 start ----->>
//	echo "<FONT color='red'>1行目に　ユーザー番号</FONT><br>";
//	echo "<FONT color='red'>2行目以降に　日付(yyyy/mm/dd)、PJコード、枝番、工程番号、定時時間、残業時間<br>をCSV形式で作成してください。</FONT><br><br>";
        echo "<FONT color='red'>社員番号、日付(yyyy/mm/dd)、PJコード、枝番、工程番号、定時時間、残業時間<br>をCSV形式で作成してください。</FONT><br><br>";
        //----2018/01/25 項番32 asanoma 取込形式修正 end -----<<
        echo "<FONT color='red'>同一日付にすでに登録データが存在する場合は存在しているデータは破棄され取り込みデータが正しいデータとして登録されます。</FONT><br><br>";
	echo "</div>";
?>
</body>
</html>
