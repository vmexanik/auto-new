$(function() {
		var status=$('#status');
		var status_warning='Only PDF, JPG, PNG or GIF files are allowed';
		var status_process='Uploading...';
		var id_file=$("#a_data_id").val();
		new AjaxUpload( $('#upload'+$("#a_data_id").val()), { 
			action: '?action=catalog_manager_upload_pic&id_cat_part='+$("#a_data_id").val(),
			name: 'uploadfile',
			onSubmit: function(file, ext) {
				 if (! (ext && /^(jpg|png|jpeg|gif|pdf)$/.test(ext))) {
                    // extension is not allowed 
					status.text(status_warning);
					return false;
				 }
				status.text(status_process);
			},
			onComplete: function(file, response) {
				//On completion clear the status
				status.text('');
				//Add uploaded file to list
				if(response>0)
					$('<span></span>').appendTo('#files'.$("#a_data_id").val()).html('<a target=blaank href="/imgbank/Image/pic/'+response+'_'+file+'">'+file+'</a><br />').addClass('success');
				 else 
					$('<span></span>').appendTo('#files'.$("#a_data_id").val()).text(file).addClass('error');
			}
		});
});