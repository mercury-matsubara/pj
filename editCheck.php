<?php
	session_start(); 
	header('Expires:-1'); 
	header('Cache-Control:'); 
	header('Pragma:'); 
	header('Content-type: text/html; charset=Shift_JIS'); 
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
        if($filename != 'TOP_3')
        {
                $checkResultarray = existID($_SESSION['list']['id']);
                if(count($checkResultarray) == 0)
                {
                        $isexist = false;
                }
        }
	$endmonth = endMonth();
	$endMonth = explode(',', $endmonth);
?>
<head>
<title><?php echo $title1.$title2 ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script src="./progress.js"></script>
<script language="JavaScript"><!--
	history.forward();
	
	var totalcount  = "<?php echo $maxover; ?>";
	var iscansel = true;
	
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

	function check(checkList,notnullcolumns,notnulltype)
	{
		var judge = true;
		var filename = "<?php echo $filename; ?>";
		if(iscansel == true)
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
                        //2022-01-28 日付入力欄をカレンダー表示に変更　start ----->>
                        var id = formelements.elements[j].id;
                        if(id == 'form_704_0')
                        {
                            if(formelements.elements[j].value == "")
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
                        //2022-01-28 日付入力欄をカレンダー表示に変更　end -----<<                                                    
					}
				}
			}
			if(filename == 'PROGRESSINFO_2')
			{
                //2022-01-28 日付入力欄をカレンダー表示に変更　start ----->>
				//月次チェック
//				var endmonth = "<?php echo $endmonth; ?>";
//				var endArray = endmonth.split(",");
//				var yr = document.getElementById("form_704_0").value;
//				var mn = document.getElementById("form_704_1").value;
//				var cnt = 0;
//				
//				while(cnt < endArray.length)
//				{
//					if(yr == endArray[cnt + 1] && mn == endArray[I + 2])
//					{
//						judge = false;
//						break;
//					}
//					else
//					{
//						cnt = cnt + 3;
//					}
//				}
                //月次チェック
				var endmonth = "<?php echo $endmonth; ?>";
				var endArray = endmonth.split(",");
                var date = document.getElementById("form_704_0").value;
                var dateArray = date.split('-');
                var cnt = 0;
                while(cnt < endArray.length)
                {
                    if(endArray[cnt + 2] <= 9)
                    {
                        var endmonth = "0" + endArray[cnt + 2];
                    }
                    else
                    {
                        var endmonth = endArray[cnt + 2];
                    }
                    if(dateArray[0] == endArray[cnt + 1] && dateArray[1] == endmonth)
                    {
                        judge = false;
                        break;
                    }
                    else
                    {
                        cnt = cnt + 3;
                    }
                }
                //2022-01-28 日付入力欄をカレンダー表示に変更　end -----<<
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
                var filename = "<?php echo $filename; ?>";
                if(filename == 'TOP_3')
                {
                    var getArray = GET.split('_')
                    url = 'Modal.php?tablenum='+getArray[0]+'&form=edit&row='+getArray[1];
                }
                else
                {
                    url = 'Modal.php?tablenum='+GET+'&form=edit';
                }
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
	$judge = false;
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	if($isexist)
	{
                if($filename == 'PROGRESSINFO_2')
                {
                        //2022-01-27 日付入力欄をカレンダー表示に変更　start ----->>
//			$errorinfo = endCheck($_SESSION['edit']['form_704_0'],$_SESSION['edit']['form_704_1']);
                        $date = explode('-', $_SESSION['edit']['form_704_0']);
                        $errorinfo = endCheck($date[0], $date[1]);
                        //2022-01-26 日付入力欄をカレンダー表示に変更　end -----<<
                        if(count($errorinfo) == 1 && $errorinfo[0] == "" )
                        {
                                $judge = true;
                                $_SESSION['edit']['true'] = true;
                                $_SESSION['pre_post'] = $_SESSION['post'];
                        }
                }
                else 
                {
                        if($filename == 'TOP_3')
                        {
                                $list = makePROGRESSlist($_SESSION['edit']);
                                $_SESSION['edit'] = datasetting($_SESSION['edit']);
                        }
                        $errorinfo = existCheck($_SESSION['edit'],$main_table,2);
                        if(count($errorinfo) == 2 && $errorinfo[0] == "" && $errorinfo[1] == "")
                        {
                                $judge = true;
                                $_SESSION['edit']['true'] = true;
                                $_SESSION['pre_post'] = $_SESSION['post'];
                        }
                }
                if(isset($_SESSION['data']))
                {
                        $data = $_SESSION['data'];
                }
                else
                {
                        $data = "";
                }
                $form = makeformEdit_set($_SESSION['edit'],$errorinfo[0],$isReadOnly,"edit",$data );
		$checkList = $_SESSION['check_column'];
		$notnullcolumns = $_SESSION['notnullcolumns'];
		$notnulltype = $_SESSION['notnulltype'];
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton();
		echo "</div>";
		echo "</form>";
//		echo "<form action='listJump.php' method='post'><div class = 'left' style = 'HEIGHT : 30px'>";
//		echo "<input type ='submit' value = '戻る' name = 'cancel' class = 'free'>";
//		echo "</div></form>";
		echo "<div style='clear:both;'></div>";
                if($filename == 'TOP_3')
                {
                    echo '<form name ="edit" action="listJump.php" method="post" enctype="multipart/form-data" 
                                            onsubmit = "return PROGRESScheck();">';
                }
                else
                {
                    echo '<form name ="edit" action="listJump.php" method="post" enctype="multipart/form-data" 
                    			onsubmit = "return check(\''.$checkList.
                    			'\',\''.$notnullcolumns.
                    			'\',\''.$notnulltype.'\');">';
                }
		echo "<div class = 'center'>";
		echo "<a class = 'title'>".$title1.$title2."</a>";
		echo "</div><br><br>";
		if(isset($errorinfo[1]) && $errorinfo[1] != "")
		{
			echo "<a class = 'error'>".$errorinfo[1]."</a><br>";
		}
                if(isset($errorinfo))
                {
                    for($i = 2 ; $i < count($errorinfo) ; $i++)
                    {
                            echo "<a class = 'error'>".$errorinfo[$i]."</a><br>";
                    }
                }
		echo $form;
		echo "</tr></table>";
		echo "<div class = 'center'>";
		if($filename == 'TOP_3')
                {
                    echo $list;
                }
		if($filename == "SAIINFO_2")
		{
			echo '<input type="submit" name = "kousinn" value = "登録" 
					class="free" ';
		}
		else
		{
			echo '<input type="submit" name = "kousinn" value = "更新" 
					class = "free" ';
		}
		
		if(isset($errorinfo[1]) && $errorinfo[1] != "")
		{
			echo 'disabled>';
		}
		else
		{
			echo '>';
		}
                
                if($filename != 'TOP_3')
                {
            		echo '<input type="submit" name = "clear" value = "クリア" 
				class = "free" onClick = "iscansel = false;">';
                }
                echo '<input type="submit" name = "cancel" value = "戻る" 
				class = "free" onClick = "iscansel = false;">';
		echo "</form>";
		echo "</div>";
	}
	else
	{
		$judge = false;
		echo "<form action='pageJump.php' method='post'><div class='left'>";
		echo makebutton();
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
<script language="JavaScript"><!--

	window.onload = function(){
		var judge = '<?php echo $judge ?>';
		if(judge)
		{
			if(confirm("入力内容正常確認。\n情報更新しますがよろしいですか？" +
						"\n再度確認する場合は「キャンセル」ボタンを押してください。"))
			{
				location.href = "./editComp.php";
			}
		}
	}
--></script>
</body>
</html>


