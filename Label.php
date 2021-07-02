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
*                                          ver 1.0.0  2014/05/14                                             *
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
	$label_ini = parse_ini_file('./ini/label.ini', true);
	
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$filename = $_SESSION['filename'];
	$tablenum = $form_ini[$filename]['use_maintable_num'];
	$code = "";
	$labelary = array();
	if(isset($_SESSION['list']['checkdata']))
	{
		$code = $_SESSION['list']['checkdata'];
	}
?>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS" />
<link rel="stylesheet" type="text/css" href="./list_css.css">
<link rel='stylesheet' href='./jquery-ui-1.10.3.custom.css' />
<script src='./inputcheck.js'></script>
<script src='./generate_date.js'></script>
<script src='./pulldown.js'></script>
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery-ui-1.10.3.custom.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./jquery.flatshadow.js'></script>
<script src='./button_size.js'></script>
<script language="JavaScript"><!--
	
	var idnum = -1;
	var dialog_w = 600;
	window.name = "Modal";																						//　submitボタンで更に子画面開かないように
	
	
	$(window).resize(function()
	{
		var w = $(window).width ();
		var h = $(window).height ();
		var d_w = $('div.center').width ();
		$('div.center').css({
			width : (w)
		});
		var t =  $('table.label').width();
		var t1 =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (t)/2;
		width_div1 = w/2 - (t1)/2;
		$('td#space_label_1').css({
			width : width_div
		});
		$('td#space_button_1').css({
			width : width_div1
		});
	});

	$(function()
	{
		$(".button").corner();
		$(".free").corner();
		$("a.title").flatshadow({
			fade: true
		});
		set_button_size();
		var w = $(window).width ();
		var h = $(window).height ();
		var d_w = $('div.center').width ();
		var t =  $('table.label').width();
		var t1 =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (t)/2;
		width_div1 = w/2 - (t1)/2;
		$('td#space_label_1').css({
			width : width_div
		});
		$('td#space_button_1').css({
			width : width_div1
		});
	});
	function closewindow()
	{
		close();
	}

--></script>
</head>
<body>

<?php
	if($code != '')
	{
		$labelary = make_label($code,$tablenum);
		$result_label = make_label_list($labelary[1],$labelary[2],$labelary[0]);
		$disabled = "";
		$arrayDoc = array();
		if($result_label[2] == 0)
		{
			$disabled = "disabled";
		}
		$jsonDstAdd = $labelary[0];
		$jsonDstName = $labelary[1];
		$jsonDstMail = $labelary[2];
		$jsonOrgAdd = $labelary[3];
		$jsonOrgName = $labelary[4];
		$jsonOrgMail = $labelary[5];
		for($i = 0 ; $i < count($jsonDstAdd) ; $i++) {
			$arrayDoc[$i] = array(
//				"pOrgMailAdd" => $jsonOrgMail[$i],
//				"pOrgAddress" => $jsonOrgAdd[$i],
//				"pOrgName" => $jsonOrgName[$i],
				"pOrgMailAdd" => mb_convert_encoding($label_ini['template']['master_postcd'], "UTF-8", "SJIS"),
				"pOrgAddress" => mb_convert_encoding($label_ini['template']['master_add'], "UTF-8", "SJIS"),
				"pOrgName" => mb_convert_encoding($label_ini['template']['master_name'], "UTF-8", "SJIS"),
				"pDstMailAdd" => mb_convert_encoding($jsonDstMail[$i], "UTF-8", "cp932"),
				"pDstAddress" => mb_convert_encoding($jsonDstAdd[$i], "UTF-8", "cp932"),
				"pDstName" => mb_convert_encoding($jsonDstName[$i], "UTF-8", "cp932"),
				"pTemplate" => "MailSendForm.lbx"
			);
		}
		$jsonStrphp = json_encode($arrayDoc);
		
		echo "<br>";
		echo "<div class='center'>";
		echo "<a class = 'title'> ラベル発行 </a>";
		echo "</a></div>";
		echo "<table id = 'space_button'><tr><td id = 'space_button_1'></td><td>";
		echo "<table id='button'><tr>";
		echo "<td><input type ='button' name = 'label_send' class='free' value = 'ラベル発行'".
				$disabled." onclick=\"DoPrint('')\"></td>";
		echo "<td><input type='button' class='free' value ='閉じる' 
				onClick=\"closewindow();\" ></td>";
		echo "</tr></table>";
		echo "</td></tr></table>";
		echo "<div class = 'center'>";
		echo $result_label[1];
		echo "</div>";
		if($labelary[6] != 0)
		{
			echo "<div class = 'center'>";
			echo "<a class = 'error' >".$labelary[6]."件のデータが削除されていました。</a>";
			echo "</div>";
		}
		echo "<table id = 'space_label'><tr><td id = 'space_label_1'></td><td>";
		echo $result_label[0];
		echo "</td></tr></table>";
		echo "<br>";
	}
?>

	
</body>
	<script language="javascript" type="text/javascript">
		var DATA_FOLDER = "C:\\Program Files\\Brother bPAC3 SDK\\Templates\\";
		//------------------------------------------------------------------------------
		//   Function name   :   DoPrint
		//   Description     :   Print, Preview Module
		//------------------------------------------------------------------------------
		function DoPrint(strExport)
		{
			var theForm = document.getElementById("myForm");
			var strPath = DATA_FOLDER + "MailSendForm.lbx" ;
			
			var objDoc = new ActiveXObject("bpac.Document");
			if(objDoc.Open(strPath) != false)
			{
				var j;
				var datelist = getJsonStr();
				
				// 印刷プリンタ決定
				objDoc.SetMediaByName("Brother QL-720NW", true);
//				objDoc.SetMediaByName("Brother QL-720NW wifi", true);
				
				for ( j = 0; j < datelist.length ; j++ ){
						// 印刷プロパティセット(印刷データ実体)
						objDoc.GetObject("objOrgMailAdd").Text = datelist[j].pOrgMailAdd.substr(0, 3) + "-" + datelist[j].pOrgMailAdd.substr( 3, 4);
						objDoc.GetObject("objOrgAddress").Text = datelist[j].pOrgAddress;
						objDoc.GetObject("objOrgName").Text = datelist[j].pOrgName;
						objDoc.GetObject("objDstMailAdd").Text = datelist[j].pDstMailAdd.substr(0, 3) + "-" + datelist[j].pDstMailAdd.substr( 3, 4);
						objDoc.GetObject("objDstAddress").Text = datelist[j].pDstAddress;
						objDoc.GetObject("objDstName").Text = datelist[j].pDstName;
						
						// 印刷キュー送信
						objDoc.StartPrint("", 0);
						// 印刷開始コマンド実行
						objDoc.PrintOut(1, 0);
				}
				// クローズ処理
				objDoc.Close();
				objDoc.EndPrint();
			}
	    }
	    
		function getJsonStr() {
			var json_string = '<?php print_r($jsonStrphp); ?>';
//			var json_string = '<?= $jsonStrphp ?>';
			var datelist = JSON.parse(json_string);
			return datelist;
		}
		
	</script>
</html>
