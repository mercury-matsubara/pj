<?php
    session_start();
    header('Content-type: text/html; charset=Shift_JIS'); 
    require_once("f_Construct.php");
    start();
?>
<html>
    <?php
        require_once ("f_Form.php");
        require_once ("f_Button.php");
	require_once ("f_DB.php");
        $_SESSION['post'] = $_SESSION['pre_post'];
        $_SESSION['pre_post'] = null;
        $form_ini = parse_ini_file('./ini/form.ini', true);
        $filename = $_SESSION['filename'];
        $title1 = $form_ini[$filename]['title'];
	$title2 = '完了';
        $startdate = '';
        $enddate = '';
        $errordata = array();
        $message = '';
        $message2 = '';
        $message3 = '';
        $list = '';
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
        </script>
    </head>
    <body>
        <?php
            if(isset($_SESSION['insert']))
            {
                    $_SESSION['teijicheck'] = $_SESSION['insert'];
                    $startdate = explode('-',$_SESSION['teijicheck']['startdate']);
                    //1桁の月の場合、0を表示しない
                    if(isset($startdate) && substr($startdate[1],0,1) == "0")
                    {
                            $startdate[1] = ltrim($startdate[1],"0");
                    }
                    $enddate = explode('-',$_SESSION['teijicheck']['enddate']);
                    //1桁の月の場合、0を表示しない
                    if(substr($enddate[1],0,1) == "0")
                    {
                            $enddate[1] = ltrim($enddate[1],"0");
                    }
            }
            else
            {
                    $_SESSION['teijicheck'] = array();
            }

            $errordata = teijicheck($_SESSION['teijicheck']);
            
            if(!empty($errordata))
            {
                $message = "<br><a style='color:red;'>修正が必要なデータがあります</a>";
                $message2 = "<a style='color:red;'>以下のデータを修正してください</a>";
                $message3 = "<table style='margin:auto;'><tr><td style='width:100px;;'>エラー理由</td><td>1.定時時間が7.75でない</td></tr>
                                          <tr><td></td><td>2.総作業時間が24時間を超えている</td></tr></table>";
                $list = make_teijicomplist($errordata);
            }
            else
            {
                $message = "<br><a>修正が必要なデータはありませんでした</a>";
            }
            
            echo "<div class = 'center'><br>";
            echo "<a class = 'title'>".$title1.$title2."</a>";
            echo "<br>";
            echo $message;
            echo "<br>";
            echo "<fieldset style='width:50%; margin:auto;'>";
            echo "<legend>チェック条件</legend>";
            echo "<table style='margin:auto;'><tbody>";
            if($_SESSION['teijicheck']['startdate'] == $_SESSION['teijicheck']['enddate'])
            {
                echo "<tr><td style='width:80px'>開始日付<br>終了日付</td>";
                echo "<td>全期間</td></tr>";
            }
            else
            {
                echo "<tr><td style='width:80px'>開始日付</td><td>".$startdate[0]."年".$startdate[1]."月".$startdate[2]."日</td></tr>";
                echo "<tr><td>終了日付</td><td>".$enddate[0]."年".$enddate[1]."月".$enddate[2]."日</td></tr>";
            }
            echo "<tr><td>社員</td><td>".$_SESSION['teijicheck']['syain'][0]."</td></tr>";
            for($i = 1 ; $i < count($_SESSION['teijicheck']['syain']) ; $i++)
            {
                echo "<tr><td></td><td>".$_SESSION['teijicheck']['syain'][$i]."</td></tr>";
            }
            echo "</table></tbody>";
            echo "</fieldset>";
            echo "<br>";
            echo $message2;
            echo $list;
            echo "<br>";
            echo $message3;
            echo "<form action='teijiJump.php' method='post'>";
            echo "<input type ='submit' name = 'back' class='free' value = '戻る'>";
            echo "</form>";
            echo "</div>";
            echo "<form action='pageJump.php' method='post'>";
            echo makebutton();
            echo "</form>";
        ?>
    </body>
</html>
