<div class="row">
	<div class="col-md-7">
		<div class="card">
			<div class="card-header">
                <h3 class="card-title">{$oLanguage->getDMessage('Customer')}</h3>
            </div>
		
<FORM id='main_form' action='javascript:void(null);' onsubmit="submit_form(this)">
<div class="card-body">
<!-- body begin -->

<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Manager')}:</label>
			{html_options name=data[id_manager] options=$aManagerAssoc selected=$aData.id_manager class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Customer Group')}:</label>
   			{html_options name=data[id_customer_group] options=$aCustomerGroupAssoc selected=$aData.id_customer_group class="form-control btn-sm"}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Login')}:{$sZir}</label>
   			<input type=text name=data[login] value='{$aData.login}'  class="form-control btn-sm">
		</div>
	</div>
</div>

{if $aData.password_temp}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Password Temp')}:</label>
			<input type=text name=data[password_temp] value="{$aData.password_temp|escape}"  readonly class="form-control btn-sm">
		</div>
	</div>
</div>
{/if}
{if !$aData.id}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Password')}:{$sZir}</label>
	   		<input type=password name=data[password] value="{$aData.password|escape}" class="form-control btn-sm">
		</div>
	</div>
</div>
{/if}
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Name')}:</label>
   			<input type=text name=data[name] value='{$aData.name}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Discount Static')}:</label>
   			<input type=text name=data[discount_static] value='{$aData.discount_static}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Discount Dynamic (%)')}:</label>
   			<input type=text name=data[discount_dynamic] value='{$aData.discount_dynamic}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('User Debt')}:</label>
   			<input type=text name=data[user_debt] value='{$aData.user_debt}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Country')}:</label>
   			<input type=text name=data[country] value='{$aData.country}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('State')}:</label>
   			<input type=text name=data[state] value='{$aData.state}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('City')}:</label>
   			<input type=text name=data[city] value='{$aData.city}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label> {$oLanguage->getDMessage('Zip')}:</label>
   			<input type=text name=data[zip] value='{$aData.zip}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Company')}:</label>
   			<input type=text name=data[company] value='{$aData.company}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Address')}:</label>
   			<input type=text name=data[address] value='{$aData.address}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Email')}:{$sZir}</label>
   			<input type=text name=data[email] value='{$aData.email}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Phone')}:</label>
   			<input type=text name=data[phone] value='{$aData.phone}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Phone 2')}:</label>
   			<input type=text name=data[phone2] value='{$aData.phone2}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Mobile Phone')}:</label>
   			<input type=text name=data[phone3] value='{$aData.phone3}' class="form-control btn-sm">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Remarks')}:</label>
   			<textarea name=data[remark] rows=3 class="form-control btn-sm">{$aData.remark}</textarea>
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
			<label>{$oLanguage->getDMessage('Approved')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='approved' bChecked=$aData.approved}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Is Test')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_test' bChecked=$aData.is_test}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Receive Notification')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='receive_notification' bChecked=$aData.receive_notification}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Is provider paid')}:</label>
   			{include file='addon/mpanel/form_checkbox.tpl' sFieldName='is_provider_paid' bChecked=$aData.is_provider_paid}
		</div>
	</div>
</div>
<div class="row">
	<div class="col-sm">
	    <div class="form-group">
			<label>{$oLanguage->getDMessage('Ip')}:</label>
   			{$aData.ip}<input type=hidden name=data[ip] value="{$aData.ip|escape}">
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