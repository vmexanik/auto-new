<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Delivery Type')}</h3>
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
			<label>{$oLanguage->getDMessage('Name')}:{$sZir}</label>
   			<input type=text name=data[name] value="{$aData.name|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Price')}:{$sZir}</label>
   			<input type=text name=data[price] value="{$aData.price|escape}" class="form-control btn-sm">
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
			<label>{$oLanguage->getDMessage('Url')}:</label>
   			<input type=text name=data[url] value="{$aData.url|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Url additional')}:</label>
   			<input type=text name=data[url_additional] value="{$aData.url_additional|escape}" class="form-control btn-sm">
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
			{include file='addon/mpanel/form_visible.tpl' aData=$aData}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Num')}:</label>
   			<input type=text name=data[num] value="{$aData.num|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('group_type')}:</label>
   			{html_options name=data[group_type] options=$aGroupType selected=$aData.group_type class="form-control btn-sm"}
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