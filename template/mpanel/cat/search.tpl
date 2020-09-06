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
				        <input type=text name=search[id] class="form-control btn-sm" value="{$aSearch.id|escape}" maxlength=50>
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetdMessage('name')}:</label>
				        <input type=text name=search[name] value="{$aSearch.name|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetdMessage('title')}:</label>
				        <input type=text name=search[title] value="{$aSearch.title|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('id_tof')}:</label>
				        <input type=text name=search[id_tof] value="{$aSearch.id_tof|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->GetdMessage('pref')}:</label>
				        <input type=text name=search[pref] value="{$aSearch.pref|escape}" maxlength=50 class="form-control btn-sm">
				    </div>
				
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('is_brand')}:</label>
				        <select name="search[is_brand]" class="form-control btn-sm">
				            <option value='1' {if $aSearch.is_brand=='1' } selected {/if}>{$oLanguage->getDMessage('Yes')}</option>
				            <option value='0' {if $aSearch.is_brand=='0' } selected {/if}>{$oLanguage->getDMessage('No')}</option>
				            <option value='' {if $aSearch.is_brand=='' } selected {/if}>{$oLanguage->getDMessage('Ignore')}</option>
				        </select>
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('is_vin_brand')}:</label>
				        <select name="search[is_vin_brand]" class="form-control btn-sm">
				            <option value='1' {if $aSearch.is_vin_brand=='1' } selected {/if}>{$oLanguage->getDMessage('Yes')}</option>
				            <option value='0' {if $aSearch.is_vin_brand=='0' } selected {/if}>{$oLanguage->getDMessage('No')}</option>
				            <option value='' {if $aSearch.is_vin_brand=='' } selected {/if}>{$oLanguage->getDMessage('Ignore')}</option>
				        </select>
				
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('is_main')}:</label>
				        <select name="search[is_main]" class="form-control btn-sm">
				            <option value='1' {if $aSearch.is_main=='1' } selected {/if}>{$oLanguage->getDMessage('Yes')}</option>
				            <option value='0' {if $aSearch.is_main=='0' } selected {/if}>{$oLanguage->getDMessage('No')}</option>
				            <option value='' {if $aSearch.is_main=='' } selected {/if}>{$oLanguage->getDMessage('Ignore')}</option>
				        </select>
				    </div>
				</div>
				<div class="col-sm">
				    <div class="form-group">
				        <label>{$oLanguage->getDMessage('visible')}:</label>
				        <select name="search[visible]" class="form-control btn-sm">
				            <option value='1' {if $aSearch.visible=='1' } selected {/if}>{$oLanguage->getDMessage('Yes')}</option>
				            <option value='0' {if $aSearch.visible=='0' } selected {/if}>{$oLanguage->getDMessage('No')}</option>
				            <option value='' {if $aSearch.visible=='' } selected {/if}>{$oLanguage->getDMessage('Ignore')}</option>
				        </select>
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