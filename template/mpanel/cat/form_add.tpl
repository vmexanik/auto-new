<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Catalog')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())" >
<div class="card-body">
<!-- body begin -->


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
			<label>{$oLanguage->getDMessage('Pref')}:{$sZir} {$oLanguage->getContextHint("catalog_pref")}</label>
   			<input type=text name=data[pref] value="{$aData.pref|escape}" maxlength="3" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Title')}:{$sZir}</label>
   			<input type=text name=data[title] value="{$aData.title|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Description')}:</label>
   			<textarea name=data[description] class="form-control btn-sm">{$aData.description}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			{include file='addon/mpanel/form_image.tpl' aData=$aData}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('is_use_own_logo')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_use_own_logo' bChecked=$aData.is_use_own_logo}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('link')}:</label>
   			<input type=text name=data[link] value="{$aData.link|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('country')}:</label>
   			<input type=text name=data[country] value="{$aData.country|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('address')}:</label>
   			<input type=text name=data[addres] value="{$aData.addres|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Descr')}:</label>
			{$oAdmin->getCKEditor('data[descr]',$aData.descr, 650, 300)}
		</div>
	</div>
</div>

<hr>

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('parser_patern')}:</label>
   			<input type=text name=data[parser_patern] value="{$aData.parser_patern}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('parser_before')}:</label>
   			<input type=text name=data[parser_before] value="{$aData.parser_before}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('parser_after')}:</label>
   			<input type=text name=data[parser_after] value="{$aData.parser_after}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('trim_left_by')}:</label>
   			<input type=text name=data[trim_left_by] value="{$aData.trim_left_by}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('trim_right_by')}:</label>
   			<input type=text name=data[trim_right_by] value="{$aData.trim_right_by}" class="form-control btn-sm">
		</div>
	</div>
</div>

<hr>

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('id_sync')}:</label>
   			<input type=text name=data[id_sync] value="{$aData.id_sync|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('id tof')}:</label>
   			<input type=text name=data[id_tof] value="{$aData.id_tof|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('is brand')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_brand' bChecked=$aData.is_brand}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('is main')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_main' bChecked=$aData.is_main}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('is vin brand')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_vin_brand' bChecked=$aData.is_vin_brand}
		</div>
	</div>
</div>
<hr>
{if !$aData.is_cat_virtual}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('id cat virtual')}:</label>
   			{html_options name=data[id_cat_virtual] options=$aCatVirtual selected=$aData.id_cat_virtual class="form-control btn-sm"}
		</div>
	</div>
</div>
{/if}
{if !$aData.id_cat_virtual}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('is cat virtual')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_cat_virtual' bChecked=$aData.is_cat_virtual}
		</div>
	</div>
</div>
{/if}
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