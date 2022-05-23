

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////




//---------------------------------------------------//
//                                                   //
//             入力チェック関数                      //
//   引数name : ドキュメントID                       //
//   引数size : 最大文字入力文字数                   //
//   引数type : 入力タイプ(                          //
//                       0:半角小数のみ              //
//                       1:全角のみ                  //
//                       2:半角のみ                  //
//                       3:半角英数のみ(記号不可)    //
//                       4:半角数字のみ              //
//                       5:All OK                    //
//   引数isnotnull:入力必須か                        //
//   戻り値judge:チェック結果                        //
//                                                   //
//---------------------------------------------------//
function inputcheck(name,size,type,isnotnull,isJust){
	var judge =true;
	var str = document.getElementById(name).value;
	m = String.fromCharCode(event.keyCode);
	var len = 0;
	var str2 = escape(str);
/*	if(type===0)
	{
		if(str.match(/[0-9a-zA-Z\. ]+/g))
		{
			judge=false;
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('半角数字とピリオドで入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
	else */
	if(type==1)
	{
		for(i = 0; i < str2.length; i++, len++){
			if(str2.charAt(i) == "%"){
				if(str2.charAt(++i) == "u"){
					i += 3;
					len++;
				}
				else
				{
					judge=false;
				}
				i++;
			}
			else
			{
				judge=false;
			}
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('全角で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
	else if(type==2)
	{
		for(i = 0; i < str2.length; i++, len++){
			if(str2.charAt(i) == "%"){
				if(str2.charAt(++i) == "u"){
					i += 3;
					len++;
					judge=false;
				}
			}
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('半角で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
	else if(type==3)
	{
		if(str.match(/[^0-9A-Za-z]+/)) 
		{
			judge=false;
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('半角英数で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
	else if(type==4 || type==7 )
	{
		if(str.match(/[^0-9]+/)) 
		{
			judge=false;
		}
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
		else
		{
			window.alert('半角数字で入力してください');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
//	if (size < (str.length))
	if (size < strlen(str) && isJust == 2)
	{
		if("\b\r".indexOf(m, 0) < 0)
		{
			window.alert(size+'文字以内で入力してください');
		}
		document.getElementById(name).style.backgroundColor = '#ff0000';
		judge = false;
	}
	else if(isJust == 2)
	{
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
	}
	else if (size != strlen(str) && strlen(str) != 0 && isJust == 1)
	{
		if("\b\r".indexOf(m, 0) < 0)
		{
			window.alert(size+'文字で入力してください');
		}
		document.getElementById(name).style.backgroundColor = '#ff0000';
		judge = false;
	}
	else if(isJust == 1)
	{
		if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
	}
	
	if(isnotnull == 1)
	{
		if(document.getElementById(name).value == '')
		{
			document.getElementById(name).style.backgroundColor = '#ff0000';
			judge = false;
			window.alert('値を入力してください');
		}
		else if(judge)
		{
			document.getElementById(name).style.backgroundColor = '';
		}
	}
	if(judge == true && type==7)
	{
		var name_array =  name.split("_");
		var total = name_array[1];
		var Charge = 0 ;
		for(var i = 1 ; i <= total ; i++)
		{
			var id = 'kobetu_'+ total + '_' + i;
			if(document.getElementById(id).value == '')
			{
				Charge += 0;
			}
			else if(document.getElementById(id).value.match(/[^0-9]+/))
			{
				document.getElementById(id).style.backgroundColor = '#ff0000';
				window.alert('半角数字で入力してください');
				judge = false;
			}
			else
			
			{
				Charge += parseInt(document.getElementById(id).value);
			}
		}
		document.getElementById('chage').value = Charge;
	}
        //重複チェック(枝番コードが同じかつ製番・案件名)が同じデータが登録されていたらエラーとする。
        if(filename == 'EDABANINFO_1')
        {     
            if(name == "form_202_0" || name == "form_203_0")
            {
                var str1 = document.getElementById("form_202_0").value;
                var str2 = document.getElementById("form_203_0").value;
                var numcnt = 0;
                while(numcnt < edaitem.length - 1)
                {
                    if (str1 == edaitem[numcnt + 0] && str2 == edaitem[numcnt + 1])
                    {
                        judge = false;
                        document.getElementById("form_202_0").style.backgroundColor = '#ff0000';
                        document.getElementById("form_203_0").style.backgroundColor = '#ff0000';
                        
                        if(event.type == "change" || (event.type == "submit" && name == "form_202_0"))
                        {
                            alert('枝番コード、製番・案件名が同じデータが登録されています。');
                        }
                        break;
                    }
                    numcnt = numcnt + 2;
                }
            }
        }
	return judge;
}

function notnullcheck(id,isnotnull)
{
	if(isnotnull == 1)
	{
		var selectnum = document.getElementById(id).selectedIndex;
		if(document.getElementById(id).options[selectnum].value == "")
		{
			document.getElementById(id).style.backgroundColor = '#ff0000';
			judge = false;
				window.alert('値を選択して下さい');
		}
		else
		{
			document.getElementById(id).style.backgroundColor = '';
		}
	}
}

function nullcheck(id,isnotnull)
{
    console.log("入力チェック");
    if(isnotnull == 1)
    {
        if(document.getElementById(id).value == "")
        {
            document.getElementById(id).style.backgroundColor = '#ff0000';
            judge = false;
            window.alert('値を入力してください');
        }
        else
        {
            document.getElementById(id).style.backgroundColor = '';
        }
    }
}

function strlen(str) {
  var ret = 0;
  for (var i = 0; i < str.length; i++,ret++) {
    var upper = str.charCodeAt(i);
    var lower = str.length > (i + 1) ? str.charCodeAt(i + 1) : 0;
    if (isSurrogatePear(upper, lower)) {
      i++;
    }
  }
  return ret;
}

function strsub(str, begin, end) {
  var ret = '';
  for (var i = 0, len = 0; i < str.length; i++, len++) {
    var upper = str.charCodeAt(i);
    var lower = str.length > (i + 1) ? str.charCodeAt(i + 1) : 0;
    var s = "";
    if(isSurrogatePear(upper, lower)) {
      i++;
      s = String.fromCharCode(upper, lower);
    } else {
      s = String.fromCharCode(upper);
    }
    if (begin <= len && len < end) {
      ret += s;
    }
  }
  return ret;
}

function isSurrogatePear(upper, lower) {
  return 0xD800 <= upper && upper <= 0xDBFF && 0xDC00 <= lower && lower <= 0xDFFF;
}


