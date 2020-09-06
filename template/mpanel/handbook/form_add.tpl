<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Handbooks')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->
			
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Name')}:{$sZir}</label>
			<input type=text id=data[name] name=data[name] value="{$aData.name}" class="form-control btn-sm"/>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Table')}:{$sZir}</label>
			<input type=text id=data[table_] name=data[table_] value="{$aData.table_}" class="form-control btn-sm"/>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Order number')}:{$sZir}</label>
			<input type=text id=data[number] name=data[number] value="{$aData.number}" class="form-control btn-sm"/>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Collapsed')}:</label>
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_collapsed' bChecked=$aData.is_collapsed}
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