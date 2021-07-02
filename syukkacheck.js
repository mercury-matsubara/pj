

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////


function syukkacheck()
{
	var judge =true;
	var count = 1;
	var id = "";
	var value_obj = "";
	var name = "";
	var num = "";
	var value_num = 0;
	var zaiko_num = 0;
	var value_4CODE = 0;
	var isnum = true;
	while(1)
	{
		id = "syukka_"+count;
		var obj = document.getElementById(id);
		isnum = true;
//		alert(id);
		if(!obj)
		{
//			alert('break');
			break;
		}
		value_obj = obj.value;
		if(value_obj.match(/[^0-9]+/)) 
		{
			window.alert('数値を入力してください。');
			obj.style.backgroundColor = '#ff0000';
			judge = false;
			isnum = false;
		}
		value_num = Number(value_obj) ;
		name = obj.name;
		var num_array = name.split('_');
		num = num_array[2];
		zaiko_num = Number(num);
		if(isnum == true)
		{
			if(value_obj != "")
			{
				if(value_obj != '0')
				{
					if(value_obj.charAt(0)  == '0')
					{
						window.alert('正しい値を入力してください。');
						obj.style.backgroundColor = '#ff0000';
						judge = false;
					}
					else if(num != "-1" && zaiko_num < value_num)
					{
						window.alert('正しい値を入力してください。');
						obj.style.backgroundColor = '#ff0000';
						judge = false;
					}
					else
					{
						obj.style.backgroundColor  = '';
					}
				}
				else
				{
					obj.style.backgroundColor  = '';
				}
			}
			else
			{
				obj.style.backgroundColor  = '';
			}
		}
		count++;
//		if(count == 10)
//		{
//			break;
//		}
	}
	obj = document.getElementsByName('4CODE')[0];
	value_4CODE = obj.value;
	obj = document.getElementById('check_4CODE');
	if(value_4CODE == "")
	{
		window.alert('現場を選択してください。');
		judge = false;
	}
	else if(value_4CODE != obj.value)
	{
		window.alert('現場を選択しましたら表示ボタンを押下してください。');
		judge = false;
	}
	obj = document.getElementById('form_start_0');
	if(obj.value == "")
	{
		window.alert('年度を選択してください。');
		obj.style.backgroundColor = '#ff0000';
		judge = false;
	}
	else
	{
		obj.style.backgroundColor  = '';
	}
	obj = document.getElementById('form_start_1');
	if(obj.value == "")
	{
		window.alert('月を選択してください。');
		obj.style.backgroundColor = '#ff0000';
		judge = false;
	}
	else
	{
		obj.style.backgroundColor  = '';
	}
	obj = document.getElementById('form_start_2');
	if(obj.value == "")
	{
		window.alert('日を選択してください。');
		obj.style.backgroundColor = '#ff0000';
		judge = false;
	}
	else
	{
		obj.style.backgroundColor  = '';
	}
	if(judge == true )
	{
		document.form.submit();
	}
	return judge;

}


