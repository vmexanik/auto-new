<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('search')}</h3>
            </div>
		
			<form id="filter_form" name="filter_form" role="form" action="javascript:void(null)" onsubmit="submit_form(this)">
			<div class="card-body">
			
				<div class="row">
				<!-- body begin -->
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetDMessage('Admin')}:</label>
				        <input type=text name=search[login] value="{$aSearch.login|escape}" maxlength=20 class="form-control btn-sm">
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetDMessage('Date from')}:</label>
				        <input id=date_from name=search[date_from] class="form-control btn-sm" readonly="readonly" value="{$aSearch.date_from|escape}" onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetDMessage('Date To')}:</label>
				        <input id=date_to name=search[date_to] class="form-control btn-sm" readonly="readonly" value="{$aSearch.date_to|escape}" onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetDMessage('Action')}:</label>
				        <input type=text name=search[action] value="{$aSearch.action|escape}" maxlength=20 class="form-control btn-sm">
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetDMessage('TableName')}:</label>
				        <input type=text name=search[table_name] value="{$aSearch.table_name|escape}" maxlength=20 class="form-control btn-sm">
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetDMessage('IP')}:</label>
				        <input type=text name=search[ip] value="{$aSearch.ip|escape}" maxlength=20 class="form-control form-control-sm">
				    </div>
				</div>
				<!-- body end -->
		        </div>
				
			</div>
			<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=button class="btn btn-danger btn-sm" value="{$oLanguage->getDMessage('Clear')}"
				onclick="xajax_process_browse_url('?{$sSearchReturn|escape}')">
				<input type=submit value='Search' class="btn btn-success btn-sm">
				
				<input type=hidden name=action value={$sBaseAction}_search>
				<input type=hidden name=return value="{$sSearchReturn|escape}">
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>