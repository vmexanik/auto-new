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
				        <label>Customer:</label>
				        <input type="text" name=search[customer_login] value="{$aSearch.customer_login|escape}" maxlength=20 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>Date from:</label>
				        <input type="text" id=date_from name=search[date_from] class="form-control btn-sm" readonly value="{$aSearch.date_from|escape}" onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				
				        <label>Date To:</label>
				        <input type="text" id=date_to name=search[date_to] class="form-control btn-sm" readonly value="{$aSearch.date_to|escape}" onclick="popUpCalendar(this, this, 'dd.mm.yyyy');">
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