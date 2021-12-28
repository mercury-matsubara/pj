<?php
	session_start();
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once("f_Construct.php");
	start();

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////


	require_once ("f_Button.php");
	require_once ("f_DB.php");
	require_once ("f_Form.php");
	require_once ("f_SQL.php");
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$isCSV = $form_ini[$filename]['isCSV'];
	$filename_array = explode('_',$filename);
	$filename_insert = $filename_array[0]."_1";
        if(isset($_SESSION['kensaku']))
        {
            $_SESSION['list'] = $_SESSION['kensaku'];
            unset($_SESSION['kensaku']);
        }
	if(isset($_SESSION['list']['limit']) == false)
	{
		$_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
	}
	$main_table = $form_ini[$filename]['use_maintable_num'];
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
<script language="JavaScript">    
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
	
    function select_checkbox(value,name,type)
    {
        const checkbox = document.form.checkbox;
        var tabledata = document.getElementById("endpjlist");
        var table = document.getElementById("select_pj");

        //�S�s�폜
        var rowLen = table.rows.length;
        for (var i = rowLen-1; i > 0; i--) {
            table.deleteRow(-1);
        }
        
        //�\�č쐬                
        table = document.getElementById("select_pj");
        var oncheck_num = 0;
        
        for(let i = 0; i < checkbox.length; i++)
        {
            if(checkbox[i].checked === true)
            {
                oncheck_num++;
                //�ǉ��̏���
                var rows = table.insertRow(-1);

                // -1�ŗ񖖔��ɒǉ��B�C���f�b�N�X�Ŏw��̈ʒu�ɒǉ����\
                var cell1 = rows.insertCell(-1);
                var cell2 = rows.insertCell(-1);
                var cell3 = rows.insertCell(-1);
                var cell4 = rows.insertCell(-1);
                
                cell1.innerHTML = oncheck_num;
                cell2.innerHTML = tabledata.rows[i].cells[1].textContent;
                cell3.innerHTML = tabledata.rows[i].cells[2].textContent;
                cell4.innerHTML = tabledata.rows[i].cells[3].textContent;
                
                //�w�i�F�ύX(�����s�̔w�i�F�𐅐F�ɂ���)
                if(oncheck_num%2 == 0)
                {
                    rows.style.backgroundColor="#B0E0E6";
                }
            }
        }
        
        //�I�����b�Z�[�W�o��
        document.getElementById("selectmsg").innerText = oncheck_num + "���I��";
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
			alert("�I������v���W�F�N�g��I�����Ă��������B");
			judge = false;
		}
		return judge;
	}
	
    function check_checkbox()
    {
        let pjstat = document.getElementById("pjstat_value").value;
        
        if(pjstat == "2")
        {
            alert("�I������v���W�F�N�g��I�����Ă��������B");
        }
        else
        {
            const checkbox = document.form.checkbox;
            var oncheckbox = 0;
            var jadge = false;
            var tabledata = document.getElementById("endpjlist");

            for(let i = 0; i < checkbox.length; i++)
            {
                if(checkbox[i].checked === true)
                {
                    oncheckbox++;
                }
            }

            //PJ���M���쐬
            const code_array = new Array(oncheckbox);        
            const pjname_array = new Array(oncheckbox);
            const pjcode_array = new Array(oncheckbox);
            const edabancode_array = new Array(oncheckbox);
            var count = 0;

            for(let i = 0; i < checkbox.length; i++)
            {
                if(checkbox[i].checked === true)
                {
                    code_array[count] = checkbox[i].value;
                    pjcode_array[count] = tabledata.rows[i].cells[1].textContent;
                    edabancode_array[count] = tabledata.rows[i].cells[2].textContent;
                    pjname_array[count] = tabledata.rows[i].cells[3].textContent;
                    count++;
                }
            }

            console.log(code_array);
            console.log(pjcode_array);
            console.log(edabancode_array);
            console.log(pjname_array);
            if(oncheckbox === 0)
            {
                alert("�I������v���W�F�N�g��I�����Ă��������B");
                jadge = false;
            }
            else
            {
                    var form = document.createElement('form');
                    var request;
                    var end;
                    form.method = 'POST';
                    form.action = 'pjendJump.php';

                    //5CODE���M
                    request = document.createElement('input');
                    request.type = 'hidden'; //���̓t�H�[�����\������Ȃ��悤��
                    request.name = '5CODE';
                    request.value = code_array;
                    form.appendChild(request);

                    //PJ�I��
                    end = document.createElement('input');
                    end.type = 'hidden'; //���̓t�H�[�����\������Ȃ��悤��
                    end.name = 'end';
                    end.value = 'PJ�I��';                
                    form.appendChild(end);

                    //�v���W�F�N�g�R�[�h
                    var pjcode = document.createElement('input');
                    pjcode.type = 'hidden'; //���̓t�H�[�����\������Ȃ��悤��
                    pjcode.name = 'pjcode';
                    pjcode.value = pjcode_array;
                    form.appendChild(pjcode);

                    //�}�ԃR�[�h
                    var edabancode = document.createElement('input');
                    edabancode.type = 'hidden'; //���̓t�H�[�����\������Ȃ��悤��
                    edabancode.name = 'edabancode';
                    edabancode.value = edabancode_array;
                    form.appendChild(edabancode);

                    //�v���W�F�N�g���R�[�h
                    var pjname = document.createElement('input');
                    pjname.type = 'hidden'; //���̓t�H�[�����\������Ȃ��悤��
                    pjname.name = 'pjname';
                    pjname.value = pjname_array;
                    form.appendChild(pjname);

                    //�t�H�[�����M
                    document.body.appendChild(form);

                    form.submit();

                jadge = true;
            }
            return jadge;
        }
    }
    
    function onpjstat()
    {
        let elements = document.getElementsByName("pjstat");
        
        if(elements.item(0).checked)
        {            
            //���t���͗����͕s��
            document.getElementById("startdate").disabled = true;
            document.getElementById("enddate").disabled = true;
            
            //���t���͗��w�i�F�ύX
            document.getElementById("startdate").style.backgroundColor = '#c0c0c0';
            document.getElementById("enddate").style.backgroundColor = '#c0c0c0';
            
            //���t���͗��l������
            document.getElementById("startdate").value = "";
            document.getElementById("enddate").value = "";
        }
        
        if(elements.item(1).checked)
        {
            //���t���͗����͉�
            document.getElementById("startdate").disabled = false;
            document.getElementById("enddate").disabled = false;
            
            //���t���͗��w�i�F�ύX
            document.getElementById("startdate").style.backgroundColor = '#ffffff';
            document.getElementById("enddate").style.backgroundColor = '#ffffff';
        }
    }
</script>
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
        $list = makeList_check($sql,$_SESSION['list'],$main_table);
	$columns = $form_ini[$filename]['sech_form_num'];
	$form = makeformModal_set($_SESSION['list'],'',"form",$columns);
	$columns = $form_ini[$filename]['insert_form_tablenum'];
	//$form_drop = makeformModal_set($damy_array,'readOnly','drop',$columns);
	$form_drop = make_selectlist();
	$checkList = $_SESSION['check_column'];
	echo "<div style='clear:both;'></div>";
	echo "<div class = 'center'><br>";
	echo "<a class = 'title'>".$title1.$title2."</a>";
	echo "<br>";
	echo "</div>";
	echo "<div class = 'pad' >";
	echo '<form name ="form" action="pjendJump.php" method="post" >';
	echo "<table><tr><td>";
	echo "<fieldset><legend>��������</legend>";
	echo $form;
	echo "</fieldset>";
	echo "</td><td valign='bottom'>";
	echo '<input type="submit" name="serch" value = "�\��" class="free" >';
	echo "</td></tr></table>";
	echo $list;
	echo "</form>";
	//echo '<form name ="drop" id = "drop" action="pjendJump.php" method="post" onsubmit ="return checkonradio();">';
    //echo '<form name ="drop" id = "drop" action="pjendJump.php" method="post" onsubmit ="return check_checkbox();">';
    if(isset($_SESSION["list"]["pjstat"]))
    {
        $pjstat = $_SESSION["list"]["pjstat"];
    }
    else
    {
        $pjstat = "1";
    }
    echo "<input type = 'hidden' name = 'pjstat_value' id = 'pjstat_value' value ='".$pjstat."'>";
    echo '<table><tr><td><div id="selectmsg">0���I��</div></td><td><input type="submit" name="end" class="button" value="�o�i�I��" onclick="check_checkbox();"></td></tr></table>';
    echo "<div class='listScroll'>";
	echo $form_drop ;
    echo "</div>";

    echo "</div>";
    echo "<form action='pageJump.php' method='post'>";
    echo makebutton();
    echo "</form>";
?>

</body>
<script language="JavaScript">
    window.onload = function() {
        var pjstat = '<?php if(isset($_SESSION["list"]["pjstat"])){ echo $_SESSION["list"]["pjstat"];}else{ echo "1"; } ?>';
        let elements = document.getElementsByName("pjstat");
        
        if(pjstat == 2)
        {
            elements.item(1).checked = true;
            //���t���͗����͉�
            document.getElementById("startdate").disabled = false;
            document.getElementById("enddate").disabled = false;
            
            //���t���͗��w�i�F�ύX
            document.getElementById("startdate").style.backgroundColor = '#ffffff';
            document.getElementById("enddate").style.backgroundColor = '#ffffff';

            //���t���͗��l
            document.getElementById("startdate").value = '<?php if(isset($_SESSION["list"]["startdate"])){ echo $_SESSION["list"]["startdate"]; }else{ echo ""; } ?>';
            document.getElementById("enddate").value = '<?php if(isset($_SESSION["list"]["enddate"])){ echo $_SESSION["list"]["enddate"]; }else{ echo ""; } ?>';
        }
        else
        {
            elements.item(0).checked = true;
               
            //���t���͗����͕s��
            document.getElementById("startdate").disabled = true;
            document.getElementById("enddate").disabled = true;
            
            //���t���͗��w�i�F�ύX
            document.getElementById("startdate").style.backgroundColor = '#c0c0c0';
            document.getElementById("enddate").style.backgroundColor = '#c0c0c0';
            
            //���t���͗��l
            document.getElementById("startdate").value = '<?php if(isset($_SESSION["list"]["startdate"])){ echo $_SESSION["list"]["startdate"]; }else{ echo ""; } ?>';
            document.getElementById("enddate").value = '<?php if(isset($_SESSION["list"]["enddate"])){ echo $_SESSION["list"]["enddate"]; }else{ echo ""; } ?>';
        }
    }
    
    function syousai_open(code5)
    {
		var w = screen.availWidth;
		var h = screen.availHeight;
		w = (w * 0.8);
		h = (h * 0.8);
		url = 'pj_syousai.php?code='+code5+'';
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
</script>
</html>
