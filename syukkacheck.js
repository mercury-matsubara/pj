

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
			window.alert('���l����͂��Ă��������B');
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
						window.alert('�������l����͂��Ă��������B');
						obj.style.backgroundColor = '#ff0000';
						judge = false;
					}
					else if(num != "-1" && zaiko_num < value_num)
					{
						window.alert('�������l����͂��Ă��������B');
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
		window.alert('�����I�����Ă��������B');
		judge = false;
	}
	else if(value_4CODE != obj.value)
	{
		window.alert('�����I�����܂�����\���{�^�����������Ă��������B');
		judge = false;
	}
	obj = document.getElementById('form_start_0');
	if(obj.value == "")
	{
		window.alert('�N�x��I�����Ă��������B');
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
		window.alert('����I�����Ă��������B');
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
		window.alert('����I�����Ă��������B');
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


