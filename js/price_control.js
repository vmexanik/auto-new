// ---------------------------
function update_cat_parse(id) {
	if (id==0)
		return;
	
	$('#result_'+id).html('');
	
	parser_before = '';
	if ($('#parser_before_'+id).length!=0) 
		parser_before = $('#parser_before_'+id).val();

	parser_after = '';
	if ($('#parser_after_'+id).length!=0) 
		parser_after = $('#parser_after_'+id).val();

	trim_left_by = '';
	if ($('#trim_left_by_'+id).length!=0) 
		trim_left_by = $('#trim_left_by_'+id).val();
	
	trim_right_by = '';
	if ($('#trim_right_by_'+id).length!=0) 
		trim_right_by = $('#trim_right_by_'+id).val();

	return_action='';
	if ($('#return_action_'+id).length!=0) 
		return_action = $('#return_action_'+id).val();
	
	data = btoa('id='+id+'&parser_before='+parser_before+'&parser_after='+parser_after+'&trim_left_by='+trim_left_by+'&trim_right_by='+trim_right_by);
	xajax_process_browse_url('?action=price_control_update_parse&data='+data);
}
//-------------------------------------------------------------------
function viewstorage() {
	id=0;
	if ($('#id_provider').length)
		id = $('#id_provider').val();
	//$('#id_cat').hide();$('#id_cat').uniform();
	xajax_process_browse_url('?action=price_control_update_select_provider&id_provider='+id);
}
//------
function check_product(id) {
	$('#info_change_code').html('');
	id_provider = '';
	new_code = '';
	pref = '';
	if($('#pref_'+id).val()!='')
		pref = $('#pref_'+id).val(); 
	if ($('#code_change_'+id).length!=0) 
			new_code = $('#code_change_'+id).val();
	if ($('#id_provider_'+id).length!=0) 
			id_provider = $('#id_provider_'+id).val();

	return_action='';
	if ($('#return_action_'+id).length!=0) 
		return_action = $('#return_action_'+id).val();
			
	if (new_code=='' || pref=='' || id_provider=='') {
		$('#info_change_code').html('<span style="color:red">Ошибка! Для проверки кода нужно два заполненных параметра: Бренд, Код!</span>');
	}
	else {
		data = btoa('id='+id+'&pref='+pref+'&new_code='+new_code+'&return_action='+return_action);
		xajax_process_browse_url('?action=price_control_check_code&data='+data);
	}
}
//------
function check_product_e(id) {
	$('#info_change_code').html('');

	parser_before = '';
	if ($('#parser_before_'+id).length!=0) 
		parser_before = $('#parser_before_'+id).val();

	parser_after = '';
	if ($('#parser_after_'+id).length!=0) 
		parser_after = $('#parser_after_'+id).val();

	trim_left_by = '';
	if ($('#trim_left_by_'+id).length!=0) 
		trim_left_by = $('#trim_left_by_'+id).val();
	
	trim_right_by = '';
	if ($('#trim_right_by_'+id).length!=0) 
		trim_right_by = $('#trim_right_by_'+id).val();
	
	return_action='';
	if ($('#return_action_'+id).length!=0) 
		return_action = $('#return_action_'+id).val();
			
	data = btoa('id='+id+'&parser_before='+parser_before+'&parser_after='+parser_after+'&trim_left_by='+trim_left_by+'&trim_right_by='+trim_right_by+'&return_action='+return_action);
	xajax_process_browse_url('?action=price_control_check_code_e&data='+data);
}
//------
function locked_product(id) {
	$('#info_change_code').html('');
	cat_in = '';
	code_in = '';
	id_provider = '';
	if ($('#cat_'+id).length!=0)
		cat_in = $('#cat_'+id).val();
	if ($('#code_in_'+id).length!=0) 
			code_in = $('#code_in_'+id).val();
	if ($('#id_provider_'+id).length!=0) 
			id_provider = $('#id_provider_'+id).val();

	return_action='';
	if ($('#return_action_'+id).length!=0) 
		return_action = $('#return_action_'+id).val();
		
	if (cat_in=='' || code_in=='' || id_provider=='')
		$('#info_change_code').html('<span style="color:red">Ошибка! Для блокирования записи нужны три параметра: Поставщик, Бренд прайса, Код прайса!</span>');
	else {
		data = btoa('id='+id+'&return_action='+return_action);
		xajax_process_browse_url('?action=price_control_locked_code_one&data='+data);
	}
}
//------
function create_product(id) {
	$('#info_change_code').html('');

	parser_before = '';
	if ($('#parser_before_'+id).length!=0) 
		parser_before = $('#parser_before_'+id).val();

	parser_after = '';
	if ($('#parser_after_'+id).length!=0) 
		parser_after = $('#parser_after_'+id).val();

	trim_left_by = '';
	if ($('#trim_left_by_'+id).length!=0) 
		trim_left_by = $('#trim_left_by_'+id).val();
	
	trim_right_by = '';
	if ($('#trim_right_by_'+id).length!=0) 
		trim_right_by = $('#trim_right_by_'+id).val();

	return_action='';
	if ($('#return_action_'+id).length!=0) 
		return_action = $('#return_action_'+id).val();

	data = btoa('id='+id+'&parser_before='+parser_before+'&parser_after='+parser_after+'&trim_left_by='+trim_left_by+'&trim_right_by='+trim_right_by+'&return_action='+return_action);
	xajax_process_browse_url('?action=price_control_edit_code_add_product&data='+data);
}
//------
function add_product(id) {
	return_action='';
	if ($('#return_action_buffer').length!=0) 
		return_action = $('#return_action_buffer').val();
	
	data = btoa('id='+id+'&return_action='+return_action);
	xajax_process_browse_url('?action=price_control_add_product&data='+data);
}
//------
function set_new_brand(id) {
	return_action='';
	if ($('#return_action_buffer').length!=0) 
		return_action = $('#return_action_buffer').val();
	
	data = btoa('id='+id+'&return_action='+return_action);

	xajax_process_browse_url('?action=price_control_set_newbrand&data='+data);
}
//---------------------------
function check_install_cat_parse(id) {
	if (id==0)
		return;
	
	$('#result_'+id).html('');
	
	parser_before = '';
	if ($('#parser_before_'+id).length!=0) 
		parser_before = $('#parser_before_'+id).val();

	parser_after = '';
	if ($('#parser_after_'+id).length!=0) 
		parser_after = $('#parser_after_'+id).val();

	trim_left_by = '';
	if ($('#trim_left_by_'+id).length!=0) 
		trim_left_by = $('#trim_left_by_'+id).val();
	
	trim_right_by = '';
	if ($('#trim_right_by_'+id).length!=0) 
		trim_right_by = $('#trim_right_by_'+id).val();

	return_action='';
	if ($('#return_action_'+id).length!=0) 
		return_action = $('#return_action_'+id).val();
	
	data = btoa('id='+id+'&parser_before='+parser_before+'&parser_after='+parser_after+'&trim_left_by='+trim_left_by+'&trim_right_by='+trim_right_by);
	xajax_process_browse_url('?action=price_control_check_install_cat_parse&data='+data);
}

//------
function change_code(id) {
	$('#info_change_code').html('');
	id_provider = '';
	new_code = '';
	pref = '';
	if($('#pref_'+id).val()!='')
		pref = $('#pref_'+id).val(); 
	if ($('#code_change_'+id).length!=0) 
			new_code = $('#code_change_'+id).val();
	if ($('#id_provider_'+id).length!=0) 
			id_provider = $('#id_provider_'+id).val();

	return_action='';
	if ($('#return_action_'+id).length!=0) 
		return_action = $('#return_action_'+id).val();
		
	/*is_chected_code = "0";
	if ($('#checked_code_ok_'+id).val()=="1")
		is_chected_code = "1";
	*/
	if (new_code=='' || pref=='' || id_provider=='') {
		$('#info_change_code').html('<span style="color:red">Ошибка! Для создания замены нужно три заполненных параметра: Поставщик, Бренд, Код!</span>');
	}
	else {
		data = btoa('id='+id+'&pref='+pref+'&new_code='+new_code+'&return_action='+return_action);
		xajax_process_browse_url('?action=price_control_replace_code&data='+data);
	}

	/*else if (is_chected_code == "0") {
			$('#info_change_code').html('<span style="color:red">Ошибка! Для создания замены нужно выполнить проверку товара!</span>');
		 }
		 else {
			data = btoa('id='+id+'&pref='+pref+'&new_code='+new_code+'&return_action='+return_action);
			xajax_process_browse_url('?action=price_replace_code&data='+data);
		 }
	 */
}