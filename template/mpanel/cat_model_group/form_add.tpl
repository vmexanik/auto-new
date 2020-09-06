{literal}
<style>
.select2-selection {
    min-height: 200px !important;
}
</style>
{/literal}
<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Cat Model group')}</h3>
            </div>
		

<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Brand')}:{$sZir}</label>
   			{html_options name=data[id_make] options=$aCat selected=$aData.id_make class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Name')}:{$sZir}</label>
   			<input type=text name=data[name] value="{$aData.name|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('code')}:{$sZir}</label>
   			<input type=text name=data[code] value="{$aData.code|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('id_models')}:</label>
			{html_options name=data[id_models_selected][] options=$aModels selected=$aModelsPreview multiple="multiple" size="15" id='select_model'  class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			{include file='addon/mpanel/form_visible.tpl' aData=$aData}
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