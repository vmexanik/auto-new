var MstarForm=function (data) {
	//this.data=data;
};

// need /libp/jquery/jquery.min.js
MstarForm.prototype.SetFocus = function (id_element)
{
	$("#"+id_element)[0].focus();
}

// need /libp/jquery/jquery.min.js
MstarForm.prototype.Location = function(sAction,aId)
{
	var sAdd="";
	//alert(sAction);
	//var sss="";
	//if ($("#cart_notconfirm:checked").val()==1) {sss=1}
	//sUrl=$("#code").text();
	for (key in aId) {
		if (key%2==0) {
			sAdd=sAdd+"&"+aId[key]+"=";
		} else {
			sAdd=sAdd+$("#"+aId[key]).val();
		}
	}
	location.href=sAction+sAdd;
}

MstarForm.prototype.InitPrint = function (sHeader,sFooter, bPortrait, leftMargin, topMargin, rightMargin, bottomMargin, bPrint)
{
	if (!factory.object) {
		return
	} else {
		factory.printing.header = sHeader;
		factory.printing.footer = sFooter;
		factory.printing.portrait = bPortrait;
		factory.printing.leftMargin = leftMargin;
		factory.printing.topMargin = topMargin;
		factory.printing.rightMargin = rightMargin;
		factory.printing.bottomMargin = bottomMargin;
		//factory.printing.Print(bPrint);

	}
}

MstarForm.prototype.SetFocusOnEnter = function (event, idFocus)
{
	if(event.keyCode==13){
		mf.SetFocus(idFocus);
		return true;
	}
	return false;
}

MstarForm.prototype.BlockEnterIf = function (evt,aId)
{
	evt = (evt) ? evt : event;
	var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
	
	for (key in aId) {
		if (key%2==0) {
			sValue1=$("#"+aId[key]).val();
		} else {
			sValue2=aId[key];
			
			if ((sValue1=='----' || sValue1==sValue2)&& charCode == 13) {
				return false;
			} else {
				return true;
			}
		}
	}
}

var mf=new MstarForm();

// buh/form_changeling && buh/form_changeling_preview
//$().ready(function() {
//	$("#id_buh").change(function() {
//		if ($("#id_subconto").val()) document.getElementById("id_subconto").value="";
//		if ($("#subconto").val()) document.getElementById("subconto").value="";
//	});
//	$("#subconto").click(function() {
//		$("#subconto").autocomplete("/?action=buh_get_subconto&id_buh=" + $("#id_buh").val(), {
//			width: 260,
//			selectFirst: true
//		});
//	});	
//	$("#subconto").change(function() {
//		$("#subconto").autocomplete("/?action=buh_get_subconto&id_buh=" + $("#id_buh").val(), {
//			width: 260,
//			selectFirst: true
//		});
//	});
//	$("#subconto").result(function(event, data, formatted) {
//		if (data)
//			$("#id_subconto").val(data[1]);
//	});
//});


// buh/form_add_amount
$().ready(function() {
	$("#id_buh_debit").change(function() {
		$("#id_buh_debit_subconto1").val("");
		$("#debit_subconto1").val("");
	});
	
	$("#debit_subconto1").autocomplete("/?action=buh_get_subconto", {
			width: 260,
			selectFirst: true,
			extraParams:{id_buh:function(){return $('#id_buh_debit').val();}}
		});
	
	$("#debit_subconto1").result(function(event, data, formatted) {
		if (data) $("#id_buh_debit_subconto1").val(data[1]);
	});
		
	$("#id_buh_credit").change(function() {
		$("#id_buh_credit_subconto1").val("");
		$("#credit_subconto1").val("");
	});
	
	$("#credit_subconto1").autocomplete("/?action=buh_get_subconto", {
			width: 260,
			selectFirst: true,
			extraParams:{id_buh:function(){return $('#id_buh_credit').val();}}
		});
	
	$("#credit_subconto1").result(function(event, data, formatted) {
		if (data) $("#id_buh_credit_subconto1").val(data[1]);
	});
});
