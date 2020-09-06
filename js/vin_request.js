var MstarVinRequest=function (data) {
	//this.data=data;
};

MstarVinRequest.prototype.CheckForm = function (form)
{
	var ErrMsg='';

	form.vin.value = form.vin.value.replace(/ /g,'');
	if (form.vin.value.length<17) ErrMsg=ErrMsg+$("#vin_request_17symbol").val()+"\n";
	if (form.model.value.length<1) ErrMsg=ErrMsg+$("#vin_request_model_empty").val()+"\n";
	if (form.id_model_detail.value.length<1) ErrMsg=ErrMsg+$("#vin_request_modification_empty").val()+"\n";
	if ($("input[name^=azpDescript]:first").val().length<4) ErrMsg=ErrMsg+$("#vin_request_spareparts_empty").val()+"\n";

	if (document.getElementById('id_partner_region')) {
		var sIdIregion=document.getElementById('id_partner_region')
		.options[document.getElementById('id_partner_region').selectedIndex].value;
		if (sIdIregion==0) ErrMsg=ErrMsg+$("#vin_request_region_empty").val()+"\n";
	}

	//get all values and assign to variable
	var sDescriptionValues  = $("input[name=azpDescript]").map(function(){
  			 return $(this).val();
		}).get().join(";");
	var sCountValues  = $("input[name=azpCnt]").map(function(){
  			 return $(this).val();
		}).get().join(";");

	//assign to submit variables
	$("input[name=azpDescriptArr]").val(sDescriptionValues);
	$("input[name=azpCntArr]").val(sCountValues);

	if ( (form.mobile && form.mobile.value.length!=7) || (form.mobile && isNaN(parseInt(form.mobile.value)))) {
		ErrMsg=ErrMsg+$("#vin_request_phoneformat_error").val()+" "+form.mobile.value+" \n";
	}
	var sMarka=document.getElementById('marka').options[document.getElementById('marka').selectedIndex].value;
	if (sMarka=='Renault' || sMarka=='Opel') {
		if (form.kpp_number.value.length<1) ErrMsg=ErrMsg+$("#vin_request_kpp_empty").val()+"\n";
		if (form.utable.value.length<1) ErrMsg=ErrMsg+$("#vin_request_podkapot_empty").val()+"\n";
		if (form.engine_number.value.length<1) ErrMsg=ErrMsg+$("#vin_request_engine_number_empty").val()+"\n";
		if (form.engine_code.value.length<1) ErrMsg=ErrMsg+$("#vin_request_engine_kod_empty").val()+"\n";
	}
	if (ErrMsg=='') {
		var sMessage = form.isUserAuth.value == 1 ?
		$("#vin_request_ok_auth_user").val() :
		$("#vin_request_ok_user").val();

		return confirm(sMessage);
	}
	else {
		window.alert($("#vin_request_error_message").val()+"\n"+ErrMsg);
		return false;
	}
};

MstarVinRequest.prototype.ChangeForm = function (form)
{
	sMarka=document.getElementById('marka').options[document.getElementById('marka').selectedIndex].value;
	if (sMarka=='Renault' || sMarka=='Opel') {
		document.getElementById('kpp_number').value="";
		document.getElementById('utable').value="";
		document.getElementById('engine_number').value="";
		document.getElementById('engine_code').value="";
		document.getElementById('engine_volume').value="";
		document.getElementById('gur_available').checked = false;
		mvr.ToogleTr('');
	}
	else {
		mvr.ToogleTr('none');
	}
}

MstarVinRequest.prototype.ToogleTr = function(sDisplay)
{
	document.getElementById('tr_utable_id').style.display = sDisplay;
	document.getElementById('tr_engine_number_id').style.display = sDisplay;
	document.getElementById('tr_engine_code_id').style.display = sDisplay;
	document.getElementById('tr_engine_volume_id').style.display = sDisplay;
	document.getElementById('tr_kpp_number_id').style.display = sDisplay;
	document.getElementById('tr_gur_available_id').style.display = sDisplay;
}

MstarVinRequest.prototype.AddRow = function (form)
{
	var a=parseInt(form.RowCount.value);
	var b=a+1;
	if (a!=100) {
		form.RowCount.value=b;
		var tr=document.getElementsByClassName("queryByVIN")[0].cloneNode(true);
			
		var tb=document.getElementById("btn_queryByVIN").closest("tbody");
		tr.cells[0].innerHTML="<b>"+b+":</b>\n";
		tr.cells[1].innerHTML="<input type=text name=\"azpDescript"+b+"\" maxlength=\"100\" style=\"max-width: 75% !important;\" placeholder=\"наименования детали\" value=\"\">" +
				"<input type=text name=\"azpCnt"+b+"\" maxlength=\"2\" style=\"max-width: 15% !important; margin-left: 3px;\" " +
						" placeholder=\"кол-во\" value=\"1\">\n";
		tb.insertBefore(tr, document.getElementById("btn_queryByVIN").closest("tr"));
		
	}
	else window.alert($("#vin_request_less_100lines").val());
};

MstarVinRequest.prototype.DeleteRow = function (form)
{
	var a=form.RowCount.value;
	var b=a-1;
	if (a!=1) {
		form.RowCount.value=b;
		var length=document.getElementsByClassName("queryByVIN").length;
		document.getElementsByClassName("queryByVIN")[length-1].remove();
	}
	else window.alert($("#vin_request_parts_list_empty").val());
};


MstarVinRequest.prototype.AddManagerRow = function (form)
{
	var a=parseInt(form.RowCount.value);
	var b=a+1;
	if (a!=100) {
		form.RowCount.value=b;
		var tb=document.getElementById("queryByVIN");
		var tr=tb.tBodies[0].rows[0].cloneNode(true);

		tb.tBodies[0].appendChild(tr);

		tr.cells[0].innerHTML=b+" <input type=checkbox class='js-checkbox' name='part["+b+"][i]' value='1' checked>";
		tr.cells[1].innerHTML="<input type=checkbox class='js-checkbox' name='part["+b+"][code_visible]' value='1'>";
		tr.cells[2].innerHTML="<input type=text name='part["+b+"][name]' value='' style='width:250px;'>";
		tr.cells[3].innerHTML="<input type=text name='part["+b+"][code]' value=''>";
		/*tr.cells[4].innerHTML="<input type=text name='part["+b+"][user_input_code]' value=''>";*/
		tr.cells[4].innerHTML="<input type=text name='part["+b+"][number]' value='' style='width:55px;'>";
		tr.cells[5].innerHTML="<input type=text name='part["+b+"][weight]' value='' style='width:50px;'> кг";
		tr.cells[6].innerHTML="<div style='width:88px'>&nbsp;<input type=hidden name='part["+b+"][user_input_code]' value=''></div>";
	}
	//else window.alert("В одном запросе может быть не более 100 строк");
};


MstarVinRequest.prototype.HilightPartByCode = function (part_code, hex_color)
{
	try {
		if( ! part_code ) return;
		var v = parseInt(part_code);
		var sHilightColor;
		if( !hex_color ) {
			sHilightColor = '#A52A2A';
		} else {
			sHilightColor = '#' + hex_color;
		}
		var aHilightCols = Array(1, 2, 3);
		var fel = document.getElementById('table_form');
		var tbl = fel.getElementsByTagName("TABLE");
		for(var r=0; r < tbl[0].rows.length; r++) {
			for(var c=0; c < tbl[0].rows[r].cells.length; c++) {
				if(c == 2){
					var cls = tbl[0].rows[r].cells[2];
					if(v == parseInt(cls.innerHTML)) {
						for(var j=0; j<aHilightCols.length; j++) {
							tbl[0].rows[r].cells[aHilightCols[j]].style.color = sHilightColor;
						}
					}
				}
			}
		}
	} catch ( e ) { }
}

var mvr=new MstarVinRequest();
