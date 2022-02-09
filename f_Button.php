<?php


/***************************************************************************
function makebutton()

戻り値	$con	mysql接続済みobject
***************************************************************************/

function makebutton(){
	
	//------------------------//
	//        初期設定        //
	//------------------------//
	$button_ini_array = parse_ini_file("./ini/button.ini",true);												// ボタン基本情報格納.iniファイル
	
	
	//------------------------//
	//          定数          //
	//------------------------//
	$mainbutton_num_array = explode(",",$button_ini_array['MENU_4']['set_button_center']);
	
	
	//------------------------//
	//          変数          //
	//------------------------//
	$total_count = 0;
	$button_html = "";
	
	//------------------------//
	//     ボタン作成処理     //
	//------------------------//
        
        $button_html = '<nav><ul>';
        $button_html .= '<li class="has-child"><a href="TOPexe.php?mainmenu="><span class="line">TOP</span></a></li>';
        foreach($mainbutton_num_array as $mainbutton_num)
        {
            $mainbutton_value = $button_ini_array[$mainbutton_num]['value'];    //ボタン文字
            $mainbutton_name = $button_ini_array[$mainbutton_num]['button_name'];   //ボタン名称  
            $name = str_replace('_button', '', $mainbutton_name);
            $subbutton_num_array = explode(",",$button_ini_array[$name]['set_button_center']); //サブボタンナンバー
            
            $button_html .= '<li class="has-child"><a><span class="line">'.$mainbutton_value.'</span></a>';
            $button_html .= '<ul>';
            foreach($subbutton_num_array as $subbutton_num)
            {
                $subbutton_value = $button_ini_array[$subbutton_num]['value'];    //ボタン文字
                $subbutton_name = $button_ini_array[$subbutton_num]['button_name'];   //ボタン名称
                
                $button_html .= '<li>';
                $button_html .= '<input type = "submit" class = "menu" name = "'.$subbutton_name.'" value = "'.$subbutton_value.'">';
                $button_html .= '</li>';
            }
            $button_html .= '</ul></li>';
        }
        $button_html .= '<li class="has-child"><a href="login.php"><span class="line">ログアウト</span></a></li>';
        $button_html .= '</ul></nav>';
	return ($button_html);
}

