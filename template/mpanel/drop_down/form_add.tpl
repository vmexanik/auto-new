<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Page')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Parent')}:</label>
			<select name=data[id_parent] class="form-control btn-sm">
					<option value=0>{$oLanguage->getDMessage('Top Level')}</option>
					{foreach from=$aParent item=aRow}
					<option value={$aRow.id}{if $aRow.id==$aData.id_parent} selected{/if} style="padding-left:{$aRow.level*12-12}px">{$aRow.nice_num} {$aRow.name}</option>
					{/foreach}
				</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			{include file='mpanel/drop_down/form_add_part.tpl'}
		</div>
	</div>
</div>
<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=hidden name=data[id] value="{$aData.id|escape}">
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>