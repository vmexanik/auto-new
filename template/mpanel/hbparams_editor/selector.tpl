<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('search')}</h3>
            </div>
		
			<form id="main_form" name="main_form" role="form" action="javascript:void(null)" onsubmit="submit_form(this)">
			<div class="card-body">
			
				<div class="row">
				<!-- body begin -->
				<div class="col-sm">
					<div class="form-group">
						<label>{$oLanguage->GetDMessage('Select table')}:</label>
						{html_options name=data[table_] options=$aTables selected=$sSelectedTable class="form-control btn-sm"}
					</div>
				</div>
				
				</div>
			</div>
		
			<div class="card-footer">
				<input type=submit value='Выбрать' class="btn btn-success btn-sm">
				
				<input type="hidden" name="action" value="hbparams_editor">
				<input type="hidden" name="is_post" value="1">
		   </div>
		   <!-- /.card-footer -->
		   </form>
		</div>
	</div>
</div>