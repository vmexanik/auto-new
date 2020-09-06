<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Account')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('IdBuh')}:{$sZir}</label>
   			<input type=text name=data[id_buh] value="{$aData.id_buh|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Currency')}:</label>
   			{html_options name=data[id_currency] options=$aCurrencyAssoc selected=$aData.id_currency class="form-control btn-sm"}
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
			<label>{$oLanguage->getDMessage('title')}:{$sZir}</label>
   			<input type=text name=data[title] value="{$aData.title|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('account_id')}:{$sZir}</label>
   			<input type=text name=data[account_id] value="{$aData.account_id|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('holder_name')}:{$sZir}</label>
   			<input type=text name=data[holder_name] value="{$aData.holder_name|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('holder_code')}:{$sZir}</label>
   			<input type=text name=data[holder_code] value="{$aData.holder_code|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('holder_kpp')}:{$sZir}</label>
   			<input type=text name=data[holder_kpp] value="{$aData.holder_kpp|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('bank_name')}:{$sZir}</label>
   			<input type=text name=data[bank_name] value="{$aData.bank_name|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('bank_code')}:{$sZir}</label>
   			<input type=text name=data[bank_code] value="{$aData.bank_code|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('correspondent_account')}:</label>
   			<input type=text name=data[correspondent_account] value="{$aData.correspondent_account|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('bank_mfo')}:{$sZir}</label>
   			<input type=text name=data[bank_mfo] value="{$aData.bank_mfo|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('holder_sign')}:{$sZir}</label>
   			<input type=text name=data[holder_sign] value="{$aData.holder_sign|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Description')}:</label>
   			<textarea name=data[description] class="form-control btn-sm">{$aData.description|escape}</textarea>
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
			<label>{$oLanguage->getDMessage('link_user_account_type_code')}:</label>
   			<input type=text name=data[link_user_account_type_code] value="{$aData.link_user_account_type_code|escape}" />
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('in_use_pko')}:</label>
    		{include file='addon/mpanel/form_checkbox.tpl' sFieldName='in_use_pko' bChecked=$aData.in_use_pko}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('in_use_bv')}:</label>
    		{include file='addon/mpanel/form_checkbox.tpl' sFieldName='in_use_bv' bChecked=$aData.in_use_bv}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('in_use_rko')}:</label>
    		{include file='addon/mpanel/form_checkbox.tpl' sFieldName='in_use_rko' bChecked=$aData.in_use_rko}
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