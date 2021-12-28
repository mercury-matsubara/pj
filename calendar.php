<!DOCTYPE html PUBLIC "-//W3C/DTD HTML 4.01">
<!-- saved from url(0013)about:internet -->
<!-- 
*------------------------------------------------------------------------------------------------------------*
*                                                                                                            *
*                                                                                                            *
*                                          ver 1.0.0  2014/05/09                                             *
*                                                                                                            *
*                                                                                                            *
*------------------------------------------------------------------------------------------------------------*
 -->

<html>
<?php
	session_start();
	header('Content-type: text/html; charset=Shift_JIS'); 
	require_once ("f_Button.php");
	require_once("f_Construct.php");
	require_once("f_File.php");																							// DB�֐��Ăяo������
	require_once("f_DB.php");																							// DB�֐��Ăяo������
	start();
	check_mail();
	$title = "";
	$form_ini = parse_ini_file('./ini/form.ini', true);
	$filename = $_SESSION['filename'];
	$main_table = $form_ini[$filename]['use_maintable_num'];
	$title = $form_ini[$filename]['title'];
	$_SESSION['post'] = $_SESSION['pre_post'];
	$_SESSION['pre_post'] = null;
?>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<link rel='stylesheet' href='./jquery-ui.min.css' />
<link rel='stylesheet' href='./jquery-ui-1.10.3.custom.css' />
<link href='./fullcalendar.css' rel='stylesheet' />
<link href='./fullcalendar.print.css' rel='stylesheet' media='print' />
<link rel="stylesheet" type="text/css" href="./list_css.css">
<script src='./jquery-1.8.3.min.js'></script>
<script src='./jquery-ui-1.10.3.custom.js'></script>
<script src='./jquery.ui.datepicker-ja.js'></script>
<script src='./fullcalendar.js'></script>
<script src='./jquery.corner.js'></script>
<script src='./inputcheck.js'></script>
<script type='text/javascript' src='./gcal.js'></script>
<script>

	$(document).ready(function() {
	
	var cal;
		
		$(".button").corner();
		$(".free").corner();
		$('#dialog').dialog({
			autoOpen: false,
			width: 600,
			title : "�C�x���g�ݒ�",
			modal : true,
			buttons : {
				'btadd': { 
					text : "�o�^",
					name : "add",
					id : "added",
					click : function() {
						var fulldate_start = document.getElementsByName("start_year")[0].value;
						var sepString = fulldate_start.split("/");
						var strSYear = sepString[0];
						var strSMonth = sepString[1];
						var strSDay = sepString[2];
						var datein = new Date(strSYear, strSMonth - 1, strSDay);
						var dateinres = true;
						var start_hour = document.getElementsByName("start_hour")[0].value;
						var start_min = document.getElementsByName("start_min")[0].value;
						start_hour = Number(start_hour);
						start_min = Number(start_min);
						if (!(datein.getFullYear() == strSYear && datein.getMonth() == strSMonth - 1 && datein.getDate() == strSDay )){
							dateinres = false;
						}
						
						var fulldate_end = document.getElementsByName("end_year")[0].value;
						sepString = fulldate_end.split("/");
						var strEYear = sepString[0];
						var strEMonth = sepString[1];
						var strEDay = sepString[2];
						var dateout = new Date(strEYear, strEMonth - 1, strEDay);
						var dateoutres = true;
						var end_hour = document.getElementsByName("end_hour")[0].value;
						var end_min = document.getElementsByName("end_min")[0].value;
						end_hour = Number(end_hour);
						end_min = Number(end_min);
						var isOKstr_end = true;
						if(dateout < datein)
						{
							isOKstr_end = false;
						}
						else if ((dateout - datein) == 0)
						{
							if(end_hour < start_hour)
							{
								isOKstr_end = false;
							}
							else if(end_hour == start_hour)
							{
								if(end_min <= start_min)
								{
									isOKstr_end = false;
								}
							}
						}
						if (!(dateout.getFullYear() == strEYear && dateout.getMonth() == strEMonth - 1 && dateout.getDate() == strEDay )){
							dateoutres = false;
						}
						
						if ( strSDay == "" || strSMonth == "" || strSYear == "" || strEDay == "" || strEMonth == "" || strEYear == "" || dateinres == false || dateoutres == false)
						{
							var res = confirm("���t�̒l���s���ł��B\n���͂������ꍇ��OK�������Ă��������B\n�L�����Z���������Ɠ��͉�ʂ���܂��B");
							if ( res == true ) { 
							} else {
								$('#dialog').dialog('close' );
							}
						}
						else if (inputcheck('honbun',255,5,0) == false || inputcheck('title1',50,5,0) == false )
						{
							//  //
						}
						else if(isOKstr_end == false)
						{
							alert("�I�������J�n�����O�ɐݒ肳��Ă��܂��B");
						}
						else
						{
						
							$.ajax({
								url: "eventEntry.php",
								dataType: 'json',
								type:"post",
								data: {
									title1: document.getElementsByName("title1")[0].value,
									start_year: document.getElementsByName("start_year")[0].value,
									start_hour: document.getElementsByName("start_hour")[0].value,
									start_min: document.getElementsByName("start_min")[0].value,
									end_year: document.getElementsByName("end_year")[0].value,
									end_hour: document.getElementsByName("end_hour")[0].value,
									end_min: document.getElementsByName("end_min")[0].value,
									honbun: document.getElementsByName("honbun")[0].value
								},
								complete:function() {
									cal.fullCalendar('refetchEvents');
								}
							});
							
							$('#dialog').dialog('close' );
						}
					}
				},
				'btcancel' : {
					text : "��ݾ�",
					name : "can",
					id : "cancel",
					click : function() {
						$('#dialog').dialog('close');
					}
				}
			}
			
			
		});
		
		$('#dialog2').dialog({
			autoOpen: false,
			width: 600,
			title : "�C�x���g�ڍ�",
			modal : true,
			buttons: {
				'bt1': { 
					text : "�ύX",
					name : "mod",
					id : "modify",
					click : function() {
						var fulldate_str = document.getElementsByName("read_start_year")[0].value;
						var sepString = fulldate_str.split("/");
						var strSYear = sepString[0];
						var strSMonth = sepString[1];
						var strSDay = sepString[2];
						var datein = new Date(strSYear, strSMonth - 1, strSDay);
						var dateinres = true;
						var start_hour = document.getElementsByName("read_start_hour")[0].value;
						var start_min = document.getElementsByName("read_start_min")[0].value;
						start_hour = Number(start_hour);
						start_min = Number(start_min);
						if (!(datein.getFullYear() == strSYear && datein.getMonth() == strSMonth - 1 && datein.getDate() == strSDay )){
							dateinres = false;
						}
						
						var fulldate_end = document.getElementsByName("read_end_year")[0].value;
						sepString = fulldate_end.split("/");
						var strEYear = sepString[0];
						var strEMonth = sepString[1];
						var strEDay = sepString[2];
						var dateout = new Date(strEYear, strEMonth - 1, strEDay);
						var dateoutres = true;
						var end_hour = document.getElementsByName("read_end_hour")[0].value;
						var end_min = document.getElementsByName("read_end_min")[0].value;
						end_hour = Number(end_hour);
						end_min = Number(end_min);
						var isOKstr_end = true;
						if(dateout < datein)
						{
							isOKstr_end = false;
						}
						else if((dateout - datein) == 0)
						{
							if(end_hour < start_hour)
							{
								isOKstr_end = false;
							}
							else if(end_hour == start_hour)
							{
								if(end_min <= start_min)
								{
									isOKstr_end = false;
								}
							}
						}
						if (!(dateout.getFullYear() == strEYear && dateout.getMonth() == strEMonth - 1 && dateout.getDate() == strEDay )){
							dateoutres = false;
						}
						
						if ( strSDay == "" || strSMonth == "" || strSYear == "" || strEDay == "" || strEMonth == "" || strEYear == "" || dateinres == false || dateoutres == false) {
							var res = confirm("���t�̒l���s���ł��B\n���͂������ꍇ��OK�������Ă��������B\n�L�����Z���������Ɠ��͉�ʂ���܂��B");
							if ( res == true ) { 
								// OK�{�^�������������̏���
							} else {
								$('#dialog2').dialog('close' );
							}
						}
						else if(inputcheck('read_honbun',255,5,0) == false || inputcheck('read_title',50,5,0) == false )
						{
							//  //
						}
						else if(isOKstr_end == false)
						{
							alert("�I�������J�n�����O�ɐݒ肳��Ă��܂��B");
						}
						else
						{
							$.ajax({
								url: "modify.php",
								dataType: 'json',
								type:"post",
								data: {
									textid: document.getElementsByName("read_id")[0].value,
									title1: document.getElementsByName("read_title")[0].value,
									start_year: document.getElementsByName("read_start_year")[0].value,
									start_hour: document.getElementsByName("read_start_hour")[0].value,
									start_min: document.getElementsByName("read_start_min")[0].value,
									end_year: document.getElementsByName("read_end_year")[0].value,
									end_hour: document.getElementsByName("read_end_hour")[0].value,
									end_min: document.getElementsByName("read_end_min")[0].value,
									honbun: document.getElementsByName("read_honbun")[0].value
								},
								complete:function() {
									cal.fullCalendar('refetchEvents');
								}
							});
							
							$('#dialog2').dialog('close' );
						}
					}
				},
				'bt2': {
					text : "�폜",
					name : "del",
					id : "delete",
					click : function() {
						$.ajax({
							url: "eventDelete.php",
							dataType: 'json',
							type:"post",
							data: {
								textid: document.getElementsByName("read_id")[0].value
							},
							complete:function() {
								cal.fullCalendar('refetchEvents');
							}
						});
					
						$('#dialog2').dialog('close');
					}
				},
				'bt3': {
					text : "����",
					name : "cls",
					id : "close",
					click : function() {
						$('#dialog2').dialog('close');
					}
				}
			}
			
			
		});
		
		cal = $('#calendar').fullCalendar({
			editable: true,
			header: {
				left: 'prevYear prev,next nextYear today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			timeFormat: {
				'': 'HH:mm' // default
			},
			eventSources:
				[{
					url: "schedule.php",
					dataType: 'json',
					type:"post",
				},
				{
					url: "http://www.google.com/calendar/feeds/ja.japanese%23holiday%40group.v.calendar.google.com/public/full/",
					color: 'red',
					textColor: 'white',
					currentTimezone : 'Asia/Tokyo',
					success:function(eventSources){
						$(eventSources).each(function(){
							this.url = null;
							this.className="google";
						});
					}
				}],
			
			viewDisplay: 
				function(view) {
//					�����_�����O(��ʕ`�抮�����C�x���g)
//					$.ajax({
//						url: "schedule.php",
//						dataType: 'json',
//						type:"post",
//						data: {
//							"start": view.start.toString(),
//							"end": view.end.toString()
//						},
//						success: function(EventSource) {
//							$('#calendar').fullCalendar('removeEvents');
//							$('#calendar').fullCalendar('addEventSource', EventSource);
//						}
//					});
					
				},
			
			eventClick: function(event, daydelta, jsEvent, ui, view) {
//				�C�x���g(�X�P�W���[���\������N���b�N�������̏���)
				document.getElementById("read_title").style.backgroundColor = '';
				document.getElementById("read_honbun").style.backgroundColor = '';
				$('#dialog2').dialog('open');
				
				// ������
				document.getElementsByName("read_id")[0].value="";
				document.getElementsByName("title1")[0].value="";
				document.getElementsByName("honbun")[0].value="";
				document.getElementsByName("read_start_hour")[0].value="00";
				document.getElementsByName("read_start_min")[0].value="00";
				document.getElementsByName("read_end_hour")[0].value="00";
				document.getElementsByName("read_end_min")[0].value="00";
				
				document.getElementsByName("read_title")[0].value=event.title;
				if (event.className != "google")
					document.getElementsByName("read_id")[0].value=event.id;
				else
					document.getElementsByName("read_id")[0].value="";
					
				if (event.className != "google")
					document.getElementsByName("read_honbun")[0].value=event.maintext;
				else
					document.getElementsByName("read_honbun")[0].value="";
				var startdate = new Date(event.start);
				if ( startdate.getTime() != 0 ) {
					document.getElementsByName("read_start_year")[0].value=startdate.getFullYear() + "/" + ("0" + (startdate.getMonth()+1)).slice(-2)  + "/" +  ("0" + startdate.getDate()).slice(-2);
					document.getElementsByName("read_start_hour")[0].value=("0" + startdate.getHours()).slice(-2);
					document.getElementsByName("read_start_min")[0].value=("0" + startdate.getMinutes()).slice(-2);
				}
				else
				{
					document.getElementsByName("read_start_year")[0].value=""
				}
				var enddate = new Date(event.end);
				if ( enddate.getTime() != 0 ) {
					document.getElementsByName("read_end_year")[0].value=enddate.getFullYear() + "/" + ("0" + (enddate.getMonth()+1)).slice(-2)  + "/" +  ("0" + enddate.getDate()).slice(-2);
					document.getElementsByName("read_end_hour")[0].value=("0" + enddate.getHours()).slice(-2);
					document.getElementsByName("read_end_min")[0].value=("0" + enddate.getMinutes()).slice(-2);
				}
				else
				{
					document.getElementsByName("read_end_year")[0].value=""
				}
				
				if (event.className == "google") {
					document.getElementsByName("mod")[0].disabled = "disabled"
					document.getElementsByName("del")[0].disabled = "disabled"
				}
				else
				{
					document.getElementsByName("mod")[0].disabled = ""
					document.getElementsByName("del")[0].disabled = ""
				}
			},
			
			eventResize:  function(event, daydelta) {
				if(event.end != null)
				{
					result=window.confirm('�\���ύX���܂���?' );
					if (result == true){
						var startdate = new Date(event.start);
						var enddate = new Date(event.end);
						$.ajax({
							url: "modify.php",
							dataType: 'json',
							type:"post",
							data: {
								textid: event.id,
								title1: event.title,
								start_year: startdate.getFullYear() + "/" + ("0" + (startdate.getMonth()+1)).slice(-2)  + "/" +  ("0" + startdate.getDate()).slice(-2),
								start_hour: ("0" + startdate.getHours()).slice(-2),
								start_min: ("0" + startdate.getMinutes()).slice(-2),
								end_year: enddate.getFullYear() + "/" + ("0" + (enddate.getMonth()+1)).slice(-2)  + "/" +  ("0" + enddate.getDate()).slice(-2),
								end_hour: ("0" + enddate.getHours()).slice(-2),
								end_min: ("0" + enddate.getMinutes()).slice(-2),
								honbun: event.maintext
							},
							complete:function() {
								cal.fullCalendar('refetchEvents');
							}
						});
					}
					else
					{
						cal.fullCalendar('refetchEvents');
					}
				}
				else
				{
					alert("�I�������J�n�����O�ɐݒ肳��Ă��܂��̂ŕύX�ł��܂���B");
					cal.fullCalendar('refetchEvents');
				}
			},
			eventDrop:  function(event, daydelta) {
				result=window.confirm('�\���ύX���܂���?' );
				if (result == true){
					var startdate = new Date(event.start);
					var enddate = new Date(event.end);
					$.ajax({
						url: "modify.php",
						dataType: 'json',
						type:"post",
						data: {
							textid: event.id,
							title1: event.title,
							start_year: startdate.getFullYear() + "/" + ("0" + (startdate.getMonth()+1)).slice(-2)  + "/" +  ("0" + startdate.getDate()).slice(-2),
							start_hour: ("0" + startdate.getHours()).slice(-2),
							start_min: ("0" + startdate.getMinutes()).slice(-2),
							end_year: enddate.getFullYear() + "/" + ("0" + (enddate.getMonth()+1)).slice(-2)  + "/" +  ("0" + enddate.getDate()).slice(-2),
							end_hour: ("0" + enddate.getHours()).slice(-2),
							end_min: ("0" + enddate.getMinutes()).slice(-2),
							honbun: event.maintext
						},
						complete:function() {
							cal.fullCalendar('refetchEvents');
						}
					});
				}
				else{
					cal.fullCalendar('refetchEvents');
				}
			},
			
			dayClick: function(dayDate, allDay, jsEvent, view) { 
			
				
				document.getElementById("datepicker3").value=dayDate.getFullYear()+"/"+(dayDate.getMonth() +1)+"/"+dayDate.getDate();
				document.getElementById("datepicker4").value=dayDate.getFullYear()+"/"+(dayDate.getMonth() +1)+"/"+dayDate.getDate();
				document.getElementsByName("read_id")[0].value="";
				document.getElementsByName("title1")[0].value="";
				document.getElementsByName("honbun")[0].value="";
				document.getElementById("title1").style.backgroundColor = '';
				document.getElementById("honbun").style.backgroundColor = '';
				document.getElementsByName("read_start_hour")[0].value="00";
				document.getElementsByName("read_start_min")[0].value="00";
				document.getElementsByName("read_end_hour")[0].value="00";
				document.getElementsByName("read_end_min")[0].value="00";
				
				document.getElementsByName("end_hour")[0].value="23";
				document.getElementsByName("end_min")[0].value="45";


				$('#dialog').dialog('open');
				
			},
			
			eventMouseover: function(event) {
			// �}�E�X�J�[�\�����^�[�Q�b�g��ɍ��킹�����ɔ������鏈��
				if((event.maintext != null || event.maintext != '' || event.maintext == '\0') && event.className != "google")
				{
					$(this).append('<div class="show_memo">'+event.maintext+'</div>');
//					alert(event.memo1);
				}
			},
			eventMouseout: function(event) {
			// �}�E�X�J�[�\�����^�[�Q�b�g�ォ��O�������ɔ������鏈��
				$(".show_memo").remove();
			}

		});
		$( "#datepicker" ).datepicker();
		$( "#datepicker" ).datepicker( "option", "showOn", 'both' );
		$("#datepicker").datepicker("option", "buttonImageOnly", true);
		$("#datepicker").datepicker("option", "buttonImage", 'image/calbutton.png');
		
		
		$( "#datepicker2" ).datepicker();
		$( "#datepicker2" ).datepicker( "option", "showOn", 'both' );
		$("#datepicker2").datepicker("option", "buttonImageOnly", true);
		$("#datepicker2").datepicker("option", "buttonImage", 'image/calbutton.png');
		
		$( "#datepicker3" ).datepicker();
		$( "#datepicker3" ).datepicker( "option", "showOn", 'both' );
		$("#datepicker3").datepicker("option", "buttonImageOnly", true);
		$("#datepicker3").datepicker("option", "buttonImage", 'image/calbutton.png');
		
		$( "#datepicker4" ).datepicker();
		$( "#datepicker4" ).datepicker( "option", "showOn", 'both' );
		$("#datepicker4").datepicker("option", "buttonImageOnly", true);
		$("#datepicker4").datepicker("option", "buttonImage", 'image/calbutton.png');
		
		$( "#datepicker5" ).datepicker();
		$( "#datepicker5" ).datepicker( "option", "showOn", 'both' );
		$("#datepicker5").datepicker("option", "buttonImageOnly", true);
		$("#datepicker5").datepicker("option", "buttonImage", 'image/calbutton.png');
		
		$( "#datepicker6" ).datepicker();
		$( "#datepicker6" ).datepicker( "option", "showOn", 'both' );
		$("#datepicker6").datepicker("option", "buttonImageOnly", true);
		$("#datepicker6").datepicker("option", "buttonImage", 'image/calbutton.png');
		 
	});
	
//	function entryChange(){
//			// ���t�敪�I��ω�������(���g�p)
//			radio = document.getElementsByName('entryPlan');
//			
//			if(radio[1].checked){
//				document.getElementById('select1').style.display = "";
//				document.getElementById('select2').style.display = "";
//				document.getElementById('select3').style.display = "";
//				document.getElementById('select4').style.display = "";
//			}
//			if(radio[0].checked){
//				document.getElementById('select1').style.display = "none";
//				document.getElementById('select2').style.display = "none";
//				document.getElementById('select3').style.display = "none";
//				document.getElementById('select4').style.display = "none";
//			}
//		}
	
//	window.onload = entryChange;
	
	
	function categorychange(){
		// �ً}�A�ʏ�敪�\���ؑ֏���(���g�p)
		if(document.getElementById('category')){
			id = document.getElementById('category').value;
			if(id == 'all'){
				$("div.defalt").css("display", "block");
				$("div.emargency").css("display", "block");
				$("div.google").css("display", "block");
				
			}
			else if(id == 'defalt'){
				$("div.defalt").css("display", "block");
				$("div.emargency").css("display", "none");
				$("div.google").css("display", "block");
				
			}
			else if(id == 'emargency'){
				$("div.defalt").css("display", "none");
				$("div.emargency").css("display", "block");
				$("div.google").css("display", "block");
				
			}
		}

	
	}
	
	

</script>
<style>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
		
	table#popupmenu {
		text-align: left;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}

	#calendar {
		width: 800px;
		margin: 0 auto;
		}

</style>
</head>
<body>
<?php

	echo "<form action='pageJump.php' method='post'>";
	echo makebutton();
	echo "<div style='clear:both;'></div></form><br><br><br>";

?>




<!--
<form action="eventEntry.php" method="post">
���e
<input name="title" type="text" size=30>
<br>

�J�n��: <input type="text" id="datepicker" />
�@�@�`�@�@
�I����: <input type="text" id="datepicker2" />

<input name="entry" type="submit" value="�\��ǉ�">

</form>



<select id="category" name="category" onchange="categorychange();">
	<option value="all">�S�J�e�S���[</option>
	<option value="defalt">�f�t�H���g</option>
	<option value="emargency">�ً}</option>
</select>

-->
<div id='calendar'></div>
<div id="dialog">
	<form id="form">
		<table id="popupmenu">
		<tr>
		<td>
		<label for="title1">����</label>
		</td>
		<td>
		<input type="text" name="title1" id = "title1" onChange = "return inputcheck('title1',50,5,0)"/>
		</td>
		</tr>
<!--
		<tr>
		<td>
		���ԏڍ׎w��I�v�V����
		</td>
		<td>
		<input type="radio" name="entryPlan" value="select1" onclick="entryChange();" checked="checked" />�S��
		<input type="radio" name="entryPlan" value="select2" onclick="entryChange();" />���Ԏw��
		</td>
		</tr>
-->
		<tr>
		<td>
		�J�n��
		</td>
		<td>
		<input type="text" name="start_year" id="datepicker3" />
		<select id="select1" name="start_hour">
			<option value="00">00��</option>
			<option value="01">01��</option>
			<option value="02">02��</option>
			<option value="03">03��</option>
			<option value="04">04��</option>
			<option value="05">05��</option>
			<option value="06">06��</option>
			<option value="07">07��</option>
			<option value="08">08��</option>
			<option value="09">09��</option>
			<option value="10">10��</option>
			<option value="11">11��</option>
			<option value="12">12��</option>
			<option value="13">13��</option>
			<option value="14">14��</option>
			<option value="15">15��</option>
			<option value="16">16��</option>
			<option value="17">17��</option>
			<option value="18">18��</option>
			<option value="19">19��</option>
			<option value="20">20��</option>
			<option value="21">21��</option>
			<option value="22">22��</option>
			<option value="23">23��</option>
		</select>
		<select id="select2" name="start_min">
			<option value="00">00��</option>
			<option value="15">15��</option>
			<option value="30">30��</option>
			<option value="45">45��</option>
		</select>
		</td>
		</tr>
		<tr>
		<td>
		�I����
		</td>
		<td>
		<input type="text" name="end_year" id="datepicker4" />
		<select id="select3" name="end_hour">
			<option value="00">00��</option>
			<option value="01">01��</option>
			<option value="02">02��</option>
			<option value="03">03��</option>
			<option value="04">04��</option>
			<option value="05">05��</option>
			<option value="06">06��</option>
			<option value="07">07��</option>
			<option value="08">08��</option>
			<option value="09">09��</option>
			<option value="10">10��</option>
			<option value="11">11��</option>
			<option value="12">12��</option>
			<option value="13">13��</option>
			<option value="14">14��</option>
			<option value="15">15��</option>
			<option value="16">16��</option>
			<option value="17">17��</option>
			<option value="18">18��</option>
			<option value="19">19��</option>
			<option value="20">20��</option>
			<option value="21">21��</option>
			<option value="22">22��</option>
			<option value="23">23��</option>
		</select>
		
		<select id="select4" name="end_min">
			<option value="00">00��</option>
			<option value="15">15��</option>
			<option value="30">30��</option>
			<option value="45">45��</option>
		</select>
		
		</td>
		</tr>
		<tr>
		<td>
		�{��
		</td>
		<td>
		<textarea name="honbun" id = "honbun" rows="4" cols="40" onChange = "return inputcheck('honbun',255,5,0)"></textarea>
		</td>
		</tr>
		</table>
		
	</form>
</div>

<div id="dialog2">
	<form id="form">
		<table id="popupmenu">
		<tr>
		<td>
		<label for="read_id">ID</label>
		</td>
		<td>
		<input type="text" name="read_id" readonly style="background-color:#bbbbbb;"/>
		</td>
		</tr>
		<tr>
		<td>
		<label for="read_title">����</label>
		</td>
		<td>
		<input type="text" name="read_title" id = "read_title" onChange = "return inputcheck('read_title',50,5,0)"/>
		</td>
		</tr>
<!--		
		<tr>
		<td>
		���ԏڍ׎w��I�v�V����
		</td>
		<td>
		<input type="radio" name="read_entryPlan" value="select1" onclick="entryChange();" checked="checked" />�S��
		<input type="radio" name="read_entryPlan" value="select2" onclick="entryChange();" />���Ԏw��
		</td>
		</tr>
-->
		<tr>
		<td>
		�J�n��
		</td>
		<td>
		<input type="text" name="read_start_year" id="datepicker5"/>
		<select id="select1" name="read_start_hour">
			<option value="00">00��</option>
			<option value="01">01��</option>
			<option value="02">02��</option>
			<option value="03">03��</option>
			<option value="04">04��</option>
			<option value="05">05��</option>
			<option value="06">06��</option>
			<option value="07">07��</option>
			<option value="08">08��</option>
			<option value="09">09��</option>
			<option value="10">10��</option>
			<option value="11">11��</option>
			<option value="12">12��</option>
			<option value="13">13��</option>
			<option value="14">14��</option>
			<option value="15">15��</option>
			<option value="16">16��</option>
			<option value="17">17��</option>
			<option value="18">18��</option>
			<option value="19">19��</option>
			<option value="20">20��</option>
			<option value="21">21��</option>
			<option value="22">22��</option>
			<option value="23">23��</option>
		</select>
		<select id="select2" name="read_start_min">
			<option value="00">00��</option>
			<option value="15">15��</option>
			<option value="30">30��</option>
			<option value="45">45��</option>
		</select>
		</td>
		</tr>
		<tr>
		<td>
		�I����
		</td>
		<td> 
		<input type="text" name="read_end_year" id="datepicker6"/>
		<select id="select3" name="read_end_hour">
			<option value="00">00��</option>
			<option value="01">01��</option>
			<option value="02">02��</option>
			<option value="03">03��</option>
			<option value="04">04��</option>
			<option value="05">05��</option>
			<option value="06">06��</option>
			<option value="07">07��</option>
			<option value="08">08��</option>
			<option value="09">09��</option>
			<option value="10">10��</option>
			<option value="11">11��</option>
			<option value="12">12��</option>
			<option value="13">13��</option>
			<option value="14">14��</option>
			<option value="15">15��</option>
			<option value="16">16��</option>
			<option value="17">17��</option>
			<option value="18">18��</option>
			<option value="19">19��</option>
			<option value="20">20��</option>
			<option value="21">21��</option>
			<option value="22">22��</option>
			<option value="23">23��</option>
		</select>
		
		<select id="select4" name="read_end_min">
			<option value="00">00��</option>
			<option value="15">15��</option>
			<option value="30">30��</option>
			<option value="45">45��</option>
		</select>
		</td>
		</tr>
		<tr>
		<td>
		�{��
		</td>
		<td>
		<textarea name="read_honbun" id = "read_honbun" rows="4" cols="40" onChange = "return inputcheck('read_honbun',255,5,0)"></textarea>
		</td>
		</tr>
		</table>
		
	</form>
</div>







</body>
</html>
