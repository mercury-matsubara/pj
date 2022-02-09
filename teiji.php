<?php
    session_start();
    header('Content-type: text/html; charset=Shift_JIS'); 
    require_once("f_Construct.php");
    require_once ("f_Form.php");
    require_once ("f_Button.php");
    require_once ("f_SQL.php");
    start();
    $_SESSION['post'] = $_SESSION['pre_post'];
    $_SESSION['pre_post'] = null;
?>
<html>
    <?php
        $form_ini = parse_ini_file('./ini/form.ini', true);
        $filename = $_SESSION['filename'];
        $title1 = $form_ini[$filename]['title'];
        $title2 = '処理';
        $main_table = $form_ini[$filename]['use_maintable_num'];
        if(isset($_SESSION['list']['limit']) == false)
	{
		$_SESSION['list']['limitstart'] = 0 ;
		$_SESSION['list']['limit'] = ' LIMIT '.$_SESSION['list']['limitstart'].','
											.$form_ini[$filename]['limit'];
	}
        $start_value = '';
        $end_value = '';
    ?>
    <head>
        <title><?php echo $title1.$title2 ; ?></title>
        <link rel="stylesheet" type="text/css" href="./list_css.css">
        <script src='./jquery-1.8.3.min.js'></script>
        <script src='./jquery.corner.js'></script>
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
            
            //「全選択」ボタン
            const checkbox1 = document.getElementsByName("checkbox[]");
            function checkAll() {
                for(i = 0; i < checkbox1.length; i++) {
                  checkbox1[i].checked = true;
                }
            }
            
            //「選択解除」ボタン
            function clearAll() {
                for(i = 0; i < checkbox1.length; i++) 
                {
                  checkbox1[i].checked = false;
                }
            }
            
            //検索条件のチェック
            function check() {
                var judge = false;
                for(i = 0; i < checkbox1.length; i++) 
                {
                    if(checkbox1[i].checked)
                    {
                        judge = true;
                    }
                }
                
                if(judge == false)
                {
                    alert("社員が未選択です。\n社員を選択してください。")
                }
                return judge;
            }
        </script>
    </head>
    <body>
        <?php
            $sql = array();
            if(!isset($_SESSION['teijicheck']))
            {
                    $_SESSION['teijicheck'] = array();
            }
            $sql = joinSelectSQL($_SESSION['teijicheck'],$main_table);
            $sql = SQLsetOrderby($_SESSION['teijicheck'],$filename,$sql);
            $list = makeList_check($sql,$_SESSION['teijicheck'],$main_table);
            echo "<div class = 'center'><br>";
            echo "<a class = 'title'>".$title1.$title2."</a>";
            echo "<form action='teijiJump.php' method='post' onsubmit='return check()'>";
            echo "<br>";
            echo "<table style='margin:auto;'><tbody>";
            echo "<tr><td style='width:80px'>開始日付</td><td><input type='date' id='startdate' name='startdate'></td></tr>";
            echo "<tr><td>終了日付</td><td><input type='date' id='enddate' name='enddate'></td></tr>";
            echo "<tr><td style='vertical-align:top;'>社員</td><td>";
            
            if($list != false)
            {
                echo $list."</td>";
                echo "<td style='vertical-align:bottom;'><input type ='button' class='free' value = '全選択' onClick = 'checkAll()'><br>";
                echo "<input type ='button' class='free' value = '選択解除' onClick = 'clearAll()'></td></tr>";
                echo "</table></tbody>";
                echo "<input type ='submit' name = 'teijicheck' class = 'free' value = '実行'>";
            }
            else
            {
                echo "社員がいません</td>";
                echo "<td style='vertical-align:bottom;'><input type ='button' class='free' value = '全選択' onClick = 'checkAll()' disabled><br>";
                echo "<input type ='button' class='free' value = '選択解除' onClick = 'clearAll()' disabled></td></tr>";
                echo "</table></tbody>";
                echo "<input type ='submit' name = 'teijicheck' class = 'free' value = '実行' disabled>";
            }
            
            echo "</form>";
            echo "</div>";
            echo "<form action='pageJump.php' method='post'>";
            echo makebutton();
            echo "</form>";
        ?>
    </body>
    <script language="JavaScript">
            window.onload = function() {
                
                var startdate = '<?php if(isset($_SESSION["teijicheck"]["startdate"])){ echo $_SESSION["teijicheck"]["startdate"]; }else{ echo ""; } ?>';
                var enddate = '<?php if(isset($_SESSION["teijicheck"]["enddate"])){ echo $_SESSION["teijicheck"]["enddate"]; }else{ echo ""; } ?>';
                
                //カレンダーの初期値を今日の日付にする
                var today = new Date();
                today.setDate(today.getDate());
                var yyyy = today.getFullYear();
                var mm = ("0"+(today.getMonth()+1)).slice(-2);
                var dd = ("0"+today.getDate()).slice(-2);
                
                if(startdate != "")
                {
                    //戻るボタンで戻ってきた場合
                    document.getElementById("startdate").value = startdate;
                }
                else
                {
                    document.getElementById("startdate").value = yyyy+'-'+mm+'-'+dd;
                }
                if(enddate != "")
                {
                    //戻るボタンで戻ってきた場合
                    document.getElementById("enddate").value = enddate;
                }
                else
                {
                    document.getElementById("enddate").value = yyyy+'-'+mm+'-'+dd;
                }
            }
    </script>
</html>

