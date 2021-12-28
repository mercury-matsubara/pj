<?php
    session_start();
    header('Content-type: text/html; charset=Shift_JIS'); 
    
    //5CODE���擾����
    $code_5 = $_GET["code"];
       
?>
<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<html>
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
<script language="JavaScript">
    	
	var idnum = -1;
	var dialog_w = 600;
	window.name = "Modal";																						//�@submit�{�^���ōX�Ɏq��ʊJ���Ȃ��悤��
	
	
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
</script>
</head>
<body>

<?php
    require_once ("f_DB.php");
    
    //DB�ڑ�
    $con = dbconect();
    
    //PJ�̏����擾����
    $sql = "SELECT * FROM projectinfo LEFT JOIN projectnuminfo USING (1CODE ) LEFT JOIN edabaninfo USING (2CODE ) where 5CODE = ".$code_5.";";
    $result = $con->query($sql) or ($judge = true);	
    
    while($result_row = $result->fetch_array(MYSQLI_ASSOC))
    {
        $pjcode = $result_row["PROJECTNUM"];        //�v���W�F�N�g�R�[�h
        $edaban = $result_row["EDABAN"];            //�}�ԃR�[�h
        $pjname = $result_row["PJNAME"];            //���ԁE�Č���
    }
    
    echo "<div class='pad' id='pop'>";
    echo "<br>";
    echo "<div class='center'>";
    echo "<a class = 'title'> PJ�ڍ� </a>";
    echo "</a></div>";
    echo "<br><br>";
    echo '<table name = "formInsert" id ="serch">';
    echo '<tr><td><a class = "itemname">�v���W�F�N�g�R�[�h</a></td><td><input type ="text" class="readOnly" value = "'.$pjcode.'" size = "20" disabled></td></tr>';
    echo '<tr><td><a class = "itemname">�}�ԃR�[�h</a></td><td><input type ="text" class="readOnly" value = "'.$edaban.'" size = "20" disabled></td></tr>';
    echo '<tr><td><a class = "itemname">���ԁE�Č���</a></td><td><input type ="text" class="readOnly" value = "'.$pjname.'" size = "120" disabled></td></tr>';
    echo '</table>';
    echo "<br><br>";
    
    //�o�^����Ă���Ј��̏������߂�
    $row_sql = "SELECT *FROM projectditealinfo left join syaininfo ON projectditealinfo.4CODE = syaininfo.4CODE where 5CODE = ".$code_5.";";
    $row_result = $con->query($row_sql) or ($judge = true);
    
    $result_num = $row_result->num_rows;
    
    echo '<div>�o�^�Ј����@'.$result_num.'��</div>';
    echo "<div class='listScroll'>";
    echo '<table border="1" class ="list">';
    echo '<thead><tr><th><a class ="head">�Ј���</a></th><th><a class ="head">���z</a></th><th><a class ="head">����</a></th></tr></thead>';

    $rowcounter = 0;
    while($row_list = $row_result->fetch_array(MYSQLI_ASSOC)){
        if(($rowcounter%2) == 1)
        {
            $stripe = "id = 'stripe'";
        }
        else
        {
            $stripe = "";
        }
        
        //�Ј���
        echo "<tr>";
        echo "<td ".$stripe."><a class='body'>".$row_list["STAFFNAME"]."</a></td>";
        echo "<td ".$stripe." align = 'right'><a class='body'>".number_format($row_list["DETALECHARGE"])."</a></td>";
        
        //�Ј����Ƃ̒莞���ԂƎc�Ǝ��Ԃ̍��v�擾
        $item_sql = "SELECT SUM(TEIZITIME),SUM(ZANGYOUTIME) FROM progressinfo WHERE 6CODE = ".$row_list["6CODE"].";";
        $item_result = $con->query($item_sql) or ($judge = true);																		// �N�G�����s
        $item_row = $item_result->fetch_array(MYSQLI_ASSOC);
        $sagyoutime = $item_row["SUM(TEIZITIME)"] + $item_row["SUM(ZANGYOUTIME)"]; 
        
        echo "<td ".$stripe."><a class='body'>".$sagyoutime."</a></td>";
        echo "</tr>";
        $rowcounter++;
    }
    echo '</table>';
    echo "</div>";
    echo "<div>";
    echo "<input type='button' class='free' value ='����' 
        onClick=\"closewindow();\" >";
    echo "</div>";
    echo "</div>";

?>

	
</body>
</html>