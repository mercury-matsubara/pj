function generateDay(monthID,start_date,nenngou) {
	var yearID ;
	var dayID;
	var idcreate = monthID.split("_");
	var isSelect = true;
	if(idcreate.length==4){
		yearID = idcreate[0]+"_"+idcreate[1]+"_0_"+idcreate[3];
		dayID = idcreate[0]+"_"+idcreate[1]+"_2_"+idcreate[3];
	}
	else
	{
		yearID = idcreate[0]+"_"+idcreate[1]+"_0";
		dayID = idcreate[0]+"_"+idcreate[1]+"_2";
	}
	var y = document.getElementById(yearID).options[document.getElementById(yearID).selectedIndex].value;
	var m = document.getElementById(monthID).options[document.getElementById(monthID).selectedIndex].value;
	var d = document.getElementById(dayID).options[document.getElementById(dayID).selectedIndex].value;
	var y_text = document.getElementById(yearID).options[document.getElementById(yearID).selectedIndex].text;
	var start = 0;
	if(m != '' && y != '')
	{
		if (2 == m && (0 == y % 4 ))
		{
			var last = 29;
		}
		else
		{
			var last = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31)[m - 1];
		}
	}
	else if(m != '')
	{
		var last = new Array(31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31)[m - 1];
	}
	else
	{
		var last = 31;
	}
	var result = make_day(y_text,m,start_date,nenngou);
	if(result[0] != "")
	{
		start = result[0];
	}
	if(result[1] != "")
	{
		last = result[1];
	}

	// óvëféÊìæÇ∆èâä˙âª
	obj = document.getElementById(dayID);
	obj.length = 0;

	//	ì˙ÇÃóvëfê∂ê¨
	for (var daycount = start; daycount < last; daycount++) {
		var d_text = ('00'+(daycount + 1)).slice(-2);
		obj.options[obj.length++] = new Option(d_text, daycount + 1);
		if((daycount + 1) == d && d != ''){
			obj.options[obj.length-1].selected=true;
			isSelect = false;
		}
	}
	obj.options[obj.length++] = new Option('','');
	if(isSelect)
	{
		obj.options[obj.length-1].selected=true;
	}
}



function generateMonth(yearID,type,start_date,nenngou) {
	var monthID;
	var dayID;
	var idcreate = yearID.split("_");
	var isSelect = true;
	var start = 0;
	var last = 12;
	if(idcreate.length==4){
		var monthID = idcreate[0]+"_"+idcreate[1]+"_1_"+idcreate[3];
		var dayID = idcreate[0]+"_"+idcreate[1]+"_2_"+idcreate[3];
	}
	else{
		var monthID = idcreate[0]+"_"+idcreate[1]+"_1";
		var dayID = idcreate[0]+"_"+idcreate[1]+"_2";
	}
	var y = document.getElementById(yearID).options[document.getElementById(yearID).selectedIndex].value;
	var m = document.getElementById(monthID).options[document.getElementById(monthID).selectedIndex].value;
	var y_text = document.getElementById(yearID).options[document.getElementById(yearID).selectedIndex].text;
	var result = make_month(y_text,start_date,nenngou);
	if(result[0] != "")
	{
		start = result[0];
	}
	if(result[1] != "")
	{
		last = result[1];
	}
	// óvëféÊìæÇ∆èâä˙âª
	obj1 = document.getElementById(monthID);
	obj1.length = 0;

	//	åéÇÃóvëfê∂ê¨
	if(type ==1 || type ==2)
	{
		for (var monthcount = start; monthcount < last; monthcount++)
		{
			var m_text = ('00'+(monthcount + 1)).slice(-2);
			obj1.options[obj1.length++] = new Option(m_text, monthcount + 1);
			if((monthcount + 1) == m && m != '')
			{
				obj1.options[obj1.length-1].selected=true;
				isSelect = false;
			}
		}
	}
	else if(type == 4)
	{
		for (var monthcount = start; monthcount < last; monthcount++)
		{
			var m_text = ('00'+(monthcount + 1)).slice(-2);
			obj1.options[obj1.length++] = new Option(m_text,(monthcount + 1)+'-1');
			if((monthcount + 1) == m && m != '')
			{
				obj1.options[obj1.length-1].selected=true;
				isSelect = false;
			}
		}
	}
	else
	{
		for (var monthcount = start; monthcount < last; monthcount++)
		{
			var m_text = ('00'+(monthcount + 1)).slice(-2);
			obj1.options[obj1.length++] = new Option(m_text,(monthcount + 1)+'åé');
			if((monthcount + 1) == m && m != '')
			{
				obj1.options[obj1.length-1].selected=true;
				isSelect = false;
			}
		}
	}
	obj1.options[obj1.length++] = new Option("","");
	if(isSelect)
	{
		obj1.options[obj1.length-1].selected=true;
	}
	if(type ==1 || type ==2){
		generateDay(monthID,start_date,nenngou);
	}
}

function make_month(y_text,start,nenngou)
{
	var start_array = start.split(",");
	var nenngou_array = nenngou.split(",");
	
	var start_date = '';
	var end_date = '';
	var start_year = '';
	var end_year = '';
	var start_month = '';
	var end_month= '';
	var count = 0;
	var valuetext = 0;
//	alert(nenngou);
	
	
	for(var i = 0; i< nenngou_array.length ; i++)
	{
		if(y_text.indexOf(nenngou_array[i]) != -1)
		{
			count = i;
			start_date = start_array[i];
			if(i != 0)
			{
				end_date = start_array[i - 1];
			}
			break;
		}
	}
	if(y_text == nenngou_array[count]+' '+1)
	{
		var start_date_array = start_date.split("-");
		start_month = (start_date_array[1] - 1);
	}
	else if(end_date != 0)
	{
		var start_date_array  = start_date.split("-");
		var end_date_array  = end_date.split("-");
		start_year = start_date_array[0];
		end_year = end_date_array[0];
		valuetext = nenngou_array[count]+' '+(end_year - start_year + 1);
		if(y_text == valuetext)
		{
			end_month = end_date_array[1];
		}
	}
	var return_array = [start_month,end_month ];
	return(return_array);
}

function make_day(y_text,m,start,nenngou)
{
	var start_array = start.split(",");
	var nenngou_array = nenngou.split(",");
	
	var start_date = '';
	var end_date = '';
	var start_year = '';
	var end_year = '';
	var start_day = '';
	var end_day= '';
	var count = 0;
	var valuetext = 0;
	
	
	for(var i = 0; i< nenngou_array.length ; i++)
	{
		if(y_text.indexOf(nenngou_array[i]) != -1)
		{
			start_date = start_array[i];
			if(i != 0)
			{
				end_date = start_array[i - 1];
			}
			count = i;
			break;
		}
	}
	var start_date_array = start_date.split("-");
	if(y_text == nenngou_array[count]+' '+1 && m == start_date_array[1])
	{
		start_day = (start_date_array[2] - 1);
	}
	else if(end_date != '')
	{
		var start_date_array = start_date.split("-");
		var end_date_array = end_date.split("-");
		start_year = start_date_array[0];
		end_year = end_date_array[0];
		valuetext = nenngou_array[count]+' '+(end_year - start_year + 1);
		if(y_text == valuetext && m == end_date_array[1])
		{
			end_day = (end_date_array[2] - 1);
		}
	}
	var return_array = [start_day,end_day];
	return(return_array);
}
