<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('News item')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);'
	onsubmit="submit_form(this,Array())">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Date')}:</label>
				<input type=text name=data[post_date] value='{$aData.post_date|date_format:"%d-%m-%Y"}' class="form-control btn-sm">
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
			<label>{$oLanguage->getDMessage('name')}: {$sZir}</label>
				<textarea name=data[name] class="form-control btn-sm">{$aData.name}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Short')}: {$sZir}</label>
				<textarea name=data[short] class="form-control btn-sm">{$aData.short}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Full')}:</label>
				{$oAdmin->getCKEditor('data[full]',$aData.full)}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Page title')}</label>
				<textarea name=data[title] class="form-control btn-sm">{$aData.title}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Page description')}</label>
				<textarea name=data[page_description] class="form-control btn-sm">{$aData.page_description}</textarea>
		</div>
	</div>
</div>	
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Page keyword')}</label>
				<textarea name=data[page_keyword] class="form-control btn-sm">{$aData.page_keyword}</textarea>
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
			{include file='addon/mpanel/form_visible.tpl' aData=$aData}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
	    	<label>{$oLanguage->getDMessage('has_full_link')}:</label>
		    {include file='addon/mpanel/form_checkbox.tpl' sFieldName='has_full_link' bChecked=$aData.has_full_link}
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