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
			<label>{$oLanguage->getDMessage('Code')}:{$sZir}</label>
			<input type=text name=data[code] value="{$aData.code|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Order Num')}:</label>
			<input type=text name=data[num] value="{$aData.num|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Code is URL')}:</label>
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='link' bChecked=$aData.link}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Invisible Map')}:</label>
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='invisible_map' bChecked=$aData.invisible_map}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
		   <label>{$oLanguage->getDMessage('Is Menu Visible')}:</label>
		   {include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_menu_visible' bChecked=$aData.is_menu_visible}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Title')}:</label>
			<input type=text name=data[title] value="{$aData.title|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Page Description')}:</label>
			<textarea name=data[page_description] class="form-control btn-sm">{$aData.page_description}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Page Keywords')}:</label>
			<textarea name=data[page_keyword] class="form-control btn-sm">{$aData.page_keyword}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Width Limit')}:</label>
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='width_limit' bChecked=$aData.width_limit}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Without link')}:</label>
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='only_childs' bChecked=$aData.only_childs}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Is Featured')}:</label>
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_featured' bChecked=$aData.is_featured}
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