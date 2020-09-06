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
				        <label>{$oLanguage->GetdMessage('id')}:</label>
				        <input type=text name=search[id] value="{$aSearch.id|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetdMessage('id_price_group')}:</label>
				        <input type=text name=search[id_price_group] value="{$aSearch.id_price_group|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('id_provider')}:</label>
				        <input type=text name=search[id_provider] value="{$aSearch.id_provider|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('code')}:</label>
				        <input type=text name=search[code] value="{$aSearch.code|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('Price')}:</label>
				        <input type=text name=search[price] value="{$aSearch.price|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('pref')}:</label>
				        <input type=text name=search[pref] value="{$aSearch.pref|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('cat')}:</label>
				        <input type=text name=search[cat] value="{$aSearch.cat|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('post_date')}:</label>
				        <input type=text name=search[post_date] value="{$aSearch.post_date|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('Term')}:</label>
				        <input type=text name=search[term] value="{$aSearch.term|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('stock')}:</label>
				        <input type=text name=search[stock] value="{$aSearch.stock|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<!-- body end -->
		    </div>
		    <div class="row">
   				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetdMessage('part_rus')}:</label>
				        <input type=text name=search[part_rus] value="{$aSearch.part_rus|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
   				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetdMessage('part_ua')}:</label>
				        <input type=text name=search[part_ua] value="{$aSearch.part_ua|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetdMessage('number_min')}:</label>
				        <input type=text name=search[number_min] value="{$aSearch.number_min|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
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