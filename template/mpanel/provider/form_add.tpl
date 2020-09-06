<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Provider')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Login')}:{$sZir}</label>
   			<input type=text name=data[login] value="{$aData.login|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
{if !$aData.id}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Password')}:{$sZir}</label>
   			<input type=text name=data[password] value="{$aData.password|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
{/if}
{if !$aData.is_public}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Name_Provide')}:</label>
   			<input type=text name=data[name] value="{$aData.name|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
{/if}
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
			<label>{$oLanguage->getDMessage('Code Name')}:</label>
   			<input type=text name=data[code_name] value="{$aData.code_name|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Code Delivery')}:</label>
   			<input type=text name=data[code_delivery] value="{$aData.code_delivery|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Provider Group')}:</label>
   			{html_options name=data[id_provider_group] options=$aProviderGroupList selected=$sProviderGroupSelected class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Provider Region')}:</label>
   			{html_options name=data[id_provider_region] options=$aProviderRegionList selected=$sProviderRegionSelected class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Term delivery')}:</label>
   			<input type=text name=data[term] value="{$aData.term|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Price Currency')}:</label>
   			{html_options name=data[id_currency] options=$aCurrency selected=$aData.id_currency class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Country')}:</label>
   			<input type=text name=data[country] value="{$aData.country|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('State')}:</label>
   			<input type=text name=data[state] value="{$aData.state|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('City')}:</label>
   			<input type=text name=data[city] value="{$aData.city|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Zip')}:</label>
   			<input type=text name=data[zip] value="{$aData.zip|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Company')}:</label>
   			<input type=text name=data[company] value="{$aData.company|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Address')}:</label>
   			<input type=text name=data[address] value="{$aData.address|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
{if !$aData.is_public}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Email')}:</label>
   			<input type=text name=data[email] value="{$aData.email|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Phone')}:</label>
   			<input type=text name=data[phone] value="{$aData.phone|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
{/if}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Phone 2')}:</label>
   			<input type=text name=data[phone2] value="{$aData.phone2|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Mobile Phone')}:</label>
   			<input type=text name=data[phone3] value="{$aData.phone3|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Remarks')}:</label>
   			<textarea name=data[remark] class="form-control btn-sm">{$aData.remark}</textarea>
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
			<label>{$oLanguage->getDMessage('Is Test')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_test' bChecked=$aData.is_test}


<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Is Our Store')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_our_store' bChecked=$aData.is_our_store}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Approved')}:</label>
			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='approved' bChecked=$aData.approved}
		</div>
	</div>
</div>

<!-- body end -->
			</div>
<!-- /.card-body -->
		    <div class="card-footer">
		        <input type=hidden name=data[id] value="{$aData.id|escape}">
				<input type=hidden name=data[type_] value="provider">
				{include file='addon/mpanel/base_add_button.tpl' sBaseAction=$sBaseAction}
		   </div>
		   <!-- /.card-footer -->
		   </form>
		   
		</div>
	</div>
</div>