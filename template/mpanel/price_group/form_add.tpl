<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Price group')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this,Array())">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Code')}:{$sZir}</label>
   			<input type=text name=data[code] value="{$aData.code|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Code name')}:{$sZir}</label>
   			<input type=text name=data[code_name] value="{$aData.code_name|escape}" class="form-control btn-sm">
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
			<label>{$oLanguage->getDMessage('Level')}:</label>
   			{html_options name=data[level] options=$aBaseLevels selected=$sBaseLevels class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('ID Parent')}:</label>
   			{html_options name=data[id_parent] options=$aBaseLevelGroups selected=$sBaseLevelGroups class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('title')}:</label>
			<input type=text name=data[title] value="{$aData.title|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('page_description')}:</label>
			<textarea name=data[page_description] rows='5' class="form-control btn-sm">{$aData.page_description|escape}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('page_keyword')}:</label>
			<textarea name=data[page_keyword] rows='5' class="form-control btn-sm">{$aData.page_keyword|escape}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('associate data')}:</label>
			<textarea name=data[link_name_group] rows='5' class="form-control btn-sm">{$aData.link_name_group|escape}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('link_group_stop')}:</label>
			<textarea name=data[link_group_stop] rows='5' class="form-control btn-sm">{$aData.link_group_stop|escape}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Description')}:</label>
			{$oAdmin->getCKEditor('data[description]',$aData.description)}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('bottom_text')}:</label>
			{$oAdmin->getCKEditor('data[bottom_text]',$aData.bottom_text)}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Handbooks for filter')}:</label>
			<select name="data[handbook][]" multiple="multiple" size="6" class="form-control btn-sm">
				<option value="">Выберите параметры</option>
				{foreach from=$aHandbook key=iKey item=aValue}
					<option value="{$aValue.id}" 
				  	{if $aValue.id==$aSelectedHandbook[$aValue.id]}
				  		selected
				  	{/if}
				  	>{$aValue.name}</option>
				{/foreach}
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('is_product_list_visible')}:</label>
		    {include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_product_list_visible' bChecked=$aData.is_product_list_visible}
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
			<label>{$oLanguage->getDMessage('is menu')}:</label>
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_menu' bChecked=$aData.is_menu}
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
			<label>{$oLanguage->getDMessage('sort')}:</label>
			<input type=text name=data[sort] value="{$aData.sort|escape}" class="form-control btn-sm">
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
