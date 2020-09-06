$(function() {
    $('.js-popup-toggle').click(function() {
        $('.js_manager_panel_popup').slideToggle();
    });
    
	$(document).on("mousedown", ".date-range", function(e) {
		$('.date-range').daterangepicker({
			"showDropdowns": true,
			"autoUpdateInput": false,
			"locale": {
				"format": "DD.MM.YYYY",
				"separator": " - ",
				"applyLabel": "Применить",
				"cancelLabel": "Отмена",
				"fromLabel": "От",
				"toLabel": "до",
				"customRangeLabel": "Выбрать",
				"daysOfWeek": [
					"вс",
					"пн",
					"вт",
					"ср",
					"чт",
					"пт",
					"сб"
				],
				"monthNames": [
					"Январь",
					"Февраль",
					"Март",
					"Апрель",
					"Май",
					"Июнь",
					"Июль",
					"Август",
					"Сентябрь",
					"Октябрь",
					"Ноябрь",
					"Декабрь"
				],
 				"firstDay": 1
			},
		}, 
		function(start, end, label) {
			$('.date-range').val(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));
			xajax_process_browse_url('/?action=manager_panel_manager_package_list&search_order_status='+$('#id_order_status').val()+'&search_manager='+$('#id_manager').val()+'&search_date='+$('#id_date').val()+'&search_delivery='+$('#id_delivery').val());
		});
	});
	
	$(document).on("click", ".daterangepicker .cancelBtn", function(e) {
		$('.date-range').val('');
		xajax_process_browse_url('/?action=manager_panel_manager_package_list&search_order_status='+$('#id_order_status').val()+'&search_manager='+$('#id_manager').val()+'&search_date='+$('#id_date').val()+'&search_delivery='+$('#id_delivery').val());
	});

	$(document).on("mousedown", ".date-picker", function(e) {
		$('.date-picker').datetimepicker({
			locale: 'ru', 
			format: 'DD.MM.YYYY'
		});
	});
	
	$(document).on("blur", ".date-picker", function(e) {
		$('#'+$(this).attr('id')+'-value').val(mysqlDate($(this).val()));
	});
});
//--------------------------------------------------
function GetTotal() {
	var number = document.getElementById('number').value;
	var price = document.getElementById('price').value;
	if(price) var total = number * price;
	else var total = 0;
	document.getElementById('total').value = total;
}
//--------------------------------------------------
function validate(evt,dot) {
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	if(dot) var regex = /[0-9]|\./;
	else var regex = /[1-9]/;
	if( !regex.test(key) ) {
	theEvent.returnValue = false;
	if(theEvent.preventDefault) theEvent.preventDefault();
	}
}
//--------------------------------------------------
function change_delivery_type() {
	$('#id_delivery_type').change(function() {
    	if($( this ).val()==6) {
    		$('.delivery_nova').show();
    		$('.delivery_default').hide();
    	} else {
    		$('.delivery_nova').hide();
    		$('.delivery_default').show();
    	}
    });
}
// --------------------------------------------------
function save_manager_panel_order($order_id) {
	$cnt = $("[name^='name_translate_']").length;
	$name = '';
	if ($cnt > 0) {
		$("[name^='name_translate_']").each(function () {
			$mass = $(this).attr('id').split('_');
			$name += $mass[2] +"|"+ encodeURIComponent($(this).val())+"::";
	    });
	}
	$cnt = $("[name^='number_']").length;
	$number = '';
	if ($cnt > 0) {
		$("[name^='number_']").each(function () {
			$mass = $(this).attr('id').split('_');
			$number += $mass[1]+"|"+encodeURIComponent($(this).val())+"::";
	    });
	}
	$cnt = $("[name^='price_']").length;
	$price = '';
	if ($cnt > 0) {
		$("[name^='price_']").each(function () {
			$mass = $(this).attr('id').split('_');
			$price += $mass[1] +"|"+encodeURIComponent($(this).val())+"::";
	    });
	}
	$add = '&id='+$order_id+'&name='+$name+"&number="+$number+"&price="+$price;
	xajax_process_browse_url('/?action=manager_panel_edit_order_apply'+$add);
	return false;
}
//-----------------------------------------------------------------------------
function add_auto_form_return() {
	$('#list_auto').show();
	$('#add_form_auto').hide();
}
//-----------------------------------------------------------------------------
function popup_submit_mp($element) {
	var ErrMsg='';
	
	// check correct fill fields
	vin = $('[name="data[vin]"]').val();
	
	vin = vin.replace(/ /g,'');
	if (vin.length<17) ErrMsg=ErrMsg+$("#add_auto_17symbol").val()+"\n";

	model = $('#ctlModelOwnAuto').val();
	if (model == null || model == 0) ErrMsg=ErrMsg+$("#add_auto_model_empty").val()+"\n";
	
	modyfication = $('#ctlModelDetailOwnAuto').val();
	if (modyfication == null || modyfication == 0) ErrMsg=ErrMsg+$("#add_auto_modyfication_empty").val()+"\n";

	volume='';
	
	if (ErrMsg == '') {
		is_abs = 0;
		if ($('input:checkbox[name="data[is_abs]"]:checked').val() != undefined)
			is_abs = $('input:checkbox[name="data[is_abs]"]:checked').val();
		
		is_hyd_weel = 0;
		if ($('input:checkbox[name="data[is_hyd_weel]"]:checked').val() != undefined)
			is_hyd_weel = $('input:checkbox[name="data[is_hyd_weel]"]:checked').val();

		is_conditioner = 0;
		if ($('input:checkbox[name="data[is_conditioner]"]:checked').val() != undefined)
			is_conditioner = $('input:checkbox[name="data[is_conditioner]"]:checked').val();

		id_cp = 0;
		if ($('input:hidden[name=id_cp]').val() != undefined)
			id_cp = $('input:hidden[name=id_cp]').val();

		id_user = 0;
		if ($('input:hidden[name=id_user]').val() != undefined)
			id_user = $('input:hidden[name=id_user]').val();

		$str="/?action=manager_panel_user_edit_auto_add&vin="+vin+"&id_model="+model;
		$str+="&id_model_detail="+modyfication+"&volume="+volume+"&id_make="+$('#ctlMakeOwnAuto').val();
		$str+="&body="+$('[name="data[body]"]').val()+"&engine="+$('[name="data[engine]"]').val();
		$str+="&country_producer="+$('[name="data[country_producer]"]').val()+"&month="+$('[name="data[month]"]').val();
		$str+="&year="+$('[name=Year]').val()+"&kpp="+$('[name="data[kpp]"]').val()+"&wheel="+$('[name="data[wheel]"]').val();
		$str+="&is_abs="+is_abs+"&is_hyd_weel="+is_hyd_weel+"&is_conditioner="+is_conditioner;
		$str+="&customer_comment="+encodeURIComponent($('[name="data[customer_comment]"]').val());
		$str+="&id_cp="+id_cp+"&id_user="+id_user;

		xajax_process_browse_url($str);
	}
	else {
		alert($("#add_auto_error").val()+"\n" + ErrMsg);
	}
	return false;
}
//--------------------------------------------------
function split_manager_panel_order($order_id) {
	var log = $("[name^='row_check']:checked").map(function () {
	      return this.value;
    }).get().join(",");
	
	var cart_items = $('#cart_items').val();
	
	if (cart_items > 0 && cart_items==$("[name^='row_check']:checked").length)
		alert('Нельзя при разделении заказа выделять все позиции!');
	
	$str = '/?action=manager_panel_edit_order_split_form&id='+$order_id+'&checked='+log;

	xajax_process_browse_url($str);
}
//-----------------------------------------------------------------------------
function InitCalendar(id) {
	$('#datetimepicker_'+id).datetimepicker({
        locale: 'ru',
        sideBySide: true
    });
}
//-----------------------------------------------------------------------------
function SaveCalendar(id) {
	var value = $('#datetimepicker_'+id).find("input").val();
	
	if (value!='')
		xajax_process_browse_url('?action=manager_panel_purchase_save_calendar&id='+id+'&value='+encodeURIComponent(value));
}
//-----------------------------------------------------------------------------
function change_value(id,type) {
	if (type=='kurs') {
		$('#id_kurs_'+id).hide();
		$('#id_kurs_edit_'+id).show();
	}
	else if (type=='price_original') {
		$('#id_price_original_'+id).hide();
		$('#id_price_original_edit_'+id).show();
	}
}
//-----------------------------------------------------------------------------
function change_value_apply(id,type) {
	if (type=='kurs') {
		var value = parseFloat($('#id_kurs_value_'+id).val());
		if (value<=0)
			return;
	}
	else if (type=='price_original') {
		var value = parseFloat($('#id_price_original_value_'+id).val());
		if (value<=0)
			return;
	}
	xajax_process_browse_url('?action=manager_panel_purchase_save_value&id='+id+'&value='+encodeURIComponent(value)+'&type='+type);
}
//-----------------------------------------------------------------------------
function mysqlDate(date) {
	var data = date.split('.');
	return [data[2], data[1], data[0]].join('-');
}
//-----------------------------------------------------------------------------
function GetRowIds()
{
	var values = new Array();
	$.each($("input[name='row_check[]']:checked"), function() {
		values.push($(this).val());
	});
	$("input[name='rows']").val(values);
}
//-----------------------------------------------------------------------------