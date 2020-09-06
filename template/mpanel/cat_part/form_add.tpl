<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Part')}</h3>
            </div>
		
{*assign var="sBaseUpload" value="upload_excel"}
{include file='addon/mpanel/form_upload.tpl' sBaseUpload=$sBaseUpload sFormAction="/single/mpanel_file_upload.php?BaseUpload=$sBaseUpload"*}

<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->
	
{if ($oLanguage->getConstant('use_price_control',0))}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Item Code')}:</label>
				   <b>{$aData.item_code}</b>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('brand')}:</label>
				{$aData.brand}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Name price')}:</label>
				{$aData.name}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Pref')}:</label>
				   {html_options name=data[pref] options=$aPref selected=$aData.pref class="form-control btn-sm"}
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
			<label>{$oLanguage->getDMessage('Name Rus')}:</label>
				   <input type=text name=data[name_rus] value="{$aData.name_rus|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Weight')}:</label>
				<input type=text name=data[weight] value="{$aData.weight|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
			
			
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('is_checked_code_ok')}:</label>
				{strip}
					{if $aData.is_checked_code_ok==1}
						<input type="hidden" name="data[is_checked_code_ok]" value="0">
					{/if}
					<input type="checkbox" name="data[is_checked_code_ok]" value="1" {if $aData.is_checked_code_ok==1} checked="checked"{/if}>
				{/strip}
		</div>
	</div>
</div>
{else}
			
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Item Code')}:</label>
				   <b>{$aData.item_code}</b>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Pref')}:</label>
				   {html_options name=data[pref] options=$aPref selected=$aData.pref class="form-control btn-sm"}
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
			<label>{$oLanguage->getDMessage('Name Rus')}:</label>
				   <input type=text name=data[name_rus] value="{$aData.name_rus|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Information')}:</label>
				   <textarea name=information rows=4 class="form-control btn-sm">{$aData.information}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Weight')}:</label>
				<input type=text name=data[weight] value="{$aData.weight|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
			
{/if}

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