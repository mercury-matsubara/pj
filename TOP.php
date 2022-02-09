<?php
	session_start();
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	start();
?>
<html>
<?php
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
	$form_ini = parse_ini_file('./ini/form.ini', true);
        $filename = 'TOP_4';
	$title = $form_ini[$filename]['title'];
?>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel="stylesheet" type="text/css" href="./list_css.css">
<link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
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
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
		set_button_size();
	});
        
	$(window).resize(function()
	{
		var w = $(window).width ();
		var width_center =  $('table#button').width();
		var width_div = 0;
		width_div = w/2 - (width_center)/2;
		$('div#space_button').css({
			width : width_div
		});
	});
        
        //工数登録画面に遷移
        function openinsert ()
        {
                var yearmonth;
                var month = $(".month").text();
                //2020/X
                month = month.replace(/[^0-9]/g, '/');
                month = month.slice(2,-3);
                yearmonth = month.split("/");
                var day = $(".dayof",this).text();
                var date = yearmonth[0] +"/" + ("00"+ yearmonth[1]).slice(-2) + "/" + ("00" + day).slice(-2);
                location.href = "./pageJump.php?date="+date;
        }
        
        //コピー先選択ダイアログ表示
        function showdialog(date)
        {
                sessionStorage.setItem('date',date);
                setdate();
                document.getElementById('dgl').showModal()
        }
        
        function setdate() 
        {        
                //カレンダーの初期値を今日の日付にする
                var today = new Date();
                today.setDate(today.getDate());
                var yyyy = today.getFullYear();
                var mm = ("0"+(today.getMonth()+1)).slice(-2);
                var dd = ("0"+today.getDate()).slice(-2);
                
                document.getElementById("startdate").value = yyyy+'-'+mm+'-'+dd;
                document.getElementById("startdate").max = yyyy+'-'+mm+'-'+dd;
                document.getElementById("enddate").value = yyyy+'-'+mm+'-'+dd;
                document.getElementById("enddate").max = yyyy+'-'+mm+'-'+dd;
        }
        
        function copy()
        {
                jQuery.ajax({
                    type: 'post',
                    url: 'TOPexe.php',
                    data: {'copydate' : sessionStorage.getItem('date'),
                        'pasteStart': document.getElementById("startdate").value,
                        'pasteEnd': document.getElementById("enddate").value},
                    success: function(){ 
                        sessionStorage.removeItem('date');
                        location.href = "./TOP.php";
                    }

                });
        }
--></script>
</head>
<body>
<?php
	require_once("f_Button.php");        
	require_once("f_DB.php");	
        $calender_html = "";
        // カレンダー作成
        $calendar = makeCalendar();
        foreach ($calendar as $week) {
            // カレンダー表示
            $calender_html .= $week;
        }
        
        $min = lastEndMonth();
        
        echo "<form action='TOPexe.php' method='post'>";
	echo "<center><br>";
	echo "<a class='title'>".$title."</a><br>";
	echo "</center>";
        echo "<div class='container'>";
        echo "<div class='month'><h3><input type='submit' value='&lt;' name='prev' class='monthbtn'>"
                . "<a>".$_SESSION['month']."</a>"
                . "<input type='submit' value='&gt;' name='next' class='monthbtn'></h3></div>";
        echo "</form>";
        echo "<form action='pageJump.php' method='post'>";
        echo "<input type ='submit' value = 'ファイル取込' class = 'free' name = 'PROGRESSINFO_6_button_T' style='float: right;margin-top: 18px;'>";
        echo "<table class='calendar'>";
        echo "    <tr class=youbi>";
        echo "        <th class=youbiColor>日</th>";
        echo "        <th class=youbiColor>月</th>";
        echo "        <th class=youbiColor>火</th>";
        echo "        <th class=youbiColor>水</th>";
        echo "        <th class=youbiColor>木</th>";
        echo "        <th class=youbiColor>金</th>";
        echo "        <th class=youbiColor>土</th>";
        echo "    </tr>";
        echo $calender_html;
        echo "</table>";
        echo "</div>";
        //コピー先選択ダイアログ
        echo "<dialog id='dgl'>
            <p class='dlgtitle'>コピー先選択</p>
            <table class='dlgtable'>
                <tr><td style='width:40%;'>開始日付</td><td><input type='date' id='startdate' min='".$min."'></td></tr>
                <tr><td style='width:40%;'>終了日付</td><td><input type='date' id='enddate' min='".$min."'></td></tr>
            </table>
            <input type='button' class='dlgbtn' value='戻る' style='margin: 10px 0 0 25px;' onclick='document.getElementById(".'"dgl"'.").close()'>
            <input type='button' class='dlgbtn' value='登録' style='margin: 10px 25px 0 0; float: right;' onclick='copy()'>
            </dialog>";
	echo "<br><br>";
	echo makebutton();
	echo "</form>";
?>
</body>
</html>


