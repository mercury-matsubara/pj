

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////




//---------------------------------------------------//
//                                                   //
//             ���̓`�F�b�N�֐�                      //
//   ����name : �h�L�������gID                       //
//   ����size : �ő啶�����͕�����                   //
//   ����type : ���̓^�C�v(                          //
//                       0:���p�����̂�              //
//                       1:�S�p�̂�                  //
//                       2:���p�̂�                  //
//                       3:���p�p���̂�(�L���s��)    //
//                       4:���p�����̂�              //
//                       5:All OK                    //
//   ����isnotnull:���͕K�{��                        //
//   �߂�ljudge:�`�F�b�N����                        //
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
			window.alert('���p�����ƃs���I�h�œ��͂��Ă�������');
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
			window.alert('�S�p�œ��͂��Ă�������');
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
			window.alert('���p�œ��͂��Ă�������');
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
			window.alert('���p�p���œ��͂��Ă�������');
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
			window.alert('���p�����œ��͂��Ă�������');
			document.getElementById(name).style.backgroundColor = '#ff0000';
		}
	}
//	if (size < (str.length))
	if (size < strlen(str) && isJust == 2)
	{
		if("\b\r".indexOf(m, 0) < 0)
		{
			window.alert(size+'�����ȓ��œ��͂��Ă�������');
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
			window.alert(size+'�����œ��͂��Ă�������');
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
			window.alert('�l����͂��Ă�������');
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
				window.alert('���p�����œ��͂��Ă�������');
				judge = false;
			}
			else
			
			{
				Charge += parseInt(document.getElementById(id).value);
			}
		}
		document.getElementById('chage').value = Charge;
	}
        //�d���`�F�b�N(�}�ԃR�[�h�����������ԁE�Č���)�������f�[�^���o�^����Ă�����G���[�Ƃ���B
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
                            alert('�}�ԃR�[�h�A���ԁE�Č����������f�[�^���o�^����Ă��܂��B');
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
				window.alert('�l��I�����ĉ�����');
		}
		else
		{
			document.getElementById(id).style.backgroundColor = '';
		}
	}
}

function nullcheck(id,isnotnull)
{
    console.log("���̓`�F�b�N");
    if(isnotnull == 1)
    {
        if(document.getElementById(id).value == "")
        {
            document.getElementById(id).style.backgroundColor = '#ff0000';
            judge = false;
            window.alert('�l����͂��Ă�������');
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


