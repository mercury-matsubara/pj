<?php


/***************************************************************************
function makebutton()

�߂�l	$con	mysql�ڑ��ς�object
***************************************************************************/

function makebutton(){
	
	//------------------------//
	//        �����ݒ�        //
	//------------------------//
	$button_ini_array = parse_ini_file("./ini/button.ini",true);												// �{�^����{���i�[.ini�t�@�C��
	
	
	//------------------------//
	//          �萔          //
	//------------------------//
	$mainbutton_num_array = explode(",",$button_ini_array['MENU_4']['set_button_center']);
	
	
	//------------------------//
	//          �ϐ�          //
	//------------------------//
	$total_count = 0;
	$button_html = "";
	
	//------------------------//
	//     �{�^���쐬����     //
	//------------------------//
        
        $button_html = '<nav><ul>';
        $button_html .= '<li class="has-child"><a href="TOPexe.php?mainmenu="><span class="line">TOP</span></a></li>';
        foreach($mainbutton_num_array as $mainbutton_num)
        {
            $mainbutton_value = $button_ini_array[$mainbutton_num]['value'];    //�{�^������
            $mainbutton_name = $button_ini_array[$mainbutton_num]['button_name'];   //�{�^������  
            $name = str_replace('_button', '', $mainbutton_name);
            $subbutton_num_array = explode(",",$button_ini_array[$name]['set_button_center']); //�T�u�{�^���i���o�[
            
            $button_html .= '<li class="has-child"><a><span class="line">'.$mainbutton_value.'</span></a>';
            $button_html .= '<ul>';
            foreach($subbutton_num_array as $subbutton_num)
            {
                $subbutton_value = $button_ini_array[$subbutton_num]['value'];    //�{�^������
                $subbutton_name = $button_ini_array[$subbutton_num]['button_name'];   //�{�^������
                
                $button_html .= '<li>';
                $button_html .= '<input type = "submit" class = "menu" name = "'.$subbutton_name.'" value = "'.$subbutton_value.'">';
                $button_html .= '</li>';
            }
            $button_html .= '</ul></li>';
        }
        $button_html .= '<li class="has-child"><a href="login.php"><span class="line">���O�A�E�g</span></a></li>';
        $button_html .= '</ul></nav>';
	return ($button_html);
}

