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
	$title2 = '����';
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
                    //1���̌��̏ꍇ�A0��\�����Ȃ�
                    if(isset($startdate) && substr($startdate[1],0,1) == "0")
                    {
                            $startdate[1] = ltrim($startdate[1],"0");
                    }
                    $enddate = explode('-',$_SESSION['teijicheck']['enddate']);
                    //1���̌��̏ꍇ�A0��\�����Ȃ�
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
                $message = "<br><a style='color:red;'>�C�����K�v�ȃf�[�^������܂�</a>";
                $message2 = "<a style='color:red;'>�ȉ��̃f�[�^���C�����Ă�������</a>";
                $message3 = "<table style='margin:auto;'><tr><td style='width:100px;;'>�G���[���R</td><td>1.�莞���Ԃ�7.75�łȂ�</td></tr>
                                          <tr><td></td><td>2.����Ǝ��Ԃ�24���Ԃ𒴂��Ă���</td></tr></table>";
                $list = make_teijicomplist($errordata);
            }
            else
            {
                $message = "<br><a>�C�����K�v�ȃf�[�^�͂���܂���ł���</a>";
            }
            
            echo "<div class = 'center'><br>";
            echo "<a class = 'title'>".$title1.$title2."</a>";
            echo "<br>";
            echo $message;
            echo "<br>";
            echo "<fieldset style='width:50%; margin:auto;'>";
            echo "<legend>�`�F�b�N����</legend>";
            echo "<table style='margin:auto;'><tbody>";
            if($_SESSION['teijicheck']['startdate'] == $_SESSION['teijicheck']['enddate'])
            {
                echo "<tr><td style='width:80px'>�J�n���t<br>�I�����t</td>";
                echo "<td>�S����</td></tr>";
            }
            else
            {
                echo "<tr><td style='width:80px'>�J�n���t</td><td>".$startdate[0]."�N".$startdate[1]."��".$startdate[2]."��</td></tr>";
                echo "<tr><td>�I�����t</td><td>".$enddate[0]."�N".$enddate[1]."��".$enddate[2]."��</td></tr>";
            }
            echo "<tr><td>�Ј�</td><td>".$_SESSION['teijicheck']['syain'][0]."</td></tr>";
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
            echo "<input type ='submit' name = 'back' class='free' value = '�߂�'>";
            echo "</form>";
            echo "</div>";
            echo "<form action='pageJump.php' method='post'>";
            echo makebutton();
            echo "</form>";
        ?>
    </body>
</html>
